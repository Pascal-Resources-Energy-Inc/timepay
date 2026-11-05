@extends('layouts.header')

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Edit ID & Uniform Request</h4>
        <hr>

        <form action="{{ route('iur.update', $iur->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
          </div>
          @endif

          @if (session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
          @endif

          @php
            $requestFor = old('request_for', $iur->request_for);
            $standardSizes = ['S', 'M', 'L', 'XL', 'XXL'];
            $selectedSize = old(
              'size',
              in_array($iur->size, $standardSizes, true)
                ? $iur->size
                : ($iur->size ? 'other' : '')
            );
            $customSize = old(
              'other_size',
              in_array($iur->size, $standardSizes, true) ? '' : $iur->size
            );
          @endphp

          <!-- info row -->
          <div class="row">
            <div class="col-md-6 mb-2">
              <p class="mb-0"><strong>Type:</strong> {{ $iur->type }}</p>
              <input type="hidden" name="type" value="{{ $iur->type }}">
            </div>
            <div class="col-md-6 mb-2">
              <p class="mb-0"><strong>Work Location:</strong> {{ $iur->work_location }}</p>
              <input type="hidden" name="work_location" value="{{ $iur->work_location }}">
            </div>
            <div class="col-md-6 mb-3">
              <label for="request_for" class="form-label">Request For&nbsp;<span class="text-danger">*</span></label>
              <select name="request_for" id="request_for" class="form-control select2" required>
                <option value="">-- Select Request --</option>
                <option value="Uniform" {{ $requestFor === 'Uniform' ? 'selected' : '' }}>Uniform</option>
                <option value="ID" {{ $requestFor === 'ID' ? 'selected' : '' }}>ID</option>
                <option value="Both" {{ $requestFor === 'Both' ? 'selected' : '' }}>Both</option>
              </select>
            </div>
            <div class="col-md-6 mb-3" id="sizeField">
              <label for="size" class="form-label">Size&nbsp;<span class="text-danger">*</span></label>
              <select name="size" id="size" class="form-control select2">
                <option value="">-- Select Size --</option>
                @foreach($standardSizes as $size)
                <option value="{{ $size }}" {{ $selectedSize === $size ? 'selected' : '' }}>{{ $size }}</option>
                @endforeach
                <option value="other" {{ $selectedSize === 'other' ? 'selected' : '' }}>Other</option>
              </select>

              <div id="other_size_div" class="mt-2" style="{{ $selectedSize === 'other' ? '' : 'display:none;' }}">
                <label for="other_size">Please specify your size</label>
                <input type="text"
                  name="other_size"
                  id="other_size"
                  class="form-control"
                  maxlength="10"
                  value="{{ $customSize }}"
                  placeholder="Specify size">
              </div>
            </div>
          </div>

          <hr />

          <!-- DETAILS -->
          <div class="row">
            <div class="col-md-12 mb-3">
              <label>Details of Work Assignment&nbsp;<span class="text-danger">*</span></label>
              <textarea name="details" class="form-control" rows="2" required>{{ $iur->details }}</textarea>
            </div>
          </div>

          {{-- UNIFORM SECTION --}}
          <div id="uniformRequestSection" style="{{ in_array($requestFor, ['Uniform', 'Both'], true) ? '' : 'display:none;' }}">
          @php
            $uniformAllocations = [
              'HO' => [
                'label' => 'Head Office: 2 Polo, 1 White, and 1 Black uniform.',
                'limits' => ['prod_qty' => 0, 'white_qty' => 1, 'black_qty' => 1, 'collar_qty' => 2],
              ],
              'Plant' => [
                'label' => 'Plant: 5 Production and 1 White uniform.',
                'limits' => ['prod_qty' => 5, 'white_qty' => 1, 'black_qty' => 0, 'collar_qty' => 0],
              ],
              'Guinobatan Warehouse / Lubao Office' => [
                'label' => 'Guinobatan Warehouse / Lubao Office: 3 Polo, 1 White, and 1 Black uniform.',
                'limits' => ['prod_qty' => 0, 'white_qty' => 1, 'black_qty' => 1, 'collar_qty' => 3],
              ],
              'Field' => [
                'label' => 'Field: 3 Polo and 3 Black uniforms.',
                'limits' => ['prod_qty' => 0, 'white_qty' => 0, 'black_qty' => 3, 'collar_qty' => 3],
              ],
            ];
            $uniformItems = [
              'prod_qty' => ['label' => 'Production', 'image' => 'images/icons/image4.png', 'class' => 'qty-prod'],
              'white_qty' => ['label' => 'White', 'image' => 'images/icons/image1.jpg', 'class' => 'qty-white'],
              'black_qty' => ['label' => 'Black', 'image' => 'images/icons/image3.png', 'class' => 'qty-black'],
              'collar_qty' => ['label' => 'Polo', 'image' => 'images/icons/image2.jpg', 'class' => 'qty-collar'],
            ];
            $uniformAllocation = $uniformAllocations[$iur->work_location] ?? ['label' => '', 'limits' => []];
            $uniformTotalLimit = array_sum($uniformAllocation['limits']);
          @endphp
          <div class="card shadow-sm mb-4 iur-edit-section-card">
            <div class="card-header iur-edit-section-header">
              <div>
                <h5 class="mb-1 font-weight-bold"><i class="fa fa-tags mr-1"></i> Uniform Request</h5>
              </div>
            </div>
            <div class="card-body">
              <div class="uniform-allocation-summary mb-3" role="note">
                <i class="ti-info-alt" aria-hidden="true"></i>
                <div>
                  <strong class="d-block">Reference guide</strong>
                  <span>{{ $uniformAllocation['label'] }}</span>
                </div>
              </div>

              <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                <div>
                  <label class="font-weight-bold mb-0">Uniform Quantity / Partial Selection&nbsp;<span class="text-danger">*</span></label>
                  <small class="text-muted d-block">Only uniforms assigned to this location are shown.</small>
                </div>
                <span class="badge badge-info px-3 py-2 mt-2 mt-md-0" aria-live="polite">
                  Total selected: <strong id="uniformQtyTotal">0</strong> / <strong>{{ $uniformTotalLimit }}</strong>
                </span>
              </div>

              <div class="uniform-selection-grid" id="uniformQtyGrid" data-total-limit="{{ $uniformTotalLimit }}">
                @foreach($uniformItems as $field => $item)
                  @php $itemMax = $uniformAllocation['limits'][$field] ?? 0; @endphp
                  @if($itemMax > 0)
                  <div class="uniform-selection-card">
                    <div class="uniform-image-wrap">
                      <img src="{{ asset($item['image']) }}" class="uniform-selection-image" alt="{{ $item['label'] }} uniform">
                    </div>
                    <label class="uniform-selection-label">{{ $item['label'] }}</label>
                    <div class="input-group {{ $item['class'] }}">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-outline-secondary btn-sm qty-minus" aria-label="Decrease {{ $item['label'] }} quantity">-</button>
                      </div>
                      <input type="number" name="{{ $field }}" class="form-control text-center qty-input" min="0" max="{{ $itemMax }}" value="{{ old($field, $iur->{$field} ?? 0) }}" readonly required>
                      <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary btn-sm qty-plus" aria-label="Increase {{ $item['label'] }} quantity">+</button>
                      </div>
                    </div>
                  </div>
                  @endif
                @endforeach
              </div>

              <hr class="my-4" />

              <div class="row">
                <div class="col-12 mb-3">
                  <label>Have you been issued with Uniform before?&nbsp;<span class="text-danger">*</span></label>
                  <select name="issued" id="issued" class="form-control select2" required>
                    <option value="">-- Select --</option>
                    <option value="Yes" {{ $iur->issued=='Yes'?'selected':'' }}>Yes</option>
                    <option value="No" {{ $iur->issued=='No'?'selected':'' }}>No</option>
                  </select>
                </div>
                <div class="col-12 mb-3 remarks-show" style="{{ $iur->issued=='Yes' ? '' : 'display:none;' }}">
                  <label>How many was issued to you before? And where are they?&nbsp;<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="issued_remarks" id="issued_remarks" value="{{ old('issued_remarks', $iur->issued_remarks) }}" placeholder="Enter Issued Remarks">
                </div>
                <div class="col-12 mb-3 reasons-show" style="{{ $iur->issued=='Yes' || $iur->issued=='No' ? '' : 'display:none;' }}">
                  <label>Reason for request of new set of uniforms&nbsp;<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="issued_reasons" id="issued_reasons" value="{{ old('issued_reasons', $iur->issued_reasons) }}" placeholder="Enter Reason">
                </div>
                <div class="col-12 mb-3">
                  <label>Notes <span style="color: gray;">(optional)</span></label>
                  <input type="text" class="form-control" name="notes" value="{{ old('notes', $iur->notes) }}" placeholder="Enter Notes">
                </div>
                @if($iur->type === 'Active Employee')
                @php
                  $hasLegacyUniformConditionImage = $iur->uniformConditionImages->isEmpty() && $iur->uniform_condition_image;
                  $existingUniformConditionCount = $iur->uniformConditionImages->count() + ($hasLegacyUniformConditionImage ? 1 : 0);
                @endphp
                <div class="col-12 mb-3">
                  <label>Current Uniform Condition Proofs&nbsp;<span class="text-danger">*</span></label>
                  <input type="file"
                    name="uniform_condition_images[]"
                    id="uniform_condition_images"
                    class="form-control-file"
                    multiple
                    data-existing-count="{{ $existingUniformConditionCount }}"
                    accept="image/jpeg,image/jpg,image/png"
                    @if($existingUniformConditionCount === 0) required @endif>
                  <small class="form-text text-muted">Keep or upload up to 3 photos total (JPG, JPEG, PNG - Max 5MB each).</small>

                  <div id="existingUniformConditionWrap" class="mt-2 uniform-proof-grid">
                    @foreach($iur->uniformConditionImages as $conditionImage)
                    <div class="uniform-proof-card">
                      <a href="{{ asset($conditionImage->image_path) }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset($conditionImage->image_path) }}"
                          alt="Current uniform condition proof"
                          style="width: 130px; height: 130px; object-fit: cover; border-radius: 4px;">
                      </a>
                      <!-- <div class="form-check mt-2"> -->
                      <!--   <input class="form-check-input uniform-condition-remove" type="checkbox" -->
                      <!--     name="remove_uniform_condition_images[]" -->
                      <!--     value="{{ $conditionImage->id }}" -->
                      <!--     id="remove_uniform_condition_image_{{ $conditionImage->id }}" -->
                      <!--     {{ in_array($conditionImage->id, old('remove_uniform_condition_images', [])) ? 'checked' : '' }}> -->
                      <!--   <label class="form-check-label text-danger" for="remove_uniform_condition_image_{{ $conditionImage->id }}">Remove</label> -->
                      <!-- </div> -->
                    </div>
                    @endforeach

                    @if($hasLegacyUniformConditionImage)
                    <div class="uniform-proof-card">
                      <a href="{{ asset($iur->uniform_condition_image) }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset($iur->uniform_condition_image) }}"
                          alt="Current uniform condition proof"
                          style="width: 130px; height: 130px; object-fit: cover; border-radius: 4px;">
                      </a>
                      <small class="d-block mt-2 text-muted">Existing proof</small>
                    </div>
                    @endif
                  </div>
                  <div id="uniformConditionPreview" class="mt-2 uniform-proof-grid" style="display:none;"></div>
                </div>
                @endif
              </div>
            </div>
          </div>
          </div>

          {{-- ID SECTION --}}
          <div id="idRequestSection" style="{{ in_array($requestFor, ['ID', 'Both'], true) ? '' : 'display:none;' }}">
          <div class="card shadow-sm mb-4 iur-edit-section-card">
            <div class="card-header iur-edit-section-header">
              <div>
                <h5 class="mb-1 font-weight-bold"><i class="fa fa-id-card mr-1"></i> ID Request</h5>
                <small class="text-muted">Review the ID details, photo, and emergency contact information.</small>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-6 mb-3">
                  <label>Reason for ID Request&nbsp;<span class="text-danger">*</span></label>
                  <input type="text" name="id_request" class="form-control" value="{{ old('id_request', $iur->id_request) }}" placeholder="Enter Reason for ID Request" required>
                </div>
                <div class="col-lg-6 mb-3">
                  <div class="id-picture-panel">
                  <label>Upload 2x2 ID Picture with White Background&nbsp;<span class="text-danger">*</span></label>
                  <input type="file" name="id_picture" class="form-control-file" accept="image/jpeg,image/jpg,image/png" @if($iur->id_picture) data-has-existing="true" @endif onchange="previewNewIdImage(this)">
                  <small class="form-text text-muted">JPG, JPEG, PNG - Max 5MB</small>
                  <div id="existingImageWrap" class="mt-3" @if(!$iur->id_picture) style="display:none;" @endif>
                    <img src="{{ $iur->id_picture ? asset($iur->id_picture) : '' }}" id="existingIdImage" class="id-picture-preview" alt="Existing ID picture">
                  </div>
                  <div id="imagePreview" class="mt-2" style="display: none;">
                    <img src="" alt="New ID picture preview" class="id-picture-preview">
                  </div>
                  </div>
                </div>
              </div>

              <div class="iur-edit-subsection mt-2">
                <h6 class="font-weight-bold mb-1">In case of Emergency</h6>
                <p class="text-muted small mb-3">Please make sure these contact details are current.</p>
                <div class="row">
                <div class="col-md-4 mb-3">
                  <label>Name&nbsp;<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="emergency_name" value="{{ old('emergency_name', optional(optional($iur->user)->contact_person)->name ?? '') }}" placeholder="Name of the person for emergency" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label>Contact No.&nbsp;<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="emergency_contact_number" value="{{ old('emergency_contact_number', optional(optional($iur->user)->contact_person)->contact_number ?? '') }}" placeholder="09xxxxxxxxx" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label>Relationship&nbsp;<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="emergency_relation" value="{{ old('emergency_relation', optional(optional($iur->user)->contact_person)->relation ?? '') }}" placeholder="e.g., Wife, Husband, Mother, Father" required>
                </div>
                </div>
              </div>
            </div>
          </div>
          </div>

          <div class="text-right mt-3">
            <a href="{{ url('iur') }}" class="btn btn-light border">Back</a>
            <button class="btn btn-primary">Update</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection

<style>
  .iur-edit-section-card {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
  }

  .iur-edit-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem 1.25rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
  }

  .uniform-allocation-summary {
    display: flex;
    align-items: flex-start;
    gap: .65rem;
    padding: .75rem 1rem;
    color: #4b5563;
    background: #edf1f8;
    border-left: 3px solid #6c8eab;
    border-radius: 4px;
    font-size: 13px;
    line-height: 1.45;
  }

  .uniform-allocation-summary i {
    flex-shrink: 0;
    margin-top: .2rem;
    color: #527694;
  }

  .uniform-selection-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 1rem;
  }

  .uniform-selection-card {
    padding: .75rem;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
  }

  .uniform-image-wrap {
    position: relative;
    height: 180px;
    margin-bottom: .75rem;
    overflow: hidden;
    background: #f8f9fa;
    border-radius: 6px;
  }

  .uniform-selection-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .uniform-selection-label {
    display: block;
    margin-bottom: .4rem;
    color: #374151;
    font-weight: 600;
    text-align: center;
  }

  .uniform-proof-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }

  .uniform-proof-card {
    width: 150px;
    padding: .5rem;
    text-align: center;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 6px;
  }

  .id-picture-panel,
  .iur-edit-subsection {
    padding: 1rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
  }

  .id-picture-preview {
    width: 140px;
    height: 140px;
    padding: 4px;
    object-fit: cover;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
  }

  .qty-input {
    background: #fff !important;
  }
  .qty-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
  }
  .qty-prod .qty-input {
    background: #ff4d4d !important;
    color: #fff;
  }
  .qty-prod .input-group-text,
  .qty-prod .btn-outline-secondary {
    background: #ff8080;
    border-color: #ff8080;
    color: #fff;
  }
  .qty-white .qty-input {
    background: #fff !important;
    color: #333;
  }
  .qty-white .input-group-text,
  .qty-white .btn-outline-secondary {
    background: #f0f0f0;
    border-color: #ccc;
  }
  .qty-black .qty-input {
    background: #222 !important;
    color: #fff;
  }
  .qty-black .input-group-text,
  .qty-black .btn-outline-secondary {
    background: #444;
    border-color: #444;
    color: #fff;
  }
  .qty-collar .qty-input {
    background: linear-gradient(140deg, #59c2e5 50%, #fe0001 50%) !important;
    color: #fff;
    font-weight: bold;
  }
  .qty-collar .input-group-text,
  .qty-collar .btn-outline-secondary {
    background: #91d7ee;
    border-color: #91d7ee;
    color: #fff;
  }
  .qty-collar .input-group-append .btn-outline-secondary {
    background: #ff4d4d;
    border-color: #ff4d4d;
  }

  @media (max-width: 575.98px) {
    .iur-edit-section-header {
      align-items: flex-start;
      flex-direction: column;
    }

    .uniform-selection-grid {
      grid-template-columns: 1fr;
    }

    .uniform-image-wrap {
      height: 210px;
    }
  }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('.select2').select2();

    function toggleIssuedFields() {
      const issued = $('#issued').val();
      if (issued === 'Yes') {
        $('.remarks-show').show();
        $('.reasons-show').show();
        $('#issued_remarks').prop('required', true);
        $('#issued_reasons').prop('required', true);
      } else if (issued === 'No') {
        $('.reasons-show').show();
        $('#issued_reasons').prop('required', true);
        $('.remarks-show').hide();
        $('#issued_remarks').prop('required', false).val('');
      } else {
        $('.remarks-show, .reasons-show').hide();
        $('#issued_remarks, #issued_reasons').prop('required', false).val('');
      }
    }

    function toggleOtherSize() {
      const requestFor = $('#request_for').val();
      const isUniformRequest = requestFor === 'Uniform' || requestFor === 'Both';
      const isOther = isUniformRequest && $('#size').val() === 'other';

      $('#other_size_div').toggle(isOther);
      $('#other_size').prop('required', isOther).prop('disabled', !isOther);
    }

    function toggleRequestSections() {
      const requestFor = $('#request_for').val();
      const showUniform = requestFor === 'Uniform' || requestFor === 'Both';
      const showId = requestFor === 'ID' || requestFor === 'Both';

      $('#uniformRequestSection').toggle(showUniform);
      $('#idRequestSection').toggle(showId);
      $('#sizeField').toggle(showUniform);

      $('#uniformRequestSection').find(':input').prop('disabled', !showUniform);
      $('#idRequestSection').find(':input').prop('disabled', !showId);
      $('#size').prop('disabled', !showUniform).prop('required', showUniform);
      $('#issued').prop('required', showUniform);

      toggleOtherSize();
      toggleIssuedFields();
    }

    $('#request_for').on('change select2:select select2:clear', toggleRequestSections);
    $('#size').on('change select2:select select2:clear', toggleOtherSize);

    $('#issued').on('change', toggleIssuedFields);
    $('#issued').on('select2:select select2:clear', toggleIssuedFields);
    toggleRequestSections();

    // Qty +/- buttons
    const uniformTotalLimit = parseInt($('#uniformQtyGrid').data('total-limit'), 10) || 0;

    function getUniformTotal() {
      let total = 0;

      $('.qty-input').each(function() {
        total += parseInt($(this).val(), 10) || 0;
      });

      return total;
    }

    function updateUniformTotal() {
      const total = getUniformTotal();

      $('#uniformQtyTotal').text(total);
      $('.qty-plus').each(function() {
        const input = $(this).closest('.input-group').find('.qty-input');
        const value = parseInt(input.val(), 10) || 0;
        const max = parseInt(input.attr('max'), 10) || 0;
        $(this).prop('disabled', total >= uniformTotalLimit || value >= max);
      });
    }

    $(document).on('click', '.qty-plus', function() {
      let input = $(this).closest('.input-group').find('.qty-input');
      let val = parseInt(input.val(), 10) || 0;
      let max = parseInt(input.attr('max'), 10);

      if (val < max && getUniformTotal() < uniformTotalLimit) {
        input.val(val + 1);
      }

      updateUniformTotal();
    });

    $(document).on('click', '.qty-minus', function() {
      let input = $(this).closest('.input-group').find('.qty-input');
      let val = parseInt(input.val(), 10) || 0;
      let min = parseInt(input.attr('min'), 10);

      if (val > min) {
        input.val(val - 1);
      }

      updateUniformTotal();
    });

    updateUniformTotal();

    window.previewNewIdImage = function(input) {
      const file = input.files[0];
      if (!file) return;
      if (file.size > 5 * 1024 * 1024) {
        alert('Image size must not exceed 5MB');
        input.value = '';
        return;
      }
      const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
      if (!validTypes.includes(file.type)) {
        alert('Please upload a valid image (JPG, JPEG, or PNG)');
        input.value = '';
        return;
      }
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('existingImageWrap').style.display = 'none';
        const preview = document.getElementById('imagePreview');
        preview.querySelector('img').src = e.target.result;
        preview.style.display = 'block';
      };
      reader.readAsDataURL(file);
    };

    const uniformConditionInput = document.getElementById('uniform_condition_images');

    if (uniformConditionInput) {
      const uniformConditionPreview = document.getElementById('uniformConditionPreview');
      const existingCount = parseInt(uniformConditionInput.dataset.existingCount, 10) || 0;

      function clearUniformConditionPreview() {
        uniformConditionPreview.innerHTML = '';
        uniformConditionPreview.style.display = 'none';
      }

      function previewUniformConditionImages() {
        const files = Array.from(uniformConditionInput.files);
        const removedCount = document.querySelectorAll('.uniform-condition-remove:checked').length;
        const keptCount = existingCount - removedCount;

        if (keptCount + files.length > 3) {
          alert('You may keep and upload a maximum of 3 images total.');
          uniformConditionInput.value = '';
          clearUniformConditionPreview();
          return;
        }

        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        const invalidFile = files.find(file => !validTypes.includes(file.type));
        const oversizedFile = files.find(file => file.size > 5 * 1024 * 1024);

        if (invalidFile || oversizedFile) {
          alert(invalidFile
            ? 'Please upload only valid images (JPG, JPEG, or PNG).'
            : 'Each image must not exceed 5MB.');
          uniformConditionInput.value = '';
          clearUniformConditionPreview();
          return;
        }

        clearUniformConditionPreview();
        if (!files.length) return;

        uniformConditionPreview.style.display = 'flex';
        files.forEach(function(file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            const image = document.createElement('img');
            image.src = e.target.result;
            image.alt = 'New uniform condition preview';
            image.style.cssText = 'width: 140px; height: 140px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px; padding: 5px;';
            uniformConditionPreview.appendChild(image);
          };
          reader.readAsDataURL(file);
        });
      }

      uniformConditionInput.addEventListener('change', previewUniformConditionImages);
      document.querySelectorAll('.uniform-condition-remove').forEach(function(checkbox) {
        checkbox.addEventListener('change', previewUniformConditionImages);
      });
    }
  });
</script>
