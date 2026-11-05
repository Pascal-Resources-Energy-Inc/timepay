<div class="modal fade" id="createIurModal" tabindex="-1" role="dialog" aria-labelledby="createIurModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-scrollable iur-create-dialog" role="document">
    <div class="modal-content border-0">
      <div class="modal-header align-items-start">
        <div class="d-flex flex-column">
          <div class="d-inline-block mb-1" style="width: fit-content;">
            <h4 class="modal-title mb-1" id="createIurModalLabel">ID &amp; UNIFORM REQUEST</h4>
          </div>
          <small>HRD-TAD-007-001</small>
        </div>
        <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form action="{{ route('iur.store') }}" method="POST" id="dealerForm" enctype="multipart/form-data">
              @csrf

              @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
              </div>
              @endif

              @if (session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
              @endif

              <div class="row">

                <!-- select request -->
                <div class="col-md-6 mb-3">
                  <label for="request_for" class="form-label">I would like to request for&nbsp;<span class="text-danger">*</span></label>
                  <select class="form-control select2" name="request_for" id="request_for" required>
                    <option value="">-- Select Request --</option>
                    <option value="Uniform">Uniform</option>
                    <option value="ID">ID</option>
                    <option value="Both">Both</option>
                  </select>
                </div>


                <div class="col-md-6 mb-3">
                  <label for="work_location" class="form-label">Location&nbsp;<span class="text-danger">*</span></label>
                  <select class="form-control select2" name="work_location" id="work_location" required>
                    <option value="" disabled>-- Select Work Location --</option>
                    <option value="HO">Head Office</option>
                    <option value="Plant">Plant</option>
                    <option value="Guinobatan Warehouse / Lubao Office">Guinobatan Warehouse / Lubao Office</option>
                    <option value="Field">Field</option>
                  </select>
                </div>

                <!-- employee's type -->
                <div class="col-md-6 mb-3">
                  <label for="type" class="form-label">Select Type&nbsp;<span class="text-danger">*</span></label>
                  <select class="form-control select2" name="type" id="type" required>
                    <option value="">-- Select Type --</option>
                    <option value="New Hire">New Hire</option>
                    <option value="Active Employee">Active Employee</option>
                  </select>
                </div>

                <!-- details work assignment -->
                <div class="col-md-6 mb-3">
                  <label for="details" class="form-label">Details of Work Assignment&nbsp;<span class="text-danger">*</span></label>
                  <input class="form-control" id="details" name="details" rows="1" placeholder="Ex: Name of Warehouse / Name of Hub - Region 1 - Piddig Hub" required>
                </div>
              </div>
              <div class="uniform-section row">
                <!-- <div class="col-md-12 mb-3"> -->
                <!--   <h4 class="card-title">Uniform Request</h4> -->
                <!-- </div> -->

                <!-- <div class="uniform-container" id="uniformContainer"> -->
                <!--   <div class="img-container" data-uniform-type="prod_qty"> -->
                <!--     <img src="{{asset('images/icons/image4.png')}}" class="img-fluid border uniform-img" style="background-color: #ff4d4d;" alt="Production Uniform" title="Production"> -->
                <!--   </div> -->
                <!---->
                <!--   <div class="img-container" data-uniform-type="white_qty"> -->
                <!--     <img src="{{asset('images/icons/image1.jpg')}}" class="img-fluid border uniform-img" alt="White Uniform" title="White"> -->
                <!--   </div> -->
                <!---->
                <!--   <div class="img-container" data-uniform-type="black_qty"> -->
                <!--     <img src="{{asset('images/icons/image3.png')}}" class="img-fluid border uniform-img" alt="Black Uniform" title="Black"> -->
                <!--   </div> -->
                <!---->
                <!--   <div class="img-container" data-uniform-type="collar_qty"> -->
                <!--     <img src="{{asset('images/icons/image2.jpg')}}" class="img-fluid border uniform-img" alt="Polo Shirt" title="Polo"> -->
                <!--   </div> -->
                <!-- </div> -->

                <!-- UNIFORM QUANTITY -->
                <div class="uniform-qty-only w-100">
                  <div class="mb-2">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                      <label class="form-label mb-0">Uniform Quantity / Partial Selection&nbsp;<span class="text-danger">*</span></label>
                      <span class="badge badge-info px-3 py-2 mt-1" aria-live="polite">
                        Total selected: <strong id="uniformQtyTotal">0</strong> / <strong id="uniformQtyMax">0</strong>
                      </span>
                    </div>
                    <small class="text-muted" id="uniformQtyHint">Select a work location to view its uniform allocation.</small>

                    <div id="uniformAllocationLegend" class="uniform-allocation-legend" role="note" aria-live="polite">
                      <i class="ti-info-alt" aria-hidden="true"></i>
                      <div class="uniform-allocation-content">
                        <strong class="d-block mb-1">Reference guide</strong>
                        <span id="uniformAllocationText"></span>
                      </div>
                    </div>
                  </div>
                  <div class="uniform-qty-grid" id="uniformQtyGrid">
                    <!-- production qty -->
                    <div class="qty-item" data-uniform-type="prod_qty">
                      <small class="qty-label">Production</small>
                      <div class="input-group qty-prod">
                        <div class="input-group-prepend">
                          <button type="button" class="btn btn-outline-secondary btn-sm qty-minus">-</button>
                        </div>
                        <input type="number" name="prod_qty" class="form-control text-center qty-input border-0" min="0" max="6" value="0" readonly>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-outline-secondary btn-sm qty-plus">+</button>
                        </div>
                      </div>
                    </div>

                    <!-- white qty -->
                    <div class="qty-item" data-uniform-type="white_qty">
                      <small class="qty-label">White</small>
                      <div class="input-group qty-white">
                        <div class="input-group-prepend">
                          <button type="button" class="btn btn-outline-secondary btn-sm qty-minus">-</button>
                        </div>
                        <input type="number" name="white_qty" class="form-control text-center qty-input" min="0" max="6" value="0" readonly>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-outline-secondary btn-sm qty-plus">+</button>
                        </div>
                      </div>
                    </div>

                    <!-- black qty -->
                    <div class="qty-item" data-uniform-type="black_qty">
                      <small class="qty-label">Black</small>
                      <div class="input-group qty-black">
                        <div class="input-group-prepend">
                          <button type="button" class="btn btn-outline-secondary btn-sm qty-minus">-</button>
                        </div>
                        <input type="number" name="black_qty" class="form-control text-center qty-input border-0" min="0" max="6" value="0" readonly>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-outline-secondary btn-sm qty-plus">+</button>
                        </div>
                      </div>
                    </div>

                    <!-- collar qty -->
                    <div class="qty-item" data-uniform-type="collar_qty">
                      <small class="qty-label">Polo</small>
                      <div class="input-group qty-collar">
                        <div class="input-group-prepend">
                          <button type="button" class="btn btn-outline-secondary btn-sm qty-minus">-</button>
                        </div>
                        <input type="number" name="collar_qty" class="form-control text-center qty-input border-0" min="0" max="6" value="0" readonly>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-outline-secondary btn-sm qty-plus">+</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- THIRD SECTION -->
                <div class="col-md-4 mb-3 mt-3">
                  <label for="issued" class="form-label">Have you been issued with Uniform before?&nbsp;<span class="text-danger">*</span></label>
                  <select class="form-control select2" name="issued" id="issued" required>
                    <option value="">-- Select Issued --</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
                <div class="w-100"></div>
                <div class="col-md-4 mb-3 remarks-show" style="display: none;">
                  <label for="type" class="form-label">How many was issued to you before? And where are they?&nbsp;<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="issued_remarks" id="issued_remarks" placeholder="Enter Issued Remarks">
                </div>
                <div class="w-100"></div>
                <div class="col-md-4 mb-3 reasons-show" style="display: none;">
                  <label for="type" class="form-label">Reason for request of new set of uniforms&nbsp;<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="issued_reasons" id="issued_reasons" placeholder="Enter Reason">
                </div>
                <div class="w-100"></div>
                <div class="col-md-4 mb-3">
                  <div class="d-flex flex-column" style="gap: 0.5rem;">
                    <div>
                      <label for="size" class="form-label">Size&nbsp;<span class="text-danger">*</span></label>
                      <select class="form-control select2" name="size" id="size" required>
                        <option value="">-- Select Size --</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                        <option value="other">Other</option>
                      </select>
                    </div>
                    <div style="display: none;" id="other_size_div">
                      <label>Please specify your size</label>
                      <input type="text" name="other_size" class="form-control" placeholder="specify size">
                    </div>
                  </div>
                </div>
                <div class="w-100"></div>
                <div class="col-md-4 mb-3">
                  <label for="notes" class="form-label">Notes <span style="color: gray;">(optional)</span></label>
                  <input type="text" class="form-control" name="notes" id="notes" placeholder="Enter Notes">
                </div>
                <div class="w-100"></div>
                <div class="col-md-6 mb-3" id="uniformConditionProofWrapper" style="display: none;">
                  <label for="uniform_condition_images" class="form-label">
                    Current Uniform Condition Proofs&nbsp;<span class="text-danger">*</span>
                  </label>
                  <input type="file"
                    class="form-control-file"
                    name="uniform_condition_images[]"
                    id="uniform_condition_images"
                    multiple
                    accept="image/jpeg,image/jpg,image/png">
                  <small class="form-text text-muted">Upload 1 to 3 clear photos showing the condition of the uniform (JPG, JPEG, PNG - Max 5MB each).</small>
                  <div id="uniformConditionPreview" class="mt-2 d-flex flex-wrap" style="display: none; gap: 10px;"></div>
                </div>
              </div>
              <div class="id-section row">
                <div class="col-md-12 mb-3">
                  <h4 class="card-title">ID Request</h4>
                  <hr>
                </div>
                <div class="col-md-12 mb-3">
                  <label for="type" class="form-label">Reason for ID Request&nbsp;<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="id_request" id="id_request" placeholder="Enter Reason for ID Request">
                </div>


                <!-- emergency contact -->
                <div class="col-md-12 mt-3">
                  <h4 class="card-title">In case of Emergency</h4>
                </div>
                <div class="col-md-12 mb-3">
                  <label for="type" class="form-label">Name&nbsp;<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="emergency_name" value="{{ optional(optional(auth()->user()->employee)->contact_person)->name ?? '' }}" id="id_request" placeholder="Name of the person for emergency">
                </div>

                <div class="col-md-12 mb-3">
                  <label for="type" class="form-label">Contact No.&nbsp;<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="emergency_contact_number" value="{{ optional(optional(auth()->user()->employee)->contact_person)->contact_number ?? '' }}" id="id_request" placeholder="09xxxxxxxxx">
                </div>

                <div class="col-md-12 mb-3">
                  <label for="type" class="form-label">Relationship&nbsp;<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="emergency_relation" value="{{ optional(optional(auth()->user()->employee)->contact_person)->relation ?? '' }}" id="id_request" placeholder="e.g., Wife, Husband, Mother, Father, and etc.">
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>Upload 2x2 ID Picture with White Background&nbsp;<span class="text-danger">*</span></label>
                    <input type="file" class="form-control-file" name="id_picture" accept="image/jpeg,image/jpg,image/png">
                    <small class="form-text text-muted">Upload 2x2 ID Picture (JPG, JPEG, PNG - Max 5MB)</small>
                    <div id="imagePreview" class="mt-2" style="display: none;">
                      <img src="" alt="Preview" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-end border-top pt-3 mt-3">
                <button type="button" class="btn btn-light border mr-2" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
      </div>
    </div>
  </div>
</div>
  <style>
    .iur-create-dialog {
      max-width: 1200px;
      width: calc(100% - 2rem);
    }

    #createIurModal .modal-body {
      max-height: calc(100vh - 10rem);
      overflow-y: auto;
    }

    .uniform-section,
    .id-section {
      display: none;
    }

    .uniform-section {
      display: flex;
      justify-content: start;
      flex-wrap: wrap;
    }

    .uniform-qty-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1rem;
    }

    .uniform-qty-grid.prod-visible {
      grid-template-columns: repeat(4, 1fr);
    }

    .uniform-qty-only {
      visibility: hidden;
      height: 0;
      overflow: hidden;
      padding: 0 15px;
      margin: 0;
    }

    .uniform-qty-only.qty-visible {
      visibility: visible;
      height: auto;
      overflow: visible;
    }

    .uniform-allocation-legend {
      display: none;
      margin-top: 10px;
      padding: .5rem 1rem;
      color: #4b5563;
      background: #edf1f8;
      border-left: 3px solid #6c8eab;
      border-radius: 4px;
      font-size: 13px;
      font-weight: 400;
    }

    .uniform-allocation-legend.is-visible {
      display: flex;
      align-items: flex-start;
    }

    .uniform-allocation-legend i {
      flex-shrink: 0;
      margin-top: .25rem;
      margin-right: .5rem;
      color: #527694;
    }

    .uniform-allocation-content {
      min-width: 0;
      line-height: 1.45;
    }

    .uniform-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      padding: 0 15px;
      margin: auto;
      gap: 1rem;
      min-height: 250px;
    }

    .uniform-container.prod-visible {
      grid-template-columns: repeat(4, 1fr);
    }

    .prod-only {
      display: none !important;
    }

    .prod-visible .prod-only {
      display: flex !important;
    }

    .img-container {
      margin: auto;
      max-width: 250px;
      padding: 0;
    }

    .uniform-img {
      width: 100%;
      object-fit: contain;
      background: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 4px;
    }

    .placeholder-img {
      width: 100%;
      background: #e9ecef;
      border: 1px solid #dee2e6;
      border-radius: 4px;
      font-size: 14px;
    }

    .qty-input {
      background: #fff !important;
    }

    .qty-item {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .qty-label {
      font-size: 11px;
      color: #6c757d;
      text-align: center;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
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

    .select2-container {
      width: 100% !important;
    }

    @media (max-width: 991.98px) {

      .uniform-container,
      .uniform-qty-grid,
      .uniform-container.prod-visible,
      .uniform-qty-grid.prod-visible {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 575.98px) {

      .uniform-container,
      .uniform-qty-grid,
      .uniform-container.prod-visible,
      .uniform-qty-grid.prod-visible {
        grid-template-columns: 1fr;
      }

      .uniform-img,
      .placeholder-img {
        height: 200px !important;
      }
    }
  </style>
  <script>
    $(document).ready(function() {
      const createIurModal = $('#createIurModal');

      createIurModal.find('.select2').select2({
        dropdownParent: createIurModal
      });

      @if ($errors->any() || request('create') == 1)
        createIurModal.modal('show');
      @endif

      const uniformAllocations = {
        HO: {
          label: 'Head Office: 2 Polo, 1 White, and 1 Black uniform.',
          limits: { prod_qty: 0, white_qty: 1, black_qty: 1, collar_qty: 2 }
        },
        Plant: {
          label: 'Plant: 5 Production and 1 White uniform.',
          limits: { prod_qty: 5, white_qty: 1, black_qty: 0, collar_qty: 0 }
        },
        'Guinobatan Warehouse / Lubao Office': {
          label: 'Guinobatan Warehouse / Lubao Office: 3 Polo, 1 White, and 1 Black uniform.',
          limits: { prod_qty: 0, white_qty: 1, black_qty: 1, collar_qty: 3 }
        },
        Field: {
          label: 'Field: 3 Polo and 3 Black uniforms.',
          limits: { prod_qty: 0, white_qty: 0, black_qty: 3, collar_qty: 3 }
        }
      };

      function getUniformAllocation() {
        return uniformAllocations[$('#work_location').val()] || null;
      }

      function updateUniformLegend() {
        const requestType = $('#request_for').val();
        const allocation = getUniformAllocation();
        const isUniformRequest = requestType === 'Uniform' || requestType === 'Both';
        const legendText = isUniformRequest && allocation ? allocation.label : '';

        $('#uniformAllocationText').text(legendText);
        $('#uniformAllocationLegend').toggleClass('is-visible', legendText !== '');
      }

      function applyUniformAllocation() {
        const allocation = getUniformAllocation();
        const limits = allocation ? allocation.limits : {};
        const requestType = $('#request_for').val();
        const isUniformRequest = requestType === 'Uniform' || requestType === 'Both';
        let totalLimit = 0;

        $('.qty-input').each(function() {
          const input = $(this);
          const uniformType = input.attr('name');
          const max = limits[uniformType] || 0;
          const isAllowed = max > 0;
          const currentValue = parseInt(input.val(), 10) || 0;

          totalLimit += max;
          input
            .attr('max', max)
            .prop('required', isUniformRequest && isAllowed)
            .val(isAllowed ? Math.min(currentValue, max) : 0);
          input.closest('.qty-item').toggle(isAllowed);
          $('#uniformContainer [data-uniform-type="' + uniformType + '"]').toggle(isAllowed);
        });

        $('#uniformQtyMax').text(totalLimit);
        $('#uniformQtyHint').text(allocation
          ? 'Select at least 1 item without exceeding the maximum for each uniform type.'
          : 'Select a work location to view its uniform allocation.');

        updateUniformTotal();
        updateUniformLegend();
      }

      $('#work_location').on('change', applyUniformAllocation);
      $('#work_location').on('select2:select select2:clear', applyUniformAllocation);
      applyUniformAllocation();

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
          // If empty selection
          $('.remarks-show, .reasons-show').hide();
          $('#issued_remarks, #issued_reasons')
            .prop('required', false)
            .val('');
        }
      }

      // Normal change
      $('#issued').on('change', toggleIssuedFields);

      // If using Select2
      $('#issued').on('select2:select select2:clear', toggleIssuedFields);

      // Run on page load (for old value)
      toggleIssuedFields();

      $(document).on('change', '#size', function() {
        if ($(this).val() === 'other') {
          $('#other_size_div').show();
          $('#other_size_div input').prop('required', true);
        } else {
          $('#other_size_div').hide();
          $('#other_size_div input').prop('required', false).val('');
        }
      });

      const imageInput = document.querySelector('input[name="id_picture"]');
      const imagePreview = document.getElementById('imagePreview');
      const previewImg = imagePreview.querySelector('img');

      if (imageInput) {
        imageInput.addEventListener('change', function(e) {
          const file = e.target.files[0];
          if (file) {
            if (file.size > 5 * 1024 * 1024) {
              alert('Image size must not exceed 5MB');
              this.value = '';
              imagePreview.style.display = 'none';
              return;
            }

            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
              alert('Please upload a valid image (JPG, JPEG, or PNG)');
              this.value = '';
              imagePreview.style.display = 'none';
              return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
              previewImg.src = e.target.result;
              imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
          } else {
            imagePreview.style.display = 'none';
          }
        });
      }

      const uniformConditionInput = document.getElementById('uniform_condition_images');
      const uniformConditionPreview = document.getElementById('uniformConditionPreview');

      function clearUniformConditionPreview() {
        uniformConditionPreview.innerHTML = '';
        uniformConditionPreview.style.display = 'none';
      }

      uniformConditionInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);

        if (!files.length) {
          clearUniformConditionPreview();
          return;
        }

        if (files.length > 3) {
          alert('You may upload a maximum of 3 images.');
          this.value = '';
          clearUniformConditionPreview();
          return;
        }

        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        const invalidFile = files.find(file => !validTypes.includes(file.type));
        const oversizedFile = files.find(file => file.size > 5 * 1024 * 1024);

        if (invalidFile) {
          alert('Please upload only valid images (JPG, JPEG, or PNG).');
          this.value = '';
          clearUniformConditionPreview();
          return;
        }

        if (oversizedFile) {
          alert('Each image must not exceed 5MB.');
          this.value = '';
          clearUniformConditionPreview();
          return;
        }

        clearUniformConditionPreview();
        uniformConditionPreview.style.display = 'flex';

        files.forEach(function(file) {
          const reader = new FileReader();
          reader.onload = function(event) {
            const image = document.createElement('img');
            image.src = event.target.result;
            image.alt = 'Uniform condition preview';
            image.style.cssText = 'width: 140px; height: 140px; border: 1px solid #ddd; border-radius: 4px; padding: 5px; object-fit: cover;';
            uniformConditionPreview.appendChild(image);
          };
          reader.readAsDataURL(file);
        });
      });

      function toggleUniformConditionProof() {
        const requestType = $('#request_for').val();
        const employeeType = $('#type').val();
        const isRequired = employeeType === 'Active Employee'
          && (requestType === 'Uniform' || requestType === 'Both');

        $('#uniformConditionProofWrapper').toggle(isRequired);
        uniformConditionInput.required = isRequired;

        if (!isRequired) {
          uniformConditionInput.value = '';
          clearUniformConditionPreview();
        }
      }

      function toggleRequestSections() {
        let requestType = $('#request_for').val();

        // Reset all required first
        $('.uniform-section input, .uniform-section select, .uniform-section textarea').prop('required', false);
        $('.id-section input, .id-section select, .id-section textarea').prop('required', false);
        $('.uniform-qty-only input').prop('required', false);

        if (requestType === 'Uniform') {
          $('.uniform-section').show();
          $('.uniform-qty-only').addClass('qty-visible');
          $('.id-section').hide();

          $('#issued, #size').prop('required', true);
          $('.qty-input:visible').prop('required', true);

        } else if (requestType === 'ID') {
          $('.uniform-section').hide();
          $('.uniform-qty-only').removeClass('qty-visible');
          $('.id-section').show();

          $('#id_request, input[name="id_picture"]').prop('required', true);

        } else if (requestType === 'Both') {
          $('.uniform-section').show();
          // $('.uniform-qty-only').removeClass('qty-visible');
          $('.uniform-qty-only').addClass('qty-visible');
          $('.id-section').show();

          $('#issued, #size, #id_request, input[name="id_picture"]').prop('required', true);
          $('.qty-input:visible').prop('required', true);
        } else {
          $('.uniform-section, .id-section').hide();
          $('.uniform-qty-only').removeClass('qty-visible');
        }

        applyUniformAllocation();
        toggleUniformConditionProof();
      }

      $('#request_for').on('change', toggleRequestSections);

      // for select2
      $('#request_for').on('select2:select select2:clear', toggleRequestSections);

      $('#type').on('change select2:select select2:clear', toggleUniformConditionProof);

      // run on load (important for edit / old values)
      toggleRequestSections();

      // Qty +/- buttons
      function getUniformTotal() {
        let total = 0;
        $('.qty-input').each(function() {
          total += parseInt($(this).val(), 10) || 0;
        });
        return total;
      }

      function getUniformMaximum() {
        const allocation = getUniformAllocation();
        if (!allocation) return 0;

        return Object.values(allocation.limits).reduce(function(total, max) {
          return total + max;
        }, 0);
      }

      function updateUniformTotal() {
        const total = getUniformTotal();
        const totalMax = getUniformMaximum();
        $('#uniformQtyTotal').text(total);
        $('#uniformQtyMax').text(totalMax);

        $('.qty-plus').each(function() {
          const input = $(this).closest('.input-group').find('.qty-input');
          const value = parseInt(input.val(), 10) || 0;
          const inputMax = parseInt(input.attr('max'), 10) || 0;
          $(this).prop('disabled', total >= totalMax || value >= inputMax);
        });
      }

      $(document).on('click', '.qty-plus', function() {
        let input = $(this).closest('.input-group').find('.qty-input');
        let val = parseInt(input.val(), 10) || 0;
        let max = parseInt(input.attr('max'), 10);
        if (val < max && getUniformTotal() < getUniformMaximum()) input.val(val + 1);
        updateUniformTotal();
      });

      $(document).on('click', '.qty-minus', function() {
        let input = $(this).closest('.input-group').find('.qty-input');
        let val = parseInt(input.val(), 10) || 0;
        let min = parseInt(input.attr('min'), 10);
        if (val > min) input.val(val - 1);
        updateUniformTotal();
      });

      updateUniformTotal();

    });
  </script>
