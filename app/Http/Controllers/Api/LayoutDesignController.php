<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\LayoutDesign;
use App\LayoutDesignAttachment;
use App\Mail\LayoutDesignMail;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;


// API for DMS

class LayoutDesignController extends Controller {
    public function index(Request $request) {
        $validated = $request->validate([
            'requestor_email' => 'required|email|max:255',
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:Pending,Approved,Processed,Declined,Cancelled',
            'per_page' => 'nullable|integer|min:1|max:50',
        ]);

        $query = LayoutDesign::query()->where('requestor_email', $validated['requestor_email']);

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['search'])) {
            $search = $validated['search'];

            $query->where(function ($query) use ($search) {
                $query->where('reference_no', 'like', "%{$search}%")
                      ->orWhere('requestor_first_name', 'like', "%{$search}%")
                      ->orWhere('requestor_last_name', 'like', "%{$search}%")
                      ->orWhere('marketing_collateral_type', 'like', "%{$search}%");
            });
        }

        $requests = $query
            ->orderBy('submitted_at', 'desc')
            ->paginate($validated['per_page'] ?? 15);

        $statusCounts = \Illuminate\Support\Facades\DB::table('layout_design_requests')
            ->where('requestor_email', $validated['requestor_email'])
            ->select('status', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $data = collect($requests->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'reference_no' => $item->reference_no,
                'requestor_first_name' => $item->requestor_first_name,
                'requestor_last_name' => $item->requestor_last_name,
                'requestor_email' => $item->requestor_email,
                'department' => $item->department,
                'medium_type' => $item->medium_type,
                'marketing_collateral_type' => $item->marketing_collateral_type,
                'date_needed' => optional($item->date_needed)->toIso8601String(),
                'priority_level' => $item->priority_level,
                'due_date' => optional($item->due_date)->toIso8601String(),
                'submitted_at' => optional($item->submitted_at)->toIso8601String(),
                'status' => $item->status,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $requests->currentPage(),
                'last_page' => $requests->lastPage(),
                'per_page' => $requests->perPage(),
                'total' => $requests->total(),
                'status_counts' => [
                    'pending' => (int) $statusCounts->get(LayoutDesign::STATUS_PENDING, 0),
                    'approved' => (int) $statusCounts->get(LayoutDesign::STATUS_APPROVED, 0),
                    'processed' => (int) $statusCounts->get(LayoutDesign::STATUS_PROCESSED, 0),
                    'declined' => (int) $statusCounts->get(LayoutDesign::STATUS_DECLINED, 0),
                    'cancelled' => (int) $statusCounts->get(LayoutDesign::STATUS_CANCELLED, 0),
                ],
            ],
        ]);
    }

    public function store(Request $request) {
        $selectedCollateralTypes = array_values(array_unique(
            (array) $request->input('marketing_collateral_type', [])
        ));
        $externalCollateralTypes = array_merge(
            LayoutDesign::COLLATERAL_TYPES,
            ['Store Signage']
        );
        $storeSignageSelected = in_array(
            'Store Signage',
            $selectedCollateralTypes,
            true
        );
        $storeSignageRules = $storeSignageSelected
            ? ['required', Rule::in(LayoutDesign::STORE_SIGNAGE_OPTIONS)]
            : ['nullable', Rule::in(LayoutDesign::STORE_SIGNAGE_OPTIONS)];
        $storeSignageDetailRules = in_array(
            $request->input('store_signage_option'),
            LayoutDesign::STORE_SIGNAGE_OPTIONS,
            true
        ) ? 'required|string' : 'nullable|string';

        $validated = $request->validate([
            'requestor_first_name' => 'required|string|max:255',
            'requestor_last_name' => 'required|string|max:255',
            'department' => 'required|in:Provincial Distributor (PD),Area Distributor (AD),Mega Dealer,Dealer',
            'requestor_email' => 'required|email|max:255',
            'intended_use' => 'required|array|min:1',
            'intended_use.*' => 'in:' . implode(',', LayoutDesign::INTENDED_USES),
            'medium_type' => 'required|in:' . implode(',', LayoutDesign::MEDIUM_TYPES),
            'marketing_collateral_type' => 'required|array|min:1',
            'marketing_collateral_type.*' => Rule::in($externalCollateralTypes),
            'store_signage_option' => $storeSignageRules,
            'business_name' => $storeSignageDetailRules . '|max:255',
            'ad_territory' => $storeSignageDetailRules . '|max:255',
            'business_address' => $storeSignageDetailRules . '|max:255',
            'contact_number' => $storeSignageDetailRules . '|max:11',
            'purpose_of_layout_request' => 'required|string',
            'project_description' => 'required|string',
            'peg_samples' => ['nullable', 'array', 'max:6'],
            'peg_samples.*' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,mp4,pdf,doc,docx,ppt,pptx',
                'max:30720',
            ],
            'peg_sample_link' => [
                'nullable',
                'url',
                'max:1000',
                'regex:/^https?:\/\//i',
            ],
            // Backward compatibility for clients using the original single-file field.
            'peg_sample' => [
                'nullable',
                'mimes:jpg,jpeg,png,webp,mp4,pdf,doc,docx,ppt,pptx',
                'max:30720',
            ],
            'date_requested' => 'nullable|date',
            'date_needed' => 'nullable|date_format:Y-m-d',
        ], [
            'store_signage_option.required' => 'Select a Store Signage option.',
            'business_name.required' => 'Enter the business name for the Store Signage.',
            'ad_territory.required' => 'Enter the AD territory for the Store Signage.',
            'business_address.required' => 'Enter the business address for the Store Signage.',
            'contact_number.required' => 'Enter the contact number for the Store Signage.',
            'peg_samples.max' => 'You may upload up to 6 peg samples.',
            'peg_samples.*.mimes' => 'Each peg sample must be a JPG, JPEG, PNG, WebP, MP4, PDF, Word, or PowerPoint file.',
            'peg_samples.*.max' => 'Each peg sample must not exceed 30 MB.',
            'peg_sample.mimes' => 'The peg sample must be a JPG, JPEG, PNG, WebP, MP4, PDF, Word, or PowerPoint file.',
            'peg_sample.max' => 'The peg sample must not exceed 30 MB.',
            'peg_sample_link.url' => 'Enter a valid peg sample link.',
            'peg_sample_link.max' => 'The peg sample link must not exceed 1000 characters.',
        ]);

        if ($storeSignageSelected) {
            $selectedCollateralTypes[] = $validated['store_signage_option'];
        }

        $validated['marketing_collateral_type'] = array_values(
            array_unique($selectedCollateralTypes)
        );

        $pegSampleFiles = $request->file('peg_samples', []);
        if ($request->hasFile('peg_sample')) {
            $pegSampleFiles[] = $request->file('peg_sample');
        }

        $nextId = (int) LayoutDesign::max('id') + 1;
        unset($validated['peg_sample']);
        unset($validated['peg_samples']);
        unset($validated['peg_sample_link']);
        unset($validated['store_signage_option']);

        $layoutDesign = LayoutDesign::create(array_merge($validated, [
            'peg_sample_path' => null,
            'date_requested' => $validated['date_requested'] ?? now(),
            'date_needed' => $request->filled('date_needed')
                ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->date_needed)
                : null,
            'reference_no' => 'LDR -' . str_pad($nextId, 6, '0', STR_PAD_LEFT),
            'user_id' => null,
            'status' => LayoutDesign::STATUS_PENDING,
        ]));

        $storedPegSamplePaths = [];

        try {
            $destDir = public_path('peg_samples');

            if (!empty($pegSampleFiles) && !file_exists($destDir)) {
                mkdir($destDir, 0755, true);
            }

            foreach ($pegSampleFiles as $index => $file) {
                $originalName = $file->getClientOriginalName();
                $mimeType = $file->getClientMimeType();
                $fileSize = $file->getSize();
                $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
                $fileName = time() . '_' . $layoutDesign->id . '_' . ($index + 1) . '_' . uniqid() . '_' . $safeName;
                $storedPath = '/peg_samples/' . $fileName;

                $file->move($destDir, $fileName);
                $storedPegSamplePaths[] = $storedPath;

                $layoutDesign->pegSamples()->create([
                    'output_attachment_path' => $storedPath,
                    'original_name' => $originalName,
                    'mime_type' => $mimeType,
                    'file_size' => $fileSize,
                ]);
            }

            if ($request->filled('peg_sample_link')) {
                $layoutDesign->pegSamples()->create([
                    'output_attachment_path' => $request->input('peg_sample_link'),
                    'original_name' => 'Shared peg sample link',
                    'mime_type' => LayoutDesignAttachment::PEG_SAMPLE_LINK_MIME_TYPE,
                    'file_size' => null,
                ]);
            }

            if (!empty($storedPegSamplePaths)) {
                $layoutDesign->peg_sample_path = $storedPegSamplePaths[0];
                $layoutDesign->save();
            }
        } catch (\Throwable $e) {
            foreach ($storedPegSamplePaths as $storedPath) {
                \Illuminate\Support\Facades\File::delete(
                    public_path('peg_samples/' . basename($storedPath))
                );
            }

            $layoutDesign->pegSamples()->delete();
            $layoutDesign->delete();

            throw $e;
        }

        $approvers = \App\ApproverSetting::where('type_of_form', 'ldr')
            ->where('status', 'Active')
            ->get();

        foreach ($approvers as $approver) {
            if (!$approver->user || !$approver->user->email) continue;

            try {
                Mail::to($approver->user->email)
                    ->send(new LayoutDesignMail($layoutDesign, 'submitted'));
            } catch (\Exception $e) {
                \Log::error('LDR public submit email failed (approver ' . $approver->user_id . '): ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Layout design request submitted.',
            'data' => [
                'id' => $layoutDesign->id,
                'reference_no' => $layoutDesign->reference_no,
                'status' => $layoutDesign->status,
                'date_requested' => optional($layoutDesign->date_requested)->toIso8601String(),
                'date_needed' => optional($layoutDesign->date_needed)->toIso8601String(),
            ],
        ], 201);
    }

    public function update(Request $request, $id) {
        $selectedCollateralTypes = array_values(array_unique(
            (array) $request->input('marketing_collateral_type', [])
        ));
        $externalCollateralTypes = array_merge(
            LayoutDesign::COLLATERAL_TYPES,
            ['Store Signage']
        );
        $storeSignageSelected = in_array(
            'Store Signage',
            $selectedCollateralTypes,
            true
        );
        $storeSignageRules = $storeSignageSelected
            ? ['required', Rule::in(LayoutDesign::STORE_SIGNAGE_OPTIONS)]
            : ['nullable', Rule::in(LayoutDesign::STORE_SIGNAGE_OPTIONS)];
        $storeSignageDetailRules = in_array(
            $request->input('store_signage_option'),
            LayoutDesign::STORE_SIGNAGE_OPTIONS,
            true
        ) ? 'required|string' : 'nullable|string';

        $validated = $request->validate([
            'requestor_email' => 'required|email|max:255',
            'requestor_first_name' => 'required|string|max:255',
            'requestor_last_name' => 'required|string|max:255',
            'department' => 'required|in:Provincial Distributor (PD),Area Distributor (AD),Mega Dealer,Dealer',
            'intended_use' => 'required|array|min:1',
            'intended_use.*' => 'in:' . implode(',', LayoutDesign::INTENDED_USES),
            'medium_type' => 'required|in:' . implode(',', LayoutDesign::MEDIUM_TYPES),
            'marketing_collateral_type' => 'required|array|min:1',
            'marketing_collateral_type.*' => Rule::in($externalCollateralTypes),
            'store_signage_option' => $storeSignageRules,
            'business_name' => $storeSignageDetailRules . '|max:255',
            'ad_territory' => $storeSignageDetailRules . '|max:255',
            'business_address' => $storeSignageDetailRules . '|max:255',
            'contact_number' => $storeSignageDetailRules . '|max:11',
            'purpose_of_layout_request' => 'required|string',
            'project_description' => 'required|string',
            'peg_sample_replacements' => ['nullable', 'array'],
            'peg_sample_replacements.*' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,mp4,pdf,doc,docx,ppt,pptx',
                'max:30720',
            ],
            'remove_peg_samples' => ['nullable', 'array'],
            'remove_peg_samples.*' => ['integer'],
            'remove_legacy_peg_sample' => ['nullable', 'boolean'],
            'peg_sample_link' => [
                'nullable',
                'url',
                'max:1000',
                'regex:/^https?:\/\//i',
            ],
            'peg_sample' => [
                'nullable',
                'mimes:jpg,jpeg,png,webp,mp4,pdf,doc,docx,ppt,pptx',
                'max:30720',
            ],
            'date_needed' => 'nullable|date_format:Y-m-d',
        ], [
            'store_signage_option.required' => 'Select a Store Signage option.',
            'business_name.required' => 'Enter the business name for the Store Signage.',
            'ad_territory.required' => 'Enter the AD territory for the Store Signage.',
            'business_address.required' => 'Enter the business address for the Store Signage.',
            'contact_number.required' => 'Enter the contact number for the Store Signage.',
            'peg_sample_replacements.*.mimes' => 'Each replacement must be a JPG, JPEG, PNG, WebP, MP4, PDF, Word, or PowerPoint file.',
            'peg_sample_replacements.*.max' => 'Each replacement must not exceed 30 MB.',
            'peg_sample.mimes' => 'The peg sample must be a JPG, JPEG, PNG, WebP, MP4, PDF, Word, or PowerPoint file.',
            'peg_sample.max' => 'The peg sample must not exceed 30 MB.',
            'peg_sample_link.url' => 'Enter a valid peg sample link.',
            'peg_sample_link.max' => 'The peg sample link must not exceed 1000 characters.',
        ]);

        $layoutDesign = LayoutDesign::where('id', $id)
            ->where('requestor_email', $validated['requestor_email'])
            ->firstOrFail();

        if ($layoutDesign->status !== LayoutDesign::STATUS_PENDING) {
            return response()->json([
                'success' => false,
                'message' => 'Only pending requests can be edited.',
            ], 409);
        }

        // Email is used for ownership checking, not modification.
        unset($validated['requestor_email']);

        if ($storeSignageSelected) {
            $selectedCollateralTypes[] = $validated['store_signage_option'];
        }

        $validated['marketing_collateral_type'] = array_values(
            array_unique($selectedCollateralTypes)
        );
        unset($validated['store_signage_option']);

        $oldPegSamplePath = $layoutDesign->peg_sample_path;
        $existingPegSamples = $layoutDesign->pegSamples()->get()->keyBy('id');
        $existingFilePegSamples = $existingPegSamples->reject(function ($attachment) {
            return $attachment->isExternalLink();
        });
        $existingPegSampleLink = $existingPegSamples->first(function ($attachment) {
            return $attachment->mime_type === LayoutDesignAttachment::PEG_SAMPLE_LINK_MIME_TYPE;
        });
        $pegSampleLinkWasSubmitted = $request->exists('peg_sample_link');
        $pegSampleLink = trim((string) ($validated['peg_sample_link'] ?? ''));
        unset($validated['peg_sample_link']);
        $replacementFiles = $request->file('peg_sample_replacements', []);
        $removePegSampleIds = collect($request->input('remove_peg_samples', []))
            ->map(function ($attachmentId) {
                return (int) $attachmentId;
            })
            ->unique()
            ->values();

        if ($pegSampleLinkWasSubmitted && $existingPegSampleLink && $pegSampleLink === '') {
            $removePegSampleIds->push((int) $existingPegSampleLink->id);
            $removePegSampleIds = $removePegSampleIds->unique()->values();
        }

        $removeLegacyPegSample = (string) $request->input('remove_legacy_peg_sample') === '1';
        $pendingReplacements = [];
        $pendingPegSampleLinkUpdate = null;
        $newPegSampleLinkDetails = null;
        $newStoredPaths = [];
        $oldPathsToDelete = [];
        $legacyPegSampleDetails = null;

        if ($pegSampleLinkWasSubmitted && $pegSampleLink !== '') {
            $linkDetails = [
                'output_attachment_path' => $pegSampleLink,
                'original_name' => 'Shared peg sample link',
                'mime_type' => LayoutDesignAttachment::PEG_SAMPLE_LINK_MIME_TYPE,
                'file_size' => null,
            ];

            if ($existingPegSampleLink) {
                $pendingPegSampleLinkUpdate = [
                    'attachment' => $existingPegSampleLink,
                    'details' => $linkDetails,
                ];
            } else {
                $newPegSampleLinkDetails = $linkDetails;
            }
        }

        foreach (array_keys($replacementFiles) as $attachmentId) {
            if (!$existingPegSamples->has((int) $attachmentId)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'peg_sample_replacements' => 'One of the selected peg samples does not belong to this request.',
                ]);
            }

            if ($existingPegSamples->get((int) $attachmentId)->isExternalLink()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'peg_sample_replacements' => 'Use the peg sample link field to update a shared link.',
                ]);
            }
        }

        foreach ($removePegSampleIds as $attachmentId) {
            if (!$existingPegSamples->has($attachmentId)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'remove_peg_samples' => 'One of the selected peg samples does not belong to this request.',
                ]);
            }
        }

        $replacementIds = collect(array_keys($replacementFiles))->map(function ($attachmentId) {
            return (int) $attachmentId;
        });

        if ($replacementIds->intersect($removePegSampleIds)->isNotEmpty()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'peg_sample_replacements' => 'A peg sample cannot be replaced and removed at the same time.',
            ]);
        }

        if ($removeLegacyPegSample && $existingFilePegSamples->isNotEmpty()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'remove_legacy_peg_sample' => 'Use the Remove checkbox beside the specific file you want to remove.',
            ]);
        }

        if ($removeLegacyPegSample && $request->hasFile('peg_sample')) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'peg_sample' => 'The same peg sample cannot be replaced and removed at the same time.',
            ]);
        }

        $attachmentsToRemove = $existingPegSamples->only($removePegSampleIds->all());

        if ($request->hasFile('peg_sample') && $existingFilePegSamples->isNotEmpty()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'peg_sample' => 'Use the replacement field beside the specific file you want to update.',
            ]);
        }

        if (!empty($replacementFiles) || $request->hasFile('peg_sample')) {
            $destDir = public_path('peg_samples');

            if (!file_exists($destDir)) {
                mkdir($destDir, 0755, true);
            }
        }

        foreach ($replacementFiles as $attachmentId => $file) {
            $attachment = $existingPegSamples->get((int) $attachmentId);
            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getClientMimeType();
            $fileSize = $file->getSize();
            $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
            $fileName = time() . '_' . $layoutDesign->id . '_' . $attachment->id . '_' . uniqid() . '_' . $safeName;
            $newPath = '/peg_samples/' . $fileName;

            try {
                $file->move($destDir, $fileName);
            } catch (\Throwable $e) {
                foreach ($newStoredPaths as $newStoredPath) {
                    \Illuminate\Support\Facades\File::delete(
                        public_path('peg_samples/' . basename($newStoredPath))
                    );
                }

                throw $e;
            }

            $newStoredPaths[] = $newPath;
            $oldPathsToDelete[] = $attachment->output_attachment_path;
            $pendingReplacements[] = [
                'attachment' => $attachment,
                'details' => [
                    'output_attachment_path' => $newPath,
                    'original_name' => $originalName,
                    'mime_type' => $mimeType,
                    'file_size' => $fileSize,
                ],
            ];

            if ($attachment->output_attachment_path === $oldPegSamplePath) {
                $validated['peg_sample_path'] = $newPath;
            }
        }

        if ($request->hasFile('peg_sample')) {
            $file = $request->file('peg_sample');
            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getClientMimeType();
            $fileSize = $file->getSize();
            $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
            $fileName = time() . '_' . $layoutDesign->id . '_' . uniqid() . '_' . $safeName;
            $newPegSamplePath = '/peg_samples/' . $fileName;

            try {
                $file->move($destDir, $fileName);
            } catch (\Throwable $e) {
                foreach ($newStoredPaths as $newStoredPath) {
                    \Illuminate\Support\Facades\File::delete(
                        public_path('peg_samples/' . basename($newStoredPath))
                    );
                }

                throw $e;
            }

            $newStoredPaths[] = $newPegSamplePath;

            if ($oldPegSamplePath) {
                $oldPathsToDelete[] = $oldPegSamplePath;
            }

            $validated['peg_sample_path'] = $newPegSamplePath;
            $legacyPegSampleDetails = [
                'output_attachment_path' => $newPegSamplePath,
                'original_name' => $originalName,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
            ];
        }

        foreach ($attachmentsToRemove as $attachmentToRemove) {
            if (!$attachmentToRemove->isExternalLink()) {
                $oldPathsToDelete[] = $attachmentToRemove->output_attachment_path;
            }
        }

        $finalPegSamplePaths = $existingPegSamples
            ->reject(function ($attachment) {
                return $attachment->isExternalLink();
            })
            ->map(function ($attachment) {
                return $attachment->output_attachment_path;
            });

        foreach ($pendingReplacements as $replacement) {
            $finalPegSamplePaths->put(
                $replacement['attachment']->id,
                $replacement['details']['output_attachment_path']
            );
        }

        foreach ($removePegSampleIds as $attachmentId) {
            $finalPegSamplePaths->forget($attachmentId);
        }

        if ($existingFilePegSamples->isNotEmpty()) {
            $validated['peg_sample_path'] = $finalPegSamplePaths->first() ?: null;
        } elseif ($removeLegacyPegSample) {
            $validated['peg_sample_path'] = null;

            if ($oldPegSamplePath) {
                $oldPathsToDelete[] = $oldPegSamplePath;
            }
        }

        unset($validated['peg_sample']);
        unset($validated['peg_sample_replacements']);
        unset($validated['remove_peg_samples']);
        unset($validated['remove_legacy_peg_sample']);
        $validated['date_needed'] = $request->filled('date_needed')
            ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->date_needed)
            : null;

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use (
                $layoutDesign,
                $validated,
                $pendingReplacements,
                $pendingPegSampleLinkUpdate,
                $newPegSampleLinkDetails,
                $legacyPegSampleDetails,
                $attachmentsToRemove
            ) {
                $layoutDesign->update($validated);

                foreach ($pendingReplacements as $replacement) {
                    $replacement['attachment']->update($replacement['details']);
                }

                if ($pendingPegSampleLinkUpdate) {
                    $pendingPegSampleLinkUpdate['attachment']->update(
                        $pendingPegSampleLinkUpdate['details']
                    );
                }

                if ($newPegSampleLinkDetails) {
                    $layoutDesign->pegSamples()->create($newPegSampleLinkDetails);
                }

                if ($legacyPegSampleDetails) {
                    $layoutDesign->pegSamples()->create($legacyPegSampleDetails);
                }

                foreach ($attachmentsToRemove as $attachmentToRemove) {
                    $attachmentToRemove->delete();
                }
            });
        } catch (\Throwable $e) {
            foreach ($newStoredPaths as $newStoredPath) {
                \Illuminate\Support\Facades\File::delete(
                    public_path('peg_samples/' . basename($newStoredPath))
                );
            }

            throw $e;
        }

        foreach (array_unique(array_filter($oldPathsToDelete)) as $oldPath) {
            $oldFile = public_path('peg_samples/' . basename($oldPath));

            if (is_file($oldFile)) {
                \Illuminate\Support\Facades\File::delete($oldFile);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Layout design request updated.',
            'data' => [
                'id' => $layoutDesign->id,
                'reference_no' => $layoutDesign->reference_no,
                'status' => $layoutDesign->status,
            ],
        ]);
    }

    public function show(Request $request, $id) {
        // validate request by its email address (anti IDOR vuln)
        $validated = $request->validate([
            'requestor_email' => 'required|email|max:255'
        ]);

        $ld = LayoutDesign::with(['pegSamples', 'outputAttachments'])
            ->where('id', $id)
            ->where('requestor_email', $validated['requestor_email'])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $ld
        ]);
    }

    public function cancel(Request $request, $id) {
        $validated = $request->validate([
            'requestor_email' => 'required|email|max:255'
        ]);

        $layoutDesign = LayoutDesign::where('id', $id)
            ->where('requestor_email', $validated['requestor_email'])
            ->firstOrFail();

        if ($layoutDesign->status !== LayoutDesign::STATUS_PENDING) {
            return response()->json([
                'success' => false,
                'message' => 'Only pending requests can be cancelled.',
            ], 409);
        }

        $oldPegSamplePath = $layoutDesign->peg_sample_path;
        $pegSamples = $layoutDesign->pegSamples()->get();
        $pegSamplePaths = $pegSamples
            ->reject(function ($attachment) {
                return $attachment->isExternalLink();
            })
            ->pluck('output_attachment_path')
            ->filter()
            ->all();

        $layoutDesign->update([
            'status' => LayoutDesign::STATUS_CANCELLED,
            'peg_sample_path' => null,
        ]);

        $layoutDesign->pegSamples()->delete();

        foreach (array_unique($pegSamplePaths) as $pegSamplePath) {
            $pegSampleFile = public_path('peg_samples/' . basename($pegSamplePath));

            if (is_file($pegSampleFile)) {
                \Illuminate\Support\Facades\File::delete($pegSampleFile);
            }
        }

        if ($oldPegSamplePath && !in_array($oldPegSamplePath, $pegSamplePaths, true)) {
            $usedByAnotherRequest = LayoutDesign::where('peg_sample_path', $oldPegSamplePath)
                ->where('id', '!=', $layoutDesign->id)
                ->exists();

            if (!$usedByAnotherRequest) {
                $oldFile = public_path('peg_samples/' . basename($oldPegSamplePath));

                if (is_file($oldFile)) {
                    \Illuminate\Support\Facades\File::delete($oldFile);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Layout design request cancelled.',
            'data' => [
                'id' => $layoutDesign->id,
                'reference_no' => $layoutDesign->reference_no,
                'status' => $layoutDesign->status,
            ],
        ]);

    }

}
