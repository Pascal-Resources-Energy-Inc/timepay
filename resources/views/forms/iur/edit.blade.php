@extends('layouts.header')

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-body">
        <h4>Edit ID & Uniform Request</h4>
        <hr>

        <form action="{{ route('iur.update', $iur->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Type</label>
              <select name="type" class="form-control select2" required>
                <option value="New" {{ $iur->type=='New'?'selected':'' }}>New Hire</option>
                <option value="Active" {{ $iur->type=='Active'?'selected':'' }}>Active Employee</option>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label>Work Location</label>
              <select name="work_location" class="form-control select2" required>
                <option value="HO" {{ $iur->work_location=='HO'?'selected':'' }}>Head Office</option>
                <option value="Plant" {{ $iur->work_location=='Plant'?'selected':'' }}>Plant</option>
                <option value="Warehouse" {{ $iur->work_location=='Warehouse'?'selected':'' }}>Warehouse</option>
                <option value="Retail" {{ $iur->work_location=='Retail'?'selected':'' }}>Retail Hub</option>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label>Request For</label>
              <select name="request_for" id="request_for" class="form-control select2" readonly>
                <option value="Uniform" {{ $iur->request_for=='Uniform'?'selected':'' }}>Uniform</option>
                <option value="ID" {{ $iur->request_for=='ID'?'selected':'' }}>ID</option>
                <option value="Both" {{ $iur->request_for=='Both'?'selected':'' }}>Both</option>
              </select>
            </div>

            <div class="col-md-12 mb-3">
              <label>Details</label>
              <textarea name="details" class="form-control" required>{{ $iur->details }}</textarea>
            </div>
          </div>

          {{-- UNIFORM SECTION --}}
          <div class="uniform-section row">
            <div class="col-md-6 mb-3">
              <label>Issued</label>
              <select name="issued" id="issued" class="form-control">
                <option value="Yes" {{ $iur->issued=='Yes'?'selected':'' }}>Yes</option>
                <option value="No" {{ $iur->issued=='No'?'selected':'' }}>No</option>
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
              <label>Size</label><br>
              @foreach(['S','M','L','XL','XXL'] as $size)
                <label>
                  <input type="radio" name="size" value="{{ $size }}"
                  {{ $iur->size == $size ? 'checked' : '' }}> {{ $size }}
                </label>
              @endforeach

              <label>
                <input type="radio" name="size" value="other"
                {{ !in_array($iur->size,['S','M','L','XL','XXL']) ? 'checked' : '' }}> Other
              </label>
            </div>

            <div class="col-md-6 mb-3" id="other_size_div"
              style="{{ !in_array($iur->size,['S','M','L','XL','XXL']) ? '' : 'display:none;' }}">
              <input type="text" name="other_size" class="form-control"
                     value="{{ !in_array($iur->size,['S','M','L','XL','XXL']) ? $iur->size : '' }}">
            </div>
          </div>

          {{-- ID SECTION --}}
          <div class="id-section row">
            <div class="col-md-6 mb-3">
              <label>Reason for ID</label>
              <input type="text" name="id_request" class="form-control"
                     value="{{ $iur->id_request }}">
            </div>

            <div class="col-md-6">
              <label>Upload ID Picture</label>
              <input type="file" name="id_picture" class="form-control">

              @if($iur->id_picture)
                <img src="{{ asset($iur->id_picture) }}" width="120" class="mt-2">
              @endif
            </div>
          </div>

          <div class="text-right">
            <a href="{{ url('iur') }}" class="btn btn-secondary">Back</a>
            <button class="btn btn-primary">Update</button>
          </div>

        </form>
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