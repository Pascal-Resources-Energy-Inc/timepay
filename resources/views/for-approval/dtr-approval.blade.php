@extends('layouts.header')
@section('content')
<style>
  .dtr-summary-card {
    height: 100%;
    border: 1px solid #e5e7eb;
    border-left-width: 4px;
    border-radius: 0.65rem;
    background: #fff;
    box-shadow: 0 0.2rem 0.75rem rgba(15, 23, 42, 0.05);
  }

  .dtr-summary-link {
    display: block;
    height: 100%;
    color: inherit;
    text-decoration: none;
  }

  .dtr-summary-link:hover,
  .dtr-summary-link:focus {
    color: inherit;
    text-decoration: none;
  }

  .dtr-summary-card .card-body {
    padding: 1.15rem 1.25rem;
  }

  .dtr-summary-label {
    margin-bottom: 0.15rem;
    color: #4b5563;
    font-size: 0.78rem;
    font-weight: 600;
    line-height: 1.3;
  }

  .dtr-summary-count {
    margin: 0;
    color: #1f2937;
    font-size: 1.65rem;
    font-weight: 700;
    line-height: 1.2;
  }

  .dtr-summary-description {
    display: block;
    margin-top: 0.2rem;
    color: #6b7280;
    font-size: 0.7rem;
    line-height: 1.35;
  }

  .dtr-summary-card--pending { border-left-color: #d69e2e; }
  .dtr-summary-card--approved { border-left-color: #22a06b; }
  .dtr-summary-card--declined { border-left-color: #dc3545; }

  .dtr-summary-link:hover .dtr-summary-card,
  .dtr-summary-link:focus .dtr-summary-card {
    border-color: #cbd5e1;
    transform: translateY(-1px);
    box-shadow: 0 0.35rem 1rem rgba(15, 23, 42, 0.08);
  }

  .dtr-filter-panel {
    border: 1px solid #e5e7eb;
    border-radius: 0.65rem;
    background: #f8fafc;
  }

  .dtr-filter-button {
    display: inline-flex;
    width: 2.5rem;
    height: 2.5rem;
    align-items: center;
    justify-content: center;
    padding: 0;
  }

  .dtr-bulk-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1rem;
    padding: 0.85rem 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.65rem;
    background: #f8fafc;
  }

  .dtr-bulk-intro {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    min-width: 0;
  }

  .dtr-bulk-icon {
    display: inline-flex;
    width: 2.25rem;
    height: 2.25rem;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    border-radius: 0.55rem;
    background: #e8edff;
    color: #4b49ac;
  }

  .dtr-bulk-title,
  .dtr-bulk-description {
    display: block;
  }

  .dtr-bulk-title {
    color: #374151;
    font-size: 0.82rem;
    font-weight: 600;
    line-height: 1.3;
  }

  .dtr-bulk-description {
    margin-top: 0.1rem;
    color: #6b7280;
    font-size: 0.7rem;
    line-height: 1.35;
  }

  .dtr-bulk-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 0.5rem;
  }

  .dtr-select-all {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    min-height: 2.25rem;
    margin: 0;
    padding: 0.4rem 0.65rem;
    border: 1px solid #dbe4ee;
    border-radius: 0.5rem;
    background: #fff;
    color: #374151;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
  }

  .dtr-select-all:hover {
    border-color: #b8c3d1;
    background: #f9fafb;
  }

  .dtr-select-all input {
    width: 1rem;
    height: 1rem;
    margin: 0;
    accent-color: #4b49ac;
  }

  #dtrDataTable_wrapper .dataTables_filter {
    float: left !important;
    width: 100%;
    text-align: left !important;
  }

  #dtrDataTable_wrapper .dataTables_filter label {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }

  #dtrDataTable_wrapper .dataTables_filter input {
    min-width: 300px;
    margin: 0.2rem 0 0 !important;
  }

  @media (max-width: 575.98px) {
    .dtr-page-title {
      font-size: 1.05rem;
    }

    .dtr-filter-button {
      width: 100%;
    }

    .dtr-bulk-toolbar {
      align-items: stretch;
      flex-direction: column;
    }

    .dtr-bulk-actions {
      width: 100%;
      justify-content: flex-start;
    }

    .dtr-select-all {
      width: 100%;
      justify-content: center;
    }

    .dtr-bulk-toolbar .btn {
      flex: 1 1 auto;
    }

    #dtrDataTable_wrapper .dataTables_filter input {
      width: 100%;
      min-width: 0;
    }
  }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row mb-4">
          <div class="col-12 col-md-4 mb-3 mb-md-0">
            <a href="{{ url('for-dtr-correction') }}?status=Pending" class="dtr-summary-link" aria-label="View DTR corrections for approval">
              <div class="card dtr-summary-card dtr-summary-card--pending">
                <div class="card-body">
                  <div class="dtr-summary-label">For Approval</div>
                  <h2 class="dtr-summary-count">{{ $for_approval }}</h2>
                  <small class="dtr-summary-description">Awaiting your review</small>
                </div>
              </div>
            </a>
          </div>

          <div class="col-12 col-md-4 mb-3 mb-md-0">
            <a href="{{ url('for-dtr-correction') }}?status=Approved" class="dtr-summary-link" aria-label="View approved DTR corrections">
              <div class="card dtr-summary-card dtr-summary-card--approved">
                <div class="card-body">
                  <div class="dtr-summary-label">Approved</div>
                  <h2 class="dtr-summary-count">{{ $approved }}</h2>
                  <small class="dtr-summary-description">Completed approvals</small>
                </div>
              </div>
            </a>
          </div>

          <div class="col-12 col-md-4">
            <a href="{{ url('for-dtr-correction') }}?status=Declined" class="dtr-summary-link" aria-label="View declined DTR corrections">
              <div class="card dtr-summary-card dtr-summary-card--declined">
                <div class="card-body">
                  <div class="dtr-summary-label">Declined / Rejected</div>
                  <h2 class="dtr-summary-count">{{ $declined }}</h2>
                  <small class="dtr-summary-description">Not approved</small>
                </div>
              </div>
            </a>
          </div>
        </div>
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <h4 class="card-title dtr-page-title mb-0">DTR Correction Approval</h4>
                </div>

                <form method='get' onsubmit='show();' enctype="multipart/form-data" class="dtr-filter-panel p-3 mt-3 mb-4">
                  <div class="row align-items-end">
                    <div class='col-12 col-md-6 col-xl-3'>
                      <div class="form-group mb-3 mb-xl-0">
                        <label class="text-right">From</label>
                        <input type="date" value='{{$from}}' class="form-control form-control-sm" name="from" onchange='get_min(this.value);' required />
                      </div>
                    </div>
                    <div class='col-12 col-md-6 col-xl-3'>
                      <div class="form-group mb-3 mb-xl-0">
                        <label class="text-right">To</label>
                        <input type="date" value='{{$to}}' class="form-control form-control-sm" id='to' name="to" required />
                      </div>
                    </div>
                    <div class='col-12 col-md-6 col-xl-3 pr-sm-1'>
                      <div class="form-group mb-3 mb-xl-0">
                        <label class="text-right">Status</label>
                        <select data-placeholder="Select Status" class="form-control form-control-sm" name='status' required>
                          <option value="">-- Select Status --</option>
                          <option value="Approved" @if ('Approved' == $status) selected @endif>Approved</option>
                          <option value="Pending" @if ('Pending' == $status) selected @endif>Pending</option>
                          <option value="Cancelled" @if ('Cancelled' == $status) selected @endif>Cancelled</option>
                          <option value="Declined" @if ('Declined' == $status) selected @endif>Declined</option>
                        </select>
                      </div>
                    </div>
                    <div class='col-12 col-sm-auto pl-sm-3'>
                      <div class="form-group mb-0">
                        <button type="submit" class="dtr-filter-button btn btn-primary btn-sm" title="Apply filters" aria-label="Apply filters">
                          <i class="ti-filter" aria-hidden="true"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
                @if(empty($status) || $status == 'Pending')
                  <!-- <div class="dtr-bulk-toolbar"> -->
                  <!--   <div class="dtr-bulk-intro"> -->
                  <!--     <div> -->
                  <!--       <span class="dtr-bulk-title">Bulk actions</span> -->
                  <!--       <span class="dtr-bulk-description">Select one or more requests to process together.</span> -->
                  <!--     </div> -->
                  <!--   </div> -->
                  <!--   <div class="dtr-bulk-actions"> -->
                  <!--     <label class="dtr-select-all"> -->
                  <!--       <input type="checkbox" id="selectAll"> -->
                  <!--       <span id="labelSelectAll">Select All</span> -->
                  <!--     </label> -->
                  <!--     <button type="button" class="btn btn-success btn-sm" id="approveAllBtn" style="display: none;">Approve</button> -->
                  <!--     <button type="button" class="btn btn-danger btn-sm" id="disApproveAllBtn" style="display: none;">Disapprove</button> -->
                  <!--   </div> -->
                  <!-- </div> -->
                @endif

                <div class="table-responsive">
                  <table id="dtrDataTable" class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        @if(empty($status) || $status == 'Pending')
                          <!-- <th> -->
                          <!--   Select -->
                          <!-- </th> -->
                        @endif
                        <th>Employee Name</th>
                        <th>Date Filed</th>
                        <th>DTR Date </th>
                        <th>Correction</th>
                        <th>Adjustment Type</th>
                        <th>Time-in</th>
                        <th>Time-Out</th>
                        <th>Reason</th>
                        <th>Approvers</th>
                        <th>Attachment</th>
                        <th>Status</th>
                        <th>Action </th> 
                      </tr>
                    </thead>
                    <tbody> 
                        @foreach ($dtrs as $form_approval)
                        <tr>
                          <!-- @if(empty($status) || $status == 'Pending') -->
                          <!--   <td align="center"> -->
                          <!--     @foreach($form_approval->approver as $k => $approver) -->
                          <!--       @if($approver->approver_id == $approver_id && $form_approval->level == $approver->level && $form_approval->status == 'Pending') -->
                          <!---->
                          <!--           <input type="checkbox" class="checkbox-item" data-id="{{$form_approval->id}}"> -->
                          <!--         </td> -->
                          <!--       @endif -->
                          <!--     @endforeach -->
                          <!--   </td> -->
                          <!-- @endif -->
                          <td>
                              <strong>{{ data_get($form_approval, 'user.name', '') }}</strong> <br>
                              <small>Position : {{ data_get($form_approval, 'user.employee.position', '') }}</small> <br>
                              <small>Location : {{ data_get($form_approval, 'user.employee.location', '') }}</small> <br>
                              <small>Department : {{ data_get($form_approval, 'user.employee.department.name', '') }}</small>
                          </td>
                          <td>{{date('m/d/Y', strtotime($form_approval->created_at))}}</td>
                          <td>{{date('m/d/Y', strtotime($form_approval->dtr_date))}}</td>
                          <td>{{$form_approval->correction}}</td>
                          <td>{{$form_approval->adjustment_type ?? '----'}}</td>
                          <td> {{(isset($form_approval->time_in)) ? date('h:i A', strtotime($form_approval->time_in)) : '----'}}</td>
                          <td> {{(isset($form_approval->time_out)) ? date('h:i A', strtotime($form_approval->time_out)) : '----'}}</td>
                          <td>
                            <p >
                              {!!$form_approval->remarks!!}
                            </p>
                          </td>
                          <td id="tdStatus{{ $form_approval->id }}">
                            @foreach($form_approval->approver as $approver)
                              @if($form_approval->level >= $approver->level)
                                  @if ($form_approval->status == 'Declined')
                                  {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                  @elseif(($form_approval->level == $approver->level) && ($form_approval->status == 'Pending'))
                                   {{$approver->approver_info->name}} -  <label class="badge badge-warning mt-1">Pending</label>
                                  @else
                                    {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                                  @endif
                              @else
                                @if ($form_approval->status == 'Declined')
                                  {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @else
                                  {{$approver->approver_info->name}} -  <label class="badge badge-warning mt-1">Pending</label>
                                @endif
                              @endif<br> 
                            @endforeach
                          </td>
                          <td>
                            @forelse($form_approval->available_attachments as $index => $attachment)
                              <a href="{{ url($attachment['path']) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info btn-sm mb-1">
                                Attachment {{ $index + 1 }}
                              </a>
                            @empty
                              <span class="text-muted">—</span>
                            @endforelse
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
                          <td align="center" id="tdActionId{{ $form_approval->id }}" data-id="{{ $form_approval->id }}">
                            @foreach($form_approval->approver as $k => $approver)
                              @if($approver->approver_id == $approver_id && $form_approval->level == $k && $form_approval->status == 'Pending')
                                <button type="button" class="btn btn-success btn-sm" id="{{ $form_approval->id }}" data-target="#dtr-approved-remarks-{{ $form_approval->id }}" data-toggle="modal" title="Approve">
                                  <i class="ti-check btn-icon-prepend"></i>                                                    
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" id="{{ $form_approval->id }}" data-target="#dtr-declined-remarks-{{ $form_approval->id }}" data-toggle="modal" title="Decline">
                                  <i class="ti-close btn-icon-prepend"></i>                                                    
                                </button> 
                              @endif<br> 
                            @endforeach
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
<script>
$(document).ready(function() {
    const $checkboxItems = $('.checkbox-item');
    const $approveBtn = $('#approveAllBtn');
    const $disapproveBtn = $('#disApproveAllBtn');
    const $selectAll = $('#selectAll');
    const $labelSelectAll = $('#labelSelectAll');

    function getSelectedItems() {
        return $checkboxItems.filter(':checked').map(function() {
            return $(this).data('id');
        }).get();
    }

    function updateSelectedCount() {
        const selectedCount = getSelectedItems().length;
        $approveBtn.text('(' + selectedCount + ') Approve');
        $disapproveBtn.text('(' + selectedCount + ') Disapprove');
    }

    function toggleButtons() {
        const anyChecked = getSelectedItems().length > 0;
        $approveBtn.toggle(anyChecked);
        $disapproveBtn.toggle(anyChecked);
        $labelSelectAll.text($selectAll.prop('checked') ? 'Unselect All' : 'Select All');
        updateSelectedCount();
    }

    function setLoader(isVisible) {
        const loader = document.getElementById("preloaderHera");

        if (loader) {
            loader.style.display = isVisible ? "block" : "none";
        }
    }

    function getErrorMessage(xhr) {
        if (xhr.responseJSON && xhr.responseJSON.message) {
            return xhr.responseJSON.message;
        }

        if (xhr.responseText) {
            try {
                const response = JSON.parse(xhr.responseText);
                return response.message || "Something went wrong.";
            } catch (e) {
                return xhr.responseText;
            }
        }

        return "Something went wrong.";
    }

    function submitSelectedDtrs(options) {
        const selectedItems = getSelectedItems();

        if (selectedItems.length <= 0) {
            Swal.fire("Warning", "Please select DTR.", "warning");
            return;
        }

        Swal.fire({
            title: "Are you sure?",
            text: options.confirmText,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: options.confirmButtonText,
            confirmButtonColor: options.confirmButtonColor
        }).then(function(result) {
            if (!result.isConfirmed) {
                return;
            }

            setLoader(true);

            $.ajax({
                type: 'POST',
                url: options.url,
                data: {
                    ids: JSON.stringify(selectedItems),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(response) {
                    setLoader(false);

                    const message = response.message || options.successMessage;
                    const count = response.count ? ' (' + response.count + ')' : '';

                    Swal.fire("Success", message + count, "success").then(function() {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    setLoader(false);
                    console.log(xhr.responseText);
                    Swal.fire("Error", getErrorMessage(xhr), "error");
                }
            });
        });
    }

    $selectAll.on('click', function() {
        $checkboxItems.prop('checked', $(this).prop('checked'));
        toggleButtons();
    });

    $checkboxItems.on('click', function() {
        $selectAll.prop('checked', $checkboxItems.length === $checkboxItems.filter(':checked').length);
        toggleButtons();
    });

    $approveBtn.on('click', function() {
        submitSelectedDtrs({
            url: @json(url('approve-dtr-all')),
            confirmText: "You want to approve this DTR?",
            confirmButtonText: "Yes, approve it!",
            confirmButtonColor: "#28a745",
            successMessage: "DTR has been approved."
        });
    });

    $disapproveBtn.on('click', function() {
        submitSelectedDtrs({
            url: @json(url('disapprove-dtr-all')),
            confirmText: "You want to disapprove this DTR?",
            confirmButtonText: "Yes, disapprove it!",
            confirmButtonColor: "#dc3545",
            successMessage: "DTR has been disapproved."
        });
    });

    toggleButtons();
});
</script>

@foreach ($dtrs as $dtr)
  @include('for-approval.remarks.dtr_approved_remarks')
  @include('for-approval.remarks.dtr_declined_remarks')
@endforeach

{{-- @php
function get_count_days($data,$date_from,$date_to)
 {
    $data = ($data->pluck('name'))->toArray();
    $count = 0;
    $startTime = strtotime($date_from);
    $endTime = strtotime($date_to);

    for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
      $thisDate = date( 'l', $i ); // 2010-05-01, 2010-05-02, etc
      if(in_array($thisDate,$data)){
          $count= $count+1;
      }
    }

    return($count);
 } 
@endphp   --}}

@endsection

