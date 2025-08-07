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
                    <h2 class="card-text">{{($ads_all->where('status','Pending'))->count()}}</h2>
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
                    <h2 class="card-text">{{($ads_all->where('status','Cancelled'))->count() + ($ads_all->where('status','Declined'))->count()}}</h2>
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
                    <h2 class="card-text">{{($ads_all->where('status','Approved'))->count()}}</h2>
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
                <h4 class="card-title">Authority To Deduct</h4>
                <p class="card-description">
                  <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#authorityDeductModal">
                    <i class="ti-plus btn-icon-prepend"></i>
                    Apply Authority To Deduct
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
                        <th>Action</th>
                        <th>Date Filed</th>
                        <th>Type of Deduction</th>
                        <th>Start of Deduction</th>
                        <th>Number of Deduction</th>
                        <th>Amount Per Cut Off</th>
                        <th>Total Amount</th>
                        <th>Approvers</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($ads as $ad)
                      <tr>
                        <td>
                          @if ($ad->status == 'Pending' && $ad->level == 0)
                            <button type="button" class="btn btn-info btn-rounded btn-icon" data-toggle="modal" data-target="#view-ad-{{ $ad->id }}" title="View Approved">
                              <i class="ti-eye btn-icon-prepend"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-rounded btn-icon" data-toggle="modal" data-target="#edit_ad{{ $ad->id }}" title="Edit">
                              <i class="ti-pencil-alt"></i>
                            </button>
                            <button title="Cancel" onclick="cancel({{ $ad->id }})"  class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>
                          @elseif ($ad->status == 'Pending' && $ad->level > 0)
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#view-ad-{{ $ad->id }}">
                                <i class="ti-eye"></i> View
                            </button>
                          @elseif ($ad->status == 'Approved')
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#view-ad-{{ $ad->id }}">
                                <i class="ti-eye"></i> View
                            </button>
                          @else
                            <button type="button" class="btn btn-info btn-rounded btn-icon" data-toggle="modal" data-target="#view-ad-{{ $ad->id }}" title="View Approved">
                              <i class="ti-eye btn-icon-prepend"></i>
                            </button>
                          @endif
                        </td>

                        <td>{{ date('M. d, Y', strtotime($ad->created_at)) }}</td>
                        <td>{{ $ad->type_of_deduction ?? 'N/A' }}</td>
                        <td>{{ date('M. d, Y', strtotime($ad->start_date ?? '')) }}</td>
                        <td>{{ $ad->frequency ?? 'N/A' }}</td>
                        <td>{{ number_format($ad->deductible ?? 0, 2) }}</td>
                        <td>{{ number_format($ad->amount ?? 0, 2) }}</td>
                        <td>
                          @php
                              // Get required approvers based on deduction amount and threshold
                              $deductionAmount = $ad->amount ?? 0;
                              $currentEmployee = auth()->user()->employee;
                              $requiredApprovers = $getApproverForEmployee($currentEmployee, $deductionAmount);
                          @endphp

                          @if($requiredApprovers->count() > 0)
                              @foreach($requiredApprovers as $approver)
                                  @if($approver->user)
                                      @php
                                          $name = $approver->user->name ?? 'Unknown';
                                          $approver_level = $approver->user->employee->level ?? 0;
                                          $statusLabel = '';
                                          $badgeClass = '';

                                          // Determine approval status
                                          if ($ad->level >= $approver_level) {
                                              if ($ad->level == 0 && $ad->status == 'Declined') {
                                                  $statusLabel = 'Declined';
                                                  $badgeClass = 'danger';
                                              } else {
                                                  $statusLabel = 'Approved';
                                                  $badgeClass = 'success';
                                              }
                                          } else {
                                              if ($ad->status == 'Approved') {
                                                  $statusLabel = 'Approved';
                                                  $badgeClass = 'success';
                                              } elseif ($ad->status == 'Declined') {
                                                  $statusLabel = 'Declined';
                                                  $badgeClass = 'danger';
                                              } else {
                                                  $statusLabel = 'Pending';
                                                  $badgeClass = 'warning';
                                              }
                                          }
                                      @endphp

                                      <div class="mb-1">
                                          {{ $name }}
                                          @if($statusLabel && $badgeClass)
                                              - <label class="badge badge-{{ $badgeClass }} mt-1">{{ $statusLabel }}</label>
                                          @endif
                                      </div>
                                  @endif
                              @endforeach
                          @else
                              <span class="text-muted">No approver assigned</span>
                          @endif
                        </td>
                        <td>
                          @if ($ad->status == 'Pending')
                            <label class="badge badge-warning">{{ $ad->status }}</label>
                          @elseif ($ad->status == 'Approved')
                            <label class="badge badge-success">{{ $ad->status }}</label>
                          @elseif (in_array($ad->status, ['Declined', 'Cancelled']))
                            <label class="badge badge-danger">{{ $ad->status }}</label>
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
        text: "You want to cancel this deduction?",
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
                url: "disable-ad/" + id,
                method: "GET",
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (loader) {
                        loader.style.display = "none";
                    }
                    
                    Swal.fire({
                        title: "Cancelled!",
                        text: "Deduction has been cancelled!",
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
                    // Hide loader on error
                    if (loader) {
                        loader.style.display = "none";
                    }
                    
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to cancel deduction. Please try again.",
                        icon: "error"
                    });
                }
            });
        } else {
            Swal.fire({
                text: "Deduction cancellation was stopped.",
                icon: "info"
            });
        }
    });
}

	</script>
@endsection


@include('forms.authoritydeduct.apply_ad')

@foreach ($ads as $ad)
 @include('forms.authoritydeduct.view_ad')
 @include('forms.authoritydeduct.edit_ad')
@endforeach  