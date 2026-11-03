<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@extends('layouts.header')
@section('content')

<style>
    .dataTables_wrapper .dataTables_filter input.form-control-sm {
        height: calc(1.5em + 0.5rem + 2px);
        padding: 0.25rem 0.5rem;
        min-width: 300px;
    }

    #coeTable_wrapper .dataTables_filter {
        float: left !important;
        width: 100%;
        text-align: left !important;
    }

    #coeTable_wrapper .dataTables_filter label {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    #coeTable_wrapper .dataTables_filter input {
        margin: 0.2rem 0 0 !important;
    }

    .coe-reference {
        display: block;
        color: #2d3748;
        font-weight: 600;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .coe-reference-stack {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.3rem;
    }

    .coe-requestor-type {
        color: #dc3545;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1.2;
    }

    .coe-reference-cell--overdue {
        background-color: #fff1f2 !important;
        box-shadow: inset 3px 0 0 #dc3545;
    }

    .coe-reference-cell--overdue .coe-reference {
        color: #9f1239;
    }

    .coe-status {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 0;
        color: #6c757d;
        font-size: 0.75rem;
        font-weight: 500;
        line-height: 1.2;
    }

    .coe-status::before {
        width: 0.5rem;
        height: 0.5rem;
        border-radius: 50%;
        background-color: currentColor;
        content: '';
        flex: 0 0 auto;
    }

    .coe-status--pending { color: #a66b00; }
    .coe-status--approved { color: #198754; }
    .coe-status--processing { color: #087990; }
    .coe-status--declined { color: #dc3545; }
</style>

<div class="main-panel">
  <div class="content-wrapper">
    {{-- Summary Cards --}}
    <div class='row grid-margin'>
      <div class='col-lg-2 mt-2'>
        <div class="card card-tale">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h6 class="mb-4 text-truncate">Pending</h6>
                <a href="/coe-approval?status=Pending" class="h2 card-text text-white text-truncate d-block">{{$for_approval}}</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class='col-lg-2 mt-2'>
        <div class="card card-light-blue">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h6 class="mb-4 text-truncate">Processing</h6>
                <a href="/coe-approval?status=Processing" class="h2 card-text text-white text-truncate d-block">{{$processing}}</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class='col-lg-2 mt-2'>
        <div class="card card-dark-blue bg-success">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h6 class="mb-4 text-truncate">Released</h6>
                <a href="/coe-approval?status=Approved" class="h2 card-text text-white text-truncate d-block">{{$approved}}</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class='col-lg-2 mt-2'>
        <div class="card card-light-danger text-white">
          <div class="card-body">
            <div class="media">
              <div class="media-body" style="min-width: 0">
                <h6 class="mb-4 text-truncate" title="Declined/Cancelled">Declined/Cancelled</h6>
                <a href="/coe-approval?status=Declined" class="h2 card-text text-white text-truncate d-block">{{$declined}}</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Main Table Section --}}
    <div class='row'>
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
              <div class="d-flex justify-content-between">
                <h4 class="card-title">COE Approval Requests</h4>

                <a href="{{ asset('template/COE_TEMPLATE.docx') }}" class="text-info btn btn-sm" title="COE Template"> 
                     <i class="ti-download mr-1"></i> 
                     <span style="text-decoration: underline; text-underline-offset: 2px;">Download COE Template</span>
                </a>
              </div>

            {{-- Filter Form --}}
            <form method='get' onsubmit='show();' enctype="multipart/form-data" class="border rounded bg-light p-3 mt-3 mb-4">
              <div class="row align-items-end">
                <div class='col-12 col-md-6 col-xl-2'>
                  <div class="form-group mb-3 mb-xl-0">
                    <label class="text-right">From</label>
                    <input type="date" value='{{$from}}' class="form-control form-control-sm" name="from" onchange='get_min(this.value);' />
                  </div>
                </div>
                <div class='col-12 col-md-6 col-xl-2'>
                  <div class="form-group mb-3 mb-xl-0">
                    <label class="text-right">To</label>
                    <input type="date" value='{{$to}}' class="form-control form-control-sm" id='to' name="to" />
                  </div>
                </div>
                <div class='col-12 col-md-6 col-xl-2'>
                  <div class="form-group mb-3 mb-xl-0">
                    <label class="text-right">Status</label>
                    <select data-placeholder="Select Status" class="form-control form-control-sm" name='status' required>
                      <option value="">-- Select Status --</option>
                      <option value="Approved" @if ('Approved'==$status) selected @endif>Released</option>
                      <option value="Pending" @if ('Pending'==$status) selected @endif>Pending</option>
                      <option value="Processing" @if ('Processing'==$status) selected @endif>Processing</option>
                      <option value="Declined" @if ('Declined'==$status) selected @endif>Declined</option>
                      <option value="Cancelled" @if ('Cancelled'==$status) selected @endif>Cancelled</option>
                      <option value="From Import" @if ('From Import'==$status) selected @endif>From Import</option>
                      <option value="All" {{ $status == 'All' ? 'selected' : '' }}>All</option>
                    </select>
                  </div>
                </div>
                <div class='col-12 col-md-6 col-xl-2'>
                  <div class="form-group mb-3 mb-xl-0">
                    <label for="per-page">Rows per page</label>
                    <select id="per-page" class="form-control form-control-sm" name="per_page">
                      @foreach ([10, 30, 50, 100, 150] as $pageSize)
                        <option value="{{ $pageSize }}" {{ (int) $per_page === $pageSize ? 'selected' : '' }}>{{ $pageSize }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class='col-12 col-sm-auto pl-sm-3'>
                  <div class="form-group mb-3 mb-xl-0">
                    <button type="submit" class="d-flex align-items-center form-control-sm filter-btn btn btn-primary btn-sm" title="Apply Filters" aria-label="Apply filters">
                      <i class="ti-filter"></i>
                    </button>
                  </div>
                </div>
              </div>
            </form>

            <form method="get" class="d-flex justify-content-start mb-3">
              @if ($from)
                <input type="hidden" name="from" value="{{ $from }}">
              @endif
              @if ($to)
                <input type="hidden" name="to" value="{{ $to }}">
              @endif
              <input type="hidden" name="status" value="{{ $status }}">
              <input type="hidden" name="per_page" value="{{ $per_page }}">

              <div class="form-group mb-0 w-100" style="max-width: 320px;">
                <label for="coe-search" class="mb-1">Search:</label>
                <input id="coe-search" type="search" name="search" value="{{ $search }}"
                  class="form-control form-control-sm" placeholder="Reference, employee, or email">
              </div>
            </form>

            {{-- Bulk Actions --}}
            @if((empty($status) || $status == 'Pending'))
            <!-- <div class="mb-3">
              <label>
                <input type="checkbox" id="selectAll">
                <span id="labelSelectAll">Select All</span>
              </label>
              <button class="btn btn-success btn-sm ml-2" id="approveAllBtn" style="display: none;">Approve Selected</button>
              <button class="btn btn-danger btn-sm ml-1" id="disApproveAllBtn" style="display: none;">Disapprove Selected</button>
            </div> -->
            @endif

            {{-- Results Table --}}
            <div class="table-responsive">
              <table id="coeTable" class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Reference No.</th>
                    <th>Employee</th>
                    @if ($status !== 'Pending' || $status === 'All')
                        <th>Attachment</th>
                    @endif
                    @if ($status === 'Approved' || $status === 'All')
                        <th>Proof of Issuance</th>
                    @endif
                    <th>Delivery Method</th>
                    <th>Timeline</th>
                    <th>Purpose</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($coes as $form_approval)
                  @php
                      $isConfid = (int) data_get($form_approval, 'user.employee.company_id') === 2;
                      $requiresAttachment = $form_approval->requiresAttachment();
                  @endphp
                  <tr>
                    <td align="center" id="tdActionId{{ $form_approval->id }}" data-id="{{ $form_approval->id }}">
                    @if ($user_role === 'coe_approver' && $form_approval->status === 'Approved' && empty($form_approval->proof))
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#coe-proof-upload-{{ $form_approval->id }}" title="Proof of Delivery">
                          <i class="ti-upload btn-icon-prepend"></i>
                        </button>
                    @endif

                    @if($user_role === 'coe_approver' && $form_approval->status === 'Processing' && !$isConfid)
                      @if($requiresAttachment && empty($form_approval->attachment))
                      <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#coe-upload-{{ $form_approval->id }}" title="Attach document">
                        <i class="ti-upload btn-icon-prepend"></i>
                      </button>
                      @else
                      <button type="button" class="btn btn-success btn-sm" data-target="#coe-approved-remarks-{{ $form_approval->id }}" data-toggle="modal" title="Send to requestor">
                        <i class="ti-arrow-right btn-icon-prepend"></i>
                      </button>
                      @endif
                    @endif
                      @if($form_approval->status == 'Approved')
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View Approved">
                        <i class="ti-eye btn-icon-prepend"></i>
                      </button>
                      @elseif($form_approval->status == 'Declined')
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View Declined">
                        <i class="ti-eye btn-icon-prepend"></i>
                      </button>
                      @elseif($form_approval->status == 'Pending' && $user_role == 'coe_approver')
                      {{-- Current user can approve this pending request --}}
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View">
                        <i class="ti-eye btn-icon-prepend"></i>
                      </button>

                      @if($isConfid)
                        @if($is_hr_head_approver)
                        <button type="button" class="btn btn-success btn-sm"
                          data-target="#coe-approved-remarks-{{ $form_approval->id }}"
                          data-toggle="modal" title="Release">
                          <i class="ti-check btn-icon-prepend"></i>
                        </button>
                        @endif
                      @else
                        <button type="button" class="btn btn-primary btn-sm"
                          data-target="#coe-processing-remarks-{{ $form_approval->id }}"
                          data-toggle="modal" title="Process">
                          <i class="ti-arrow-right btn-icon-prepend"></i>
                        </button>
                      @endif

                      @if(!$isConfid || $is_hr_head_approver)
                        <button type="button" class="btn btn-danger btn-sm" data-target="#coe-declined-remarks-{{ $form_approval->id }}" data-toggle="modal" title="Decline">
                          <i class="ti-close btn-icon-prepend"></i>
                        </button>
                      @endif
                      @elseif($form_approval->status == 'Processing' && $user_role == 'coe_approver' && !$isConfid)
                      {{-- Current user can approve this processing request --}}
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View">
                        <i class="ti-eye btn-icon-prepend"></i>
                      </button>

                      <a href="/coe-print/{{ $form_approval->id }}" target="_blank" class="btn btn-primary btn-sm" title="Print Certificate">
                          <i class="ti-printer btn-icon-prepend text-white"></i>
                      </a>

                      @else
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View">
                        <i class="ti-eye btn-icon-prepend"></i>
                      </button>
                      @endif
                    </td>
                    @php
                        $businessDueDate = \Carbon\Carbon::parse($form_approval->created_at)
                            ->startOfDay()
                            ->addWeekdays(3);
                        $isOverdue = in_array($form_approval->status, ['Pending', 'Processing'], true)
                            && now()->startOfDay()->gt($businessDueDate);
                    @endphp
                    <td
                        class="{{ $isOverdue ? 'coe-reference-cell--overdue' : '' }}"
                        @if($isOverdue)
                            title="Overdue: not released within 3 business days. Due {{ $businessDueDate->format('M d, Y') }}."
                        @endif
                    >
                        <div class="coe-reference-stack">
                            <span class="coe-reference">{{ $form_approval->reference_number }}</span>

                            @if ((int) data_get($form_approval, 'user.employee.company_id') === 2)
                            <span class="coe-requestor-type">Confid</span>
                            @endif

                            @if ($form_approval->status == 'Pending')
                            <span class="coe-status coe-status--pending">{{ $form_approval->status }}</span>
                            @elseif($form_approval->status == 'Approved')
                            <span class="coe-status coe-status--approved" title="{{ $form_approval->approval_remarks }}">Released</span>
                            @elseif($form_approval->status == 'Processing')
                            <span class="coe-status coe-status--processing">{{ $form_approval->status }}</span>
                            @elseif($form_approval->status == 'Declined' || $form_approval->status == 'Cancelled')
                            <span class="coe-status coe-status--declined" title="{{ $form_approval->approval_remarks }}">{{ $form_approval->status }}</span>
                            @elseif($form_approval->status == 'From Import')
                            <span class="text-info" title="{{ $form_approval->approval_remarks }}"><small>{{ $form_approval->status }}</small></span>
                            @endif
                        </div>
                    </td>
                    <td style="line-height: 1.2;">
                        <strong>{{$form_approval->user->name ?? $form_approval->first_name . ' ' . $form_approval->last_name}}</strong> <br>
                        @if ($form_approval->user)
                        <small>Position: {{$form_approval->user->employee->position }}</small> <br>
                        <small>Department: {{ $form_approval->user->employee->department ? $form_approval->user->employee->department->name : "N/A"}}</small> <br>
                        <small>
                            Request: 
                            <span class="text-info" style="font-weight: bold;"> {{ $form_approval->reason_for_request }}</span>
                        </small>
                        @else
                        <small class="text-danger" style="font-weight: bold;">Public submission</small> <br>
                        <small>
                            Request: 
                            <span class="text-info" style="font-weight: bold;"> {{ $form_approval->reason_for_request }}</span>
                        </small>
                        @endif
                    </td>
                    @if ($status !== 'Pending' || $status === 'All')
                    <td>
                    @if (in_array($form_approval->status, ['Processing', 'Approved', 'Declined', 'Cancelled']))
                            @if($form_approval->attachment)
                                @if($form_approval->status === 'Processing')
                                    <a href="#" data-toggle="modal" data-target="#coe-upload-{{ $form_approval->id }}" style="font-size: 12px; display: flex; justify-content: center; align-items: center;" title="Change attachment">
                                      <i class="ti-image btn-icon-prepend mr-1"></i> View attachment
                                    </a>
                                @else
                                    <a href="{{ asset($form_approval->attachment) }}" target="_blank" style="font-size: 12px; color: #0d6efd;" title="View attachment">
                                      <i class="ti-image btn-icon-prepend mr-1"></i> View attachment
                                    </a>
                                @endif
                            @elseif($requiresAttachment)
                                <span class="text-danger" style="font-size: 12px; text-align: center;">Attachment required</span>
                            @else
                                <span class="text-muted" style="font-size: 12px;">No attachment required</span>
                            @endif
                    @else
                        <span class="text-muted">—</span>
                    @endif
                    </td>
                    @endif
                    @if ($status === 'Approved' || $status === 'All')
                    <td>
                    @if ($form_approval->status === 'Approved')
                            @if($form_approval->proof)
                            <a href="#" data-toggle="modal" data-target="#coe-proof-upload-{{ $form_approval->id }}" style="font-size: 12px; display: flex; justify-content: center; align-items: center; color: #0d6efd; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'" title="View proof of issuance">
                                <i class="ti-image btn-icon-prepend mr-1"></i> Proof of issuance
                            </a>
                            @else
                            <span class="text-danger" style="font-size: 12px;">Upload proof of issuance</span>
                            @endif
                    @else
                        <span>-</span>
                    @endif
                    </td>
                    @endif
                    <td>{{ $form_approval->receive_method === 'Hard Copy' ? 'Pick up' : $form_approval->receive_method }}</td>
                    @php
                        $processingTrail = $form_approval->trails->firstWhere(
                            'action',
                            \App\EmployeeCoeTrail::ACTION_PROCESSING_STARTED
                        );
                        $releasedTrail = $form_approval->trails->firstWhere(
                            'action',
                            \App\EmployeeCoeTrail::ACTION_CERTIFICATE_RELEASED
                        );

                        $processingAt = optional($processingTrail)->created_at;
                        $releasedAt = optional($releasedTrail)->created_at;

                        if (!$processingAt && $form_approval->processed_at) {
                            $processingAt = \Carbon\Carbon::parse($form_approval->processed_at);
                        }

                        if (!$releasedAt && $form_approval->status === 'Approved' && $form_approval->approved_date) {
                            $releasedAt = \Carbon\Carbon::parse($form_approval->approved_date);
                        }

                        $filingToProcessingDays = $processingAt
                            ? (int) $form_approval->created_at->copy()
                                ->startOfDay()
                                ->diffInDaysFiltered(function ($date) {
                                    return !$date->isSunday();
                                }, $processingAt->copy()->startOfDay())
                            : null;

                        $releaseStartAt = $processingAt ?: $form_approval->created_at;

                        $releaseDays = $releaseStartAt && $releasedAt
                            ? (int) $releaseStartAt->copy()
                                ->startOfDay()
                                ->diffInDaysFiltered(function ($date) {
                                    return !$date->isSunday();
                                }, $releasedAt->copy()->startOfDay())
                            : null;

                    @endphp
                    <td style="min-width: 150px; line-height: 1.65;">
                        <small class="d-block font-weight-bold">
                            <span class="text-primary">Filed at:</span> 
                            <span>{{ $form_approval->created_at->format('M d, Y') }}</span>
                        </small>

                        @if(!$isConfid && $processingAt)
                            <small class="d-block font-weight-bold">
                                <span class="text-info">Processing started:</span> 
                                <span>{{ $processingAt->format('M d, Y') }}</span>
                                <span class="text-muted font-weight-normal">
                                    ({{ $filingToProcessingDays }} {{ \Illuminate\Support\Str::plural('day', $filingToProcessingDays) }})
                                </span>
                            </small>
                        @endif

                        @if($releasedAt)
                            <small class="d-block font-weight-bold">
                                <span style="color:#006600;">Released:</span> 
                                <span>{{ $releasedAt->format('M d, Y') }}</span>
                                <span class="text-muted font-weight-normal">
                                    ({{ $releaseDays }} {{ \Illuminate\Support\Str::plural('day', $releaseDays) }})
                                </span>
                            </small>
                        @endif
                    </td>
                    <td style="max-width: 250px; white-space: normal;">{{$form_approval->purpose ?? $form_approval->type ?? 'General COE'}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @if ($coes->hasPages())
              <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-3">
                <small class="text-muted mb-2 mb-md-0">
                  Showing {{ $coes->firstItem() }} to {{ $coes->lastItem() }} of {{ $coes->total() }} records
                </small>
                <div>
                  {{ $coes->links() }}
                </div>
              </div>
            @endif
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
        text: `You are about to ${successMessage.toLowerCase()} ${selectedItems.length} COE(s).`,
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
      sendAjax('/approve-coe-all', 'Approved');
    });

    $disapproveBtn.on('click', function() {
      sendAjax('/disapprove-coe-all', 'Disapproved');
    });


    console.log('jQuery version:', $.fn.jquery);
    console.log('Checkbox count:', $checkboxItems.length);
  });
</script>

@foreach ($coes as $coe)
@if ((int) data_get($coe, 'user.employee.company_id') !== 2)
@include('for-approval.remarks.coe_processing_remarks')
@endif
@include('for-approval.remarks.coe_approved_remarks')
@include('for-approval.remarks.coe_declined_remarks')
@include('for-approval.view-coe')

{{-- Inline COE attachment upload modal --}}
<div class="modal fade" id="coe-upload-{{$coe->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title">Attach Document</h5>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ url('upload-coe-attachment/'.$coe->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @if($coe->attachment)
                    <div class="mb-3 border rounded p-2" style="background: #fafafa;">
                        <label class="d-block text-muted mb-2" style="font-size: 12px; font-weight: 600;">
                            <i class="ti-image mr-1"></i> Current Attachment
                        </label>
                        <div class="d-flex align-items-center">
                            <a href="{{ asset($coe->attachment) }}" target="_blank" class="d-inline-block mr-3">
                                <img src="{{ asset($coe->attachment) }}" alt="Current attachment"
                                    style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #dee2e6; border-radius: 4px;">
                            </a>
                            <div>
                                <div style="font-size: 13px; font-weight: 500; word-break: break-all; max-width: 220px;">
                                    {{ basename($coe->attachment) }}
                                </div>
                                <a href="{{ asset($coe->attachment) }}" target="_blank" style="display: flex; justify-content: center; align-items: center;" class="btn btn-light border btn-sm mt-1">
                                    <i class="ti-eye mr-1"></i> View full image
                                </a>
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-top">
                            <label class="d-block mb-1" style="font-size: 12px; font-weight: 600; color: #666;">
                                <i class="ti-refresh mr-1"></i> Replace attachment
                            </label>
                            <span class="text-muted" style="font-size: 11px;">Uploading a new file will overwrite the current one.</span>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Upload an attachment (scanned image) <span class="text-danger">*</span></label>
                            <input type="file" name="attachment" class="form-control" accept="image/*" @if(empty($coe->attachment)) required @endif>
                            @if ($coe->receive_method === 'email')
                                <p class="text-muted" style="font-size: 10px;">This will be send to requestor</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label for="attachment-remarks-{{ $coe->id }}">Remarks <span class="text-muted">(optional)</span></label>
                        <textarea
                            id="attachment-remarks-{{ $coe->id }}"
                            name="attachment_remarks"
                            class="form-control"
                            rows="3"
                            maxlength="1000"
                            placeholder="Add notes about this document"
                        >{{ old('attachment_remarks', $coe->attachment_remarks) }}</textarea>
                        <small class="form-text text-muted">These remarks will be saved with the attached document.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@foreach ($coes as $coe)
{{-- Proof of Delivery upload modal --}}
<div class="modal fade" id="coe-proof-upload-{{$coe->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title">Proof of Issuance</h5>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ url('upload-proof-delivery/'.$coe->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @if($coe->proof)
                    <div class="border rounded p-2" style="background: #fafafa;">
                        <label class="d-block text-muted mb-2" style="font-size: 12px; font-weight: 600;">
                            <i class="ti-image mr-1"></i> Proof of Issuance
                        </label>
                        <div class="d-flex align-items-center">
                            <a href="{{ asset($coe->proof) }}" target="_blank" class="d-inline-block mr-3">
                                <img src="{{ asset($coe->proof) }}" alt="Proof of delivery"
                                    style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #dee2e6; border-radius: 4px;">
                            </a>
                            <div>
                                <div style="font-size: 13px; font-weight: 500; word-break: break-all; max-width: 220px;">
                                    {{ basename($coe->proof) }}
                                </div>
                                <a href="{{ asset($coe->proof) }}" target="_blank" style="display: flex; justify-content: center; align-items: center;" class="btn btn-light border btn-sm mt-1">
                                    <i class="ti-eye mr-1"></i> View full image
                                </a>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-12">
                            <div class="border rounded bg-light p-3">
                                <label for="proof-of-issuance-{{ $coe->id }}" class="d-block mb-1 font-weight-bold">
                                    <i class="ti-image mr-1 text-primary" aria-hidden="true"></i>
                                    Upload proof of issuance <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="file"
                                    id="proof-of-issuance-{{ $coe->id }}"
                                    name="proof_of_delivery"
                                    class="form-control"
                                    accept="image/*"
                                    required
                                >
                            </div>

                            <div class="form-group mt-3 mb-0">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <label for="issuance-remarks-{{ $coe->id }}" class="mb-0 font-weight-bold">
                                        Remarks
                                        <span><small class="text-muted">(optional)</small></span>
                                    </label>
                                </div>
                                <textarea
                                    id="issuance-remarks-{{ $coe->id }}"
                                    name="issuance_remarks"
                                    class="form-control"
                                    rows="3"
                                    maxlength="500"
                                    placeholder="Add a short note about the issuance."
                                >{{ old('issuance_remarks') }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-sm-end px-3">
                    <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
                    @if(empty($coe->proof))
                    <button type="submit" class="btn btn-primary mt-2 mt-sm-0 ml-sm-2">Upload</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach


@endsection
