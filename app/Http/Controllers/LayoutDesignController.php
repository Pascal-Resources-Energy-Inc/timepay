<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LayoutDesign;
use App\Mail\LayoutDesignMail;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use PhpParser\Node\Expr\FuncCall;
use RealRashid\SweetAlert\Facades\Alert;

// approver: ethan.par@pascalresources.com.ph & irene.dura@pascalresources.com.ph

class LayoutDesignController extends Controller {
    // might be deprecated (public link for HERA)
    public function publicCreate() {
        return view('forms.ldr.public_ldr');
    }

    public function index(Request $request) {
        $query = LayoutDesign::where('user_id', auth()->id())
            ->orderBy('submitted_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('submitted_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('submitted_at', '<=', $request->to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                  ->orWhere('requestor_first_name', 'like', "%{$search}%")
                  ->orWhere('requestor_last_name', 'like', "%{$search}%")
                  ->orWhere('requestor_email', 'like', "%{$search}%")
                  ->orWhere('marketing_collateral_type', 'like', "%{$search}%")
                  ->orWhere('medium_type', 'like', "%{$search}%")
                  ->orWhere('date_needed', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(20)->appends($request->query());

        $from = $request->from;
        $to = $request->to;
        $status = $request->status;
        $search = $request->search;

        $total_requests = LayoutDesign::where('user_id', auth()->id())->count();
        $processed_count = LayoutDesign::where('user_id', auth()->id())
            ->where('status', LayoutDesign::STATUS_PROCESSED)
            ->count();
        $approved_count = LayoutDesign::where('user_id', auth()->id())
            ->where('status', LayoutDesign::STATUS_APPROVED)
            ->count();
        $cancelled_count = LayoutDesign::where('user_id', auth()->id())->where('status', 'Cancelled')->count();
        $declined_count = LayoutDesign::where('user_id', auth()->id())->where('status', 'Declined')->count();
        $pending_count = LayoutDesign::where('user_id', auth()->id())->where('status', 'Pending')->count();

        return view('forms.ldr.index', compact('requests',
            'search',
            'status',
            'from',
            'to',
            'total_requests',
            'processed_count',
            'approved_count',
            'cancelled_count',
            'declined_count',
            'pending_count') + ['header' => 'forms']);
    }

    public function create() {
        return view('forms.ldr.create', array('header' => 'forms'));
    }

    public function show($id) {
        $isApprover = \App\ApproverSetting::where('user_id', auth()->id())
            ->where('type_of_form', 'ldr')
            ->where('status', 'Active')
            ->exists();

        $request = LayoutDesign::with(['pegSamples', 'outputAttachments'])->findOrFail($id);

        if (!$isApprover) {
            $userEmail = User::where('id', auth()->id())->value('email');
            $ownerIds = User::where('email', $userEmail)->pluck('id');
            abort_if(!$ownerIds->contains($request->user_id), 403);
        }

        return view('forms.ldr.show', compact('request') + ['header' => 'forms']);
    }

    public function store(Request $request) {
        $selectedCollateralTypes = array_values(array_unique(
            (array) $request->input('marketing_collateral_type', [])
        ));
        $storeSignageSelected = in_array(
            'Store Signage',
            $selectedCollateralTypes,
            true
        );
        $storeSignageRules = $storeSignageSelected
            ? ['required', Rule::in(LayoutDesign::STORE_SIGNAGE_OPTIONS)]
            : ['nullable', Rule::in(LayoutDesign::STORE_SIGNAGE_OPTIONS)];

        $validated = $request->validate([
            'requestor_first_name' => 'required|string|max:255',
            'requestor_last_name' => 'required|string|max:255',
            'department' => 'required|in:' . implode(',', LayoutDesign::DEPARTMENTS),
            'requestor_email' => 'required|email|max:255',
            'intended_use' => 'required|array|min:1',
            'intended_use.*' => 'in:' . implode(',', LayoutDesign::INTENDED_USES),
            'medium_type' => 'required|in:' . implode(',', LayoutDesign::MEDIUM_TYPES),
            'marketing_collateral_type' => 'required|array|min:1',
            'marketing_collateral_type.*' => 'in:' . implode(',', LayoutDesign::COLLATERAL_TYPES),
            'store_signage_option' => $storeSignageRules,
            'purpose_of_layout_request' => 'required|string',
            'project_description' => 'required|string',
            'peg_samples' => ['nullable', 'array', 'max:6'],
            'peg_samples.*' => [
                'required',
                'mimes:jpg,jpeg,png,webp,mp4,pdf,doc,docx,ppt,pptx',
                'max:30720',
            ],
            'peg_sample_link' => [
                'nullable',
                'url',
                'max:1000',
                'regex:/^https?:\/\//i',
            ],
            'date_requested' => 'nullable|date',
            'date_needed' => 'nullable|date_format:Y-m-d',
        ], [
            'peg_samples.max' => 'You may upload up to 6 peg samples.',
            'peg_samples.*.mimes' => 'Each peg sample must be a JPG, JPEG, PNG, WebP, MP4, PDF, Word, or PowerPoint file.',
            'peg_samples.*.max' => 'Each peg sample must not exceed 30 MB.',
            'store_signage_option.required' => 'Select a Store Signage option.',
            'peg_sample_link.url' => 'Enter a valid peg sample link.',
            'peg_sample_link.max' => 'The peg sample link must not exceed 1000 characters.',
        ]);

        if ($storeSignageSelected) {
            $selectedCollateralTypes[] = $validated['store_signage_option'];
        }

        $validated['marketing_collateral_type'] = array_values(
            array_unique($selectedCollateralTypes)
        );

        $nextId = (int) LayoutDesign::max('id') + 1;

        unset($validated['peg_samples']);
        unset($validated['peg_sample_link']);
        unset($validated['store_signage_option']);

        $layoutDesign = LayoutDesign::create(array_merge($validated, [
            'peg_sample_path' => null,
            'date_requested' => $request->date_requested ?? now(),
            'date_needed' => $request->filled('date_needed')
                ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->date_needed)->startOfDay()
                : null,
            'reference_no' => 'LDR -' . str_pad($nextId, 6, '0', STR_PAD_LEFT),
            'user_id' => auth()->id(),
            'status' => LayoutDesign::STATUS_PENDING,
        ]));

        $storedPegSamplePaths = [];

        try {
            $destDir = public_path('peg_samples');

            if ($request->hasFile('peg_samples') && !file_exists($destDir)) {
                mkdir($destDir, 0755, true);
            }

            foreach ($request->file('peg_samples', []) as $index => $file) {
                $originalName = $file->getClientOriginalName();
                $mimeType = $file->getClientMimeType();
                $fileSize = $file->getSize();
                $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
                $fileName = time() . '_' . $layoutDesign->id . '_' . ($index + 1) . '_' . $safeName;
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
                    'mime_type' => \App\LayoutDesignAttachment::PEG_SAMPLE_LINK_MIME_TYPE,
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

            // delete the file if there something went wrong 
            $layoutDesign->pegSamples()->delete();
            $layoutDesign->delete();

            throw $e;
        }

        // send email notification to approvers 
        $approvers = \App\ApproverSetting::where('type_of_form', 'ldr')
            ->where('status', 'Active')
            ->get();

        foreach ($approvers as $approver) {
            if (!$approver->user || !$approver->user->email) continue;

            try {
                Mail::to($approver->user->email)
                    ->send(new LayoutDesignMail($layoutDesign, 'submitted'));
            } catch (\Exception $e) {
                \Log::error('LDR submitted email failed (approver ' . $approver->user_id . '): ' . $e->getMessage());
            }
        }

        Alert::success('Layout design request submitted.')->persistent('Dismiss');
        return redirect()->route('layout-design-requests.show', $layoutDesign->id);
    }

    public function edit($id) {
        $layoutDesign = LayoutDesign::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        abort_if($layoutDesign->status !== 'Pending', 403);

        return view('forms.ldr.edit', [
            'request' => $layoutDesign,
            'header' => 'forms',
        ]);
    }

    public function update(Request $request, $id) {
        $layoutDesign = LayoutDesign::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // request that are not pending cannot be edited anymore
        abort_if($layoutDesign->status !== 'Pending', 403);

        // array of selected collateral type options
        $selectedCollateralTypes = array_values(array_unique(
            (array) $request->input('marketing_collateral_type', [])
        ));

        // if 'store signage' is selected to collateral types
        // then the two options are required
        $storeSignageSelected = in_array(
            'Store Signage',
            $selectedCollateralTypes,
            true
        );
        $storeSignageRules = $storeSignageSelected
            ? ['required', Rule::in(LayoutDesign::STORE_SIGNAGE_OPTIONS)]
            : ['nullable', Rule::in(LayoutDesign::STORE_SIGNAGE_OPTIONS)];

        $validated = $request->validate([
            'requestor_first_name' => 'required|string|max:255',
            'requestor_last_name' => 'required|string|max:255',
            'department' => 'required|in:' . implode(',', LayoutDesign::DEPARTMENTS),
            'requestor_email' => 'required|email|max:255',
            'intended_use' => 'required|array|min:1',
            'intended_use.*' => 'in:' . implode(',', LayoutDesign::INTENDED_USES),
            'medium_type' => 'required|in:' . implode(',', LayoutDesign::MEDIUM_TYPES),
            'marketing_collateral_type' => 'required|array|min:1',
            'marketing_collateral_type.*' => 'in:' . implode(',', LayoutDesign::COLLATERAL_TYPES),
            'store_signage_option' => $storeSignageRules,
            'purpose_of_layout_request' => 'required|string',
            'project_description' => 'required|string',

            'peg_sample_replacements' => ['nullable', 'array'],
            'peg_sample_replacements.*' => [
                'nullable',
                'mimes:jpg,jpeg,png,webp,mp4,pdf,doc,docx,ppt,pptx',
                'max:30720',
            ],
            'remove_peg_samples' => ['nullable', 'array'],
            'remove_peg_samples.*' => ['integer'],
            'remove_legacy_peg_sample' => ['nullable', 'boolean'],
            'peg_sample_link' => ['nullable', 'url', 'max:1000'],

            // Legacy requests may still have only peg_sample_path.
            'peg_sample' => [
                'nullable',
                'mimes:jpg,jpeg,png,webp,mp4,pdf,doc,docx,ppt,pptx',
                'max:30720',
            ],

            'date_needed' => 'nullable|date_format:Y-m-d',
        ], [
            'store_signage_option.required' => 'Select a Store Signage option.',
            'peg_sample_replacements.*.mimes' => 'Each replacement must be a JPG, JPEG, PNG, WebP, MP4, PDF, Word, or PowerPoint file.',
            'peg_sample_replacements.*.max' => 'Each replacement must not exceed 30 MB.',
            'peg_sample.mimes' => 'The peg sample must be a JPG, JPEG, PNG, WebP, MP4, PDF, Word, or PowerPoint file.',
            'peg_sample.max' => 'The peg sample must not exceed 30 MB.',
            'peg_sample_link.url' => 'Enter a valid peg sample link.',
            'peg_sample_link.max' => 'The peg sample link must not exceed 255 characters.',
        ]);

        if ($storeSignageSelected) {
            $selectedCollateralTypes[] = $validated['store_signage_option'];
        }

        $validated['marketing_collateral_type'] = array_values(
            array_unique($selectedCollateralTypes)
        );
        unset($validated['store_signage_option']);

        $oldPegSamplePath = $layoutDesign->peg_sample_path;
        $existingPegSamples = $layoutDesign->pegSamples()->get()->keyBy('id');
        $existingPegSampleLink = $existingPegSamples->first(function ($attachment) {
            return $attachment->mime_type === \App\LayoutDesignAttachment::PEG_SAMPLE_LINK_MIME_TYPE;
        });
        $pegSampleLink = trim((string) ($validated['peg_sample_link'] ?? ''));
        unset($validated['peg_sample_link']);
        $replacementFiles = $request->file('peg_sample_replacements', []);
        $removePegSampleIds = collect($request->input('remove_peg_samples', []))
            ->map(function ($attachmentId) {
                return (int) $attachmentId;
            })
            ->unique()
            ->values();

        if ($existingPegSampleLink && $pegSampleLink === '') {
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

        if ($pegSampleLink !== '') {
            $linkDetails = [
                'output_attachment_path' => $pegSampleLink,
                'original_name' => 'Shared peg sample link',
                'mime_type' => \App\LayoutDesignAttachment::PEG_SAMPLE_LINK_MIME_TYPE,
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

        if ($removeLegacyPegSample && $existingPegSamples->isNotEmpty()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'remove_legacy_peg_sample' => 'Use the Remove button beside the specific file you want to remove.',
            ]);
        }

        if ($removeLegacyPegSample && $request->hasFile('peg_sample')) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'peg_sample' => 'The same peg sample cannot be replaced and removed at the same time.',
            ]);
        }

        $attachmentsToRemove = $existingPegSamples->only($removePegSampleIds->all());

        if ($request->hasFile('peg_sample') && $existingPegSamples->isNotEmpty()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'peg_sample' => 'Use the Replace button beside the specific file you want to update.',
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

        if ($existingPegSamples->isNotEmpty()) {
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
            ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->date_needed)->startOfDay()
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

        Alert::success('Layout design request updated successfully.')
            ->persistent('Dismiss');

        return redirect()->route(
            'layout-design-requests.show',
            $layoutDesign->id
        );
    }

    public function cancel($id) {
        $request = LayoutDesign::with('pegSamples')->findOrFail($id);

        // requestor-only (mirror show's ownership rule)
        $userEmail = User::where('id', auth()->id())->value('email');
        $ownerIds = User::where('email', $userEmail)->pluck('id');
        abort_if(!$ownerIds->contains($request->user_id), 403);

        if ($request->status !== 'Pending') {
            Alert::error('Action denied.')->persistent('Dismiss');
            return redirect()->route('layout-design-requests.index');
        }

        $oldPegSamplePath = $request->peg_sample_path;
        $pegSamplePaths = $request->pegSamples
            ->pluck('output_attachment_path')
            ->filter()
            ->all();
        $currentRequestId = $request->id;

        $request->status = 'Cancelled';
        $request->peg_sample_path = null;
        $request->save();

        $request->pegSamples()->delete();

        foreach (array_unique($pegSamplePaths) as $pegSamplePath) {
            $pegSampleFile = public_path('peg_samples/' . basename($pegSamplePath));

            if (is_file($pegSampleFile)) {
                \Illuminate\Support\Facades\File::delete($pegSampleFile);
            }
        }

        if ($oldPegSamplePath && !in_array($oldPegSamplePath, $pegSamplePaths, true)) {
            $usedByAnotherRequest = LayoutDesign::where(
                'peg_sample_path',
                $oldPegSamplePath
            )
                ->where('id', '!=', $currentRequestId)
                ->exists();

            if (!$usedByAnotherRequest) {
                $oldFile = public_path(
                    'peg_samples/' . basename($oldPegSamplePath)
                );

                if (is_file($oldFile)) {
                    $deleted = \Illuminate\Support\Facades\File::delete($oldFile);

                    if (!$deleted) {
                        \Log::warning(
                            "Failed to delete peg sample for LDR ID {$currentRequestId}: {$oldFile}"
                        );
                    }
                }
            }
        }

        Alert::success('Layout design request has been cancelled.')->persistent('Dismiss');
        return redirect()->route('layout-design-requests.index');
    }
}
