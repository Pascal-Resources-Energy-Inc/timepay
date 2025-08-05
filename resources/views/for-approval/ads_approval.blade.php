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
                <h4 class="card-title">For Approval Authority To Deduct</h4>
                <p class="card-description">
                 <!-- Button -->
                  <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#atdperemployee">
                    <i class="ti-plus btn-icon-prepend"></i>
                    Apply Authority To Deduct For Employee
                  </button>
                </p>
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
                          <th>Select</th>
                        @endif
                        <th>Action</th>
                        <th>Employee Name</th>
                        <th>Applied Date</th>
                        <th>Type of Deduction</th>
                        <th>Start Date</th>
                        <th>Frequency</th>
                        <th>Amount</th>
                        <th>Approver</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody> 
                      @foreach ($ads as $form_approval)
                        <tr>
                        @if(empty($status) || $status == 'Pending')
                          <td align="center">
                            @if($form_approval->status == 'Pending')
                              <input type="checkbox" class="checkbox-item" data-id="{{$form_approval->id}}">
                            @endif
                          </td>
                        @endif
                        <td align="center" id="tdActionId{{ $form_approval->id }}" data-id="{{ $form_approval->id }}">
                          @if($form_approval->status == 'Approved')
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View Approved">
                              <i class="ti-eye btn-icon-prepend"></i> View
                            </button>
                            @elseif($form_approval->status == 'Declined')
                              <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View Declined Remarks">
                                <i class="ti-eye btn-icon-prepend"></i> View
                              </button>
                            @else
                                @if ($form_approval->can_first_approve || $form_approval->can_final_approve)
                                  <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View">
                                      <i class="ti-eye btn-icon-prepend"></i> 
                                  </button>
                                  <button type="button" class="btn btn-success btn-sm" id="approve-btn-{{ $form_approval->id }}" data-target="#ad-approved-remarks-{{ $form_approval->id }}" data-toggle="modal" title="Approve">
                                      <i class="ti-check btn-icon-prepend"></i>                                                    
                                  </button>
                                  <button type="button" class="btn btn-danger btn-sm" id="decline-btn-{{ $form_approval->id }}" data-target="#ad-declined-remarks-{{ $form_approval->id }}" data-toggle="modal" title="Decline">
                                      <i class="ti-close btn-icon-prepend"></i>                                                    
                                  </button>
                              @endif
                          @endif
                        </td>
                        <td>
                          <strong>{{ $form_approval->name }}</strong> <br>
                          <small>Position: {{ $form_approval->designation }}</small> <br>
                          <small>Location: {{ $form_approval->location }}</small> <br>
                          <small>Department: {{ $form_approval->department }}</small>
                        </td>
                        <td>{{ date('M. d, Y', strtotime($form_approval->applied_date)) }}</td>
                        <td>{{ $form_approval->type_of_deduction }}</td>
                        <td>{{ date('M. d, Y', strtotime($form_approval->start_date ?? '')) }}</td>
                        <td>{{ $form_approval->frequency }}</td>
                        <td>{{ number_format($form_approval->amount ?? 0, 2) }}</td>
                        <td>
                        @if($form_approval->assigned_approvers && $form_approval->assigned_approvers->count() > 0)
                            @foreach($form_approval->assigned_approvers as $approver)
                                @php
                                    $name = $approver['name'] ?? 'Unknown';
                                    $approver_level = $approver['level'] ?? 0;
                                    $statusLabel = '';
                                    $badgeClass = '';

                                    if ($form_approval->level >= $approver_level) {
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

                                <div class="mb-1">
                                    {{ $name }}
                                    @if($statusLabel && $badgeClass)
                                        - <label class="badge badge-{{ $badgeClass }} mt-1">{{ $statusLabel }}</label>
                                    @endif
                                </div>
                            @endforeach
                            @else
                                <span class="text-muted">No approver assigned</span>
                        @endif
                    </td>

                        <td>
                          @if ($form_approval->status == 'Pending')
                            <label class="badge badge-warning">{{ $form_approval->status }}</label>
                          @elseif($form_approval->status == 'Approved')
                            <label class="badge badge-success" title="{{$form_approval->remarks}}">{{ $form_approval->status }}</label>
                          @elseif($form_approval->status == 'Declined' || $form_approval->status == 'Cancelled')
                            <label class="badge badge-danger" title="{{$form_approval->remarks}}">{{ $form_approval->status }}</label>
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
            text: `You are about to ${successMessage.toLowerCase()} ${selectedItems.length} Pay Instruction(s).`,
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
        sendAjax('/approve-ad-all', 'Approved');
    });

    $disapproveBtn.on('click', function() {
        sendAjax('/disapprove-ad-all', 'Disapproved');
    });
});
</script>

@include('forms.authoritydeduct.apply_ad_per_employee')

@foreach ($ads as $ad)
  @include('for-approval.view-adManager')
  @include('for-approval.remarks.ad_approved_remarks')
  @include('for-approval.remarks.ad_declined_remarks')
@endforeach

@endsection