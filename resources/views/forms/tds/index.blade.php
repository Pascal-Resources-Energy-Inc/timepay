@extends('layouts.header')

@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                        <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="For Delivery" {{ request('status') == 'For Delivery' ? 'selected' : '' }}>For Delivery</option>
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
                      <th>Amount</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>

                  <tbody>
                    @forelse($tdsRecords as $record)
                    <tr>
                      <td>{{ \Carbon\Carbon::parse($record->created_at)->format('Y-m-d H:i:s') }}</td>
                      <td>{{ \Carbon\Carbon::parse($record->date_of_registration)->format('Y-m-d') }}</td>
                      <td>{{ $record->user->name ?? 'N/A' }}</td>
                      <td>{{ $record->region->region_name ?? 'N/A' }}</td>
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
                      <td>₱{{ number_format($record->purchase_amount, 2) }}</td>
                      <td>
                        @if($record->status == 'Delivered')
                          <span class="badge badge-success">Delivered</span>
                        @else
                          <span class="badge badge-warning">For Delivery</span>
                        @endif
                      </td>
                      <td>
                        <button class="btn btn-sm btn-primary" 
                                data-toggle="modal" 
                                data-target="#viewDetails{{ $record->id }}"
                                title="View Details">
                          <i class="ti-eye"></i>
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
                      <td colspan="11" class="text-center">No records found</td>
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

<div class="modal fade" id="registerDealer" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ route('tds.store') }}" method="POST" id="dealerForm">
        @csrf
        <div class="modal-header text-black">
          <h5 class="modal-title">Register New Dealer</h5>
          <button type="button" class="close text-white" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h5 class="mb-3 text-primary">General Details</h5>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Date of Registration <span class="text-danger">*</span></label>
                <input type="date" class="form-control" 
                       name="date_registered" value="{{ old('date_registered') }}" required>
                <small class="form-text text-muted">When the dealer signed-up</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Employee Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="employee_name" value="{{ old('employee_name', Auth::user()->name) }}" 
                       placeholder="Who acquired the dealer?" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Area <span class="text-danger">*</span></label>
                <select class="form-control" name="area" required>
                  <option value="">-- Select Area --</option>
                  @foreach($regions as $region)
                    <option value="{{ $region->id }}" {{ old('area') == $region->id ? 'selected' : '' }}>
                      {{ $region->region_name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <hr class="my-4">
          <h5 class="mb-3 text-primary">Customer Information</h5>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Customer Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="customer_name" value="{{ old('customer_name') }}" 
                       placeholder="Full name of the customer" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Contact Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="contact_no" value="{{ old('contact_no') }}" 
                       placeholder="0912-325-1234" pattern="[0-9]{4}-[0-9]{3}-[0-9]{4}" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Business Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="business_name" value="{{ old('business_name') }}" 
                       placeholder="e.g., Justin's Store" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Business Type <span class="text-danger">*</span></label>
                <select class="form-control" name="business_type" required>
                  <option value="">-- Select Business Type --</option>
                  <option value="Sari-Sari Store" {{ old('business_type') == 'Sari-Sari Store' ? 'selected' : '' }}>Sari-Sari Store</option>
                  <option value="Mini Mart" {{ old('business_type') == 'Mini Mart' ? 'selected' : '' }}>Mini Mart</option>
                  <option value="Retail Shop" {{ old('business_type') == 'Retail Shop' ? 'selected' : '' }}>Retail Shop</option>
                  <option value="Wholesale" {{ old('business_type') == 'Wholesale' ? 'selected' : '' }}>Wholesale</option>
                  <option value="Grocery" {{ old('business_type') == 'Grocery' ? 'selected' : '' }}>Grocery</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Awarded Area</label>
                <input type="text" class="form-control" 
                       name="awarded_area" value="{{ old('awarded_area') }}" 
                       placeholder="For Area Distributors">
                <small class="form-text text-muted">Only applicable for Area Distributors</small>
              </div>
            </div>
          </div>

          <hr class="my-4">
          <h5 class="mb-3 text-primary">Package & Delivery Details</h5>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Package Type <span class="text-danger">*</span></label>
                <select class="form-control" name="package_type" required>
                  <option value="">-- Select Package --</option>
                  <option value="EU" {{ old('package_type') == 'EU' ? 'selected' : '' }}>EU - End User</option>
                  <option value="D" {{ old('package_type') == 'D' ? 'selected' : '' }}>D - Dealer</option>
                  <option value="MD" {{ old('package_type') == 'MD' ? 'selected' : '' }}>MD - Mega Dealer</option>
                  <option value="AD" {{ old('package_type') == 'AD' ? 'selected' : '' }}>AD - Area Distributor</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Purchase Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" 
                       name="purchase_amount" value="{{ old('purchase_amount') }}" 
                       placeholder="25000" min="0" step="0.01" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Lead Generator <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="lead_generator" value="{{ old('lead_generator') }}" 
                       placeholder="e.g., Other Accounts" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Supplier Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="supplier_name" value="{{ old('supplier_name') }}" 
                       placeholder="e.g., MD Monicarl, AD Gorospe, PDI" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Status <span class="text-danger">*</span></label>
                <select class="form-control" name="status" required>
                  <option value="">-- Select Status --</option>
                  <option value="For Delivery" {{ old('status') == 'For Delivery' ? 'selected' : '' }}>For Delivery</option>
                  <option value="Delivered" {{ old('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Timeline</label>
                <input type="date" class="form-control" 
                       name="timeline" value="{{ old('timeline') }}">
                <small class="form-text text-muted">Expected delivery for 'For Delivery' status</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Additional Notes</label>
                <textarea class="form-control" 
                          name="additional_notes" rows="3" 
                          placeholder="Any additional information...">{{ old('additional_notes') }}</textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Register Dealer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="setSalesTarget" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('tds.update-target') }}" method="POST" id="salesTargetForm">
        @csrf
        <div class="modal-header text-black">
          <h5 class="modal-title">Set Monthly Sales Target</h5>
          <button type="button" class="close text-white" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="target_month">Month <span class="text-danger">*</span></label>
            <input type="month" class="form-control" name="month" id="target_month" 
                   value="{{ date('Y-m') }}" required>
          </div>

          <div class="form-group">
            <label for="target_amount">Target Amount <span class="text-danger">*</span></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">₱</span>
              </div>
              <input type="number" class="form-control" name="target_amount" id="target_amount" 
                     value="{{ $stats['monthly_target'] }}" 
                     min="0" step="0.01" required>
            </div>
          </div>

          <div class="form-group">
            <label for="target_notes">Notes (Optional)</label>
            <textarea class="form-control" name="notes" id="target_notes" rows="3" 
                      placeholder="Any notes or comments about this target..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Target</button>
        </div>
      </form>
    </div>
  </div>
</div>


@foreach($tdsRecords as $record)
<div class="modal fade" id="viewDetails{{ $record->id }}" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header text-black">
        <h5 class="modal-title">Dealer Details - {{ $record->customer_name }}</h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <h5 class="text-primary mb-3">General Information</h5>
            <table class="table table-borderless table-sm">
              <tr>
                <th width="45%">Date Registered:</th>
                <td>{{ \Carbon\Carbon::parse($record->date_of_registration)->format('M d, Y') }}</td>
              </tr>
              <tr>
                <th>Employee Name:</th>
                <td>{{ $record->user->name ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Area:</th>
                <td>{{ $record->region->region_name ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Status:</th>
                <td>
                  @if($record->status == 'Delivered')
                    <span class="badge badge-success">Delivered</span>
                  @else
                    <span class="badge badge-warning">For Delivery</span>
                  @endif
                </td>
              </tr>
              @if($record->timeline)
              <tr>
                <th>Timeline:</th>
                <td>{{ \Carbon\Carbon::parse($record->timeline)->format('M d, Y') }}</td>
              </tr>
              @endif
            </table>
          </div>

          <div class="col-md-6">
            <h5 class="text-primary mb-3">Customer Information</h5>
            <table class="table table-borderless table-sm">
              <tr>
                <th width="45%">Customer Name:</th>
                <td>{{ $record->customer_name }}</td>
              </tr>
              <tr>
                <th>Contact Number:</th>
                <td>{{ $record->contact_no }}</td>
              </tr>
              <tr>
                <th>Business Name:</th>
                <td>{{ $record->business_name }}</td>
              </tr>
              <tr>
                <th>Business Type:</th>
                <td>{{ $record->business_type }}</td>
              </tr>
              @if($record->awarded_area)
              <tr>
                <th>Awarded Area:</th>
                <td>{{ $record->awarded_area }}</td>
              </tr>
              @endif
            </table>
          </div>
        </div>

        <hr class="my-4">

        <div class="row">
          <div class="col-md-6">
            <h5 class="text-primary mb-3">Package Details</h5>
            <table class="table table-borderless table-sm">
              <tr>
                <th width="45%">Package Type:</th>
                <td>
                  @if($record->package_type == 'EU')
                    <span class="badge badge-secondary">EU - End User</span>
                  @elseif($record->package_type == 'D')
                    <span class="badge badge-info">D - Dealer</span>
                  @elseif($record->package_type == 'MD')
                    <span class="badge badge-warning">MD - Mega Dealer</span>
                  @elseif($record->package_type == 'AD')
                    <span class="badge badge-primary">AD - Area Distributor</span>
                  @endif
                </td>
              </tr>
              <tr>
                <th>Purchase Amount:</th>
                <td><strong class="text-success">₱{{ number_format($record->purchase_amount, 2) }}</strong></td>
              </tr>
              <tr>
                <th>Lead Generator:</th>
                <td>{{ $record->lead_generator }}</td>
              </tr>
              <tr>
                <th>Supplier Name:</th>
                <td>{{ $record->supplier_name }}</td>
              </tr>
            </table>
          </div>

          <div class="col-md-6">
            <h5 class="text-primary mb-3">Additional Information</h5>
            @if($record->additional_notes)
              <div class="alert alert-info">
                <strong><i class="ti-info-alt"></i> Notes:</strong><br>
                {{ $record->additional_notes }}
              </div>
            @else
              <p class="text-muted"><em>No additional notes available.</em></p>
            @endif
            
            <div class="mt-3">
              <small class="text-muted">
                <i class="ti-time"></i> Created: {{ \Carbon\Carbon::parse($record->created_at)->format('M d, Y h:i A') }}
              </small>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endforeach

<form id="deleteForm" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
</script>
@endsection