<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\ApproverSetting;
use App\BmcAccountability;
use App\BmcAccountabilityExtension;
use App\Mail\BmcExtensionRequestMail;
use App\MarketingCollateralBorrowing;
use Illuminate\Http\Request;
use App\BmcAccountabilityReturn;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BmcController extends Controller {
    public function index(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'search' => 'nullable|string|max:255',
            'status' => ['nullable', Rule::in([
                MarketingCollateralBorrowing::STATUS_FOR_APPROVAL,
                MarketingCollateralBorrowing::STATUS_APPROVED,
                MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED,
                MarketingCollateralBorrowing::STATUS_RELEASED,
                MarketingCollateralBorrowing::STATUS_CLOSED,
                MarketingCollateralBorrowing::STATUS_DECLINED,
                MarketingCollateralBorrowing::STATUS_CANCELLED,
            ])],
            'per_page' => 'nullable|integer|min:1|max:50',
        ]);

        $query = $this->borrowerRequests($validated['email'])->with(['borrowedItems', 'accountabilities.items']);
        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }
        if (!empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($query) use ($search) {
                $query->where('reference_no', 'like', '%' . $search . '%')
                    ->orWhere('event_name', 'like', '%' . $search . '%');
            });
        }

        $requests = $query->orderBy('id', 'desc')->paginate($validated['per_page'] ?? 15);

        return response()->json([
            'data' => $requests->items(),
            'meta' => [
                'current_page' => $requests->currentPage(),
                'last_page' => $requests->lastPage(),
                'per_page' => $requests->perPage(),
                'total' => $requests->total(),
            ],
        ]);
    }

    private function borrowerRequests($email) {
        return MarketingCollateralBorrowing::where('email', $email)
            ->whereNull('user_id')
            ->whereIn('borrower_profile', MarketingCollateralBorrowing::NON_PREI_BORROWER_PROFILES);
    }

    public function show(Request $request, $id) {
        $validated = $request->validate(['email' => 'required|email|max:255']);
        $borrowing = $this->borrowerRequests($validated['email'])
            ->with(['borrowedItems', 'accountabilities.items.serials', 'accountabilities.returnHistory.receivedBy:id,name', 'accountabilities.pendingReturns', 'accountabilities.extensions', 'accountabilities.releasedBy:id,name', 'accountabilities.closedBy:id,name'])
            ->findOrFail($id);

        $data = $borrowing->toArray();
        unset($data['proof_of_refund']);
        $data['has_refund_proof'] = (bool) $borrowing->proof_of_refund;
        $data['release_deposit'] = null;
        $data['release_token'] = null;
        if ($borrowing->release_items) {
            $data['release_deposit'] = collect($borrowing->release_items)->sum(function ($item) use ($borrowing) {
                return $borrowing->depositRate($item['item_name']) * (int) $item['quantity'];
            });
            $data['release_token'] = hash('sha256', json_encode($borrowing->release_items));
        }
        $data['accountabilities'] = $borrowing->accountabilities->map(function ($accountability) {
            return $this->accountabilityData($accountability);
        })->all();

        return response()->json(['data' => $data]);
    }

    public function submitPayment(Request $request, $id) {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'payment_method' => ['required', Rule::in(['COD', 'GCash', 'Bank Transfer'])],
            'proof_of_transfer' => 'required_unless:payment_method,COD|nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'release_token' => 'required|string|size:64',
        ]);

        $path = null;
        if ($request->hasFile('proof_of_transfer')) {
            $path = $request->file('proof_of_transfer')->store('bmc_payment_proofs', 'local');
            if (!$path) {
                abort(500, 'The proof of transfer could not be saved.');
            }
        }

        try {
            $oldPath = DB::transaction(function () use ($id, $validated, $path) {
                $borrowing = $this->borrowerRequests($validated['email'])->lockForUpdate()->findOrFail($id);
                if (!in_array($borrowing->status, [
                    MarketingCollateralBorrowing::STATUS_APPROVED,
                    MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED,
                ], true) || !$borrowing->release_items) {
                    abort(409, 'There is no release prepared for payment.');
                }
                $token = hash('sha256', json_encode($borrowing->release_items));
                if (!hash_equals($token, $validated['release_token'])) {
                    abort(409, 'The prepared release has changed. Please review it before submitting payment.');
                }

                $previousPath = $borrowing->proof_of_transfer;
                $borrowing->payment_method = $validated['payment_method'];
                $borrowing->proof_of_transfer = $path;
                $borrowing->save();
                return $previousPath;
            });
        } catch (\Throwable $exception) {
            if ($path) {
                Storage::disk('local')->delete($path);
            }
            throw $exception;
        }

        if ($oldPath && strpos($oldPath, 'bmc_payment_proofs/') === 0) {
            Storage::disk('local')->delete($oldPath);
        }

        return response()->json(['message' => 'Payment details submitted successfully.']);
    }

    public function paymentProof(Request $request, $id) {
        $validated = $request->validate(['email' => 'required|email|max:255']);
        $borrowing = $this->borrowerRequests($validated['email'])->findOrFail($id);
        return $this->paymentProofResponse($borrowing->proof_of_transfer);
    }

    public function refundProof(Request $request, $id) {
        $validated = $request->validate(['email' => 'required|email|max:255']);
        $borrowing = $this->borrowerRequests($validated['email'])->findOrFail($id);
        $path = $borrowing->proof_of_refund;
        abort_unless(is_string($path) && preg_match('#^bmc_refund_proofs/[A-Za-z0-9._-]+$#', $path), 404);
        abort_unless(Storage::disk('local')->exists($path), 404);
        return Storage::disk('local')->response($path);
    }

    public function accountabilityPaymentProof(Request $request, $id) {
        $validated = $request->validate(['email' => 'required|email|max:255']);
        $accountability = $this->borrowerAccountability($validated['email'], $id)->firstOrFail();
        return $this->paymentProofResponse($accountability->proof_of_transfer);
    }

    private function paymentProofResponse($path) {
        abort_unless(is_string($path) && preg_match('#^bmc_payment_proofs/[A-Za-z0-9._-]+$#', $path), 404);
        abort_unless(Storage::disk('local')->exists($path), 404);
        return Storage::disk('local')->response($path);
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'borrower_first_name' => 'sometimes|required|string|max:255',
            'borrower_last_name' => 'sometimes|required|string|max:255',
            'department' => 'sometimes|nullable|string|max:100',
            'business_name' => 'sometimes|nullable|string|max:255',
            'phone_number' => 'sometimes|required|string|regex:/^09[0-9]{9}$/',
            'date_needed' => 'sometimes|required|date_format:Y-m-d',
            'event_name' => 'sometimes|required|string|max:255',
            'request_details' => 'sometimes|required|string',
            'number_of_days_item_needed' => 'sometimes|required|integer|min:1|max:40',
            'borrowed_items' => 'sometimes|required|array|min:1',
            'borrowed_items.*.item_name' => ['required', 'string', 'distinct', Rule::in(MarketingCollateralBorrowing::BORROWABLE_ITEMS)],
            'borrowed_items.*.requested_quantity' => 'required|integer|min:0|max:99',
        ]);

        $items = null;
        if (isset($validated['borrowed_items'])) {
            $items = collect($validated['borrowed_items'])->filter(function ($item) {
                return (int) $item['requested_quantity'] > 0;
            })->map(function ($item) {
                return [
                    'item_name' => $item['item_name'],
                    'requested_quantity' => (int) $item['requested_quantity'],
                ];
            })->values()->all();
            if (empty($items)) {
                throw ValidationException::withMessages(['borrowed_items' => 'Select at least one item.']);
            }
        }

        $borrowing = DB::transaction(function () use ($id, $validated, $items) {
            $borrowing = $this->borrowerRequests($validated['email'])->lockForUpdate()->findOrFail($id);
            if ($borrowing->status !== MarketingCollateralBorrowing::STATUS_FOR_APPROVAL) {
                abort(409, 'Only requests awaiting approval can be edited.');
            }
            $borrowing->fill(Arr::except($validated, ['email', 'borrowed_items']));
            $borrowing->full_name = mb_substr($borrowing->borrower_first_name . ' ' . $borrowing->borrower_last_name, 0, 255);
            $borrowing->save();
            if (isset($items)) {
                $borrowing->borrowedItems()->delete();
                $borrowing->borrowedItems()->createMany($items);
            }

            return $borrowing->load('borrowedItems');
        });

        return response()->json(['data' => $borrowing]);
    }

    public function cancel(Request $request, $id) {
        $validated = $request->validate(['email' => 'required|email|max:255']);
        $borrowing = DB::transaction(function () use ($id, $validated) {
            $borrowing = $this->borrowerRequests($validated['email'])->lockForUpdate()->findOrFail($id);
            if ($borrowing->status !== MarketingCollateralBorrowing::STATUS_FOR_APPROVAL) {
                abort(409, 'Only requests awaiting approval can be cancelled.');
            }
            $borrowing->status = MarketingCollateralBorrowing::STATUS_CANCELLED;
            $borrowing->processed_at = now();
            $borrowing->mbd_remarks = null;
            $borrowing->save();

            return $borrowing;
        });

        return response()->json(['id' => $borrowing->id, 'reference_no' => $borrowing->reference_no, 'status' => $borrowing->status]);
    }

    private function borrowerAccountability($email, $id) {
        return \App\BmcAccountability::where('id', $id)
            ->whereHas('borrowing', function ($query) use ($email) {
                $query->where('email', $email)
                    ->whereNull('user_id')
                    ->whereIn('borrower_profile', MarketingCollateralBorrowing::NON_PREI_BORROWER_PROFILES);
            });
    }

    public function showAccountability(Request $request, $id) {
        $validated = $request->validate(['email' => 'required|email|max:255']);
        $accountability = $this->borrowerAccountability($validated['email'], $id)
            ->with(['borrowing', 'items.serials', 'returnHistory.receivedBy:id,name', 'pendingReturns', 'extensions', 'releasedBy:id,name', 'closedBy:id,name'])
            ->firstOrFail();

        return response()->json(['data' => $this->accountabilityData($accountability)]);
    }

    private function accountabilityData($accountability) {
        $data = $accountability->toArray();
        $data['released_by'] = $accountability->getAttributes()['released_by'] ?? null;
        $data['closed_by'] = $accountability->getAttributes()['closed_by'] ?? null;
        $data['released_by_user'] = $accountability->releasedBy ? $accountability->releasedBy->only(['id', 'name']) : null;
        $data['closed_by_user'] = $accountability->closedBy ? $accountability->closedBy->only(['id', 'name']) : null;
        $data['return_history'] = $accountability->returnHistory->map(function ($return) {
            $entry = $return->toArray();
            $entry['received_by'] = $return->getAttributes()['received_by'] ?? null;
            $entry['received_by_user'] = $return->receivedBy ? $return->receivedBy->only(['id', 'name']) : null;
            return $entry;
        })->all();

        return $data;
    }

    public function requestExtension(Request $request, $id) {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'requested_return_date' => 'required|date_format:Y-m-d',
            'reason' => 'required|string|max:2000',
        ]);

        $extension = DB::transaction(function () use ($id, $validated) {
            $accountability = $this->borrowerAccountability($validated['email'], $id)
                ->lockForUpdate()
                ->firstOrFail();

            $hasOutstandingItems = $accountability->items()
                ->whereColumn('released_quantity', '>', 'returned_quantity')
                ->exists();

            if (!$hasOutstandingItems || !in_array($accountability->status, [
                BmcAccountability::STATUS_RELEASED,
                BmcAccountability::STATUS_PARTIALLY_RETURNED,
            ], true) || !$accountability->expected_return_at || !$accountability->released_at) {
                throw ValidationException::withMessages([
                    'requested_return_date' => 'This release is no longer eligible for an extension.',
                ]);
            }

            $requestedDate = $validated['requested_return_date'];
            $maximumDate = $accountability->released_at->copy()->addDays(40)->toDateString();
            if ($requestedDate <= $accountability->expected_return_at->toDateString() ||
                $requestedDate < today()->toDateString() || $requestedDate > $maximumDate) {
                throw ValidationException::withMessages([
                    'requested_return_date' => 'Choose a date after the current due date, today or later, and within 40 days of release.',
                ]);
            }

            if ($accountability->extensions()->where('status', BmcAccountabilityExtension::STATUS_PENDING)->exists()) {
                throw ValidationException::withMessages([
                    'requested_return_date' => 'An extension request is already pending for this release.',
                ]);
            }

            return $accountability->extensions()->create([
                'current_return_date' => $accountability->expected_return_at->toDateString(),
                'requested_return_date' => $requestedDate,
                'reason' => $validated['reason'],
                'requested_by' => null,
                'status' => BmcAccountabilityExtension::STATUS_PENDING,
            ]);
        });

        $extension->load('accountability.borrowing');
        $recipients = ApproverSetting::with('user')
            ->where('type_of_form', 'bmc')
            ->where('status', 'Active')
            ->get()
            ->pluck('user.email')
            ->filter()
            ->unique();

        foreach ($recipients as $email) {
            try {
                Mail::to($email)->send(new BmcExtensionRequestMail($extension));
            } catch (\Throwable $exception) {
                \Log::error('BMC extension email failed.', [
                    'extension_id' => $extension->id,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return response()->json(['data' => $extension], 201);
    }

    public function saveSignature(Request $request, $id) {
        $identity = $request->validate(['email' => 'required|email|max:255']);
        $accountability = $this->borrowerAccountability($identity['email'], $id)->with('borrowing')->firstOrFail();
        $validated = $request->validate([
            'signature' => 'required|string|max:7000000',
            'borrower_remarks' => 'sometimes|array',
            'borrower_remarks.*' => 'nullable|string|max:2000',
        ]);

        if ($accountability->status !== \App\BmcAccountability::STATUS_RELEASED) {
            return response()->json([
                'message' => 'Only a released accountability can be signed.',
            ], 422);
        }

        if (!preg_match('/^data:image\/png;base64,(.+)$/s', $request->signature, $matches)) {
            return response()->json([
                'message' => 'The submitted signature is invalid.',
            ], 422);
        }

        $decodedSignature = base64_decode($matches[1], true);

        if ($decodedSignature === false || !@getimagesizefromstring($decodedSignature) || substr($decodedSignature, 0, 8) !== "\x89PNG\r\n\x1a\n") {
            return response()->json([
                'message' => 'The submitted signature could not be processed.',
            ], 422);
        }

        $signatureDirectory = public_path('signatures');

        if (!file_exists($signatureDirectory)) {
            mkdir($signatureDirectory, 0755, true);
        }

        $fileName = 'bmc_accountability_' . $accountability->id . '_' .
            time() . '_' . uniqid() . '.png';
        $relativePath = 'signatures/' . $fileName;
        $absolutePath = $signatureDirectory . DIRECTORY_SEPARATOR . $fileName;

        if (file_put_contents($absolutePath, $decodedSignature) === false) {
            return response()->json([
                'message' => 'The signature file could not be saved.',
            ], 500);
        }

        try {
            $oldSignaturePath = \DB::transaction(function () use ($id, $validated, $relativePath, $identity) {
                $accountability = $this->borrowerAccountability($identity['email'], $id)->lockForUpdate()->firstOrFail();
                abort_unless($accountability->status === \App\BmcAccountability::STATUS_RELEASED, 422,
                    'Only a released accountability can be signed.');

                $remarks = $validated['borrower_remarks'] ?? [];
                if ($remarks !== []) {
                    $accountability->load('items.serials');
                    $serials = $accountability->items->flatMap(function ($item) {
                        return $item->serials;
                    })->keyBy('id');

                    foreach ($remarks as $serialId => $remark) {
                        $serial = $serials->get($serialId);
                        abort_unless($serial, 422, 'A remark refers to an item outside this accountability.');
                        $remark = trim($remark ?? '');
                        $serial->borrower_remarks = $remark === '' ? null : $remark;
                        if ($serial->isDirty('borrower_remarks')) {
                            $serial->save();
                        }
                    }
                }

                $oldPath = $accountability->borrower_signature;
                $accountability->borrower_signature = $relativePath;
                $accountability->signed_at = now();
                $accountability->save();

                return $oldPath ? public_path($oldPath) : null;
            });
        } catch (\Throwable $exception) {
            \Illuminate\Support\Facades\File::delete($absolutePath);
            throw $exception;
        }

        if ($oldSignaturePath && is_file($oldSignaturePath)) {
            \Illuminate\Support\Facades\File::delete($oldSignaturePath);
        }

        return response()->json([
            'message' => 'Accountability signed successfully.',
        ]);
    }

    public function requestReturn(Request $request, $id) {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'return_remarks' => 'required|string|max:2000',
            'return_mode' => ['required', 'string', Rule::in(array_keys(BmcAccountabilityReturn::RETURN_MODES))],
            'return_mode_details' => ['required', 'string', 'max:1900', 'not_regex:/[\r\n]/'],
            'attachment' => 'nullable|array|max:3',
            'attachment.*' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'return_serial_ids' => 'nullable|array',
            'return_serial_ids.*' => 'nullable|array',
            'return_serial_ids.*.*' => 'required|integer',
            'return_serial_numbers' => 'nullable|array',
            'return_serial_numbers.*' => 'nullable|array',
            'return_serial_numbers.*.*' => 'required|string|max:255',
        ]);

        $attachmentPaths = [];
        $saveReturn = function () use ($id, $validated, &$attachmentPaths) {
            $accountability = $this->borrowerAccountability($validated['email'], $id)->with([
                'borrowing',
                'items.serials',
                'returnHistory',
            ])
                ->where('id', $id)
                ->lockForUpdate()
                ->first();

            if (!$accountability) {
                return [
                    'success' => false,
                    'message' => 'Accountability record was not found.',
                ];
            }

            if (!$accountability->borrower_signature) {
                return [
                    'success' => false,
                    'message' =>
                        'Sign the accountability before initiating a return.',
                ];
            }

            if (!in_array($accountability->status, [
                \App\BmcAccountability::STATUS_RELEASED,
                \App\BmcAccountability::STATUS_PARTIALLY_RETURNED,
            ], true)) {
                return [
                    'success' => false,
                    'message' =>
                        'This accountability is not available for a return request.',
                ];
            }

            $remainingQuantity = $accountability->items->sum(function ($item) {
                return max(
                    (int) $item->released_quantity -
                    (int) ($item->returned_quantity ?? 0),
                    0
                );
            });

            if ($remainingQuantity < 1) {
                return [
                    'success' => false,
                    'message' => 'All released items have already been returned.',
                ];
            }

            $parseSerialNumbers = function ($value) {
                $stored = json_decode((string) $value, true);
                if (is_array($stored) && isset($stored['numbers']) && is_array($stored['numbers'])) {
                    return collect($stored['numbers'])
                        ->map(function ($serialNumber) {
                            return trim((string) $serialNumber);
                        })
                        ->filter(function ($serialNumber) {
                            return $serialNumber !== '';
                        })
                        ->values();
                }

                return collect(explode(
                    ',',
                    str_replace(["\r", "\n"], ',', (string) $value)
                ))
                    ->map(function ($serialNumber) {
                        return trim($serialNumber);
                    })
                    ->filter(function ($serialNumber) {
                        return $serialNumber !== '';
                    })
                    ->values();
            };

            $submittedSerialIds = collect($validated['return_serial_ids'] ?? [])
                ->mapWithKeys(function ($serialIds, $itemId) {
                    return [
                        (int) $itemId => collect($serialIds)
                            ->map(function ($serialId) {
                                return (int) $serialId;
                            })
                            ->values(),
                    ];
                });
            $submittedSerialNumbers = collect(
                $validated['return_serial_numbers'] ?? []
            )
                ->mapWithKeys(function ($quantity, $itemId) {
                    return [
                        (int) $itemId => collect($quantity)
                            ->map(function ($serialNumber) {
                                return trim((string) $serialNumber);
                            })
                            ->filter(function ($serialNumber) {
                                return $serialNumber !== '';
                            })
                            ->values(),
                    ];
                });

            $accountabilityItemIds = $accountability->items
                ->pluck('id')
                ->map(function ($itemId) {
                    return (int) $itemId;
                });
            $submittedItemIds = $submittedSerialIds
                ->keys()
                ->merge($submittedSerialNumbers->keys())
                ->unique();

            if ($submittedItemIds->diff($accountabilityItemIds)->isNotEmpty()) {
                return [
                    'success' => false,
                    'message' =>
                        'One or more selected serial numbers do not belong to this accountability.',
                ];
            }

            $pendingReturns = [];

            foreach ($accountability->items as $item) {
                $remainingForItem = max(
                    (int) $item->released_quantity -
                    (int) ($item->returned_quantity ?? 0),
                    0
                );

                if ($remainingForItem < 1) {
                    continue;
                }

                $previouslyReturnedSerialNumbers = $accountability->returnHistory
                    ->where('bmc_accountability_item_id', $item->id)
                    ->flatMap(function ($returnEntry) use ($parseSerialNumbers) {
                        return $parseSerialNumbers($returnEntry->serial_numbers);
                    })
                    ->unique()
                    ->values();
                $selectedSerialNumbers = collect();
                $selectedSerialRecordIds = collect();

                if ($item->serials->isNotEmpty()) {
                    $selectedSerialIds = $submittedSerialIds->get(
                        (int) $item->id,
                        collect()
                    );

                    if (
                        $selectedSerialIds->count() !==
                        $selectedSerialIds->unique()->count()
                    ) {
                        return [
                            'success' => false,
                            'message' =>
                                'A serial number cannot be selected more than once.',
                        ];
                    }

                    $availableSerials = $item->serials
                        ->filter(function ($serial) {
                            return $serial->status ===
                                \App\BmcItemSerial::STATUS_AVAILABLE;
                        });
                    $selectedSerialRecords = $item->serials
                        ->whereIn('id', $selectedSerialIds->all());

                    if (
                        $selectedSerialRecords->count() !==
                            $selectedSerialIds->count() ||
                        $selectedSerialRecords
                            ->pluck('id')
                            ->diff($availableSerials->pluck('id'))
                            ->isNotEmpty()
                    ) {
                        return [
                            'success' => false,
                            'message' =>
                                'One or more selected serial numbers are not available for return.',
                        ];
                    }

                    $selectedSerialNumbers = $selectedSerialRecords
                        ->pluck('serial_number')
                        ->values();
                    $selectedSerialRecordIds = $selectedSerialRecords
                        ->pluck('id')
                        ->map(function ($serialId) {
                            return (int) $serialId;
                        })
                        ->values();
                } else {
                    $releasedSerialNumbers = $parseSerialNumbers(
                        $item->serial_number
                    );
                    $availableSerialNumbers = $releasedSerialNumbers
                        ->diff($previouslyReturnedSerialNumbers)
                        ->values();
                    $selectedSerialNumbers = $submittedSerialNumbers->get(
                        (int) $item->id,
                        collect()
                    );

                    if (
                        $selectedSerialNumbers->count() !==
                        $selectedSerialNumbers->unique()->count()
                    ) {
                        return [
                            'success' => false,
                            'message' =>
                                'A serial number cannot be selected more than once.',
                        ];
                    }

                    if (
                        $selectedSerialNumbers
                            ->diff($availableSerialNumbers)
                            ->isNotEmpty()
                    ) {
                        return [
                            'success' => false,
                            'message' =>
                                'One or more selected serial numbers are not available for return.',
                        ];
                    }
                }

                if ($selectedSerialNumbers->count() > $remainingForItem) {
                    return [
                        'success' => false,
                        'message' =>
                            'Selected serial numbers exceed the remaining quantity.',
                    ];
                }

                if ($selectedSerialNumbers->isNotEmpty()) {
                    $pendingReturns[] = [
                        'item_id' => $item->id,
                        'quantity' => $selectedSerialNumbers->count(),
                        'serial_numbers' => json_encode([
                            'ids' => $selectedSerialRecordIds->all(),
                            'numbers' => $selectedSerialNumbers->all(),
                        ]),
                    ];
                }
            }

            if (empty($pendingReturns)) {
                return [
                    'success' => false,
                    'message' =>
                        'Select at least one serial number to return.',
                ];
            }

            $directory = 'bmc_return_proofs';
            if (!empty($validated['attachment']) && !is_dir(public_path($directory))) {
                File::makeDirectory(public_path($directory), 0755, true, true);
            }
            foreach ($validated['attachment'] ?? [] as $photo) {
                $fileName = $photo->hashName();
                $attachmentPaths[] = $directory . '/' . $fileName;
                $photo->move(public_path($directory), $fileName);
            }

            $attachment = $attachmentPaths
                ? json_encode($attachmentPaths, JSON_UNESCAPED_SLASHES)
                : null;

            $returnDetails = trim($validated['return_mode_details'] ?? '');
            $borrowerRemarks = trim($validated['return_remarks']);
            $returnRemarks = ($returnDetails !== '' ? "Details: {$returnDetails}\n" : '')
                . 'Borrower remarks: ' . $borrowerRemarks;

            $accountability->pendingReturns()->delete();
            foreach ($pendingReturns as $pendingReturn) {
                $accountability->pendingReturns()->create([
                    'bmc_accountability_item_id' =>
                        $pendingReturn['item_id'],
                    'returned_quantity' => $pendingReturn['quantity'],
                    'serial_numbers' => $pendingReturn['serial_numbers'],
                    'returned_at' => now(),
                    'return_mode' => $validated['return_mode'],
                    'borrower_remarks' => $returnRemarks,
                    'attachment' => $attachment,
                ]);
            }

            $accountability->closing_remarks = $borrowerRemarks;
            $accountability->status =
                \App\BmcAccountability::STATUS_FOR_RETURN_CONFIRMATION;
            $accountability->save();

            return ['success' => true];
        };

        try {
            $result = \DB::transaction($saveReturn);
        } catch (\Throwable $exception) {
            foreach ($attachmentPaths as $path) {
                File::delete(public_path($path));
            }

            throw $exception;
        }

        if (!$result['success']) {
            return response()->json(['message' => $result['message']], 422);
        }

        return response()->json(['message' => 'Returned items are awaiting confirmation.']);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'borrower_profile' => [
                'required',
                'string',
                Rule::in(MarketingCollateralBorrowing::NON_PREI_BORROWER_PROFILES),
            ],
            'borrower_first_name' => 'required|string|max:255',
            'borrower_last_name' => 'required|string|max:255',
            'department' => 'nullable|string|max:100',
            'business_name' => 'nullable|string|max:255',
            'phone_number' => 'required|string|regex:/^09[0-9]{9}$/',
            'date_needed' => 'required|date_format:Y-m-d|after_or_equal:today',
            'event_name' => 'required|string|max:255',
            'request_details' => 'required|string',
            'number_of_days_item_needed' => 'required|integer|min:1|max:40',
            'borrowed_items' => 'required|array|min:1',
            'borrowed_items.*.item_name' => [
                'required',
                'string',
                'distinct',
                Rule::in(MarketingCollateralBorrowing::BORROWABLE_ITEMS),
            ],
            'borrowed_items.*.requested_quantity' => 'required|integer|min:0|max:99',
        ]);

        $borrowedItems = collect($validated['borrowed_items'])
            ->filter(function ($item) {
                return (int) $item['requested_quantity'] > 0;
            })
            ->map(function ($item) {
                return [
                    'item_name' => $item['item_name'],
                    'requested_quantity' => (int) $item['requested_quantity'],
                ];
            })
            ->values();

        if ($borrowedItems->isEmpty()) {
            throw ValidationException::withMessages([
                'borrowed_items' => 'Select at least one item.',
            ]);
        }

        $borrowing = DB::transaction(function () use ($validated, $borrowedItems) {
            $latestId = MarketingCollateralBorrowing::query()
                ->orderByDesc('id')
                ->lockForUpdate()
                ->value('id');
            $nextId = (int) $latestId + 1;

            $borrowing = new MarketingCollateralBorrowing(Arr::except($validated, ['borrowed_items']));
            $borrowing->reference_no = 'BMC - ' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            $borrowing->status = MarketingCollateralBorrowing::STATUS_FOR_APPROVAL;
            $borrowing->full_name = mb_substr(
                $validated['borrower_first_name'] . ' ' . $validated['borrower_last_name'],
                0,
                255
            );

            $borrowing->save();
            $borrowing->borrowedItems()->createMany($borrowedItems->all());

            return $borrowing;
        });

        return response()->json([
            'id' => $borrowing->id,
            'reference_no' => $borrowing->reference_no,
            'status' => $borrowing->status,
        ], 201);
    }
}

