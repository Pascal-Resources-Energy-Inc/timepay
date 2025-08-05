@extends('layouts.header')

@section('css_header')
<link rel="stylesheet" href="{{ asset('./body_css/vendors/fullcalendar/fullcalendar.min.css') }}">

<style>
  .select2-container {
    width: 100% !important;
  }
  
  .admin-error {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
  }
  
  .admin-error:focus {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
  }
  .modal-content {
    border-radius: 8px;
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  }

  .modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 1.5rem;
    border-radius: 8px 8px 0 0;
  }

  .modal-header h5 {
    color: #495057;
    font-weight: 600;
    margin: 0;
  }

  .modal-header .close {
    color: #6c757d;
    opacity: 0.7;
    font-size: 1.5rem;
    font-weight: 300;
  }

  .modal-header .close:hover {
    opacity: 1;
    color: #495057;
  }

  .modal-body {
    padding: 2rem;
  }

  .verification-title {
    color: #495057;
    font-weight: 500;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
  }

  .verification-subtitle {
    color: #6c757d;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
  }

  .form-group {
    margin-bottom: 1rem;
  }

  .form-control {
    border-radius: 6px;
    border: 1px solid #ced4da;
    padding: 0.75rem;
    font-size: 0.95rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  }

  .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: 0;
  }

  .btn-verify {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.15s ease-in-out;
  }

  .btn-verify:hover {
    background-color: #0056b3;
    border-color: #0056b3;
    color: white;
  }

  .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    border-radius: 6px;
  }

  .btn-secondary:hover {
    background-color: #545b62;
    border-color: #545b62;
    color: white;
  }

  .security-note {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    padding: 1rem;
    margin-top: 1.5rem;
    border-radius: 6px;
    font-size: 0.85rem;
  }

  .security-note i {
    color: #6c757d;
    margin-right: 0.5rem;
  }

  .modal-footer {
    border-top: 1px solid #dee2e6;
    padding: 1.5rem;
    background-color: #f8f9fa;
    border-radius: 0 0 8px 8px;
  }
  
  </style>
@endsection

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row mb-3">
      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-body py-3">
            <h3 class="font-weight-bold mb-1">Set Amount for the Approval</h3>
            <p class="text-muted mb-0">Configure amount thresholds for approvals.</p>
          </div>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
          <div class="card-header bg-light border-bottom">
            <h4 class="mb-0">Travel Order</h4>
          </div>
          <div class="card-body">
            <form method="POST" action="updateApprovalAmount" id="travelOrderForm" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="form_type" value="travel_order">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="travel_higher">Head Supervisor <span style="color: red">*</span></label>
                  @php
                    $placeholderTravelHigher = count($travel_higher_amounts) > 0 ? number_format($travel_higher_amounts[0]) : '-- Amount --';
                  @endphp
                  <select data-placeholder="{{ $placeholderTravelHigher }}" class="form-control js-example-basic-single required" id="travel_higher" name="higher" required>
                    <option value="">-- Amount --</option>
                    <option value="5000" {{ old('higher') == '5000' ? 'selected' : '' }}>5000</option>
                    <option value="7000" {{ old('higher') == '7000' ? 'selected' : '' }}>7000</option>
                    <option value="10000" {{ old('higher') == '10000' ? 'selected' : '' }}>10000</option>
                  </select>
                </div>

                <div class="form-group col-md-6">
                  <label for="travel_less">Immediate Supervisor <span style="color: red">*</span></label>
                  @php
                    $placeholderTravelLess = count($travel_less_amounts) > 0 ? number_format($travel_less_amounts[0]) : '-- Amount --';
                  @endphp
                  <select data-placeholder="{{ $placeholderTravelLess }}" class="form-control js-example-basic-single required" id="travel_less" name="less" required>
                    <option value="">-- Amount --</option>
                    <option value="2000" {{ old('less') == '2000' ? 'selected' : '' }}>2000</option>
                    <option value="3000" {{ old('less') == '3000' ? 'selected' : '' }}>3000</option>
                    <option value="4000" {{ old('less') == '4000' ? 'selected' : '' }}>4000</option>
                  </select>
                </div>
              </div>

              <div class="text-right mt-3">
                <button type="button" class="btn btn-primary" id="saveTravelOrderBtn">
                  <i class=""></i> Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
          <div class="card-header bg-light border-bottom">
            <h4 class="mb-0">Authority to Deduct</h4>
          </div>
          <div class="card-body">
            <form method="POST" action="updateApprovalAmount" id="authorityDeductForm" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="form_type" value="authority_to_deduct">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="authority_higher">Head Supervisor <span style="color: red">*</span></label>
                  @php
                    $placeholderAuthorityHigher = count($authority_higher_amounts) > 0 ? number_format($authority_higher_amounts[0]) : '-- Amount --';
                  @endphp
                  <select data-placeholder="{{ $placeholderAuthorityHigher }}" class="form-control js-example-basic-single required" id="authority_higher" name="higher" required>
                    <option value="">-- Amount --</option>
                    <option value="5000" {{ old('higher') == '5000' ? 'selected' : '' }}>5000</option>
                    <option value="7000" {{ old('higher') == '7000' ? 'selected' : '' }}>7000</option>
                    <option value="10000" {{ old('higher') == '10000' ? 'selected' : '' }}>10000</option>
                  </select>
                </div>

                <div class="form-group col-md-6">
                  <label for="authority_less">Immediate Supervisor <span style="color: red">*</span></label>
                  @php
                    $placeholderAuthorityLess = count($authority_less_amounts) > 0 ? number_format($authority_less_amounts[0]) : '-- Amount --';
                  @endphp
                  <select data-placeholder="{{ $placeholderAuthorityLess }}" class="form-control js-example-basic-single required" id="authority_less" name="less" required>
                    <option value="">-- Amount --</option>
                    <option value="2000" {{ old('less') == '2000' ? 'selected' : '' }}>2000</option>
                    <option value="3000" {{ old('less') == '3000' ? 'selected' : '' }}>3000</option>
                    <option value="4000" {{ old('less') == '4000' ? 'selected' : '' }}>4000</option>
                  </select>
                </div>
              </div>

              <div class="text-right mt-3">
                <button type="button" class="btn btn-primary" id="saveAuthorityDeductBtn">
                  <i class=""></i> Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="adminVerificationModal" tabindex="-1" role="dialog" aria-labelledby="adminVerificationModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="adminVerificationModalLabel">
          <i class=""></i>Admin Verification
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="modal_admin_email">Email</label>
          <input type="email" class="form-control" id="modal_admin_email" name="admin_email" placeholder="Enter your admin email" value="{{ old('admin_email') }}" required>
        </div>
        
        <div class="form-group">
          <label for="modal_admin_password">Password</label>
          <input type="password" class="form-control" id="modal_admin_password" name="admin_password" placeholder="Enter your password" required>
        </div>

        <div class="security-note">
          <i class=""></i>
          <span class="">
            Your credentials are secure and encrypted.
          </span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Cancel
        </button>
        <button type="button" class="btn btn-primary" id="verifyAndSubmit">
          <i class=""></i> Verify & Save
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let currentForm = null;

function validateForm(formType) {
  const higherSelector = formType === 'travel' ? '#travel_higher' : '#authority_higher';
  const lessSelector = formType === 'travel' ? '#travel_less' : '#authority_less';
  
  const higher = $(higherSelector).val();
  const less = $(lessSelector).val();

  if (!higher || !less) {
    Swal.fire({
      icon: 'warning',
      title: 'Required Fields Missing',
      text: 'Please select both Head Supervisor and Immediate Supervisor amounts.',
      confirmButtonColor: '#3399ff',
      confirmButtonText: 'OK',
      customClass: {
        icon: 'custom-icon-padding'
      }
    });

    if (!higher) {
      $(higherSelector).next('.select2-container').addClass('admin-error');
    }

    if (!less) {
      $(lessSelector).next('.select2-container').addClass('admin-error');
    }

    setTimeout(() => {
      $(higherSelector).next('.select2-container').removeClass('admin-error');
      $(lessSelector).next('.select2-container').removeClass('admin-error');
    }, 3000);

    return false;
  }

  return true;
}

$('#saveTravelOrderBtn').on('click', function() {
  if (validateForm('travel')) {
    currentForm = 'travelOrderForm';
    $('#adminVerificationModal').modal('show');
  }
});

$('#saveAuthorityDeductBtn').on('click', function() {
  if (validateForm('authority')) {
    currentForm = 'authorityDeductForm';
    $('#adminVerificationModal').modal('show');
  }
});

$('#verifyAndSubmit').on('click', function() {
  const adminEmail = $('#modal_admin_email').val();
  const adminPassword = $('#modal_admin_password').val();

  if (!adminEmail || !adminPassword) {
    Swal.fire({
      icon: 'warning',
      title: 'Admin Credentials Required',
      text: 'Please enter both admin email and password.',
      confirmButtonColor: '#3399ff',
      confirmButtonText: 'OK'
    });
    
    if (!adminEmail) {
      $('#modal_admin_email').addClass('admin-error');
    }
    if (!adminPassword) {
      $('#modal_admin_password').addClass('admin-error');
    }

    setTimeout(() => {
      $('#modal_admin_email, #modal_admin_password').removeClass('admin-error');
    }, 3000);

    return;
  }

  if (currentForm) {
    $('#' + currentForm).append('<input type="hidden" name="admin_email" value="' + adminEmail + '">');
    $('#' + currentForm).append('<input type="hidden" name="admin_password" value="' + adminPassword + '">');
    $('#' + currentForm).submit();
  }
});

$('#adminVerificationModal').on('hidden.bs.modal', function () {
  $('#modal_admin_email, #modal_admin_password').val('').removeClass('admin-error');
  currentForm = null;
});

$('#modal_admin_email, #modal_admin_password').on('focus', function() {
  $(this).removeClass('admin-error');
});
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Authentication Failed',
        text: '{{ session('error') }}',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Try Again',
        showClass: {
            popup: 'animate__animated animate__shakeX'
        },
        customClass: {
            popup: 'swal2-error-popup'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $('#adminVerificationModal').modal('show');
            $('#modal_admin_password').val('');
            $('#modal_admin_email').focus();
            
            var errorMessage = '{{ session('error') }}';
            if (errorMessage.includes('email')) {
                $('#modal_admin_email').addClass('admin-error');
            } else if (errorMessage.includes('password')) {
                $('#modal_admin_password').addClass('admin-error');
            } else {
                $('#modal_admin_email, #modal_admin_password').addClass('admin-error');
            }
            
            setTimeout(function() {
                $('#modal_admin_email, #modal_admin_password').removeClass('admin-error');
            }, 3000);
        }
    });
</script>
@endif

@if($errors->any())
<script>
    var errorMessages = [];
    @foreach ($errors->all() as $error)
        errorMessages.push('{{ $error }}');
    @endforeach
    
    Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        html: errorMessages.join('<br>'),
        confirmButtonColor: '#d33',
        confirmButtonText: 'Try again',
        showClass: {
            popup: 'animate__animated animate__shakeX'
        },
        customClass: {
            popup: 'swal2-error-popup'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            var hasAdminErrors = errorMessages.some(msg => 
                msg.includes('email') || msg.includes('password') || msg.includes('admin')
            );
            
            if (hasAdminErrors) {
                $('#adminVerificationModal').modal('show');
                $('#modal_admin_password').val('');
                
                if (errorMessages.some(msg => msg.includes('email'))) {
                    $('#modal_admin_email').addClass('admin-error').focus();
                } else if (errorMessages.some(msg => msg.includes('password'))) {
                    $('#modal_admin_password').addClass('admin-error').focus();
                } else {
                    $('#modal_admin_email').addClass('admin-error').focus();
                }
                
                setTimeout(function() {
                    $('#modal_admin_email, #modal_admin_password').removeClass('admin-error');
                }, 3000);
            }
        }
    });
</script>
@endif

<script>
$(document).ready(function() {
  // Initialize Select2 for Travel Order
  $('#travel_higher').select2({
    tags: true,
    placeholder: "Amount",
    allowClear: true
  });

  $('#travel_less').select2({
    tags: true,
    placeholder: "Amount",
    allowClear: true
  });

  // Initialize Select2 for Authority to Deduct
  $('#authority_higher').select2({
    tags: true,
    placeholder: "Amount",
    allowClear: true
  });

  $('#authority_less').select2({
    tags: true,
    placeholder: "Amount",
    allowClear: true
  });

  // Travel Order validation
  $('#travel_higher').on('change', function() {
    let higherValue = $(this).val();
    if (higherValue !== "") {
      if ($("#travel_less option[value='" + higherValue + "']").length === 0) {
        let newOption = new Option(higherValue, higherValue, true, true);
        $('#travel_less').append(newOption).trigger('change');
      } else {
        $('#travel_less').val(higherValue).trigger('change');
      }
    }
  });
  
  $('#travel_less').on('change', function() {
    let higherValue = parseInt($('#travel_higher').val());
    let lessValue = parseInt($(this).val());

    if (lessValue > higherValue) {
      alert("Immediate Supervisor amount must be less than or equal to Head Supervisor amount.");
      $(this).val(higherValue).trigger('change');
    }
  });

  // Authority to Deduct validation
  $('#authority_higher').on('change', function() {
    let higherValue = $(this).val();
    if (higherValue !== "") {
      if ($("#authority_less option[value='" + higherValue + "']").length === 0) {
        let newOption = new Option(higherValue, higherValue, true, true);
        $('#authority_less').append(newOption).trigger('change');
      } else {
        $('#authority_less').val(higherValue).trigger('change');
      }
    }
  });
  
  $('#authority_less').on('change', function() {
    let higherValue = parseInt($('#authority_higher').val());
    let lessValue = parseInt($(this).val());

    if (lessValue > higherValue) {
      alert("Immediate Supervisor amount must be less than or equal to Head Supervisor amount.");
      $(this).val(higherValue).trigger('change');
    }
  });
});
</script>
@endsection