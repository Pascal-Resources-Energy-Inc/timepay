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
                {{-- <a href="{{ route('tds.create') }}" class="btn btn-outline-success btn-icon-text">
                  <i class="ti-plus btn-icon-prepend"></i>
                  Register New Sales
                </a> --}}
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
                              @if($record->status != 'Delivered')
                              <button class="btn btn-sm btn-warning edit-status-btn" 
                                      data-id="{{ $record->id }}"
                                      data-status="{{ $record->status }}"
                                      data-customer="{{ $record->customer_name }}"
                                      title="Update Status">
                                  <i class="ti-pencil"></i>
                              </button>
                              @endif
                              @if($record->status == 'Delivered' && auth()->user()->employee->allowed_tds_amount == 1)
                              <button class="btn btn-sm btn-warning edit-amount-btn"
                                      data-id="{{ $record->id }}"
                                      data-status="{{ $record->status }}"
                                      data-customer="{{ $record->customer_name }}"
                                      data-amount="{{ $record->purchase_amount }}"
                                      title="Update Amount">
                                  <i class="ti-pencil"></i>
                              </button>
                              @endif
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

<div class="modal fade" id="editAmountModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="updateAmountForm" method="POST" enctype="multipart/form-data" onsubmit='show();'>
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Update Amount</h5>
          <button type="button" class="close" data-dismiss="modal" style="color: red">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="record_id">
          <div class="form-group">
            <label>Customer</label>
            <input type="text" id="customer_name" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label>Status</label>
            <input type="text" id="status" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label>Purchase Amount&nbsp;<span style="color:red;">*</span></label>
            <input type="number" step="0.01" name="purchase_amount" id="purchase_amount" class="form-control" placeholder="Enter Purchase Amount" required>
          </div>
          <div class="form-group">
            <label>Upload Documents&nbsp;<span style="color:red;">*</span></label>
            <input type="file" name="upload_docs" id="upload_docs" class="swal2-file" accept=".jpg,.jpeg,.png,.pdf" style="margin: 0px" required>
              <small style="display:block; margin-top:5px; color:#666;">
                Accepted: JPG, PNG, PDF (Max: 5MB)
              </small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<form id="deleteForm" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>

@include('forms/tds/sales-target')
@include('forms/tds/new')
@include('forms/tds/view-details')

@endsection

<style>
  .swal2-modal .swal2-icon, .swal2-modal .swal2-success-ring
  {
    margin: 10px 0px !important;
  }
  .loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url("{{ asset('login_css/images/loader.gif') }}") 50% 50% no-repeat white;
    opacity: .8;
    background-size: 120px 120px;
  }
</style>

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
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('dealerForm');
    const submitBtn = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', function (e) {
      if (!form.checkValidity()) {
        return;
      }

      submitBtn.disabled = true;

      // Show loader
      show();
    });

  });

  function show() {
      document.getElementById("loader").style.display = "block";
  }
  // $(document).on('click', '.edit-status-btn', function(e) {
  //   e.preventDefault();
  //   var recordId = $(this).data('id');
  //   var currentStatus = $(this).data('status');
  //   var customerName = $(this).data('customer');

  //   Swal.fire({
  //     title: 'Update Status',
  //     html: `
  //       <p>Customer: <strong>${customerName}</strong></p>
  //       <p>Current Status: <strong>${currentStatus}</strong></p>
  //       <select id="new-status" class="swal2-input" style="width: 70%;">
  //         <option value="">-- Select New Status --</option>
  //         <option value="Decline" ${currentStatus === 'Decline' ? 'selected' : ''}>Decline</option>
  //         <option value="Interested" ${currentStatus === 'Interested' ? 'selected' : ''}>Interested</option>
  //         <option value="For Delivery" ${currentStatus === 'For Delivery' ? 'selected' : ''}>For Delivery</option>
  //         <option value="Delivered" ${currentStatus === 'Delivered' ? 'selected' : ''}>Delivered</option>
  //       </select>
  //       <div id="amount-section mt-2" style="display:none">
  //         <input type="number" class="form-control" name="purchase_amount" value="{{ old('purchase_amount') }}" placeholder="25000" min="0" step="0.01" required>
  //       </div>
  //       <div id="proof-of-payment-section mt-2" style="display: none">
  //         <label for="proof-of-payment" style="display: block; margin-bottom: 5px; font-weight: bold;">
  //           Proof of Payment <span style="color: red;">*</span>
  //         </label>
  //         <input type="file" id="proof-of-payment" class="swal2-file" 
  //                accept=".jpg,.jpeg,.png,.pdf" 
  //                style="width: 70%; padding: 8px; border: 1px solid #d9d9d9; border-radius: 4px;">
  //         <small style="display: block; margin-top: 5px; color: #666;">
  //           Accepted formats: JPG, PNG, PDF (Max: 5MB)
  //         </small>
  //       </div>
  //     `,
  //     showCancelButton: true,
  //     confirmButtonColor: '#ffc107',
  //     cancelButtonColor: '#6c757d',
  //     confirmButtonText: 'Update Status',
  //     cancelButtonText: 'Cancel',
  //     didOpen: () => {
  //       const statusSelect = document.getElementById('new-status');
  //       const proofSection = document.getElementById('proof-of-payment-section');
  //       const amountSection = document.getElementById('amount-section');

  //       // Show/hide proof of payment field based on status
  //       statusSelect.addEventListener('change', function() {
  //         if (this.value === 'Delivered') {
  //           proofSection.style.display = 'block';
  //           amountSection.style.display = 'block';
  //         } else {
  //           proofSection.style.display = 'none';
  //           amountSection.style.display = 'none';
  //         }
  //       });
        
  //       if (statusSelect.value === 'Delivered') {
  //         proofSection.style.display = 'block';
  //         amountSection.style.display = 'block';
  //       }
  //     },
  //     preConfirm: () => {
  //       const newStatus = document.getElementById('new-status').value;
  //       const proofOfPayment = document.getElementById('proof-of-payment').files[0];
        
  //       if (!newStatus) {
  //         Swal.showValidationMessage('Please select a status');
  //         return false;
  //       }
        
  //       if (newStatus === 'Delivered' && !proofOfPayment) {
  //         Swal.showValidationMessage('Proof of payment is required for Delivered status');
  //         return false;
  //       }
        
  //       if (proofOfPayment && proofOfPayment.size > 5 * 1024 * 1024) {
  //         Swal.showValidationMessage('File size must not exceed 5MB');
  //         return false;
  //       }
        
  //       if (proofOfPayment) {
  //         const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
  //         if (!allowedTypes.includes(proofOfPayment.type)) {
  //           Swal.showValidationMessage('Only JPG, PNG, and PDF files are allowed');
  //           return false;
  //         }
  //       }
        
  //       return { status: newStatus, file: proofOfPayment };
  //     }
  //   }).then((result) => {
  //     if (result.isConfirmed) {
  //       const newStatus = result.value.status;
  //       const proofFile = result.value.file;
        
  //       Swal.fire({
  //         title: 'Updating...',
  //         text: 'Please wait',
  //         allowOutsideClick: false,
  //         didOpen: () => {
  //           Swal.showLoading();
  //         }
  //       });

  //       const formData = new FormData();
  //       formData.append('_token', '{{ csrf_token() }}');
  //       formData.append('status', newStatus);
        
  //       if (proofFile) {
  //         formData.append('proof_of_payment', proofFile);
  //       }

  //       $.ajax({
  //         url: '{{ url("/tds") }}/' + recordId + '/update-status',
  //         method: 'POST',
  //         data: formData,
  //         processData: false,
  //         contentType: false,
  //         success: function(response) {
  //           Swal.fire({
  //             icon: 'success',
  //             title: 'Status Updated!',
  //             text: response.message,
  //             timer: 2000,
  //             showConfirmButton: true,
  //             confirmButtonColor: '#28a745'
  //           }).then(() => {
  //             location.reload();
  //           });
  //         },
  //         error: function(xhr) {
  //           Swal.fire({
  //             icon: 'error',
  //             title: 'Update Failed',
  //             text: xhr.responseJSON?.message || 'Failed to update status',
  //             confirmButtonColor: '#dc3545'
  //           });
  //         }
  //       });
  //     }
  //   });
  // });

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

          <select id="new-status" class="swal2-input mb-3">
              <option value="">-- Select New Status --</option>
              <option value="Decline" ${currentStatus === 'Decline' ? 'selected' : ''}>Decline</option>
              <option value="Interested" ${currentStatus === 'Interested' ? 'selected' : ''}>Interested</option>
              <option value="For Delivery" ${currentStatus === 'For Delivery' ? 'selected' : ''}>For Delivery</option>
              <option value="Delivered" ${currentStatus === 'Delivered' ? 'selected' : ''}>Delivered</option>
          </select>

          <div id="supplier-section" class="mb-3">
            <label style="font-weight:bold;">Supplier Name </label><br>
            <input type="text" class="swal2-input" id="supplier-name" placeholder="Enter Supplier Name" style="margin: 0px">
          </div>

          <div id="amount-section" style="display:none;" class="mb-3">
            <label style="font-weight:bold;">Purchase Amount <span style="color:red;">*</span></label><br>
            <input type="number" class="swal2-input" id="purchase-amount" placeholder="Enter Purchase Amount" min="0" step="0.01" style="margin: 0px">
          </div>

          <div id="proof-of-payment-section" style="display:none; margin-top:10px;">
              <label style="font-weight:bold;">
                  Proof of Transaction <span style="color:red;">*</span>
              </label>
              <input type="file" id="proof-of-payment" class="swal2-file"
                      accept=".jpg,.jpeg,.png,.pdf" style="margin: 0px">
              <small style="display:block; margin-top:5px; color:#666;">
                  Accepted: JPG, PNG, PDF (Max: 5MB)
              </small>
          </div>
      `,
      showCancelButton: true,
      confirmButtonText: 'Update Status',
      confirmButtonColor: '#ffc107',
      cancelButtonColor: '#6c757d',

      didOpen: () => {
          const statusSelect = document.getElementById('new-status');
          const proofSection = document.getElementById('proof-of-payment-section');
          const amountSection = document.getElementById('amount-section');
          const supplierSection = document.getElementById('supplier-section');

          function toggleFields() {
              if (statusSelect.value === 'Delivered') {
                  proofSection.style.display = 'block';
                  amountSection.style.display = 'block';
                  supplierSection.style.display = 'block';
              } else {
                  proofSection.style.display = 'none';
                  amountSection.style.display = 'none';
                  supplierSection.style.display = 'none';
              }
          }

          statusSelect.addEventListener('change', toggleFields);
          toggleFields(); // run on open
      },

      preConfirm: () => {
          const newStatus = document.getElementById('new-status').value;
          const proofFile = document.getElementById('proof-of-payment').files[0];
          const amount = document.getElementById('purchase-amount').value;
          const supplier = document.getElementById('supplier-name').value;

          if (!newStatus) {
              Swal.showValidationMessage('Please select a status');
              return false;
          }

          if (newStatus === 'Delivered') {

              if (!amount || amount <= 0) {
                  Swal.showValidationMessage('Purchase amount is required');
                  return false;
              }

              if (!proofFile) {
                  Swal.showValidationMessage('Proof of payment is required');
                  return false;
              }

              if (proofFile.size > 5 * 1024 * 1024) {
                  Swal.showValidationMessage('File must not exceed 5MB');
                  return false;
              }

              const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
              if (!allowedTypes.includes(proofFile.type)) {
                  Swal.showValidationMessage('Only JPG, PNG, and PDF allowed');
                  return false;
              }
          }

          return {
              status: newStatus,
              file: proofFile,
              amount: amount,
              supplier: supplier
          };
      }
    }).then((result) => {
      if (!result.isConfirmed) return;

      // 🔵 If Delivered, show confirmation first
      if (result.value.status === 'Delivered') {

          Swal.fire({
              icon: 'question',
              title: 'Confirm Delivery',
              html: `
                  <p><strong>Purchase Amount:</strong></p>
                  <h3 style="color:#28a745;">₱ ${parseFloat(result.value.amount).toFixed(2)}</h3>
                  <p>Are you sure you want to mark this as <b>Delivered</b>?</p>
              `,
              showCancelButton: true,
              confirmButtonText: 'Yes, Confirm',
              confirmButtonColor: '#28a745',
              cancelButtonColor: '#6c757d'
          }).then((confirmResult) => {

              if (confirmResult.isConfirmed) {
                  submitStatusUpdate(result.value);
              }

          });

      } else {
          // 🔵 For other statuses, submit immediately
          submitStatusUpdate(result.value);
      }
    });

    function submitStatusUpdate(data) {

      Swal.fire({
          title: 'Updating...',
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading()
      });

      const formData = new FormData();
      formData.append('_token', '{{ csrf_token() }}');
      formData.append('status', data.status);

      if (data.amount) {
          formData.append('purchase_amount', data.amount);
      }

      if (data.supplier) {
          formData.append('supplier_name', data.supplier);
      }

      if (data.file) {
          formData.append('proof_of_payment', data.file);
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
                  showConfirmButton: false
              }).then(() => {
                  location.reload();
              });
          },

          error: function(xhr) {
              Swal.fire({
                  icon: 'error',
                  title: 'Update Failed',
                  text: xhr.responseJSON?.message || 'Something went wrong'
              });
          }
      });
    } 
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
  
  $('#lead_generator').on('change', function() {
    var leadGen = $(this).val();
    if (leadGen === 'Events') {
      $('#program_type').prop('required', true);
      $('.program-type-required').show();
    } else {
      $('#program_type').prop('required', false);
      $('.program-type-required').hide();
    }
  });
  
  $(document).on('shown.bs.modal', '.select2-modal', function () {
      const $modal = $(this);
      $modal.find('.select2').select2({
          dropdownParent: $modal
      });
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

    // $('.select2-employee').on('select2:select', function (e) {

    //   const data = e.params.data;
    //   const selectedUserId = data.id;
    //   const selectedMonth = $('#target_month').val();

    //   // 🔥 Auto-fill Date of Joining
    //   if (data.original_date_hired) {
    //       $('#date_started').val(data.original_date_hired).trigger('change');
    //   } else {
    //       $('#date_started').val('');
    //   }

    //   if (selectedUserId && selectedMonth) {
    //       loadEmployeeTarget(selectedUserId, selectedMonth);
    //   }

    // });

    $('.select2-employee').on('select2:select', function (e) {
      const selectedData = e.params.data;
      const selectedUserId = selectedData.id;

      if (selectedData.original_date_hired) {
          $('#date_started').val(selectedData.original_date_hired).trigger('change');
      }

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
    // $('#target_amount').val(200000);
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
        } 
        
        if (response.notes) {
          $('#target_notes').val(response.notes);
        } else {
          $('#target_notes').val('');
        }
      },
      error: function() {
        // $('#target_amount').val(200000);
        $('#current_target_info').html('');
        $('#target_notes').val('');
      }
    });
  }

  // Ian 
  $(document).on('change', '#type', function () {

    const type = $(this).val();
    $('#show_started, #show_prorate, #show_month, #show_target').hide();

    $('#date_started').prop('required', false);
    $('#target_month').prop('required', false);
    $('#target_amount').prop('required', false);

    if (type === 'New') {
      // Existing employee → prorated
      $('#show_started, #show_prorate').fadeIn(200);
      $('#date_started').prop('required', true);

    } else if (type === 'Existing') {
      // New → full month
      $('#show_month, #show_target').fadeIn(200);
      $('#target_month, #target_amount').prop('required', true);
      $('#target_amount').val(200000);
    }
  });

  const monthlyTarget = 200000;
  const totalDays = 30; // Fixed as you requested

  // $(document).on('change', '#date_started', function () {

  //   const selectedDate = new Date($(this).val());
  //   if (!$(this).val()) return;

  //   const monthlyTarget = 200000;
  //   const totalDays = 30;

  //   const day = selectedDate.getDate();
  //   const remainingDays = totalDays - day + 1;
  //   const dailyRate = monthlyTarget / totalDays;
  //   const proratedAmount = dailyRate * remainingDays;

  //   // Format properly
  //   const formattedAmount = proratedAmount.toLocaleString('en-PH', {
  //       minimumFractionDigits: 2,
  //       maximumFractionDigits: 2
  //   });

  //   const formattedDaily = dailyRate.toLocaleString('en-PH', {
  //       minimumFractionDigits: 2,
  //       maximumFractionDigits: 2
  //   });

  //   $('#prorate_amount').val(proratedAmount.toFixed(2)); // keep raw number for form submit

  //   $('#current_target_info').text(`Pro Rate Amount: ₱${formattedDaily} × ${remainingDays} days = ₱${formattedAmount}`);

  // });

  $(document).on('change', '#date_started, #target_month', function () {

    const startDate = $('#date_started').val();
    const targetMonth = $('#target_month').val();

    if (!startDate || !targetMonth) return;

    const selectedDate = new Date(startDate);
    const monthDate = new Date(targetMonth + '-01');

    const year = monthDate.getFullYear();
    const month = monthDate.getMonth();

    // Get total days in selected month
    const totalDays = new Date(year, month + 1, 0).getDate();

    const monthlyTarget = 200000;

    const dayStarted = selectedDate.getDate();

    const remainingDays = totalDays - dayStarted + 1;

    const dailyRate = monthlyTarget / totalDays;

    const proratedAmount = dailyRate * remainingDays;

    const formattedAmount = proratedAmount.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    const formattedDaily = dailyRate.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    $('#prorate_amount').val(proratedAmount.toFixed(2));

    $('#current_target_info').html(
        `Pro Rate Amount: ₱${formattedDaily} × ${remainingDays} days = ₱${formattedAmount}`
    );
  });

  $(document).on('click', '.edit-amount-btn', function () {

    let id       = $(this).data('id');
    let customer = $(this).data('customer');
    let status   = $(this).data('status');
    let amount   = $(this).data('amount');

    $('#customer_name').val(customer);
    $('#status').val(status);
    $('#purchase_amount').val(amount);

    // ✅ safer route handling
    let url = "{{ url('tds/update') }}/" + id;

    $('#updateAmountForm').attr('action', url);

    $('#editAmountModal').modal('show');
  });

});
</script>
@endsection