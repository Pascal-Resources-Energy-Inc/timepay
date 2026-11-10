@php
    $isEditing = !empty($release);
    $formAction = $isEditing
        ? $updateUrl
        : route('marketing-material-releases.public.store');
@endphp

<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $isEditing ? 'Edit Marketing Material Release Form' : 'Marketing Material Release Form' }}</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<style>
    body {
        background: #f8fafc;
        font-family: Roboto, sans-serif;
    }

    .field {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: .5rem;
        background: #fff;
        padding: .65rem .75rem;
        color: #111827;
        font-size: .875rem;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
    }

    .field:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
    }

    .choice-group {
        border: 1px solid #e5e7eb;
        border-radius: .75rem;
        overflow: hidden;
    }

    .choice-row {
        align-items: flex-start;
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
        cursor: pointer;
        display: flex;
        gap: .75rem;
        padding: .9rem 1rem;
    }

    .choice-row:last-child {
        border-bottom: 0;
    }

    .choice-row:has(input:checked) {
        background: #eff6ff;
    }

    .choice-row input {
        flex: 0 0 auto;
        margin-top: .2rem;
    }

    .choice-group.is-invalid {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, .08);
    }

    .signature-shell {
        background: #f8fafc;
        border: 1px solid #dfe3e8;
        border-radius: .75rem;
        overflow: hidden;
        padding: .75rem;
    }

    #signatureCanvas {
        background: #fff;
        border: 1px dashed #b8c0cc;
        border-radius: .5rem;
        cursor: crosshair;
        display: block;
        height: 200px;
        touch-action: none;
        width: 100%;
    }
</style>

<div class="mx-auto max-w-4xl px-4 py-6 sm:py-8">
    <header class="mb-6">
        <h1 class="mb-1 text-xl text-gray-900 sm:text-2xl">PASCAL RESOURCES ENERGY, INC.</h1>
        <div class="mb-3 flex w-full">
            <div class="w-full border-[3px] border-[#59c2e5]"></div>
            <div class="w-full border-[3px] border-[#fe0001]"></div>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">
            {{ $isEditing ? 'Edit Marketing Material Release Form' : 'Marketing Material Release Form' }}
        </h2>
        <p class="mt-2 text-sm text-gray-500">CEO-MBD-FOR-016-001</p>
        @if ($isEditing)
            <p class="mt-2 text-sm font-bold text-red-500">
                (This is one time edit only)
            </p>
        @endif
    </header>

    @if (session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="mb-5 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800" role="alert">
            {{ session('warning') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700" role="alert">
            <strong>Please review the following:</strong>
            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" id="marketingMaterialReleaseForm" data-editing="{{ $isEditing ? 'true' : 'false' }}" novalidate>
        @csrf
        @if ($isEditing)
            @method('PUT')
        @endif

        <section class="mb-5 rounded-xl border border-gray-200 bg-white p-4 sm:p-6">
            <div class="mb-6 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">A</span>
                <h3 class="text-base font-bold text-gray-900">Personal Information</h3>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="first_name" class="mb-2 block text-xs font-semibold text-gray-500">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $isEditing ? $release->first_name : '') }}" class="field" maxlength="100" autocomplete="given-name" required>
                </div>

                <div>
                    <label for="last_name" class="mb-2 block text-xs font-semibold text-gray-500">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $isEditing ? $release->last_name : '') }}" class="field" maxlength="100" autocomplete="family-name" required>
                </div>

                <div>
                    <label for="birth_date" class="mb-2 block text-xs font-semibold text-gray-500">Birth Date <span class="text-red-500">*</span></label>
                    <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $isEditing ? optional($release->birth_date)->format('Y-m-d') : '') }}" class="field" max="{{ date('Y-m-d') }}" required>
                </div>

                <div>
                    <label for="gender" class="mb-2 block text-xs font-semibold text-gray-500">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" id="gender" class="field" required>
                        <option value="">-- Select Gender --</option>
                        @foreach ($genders as $gender)
                            <option value="{{ $gender }}" {{ old('gender', $isEditing ? $release->gender : '') === $gender ? 'selected' : '' }}>{{ $gender }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="phone_number" class="mb-2 block text-xs font-semibold text-gray-500">Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', $isEditing ? $release->phone_number : '') }}" class="field" inputmode="numeric" autocomplete="tel" pattern="09[0-9]{9}" maxlength="11" placeholder="09xxxxxxxxx" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>

                <div>
                    <label for="email" class="mb-2 block text-xs font-semibold text-gray-500">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $isEditing ? $release->email : '') }}" class="field" maxlength="255" autocomplete="email" placeholder="example@example.com" required>
                </div>
            </div>
        </section>

        <section class="mb-5 rounded-xl border border-gray-200 bg-white p-4 sm:p-6">
            <div class="mb-6 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">B</span>
                <h3 class="text-base font-bold text-gray-900">Authorization and Release</h3>
            </div>

            <fieldset class="mb-7">
                <legend class="mb-1 text-sm font-semibold text-gray-900">What is your preference regarding the use of your name? <span class="text-red-500">*</span></legend>
                <p class="mb-3 text-xs text-gray-500">You may select one or more options.</p>

                <div class="choice-group" id="nameUsageGroup">
                    <label class="choice-row">
                        <input type="checkbox" name="consent_complete_name" value="1" class="name-usage-checkbox" {{ old('consent_complete_name', $isEditing ? $release->consent_complete_name : false) ? 'checked' : '' }}>
                        <span class="text-sm leading-6 text-gray-700">I consent to the use of my complete name.</span>
                    </label>
                    <label class="choice-row">
                        <input type="checkbox" name="consent_first_name_only" value="1" class="name-usage-checkbox" {{ old('consent_first_name_only', $isEditing ? $release->consent_first_name_only : false) ? 'checked' : '' }}>
                        <span class="text-sm leading-6 text-gray-700">I consent to the use of my first name only.</span>
                    </label>
                    <label class="choice-row">
                        <input type="checkbox" name="consent_nickname" value="1" class="name-usage-checkbox" {{ old('consent_nickname', $isEditing ? $release->consent_nickname : false) ? 'checked' : '' }}>
                        <span class="text-sm leading-6 text-gray-700">I consent to the use of my nickname.</span>
                    </label>
                    <label class="choice-row">
                        <input type="checkbox" name="consent_anonymous_photos" value="1" class="name-usage-checkbox" {{ old('consent_anonymous_photos', $isEditing ? $release->consent_anonymous_photos : false) ? 'checked' : '' }}>
                        <span class="text-sm leading-6 text-gray-700">I consent to the use of my photographs anonymously.</span>
                    </label>
                </div>

                <p class="mt-2 hidden text-xs font-medium text-red-600" id="nameUsageError">Select at least one name-usage preference.</p>
                @if ($errors->has('name_usage_preferences'))
                    <p class="mt-2 text-xs font-medium text-red-600">{{ $errors->first('name_usage_preferences') }}</p>
                @endif
            </fieldset>

            <fieldset>
                <legend class="mb-1 text-sm font-semibold text-gray-900">Please check the boxes regarding your preference. <span class="text-red-500">*</span></legend>
                <p class="mb-3 text-xs text-gray-500">You may select one or more options.</p>

                <div class="choice-group" id="authorizationGroup">
                    <label class="choice-row">
                        <input type="checkbox" name="authorize_capture" value="1" class="authorization-checkbox" {{ old('authorize_capture', $isEditing ? $release->authorize_capture : false) ? 'checked' : '' }}>
                        <span class="text-sm leading-6 text-gray-700">I authorize Pascal Resources Energy, Inc. to take my photographs, video, and narrative.</span>
                    </label>
                    <label class="choice-row">
                        <input type="checkbox" name="authorize_social_media" value="1" class="authorization-checkbox" {{ old('authorize_social_media', $isEditing ? $release->authorize_social_media : false) ? 'checked' : '' }}>
                        <span class="text-sm leading-6 text-gray-700">I authorize Pascal Resources Energy, Inc. to use my photos on Facebook, Twitter, Instagram, other social media platforms, and other marketing materials they deem fit.</span>
                    </label>
                    <label class="choice-row">
                        <input type="checkbox" name="authorize_editing" value="1" class="authorization-checkbox" {{ old('authorize_editing', $isEditing ? $release->authorize_editing : false) ? 'checked' : '' }}>
                        <span class="text-sm leading-6 text-gray-700">I authorize Pascal Resources Energy, Inc. to edit, alter, copy, or distribute the photos for social media advertising and marketing.</span>
                    </label>
                    <label class="choice-row">
                        <input type="checkbox" name="agree_company_ip_ownership" value="1" class="authorization-checkbox" {{ old('agree_company_ip_ownership', $isEditing ? $release->agree_company_ip_ownership : false) ? 'checked' : '' }}>
                        <span class="text-sm leading-6 text-gray-700">I agree that all intellectual property rights to the photos belong to Pascal Resources Energy, Inc.</span>
                    </label>
                    <label class="choice-row">
                        <input type="checkbox" name="agree_no_compensation" value="1" class="authorization-checkbox" {{ old('agree_no_compensation', $isEditing ? $release->agree_no_compensation : false) ? 'checked' : '' }}>
                        <span class="text-sm leading-6 text-gray-700">I agree that I will not receive monetary compensation for the use of my photographs on social media platforms.</span>
                    </label>
                </div>

                <p class="mt-2 hidden text-xs font-medium text-red-600" id="authorizationError">Select at least one authorization or release preference.</p>
                @if ($errors->has('authorization_preferences'))
                    <p class="mt-2 text-xs font-medium text-red-600">{{ $errors->first('authorization_preferences') }}</p>
                @endif
            </fieldset>
        </section>

        <section class="mb-5 rounded-xl border border-gray-200 bg-white p-4 sm:p-6">
            <div class="mb-6 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">C</span>
                <h3 class="text-base font-bold text-gray-900">Signature</h3>
            </div>

            <label class="mb-2 block text-sm font-semibold text-gray-900">
                Signature
                @if (!$isEditing)
                    <span class="text-red-500">*</span>
                @endif
            </label>
            @if ($isEditing)
                <div class="mb-3 rounded-lg border border-gray-200 bg-white p-3">
                    <p class="mb-2 text-xs font-semibold text-gray-500">Current signature</p>
                    <img src="{{ asset(ltrim($release->signature, '/')) }}" alt="Current signature" class="max-h-24 max-w-full">
                    <p class="mt-2 text-xs text-gray-500">
                        Your current signature will be retained unless you draw a new one below.
                    </p>
                </div>
            @endif
            <div class="signature-shell">
                <canvas id="signatureCanvas"></canvas>
                <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                    <span class="text-xs text-gray-500">
                        {{ $isEditing ? 'Draw only if you want to replace your current signature.' : 'Draw your signature using your mouse or finger.' }}
                    </span>
                    <button type="button" id="clearSignature" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700">
                        {{ $isEditing ? 'Clear new signature' : 'Clear' }}
                    </button>
                </div>
            </div>
            <input type="hidden" name="signature" id="signature">
            <p class="mt-2 hidden text-xs font-medium text-red-600" id="signatureError">
                Please provide your signature.
            </p>
            @if ($errors->has('signature'))
                <p class="mt-2 text-xs font-medium text-red-600">{{ $errors->first('signature') }}</p>
            @endif
        </section>

        <div class="flex justify-end">
            <button type="submit" id="submitButton" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60">
                {{ $isEditing ? 'Update Request' : 'Submit' }}
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('marketingMaterialReleaseForm');
    var isEditing = form.dataset.editing === 'true';
    var canvas = document.getElementById('signatureCanvas');
    var signatureInput = document.getElementById('signature');
    var signatureError = document.getElementById('signatureError');
    var clearButton = document.getElementById('clearSignature');
    var submitButton = document.getElementById('submitButton');
    var signaturePad = new SignaturePad(canvas, {
        minWidth: 0.8,
        maxWidth: 2.2,
        penColor: 'rgb(24, 32, 52)'
    });

    function resizeCanvas() {
        var data = signaturePad.isEmpty() ? null : signaturePad.toData();
        var ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = 200 * ratio;
        canvas.getContext('2d').scale(ratio, ratio);
        signaturePad.clear();

        if (data) {
            signaturePad.fromData(data);
        }
    }

    function validateGroup(selector, groupId, errorId) {
        var valid = document.querySelectorAll(selector + ':checked').length > 0;
        document.getElementById(groupId).classList.toggle('is-invalid', !valid);
        document.getElementById(errorId).classList.toggle('hidden', valid);
        return valid;
    }

    clearButton.addEventListener('click', function () {
        signaturePad.clear();
        signatureInput.value = '';
        signatureError.classList.add('hidden');
    });

    document.querySelectorAll('.name-usage-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            validateGroup('.name-usage-checkbox', 'nameUsageGroup', 'nameUsageError');
        });
    });

    document.querySelectorAll('.authorization-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            validateGroup('.authorization-checkbox', 'authorizationGroup', 'authorizationError');
        });
    });

    form.addEventListener('submit', function (event) {
        var nameUsageValid = validateGroup('.name-usage-checkbox', 'nameUsageGroup', 'nameUsageError');
        var authorizationValid = validateGroup('.authorization-checkbox', 'authorizationGroup', 'authorizationError');
        var signatureValid = isEditing || !signaturePad.isEmpty();

        signatureError.classList.toggle('hidden', signatureValid);

        if (!form.checkValidity() || !nameUsageValid || !authorizationValid || !signatureValid) {
            event.preventDefault();
            form.reportValidity();

            var firstError = document.querySelector('.is-invalid, #signatureError:not(.hidden)');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }

        signatureInput.value = signaturePad.isEmpty()
            ? ''
            : signaturePad.toDataURL('image/png');
        submitButton.disabled = true;
        submitButton.textContent = isEditing ? 'Updating...' : 'Submitting...';
    });

    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
});
</script>
