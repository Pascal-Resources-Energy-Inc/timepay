@extends('layouts.header')

@section('content')
<div class="main-panel">
  <div class="content-wrapper ldr-create-page">
    <div class="row">
      <div class="col-12 grid-margin stretch-card">
        <div class="card ldr-form-card">
          <div class="card-body p-3 p-md-4">
              <div class="ldr-page-header d-flex justify-content-between align-items-center">
                  <div class="d-flex flex-column">
                      <div class="d-inline-block mb-1" style="width: fit-content;">
                          <h4 class="card-title mb-1">Layout Design Request</h4>
                          <div class="d-flex">
                              <div class="w-100" style="border-top: 3px solid #59c2e5;"></div>
                              <div class="w-100" style="border-top: 3px solid #fe0001;"></div>
                          </div>
                      </div>
                      <small class="text-muted">CEO-MBD-FOR-001-001.A</small>
                  </div>
                  <a href="{{ route('layout-design-requests.index') }}" class="d-flex align-items-center btn btn-outline-secondary btn-sm ldr-back-button">
                      <i class="ti-arrow-left mr-1"></i>
                      <span>Back</span>
                  </a>

              </div>
             <form action="{{ route('layout-design-requests.store') }}" method="POST" enctype="multipart/form-data" class="ldr-create-form">
               @csrf

               <style>
                 .ldr-create-page {
                   background: #f4f7fb;
                 }
                 .ldr-form-card {
                   overflow: hidden;
                   border: 0;
                   border-radius: .9rem;
                   box-shadow: 0 8px 28px rgba(23, 43, 77, .08);
                 }
                 .ldr-page-header {
                   flex-wrap: wrap;
                   gap: 1rem;
                   padding-bottom: 1.25rem;
                   border-bottom: 1px solid #e6ebf2;
                 }
                 .ldr-back-button {
                   flex: 0 0 auto;
                 }
                 .ldr-create-form {
                   width: 100%;
                 }
                 .ldr-required-note {
                   margin: 1rem 0 0;
                   color: #667085;
                   font-size: .82rem;
                   text-align: right;
                 }
                 .ldr-alert {
                   margin-top: 1rem;
                   border: 0;
                   border-radius: .65rem;
                 }
                 .ldr-form-section {
                   margin-top: 1.25rem;
                   padding: 1.25rem;
                   background: #fff;
                   border: 1px solid #e3e9f1;
                   border-radius: .75rem;
                 }
                 .ldr-section-heading {
                   display: flex;
                   align-items: flex-start;
                   gap: .75rem;
                   margin-bottom: 1.25rem;
                   padding-bottom: 1rem;
                   border-bottom: 1px solid #edf0f5;
                 }
                 .ldr-section-title {
                   margin: 0;
                   color: #17233c;
                   font-size: 1.05rem;
                   font-weight: 700;
                 }
                 .ldr-section-description {
                   display: block;
                   margin-top: .2rem;
                   color: #7a8699;
                 }
                 .ldr-guidance {
                   display: flex;
                   align-items: flex-start;
                   gap: .65rem;
                   margin-bottom: 1.25rem;
                   padding: .8rem .9rem;
                   color: #7a4b00;
                   background: #fff8e5;
                   border: 1px solid #f7dfa4;
                   border-radius: .6rem;
                   line-height: 1.45;
                 }
                 .ldr-guidance i {
                   margin-top: .15rem;
                   color: #d48a00;
                 }
                 .ldr-create-form .form-label {
                   margin-bottom: .45rem;
                   color: #27364f;
                   font-weight: 600;
                 }
                 .ldr-create-form .form-control {
                   min-height: 44px;
                   border-color: #ccd5e2;
                   border-radius: .45rem;
                 }
                 .ldr-create-form textarea.form-control {
                   min-height: auto;
                   resize: vertical;
                 }
                 .ldr-create-form .form-control:focus {
                   border-color: #4b8ee8;
                   box-shadow: 0 0 0 .15rem rgba(75, 142, 232, .15);
                 }
                 .ldr-create-form .row > [class*="col-"] {
                   min-width: 0;
                 }
                 .ldr-create-form .select2-container {
                   display: block;
                   width: 100% !important;
                   max-width: 100%;
                 }
                 .ldr-create-form .select2-selection {
                   max-width: 100%;
                   box-sizing: border-box;
                 }
                 .ldr-checks {
                   display: grid;
                   grid-template-columns: repeat(2, minmax(0, 1fr));
                   gap: .65rem;
                 }
                 .ldr-choice {
                   display: flex;
                   align-items: flex-start;
                   min-width: 0;
                   margin: 0;
                   padding: .75rem .85rem;
                   color: #344054;
                   background: #f8fafc;
                   border: 1px solid #dce3ec;
                   border-radius: .55rem;
                   cursor: pointer;
                   transition: border-color .15s ease, background-color .15s ease, box-shadow .15s ease;
                 }
                 .ldr-choice:hover {
                   border-color: #9bbbea;
                   background: #f3f7fd;
                 }
                 .ldr-choice.is-selected {
                   color: #174f9c;
                   background: #eef5ff;
                   border-color: #4b8ee8;
                   box-shadow: 0 0 0 2px rgba(75, 142, 232, .08);
                 }
                 .ldr-choice input {
                   flex: 0 0 auto;
                   margin: .18rem .55rem 0 0;
                 }
                 .ldr-choice span {
                   min-width: 0;
                   line-height: 1.35;
                 }
                 .peg-attachment-list {
                   padding: .55rem;
                   background: #f7f9fc;
                   border: 1px solid #e1e7ef;
                   border-radius: .45rem;
                 }
                 .peg-attachment-row {
                   display: flex;
                   align-items: stretch;
                   gap: .5rem;
                 }
                 .peg-attachment-row + .peg-attachment-row {
                   margin-top: .5rem;
                 }
                 .peg-attachment-main {
                   display: flex;
                   align-items: center;
                   min-width: 0;
                   flex: 1 1 auto;
                   padding: .45rem .55rem;
                   color: #17233c;
                   background: #dce8fb;
                   border-radius: .4rem;
                   text-decoration: none;
                 }
                 .peg-attachment-main:hover {
                   color: #17233c;
                   background: #cfdef7;
                   text-decoration: none;
                 }
                 .peg-attachment-thumbnail {
                   display: inline-flex;
                   align-items: center;
                   justify-content: center;
                   flex: 0 0 34px;
                   width: 34px;
                   height: 34px;
                   margin-right: .7rem;
                   overflow: hidden;
                   color: #172b57;
                   background: #fff;
                   border: 1px solid #c8d6ec;
                   border-radius: .3rem;
                   font-size: 1rem;
                 }
                 .peg-attachment-thumbnail img {
                   width: 100%;
                   height: 100%;
                   object-fit: cover;
                 }
                 .peg-attachment-name {
                   min-width: 0;
                   flex: 1 1 auto;
                   overflow: hidden;
                   font-weight: 600;
                   text-overflow: ellipsis;
                   white-space: nowrap;
                 }
                 .peg-attachment-size {
                   flex: 0 0 auto;
                   margin-left: 1rem;
                   font-weight: 600;
                   white-space: nowrap;
                 }
                 .peg-attachment-remove {
                   display: inline-flex;
                   align-items: center;
                   justify-content: center;
                   flex: 0 0 46px;
                   width: 46px;
                   color: #536785;
                   background: #dce8fb;
                   border: 0;
                   border-radius: .4rem;
                   font-size: 1rem;
                   cursor: pointer;
                 }
                 .peg-attachment-remove:hover,
                 .peg-attachment-remove:focus {
                   color: #fff;
                   background: #dc3545;
                   outline: none;
                 }
                 .ldr-form-actions {
                   display: flex;
                   justify-content: flex-end;
                   gap: .65rem;
                   margin-top: 1.25rem;
                   padding-top: 1.25rem;
                   border-top: 1px solid #e6ebf2;
                 }
                 .ldr-form-actions .btn {
                   min-width: 120px;
                 }
                 @media (max-width: 767.98px) {
                   .ldr-create-page {
                     padding: 1rem !important;
                   }
                   .ldr-form-section {
                     padding: 1rem;
                   }
                   .ldr-checks {
                     grid-template-columns: 1fr;
                   }
                 }
                 @media (max-width: 575.98px) {
                   .ldr-create-page {
                     padding: .65rem !important;
                   }
                   .ldr-form-card {
                     border-radius: .65rem;
                   }
                   .ldr-page-header {
                     align-items: flex-start !important;
                   }
                   .ldr-page-header h4 {
                     font-size: 1.05rem;
                   }
                   .ldr-back-button span {
                     display: none;
                   }
                   .ldr-back-button .mr-1 {
                     margin-right: 0 !important;
                   }
                   .ldr-required-note {
                     text-align: left;
                   }
                   .ldr-section-heading {
                     gap: .6rem;
                   }
                   .ldr-guidance {
                     font-size: .84rem;
                   }
                   .peg-attachment-row {
                     align-items: stretch;
                   }
                   .peg-attachment-size {
                     display: none;
                   }
                   .ldr-form-actions {
                     flex-direction: column-reverse;
                   }
                   .ldr-form-actions .btn {
                     width: 100%;
                   }
                 }
               </style>

              @if ($errors->any())
              <div class="alert alert-danger ldr-alert" role="alert">
                <div class="font-weight-bold mb-1"><i class="ti-alert mr-1"></i>Please review the highlighted information.</div>
                <ul class="mb-0 pl-3">@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
              </div>
              @endif

              @if (session('error'))
              <div class="alert alert-danger ldr-alert" role="alert">{{ session('error') }}</div>
              @endif

              <section class="ldr-form-section" aria-labelledby="ldr-request-information">
                <div class="ldr-section-heading">
                  <div>
                    <h5 class="ldr-section-title" id="ldr-request-information">Request Information</h5>
                  </div>
                </div>

                <!-- <div class="ldr-guidance"> -->
                <!--   <i class="ti-info-alt" aria-hidden="true"></i> -->
                <!--   <small>This form is for layout design requests from various departments. For long-term projects, please allow at least 3–5 working days before the materials are needed.</small> -->
                <!-- </div> -->

                <div class="row">
                  <div class="col-12 mb-3">
                    <label class="form-label">Requestor's Name <span class="text-danger">*</span></label>
                    <div class="row">
                      <div class="col-12 col-sm-6 mb-2 mb-sm-0">
                        <input type="text" class="form-control {{ $errors->has('requestor_first_name') ? 'is-invalid' : '' }}" name="requestor_first_name" id="requestor_first_name" value="{{ old('requestor_first_name', auth()->user()->employee->first_name ?? '') }}" placeholder="First name" autocomplete="given-name" required>
                      </div>
                      <div class="col-12 col-sm-6">
                        <input type="text" class="form-control {{ $errors->has('requestor_last_name') ? 'is-invalid' : '' }}" name="requestor_last_name" id="requestor_last_name" value="{{ old('requestor_last_name', auth()->user()->employee->last_name ?? '') }}" placeholder="Last name" autocomplete="family-name" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-6 mb-3">
                    <label for="department" class="form-label">Partners/Department <span class="text-danger">*</span></label>
                    @php
                      $employeeDepartment = optional(optional(auth()->user()->employee)->department)->name;
                      $departmentWords = preg_split('/[^A-Za-z0-9]+/', (string) $employeeDepartment, -1, PREG_SPLIT_NO_EMPTY);
                      $departmentAcronym = collect($departmentWords)
                        ->reject(function ($word) {
                          return in_array(strtolower($word), ['and', 'of', 'the'], true);
                        })
                        ->map(function ($word) {
                          return strtoupper(substr($word, 0, 1));
                        })
                        ->implode('');

                      $selectedDepartment = old('department');

                      if (!$selectedDepartment && $employeeDepartment) {
                        $selectedDepartment = collect(\App\LayoutDesign::DEPARTMENTS)
                          ->first(function ($department) use ($employeeDepartment, $departmentAcronym) {
                            if (strcasecmp($department, $employeeDepartment) === 0) {
                              return true;
                            }

                            preg_match('/\(([A-Z]+)\)$/', $department, $matches);
                            return isset($matches[1]) && $matches[1] === $departmentAcronym;
                          });
                      }
                    @endphp
                    <select class="form-control select2" name="department" id="department" required>
                      <option value="">Select department</option>
                      @foreach (\App\LayoutDesign::DEPARTMENTS as $dept)
                      <option value="{{ $dept }}" {{ $selectedDepartment === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-12 col-md-6 mb-3">
                    <label for="requestor_email" class="form-label">Requestor's Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control {{ $errors->has('requestor_email') ? 'is-invalid' : '' }}" name="requestor_email" id="requestor_email" value="{{ old('requestor_email', auth()->user()->email) }}" placeholder="name@example.com" autocomplete="email" required>
                  </div>
                </div>
              </section>

              <section class="ldr-form-section" aria-labelledby="ldr-design-details">
                <div class="ldr-section-heading">
                  <div>
                    <h5 class="ldr-section-title" id="ldr-design-details">Design Details</h5>
                    <small class="ldr-section-description">Provide enough detail for the design team to understand the expected output.</small>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 col-md-6 mb-3">
                    <label for="medium_type" class="form-label">Medium Type <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="medium_type" id="medium_type" required>
                      <option value="">Select medium type</option>
                      @foreach (\App\LayoutDesign::MEDIUM_TYPES as $medium)
                      <option value="{{ $medium }}" {{ old('medium_type') === $medium ? 'selected' : '' }}>{{ $medium }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-12 col-md-6 mb-3">
                    <label for="date_needed" class="form-label">
                        Preferred Date Needed
                        <span>
                            <small>(optional)</small>
                        </span>
                    </label>
                    <input type="date" class="form-control {{ $errors->has('date_needed') ? 'is-invalid' : '' }}" name="date_needed" id="date_needed" min="{{ date('Y-m-d') }}" value="{{ old('date_needed') }}">
                  </div>

                  <div class="col-12 mb-4">
                    <label class="form-label d-block">Intended Use <span class="text-danger">*</span></label>
                    <small class="text-muted d-block mb-2">Select at least one option.</small>
                    <div class="ldr-checks" data-choice-group="intended_use">
                      @foreach (\App\LayoutDesign::INTENDED_USES as $use)
                      <label class="ldr-choice" for="iu_{{ $loop->index }}">
                        <input type="checkbox" name="intended_use[]" value="{{ $use }}" id="iu_{{ $loop->index }}" {{ in_array($use, old('intended_use', []), true) ? 'checked' : '' }}>
                        <span>{{ $use }}</span>
                      </label>
                      @endforeach
                    </div>
                    @if ($errors->has('intended_use'))
                      <small class="text-danger d-block mt-2">{{ $errors->first('intended_use') }}</small>
                    @endif
                  </div>

                  <div class="col-12 mb-4">
                    <label class="form-label d-block">Marketing Collateral Type <span class="text-danger">*</span></label>
                    @php
                      $selectedCollateralTypes = (array) old('marketing_collateral_type', []);
                      $storeSignageSelected = in_array('Store Signage', $selectedCollateralTypes, true);
                    @endphp
                    <div class="ldr-checks" data-choice-group="marketing_collateral_type">
                      @foreach (\App\LayoutDesign::COLLATERAL_TYPES as $collateral)
                      <label class="ldr-choice" for="mct_{{ $loop->index }}">
                        <input type="checkbox" name="marketing_collateral_type[]" value="{{ $collateral }}" id="mct_{{ $loop->index }}" {{ in_array($collateral, $selectedCollateralTypes, true) ? 'checked' : '' }}>
                        <span>{{ $collateral }}</span>
                      </label>
                      @endforeach
                    </div>
                    <!-- <div id="store_signage_options" class="mt-3 p-3 bg-light border rounded {{ $storeSignageSelected ? '' : 'd-none' }}"> -->
                    <!--   <label class="form-label d-block mb-2">Store Signage Option <span class="text-danger">*</span></label> -->
                    <!--   <div class="ldr-checks"> -->
                    <!--     @foreach (\App\LayoutDesign::STORE_SIGNAGE_OPTIONS as $option) -->
                    <!--     <label class="ldr-choice" for="store_signage_option_{{ $loop->index }}"> -->
                    <!--       <input type="radio" name="store_signage_option" value="{{ $option }}" id="store_signage_option_{{ $loop->index }}" {{ old('store_signage_option') === $option ? 'checked' : '' }}> -->
                    <!--       <span>{{ $option }}</span> -->
                    <!--     </label> -->
                    <!--     @endforeach -->
                    <!--   </div> -->
                    <!---->
                    <!--   <figure class="mt-3 mb-0"> -->
                    <!--     <img -->
                    <!--       src="{{ asset('images/store_signage_options.jpg') }}" -->
                    <!--       alt="Reference showing the two available store signage options, their inclusions, exclusions, and price" -->
                    <!--       class="img-fluid w-100 rounded border" -->
                    <!--       loading="lazy" -->
                    <!--     > -->
                    <!--     <figcaption class="small text-muted mt-2">Store signage options reference</figcaption> -->
                    <!--   </figure> -->
                    <!---->
                    <!--   @if ($errors->has('store_signage_option')) -->
                    <!--     <small class="text-danger d-block mt-2">{{ $errors->first('store_signage_option') }}</small> -->
                    <!--   @endif -->
                    <!-- </div> -->
                    @if ($errors->has('marketing_collateral_type'))
                      <small class="text-danger d-block mt-2">{{ $errors->first('marketing_collateral_type') }}</small>
                    @endif
                  </div>

                  <div class="col-12 mb-3">
                      <label for="project_description" class="form-label">Project Description <span class="text-danger">*</span></label>
                      <textarea style="resize: none;" class="form-control {{ $errors->has('project_description') ? 'is-invalid' : '' }}" id="project_description" name="project_description" rows="5" placeholder="Include the text, preferred size, quantity, visual direction, and other requirements" required>{{ old('project_description') }}</textarea>
                  </div>

                  <div class="col-12 mb-3">
                    <label for="purpose_of_layout_request" class="form-label">Purpose of the Layout Request <span class="text-danger">*</span></label>
                    <textarea style="resize: none;" class="form-control {{ $errors->has('purpose_of_layout_request') ? 'is-invalid' : '' }}" id="purpose_of_layout_request" name="purpose_of_layout_request" rows="3" placeholder="Explain where and how the material will be used" required>{{ old('purpose_of_layout_request') }}</textarea>
                  </div>

                  <div class="col-12 mb-0">
                    <label for="peg_samples" class="form-label">Peg Sample Files <span class="text-muted font-weight-normal">(Hold CTRL for multiple selection)</span></label>
                    <input type="file" class="form-control" name="peg_samples[]" id="peg_samples" multiple accept="image/*,video/mp4,.pdf,.doc,.docx,.ppt,.pptx" aria-describedby="peg_samples_help peg_file_error">
                    <small class="text-muted d-block mt-1" id="peg_samples_help">Up to 6 files, 30 MB each. Accepted: JPG/JPEG, PNG, WebP, MP4, PDF, Word, and PowerPoint.</small>
                    <small id="peg_file_error" class="text-danger d-none" role="alert"></small>
                    <div id="peg_preview" class="peg-attachment-list mt-2 d-none" aria-live="polite"></div>

                    <div class="mt-3">
                      <label for="peg_sample_link" class="form-label">Or link</label>
                      <input type="url" class="form-control {{ $errors->has('peg_sample_link') ? 'is-invalid' : '' }}" name="peg_sample_link" id="peg_sample_link" value="{{ old('peg_sample_link') }}" maxlength="255" placeholder="https://drive.google.com/...">
                      <small class="text-muted d-block mt-1">e.g., Google Drive or Canva link. Make sure the design team has permission to open the link.</small>
                      @if ($errors->has('peg_sample_link'))
                        <div class="invalid-feedback">{{ $errors->first('peg_sample_link') }}</div>
                      @endif
                    </div>
                  </div>
                </div>
              </section>

              <div class="ldr-form-actions">
                <a href="{{ route('layout-design-requests.index') }}" class="btn btn-light border">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit Request</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('.select2').select2({
      width: '100%'
    });

    var requiredChoiceGroups = {
      intended_use: 'Select at least one intended use.',
      marketing_collateral_type: 'Select at least one marketing collateral type.'
    };

    function validateChoiceGroup(groupName) {
      var inputs = $('input[name="' + groupName + '[]"]');
      var hasSelection = inputs.is(':checked');

      inputs.each(function() {
        this.setCustomValidity('');
      });

      if (!hasSelection && inputs.length) {
        inputs.get(0).setCustomValidity(requiredChoiceGroups[groupName]);
      }
    }

    $('.ldr-choice input').each(function() {
      $(this).closest('.ldr-choice').toggleClass('is-selected', this.checked);
    });

    Object.keys(requiredChoiceGroups).forEach(validateChoiceGroup);

    var storeSignageCheckbox = $('input[name="marketing_collateral_type[]"][value="Store Signage"]');
    var storeSignageOptions = $('#store_signage_options');
    var storeSignageRadios = $('input[name="store_signage_option"]');

    function syncStoreSignageOptions() {
      var isSelected = storeSignageCheckbox.is(':checked');

      storeSignageOptions.toggleClass('d-none', !isSelected);
      storeSignageRadios.prop('disabled', !isSelected).prop('required', isSelected);

      if (!isSelected) {
        storeSignageRadios.prop('checked', false);
        storeSignageRadios.closest('.ldr-choice').removeClass('is-selected');
      }
    }

    syncStoreSignageOptions();
    storeSignageCheckbox.on('change', syncStoreSignageOptions);

    $('.ldr-choice input').on('change', function() {
      var groupName = this.name.replace('[]', '');

      $(this).closest('.ldr-choice').toggleClass('is-selected', this.checked);
      validateChoiceGroup(groupName);
    });

    var selectedPegFiles = [];
    var pegObjectUrls = [];

    function releasePegObjectUrls() {
      pegObjectUrls.forEach(function(url) {
        URL.revokeObjectURL(url);
      });

      pegObjectUrls = [];
    }

    function formatPegFileSize(bytes) {
      if (bytes >= 1048576) {
        return (bytes / 1048576).toFixed(1) + ' MB';
      }

      return Math.max(bytes / 1024, 0.1).toFixed(1) + ' KB';
    }

    function syncPegInput() {
      var transfer = new DataTransfer();

      selectedPegFiles.forEach(function(file) {
        transfer.items.add(file);
      });

      document.getElementById('peg_samples').files = transfer.files;
    }

    function validatePegFiles() {
      var input = document.getElementById('peg_samples');
      var messages = [];
      var oversizedFiles = selectedPegFiles.filter(function(file) {
        return file.size > 30 * 1024 * 1024;
      });

      if (selectedPegFiles.length > 6) {
        messages.push('You may upload up to 6 peg samples.');
      }

      if (oversizedFiles.length) {
        messages.push('Each peg sample must not exceed 30 MB.');
      }

      var message = messages.join(' ');
      input.setCustomValidity(message);
      $('#peg_file_error').text(message).toggleClass('d-none', !message);
    }

    function renderPegPreview() {
      var preview = $('#peg_preview');

      releasePegObjectUrls();
      preview.empty();

      if (!selectedPegFiles.length) {
        preview.addClass('d-none');
        return;
      }

      selectedPegFiles.forEach(function(file, index) {
        var objectUrl = URL.createObjectURL(file);
        var thumbnail = $('<span>', {
          class: 'peg-attachment-thumbnail'
        });

        pegObjectUrls.push(objectUrl);

        if (file.type.startsWith('image/')) {
          thumbnail.append($('<img>', {
            src: objectUrl,
            alt: ''
          }));
        } else if (file.type === 'video/mp4') {
          thumbnail.append($('<i>', { class: 'ti-video-camera' }));
        } else {
          thumbnail.append($('<i>', { class: 'ti-folder' }));
        }

        var fileLink = $('<a>', {
          class: 'peg-attachment-main',
          href: objectUrl,
          target: '_blank',
          rel: 'noopener noreferrer',
          title: 'Open ' + file.name
        })
          .append(thumbnail)
          .append($('<span>', {
            class: 'peg-attachment-name',
            text: file.name,
            title: file.name
          }))
          .append($('<span>', {
            class: 'peg-attachment-size',
            text: formatPegFileSize(file.size)
          }));

        var removeButton = $('<button>', {
          type: 'button',
          class: 'peg-attachment-remove',
          title: 'Remove ' + file.name,
          'aria-label': 'Remove ' + file.name,
          'data-file-index': index
        }).append($('<i>', { class: 'ti-trash' }));

        preview.append(
          $('<div>', { class: 'peg-attachment-row' })
            .append(fileLink)
            .append(removeButton)
        );
      });

      preview.removeClass('d-none');
    }

    $('#peg_samples').on('change', function() {
      selectedPegFiles = Array.prototype.slice.call(this.files || []);
      validatePegFiles();
      renderPegPreview();
    });

    $('#peg_preview').on('click', '.peg-attachment-remove', function() {
      var fileIndex = Number($(this).attr('data-file-index'));

      selectedPegFiles.splice(fileIndex, 1);
      syncPegInput();
      validatePegFiles();
      renderPegPreview();
    });

    $(window).on('beforeunload', function() {
      releasePegObjectUrls();
    });
  });
</script>
