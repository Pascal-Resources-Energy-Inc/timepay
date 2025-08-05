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
                    <h2 class="card-text">{{($tos_all->where('status','Pending'))->count()}}</h2>
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
                    <h2 class="card-text">{{($tos_all->where('status','Cancelled'))->count() + ($tos_all->where('status','Declined'))->count()}}</h2>
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
                    <h2 class="card-text">{{($tos_all->where('status','Approved'))->count()}}</h2>
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
                <h4 class="card-title">Travel Order</h4>
                <p class="card-description">
                 <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#travelOrderModal">
                    <i class="ti-plus btn-icon-prepend"></i>
                    Apply Travel Order
                  </button>
                </p>
                <form method='get' onsubmit='show();' enctype="multipart/form-data">
                  <div class="row">
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
                        <input type="date" class="form-control form-control-sm" id='to' name="to" value="{{ old('to', $to) }}" required />
                      </div>
                    </div>

                    <div class='col-md-2 mr-2'>
                      <div class="form-group">
                        <label class="text-right">Status</label>
                        <select data-placeholder="Select Status" class="form-control form-control-sm" style='width:100%;' name='status' required>
                          <option value="">-- Select Status --</option>
                          <option value="Approved" @if ('Approved' == $status) selected @endif>Approved</option>
                          <option value="Pending" @if ('Pending' == $status) selected @endif>Pending</option>
                          <option value="Cancelled" @if ('Cancelled' == $status) selected @endif>Cancelled</option>
                          <option value="Declined" @if ('Declined' == $status) selected @endif>Declined</option>
                          <option value="All" {{ $status == 'All' ? 'selected' : '' }}>All</option>
                        </select>
                      </div>
                    </div>

                    <div class='col-md-2'>
                      <div class="form-group">
                        <label class="text-right">Show</label>
                        <select name="limit" class="form-control form-control-sm">
                          <option value="5" {{ request('limit') == 5 ? 'selected' : '' }}>5</option>
                          <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                          <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25</option>
                          <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                          <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100</option>
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
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Destination</th>
                        <th>Purpose</th>
                        <th>Approvers </th> 
                        <th>Status</th>     
                        <th>Attachment</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($tos as $to)
                      <tr>
                      <td id="tdActionId{{ $to->id }}" data-id="{{ $to->id }}">
                          @if ($to->status == 'Pending' and $to->level == 0)
                            <button type="button" class="btn btn-info btn-rounded btn-icon" data-toggle="modal" data-target="#to-view-approved-{{ $to->id }}" title="View Approved">
                              <i class="ti-eye btn-icon-prepend"></i>
                            </button>          
                            <button type="button" id="edit{{ $to->id }}" class="btn btn-primary btn-rounded btn-icon"
                                    data-target="#edit-to-{{ $to->id }}" data-toggle="modal" title='Edit'>
                                    <i class="ti-pencil-alt"></i>
                            </button>
                            <button title='Cancel' id="{{ $to->id }}" onclick="cancel({{ $to->id }})"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>
                          @elseif ($to->status == 'Pending' and $to->level > 0)
                            <button type="button" id="view{{ $to->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#to-view-approved-{{ $to->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>       
                          @elseif ($to->status == 'Approved')   
                          <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#to-view-approved{{ $to->id }}" title="View Approved">
                            <i class="ti-eye btn-icon-prepend"></i> View
                          </button>
                          @else
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#to-view-declined-{{ $to->id }}" title="View Declined Remarks">
                            <i class="ti-eye btn-icon-prepend"></i> View
                          </button>                                                                                  
                          @endif
                        </td> 
                        <td> {{ date('M. d, Y', strtotime($to->created_at)) }} - {{ date('h:i A', strtotime($to->created_at)) }} </td>
                        <td> {{ date('M. d, Y', strtotime($to->date_from)) }} - {{ date('M. d, Y', strtotime($to->date_to)) }} </td>
                        <td> {{ date('h:i A', strtotime($to->date_from)) }} </td>
                        <td>{{ date('h:i A', strtotime($to->date_to)) }} </td>
                        <td> {{$to->destination}}</td>
                        <td> {{$to->purpose}}</td>
                        @if ($to->status == 'Approved' || $to->status == 'Declined')
                                <td>
                                    {{-- Display first approver with status badge --}}
                                    @if($to->approvedBy)
                                        @php
                                            $firstName = $to->approvedBy->name ?? $to->approvedBy->full_name ?? 'Unknown';
                                            $firstStatusLabel = '';
                                            $firstBadgeClass = '';

                                            if ($to->level >= 1) {
                                                if ($to->level == 0 && $to->status == 'Declined') {
                                                    $firstStatusLabel = 'Declined';
                                                    $firstBadgeClass = 'danger';
                                                } else {
                                                    $firstStatusLabel = 'Approved';
                                                    $firstBadgeClass = 'success';
                                                }
                                            } else {
                                                if ($to->status == 'Approved') {
                                                    $firstStatusLabel = 'Approved';
                                                    $firstBadgeClass = 'success';
                                                } elseif ($to->status == 'Declined') {
                                                    $firstStatusLabel = 'Declined';
                                                    $firstBadgeClass = 'danger';
                                                } else {
                                                    $firstStatusLabel = 'Pending';
                                                    $firstBadgeClass = 'warning';
                                                }
                                            }
                                        @endphp
                                        <div>{{ $firstName }} - <label class="badge badge-{{ $firstBadgeClass }} mt-1">{{ $firstStatusLabel }}</label></div>
                                    @endif
                                    
                                    {{-- Display last approver with status badge --}}
                                    @if($to->last_approver)
                                        @php
                                            $lastName = $to->last_approver->name ?? $to->last_approver->full_name ?? 'Unknown';
                                            $lastStatusLabel = '';
                                            $lastBadgeClass = '';

                                            // Assuming last approver is level 2 or higher
                                            if ($to->level >= 2) {
                                                if ($to->level == 0 && $to->status == 'Declined') {
                                                    $lastStatusLabel = 'Declined';
                                                    $lastBadgeClass = 'danger';
                                                } else {
                                                    $lastStatusLabel = 'Approved';
                                                    $lastBadgeClass = 'success';
                                                }
                                            } else {
                                                if ($to->status == 'Approved') {
                                                    $lastStatusLabel = 'Approved';
                                                    $lastBadgeClass = 'success';
                                                } elseif ($to->status == 'Declined') {
                                                    $lastStatusLabel = 'Declined';
                                                    $lastBadgeClass = 'danger';
                                                } else {
                                                    $lastStatusLabel = 'Pending';
                                                    $lastBadgeClass = 'warning';
                                                }
                                            }
                                        @endphp
                                        <div>{{ $lastName }} - <label class="badge badge-{{ $lastBadgeClass }} mt-1">{{ $lastStatusLabel }}</label></div>
                                    @endif
                                </td>
                        @endif
                         @if ($to->status == 'Pending' || $to->status == 'Cancelled')
                        <td id="tdStatus{{ $to->id }}">
                            @foreach($to->approver as $approver)
                                @php
                                    $name = $approver->approver_info->name;
                                    $statusLabel = '';
                                    $badgeClass = '';

                                    if ($to->status === 'Cancelled') {
                                        $statusLabel = 'Cancelled';
                                        $badgeClass = 'danger';
                                    } elseif ($to->level >= $approver->level) {
                                        if ($to->level == 0 && $to->status == 'Declined') {
                                            $statusLabel = 'Declined';
                                            $badgeClass = 'danger';
                                        } else {
                                            $statusLabel = 'Approved';
                                            $badgeClass = 'success';
                                        }
                                    } else {
                                        if ($to->status == 'Approved') {
                                            $statusLabel = 'Approved';
                                            $badgeClass = 'success';
                                        } elseif ($to->status == 'Declined') {
                                            $statusLabel = 'Declined';
                                            $badgeClass = 'danger';
                                        } else {
                                            $statusLabel = 'Pending';
                                            $badgeClass = 'warning';
                                        }
                                    }
                                @endphp

                                <div>{{ $name }} - <label class="badge badge-{{ $badgeClass }} mt-1">{{ $statusLabel }}</label></div>
                            @endforeach
                        </td>
                        @endif
                        <td id="tdStatus{{ $to->id }}">
                          @if ($to->status == 'Pending')
                            <label class="badge badge-warning">{{ $to->status }}</label>
                          @elseif($to->status == 'Approved')
                            <label class="badge badge-success">{{ $to->status }}</label>
                          @elseif($to->status == 'Declined' or $to->status == 'Cancelled')
                            <label class="badge badge-danger">{{ $to->status }}</label>
                          @endif                        
                        </td>
                        <td>
                           <button type="button" class="btn btn-primary btn-sm" title="Attachment" style="" data-toggle="modal" data-target="#view-modal-{{ $to->id }}" title="View">
                            <i class="ti-folder"></i>
                          </button>
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

@foreach ($tos as $to)
  @include('forms.travelorder.edit_to')
  @include('forms.travelorder.view_to')
@endforeach  

@include('forms.travelorder.view_toform')
@include('forms.travelorder.apply_to')

@endsection
@section('obScript')
	<script>
		function cancel(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to cancel this TO?",
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
                url: "disable-to/" + id,
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
                        text: "TO has been cancelled!",
                        icon: "success"
                    }).then(function() {
                        location.reload();
                        
                        // Find the status cell and update it
                        const statusCell = document.querySelector(`tr:has(button[onclick="cancel(${id})"]) .badge`);
                        if (statusCell) {
                            statusCell.className = "badge badge-danger";
                            statusCell.textContent = "Cancelled";
                        }
                        
                        // Hide the cancel button
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
                        text: "Failed to cancel TO. Please try again.",
                        icon: "error"
                    });
                }
            });
        } else {
            Swal.fire({
                text: "TO cancellation was stopped.",
                icon: "info"
            });
        }
    });
}

	</script>

  
@foreach ($tos as $to)
  @include('for-approval.remarks.view-toapproved')
  @include('for-approval.remarks.view-todeclined')
  @include('for-approval.view-toManager') 
@endforeach

@endsection