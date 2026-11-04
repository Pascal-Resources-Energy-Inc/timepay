@extends('layouts.header')

@php
  $isEditing = !empty($release);
@endphp

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-md-12" align="right">
      <a href="{{ route('marketing-material-releases.index') }}" style="display: flex; align-items: center; width: fit-content;" class="btn btn-sm btn-light border mb-2">
        <i class="ti-arrow-left mr-1"></i> Back
      </a>
    </div>

    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-column">
              <div class="d-inline-block mb-1" style="width: fit-content;">
                <h4 class="card-title">{{ $isEditing ? 'Edit Marketing Material Release' : 'Marketing Material Release Form' }}</h4>
                <div class="d-flex">
                  <div class="w-100" style="border-top: 3px solid #59c2e5;"></div>
                  <div class="w-100" style="border-top: 3px solid #fe0001;"></div>
                </div>
              </div>
              <small class="text-muted mb-4">CEO-MBD-FOR-016-001.A</small>
            </div>

            <style>
              .mmr-section-title {
                border-bottom: 1px solid #e3e6ed;
                color: #343a40;
                font-size: 1rem;
                font-weight: 600;
                margin: 1.25rem 0 1rem;
                padding-bottom: .65rem;
              }
              .mmr-check-group {
                background: #f8f9fc;
                border: 1px solid #e3e6ed;
                border-radius: .5rem;
                padding: .35rem 1rem;
              }
              .mmr-check-option {
                align-items: flex-start;
                border-bottom: 1px solid #eceff4;
                display: flex;
                padding: .8rem 0;
              }
              .mmr-check-option:last-child {
                border-bottom: 0;
              }
              .mmr-check-option .form-check-input {
                flex: 0 0 auto;
                margin: .2rem .75rem 0 0;
                position: static;
              }
              .mmr-check-option label {
                cursor: pointer;
                line-height: 1.45;
                margin: 0;
              }
              .signature-shell {
                background: #f8f9fc;
                border: 1px solid #dfe3e8;
                border-radius: .5rem;
                overflow: hidden;
                padding: .75rem;
              }
              #signatureCanvas {
                background: #fff;
                border: 1px dashed #b8c0cc;
                border-radius: .35rem;
                cursor: crosshair;
                display: block;
                height: 200px;
                touch-action: none;
                width: 100%;
              }
              .existing-signature-preview {
                background: #fff;
                border: 1px solid #dfe3e8;
                border-radius: .35rem;
                max-height: 120px;
                max-width: 100%;
                object-fit: contain;
                padding: .5rem;
              }
            </style>

            <form id="marketingMaterialReleaseForm" action="{{ $isEditing ? route('marketing-material-releases.update', $release->id) : route('marketing-material-releases.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              @if($isEditing)
                @method('PUT')
              @endif

              @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
              @endif

              @if($isEditing)
              <div class="alert alert-warning">
                <strong>One-time edit:</strong> Saving these changes will use the only edit allowed for this request.
              </div>
              @endif

              <h5 class="mmr-section-title"><i class="ti-user mr-2 text-info"></i>Personal Information</h5>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="first_name" id="first_name" value="{{ old('first_name', $isEditing ? $release->first_name : optional(auth()->user()->employee)->first_name) }}" maxlength="100" required>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="last_name" id="last_name" value="{{ old('last_name', $isEditing ? $release->last_name : optional(auth()->user()->employee)->last_name) }}" maxlength="100" required>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="birth_date" class="form-label">Birth Date <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" name="birth_date" id="birth_date" value="{{ old('birth_date', $isEditing ? optional($release->birth_date)->format('Y-m-d') : optional(auth()->user()->employee)->birth_date) }}" max="{{ date('Y-m-d') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                  <select class="form-control" name="gender" id="gender" required>
                    <option value="">-- Select Gender --</option>
                    @foreach ($genders as $gender)
                    <option value="{{ $gender }}" {{ strtolower((string) old('gender', $isEditing ? $release->gender : optional(auth()->user()->employee)->gender)) === strtolower($gender) ? 'selected' : '' }}>{{ $gender }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                  <input type="tel" class="form-control" name="phone_number" id="phone_number" value="{{ old('phone_number', $isEditing ? $release->phone_number : optional(auth()->user()->employee)->personal_number) }}" inputmode="numeric" pattern="[0-9]*" maxlength="11" placeholder="09xxxxxxxxx" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $isEditing ? $release->email : auth()->user()->email) }}" maxlength="255" placeholder="example@example.com" required>
                </div>
              </div>

              <!-- <h5 class="mmr-section-title"><i class="ti-location-pin mr-2 text-info"></i>Address</h5> -->

              <!-- <div class="row"> -->
              <!--   <div class="col-md-12 mb-3"> -->
              <!--     <label for="address_line" class="form-label">Street Address <span class="text-danger">*</span></label> -->
              <!--     <input type="text" class="form-control" name="address_line" id="address_line" value="{{ old('address_line') }}" maxlength="255" required> -->
              <!--   </div> -->
              <!---->
              <!--   <div class="col-md-4 mb-3"> -->
              <!--     <label for="address_city" class="form-label">City <span class="text-danger">*</span></label> -->
              <!--     <input type="text" class="form-control" name="address_city" id="address_city" value="{{ old('address_city') }}" maxlength="100" required> -->
              <!--   </div> -->
              <!---->
              <!--   <div class="col-md-4 mb-3"> -->
              <!--     <label for="address_state_province" class="form-label">State / Province <span class="text-danger">*</span></label> -->
              <!--     <input type="text" class="form-control" name="address_state_province" id="address_state_province" value="{{ old('address_state_province') }}" maxlength="100" required> -->
              <!--   </div> -->
              <!---->
              <!--   <div class="col-md-4 mb-3"> -->
              <!--     <label for="address_postal_code" class="form-label">Postal / ZIP Code <span class="text-danger">*</span></label> -->
              <!--     <input type="text" class="form-control" name="address_postal_code" id="address_postal_code" value="{{ old('address_postal_code') }}" maxlength="20" required> -->
              <!--   </div> -->
              <!-- </div> -->

              <h5 class="mmr-section-title"><i class="ti-check-box mr-2 text-info"></i>Authorization and Release</h5>

              <div class="row">
                <div class="col-md-12 mb-4">
                  <label class="form-label">What is your preference regarding the use of your name? <span class="text-danger">*</span></label>
                  <small class="form-text text-muted mb-2">You may select one or more options.</small>
                  <div class="mmr-check-group" id="nameUsageGroup">
                    <div class="mmr-check-option">
                      <input class="form-check-input name-usage-checkbox" type="checkbox" name="consent_complete_name" id="consent_complete_name" value="1" {{ old('consent_complete_name', $isEditing ? $release->consent_complete_name : false) ? 'checked' : '' }}>
                      <label for="consent_complete_name">I consent to the use of my complete name.</label>
                    </div>
                    <div class="mmr-check-option">
                      <input class="form-check-input name-usage-checkbox" type="checkbox" name="consent_first_name_only" id="consent_first_name_only" value="1" {{ old('consent_first_name_only', $isEditing ? $release->consent_first_name_only : false) ? 'checked' : '' }}>
                      <label for="consent_first_name_only">I consent to the use of my first name only.</label>
                    </div>
                    <div class="mmr-check-option">
                      <input class="form-check-input name-usage-checkbox" type="checkbox" name="consent_nickname" id="consent_nickname" value="1" {{ old('consent_nickname', $isEditing ? $release->consent_nickname : false) ? 'checked' : '' }}>
                      <label for="consent_nickname">I consent to the use of my nickname.</label>
                    </div>
                    <div class="mmr-check-option">
                      <input class="form-check-input name-usage-checkbox" type="checkbox" name="consent_anonymous_photos" id="consent_anonymous_photos" value="1" {{ old('consent_anonymous_photos', $isEditing ? $release->consent_anonymous_photos : false) ? 'checked' : '' }}>
                      <label for="consent_anonymous_photos">I consent to the use of my photographs anonymously.</label>
                    </div>
                  </div>
                  <small class="text-danger d-none mt-2" id="nameUsageError">Select at least one name-usage preference.</small>
                  @if ($errors->has('name_usage_preferences'))
                    <small class="text-danger d-block mt-2">{{ $errors->first('name_usage_preferences') }}</small>
                  @endif
                </div>

                <div class="col-md-12 mb-3">
                  <label class="form-label">Please check the boxes regarding your preference. <span class="text-danger">*</span></label>
                  <small class="form-text text-muted mb-2">You may select one or more options.</small>
                  <div class="mmr-check-group" id="authorizationGroup">
                    <div class="mmr-check-option">
                      <input class="form-check-input authorization-checkbox" type="checkbox" name="authorize_capture" id="authorize_capture" value="1" {{ old('authorize_capture', $isEditing ? $release->authorize_capture : false) ? 'checked' : '' }}>
                      <label for="authorize_capture">I authorize Pascal Resources Energy, Inc. to take my photographs, video, and narrative.</label>
                    </div>
                    <div class="mmr-check-option">
                      <input class="form-check-input authorization-checkbox" type="checkbox" name="authorize_social_media" id="authorize_social_media" value="1" {{ old('authorize_social_media', $isEditing ? $release->authorize_social_media : false) ? 'checked' : '' }}>
                      <label for="authorize_social_media">I authorize Pascal Resources Energy, Inc. to use my photos on Facebook, Twitter, Instagram, other social media platforms, and other marketing materials they deem fit.</label>
                    </div>
                    <div class="mmr-check-option">
                      <input class="form-check-input authorization-checkbox" type="checkbox" name="authorize_editing" id="authorize_editing" value="1" {{ old('authorize_editing', $isEditing ? $release->authorize_editing : false) ? 'checked' : '' }}>
                      <label for="authorize_editing">I authorize Pascal Resources Energy, Inc. to edit, alter, copy, or distribute the photos for social media advertising and marketing.</label>
                    </div>
                    <div class="mmr-check-option">
                      <input class="form-check-input authorization-checkbox" type="checkbox" name="agree_company_ip_ownership" id="agree_company_ip_ownership" value="1" {{ old('agree_company_ip_ownership', $isEditing ? $release->agree_company_ip_ownership : false) ? 'checked' : '' }}>
                      <label for="agree_company_ip_ownership">I agree that all intellectual property rights to the photos belong to Pascal Resources Energy, Inc.</label>
                    </div>
                    <div class="mmr-check-option">
                      <input class="form-check-input authorization-checkbox" type="checkbox" name="agree_no_compensation" id="agree_no_compensation" value="1" {{ old('agree_no_compensation', $isEditing ? $release->agree_no_compensation : false) ? 'checked' : '' }}>
                      <label for="agree_no_compensation">I agree that I will not receive monetary compensation for the use of my photographs on social media platforms.</label>
                    </div>
                  </div>
                  <small class="text-danger d-none mt-2" id="authorizationError">Select at least one authorization or release preference.</small>
                  @if ($errors->has('authorization_preferences'))
                    <small class="text-danger d-block mt-2">{{ $errors->first('authorization_preferences') }}</small>
                  @endif
                </div>
              </div>

              <div class="alert alert-warning mb-4" role="note">
                <strong class="d-block mb-1">Consent for Future Use</strong>
                By submitting this form, you agree that your selected consent preferences will apply to future use of your photographs, videos, and related content.
              </div>

              <h5 class="mmr-section-title"><i class="ti-pencil-alt mr-2 text-info"></i>Signature</h5>

              <div class="row">
                <div class="col-md-8 mb-3">
                  <label class="form-label">Signature @if(!$isEditing)<span class="text-danger">*</span>@endif</label>
                  @if($isEditing && $release->signature)
                  <div class="mb-3">
                    <small class="text-muted d-block mb-2">Current signature</small>
                    <img src="{{ asset($release->signature) }}" alt="Current signature" class="existing-signature-preview">
                  </div>
                  @endif
                  <div class="signature-shell">
                    <canvas id="signatureCanvas"></canvas>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                      <small class="text-muted">{{ $isEditing ? 'Leave blank to keep the current signature, or draw a replacement.' : 'Draw your signature using your mouse or finger.' }}</small>
                      <button type="button" class="btn btn-sm btn-outline-secondary" id="clearSignature">
                        <i class="ti-eraser mr-1"></i> Clear
                      </button>
                    </div>
                  </div>
                  <input type="hidden" name="signature" id="signature">
                  <small class="text-danger d-none mt-2" id="signatureError">Please provide your signature.</small>
                  @if ($errors->has('signature'))
                    <small class="text-danger d-block mt-2">{{ $errors->first('signature') }}</small>
                  @endif
                </div>

              </div>

              <div class="col-md-12 px-0 mt-3" align="right">
                <a href="{{ route('marketing-material-releases.index') }}" class="btn btn-light border">Close</a>
                <button type="submit" class="btn btn-primary" id="submitButton">
                  {{ $isEditing ? 'Save Changes' : 'Submit' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('marketingMaterialReleaseForm');
  var canvas = document.getElementById('signatureCanvas');
  var signatureInput = document.getElementById('signature');
  var signatureError = document.getElementById('signatureError');
  var clearButton = document.getElementById('clearSignature');
  var submitButton = document.getElementById('submitButton');
  var isEditing = @json($isEditing);
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

  function groupHasSelection(selector) {
    return document.querySelectorAll(selector + ':checked').length > 0;
  }

  function validateGroup(selector, groupId, errorId) {
    var valid = groupHasSelection(selector);
    document.getElementById(groupId).classList.toggle('border-danger', !valid);
    document.getElementById(errorId).classList.toggle('d-none', valid);
    return valid;
  }

  clearButton.addEventListener('click', function () {
    signaturePad.clear();
    signatureInput.value = '';
    signatureError.classList.add('d-none');
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

    signatureError.classList.toggle('d-none', signatureValid);

    if (!nameUsageValid || !authorizationValid || !signatureValid) {
      event.preventDefault();
      document.querySelector('.border-danger, #signatureError:not(.d-none)').scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }

    if (!signaturePad.isEmpty()) {
      signatureInput.value = signaturePad.toDataURL('image/png');
    }
    submitButton.disabled = true;
    submitButton.innerHTML = isEditing
      ? '<i class="ti-reload mr-1"></i> Saving...'
      : '<i class="ti-reload mr-1"></i> Submitting...';
  });

  resizeCanvas();
  window.addEventListener('resize', resizeCanvas);
});
</script>
