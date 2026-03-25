@extends('layouts.header')

@section('content')
  <div class="main-panel">
    <div class="content-wrapper">
      <div class='row'>
        <div class="col-lg-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
            <h4 class="card-title">ID & Uniform Request</h4>
            <hr>
            <form action="{{ route('iur.store') }}" method="POST" id="dealerForm" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="type" class="form-label">Select Type&nbsp;<span class="text-danger">*</span></label>
                  <select class="form-control select2" name="type" id="type" required>
                    <option value="">-- Select Type --</option>
                    <option value="New Hire">New Hire</option>
                    <option value="Active Employee">Active Employee</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="work_location" class="form-label">Work Location&nbsp;<span class="text-danger">*</span></label>
                  <select class="form-control select2" name="work_location" id="work_location" required>
                    <option value="">-- Select Work Location --</option>
                    <option value="HO">Head Office</option>
                    <option value="Plant">Plant</option>
                    <option value="Warehouse">Warehouse</option>
                    <option value="Retail">Retail Hub</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="request_for" class="form-label">I would like to request for&nbsp;<span class="text-danger">*</span></label>
                  <select class="form-control select2" name="request_for" id="request_for" required>
                    <option value="">-- Select Request --</option>
                    <option value="Uniform">Uniform</option>
                    <option value="ID">ID</option>
                    <option value="Both">Both</option>
                  </select>
                </div>
                <div class="col-md-12 mb-3">
                  <label for="details" class="form-label">Details of Work Assignment&nbsp;<span class="text-danger">*</span></label>
                  <textarea class="form-control" id="details" name="details" rows="2" placeholder="Ex: Name of Warehouse / Name of Hub - Region 1 - Piddig Hub" required></textarea>
                </div>     
              </div>
              <div class="uniform-section row">
                <div class="col-md-12 mb-3">
                  <h3>Uniform Request</h3>
                  <hr>
                </div>
                <div class="col-md-4 mb-5">
                  <img src="{{asset('images/icons/image1.jpg')}}" class="img-thumbnail border-0" style="height: 450px !important" alt="Uniform for Retail Hub" title="Uniform for Retail Hub">
                </div>
                <div class="col-md-4 mb-5">
                  <img src="{{asset('images/icons/image2.jpg')}}" class="img-thumbnail border-0" style="height: 450px !important" alt="Uniform for Supervisors and Office Based" title="Uniform for Supervisors and Office Based">
                </div>
                <div class="col-md-4 mb-5">
                  <img src="{{asset('images/icons/image3.png')}}" class="img-thumbnail border-0" style="height: 450px !important" alt="Uniform for Supervisors and Office Based" title="Uniform for Supervisors and Office Based">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="issued" class="form-label">Have you been issued with Uniform before?&nbsp;<span class="text-danger">*</span></label>
                  <select class="form-control select2" name="issued" id="issued" required>
                    <option value="">-- Select Issued --</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3 remarks-show" style="display: none;">
                  <label for="type" class="form-label">How many was issued to you before? And where are they?&nbsp;<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="issued_remarks" id="issued_remarks" placeholder="Enter Issued Remarks">
                </div>
                <div class="col-md-6 mb-3 reasons-show" style="display: none;">
                  <label for="type" class="form-label">Reason for request of new set of uniforms&nbsp;<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="issued_reasons" id="issued_reasons" placeholder="Enter Reason">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label mb-3">Size&nbsp;<span class="text-danger">*</span></label><br>
                  <div class="form-check-inline">
                    <input class="form-check-input" type="radio" name="size" value="S"> S
                  </div>
                  <div class="form-check-inline">
                    <input class="form-check-input" type="radio" name="size" value="M"> M
                  </div>
                  <div class="form-check-inline">
                    <input class="form-check-input" type="radio" name="size" value="L"> L
                  </div>
                  <div class="form-check-inline">
                    <input class="form-check-input" type="radio" name="size" value="XL"> XL
                  </div>
                  <div class="form-check-inline">
                    <input class="form-check-input" type="radio" name="size" value="XXL"> XXL
                  </div>
                  <div class="form-check-inline">
                    <input class="form-check-input" type="radio" name="size" value="other"> Other
                  </div>
                </div>  
                <div class="col-md-6 mb-3" id="other_size_div" style="display:none;">
                  <label>Please specify your size</label>
                  <input type="text" name="other_size" class="form-control" placeholder="Enter size">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="type" class="form-label">Notes</label>
                  <input type="text" class="form-control" name="notes" id="notes" placeholder="Enter Notes">
                </div> 
              </div>
              <div class="id-section row">
                <div class="col-md-12 mb-3">
                  <h3>ID Request</h3>
                  <hr>
                </div> 
                <div class="col-md-6 mb-3">
                  <label for="type" class="form-label">Reason for ID Request&nbsp;<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="id_request" id="id_request" placeholder="Enter Reason for ID Request">
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
              <div class="col-md-12" align="right">
                <a href="{{ url('iur') }}" class="btn btn-secondary">Close</a>
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>  
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
<style>
  .uniform-section, .id-section {
    display: none;
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
      } 
      else if (issued === 'No') {
        $('.reasons-show').show();
        $('#issued_reasons').prop('required', true);

        $('.remarks-show').hide();
        $('#issued_remarks').prop('required', false).val('');
      } 
      else {
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

    $(document).on('change', 'input[name="size"]', function() {
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

    function toggleRequestSections() {
      let requestType = $('#request_for').val();

      // Reset all required first
      $('.uniform-section input, .uniform-section select, .uniform-section textarea').prop('required', false);
      $('.id-section input, .id-section select, .id-section textarea').prop('required', false);

      if (requestType === 'Uniform') {
          $('.uniform-section').show();
          $('.id-section').hide();

          $('#issued, input[name="size"]').prop('required', true);

      } 
      else if (requestType === 'ID') {
          $('.uniform-section').hide();
          $('.id-section').show();

          $('#id_request, input[name="id_picture"]').prop('required', true);

      } 
      else if (requestType === 'Both') {
          $('.uniform-section').show();
          $('.id-section').show();

          $('#issued, input[name="size"], #id_request, input[name="id_picture"]').prop('required', true);
      } 
      else {
          $('.uniform-section, .id-section').hide();
      }
  }

    $('#request_for').on('change', toggleRequestSections);

    // for select2
    $('#request_for').on('select2:select select2:clear', toggleRequestSections);

    // run on load (important for edit / old values)
    toggleRequestSections();
  
  });
</script> 