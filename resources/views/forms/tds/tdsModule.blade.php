@extends('layouts.header')

@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
      
      <div class='row grid-margin'>
        <div class='col-lg-3'>
          <div class="card card-tale">
            <div class="card-body">
              <div class="media">                
                <div class="media-body">
                  <h4 class="mb-4">Monthly Target</h4>
                  <h2 class="card-text">₱{{ number_format($stats['monthly_target'], 2) }}</h2>
                </div>
              </div>
            </div>
          </div>
        </div> 
        <div class='col-lg-3'>
          <div class="card card-dark-blue">
            <div class="card-body">
              <div class="media">                
                <div class="media-body">
                  <h4 class="mb-4">Actual Sales</h4>
                  <h2 class="card-text">₱{{ number_format($stats['actual_sales'], 2) }}</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class='col-lg-3'>
          <div class="card text-success">
            <div class="card-body">
              <div class="media">
                <div class="media-body">
                  <h4 class="mb-4">For Delivery</h4>
                  <h2 class="card-text">{{ $stats['for_delivery'] ?? 0 }}</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class='col-lg-3'>
          <div class="card card-light-danger">
            <div class="card-body">
              <div class="media">                
                <div class="media-body">
                  <h4 class="mb-4">Gap to Goal</h4>
                  <h2 class="card-text">₱{{ number_format($stats['gap_to_goal'], 2) }}</h2>
                </div>
              </div>
            </div>
          </div>
        </div> 
      </div>

      <div class='row'>
        <div class="col-lg-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Sales Performance Portal</h4>
              <p class="card-description">
                <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#registerDealer">
                  <i class="ti-plus btn-icon-prepend"></i>                                                    
                  Register New Sales
                </button>
                @if (auth()->user()->role == 'Admin' || checkUserPrivilege('sales_target', auth()->user()->id) == 'yes')
                    <button type="button" class="btn btn-outline-warning btn-icon-text" data-toggle="modal" data-target="#setSalesTarget">
                        <i class="ti-settings btn-icon-prepend"></i>                                                    
                        Set Sales Target
                    </button>
                @endif
                <a href="{{ route('tds.export', request()->query()) }}" class="btn btn-outline-primary btn-icon-text">
                  <i class="ti-download btn-icon-prepend"></i>                                                    
                  Export to CSV
                </a>
              </p>

              <form method='get' action="{{ route('tds.tdsModule') }}">
                <div class=row>
                  <div class='col-md-2'>
                    <div class="form-group">
                      <label class="text-right">From</label>
                      <input type="date" value='{{ request('from') }}' class="form-control form-control-sm" name="from"
                          max='{{ date('Y-m-d') }}' />
                    </div>
                  </div>
                  <div class='col-md-2'>
                    <div class="form-group">
                      <label class="text-right">To</label>
                      <input type="date" value='{{ request('to') }}' class="form-control form-control-sm" name="to" />
                    </div>
                  </div>
                  <div class='col-md-2'>
                    <div class="form-group">
                      <label class="text-right">Status</label>
                      <select class="form-control form-control-sm" name='status'>
                        <option value="">-- All Status --</option>
                        <option value="Decline" {{ request('status') == 'Decline' ? 'selected' : '' }}>Decline</option>
                        <option value="Interested" {{ request('status') == 'Interested' ? 'selected' : '' }}>Interested</option>
                        <option value="For Delivery" {{ request('status') == 'For Delivery' ? 'selected' : '' }}>For Delivery</option>
                        <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                      </select>
                    </div>
                  </div>
                  <div class='col-md-2'>
                    <div class="form-group">
                      <label class="text-right">Package Type</label>
                      <select class="form-control form-control-sm" name='package'>
                        <option value="">-- All Packages --</option>
                        <option value="EU" {{ request('package') == 'EU' ? 'selected' : '' }}>EU</option>
                        <option value="D" {{ request('package') == 'D' ? 'selected' : '' }}>D</option>
                        <option value="MD" {{ request('package') == 'MD' ? 'selected' : '' }}>MD</option>
                        <option value="AD" {{ request('package') == 'AD' ? 'selected' : '' }}>AD</option>
                      </select>
                    </div>
                  </div>
                  <div class='col-md-2'>
                    <div class="form-group">
                      <label class="text-right">Program Type</label>
                      <select class="form-control form-control-sm" name='program'>
                        <option value="">-- All Programs --</option>
                        <option value="Roadshow" {{ request('program') == 'Roadshow' ? 'selected' : '' }}>Roadshow</option>
                        <option value="Mini-Roadshow" {{ request('program') == 'Mini-Roadshow' ? 'selected' : '' }}>Mini-Roadshow</option>
                        <option value="Non-Roadshow" {{ request('program') == 'Non-Roadshow' ? 'selected' : '' }}>Non-Roadshow</option>
                      </select>
                    </div>
                  </div>
                  <div class='col-md-2'>
                    <button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Filter</button>
                  </div>
                </div>
              </form>

              <div class="table-responsive">
                <table class="table table-hover table-bordered tablewithSearch">
                  <thead>
                      <tr>
                          <th>Date Registered</th>
                          <th>Employee</th>
                          <th>Area</th>
                          <th>Customer Name</th>
                          <th>Business Name</th>
                          <th>Business Type</th>
                          <th>Package</th>
                          <th>Program Type</th>
                          <th>Amount</th>
                          <th>Status</th>
                          <th>Delivery Date</th>
                          {{-- <th>Business Image</th>
                          <th>Document</th> --}}
                          <th>Action</th>
                      </tr>
                  </thead>

                  <tbody>
                      @forelse($tdsRecords as $record)
                      <tr>
                          <td>{{ \Carbon\Carbon::parse($record->date_of_registration)->format('Y-m-d') }}</td>
                          <td>{{ $record->user->name ?? 'N/A' }}</td>
                          <td>{{ optional($record->region)->region ? optional($record->region)->region . ' - ' . optional($record->region)->province . (optional($record->region)->district ? ' - ' . optional($record->region)->district : '') : 'N/A' }}</td>
                          <td>{{ $record->customer_name }}</td>
                          <td>{{ $record->business_name }}</td>
                          <td>{{ $record->business_type }}</td>
                          <td>
                              @if($record->package_type == 'EU')
                                  <span class="badge badge-secondary">EU</span>
                              @elseif($record->package_type == 'D')
                                  <span class="badge badge-info">D</span>
                              @elseif($record->package_type == 'MD')
                                  <span class="badge badge-warning">MD</span>
                              @elseif($record->package_type == 'AD')
                                  <span class="badge badge-primary">AD</span>
                              @endif
                          </td>
                          <td>
                              @if($record->program_type)
                                  <span class="badge badge-light">{{ $record->program_type }}</span>
                              @else
                                  <span class="text-muted">N/A</span>
                              @endif
                          </td>
                          <td>₱{{ number_format($record->purchase_amount, 2) }}</td>
                          <td>
                              @if($record->status == 'Delivered')
                                  <span class="badge badge-success">Delivered</span>
                              @elseif($record->status == 'For Delivery')
                                  <span class="badge badge-warning">For Delivery</span>
                              @elseif($record->status == 'Interested')
                                  <span class="badge badge-info">Interested</span>
                              @elseif($record->status == 'Decline')
                                  <span class="badge badge-danger">Decline</span>
                              @endif
                          </td>
                          <td>
                              @if($record->delivery_date)
                                  {{ \Carbon\Carbon::parse($record->delivery_date)->format('M d, Y') }}
                              @else
                                  <span class="text-muted">-</span>
                              @endif
                          </td>
                          {{-- <td class="text-center">
                              @if($record->business_image)
                                  <button type="button" 
                                          class="btn btn-sm btn-success view-image" 
                                          data-url="{{ asset('uploads/tds_images/' . $record->business_image) }}"
                                          data-business="{{ $record->business_name }}"
                                          title="View Business Image">
                                      <i class="ti-image"></i>
                                  </button>
                              @else
                                  <span class="text-muted">-</span>
                              @endif
                          </td> --}}
                          {{-- <td class="text-center">
                              @if($record->document_attachment)
                                  <button type="button" 
                                          class="btn btn-sm btn-info view-document" 
                                          data-url="{{ asset('uploads/tds_documents/' . $record->document_attachment) }}"
                                          data-filename="{{ $record->document_attachment }}"
                                          title="View Document">
                                      <i class="ti-file"></i>
                                  </button>
                              @else
                                  <span class="text-muted">-</span>
                              @endif
                          </td> --}}
                          <td>
                              <button class="btn btn-sm btn-primary" 
                                      data-toggle="modal" 
                                      data-target="#viewDetails{{ $record->id }}"
                                      title="View Details">
                                  <i class="ti-eye"></i>
                              </button>
                              <button class="btn btn-sm btn-warning edit-status-btn" 
                                      data-id="{{ $record->id }}"
                                      data-status="{{ $record->status }}"
                                      data-customer="{{ $record->customer_name }}"
                                      title="Update Status">
                                  <i class="ti-pencil"></i>
                              </button>
                              <button type="button" class="btn btn-sm btn-danger delete-record" 
                                      data-id="{{ $record->id }}"
                                      data-customer="{{ $record->customer_name }}"
                                      title="Delete Record">
                                  <i class="ti-trash"></i>
                              </button>
                          </td>
                      </tr>
                      @empty
                      <tr>
                          <td colspan="14" class="text-center">No records found</td>
                      </tr>
                      @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

{{-- <div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle">Business Image</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center" style="padding: 20px;">
                <img id="businessImagePreview" 
                     src="" 
                     alt="Business Image" 
                     class="img-fluid"
                     style="max-height: 70vh; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            </div>
            <div class="modal-footer">
                <a id="downloadImageBtn" href="#" class="btn btn-primary" download>
                    <i class="ti-download"></i> Download
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="documentPreviewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Document Preview</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 80vh; padding: 0;">
                <iframe id="documentFrame" 
                        style="width: 100%; height: 100%; border: none;">
                </iframe>
            </div>
            <div class="modal-footer">
                <a id="downloadDocBtn" href="#" class="btn btn-primary" download>
                    <i class="ti-download"></i> Download
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> --}}

<form id="deleteForm" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>

@include('forms/tds/sales-target')
@include('forms/tds/new')
@include('forms/tds/view-details')

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- <script>
$(document).on('click', '.view-image', function(e) {
    e.preventDefault();
    var imageUrl = $(this).data('url');
    var businessName = $(this).data('business');
    
    $('#imageModalTitle').text(businessName + ' - Business Image');
    $('#businessImagePreview').attr('src', imageUrl);
    $('#downloadImageBtn').attr('href', imageUrl);
    $('#imagePreviewModal').modal('show');
});

$('#imagePreviewModal').on('hidden.bs.modal', function () {
    $('#businessImagePreview').attr('src', '');
});

$(document).on('click', '.view-document', function(e) {
    e.preventDefault();
    var docUrl = $(this).data('url');
    var filename = $(this).data('filename');
    
    var extension = filename.split('.').pop().toLowerCase();
    
    var viewerUrl;
    if (extension === 'pdf') {
        viewerUrl = docUrl;
    } else {
        viewerUrl = 'https://docs.google.com/viewer?url=' + encodeURIComponent(docUrl) + '&embedded=true';
    }
    
    $('#documentFrame').attr('src', viewerUrl);
    $('#downloadDocBtn').attr('href', docUrl);
    $('#documentPreviewModal').modal('show');
});

$('#documentPreviewModal').on('hidden.bs.modal', function () {
    $('#documentFrame').attr('src', '');
});
</script>

<script>
$(document).on('click', '.view-document', function(e) {
    e.preventDefault();
    var docUrl = $(this).data('url');
    var filename = $(this).data('filename');
    
    var extension = filename.split('.').pop().toLowerCase();
    
    var viewerUrl;
    if (extension === 'pdf') {
        viewerUrl = docUrl;
    } else {
        viewerUrl = 'https://docs.google.com/viewer?url=' + encodeURIComponent(docUrl) + '&embedded=true';
    }
    
    $('#documentFrame').attr('src', viewerUrl);
    $('#downloadDocBtn').attr('href', docUrl);
    $('#documentPreviewModal').modal('show');
});

$('#documentPreviewModal').on('hidden.bs.modal', function () {
    $('#documentFrame').attr('src', '');
});
</script> --}}

<script>
  @if(session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: '{{ session('success') }}',
      timer: 3000,
      showConfirmButton: true,
      confirmButtonColor: '#28a745'
    });
  @endif

  @if(session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: '{{ session('error') }}',
      confirmButtonColor: '#dc3545'
    });
  @endif

  @if($errors->any())
    Swal.fire({
      icon: 'error',
      title: 'Validation Error',
      html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
      confirmButtonColor: '#dc3545'
    });
    $('#registerDealer').modal('show');
  @endif

  $(document).on('click', '.delete-record', function(e) {
    e.preventDefault();
    var recordId = $(this).data('id');
    var customerName = $(this).data('customer');

    Swal.fire({
      title: 'Are you sure?',
      html: `You are about to delete <strong>${customerName}</strong>'s record.<br>This action cannot be undone!`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        var form = $('#deleteForm');
        form.attr('action', '{{ url("/tds") }}/' + recordId);
        form.submit();
      }
    });
  });

  $(document).on('click', '.edit-status-btn', function(e) {
    e.preventDefault();
    var recordId = $(this).data('id');
    var currentStatus = $(this).data('status');
    var customerName = $(this).data('customer');

    Swal.fire({
      title: 'Update Status',
      html: `
        <p>Customer: <strong>${customerName}</strong></p>
        <p>Current Status: <strong>${currentStatus}</strong></p>
        <select id="new-status" class="swal2-input" style="width: 70%;">
          <option value="">-- Select New Status --</option>
          <option value="Decline" ${currentStatus === 'Decline' ? 'selected' : ''}>Decline</option>
          <option value="Interested" ${currentStatus === 'Interested' ? 'selected' : ''}>Interested</option>
          <option value="For Delivery" ${currentStatus === 'For Delivery' ? 'selected' : ''}>For Delivery</option>
          <option value="Delivered" ${currentStatus === 'Delivered' ? 'selected' : ''}>Delivered</option>
        </select>
        <div id="proof-of-payment-section" style="display: none; margin-top: 15px;">
          <label for="proof-of-payment" style="display: block; margin-bottom: 5px; font-weight: bold;">
            Proof of Payment <span style="color: red;">*</span>
          </label>
          <input type="file" id="proof-of-payment" class="swal2-file" 
                 accept=".jpg,.jpeg,.png,.pdf" 
                 style="width: 70%; padding: 8px; border: 1px solid #d9d9d9; border-radius: 4px;">
          <small style="display: block; margin-top: 5px; color: #666;">
            Accepted formats: JPG, PNG, PDF (Max: 5MB)
          </small>
        </div>
      `,
      showCancelButton: true,
      confirmButtonColor: '#ffc107',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Update Status',
      cancelButtonText: 'Cancel',
      didOpen: () => {
        const statusSelect = document.getElementById('new-status');
        const proofSection = document.getElementById('proof-of-payment-section');
        
        // Show/hide proof of payment field based on status
        statusSelect.addEventListener('change', function() {
          if (this.value === 'Delivered') {
            proofSection.style.display = 'block';
          } else {
            proofSection.style.display = 'none';
          }
        });
        
        if (statusSelect.value === 'Delivered') {
          proofSection.style.display = 'block';
        }
      },
      preConfirm: () => {
        const newStatus = document.getElementById('new-status').value;
        const proofOfPayment = document.getElementById('proof-of-payment').files[0];
        
        if (!newStatus) {
          Swal.showValidationMessage('Please select a status');
          return false;
        }
        
        if (newStatus === 'Delivered' && !proofOfPayment) {
          Swal.showValidationMessage('Proof of payment is required for Delivered status');
          return false;
        }
        
        if (proofOfPayment && proofOfPayment.size > 5 * 1024 * 1024) {
          Swal.showValidationMessage('File size must not exceed 5MB');
          return false;
        }
        
        if (proofOfPayment) {
          const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
          if (!allowedTypes.includes(proofOfPayment.type)) {
            Swal.showValidationMessage('Only JPG, PNG, and PDF files are allowed');
            return false;
          }
        }
        
        return { status: newStatus, file: proofOfPayment };
      }
    }).then((result) => {
      if (result.isConfirmed) {
        const newStatus = result.value.status;
        const proofFile = result.value.file;
        
        Swal.fire({
          title: 'Updating...',
          text: 'Please wait',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('status', newStatus);
        
        if (proofFile) {
          formData.append('proof_of_payment', proofFile);
        }

        $.ajax({
          url: '{{ url("/tds") }}/' + recordId + '/update-status',
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            Swal.fire({
              icon: 'success',
              title: 'Status Updated!',
              text: response.message,
              timer: 2000,
              showConfirmButton: true,
              confirmButtonColor: '#28a745'
            }).then(() => {
              location.reload();
            });
          },
          error: function(xhr) {
            Swal.fire({
              icon: 'error',
              title: 'Update Failed',
              text: xhr.responseJSON?.message || 'Failed to update status',
              confirmButtonColor: '#dc3545'
            });
          }
        });
      }
    });
  });
</script>

<script>
$(document).ready(function() {
  $('#program_type').on('change', function() {
    var programType = $(this).val();
    if (programType === 'Roadshow' || programType === 'Mini-Roadshow') {
      $('#program_area').prop('required', true);
      $('.program-area-required').show();
    } else {
      $('#program_area').prop('required', false);
      $('.program-area-required').hide();
    }
  });

  $('#program_type').trigger('change');

  $('select[name="area"]').select2({
    placeholder: '-- Select Area --',
    allowClear: true,
    dropdownParent: $('#registerDealer'),
    width: '100%'
  });

  let employeeSelect2Instance = null;

  $('#setSalesTarget').on('shown.bs.modal', function () {
    if ($('.select2-employee').hasClass('select2-hidden-accessible')) {
      $('.select2-employee').select2('destroy');
    }

    employeeSelect2Instance = $('.select2-employee').select2({
      dropdownParent: $('#setSalesTarget'),
      placeholder: '-- Type to search employee --',
      allowClear: true,
      minimumInputLength: 2,
      width: '100%',
      ajax: {
        url: '{{ route("tds.get-all-users") }}',
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            search: params.term,
            page: params.page || 1
          };
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          return {
            results: data.results,
            pagination: {
              more: data.pagination && data.pagination.more
            }
          };
        },
        cache: true
      },
      language: {
        inputTooShort: function () {
          return 'Please enter at least 2 characters to search';
        },
        searching: function () {
          return 'Searching employees...';
        },
        noResults: function () {
          return 'No employees found';
        }
      }
    });

    $('.select2-employee').on('select2:select', function (e) {
      const selectedUserId = e.params.data.id;
      const selectedMonth = $('#target_month').val();
      
      if (selectedUserId && selectedMonth) {
        loadEmployeeTarget(selectedUserId, selectedMonth);
      }
    });
  });

  $('#target_month').on('change', function() {
    const selectedUserId = $('#employee_select').val();
    const selectedMonth = $(this).val();
    
    if (selectedUserId && selectedMonth) {
      loadEmployeeTarget(selectedUserId, selectedMonth);
    }
  });

  $('#setSalesTarget').on('hidden.bs.modal', function () {
    if ($('.select2-employee').hasClass('select2-hidden-accessible')) {
      $('.select2-employee').val(null).trigger('change');
    }
    $('#salesTargetForm')[0].reset();
    $('#current_target_info').html('');
    $('#target_amount').val(200000);
    $('#target_month').val('{{ date('Y-m') }}');
  });

  function loadEmployeeTarget(userId, month) {
    $.ajax({
      url: '{{ route("tds.get-employee-target") }}',
      method: 'GET',
      data: {
        user_id: userId,
        month: month
      },
      success: function(response) {
        if (response.target_amount) {
          $('#target_amount').val(response.target_amount);
          $('#current_target_info').html(
            '<span class="text-info">Current target: ₱' + 
            parseFloat(response.target_amount).toLocaleString('en-PH', {minimumFractionDigits: 2}) + 
            '</span>'
          );
        } else {
          $('#target_amount').val(200000);
          $('#current_target_info').html(
            '<span class="text-muted">No existing target for this month</span>'
          );
        }
        
        if (response.notes) {
          $('#target_notes').val(response.notes);
        } else {
          $('#target_notes').val('');
        }
      },
      error: function() {
        $('#target_amount').val(200000);
        $('#current_target_info').html('');
        $('#target_notes').val('');
      }
    });
  }
});
</script>
@endsection