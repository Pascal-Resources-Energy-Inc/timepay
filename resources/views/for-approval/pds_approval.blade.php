<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


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
                <h4 class="card-title">For Approval Payroll Disbursement</h4>

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

                @if((empty($status) || $status == 'Pending'))
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
                        @if((empty($status) || $status == 'Pending'))
                          <th>Select</th>
                        @endif
                        <th>Action </th> 
                        <th>Employee Name</th>
                        <th>Date Filed</th>
                        <th>Employee Number</th>
                        <th>Reason for Request</th>
                        <th>Disbursement Account</th>
                        <th>Status</th>
                        <th>Approvers</th> 
                      </tr>
                    </thead>
                    <tbody> 
                      @foreach ($pds as $form_approval)
                        <tr>
                          @if((empty($status) || $status == 'Pending'))
                            <td align="center">
                              @if($form_approval->status == 'Pending')
                                <input type="checkbox" class="checkbox-item" data-id="{{$form_approval->id}}">
                              @endif
                            </td>
                          @endif
                          
                          <td align="center" id="tdActionId{{ $form_approval->id }}" data-id="{{ $form_approval->id }}">
                            @if($form_approval->status == 'Approved')
                              <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-pd-{{ $form_approval->id }}" title="View Approved">
                                <i class="ti-eye btn-icon-prepend"></i> View
                              </button>
                            @elseif($form_approval->status == 'Declined')
                              <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-pd-{{ $form_approval->id }}" title="View Declined Remarks">
                                <i class="ti-eye btn-icon-prepend"></i> View
                              </button>
                            @else
                              @if($form_approval->status == 'Pending')
                                <!-- Show action buttons for PD approvers on pending requests -->
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-pd-{{ $form_approval->id }}" title="View">
                                  <i class="ti-eye btn-icon-prepend"></i> 
                                </button>
                                <button type="button" class="btn btn-success btn-sm" id="{{ $form_approval->id }}" data-target="#pd-approved-remarks-{{ $form_approval->id }}" data-toggle="modal" title="Approve">
                                  <i class="ti-check btn-icon-prepend"></i>                                                    
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" id="{{ $form_approval->id }}" data-target="#pd-declined-remarks-{{ $form_approval->id }}" data-toggle="modal" title="Decline">
                                  <i class="ti-close btn-icon-prepend"></i>                                                    
                                </button>
                              @else
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-pd-{{ $form_approval->id }}" title="View">
                                  <i class="ti-eye btn-icon-prepend"></i> View Only
                                </button>
                              @endif
                            @endif
                          </td>
                          
                          <!-- Employee details and other columns -->
                          <td>
                            <strong>{{$form_approval->user->name}}</strong> <br>
                            <small>Position : {{$form_approval->user->employee->position}}</small> <br>
                            <small>Location : {{$form_approval->user->employee->location}}</small> <br>
                            <small>Department : {{ $form_approval->user->employee->department ? $form_approval->user->employee->department->name : ""}}</small>
                          </td>
                          <td> {{ date('M. d, Y', strtotime($form_approval->created_at)) }} </td>
                          <td>{{ $form_approval->employee_number ?? 'N/A' }}</td>
                          <td>{{ ucfirst(str_replace('_', ' ', $form_approval->reason_for_request ?? 'N/A')) }}</td>
                          <td>{{ ucfirst(str_replace('_', ' ', $form_approval->disbursement_account ?? 'N/A')) }}</td>
                          <td>
                            @if ($form_approval->status == 'Pending')
                              <label class="badge badge-warning">{{ $form_approval->status }}</label>
                            @elseif($form_approval->status == 'Approved')
                              <label class="badge badge-success" title="{{$form_approval->approval_remarks}}">{{ $form_approval->status }}</label>
                            @elseif($form_approval->status == 'Declined' || $form_approval->status == 'Cancelled')
                              <label class="badge badge-danger" title="{{$form_approval->approval_remarks}}">{{ $form_approval->status }}</label>
                            @endif  
                          </td>
                        <td>
                          @php
                            $approver = $getApproverForEmployee($form_approval->user->employee);
                            $employee_company = $form_approval->user->employee->company_code ?? $form_approval->user->employee->company_id ?? null;
                          @endphp
                          
                          @if($approver)
                            <div>{{ $approver->user->name }}</div>
                          @else
                            <div class="text-danger">No PD approver available</div>
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
            text: `You are about to ${successMessage.toLowerCase()} ${selectedItems.length} PD(s).`,
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
        sendAjax('/approve-pd-all', 'Approved');
    });

    $disapproveBtn.on('click', function() {
        sendAjax('/disapprove-pd-all', 'Disapproved');
    });

    console.log('jQuery version:', $.fn.jquery);
    console.log('Checkbox count:', $checkboxItems.length);
});
</script>


@foreach ($pds as $pd)
   @include('for-approval.view-pd')
   @include('for-approval.remarks.pd_approved_remarks')
   @include('for-approval.remarks.pd_declined_remarks')
@endforeach


@endsection
