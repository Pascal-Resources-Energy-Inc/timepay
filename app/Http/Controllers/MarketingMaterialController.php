<?php

namespace App\Http\Controllers;

use App\Mail\MarketingMaterialReleaseInvitationMail;
use App\Mail\MarketingMaterialReleaseEditedMail;
use App\Mail\MarketingMaterialReleaseConfirmationMail;
use App\MarketingMaterialRelease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class MarketingMaterialController extends Controller {
    private const APPROVER_EMAILS = [
        'irene.dura@pascalresources.com.ph',
        'ethan.par@pascalresources.com.ph',
    ];

    public function index(Request $request) {
        $canViewAllReleases = in_array(
            strtolower(auth()->user()->email),
            self::APPROVER_EMAILS,
            true
        );

        $accessibleReleases = MarketingMaterialRelease::query()->with('user.employee:id,user_id,status');

        if (!$canViewAllReleases) {
            $accessibleReleases->where('user_id', auth()->id());
        }

        $query = (clone $accessibleReleases)->orderBy('submitted_at', 'desc');

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

            $query->where(function ($query) use ($search) {
                $query->where('reference_no', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $releases = $query->paginate(20)->appends($request->query());

        return view('forms.mmr.index', [
            'releases' => $releases,
            'search' => $request->search,
            'status' => $request->status,
            'from' => $request->from,
            'to' => $request->to,
            'totalReleases' => (clone $accessibleReleases)->count(),
            'approvedCount' => (clone $accessibleReleases)->where('status', 'Approved')->count(),
            'cancelledCount' => (clone $accessibleReleases)->where('status', 'Cancelled')->count(),
            'declinedCount' => (clone $accessibleReleases)->where('status', 'Declined')->count(),
            'forApprovalCount' => (clone $accessibleReleases)->where('status', 'For Approval')->count(),
            'canViewAllReleases' => $canViewAllReleases,
            'header' => 'forms',
        ]);
    }

    public function create() {
        return view('forms.mmr.create', [
            'genders' => MarketingMaterialRelease::GENDERS,
            'release' => null,
            'header' => 'forms',
        ]);
    }

    public function publicCreate() {
        return view('forms.mmr.public_create', [
            'genders' => MarketingMaterialRelease::GENDERS,
            'release' => null,
            'updateUrl' => null,
        ]);
    }

    public function sendInvitation(Request $request) {
        $request->validate([
            'recipient_email' => 'required|email|max:255',
        ]);

        $recipientEmail = strtolower(trim((string) $request->recipient_email));

        try {
            Mail::to($recipientEmail)->send(
                new MarketingMaterialReleaseInvitationMail(
                    route('marketing-material-releases.public.create'),
                    route('marketing-material-releases.create')
                )
            );
        } catch (\Throwable $exception) {
            \Log::error('Marketing material release invitation email failed: ' . $exception->getMessage());

            Alert::error('The invitation could not be sent. Please try again.')->persistent('Dismiss');
            return redirect()->back()->withInput();
        }

        Alert::success('Form link sent successfully.')->persistent('Dismiss');
        return redirect()->back();
    }

    public function store(Request $request) {
        $validated = $this->validateRelease($request);
        $signaturePath = $this->storeSignature($request);
        $release = $this->createRelease($validated, $signaturePath, auth()->id());

        Alert::success('Form submitted successfully.')->persistent('Dismiss');

        return redirect()->route('marketing-material-releases.show', $release->id);
    }

    public function edit($id) {
        $release = MarketingMaterialRelease::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (!$release->canBeEditedBy(auth()->id())) {
            Alert::error($this->editRestrictionMessage($release))->persistent('Dismiss');

            return redirect()->route('marketing-material-releases.show', $release->id);
        }

        return view('forms.mmr.create', [
            'genders' => MarketingMaterialRelease::GENDERS,
            'release' => $release,
            'header' => 'forms',
        ]);
    }

    public function update(Request $request, $id) {
        $release = MarketingMaterialRelease::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (!$release->canBeEditedBy(auth()->id())) {
            Alert::error($this->editRestrictionMessage($release))->persistent('Dismiss');

            return redirect()->route('marketing-material-releases.show', $release->id);
        }

        $validated = $this->validateRelease($request, false);
        unset($validated['signature']);

        $userId = auth()->id();

        $release = DB::transaction(function () use ($request, $validated, $id, $userId) {
            $release = MarketingMaterialRelease::where('id', $id)
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$release->canBeEditedBy($userId)) {
                throw ValidationException::withMessages([
                    'edit' => $this->editRestrictionMessage($release),
                ]);
            }

            $release->fill($validated);

            if ($this->hasSignatureInput($request)) {
                $release->signature = $this->storeSignature($request);
                $release->date_signed = now()->toDateString();
            }

            $release->edited_at = now();
            $release->save();

            return $release;
        });

        try {
            Mail::to(self::APPROVER_EMAILS)->send(
                new MarketingMaterialReleaseEditedMail($release, auth()->user())
            );
        } catch (\Throwable $exception) {
            \Log::error('Marketing material release edit notification failed: ' . $exception->getMessage());

            Alert::warning('Request updated, but the approver notification email could not be sent.')
                ->persistent('Dismiss');

            return redirect()->route('marketing-material-releases.show', $release->id);
        }

        Alert::success('Marketing material release updated. The approver has been notified.')
            ->persistent('Dismiss');

        return redirect()->route('marketing-material-releases.show', $release->id);
    }

    public function publicStore(Request $request) {
        $validated = $this->validateRelease($request);
        $signaturePath = $this->storeSignature($request);

        $release = $this->createRelease($validated, $signaturePath, null);
        $release->refresh();

        $editExpiresAt = $release->submitted_at
            ->copy()
            ->addHours(MarketingMaterialRelease::EDIT_WINDOW_HOURS);
        $editUrl = URL::temporarySignedRoute(
            'marketing-material-releases.public.edit',
            $editExpiresAt,
            ['id' => $release->id]
        );

        try {
            Mail::to($release->email)->send(
                new MarketingMaterialReleaseConfirmationMail(
                    $release,
                    $editUrl,
                    $editExpiresAt
                )
            );
        } catch (\Throwable $exception) {
            \Log::error(
                'Marketing material release confirmation email failed: '
                . $exception->getMessage()
            );

            return redirect()
                ->route('marketing-material-releases.public.create')
                ->with(
                    'warning',
                    'Your form was submitted, but the confirmation email could not be sent. '
                    . 'Please contact the administrator.'
                );
        }

        return redirect()
            ->route('marketing-material-releases.public.create')
            ->with(
                'success',
                'Form submitted successfully. '
                . 'Check your email for confirmation.'
            );
    }

    public function publicEdit($id) {
        $release = MarketingMaterialRelease::where('id', $id)
            ->whereNull('user_id')
            ->firstOrFail();

        if (!$release->canBeEditedPublicly()) {
            abort(403, $this->editRestrictionMessage($release));
        }

        $editExpiresAt = $release->submitted_at
            ->copy()
            ->addHours(MarketingMaterialRelease::EDIT_WINDOW_HOURS);
        $updateUrl = URL::temporarySignedRoute(
            'marketing-material-releases.public.update',
            $editExpiresAt,
            ['id' => $release->id]
        );

        return view('forms.mmr.public_create', [
            'genders' => MarketingMaterialRelease::GENDERS,
            'release' => $release,
            'updateUrl' => $updateUrl,
        ]);
    }

    public function publicUpdate(Request $request, $id) {
        $release = MarketingMaterialRelease::where('id', $id)
            ->whereNull('user_id')
            ->firstOrFail();

        if (!$release->canBeEditedPublicly()) {
            abort(403, $this->editRestrictionMessage($release));
        }

        $validated = $this->validateRelease($request, false);
        unset($validated['signature']);

        $release = DB::transaction(function () use ($request, $validated, $id) {
            $release = MarketingMaterialRelease::where('id', $id)
                ->whereNull('user_id')
                ->lockForUpdate()
                ->firstOrFail();

            if (!$release->canBeEditedPublicly()) {
                throw ValidationException::withMessages([
                    'edit' => $this->editRestrictionMessage($release),
                ]);
            }

            $release->fill($validated);

            if ($this->hasSignatureInput($request)) {
                $release->signature = $this->storeSignature($request);
                $release->date_signed = now()->toDateString();
            }

            $release->edited_at = now();
            $release->save();

            return $release;
        });

        try {
            $editor = (object) [
                'name' => trim($release->first_name . ' ' . $release->last_name),
                'email' => $release->email,
            ];

            Mail::to(self::APPROVER_EMAILS)->send(
                new MarketingMaterialReleaseEditedMail($release, $editor)
            );
        } catch (\Throwable $exception) {
            \Log::error(
                'Public marketing material release edit notification failed: '
                . $exception->getMessage()
            );

            return redirect()
                ->route('marketing-material-releases.public.create')
                ->with(
                    'warning',
                    'Your request was updated, but the approver notification email '
                    . 'could not be sent.'
                );
        }

        return redirect()
            ->route('marketing-material-releases.public.create')
            ->with(
                'success',
                'Your request has been updated. Your one allowed edit has now been used.'
            );
    }

    private function validateRelease(Request $request, $signatureRequired = true) {
        $checkboxFields = array_merge(
            MarketingMaterialRelease::NAME_USAGE_FIELDS,
            MarketingMaterialRelease::AUTHORIZATION_FIELDS
        );

        foreach ($checkboxFields as $field) {
            $request->merge([
                $field => filter_var(
                    $request->input($field, false),
                    FILTER_VALIDATE_BOOLEAN
                ),
            ]);
        }

        $rules = [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'birth_date' => 'required|date|before_or_equal:today',
            'gender' => 'required|in:' . implode(',', MarketingMaterialRelease::GENDERS),
            'phone_number' => 'required|string|regex:/^09[0-9]{9}$/',
            'email' => 'required|email|max:255',

            /* 'address_line' => 'required|string|max:255', */
            /* 'address_city' => 'required|string|max:100', */
            /* 'address_state_province' => 'required|string|max:100', */
            /* 'address_postal_code' => 'required|string|max:20', */

            'signature' => $request->hasFile('signature')
                ? ($signatureRequired ? 'required' : 'nullable') . '|image|mimes:png,jpg,jpeg|max:2048'
                : ($signatureRequired ? 'required' : 'nullable') . '|string|max:3000000',
        ];

        foreach ($checkboxFields as $field) {
            $rules[$field] = 'required|boolean';
        }

        $validator = Validator::make($request->all(), $rules);

        $validator->after(function ($validator) use ($request) {
            if (!$this->hasSelectedCheckbox($request, MarketingMaterialRelease::NAME_USAGE_FIELDS)) {
                $validator->errors()->add(
                    'name_usage_preferences',
                    'Select at least one name-usage preference.'
                );
            }

            if (!$this->hasSelectedCheckbox($request, MarketingMaterialRelease::AUTHORIZATION_FIELDS)) {
                $validator->errors()->add(
                    'authorization_preferences',
                    'Select at least one authorization or release preference.'
                );
            }
        });

        return $validator->validate();
    }

    private function createRelease(array $validated, $signaturePath, $userId) {
        return DB::transaction(function () use ($validated, $signaturePath, $userId) {
            $release = MarketingMaterialRelease::create(array_merge($validated, [
                'reference_no' => 'TMP-' . Str::random(20),
                'signature' => $signaturePath,
                'date_signed' => now()->toDateString(),
                'user_id' => $userId,
            ]));

            $release->reference_no = 'MMR-' . str_pad($release->id, 6, '0', STR_PAD_LEFT);
            $release->save();

            return $release;
        });
    }

    public function show($id) {
        $canViewAllReleases = in_array(
            strtolower(auth()->user()->email),
            self::APPROVER_EMAILS,
            true
        );

        $releaseQuery = MarketingMaterialRelease::where('id', $id);

        if (!$canViewAllReleases) {
            $releaseQuery->where('user_id', auth()->id());
        }

        $release = $releaseQuery->firstOrFail();

        return view('forms.mmr.show', [
            'release' => $release,
            'header' => 'forms',
        ]);
    }

    public function cancel($id) {
        $release = MarketingMaterialRelease::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($release->status !== 'For Approval') {
            Alert::error('Only requests awaiting approval can be cancelled.')
                ->persistent('Dismiss');

            return redirect()->route('marketing-material-releases.index');
        }

        $release->status = 'Cancelled';
        $release->save();

        Alert::success('Marketing material release cancelled.')
            ->persistent('Dismiss');

        return redirect()->route('marketing-material-releases.index');
    }

    private function hasSelectedCheckbox(Request $request, array $fields) {
        foreach ($fields as $field) {
            if ($request->input($field) === true) {
                return true;
            }
        }

        return false;
    }

    private function hasSignatureInput(Request $request) {
        return $request->hasFile('signature') || $request->filled('signature');
    }

    private function editRestrictionMessage(MarketingMaterialRelease $release) {
        if ($release->status !== 'For Approval') {
            return 'Only requests awaiting approval can be edited.';
        }

        if ($release->edited_at) {
            return 'Access resource has expired.';
        }

        return 'The link you are requesting has expired.';
    }

    private function storeSignature(Request $request) {
        $directory = public_path('signature');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileName = 'mmr-' . date('YmdHis') . '-' . Str::random(12);

        if ($request->hasFile('signature')) {
            $signature = $request->file('signature');
            $fileName .= '.' . strtolower($signature->extension());
            $signature->move($directory, $fileName);

            return '/signature/' . $fileName;
        }

        $signature = $request->input('signature');

        if (!preg_match('/^data:image\/(png|jpe?g);base64,(.+)$/s', $signature, $matches)) {
            throw ValidationException::withMessages([
                'signature' => 'The signature must be a valid PNG or JPEG image.',
            ]);
        }

        $image = base64_decode(preg_replace('/\s+/', '', $matches[2]), true);

        if ($image === false || strlen($image) > (2 * 1024 * 1024)) {
            throw ValidationException::withMessages([
                'signature' => 'The signature image must not exceed 2 MB.',
            ]);
        }

        if (function_exists('getimagesizefromstring') && getimagesizefromstring($image) === false) {
            throw ValidationException::withMessages([
                'signature' => 'The signature image is invalid.',
            ]);
        }

        $extension = strtolower($matches[1]) === 'png' ? 'png' : 'jpg';
        $fileName .= '.' . $extension;

        if (file_put_contents($directory . DIRECTORY_SEPARATOR . $fileName, $image) === false) {
            throw ValidationException::withMessages([
                'signature' => 'The signature could not be saved. Please try again.',
            ]);
        }

        return '/signature/' . $fileName;
    }
}
