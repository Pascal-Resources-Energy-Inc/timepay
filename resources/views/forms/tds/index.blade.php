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
                @if(Auth::user()->role === 'Admin' || Auth::user()->is_admin)
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

              <form method='get' action="{{ route('tds.index') }}">
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
                      <th>Timestamp</th>
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
                      <th>Document</th>
                      <th>Action</th>
                    </tr>
                  </thead>

                  <tbody>
                    @forelse($tdsRecords as $record)
                    <tr>
                      <td>{{ \Carbon\Carbon::parse($record->created_at)->format('Y-m-d H:i:s') }}</td>
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
                      <td class="text-center">
                        @if($record->document_attachment)
                          <a href="{{ asset('storage/tds_documents/' . $record->document_attachment) }}" 
                            target="_blank" 
                            class="btn btn-sm btn-info" 
                            title="View Document">
                            <i class="ti-file"></i>
                          </a>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
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
      `,
      showCancelButton: true,
      confirmButtonColor: '#ffc107',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Update Status',
      cancelButtonText: 'Cancel',
      preConfirm: () => {
        const newStatus = document.getElementById('new-status').value;
        if (!newStatus) {
          Swal.showValidationMessage('Please select a status');
          return false;
        }
        return newStatus;
      }
    }).then((result) => {
      if (result.isConfirmed) {
        const newStatus = result.value;
        
        Swal.fire({
          title: 'Updating...',
          text: 'Please wait',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        $.ajax({
          url: '{{ url("/tds") }}/' + recordId + '/update-status',
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            status: newStatus
          },
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
});
</script>

<script>
$(document).ready(function() {
  $('select[name="area"]').select2({
    placeholder: '-- Select Area --',
    allowClear: true,
    dropdownParent: $('#registerDealer'),
    width: '100%'
  });

  $('.select2-employee').select2({
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
            more: (params.page * 20) < data.total
          }
        };
      },
      cache: true
    },
    placeholder: '-- Search for an employee --',
    minimumInputLength: 0,
    allowClear: true,
    dropdownParent: $('#setSalesTarget')
  });

  $('#employee_select, #target_month').on('change', function() {
    var userId = $('#employee_select').val();
    var month = $('#target_month').val();
    
    if (userId && month) {
      $.ajax({
        url: '{{ route("tds.get-employee-target") }}',
        method: 'GET',
        data: {
          user_id: userId,
          month: month
        },
        success: function(response) {
          $('#target_amount').val(response.target_amount);
          $('#target_notes').val(response.notes || '');
          
          if (response.target_amount != 200000) {
            $('#current_target_info').text('Current target: ₱' + parseFloat(response.target_amount).toLocaleString());
            $('#current_target_info').addClass('text-info');
          } else {
            $('#current_target_info').text('No target set. Using default: ₱200,000');
            $('#current_target_info').removeClass('text-info');
          }
        },
        error: function() {
          $('#target_amount').val(200000);
          $('#target_notes').val('');
          $('#current_target_info').text('');
        }
      });
    }
  });

  $('#setSalesTarget').on('hidden.bs.modal', function () {
    $('#salesTargetForm')[0].reset();
    $('#employee_select').val(null).trigger('change');
    $('#current_target_info').text('');
  });
});
</script>
@endsection