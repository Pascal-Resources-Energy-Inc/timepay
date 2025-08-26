@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row grid-margin'>
          <div class='col-lg-2 '>
            <div class="card card-tale">
              <div class="card-body">
                <div class="media">                
                  <div class="media-body">
                    <h4 class="mb-4">Pending</h4>
                    <h2 class="card-text">{{ ($coes_all->where('status','Pending'))->count() }}</h2>
                  </div>
                </div>
              </div>
            </div>
          </div> 
          <div class='col-lg-2'>
            <div class="card card-light-danger">
              <div class="card-body">
                <div class="media">
                  <div class="media-body">
                    <h5 class="mb-4">Declined/Cancelled</h5>
                    <h2 class="card-text">{{ ($coes_all->where('status','Cancelled'))->count() + ($coes_all->where('status','Declined'))->count()}}</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class='col-lg-2'>
            <div class="card text-success">
              <div class="card-body">
                <div class="media">                
                  <div class="media-body">
                    <h4 class="mb-4">Approved</h4>
                    <h2 class="card-text">{{ ($coes_all->where('status','Approved'))->count() }}</h2>
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
                <h4 class="card-title">COE Request</h4>
                <p class="card-description">
                 <!-- Button -->
                  <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#coeRequestModal">
                    <i class="ti-plus btn-icon-prepend"></i>
                    Apply COE Request
                  </button>

                </p>
                <form method='get' onsubmit='show();' enctype="multipart/form-data">
                  <div class=row>
                    <div class='col-md-2'>
                      <div class="form-group">
                        <label class="text-right">From</label>
                        <input type="date" value='{{$from}}' class="form-control form-control-sm" name="from"
                            max='{{ date('Y-m-d') }}' onchange='get_min(this.value);' required />
                      </div>
                    </div>
                    <div class='col-md-2'>
                      <div class="form-group">
                        <label class="text-right">To</label>
                        <input type="date" value='{{$to}}' class="form-control form-control-sm" id='to' name="to" required />
                      </div>
                    </div>
                    <div class='col-md-2 mr-2'>
                      <div class="form-group">
                        <label class="text-right">Status</label>
                        <select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status' required>
                          <option value="">-- Select Status --</option>
                          <option value="Approved" @if ('Approved' == $status) selected @endif>Approved</option>
                          <option value="Pending" @if ('Pending' == $status) selected @endif>Pending</option>
                          <option value="Cancelled" @if ('Cancelled' == $status) selected @endif>Cancelled</option>
                          <option value="Declined" @if ('Declined' == $status) selected @endif>Declined</option>
                        </select>
                      </div>
                    </div>
                    <div class='col-md-2'>
                      <div class="form-group">
                        <label class="invisible">Filter</label>
                        <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Filter</button>
                      </div>
                    </div>
                  </div>
                </form>
                
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>Action </th>
                        <th>Date Filed</th>
                        <th>Reason</th>
                        <th>Employment Status</th>
                        <th>Purpose</th>
                        <th>Receive Method</th>
                        <th>Approvers </th>
                        <th>Status </th>                                   
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($coes as $coe)
                      <tr>
                        <td>
                          @if ($coe->status == 'Pending' && $coe->level == 0)
                            <button type="button" class="btn btn-info btn-rounded btn-icon" data-toggle="modal" data-target="#view-coe-{{ $coe->id }}" title="View Approved">
                              <i class="ti-eye btn-icon-prepend"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-rounded btn-icon" data-toggle="modal" data-target="#edit-coe-{{ $coe->id }}" title="Edit">
                              <i class="ti-pencil-alt"></i>
                            </button>
                            <button title="Cancel" onclick="cancel({{ $coe->id }})"  class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>
                          @elseif ($coe->status == 'Pending' && $coe->level > 0)
                            <button type="button" class="btn btn-primary btn-rounded btn-icon" data-toggle="modal" data-target="#view_coe{{ $coe->id }}" title="View">
                              <i class="ti-eye"></i>
                            </button>
                          @elseif ($coe->status == 'Approved')
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#edit_coe{{ $coe->id }}">
                                <i class="ti-eye"></i> View
                            </button>
                          @else
                            <button type="button" class="btn btn-info btn-rounded btn-icon" data-toggle="modal" data-target="#view-approved-{{ $coe->id }}" title="View Approved">
                              <i class="ti-eye btn-icon-prepend"></i>
                            </button>
                          @endif
                        </td>

                        <td>{{ date('M. d, Y', strtotime($coe->created_at)) }}</td>
                        <td>{{ $coe->reason_for_request ?? 'N/A' }}</td>
                        <td>{{ $coe->employment_status ?? 'N/A' }}</td>
                        <td>{{ $coe->purpose ?? 'N/A' }}</td>
                        <td>{{ $coe->receive_method ?? 'N/A' }}</td>
                        <td>
                          @php
                            $approver = $getApproverForEmployee($coe->user->employee);
                            $employee_company = $coe->user->employee->company_code ?? $coe->user->employee->company_id ?? null;
                          @endphp

                          @if($approver)
                            <div>{{ $approver->user->name }}</div>
                            @if(!$employee_company)
                              <small class="text-muted">(Default - No company assigned)</small>
                            @endif
                          @else
                            <div class="text-danger">No COE approver available</div>
                          @endif
                          </td>
                        <td>
                          @if ($coe->status == 'Pending')
                            <label class="badge badge-warning">{{ $coe->status }}</label>
                          @elseif ($coe->status == 'Approved')
                            <label class="badge badge-success">{{ $coe->status }}</label>
                          @elseif (in_array($coe->status, ['Declined', 'Cancelled']))
                            <label class="badge badge-danger">{{ $coe->status }}</label>
                          @endif
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>


@endsection
@section('obScript')
	<script>
		function cancel(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to cancel this COE?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: 'Yes, cancel it!',
        cancelButtonText: 'No, keep it',
        dangerMode: true,
    }).then((result) => { 
        if (result.isConfirmed) {
            const loader = document.getElementById("loader");
            if (loader) {
                loader.style.display = "block";
            }
            
            $.ajax({
                url: "disable-coe/" + id,
                method: "GET",
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    // Hide loader
                    if (loader) {
                        loader.style.display = "none";
                    }
                    
                    Swal.fire({
                        title: "Cancelled!",
                        text: "COE has been cancelled!",
                        icon: "success"
                    }).then(function() {
                        location.reload();

                        const statusCell = document.querySelector(`tr:has(button[onclick="cancel(${id})"]) .badge`);
                        if (statusCell) {
                            statusCell.className = "badge badge-danger";
                            statusCell.textContent = "Cancelled";
                        }
                        
                        const cancelButton = document.querySelector(`button[onclick="cancel(${id})"]`);
                        if (cancelButton) {
                            cancelButton.style.display = "none";
                        }
                    });
                },
                error: function(xhr, status, error) {
                    if (loader) {
                        loader.style.display = "none";
                    }
                    
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to cancel COE. Please try again.",
                        icon: "error"
                    });
                }
            });
        } else {
            Swal.fire({
                text: "COE cancellation was stopped.",
                icon: "info"
            });
        }
    });
}

	</script>
@endsection

@include('forms.coerequest.apply_coe')

@foreach ($coes as $coe)
 @include('forms.coerequest.view_coe')
 @include('forms.coerequest.edit_coe')
@endforeach  
