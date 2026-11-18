<?php

namespace App\Http\Controllers;

use App\ApproverSetting;
use App\BmcAccountability;
use App\BmcAccountabilityExtension;
use App\BmcAccountabilityReturn;
use App\MarketingCollateralBorrowing;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use App\Mail\BmcMail;
use App\Mail\BmcExtensionRequestMail;

// approver: irene.dura@pascalresources.com.ph

class BmcController extends Controller {
    public function index(Request $request) {
        $status = $request->input('status', '');
        $return_status = $request->input('return_status', '');

        $query = MarketingCollateralBorrowing::with([
            'borrowedItems',
            'accountabilities.items',
        ])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc');

        if ($status === 'Declined / Cancelled') {
            $query->whereIn('status', [
                MarketingCollateralBorrowing::STATUS_DECLINED,
                MarketingCollateralBorrowing::STATUS_CANCELLED,
            ]);
        } elseif ($status !== '') {
            $query->where('status', $status);
        }

        if ($return_status === \App\BmcAccountability::STATUS_PARTIALLY_RETURNED) {
            $query->whereHas('accountabilities', function ($accountabilityQuery) use ($return_status) {
                $accountabilityQuery->where('status', $return_status);
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('borrower_first_name', 'like', "%{$search}%")
                  ->orWhere('borrower_last_name', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%")
                  ->orWhere('event_name', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(20)->appends($request->query());

        $from = $request->from;
        $to = $request->to;
        $search = $request->search;
        $total_requests = MarketingCollateralBorrowing::where('user_id', auth()->id())->count();
        $approved_count = MarketingCollateralBorrowing::where('user_id', auth()->id())->where('status', 'Approved')->count();
        $cancelled_count = MarketingCollateralBorrowing::where('user_id', auth()->id())->where('status', 'Cancelled')->count();
        $declined_count = MarketingCollateralBorrowing::where('user_id', auth()->id())->where('status', 'Declined')->count();
        $for_approval_count = MarketingCollateralBorrowing::where('user_id', auth()->id())->where('status', 'For Approval')->count();
        $released_count = MarketingCollateralBorrowing::where('user_id', auth()->id())->where('status', 'Released')->count();
        $partial_release_count = MarketingCollateralBorrowing::where('user_id', auth()->id())->where('status', MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED)->count();
        $closed_count = MarketingCollateralBorrowing::where('user_id', auth()->id())->where('status', 'Closed')->count();
        $partial_return_count = MarketingCollateralBorrowing::where('user_id', auth()->id())
            ->whereHas('accountabilities', function ($accountabilityQuery) {
                $accountabilityQuery->where(
                    'status',
                    \App\BmcAccountability::STATUS_PARTIALLY_RETURNED
                );
            })
            ->count();

        return view('forms.bmc.index', compact('requests', 
            'search', 
            'status', 
            'from', 
            'to', 
            'total_requests', 
            'approved_count', 
            'cancelled_count', 
            'declined_count', 
            'for_approval_count',
            'released_count',
            'partial_release_count',
            'closed_count',
            'return_status',
            'partial_return_count') + ['header' => 'forms']);
    }

    private function authenticatedBorrowerData() {
        $user = auth()->user();
        $employee = $user ? $user->employee : null;
        $employeeDepartment = $employee
            ? optional($employee->department)->name
            : null;
        $departmentWords = preg_split(
            '/[^A-Za-z0-9]+/',
            (string) $employeeDepartment,
            -1,
            PREG_SPLIT_NO_EMPTY
        );
        $departmentAcronym = collect($departmentWords)
            ->reject(function ($word) {
                return in_array(
                    strtolower($word),
                    ['and', 'of', 'the'],
                    true
                );
            })
            ->map(function ($word) {
                return strtoupper(substr($word, 0, 1));
            })
            ->implode('');

        $department = collect(MarketingCollateralBorrowing::DEPARTMENTS)
            ->first(function ($department) use (
                $employeeDepartment,
                $departmentAcronym
            ) {
                if (strcasecmp($department, (string) $employeeDepartment) === 0) {
                    return true;
                }

                preg_match('/\(([A-Z]+)\)$/', $department, $matches);

                return isset($matches[1]) &&
                    $matches[1] === $departmentAcronym;
            });

        return [
            'borrower_first_name' => $employee->first_name ?? null,
            'borrower_last_name' => $employee->last_name ?? null,
            'department' => $department,
            'designation' => $employee->position ?? null,
            'email' => $user->email ?? null,
            'phone_number' => $employee->personal_number ?? null,
        ];
    }

    private function validatedAuthenticatedBorrowerData() {
        return validator(
            $this->authenticatedBorrowerData(),
            [
                'borrower_first_name' => 'required|string|max:255',
                'borrower_last_name' => 'required|string|max:255',
                'department' => 'required|in:' . implode(
                    ',',
                    MarketingCollateralBorrowing::DEPARTMENTS
                ),
                'designation' => 'required|string|max:100',
                'email' => 'required|email|max:255',
                'phone_number' =>
                    'required|string|regex:/^09[0-9]{9}$/',
            ],
            [
                'department.required' =>
                    'Your employee department is not configured for this form.',
                'department.in' =>
                    'Your employee department is not configured for this form.',
                'phone_number.regex' =>
                    'Your employee phone number must be a valid 11-digit mobile number.',
            ]
        )->validate();
    }

    public function create() {
        return view('forms.bmc.create', [
            'borrowerData' => $this->authenticatedBorrowerData(),
            'header' => 'forms',
        ]);
    }

    private function selectedBorrowedItems(array $items) {
        $selectedItems = collect($items)
            ->map(function ($item) {
                return [
                    'item_name' => trim($item['item_name']),
                    'requested_quantity' =>
                        (int) $item['requested_quantity'],
                ];
            })
            ->filter(function ($item) {
                return $item['requested_quantity'] > 0;
            })
            ->values();

        if ($selectedItems->isEmpty()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'borrowed_items' =>
                    'Please select at least one item to borrow.',
            ]);
        }

        return $selectedItems;
    }

    public function store(Request $request) {
        $validated = $request->validate([
            // event details
            'date_needed' => 'required|date|after_or_equal:today',
            'event_name' => 'required|string|max:255',
            'request_details' => 'required|string',
            'number_of_days_item_needed' => 'required|integer|min:1|max:40',

            // item for borrowing
            'borrowed_items' => 'required|array|min:1',
            'borrowed_items.*.item_name' => 'required|string|distinct|in:' .
            implode(',', MarketingCollateralBorrowing::BORROWABLE_ITEMS),
            'borrowed_items.*.requested_quantity' => 'required|integer|min:0|max:99',
        ]);

        $borrowedItems = $this->selectedBorrowedItems(
            $validated['borrowed_items']
        );
        $borrowerData = $this->validatedAuthenticatedBorrowerData();

        unset($validated['borrowed_items']);

        $nextId = (int) MarketingCollateralBorrowing::max('id') + 1;

        $borrowing = \DB::transaction(function () use (
            $validated,
            $borrowedItems,
            $borrowerData,
            $nextId
        ) {
            $borrowing = MarketingCollateralBorrowing::create(
                array_merge($validated, $borrowerData, [
                    'borrower_profile' => 'PREI Support Group',
                    'location' => auth()->user()->employee->location,
                    'reference_no' => 'BMC - ' .
                    str_pad($nextId, 6, '0', STR_PAD_LEFT),
                    'user_id' => auth()->id(),
                ])
            );

            $borrowing->borrowedItems()->createMany(
                $borrowedItems->all()
            );

            return $borrowing;
        });

        $approvers = \App\ApproverSetting::where('type_of_form', 'bmc')
            ->where('status', 'Active')
            ->get();

        foreach ($approvers as $approver) {
            if (!$approver->user || !$approver->user->email) continue;

            try {
                Mail::to($approver->user->email)
                    ->send(new BmcMail($borrowing, 'submitted'));
            } catch (\Exception $e) {
                \Log::error('BMC submitted email failed (approver ' . $approver->user_id . '): ' . $e->getMessage());
            }
        }

        Alert::success('Borrowing request submitted successfully')->persistent('Dismiss');
        return redirect()->route('bmc.show', $borrowing->id);
    }

    public function edit($id) {
        $borrowing = MarketingCollateralBorrowing::with('borrowedItems')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (
            $borrowing->status !==
            MarketingCollateralBorrowing::STATUS_FOR_APPROVAL
        ) {
            Alert::error(
                'Only requests awaiting approval can be edited.'
            )->persistent('Dismiss');

            return redirect()->route('bmc.show', $borrowing->id);
        }

        return view('forms.bmc.edit', [
            'borrowing' => $borrowing,
            'borrowerData' => $this->authenticatedBorrowerData(),
            'header' => 'forms',
        ]);
    }

    public function update(Request $request, $id) {
        $rules = [
            'date_needed' => 'required|date',
            'event_name' => 'required|string|max:255',
            'request_details' => 'required|string',
            'number_of_days_item_needed' => 'required|integer|min:1|max:40',

            'borrowed_items' => 'required|array|min:1',
            'borrowed_items.*.item_name' =>
                'required|string|distinct|in:' . implode(
                    ',',
                    MarketingCollateralBorrowing::BORROWABLE_ITEMS
                ),
            'borrowed_items.*.requested_quantity' =>
                'required|integer|min:0|max:99',
        ];

        $validated = $request->validate($rules);
        $borrowedItems = $this->selectedBorrowedItems(
            $validated['borrowed_items']
        );
        $borrowerData = $this->validatedAuthenticatedBorrowerData();

        $updated = \DB::transaction(function () use (
            $id,
            $validated,
            $borrowedItems,
            $borrowerData
        ) {
            $borrowing = MarketingCollateralBorrowing::where('id', $id)
                ->where('user_id', auth()->id())
                ->lockForUpdate()
                ->firstOrFail();

            // Check again in case the approver processed it
            // while the borrower had the edit form open.
            if (
                $borrowing->status !==
                MarketingCollateralBorrowing::STATUS_FOR_APPROVAL
            ) {
                return false;
            }

            unset($validated['borrowed_items']);

            $validated = array_merge($validated, $borrowerData, [
                'borrower_profile' => 'PREI Support Group',
            ]);

            // Clear legacy fields that are no longer part of the PREI form.
            $validated['business_name'] = null;
            $validated['hub_code'] = null;
            $validated['hub_name'] = null;

            $borrowing->update($validated);

            $borrowing->borrowedItems()->delete();
            $borrowing->borrowedItems()->createMany(
                $borrowedItems->all()
            );

            return true;
        });

        if (!$updated) {
            Alert::error(
                'This request can no longer be edited because its status has changed.'
            )->persistent('Dismiss');

            return redirect()->route('bmc.show', $id);
        }

        Alert::success(
            'Borrowing request updated successfully.'
        )->persistent('Dismiss');

        return redirect()->route('bmc.show', $id);
    }

    public function show($id) {
        $allowedPeople = [
            'irene.dura@pascalresources.com.ph',
        ];

        $userEmail = User::where('id', auth()->id())->value('email');
        $employee = auth()->user()->employee;
        $isAllowed = $employee
            && $employee->status === 'Active'
            && in_array($userEmail, $allowedPeople);

        $borrowing = MarketingCollateralBorrowing::with([
            'borrowedItems',
            'accountabilities.items.serials',
            'accountabilities.extensions',
            'accountabilities.returnHistory.item',
            'accountabilities.returnHistory.receivedBy',
            'accountabilities.releasedBy',
            'accountabilities.closedBy',
        ])->findOrFail($id);

        if (!$isAllowed) { 
            $ownerIds = User::where('email', $userEmail)->pluck('id');
            abort_if(!$ownerIds->contains($borrowing->user_id), 403);
        }

        return view('forms.bmc.show', compact('borrowing') + ['header' => 'forms']);
    }

    public function requestAccountabilityExtension(Request $request, $id) {
        $validated = $request->validate([
            'requested_return_date' => 'required|date_format:Y-m-d',
            'reason' => 'required|string|max:2000',
        ]);

        $extension = \DB::transaction(function () use ($id, $validated) {
            $accountability = BmcAccountability::with('borrowing')
                ->lockForUpdate()
                ->findOrFail($id);

            abort_unless($accountability->borrowing &&
                (int) $accountability->borrowing->user_id === (int) auth()->id(), 403);

            $hasOutstandingItems = $accountability->items()
                ->whereColumn('released_quantity', '>', 'returned_quantity')
                ->exists();

            if (!$hasOutstandingItems || !in_array($accountability->status, [
                BmcAccountability::STATUS_RELEASED,
                BmcAccountability::STATUS_PARTIALLY_RETURNED,
            ], true) || !$accountability->expected_return_at || !$accountability->released_at) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'requested_return_date' => 'This release is no longer eligible for an extension.',
                ]);
            }

            $requestedDate = $validated['requested_return_date'];
            $maximumDate = $accountability->released_at->copy()->addDays(40)->toDateString();

            if ($requestedDate <= $accountability->expected_return_at->toDateString() ||
                $requestedDate < today()->toDateString() || $requestedDate > $maximumDate) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'requested_return_date' => 'Choose a date after the current due date, today or later, and within 40 days of release.',
                ]);
            }

            if ($accountability->extensions()
                ->where('status', BmcAccountabilityExtension::STATUS_PENDING)
                ->exists()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'requested_return_date' => 'An extension request is already pending for this release.',
                ]);
            }

            return $accountability->extensions()->create([
                'current_return_date' => $accountability->expected_return_at->toDateString(),
                'requested_return_date' => $requestedDate,
                'reason' => $validated['reason'],
                'requested_by' => auth()->id(),
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

        Alert::success('Extension request submitted.')->persistent('Dismiss');

        return back();
    }

    public function previewAccountability($id) {
        $accountability = \App\BmcAccountability::with([
            'borrowing',
            'items.serials',
            'releasedBy',
        ])->findOrFail($id);

        $allowedPeople = [
            'irene.dura@pascalresources.com.ph',
        ];

        $userEmail = User::where('id', auth()->id())->value('email');
        $employee = auth()->user()->employee;
        $isAllowed = $employee
            && $employee->status === 'Active'
            && in_array($userEmail, $allowedPeople);

        if (!$isAllowed) {
            $ownerIds = User::where('email', $userEmail)->pluck('id');
            abort_if(
                !$ownerIds->contains($accountability->borrowing->user_id),
                403
            );
        }

        $view = in_array(
            $accountability->borrowing->borrower_profile,
            MarketingCollateralBorrowing::NON_PREI_BORROWER_PROFILES,
            true
        ) ? 'for-approval.partner_accountability_bmc' : 'for-approval.prei_accountability_bmc';

        return view($view, [
            'accountability' => $accountability,
            'borrowing' => $accountability->borrowing,
            'showActions' => false,
        ]);
    }

    public function saveBorrowerSignature(Request $request, $id) {
        $accountability = \App\BmcAccountability::with('borrowing')
            ->findOrFail($id);

        if (
            !$accountability->borrowing ||
            (int) $accountability->borrowing->user_id !== (int) auth()->id()
        ) {
            return response()->json([
                'message' => 'You do not have permission to sign this accountability.',
            ], 403);
        }

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

        if ($decodedSignature === false) {
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
            $oldSignaturePath = \DB::transaction(function () use ($id, $validated, $relativePath) {
                $accountability = \App\BmcAccountability::lockForUpdate()->findOrFail($id);
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

    public function requestAccountabilityReturn(Request $request, $id) {
        $validated = $request->validate([
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
            $accountability = \App\BmcAccountability::with([
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

            abort_if(
                !$accountability->borrowing ||
                (int) $accountability->borrowing->user_id !==
                    (int) auth()->id(),
                403
            );

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
            Alert::error($result['message'])->persistent('Dismiss');
            return back()->withInput();
        }

        Alert::success(
            "Returned items are awaiting for confirmation"
        )->persistent('Dismiss');

        return back();
    }

    public function cancel($id) {
        $borrowing = MarketingCollateralBorrowing::findOrFail($id);

        $userEmail = User::where('id', auth()->id())->value('email');
        $owner = User::where('email', $userEmail)->pluck('id');

        abort_if(!$owner->contains($borrowing->user_id), 403);

        if ($borrowing->status !== 'For Approval') {
            Alert::error('Only requests awaiting approval can be cancelled')->persistent('Dismiss');
            return back();
        }

        $borrowing->status = MarketingCollateralBorrowing::STATUS_CANCELLED;
        $borrowing->processed_by = auth()->id();
        $borrowing->processed_at = now();
        $borrowing->mbd_remarks = null;
        $borrowing->save();

        Alert::success('Borrowing request has been cancelled')->persistent('Dismiss');
        return redirect()->route('bmc.index');
    }
}
