@extends('layouts.header')

@section('content')
<div class="main-panel">
  <div class="content-wrapper ldr-edit-page">
    <div class="row justify-content-center">
      <div class="col-12 col-xl-11 grid-margin stretch-card">
        <div class="card ldr-form-card">
          <div class="card-body p-3 p-md-4 p-xl-5">
            <div class="ldr-page-header d-flex flex-wrap justify-content-between align-items-center">
              <div class="d-flex flex-column">
                <div class="d-inline-block mb-1" style="width: fit-content;">
                  <h4 class="card-title mb-1">Edit Layout Design Request</h4>
                  <div class="d-flex">
                    <div class="w-100" style="border-top: 3px solid #59c2e5;"></div>
                    <div class="w-100" style="border-top: 3px solid #fe0001;"></div>
                  </div>
                </div>
                <small class="text-muted">{{ $request->reference_no }}</small>
              </div>

              <a href="{{ route('layout-design-requests.show', $request->id) }}" class="d-flex align-items-center btn btn-outline-secondary btn-sm ldr-back-button">
                <i class="ti-arrow-left mr-1"></i>
                <span>Back</span>
              </a>
            </div>

            <div class="alert alert-warning ldr-alert" role="alert">
              <i class="ti-info-alt mr-1" aria-hidden="true"></i>
              You can edit this request while it is still pending approval.
            </div>

            @if ($errors->any())
            <div class="alert alert-danger ldr-alert" role="alert">
              <strong>Please check the information below.</strong>
              <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger ldr-alert" role="alert">{{ session('error') }}</div>
            @endif

            @php
              $selectedUses = old('intended_use', $request->intended_use ?? []);
              $selectedCollateralTypes = old('marketing_collateral_type', $request->marketing_collateral_type ?? []);
              $selectedStoreSignageOption = old(
                'store_signage_option',
                collect((array) ($request->marketing_collateral_type ?? []))
                  ->first(function ($item) {
                    return in_array($item, \App\LayoutDesign::STORE_SIGNAGE_OPTIONS, true);
                  })
              );
            @endphp

            <form action="{{ route('layout-design-requests.update', $request->id) }}" method="POST" enctype="multipart/form-data" class="ldr-edit-form">
              @csrf
              @method('PUT')

              <style>
                .ldr-edit-page {
                  background: #f4f7fb;
                }
                .ldr-form-card {
                  overflow: hidden;
                  border: 0;
                  border-radius: .9rem;
                  box-shadow: 0 8px 28px rgba(23, 43, 77, .08);
                }
                .ldr-page-header {
                  gap: 1rem;
                  padding-bottom: 1.25rem;
                  border-bottom: 1px solid #e6ebf2;
                }
                .ldr-back-button {
                  flex: 0 0 auto;
                }
                .ldr-edit-form {
                  max-width: 1120px;
                  margin: 0 auto;
                }
                .ldr-alert {
                  max-width: 1120px;
                  margin: 1rem auto 0;
                  border: 0;
                  border-radius: .65rem;
                }
                .ldr-required-note {
                  margin: 1rem 0 0;
                  color: #667085;
                  font-size: .82rem;
                  text-align: right;
                }
                .ldr-form-section {
                  margin-top: 1.25rem;
                  padding: 1.25rem;
                  background: #fff;
                  border: 1px solid #e3e9f1;
                  border-radius: .75rem;
                }
                .ldr-section-heading {
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
                .ldr-edit-form .form-label {
                  margin-bottom: .45rem;
                  color: #27364f;
                  font-weight: 600;
                }
                .ldr-edit-form .form-control {
                  min-height: 44px;
                  border-color: #ccd5e2;
                  border-radius: .45rem;
                }
                .ldr-edit-form textarea.form-control {
                  min-height: auto;
                  resize: vertical;
                }
                .ldr-edit-form .form-control:focus {
                  border-color: #4b8ee8;
                  box-shadow: 0 0 0 .15rem rgba(75, 142, 232, .15);
                }
                .ldr-edit-form .row > [class*="col-"] {
                  min-width: 0;
                }
                .ldr-edit-form .select2-container {
                  display: block;
                  width: 100% !important;
                  max-width: 100%;
                }
                .ldr-edit-form .select2-selection {
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
                .ldr-current-file {
                  padding: .75rem;
                  background: #f8f9fa;
                  border: 1px solid #e1e7ef;
                  border-radius: .55rem;
                }
                .ldr-current-file-row {
                  display: flex;
                  align-items: center;
                  justify-content: space-between;
                  gap: .75rem;
                  padding: .45rem 0;
                  border-bottom: 1px solid #e5e7eb;
                }
                .ldr-current-file-row:last-child {
                  border-bottom: 0;
                }
                .ldr-current-file-link {
                  min-width: 0;
                  flex: 1 1 auto;
                  overflow-wrap: anywhere;
                }
                .ldr-replacement-status {
                  display: block;
                  margin-top: .2rem;
                  color: #6c757d;
                  overflow-wrap: anywhere;
                }
                .ldr-file-actions {
                  display: flex;
                  align-items: center;
                  gap: .4rem;
                }
                .ldr-current-file-row.is-pending-removal .ldr-current-file-link a {
                  opacity: .55;
                  text-decoration: line-through;
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
                  min-width: 140px;
                }
                @media (max-width: 767.98px) {
                  .ldr-edit-page {
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
                  .ldr-edit-page {
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
                  .ldr-current-file-row {
                    align-items: stretch;
                    flex-direction: column;
                  }
                  .ldr-file-actions {
                    width: 100%;
                  }
                  .ldr-file-actions .btn,
                  .ldr-file-actions .ldr-replace-label {
                    flex: 1 1 0;
                    text-align: center;
                  }
                  .ldr-form-actions {
                    flex-direction: column-reverse;
                  }
                  .ldr-form-actions .btn {
                    width: 100%;
                  }
                }
              </style>

              <section class="ldr-form-section" aria-labelledby="ldr-edit-request-information">
                <div class="ldr-section-heading">
                  <h5 class="ldr-section-title" id="ldr-edit-request-information">Request Information</h5>
                </div>

                <div class="row">
                <div class="col-12 mb-3">
                  <label class="form-label">Requestor's Name <span class="text-danger">*</span></label>
                  <div class="row">
                    <div class="col-12 col-sm-6 mb-2 mb-sm-0">
                      <input type="text" class="form-control {{ $errors->has('requestor_first_name') ? 'is-invalid' : '' }}" name="requestor_first_name" id="requestor_first_name" value="{{ old('requestor_first_name', $request->requestor_first_name) }}" placeholder="First name" autocomplete="given-name" required>
                    </div>
                    <div class="col-12 col-sm-6">
                      <input type="text" class="form-control {{ $errors->has('requestor_last_name') ? 'is-invalid' : '' }}" name="requestor_last_name" id="requestor_last_name" value="{{ old('requestor_last_name', $request->requestor_last_name) }}" placeholder="Last name" autocomplete="family-name" required>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-6 mb-3">
                  <label for="department" class="form-label">Partners/Department <span class="text-danger">*</span></label>
                  <select class="form-control select2" name="department" id="department" required>
                    <option value="">Select department</option>
                    @foreach (\App\LayoutDesign::DEPARTMENTS as $department)
                    <option value="{{ $department }}" {{ old('department', $request->department) === $department ? 'selected' : '' }}>{{ $department }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-12 col-md-6 mb-3">
                  <label for="requestor_email" class="form-label">Requestor's Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control {{ $errors->has('requestor_email') ? 'is-invalid' : '' }}" name="requestor_email" id="requestor_email" value="{{ old('requestor_email', $request->requestor_email) }}" placeholder="name@example.com" autocomplete="email" required>
                </div>
                </div>
              </section>

              <section class="ldr-form-section" aria-labelledby="ldr-edit-design-details">
                <div class="ldr-section-heading">
                  <h5 class="ldr-section-title" id="ldr-edit-design-details">Design Details</h5>
                  <small class="ldr-section-description">Update the requirements and attached references for this request.</small>
                </div>

                <div class="row">
                <div class="col-12 col-md-6 mb-3">
                  <label for="medium_type" class="form-label">Medium Type <span class="text-danger">*</span></label>
                  <select class="form-control select2" name="medium_type" id="medium_type" required>
                    <option value="">Select medium type</option>
                    @foreach (\App\LayoutDesign::MEDIUM_TYPES as $medium)
                    <option value="{{ $medium }}" {{ old('medium_type', $request->medium_type) === $medium ? 'selected' : '' }}>{{ $medium }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-12 col-md-6 mb-3">
                  <label for="date_needed" class="form-label">Preferred Date Needed <span><small>(optional)</small></span></label>
                  <input type="date" class="form-control {{ $errors->has('date_needed') ? 'is-invalid' : '' }}" name="date_needed" id="date_needed" value="{{ old('date_needed', optional($request->date_needed)->format('Y-m-d')) }}">
                </div>

                <div class="col-12 mb-4">
                  <label class="form-label d-block">Intended Use <span class="text-danger">*</span></label>
                  <small class="text-muted d-block mb-2">Select at least one option.</small>
                  <div class="ldr-checks" data-choice-group="intended_use">
                    @foreach (\App\LayoutDesign::INTENDED_USES as $use)
                    <label class="ldr-choice" for="iu_{{ $loop->index }}">
                      <input type="checkbox" name="intended_use[]" value="{{ $use }}" id="iu_{{ $loop->index }}" {{ in_array($use, (array) $selectedUses) ? 'checked' : '' }}>
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
                  <div class="ldr-checks" data-choice-group="marketing_collateral_type">
                    @foreach (\App\LayoutDesign::COLLATERAL_TYPES as $collateral)
                    <label class="ldr-choice" for="mct_{{ $loop->index }}">
                      <input type="checkbox" name="marketing_collateral_type[]" value="{{ $collateral }}" id="mct_{{ $loop->index }}" {{ in_array($collateral, (array) $selectedCollateralTypes) ? 'checked' : '' }}>
                      <span>{{ $collateral }}</span>
                    </label>
                    @endforeach
                  </div>
                  <div id="store_signage_options" class="mt-3 p-3 bg-light border rounded {{ in_array('Store Signage', (array) $selectedCollateralTypes, true) ? '' : 'd-none' }}">
                    <label class="form-label d-block mb-2">Store Signage Option <span class="text-danger">*</span></label>
                    <div class="ldr-checks">
                      @foreach (\App\LayoutDesign::STORE_SIGNAGE_OPTIONS as $option)
                      <label class="ldr-choice" for="store_signage_option_{{ $loop->index }}">
                        <input type="radio" name="store_signage_option" value="{{ $option }}" id="store_signage_option_{{ $loop->index }}" {{ $selectedStoreSignageOption === $option ? 'checked' : '' }}>
                        <span>{{ $option }}</span>
                      </label>
                      @endforeach
                    </div>
                    @if ($errors->has('store_signage_option'))
                      <small class="text-danger d-block mt-2">{{ $errors->first('store_signage_option') }}</small>
                    @endif
                  </div>
                  @if ($errors->has('marketing_collateral_type'))
                    <small class="text-danger d-block mt-2">{{ $errors->first('marketing_collateral_type') }}</small>
                  @endif
                </div>

                <div class="col-12 mb-3">
                  <label for="purpose_of_layout_request" class="form-label">Purpose of the Layout Request <span class="text-danger">*</span></label>
                  <textarea style="resize: none;" class="form-control {{ $errors->has('purpose_of_layout_request') ? 'is-invalid' : '' }}" id="purpose_of_layout_request" name="purpose_of_layout_request" rows="3" placeholder="Explain where and how the material will be used" required>{{ old('purpose_of_layout_request', $request->purpose_of_layout_request) }}</textarea>
                </div>

                <div class="col-12 mb-3">
                  <label for="project_description" class="form-label">Project Description <span class="text-danger">*</span></label>
                  <textarea style="resize: none;" class="form-control {{ $errors->has('project_description') ? 'is-invalid' : '' }}" id="project_description" name="project_description" rows="5" placeholder="Include the text, preferred size, quantity, visual direction, and other requirements" required>{{ old('project_description', $request->project_description) }}</textarea>
                </div>

                <div class="col-12 mb-0">
                  <label for="peg_sample" class="form-label">Peg Samples <span class="text-muted font-weight-normal">(optional)</span></label>

                  @php
                    $existingPegSampleLink = $request->pegSamples->first(function ($pegSample) {
                      return $pegSample->isExternalLink();
                    });
                    $filePegSamples = $request->pegSamples->reject(function ($pegSample) {
                      return $pegSample->isExternalLink();
                    });
                  @endphp

                  @if ($filePegSamples->isNotEmpty())
                  <div class="ldr-current-file mb-2">
                    <small class="text-muted d-block mb-1">Current files ({{ $filePegSamples->count() }})</small>
                    @foreach ($filePegSamples as $pegSample)
                      <div class="ldr-current-file-row">
                        <div class="ldr-current-file-link">
                          <a href="{{ $pegSample->public_url }}" target="_blank" rel="noopener noreferrer" class="text-info font-weight-bold">
                            <i class="{{ $pegSample->isExternalLink() ? 'ti-link' : (strpos((string) $pegSample->mime_type, 'video/') === 0 ? 'ti-video-camera' : 'ti-file') }} mr-1"></i>{{ $pegSample->display_name }}
                          </a>
                          <small id="replacement_status_{{ $pegSample->id }}" class="ldr-replacement-status d-none"></small>
                        </div>
                        <div class="ldr-file-actions flex-shrink-0">
                          <label for="replace_peg_sample_{{ $pegSample->id }}" class="btn btn-sm btn-outline-primary mb-0 ldr-replace-label">
                            <i class="ti-reload mr-1"></i>Replace
                          </label>
                          <input
                            type="file"
                            class="d-none ldr-replacement-input"
                            name="peg_sample_replacements[{{ $pegSample->id }}]"
                            id="replace_peg_sample_{{ $pegSample->id }}"
                            data-status-target="#replacement_status_{{ $pegSample->id }}"
                            accept="image/*,video/mp4,.pdf,.doc,.docx,.ppt,.pptx"
                          >
                          <button
                            type="button"
                            class="btn btn-sm btn-outline-danger ldr-remove-button"
                            data-remove-target="#remove_peg_sample_{{ $pegSample->id }}"
                            data-replace-input="#replace_peg_sample_{{ $pegSample->id }}"
                            data-status-target="#replacement_status_{{ $pegSample->id }}"
                          >
                            <i class="ti-trash mr-1"></i>Remove
                          </button>
                          <input type="checkbox" class="d-none ldr-remove-input" name="remove_peg_samples[]" value="{{ $pegSample->id }}" id="remove_peg_sample_{{ $pegSample->id }}">
                        </div>
                      </div>
                    @endforeach
                  </div>
                  @elseif ($request->peg_sample_path && (!$existingPegSampleLink || $request->peg_sample_path !== $existingPegSampleLink->output_attachment_path))
                  <div class="ldr-current-file mb-2">
                    <small class="text-muted d-block mb-1">Current file</small>
                    <div class="ldr-current-file-row">
                      <div class="ldr-current-file-link">
                        <a href="{{ asset(ltrim($request->peg_sample_path, '/')) }}" target="_blank" rel="noopener noreferrer" class="text-info font-weight-bold">
                          <i class="ti-file mr-1"></i>{{ basename($request->peg_sample_path) }}
                        </a>
                        <small id="legacy_replacement_status" class="ldr-replacement-status d-none"></small>
                      </div>
                      <div class="ldr-file-actions flex-shrink-0">
                        <label for="peg_sample" class="btn btn-sm btn-outline-primary mb-0 ldr-replace-label">
                          <i class="ti-reload mr-1"></i>Replace
                        </label>
                        <input type="file" class="d-none ldr-replacement-input" name="peg_sample" id="peg_sample" data-status-target="#legacy_replacement_status" accept="image/*,video/mp4,.pdf,.doc,.docx,.ppt,.pptx">
                        <button
                          type="button"
                          class="btn btn-sm btn-outline-danger ldr-remove-button"
                          data-remove-target="#remove_legacy_peg_sample"
                          data-replace-input="#peg_sample"
                          data-status-target="#legacy_replacement_status"
                        >
                          <i class="ti-trash mr-1"></i>Remove
                        </button>
                        <input type="checkbox" class="d-none ldr-remove-input" name="remove_legacy_peg_sample" value="1" id="remove_legacy_peg_sample">
                      </div>
                    </div>
                  </div>
                  @else
                    <input type="file" class="form-control ldr-replacement-input" name="peg_sample" id="peg_sample" data-status-target="#new_peg_sample_status" accept="image/*,video/mp4,.pdf,.doc,.docx,.ppt,.pptx">
                    <small id="new_peg_sample_status" class="ldr-replacement-status d-none"></small>
                  @endif

                  <small class="text-muted d-block mt-1">Replace or remove individual files. Other files remain attached. Changes apply after you select Update Request. Maximum 30 MB per replacement.</small>

                  <div class="form-group mt-3 mb-0">
                    <label for="peg_sample_link" class="form-label">Upload link</label>
                    <input
                      type="url"
                      class="form-control {{ $errors->has('peg_sample_link') ? 'is-invalid' : '' }}"
                      name="peg_sample_link"
                      id="peg_sample_link"
                      value="{{ old('peg_sample_link', optional($existingPegSampleLink)->output_attachment_path) }}"
                      maxlength="255"
                      placeholder="https://drive.google.com/..."
                    >
                    @if ($errors->has('peg_sample_link'))
                      <div class="invalid-feedback">{{ $errors->first('peg_sample_link') }}</div>
                    @endif
                    <small class="text-muted d-block mt-1">
                      Update the current link
                    </small>
                  </div>
                </div>
              </div>
              </section>

              <div class="ldr-form-actions">
                <a href="{{ route('layout-design-requests.show', $request->id) }}" class="btn btn-light border">Cancel</a>
                <button type="submit" class="btn btn-primary">
                  Update Request
                </button>
              </div>
            </form>

            <form id="cancelForm" method="POST" class="d-none">
              @csrf
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('.select2').select2({ width: '100%' });

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

    $('.ldr-replacement-input').on('change', function() {
      var file = this.files && this.files.length ? this.files[0] : null;
      var status = $($(this).data('status-target'));

      if (!file) {
        this.setCustomValidity('');
        status.text('').addClass('d-none').removeClass('text-danger');
        return;
      }

      if (file.size > 30 * 1024 * 1024) {
        this.setCustomValidity('The replacement must not exceed 30 MB.');
        status.text('File exceeds the 30 MB limit.').removeClass('d-none').addClass('text-danger');
        return;
      }

      this.setCustomValidity('');
      status.text('Replacement selected: ' + file.name).removeClass('d-none text-danger');
    });

    $('.ldr-remove-button').on('click', function() {
      var button = $(this);
      var removeInput = $(button.data('remove-target'));
      var replaceInput = $(button.data('replace-input'));
      var replaceLabel = replaceInput.siblings('.ldr-replace-label');
      var status = $(button.data('status-target'));
      var row = button.closest('.ldr-current-file-row');
      var isRemoving = !removeInput.prop('checked');

      removeInput.prop('checked', isRemoving);

      if (isRemoving) {
        replaceInput.val('').prop('disabled', true);
        replaceInput.get(0).setCustomValidity('');
        replaceLabel.addClass('disabled').attr('aria-disabled', 'true');
        status.text('Marked for removal.').removeClass('d-none').addClass('text-danger');
        row.addClass('is-pending-removal');
        button
          .removeClass('btn-outline-danger')
          .addClass('btn-outline-secondary')
          .html('<i class="ti-back-left mr-1"></i>Undo');
        return;
      }

      replaceInput.prop('disabled', false);
      replaceLabel.removeClass('disabled').removeAttr('aria-disabled');
      status.text('').addClass('d-none').removeClass('text-danger');
      row.removeClass('is-pending-removal');
      button
        .removeClass('btn-outline-secondary')
        .addClass('btn-outline-danger')
        .html('<i class="ti-trash mr-1"></i>Remove');
    });

    $('.btn-cancel').on('click', function() {
      var id = $(this).data('id');
      var url = "{{ route('layout-design-requests.cancel', ':id') }}".replace(':id', id);

      Swal.fire({
        title: 'Cancel this request?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, cancel request',
        cancelButtonText: 'Keep request'
      }).then(function(result) {
        if (result.isConfirmed) {
          $('#cancelForm').attr('action', url).submit();
        }
      });
    });
  });
</script>
