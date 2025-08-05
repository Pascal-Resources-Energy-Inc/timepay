<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


  </style>

@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row grid-margin'>
          <div class='col-lg-2 mt-2'>
            <div class="card card-tale">
              <div class="card-body">
                <div class="media">                
                  <div class="media-body">
                    <h4 class="mb-4">For Approval</h4>
                    <a href="/for-official-business?status=Pending" class="h2 card-text text-white">{{$for_approval}}</a>
                  </div>
                </div>
              </div>
            </div>
          </div> 
          <div class='col-lg-2 mt-2'>
            <div class="card card-dark-blue">
              <div class="card-body">
                <div class="media">                
                  <div class="media-body">
                    <h4 class="mb-4">Approved</h4>
                    <a href="/for-official-business?status=Approved" class="h2 card-text text-white">{{$approved}}</a>
                  </div>
                </div>
              </div>
            </div>
          </div> 
          <div class='col-lg-2 mt-2'>
            <div class="card card-light-danger">
              <div class="card-body">
                <div class="media">                
                  <div class="media-body">
                    <h6 class="mb-4">Declined / Rejected</h6>
                    <a href="/for-official-business?status=Declined" class="h2 card-text text-white">{{$declined}}</a>
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
                <h4 class="card-title">For Approval Travel Order</h4>

                <form method='get' onsubmit='show();' enctype="multipart/form-data">
                  <div class=row>
                    <div class='col-md-2'>
                      <div class="form-group">
                        <label class="text-right">From</label>
                        <input type="date" value='{{$from}}' class="form-control form-control-sm" name="from" onchange='get_min(this.value);' required />
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
                      <button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Filter</button>
                    </div>
                  </div>
                </form>

                @if(empty($status) || $status == 'Pending')
                  <label>
                    <input type="checkbox" id="selectAll">
                    <span id="labelSelectAll">Select All</span> 
                  </label>
                @endif

                <button class="btn btn-success btn-sm mb-2" id="approveAllBtn" style="display: none;">Approve</button>
                <button class="btn btn-danger btn-sm mb-2" id="disApproveAllBtn" style="display: none;">Disapprove</button>
                
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        @if(empty($status) || $status == 'Pending')
                          <th>
                            Select
                          </th> 
                        @endif
                        <th>Action </th> 
                        <th>Employee Name</th>
                        <th>Date Filed</th>
                        <th>Date</th>
                        <th>Time In</th>
                         <th>Time Out</th>
                        <th>Destination</th>
                        <th>Purpose</th>
                        <th>Approvers</th> 
                        <th>Status</th>
                        <th>Attachment</th>
                      </tr>
                    </thead>
                    <tbody> 
                      @foreach ($tos as $form_approval)
                      <tr>
                        @if(empty($status) || $status == 'Pending')
                          <td align="center">
                            @foreach($form_approval->approver as $k => $approver)
                              @if($approver->approver_id == $approver_id && $form_approval->level == $k && $form_approval->status == 'Pending')
                                
                                  <input type="checkbox" class="checkbox-item" data-id="{{$form_approval->id}}">
                                </td>
                              @endif
                            @endforeach
                          </td>
                        @endif
                       <td align="center" id="tdActionId{{ $form_approval->id }}" data-id="{{ $form_approval->id }}">
                        @if($form_approval->status == 'Approved')
                          <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#to-view-approved{{ $form_approval->id }}" title="View Approved">
                            <i class="ti-eye btn-icon-prepend"></i> View
                          </button>
                        @elseif($form_approval->status == 'Declined')
                          <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#to-view-declined-{{ $form_approval->id }}" title="View Declined Remarks">
                            <i class="ti-eye btn-icon-prepend"></i> View
                          </button>
                        @else
                          @foreach($form_approval->approver as $k => $approver)
                            @if($approver->approver_id == $approver_id && $form_approval->level == $k && $form_approval->status == 'Pending')
                              <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#to-view-modal-{{ $form_approval->id }}" title="View">
                                <i class="ti-eye btn-icon-prepend"></i> 
                              </button>
                              <button type="button" class="btn btn-success btn-sm" id="{{ $form_approval->id }}" data-target="#to-approved-remarks-{{ $form_approval->id }}" data-toggle="modal" title="Approve">
                                <i class="ti-check btn-icon-prepend"></i>                                                    
                              </button>
                              <button type="button" class="btn btn-danger btn-sm" id="{{ $form_approval->id }}" data-target="#to-declined-remarks-{{ $form_approval->id }}" data-toggle="modal" title="Decline">
                                <i class="ti-close btn-icon-prepend"></i>                                                    
                              </button>
                            @endif
                          @endforeach
                        @endif
                      </td>
                        <td>
                            <strong>{{$form_approval->user->name}}</strong> <br>
                            <small>Position : {{$form_approval->user->employee->position}}</small> <br>
                            <small>Location : {{$form_approval->user->employee->location}}</small> <br>
                            <small>Department : {{ $form_approval->user->employee->department ? $form_approval->user->employee->department->name : ""}}</small>
                        </td>
                        <td> {{ date('M. d, Y', strtotime($form_approval->created_at)) }} - {{ date('h:i A', strtotime($form_approval->created_at)) }} </td>
                        <td> {{ date('M. d, Y', strtotime($form_approval->date_from)) }} - {{ date('M. d, Y', strtotime($form_approval->date_to)) }}</td>
                        <td> {{ date('h:i A', strtotime($form_approval->date_from)) }} </td>
                        <td> {{ date('h:i A', strtotime($form_approval->date_to)) }} </td>
                        <td> {{$form_approval->destination}}</td>
                        <td> {{$form_approval->purpose}}</td>
                        <td id="tdStatus{{ $form_approval->id }}">
                          @foreach($form_approval->approver as $approver)
                              @php
                                  $name = $approver->approver_info->name;
                                  $statusLabel = '';
                                  $badgeClass = '';

                                  if ($form_approval->level >= $approver->level) {
                                      if ($form_approval->level == 0 && $form_approval->status == 'Declined') {
                                          $statusLabel = 'Declined';
                                          $badgeClass = 'danger';
                                      } else {
                                          $statusLabel = 'Approved';
                                          $badgeClass = 'success';
                                      }
                                  } else {
                                      if ($form_approval->status == 'Approved') {
                                          $statusLabel = 'Approved';
                                          $badgeClass = 'success';
                                      } elseif ($form_approval->status == 'Declined') {
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
                        <td>
                          @if ($form_approval->status == 'Pending')
                            <label class="badge badge-warning">{{ $form_approval->status }}</label>
                          @elseif($form_approval->status == 'Approved')
                            <label class="badge badge-success" title="{{$form_approval->approval_remarks}}">{{ $form_approval->status }}</label>
                          @elseif($form_approval->status == 'Declined' || $form_approval->status == 'Cancelled')
                            <label class="badge badge-danger" title="{{$form_approval->approval_remarks}}">{{ $form_approval->status }}</label>
                          @endif  
                        </td>
                        <!-- <td>
                          @if($form_approval->attachment)
                          <a href="{{url($form_approval->attachment)}}" target='_blank' class="text-start"><button type="button" class="btn btn-outline-info btn-sm ">View Attachment</button></a>
                          @endif
                        </td> -->
                        <td>
                        <button type="button" class="btn btn-primary btn-sm" title="Attachment" style="" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View">
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

<script>
$(document).ready(function() {
    console.log('JavaScript loaded successfully');

    const $checkboxItems = $('.checkbox-item');
    const $approveBtn = $('#approveAllBtn');
    const $disapproveBtn = $('#disApproveAllBtn');
    const $selectAll = $('#selectAll');
    const $labelSelectAll = $('#labelSelectAll');
    const loader = document.getElementById("loader");

    function updateSelectedCount() {
        const count = $checkboxItems.filter(':checked').length;
        $approveBtn.text(`(${count}) Approve`);
        $disapproveBtn.text(`(${count}) Disapprove`);
        console.log('Updated selected count:', count);
    }

    function handleCheckboxToggle() {
        const count = $checkboxItems.filter(':checked').length;
        const anyChecked = count > 0;

        $approveBtn.toggle(anyChecked);
        $disapproveBtn.toggle(anyChecked);
        $labelSelectAll.text($selectAll.prop('checked') ? 'Unselect All' : 'Select All');

        updateSelectedCount();
    }

    $selectAll.on('click', function() {
        const isChecked = $(this).prop('checked');
        $checkboxItems.prop('checked', isChecked);
        handleCheckboxToggle();

        if (isChecked && $checkboxItems.filter(':checked').length > 0) {
            $approveBtn.show();
            $disapproveBtn.show();
        } else {
            $approveBtn.hide();
            $disapproveBtn.hide();
        }

        console.log('Select All toggled:', isChecked);
    });

    $checkboxItems.on('click', function() {
        const checkedCount = $checkboxItems.filter(':checked').length;

        if (checkedCount > 0) {
            $approveBtn.show();
            $disapproveBtn.show();
        } else {
            $approveBtn.hide();
            $disapproveBtn.hide();
        }

        $labelSelectAll.text($selectAll.prop('checked') ? 'Unselect All' : 'Select All');

        updateSelectedCount();
    });

    function sendAjax(url, successMessage) {
        const selectedItems = $checkboxItems.filter(':checked').map(function() {
            return $(this).data('id');
        }).get();

        if (selectedItems.length === 0) {
            Swal.fire('No Selection', 'Please select at least one item.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to ${successMessage.toLowerCase()} ${selectedItems.length} TO(s).`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: `Yes, ${successMessage.toLowerCase()}!`
        }).then((result) => {
            if (result.isConfirmed) {
                if (loader) loader.style.display = "block";

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        ids: JSON.stringify(selectedItems),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (loader) loader.style.display = "none";
                        Swal.fire('Success', `${successMessage}: ${response}`, 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        if (loader) loader.style.display = "none";
                        const msg = xhr.status === 404 ? 'Route not found' :
                                    xhr.status === 500 ? 'Server error: ' + xhr.responseText :
                                    xhr.responseText || 'An error occurred';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });
    }

    $approveBtn.on('click', function() {
        sendAjax('/approve-to-all', 'Approved');
    });

    $disapproveBtn.on('click', function() {
        sendAjax('/disapprove-to-all', 'Disapproved');
    });

    console.log('jQuery version:', $.fn.jquery);
    console.log('Checkbox count:', $checkboxItems.length);
});
</script>

@foreach ($tos as $to)
  @include('for-approval.remarks.to_approved_remarks')
  @include('for-approval.remarks.to_declined_remarks')
  @include('for-approval.remarks.view-toapproved')
  @include('for-approval.remarks.view-todeclined')
  @include('for-approval.view-toManager') 
  @include('for-approval.view-form') 
@endforeach



@endsection

