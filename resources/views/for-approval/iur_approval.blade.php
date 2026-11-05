<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

@extends('layouts.header')
@section('content')

<style>
  .dataTables_wrapper .dataTables_filter input.form-control-sm {
    height: calc(1.5em + 0.5rem + 2px);
    padding: 0.25rem 0.5rem;
  }

  .iur-closed-card .media,
  .iur-closed-card .media-body {
    min-width: 0;
    width: 100%;
  }

  .iur-closed-card-title {
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
</style>

<div class="main-panel">
  <div class="content-wrapper">
    {{-- Summary Cards --}}
    <div class='row grid-margin'>
      <div class='col-lg-2 mb-2'>
        <div class="card card-tale">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h4 class="mb-4 text-truncate">Pending</h4>
                <a href="/iur-approval?status=Pending" class="h4 card-text text-white text-truncate d-block">{{$for_approval}}</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Processing -->
      <div class='col-lg-2 mb-2'>
        <div class="card card-light-blue text-white">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h4 class="mb-4 text-truncate">Processing</h4>
                <a href="/iur-approval?status=Processing" class="h4 card-text text-white text-truncate d-block">{{$processing}}</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Partial Receive -->
      <div class='col-lg-2 mb-2'>
        <div class="card card-dark-blue text-white">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h4 class="mb-4 text-truncate">Partial</h4>
                <a href="/iur-approval?status=Partial" class="h4 card-text text-white text-truncate d-block">{{$partial_receive}}</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- TODO: change all "Received" status into "Released" -->
      <!-- received -->
      <div class='col-lg-2 mb-2'>
        <div class="card bg-success text-white">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h4 class="mb-4 text-truncate">Released</h4>
                <a href="/iur-approval?status=Released" class="h4 card-text text-white text-truncate d-block">{{$received}}</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- declined -->
      <div class='col-lg-2 mb-2'>
        <div class="card card-light-danger iur-closed-card">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h4 class="mb-4 iur-closed-card-title" title="Declined / Cancelled">Declined / Cancelled</h4>
                <a href="/iur-approval?status=Declined" class="h4 card-text text-white text-truncate d-block">{{$declined}}</a>
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
            <h4>Uniform & ID Requests</h4>

            {{-- Filter Form --}}
            <form method='get' onsubmit='show();' enctype="multipart/form-data" class="border rounded bg-light p-3 mt-3 mb-4">
              <div class="row align-items-end">
                <div class='col-12 col-md-6 col-xl-3'>
                  <div class="form-group mb-3 mb-xl-0">
                    <label class="text-right">From</label>
                    <input type="date" value='{{$from}}' class="form-control form-control-sm" name="from" onchange='get_min(this.value);'/>
                  </div>
                </div>
                <div class='col-12 col-md-6 col-xl-3'>
                  <div class="form-group mb-3 mb-xl-0">
                    <label class="text-right">To</label>
                    <input type="date" value='{{$to}}' class="form-control form-control-sm" id='to' name="to"/>
                  </div>
                </div>
                <div class='col-12 col-md-6 col-xl-3 pr-sm-1'>
                  <div class="form-group mb-3 mb-xl-0">
                    <label class="text-right">Status</label>
                    <select class="form-control form-control-sm required js-example-basic-single" name='status' required>
                      <option value="">-- Select Status --</option>
                      <option value="Processing" @if ('Processing'==$status) selected @endif>Processing</option>
                      <option value="Partial" @if ('Partial'==$status) selected @endif>Partial</option>
                      <option value="Released" @if ('Released'==$status) selected @endif>Released</option>
                      <option value="Pending" @if ('Pending'==$status) selected @endif>Pending</option>
                      <option value="From Import" @if ('From Import'==$status) selected @endif>From Import</option>
                      <option value="Declined" @if ('Declined'==$status) selected @endif>Declined / Cancelled</option>
                      <option value="All" {{ $status == 'All' ? 'selected' : '' }}>All</option>
                    </select>
                  </div>
                </div>
                <!-- <div class='col-md-2'> -->
                <!--   <div class="form-group"> -->
                <!--     <label class="text-right">Show</label> -->
                <!--     <select name="limit" class="form-control form-control-sm"> -->
                <!--       <option value="5" {{ request('limit') == 5 ? 'selected' : '' }}>5</option> -->
                <!--       <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option> -->
                <!--       <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25</option> -->
                <!--       <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option> -->
                <!--       <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100</option> -->
                <!--     </select> -->
                <!--   </div> -->
                <!-- </div> -->
                <div class='col-12 col-sm-auto pl-sm-1'>
                  <div class="form-group mb-3 mb-xl-0">
                    <button type="submit" class="form-control-sm btn btn-primary btn-sm">
                      <i class="ti-filter text-white"></i>
                    </button>
                    <!-- <button type="button" style="display: flex; gap: 5px; align-items: center;" class="btn btn-sm mb-2 btn-info" data-toggle="modal" data-target="#approver-signature-modal"> -->
                    <!--   <i class="ti-write"></i> Signature -->
                    <!-- </button> -->
                  </div>
                </div>
              </div>
            </form>

            {{-- Bulk Actions --}}
            @if($approvers && (empty($status) || $status !== 'Pending') && optional(auth()->user()->employee)->location !== 'Lubao Office')
            <div class="mb-3">
            @php
                $releasedNotPrintedCount = released_unprinted_iur_count(auth()->id());

                $printedUnreleasedCount = printed_unreleased_iur_count(auth()->id());
            @endphp
              <label>
                  <button class="btn btn-light border btn-sm" onclick="window.open('/batch-print-form', '_blank')">
                      <i class="bi bi-person-badge"></i> Print ID
                    @if ($releasedNotPrintedCount > 0)
                        <span class="badge badge-warning ml-1">
                            {{ $releasedNotPrintedCount }}
                        </span>
                    @endif
                    @if ($printedUnreleasedCount > 0)
                        <span class="badge badge-info ml-1">
                            {{ $printedUnreleasedCount }}
                        </span>
                    @endif
                  </button>
                  <!-- <button class="btn btn-primary btn-sm"><i class="bi bi-person-badge"></i> Batch Print IDs</button> -->
              </label>
            </div>
            @endif 

            @php
              $currentApproverLocation = optional(auth()->user()->employee)->location;

              $canManageIurRequest = function ($iur) use ($approvers, $currentApproverLocation) {
                if (!$approvers) {
                  return false;
                }

                if ($currentApproverLocation === 'Lubao Office') {
                  return $iur->work_location === 'Plant'
                    && in_array($iur->request_for, ['Uniform', 'Both'], true);
                }

                if ($currentApproverLocation === 'HO') {
                  return !(
                    $iur->work_location === 'Plant'
                    && $iur->request_for === 'Uniform'
                  );
                }

                return false;
              };
            @endphp

            {{-- Results Table --}}
            <div class="table-responsive">
              <table class="table table-hover table-bordered tablewithSearch">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>IUR Reference No.</th>
                    <th>Old Reference No.</th>
                    <th>Items</th>
                    @if ($iurs->contains(fn($i) => $i->accountabilities->count() > 0))
                      <th>Signed</th>
                    @endif
                    <th>Employee Name</th>
                    <th>Type</th>
                    <th>Work Location</th>
                    <th>Notes</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($iurs as $form_approval)
                  @php $canManageCurrentIur = $canManageIurRequest($form_approval); @endphp
                  <tr>
                    <td align="center" id="tdActionId{{ $form_approval->id }}" data-id="{{ $form_approval->id }}">
                      @if(!$canManageCurrentIur)
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View Only">
                        <i class="ti-eye btn-icon-prepend"></i>
                      </button>
                      @elseif($form_approval->status == 'Partial')
                      <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#iur-release-modal-{{ $form_approval->id }}" title="Release More Items">
                        <i class="ti-package btn-icon-prepend"></i>
                      </button>
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View and Process">
                        <i class="ti-eye btn-icon-prepend"></i>
                      </button>
                      @elseif($form_approval->status == 'Processing')
                      <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#iur-release-modal-{{ $form_approval->id }}" title="Release Items">
                        <i class="ti-package btn-icon-prepend"></i>
                      </button>
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View">
                          <i class="ti-eye btn-icon-prepend"></i>
                      </button>
                      @elseif($form_approval->status == 'Released')
                      <button type="button" class="btn btn-info btn-sm d-inline-flex align-items-center justify-content-center" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View Only">
                        <i class="ti-eye btn-icon-prepend"></i>
                      </button>
                      @elseif($form_approval->status == 'Declined')
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View Declined">
                        <i class="ti-eye btn-icon-prepend"></i>
                      </button>
                      @elseif($form_approval->status == 'Pending')
                      {{-- Current user can approve this pending request --}}
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View">
                        <i class="ti-arrow-right btn-icon-prepend"></i>
                      </button>
                      <button type="button" class="btn btn-danger btn-sm" data-target="#iur-declined-remarks-{{ $form_approval->id }}" data-toggle="modal" title="Decline">
                        <i class="ti-close btn-icon-prepend"></i>
                      </button>
                      @else
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View Only">
                        <i class="ti-eye btn-icon-prepend"></i>
                      </button>
                      @endif
                    </td>
                    <td>
                        {{ $form_approval->iur_reference }}
                        @if ($form_approval->status === 'From Import')
                            <small class="text-danger font-weight-bold d-block mt-1">From import</small>
                        @endif
                    </td>
                    <td>{{ $form_approval->legacy_reference ?? '-'}}</td>
                    <td style="font-size: 10px; line-height: 1.6; white-space: nowrap;">
                      @php $items = []; @endphp
                      @if(in_array($form_approval->request_for, ['Uniform', 'Uniform/Partial', 'Both']))
                        @if($form_approval->white_qty > 0) @php $items[] = ['label' => 'White', 'rec' => $form_approval->received_white_qty, 'req' => $form_approval->white_qty]; @endphp @endif
                        @if($form_approval->black_qty > 0) @php $items[] = ['label' => 'Black', 'rec' => $form_approval->received_black_qty, 'req' => $form_approval->black_qty]; @endphp @endif
                        @if($form_approval->collar_qty > 0) @php $items[] = ['label' => 'Polo', 'rec' => $form_approval->received_collar_qty, 'req' => $form_approval->collar_qty]; @endphp @endif
                        @if($form_approval->prod_qty > 0) @php $items[] = ['label' => 'Prod', 'rec' => $form_approval->received_prod_qty, 'req' => $form_approval->prod_qty]; @endphp @endif
                      @endif
                      @foreach($items as $i)
                      <div style="display:flex; justify-content:space-between; font-weight:bold;">
                        <span>{{ $i['label'] }}:</span>
                        <span style="color:{{ $i['rec'] >= $i['req'] ? 'blue' : 'red' }};">{{ $i['rec'] }}/{{ $i['req'] }}</span>
                      </div>
                      @endforeach

                      <!-- id line -->
                      @if(in_array($form_approval->request_for, ['ID', 'Both']) && $form_approval->status !== 'From Import')
                      @php $idRel = $form_approval->accountabilities->sum('released_id') > 0; @endphp
                          <div style="display:flex; justify-content:space-between; font-weight:bold;">
                            <span>ID:</span>
                            <span style="color:{{ $idRel ? 'blue' : 'red' }}; font-weight: bold;">{{ $idRel ? '✓' : 'X' }}</span>
                          </div>
                      @endif
                    </td>
                    @if ($iurs->contains(fn($i) => $i->accountabilities->count() > 0))
                    <td style="font-size: 10px; line-height: 1.6; white-space: nowrap;">
                      @forelse($form_approval->accountabilities as $acc)
                      <div style="display:flex; justify-content:space-between; align-items: center;">
                          <span style="font-size: 12px; font-weight: bold;">{{ $acc->accountability_ref }}:</span>
                          <span style="font-size:: 12px; font-weight: bold; color:{{ $acc->employee_signature ? 'blue' : 'red' }}; margin-left: 6px;">{{ $acc->employee_signature ? '✓' : 'X' }}</span>
                      </div>
                      @empty
                      <span style="color: #999;">—</span>
                      @endforelse
                    </td>
                    @endif
                    <td>
                      @php
                        $employeeFirst = optional($form_approval->user)->first_name ?: $form_approval->legacy_first;
                        $employeeLast = optional($form_approval->user)->last_name ?: $form_approval->legacy_last;
                        $employeeName = trim(($employeeFirst ?? '') . ' ' . ($employeeLast ?? ''));
                        $employeePosition = optional($form_approval->user)->position ?: $form_approval->legacy_designation;
                        $employeeLocation = optional($form_approval->user)->location ?: $form_approval->work_location;
                      @endphp
                      <strong>{{ $employeeName !== '' ? $employeeName : '—' }}</strong> <br>
                      <small>Position: {{ $employeePosition ?: '—' }}</small> <br>
                      <small>Location: {{ $employeeLocation ?: '—' }}</small> <br>
                      @if($form_approval->user)
                      <small>Department: {{ optional($form_approval->user->department)->name ?: '—' }}</small>
                      @elseif($form_approval->legacy_date_hired)
                      <small>Date Hired: {{ date('M d, Y', strtotime($form_approval->legacy_date_hired)) }}</small>
                      @endif
                    </td>
                    <td>{{ $form_approval->type }}</td>
                    <td>{{ $form_approval->work_location }}</td>
                    <td style="max-width: 350px; white-space: normal;">{{ $form_approval->notes }} </td>
                    {{-- <td>
                      @php
                      $approver = $form_approval->user ? $getApproverForEmployee($form_approval->user, $form_approval->work_location) : null;
                      $employee_company = optional($form_approval->user)->company_code ?? optional($form_approval->user)->company_id ?? null;
                      @endphp

                      @if($approver)
                      <div>{{ $approver->user->name }}</div>
                      @else
                      <div class="text-danger">No approver</div>
                      @endif
                    </td> --}}
                    <td>
                      @if ($form_approval->status == 'Pending')
                      <label class="badge badge-warning">{{ $form_approval->status }}</label>
                      @elseif($form_approval->status == 'Processing')
                      <label class="badge badge-primary" title="{{$form_approval->approval_remarks}}">{{ $form_approval->status }}</label>
                      @elseif($form_approval->status == 'Partial')
                      <label class="badge badge-info" title="{{$form_approval->approval_remarks}}">{{ $form_approval->status }}</label>
                      @elseif($form_approval->status == 'Released')
                      <label class="badge badge-success" title="{{$form_approval->approval_remarks}}">{{ $form_approval->status }}</label>
                      @elseif($form_approval->status == 'Declined' || $form_approval->status == 'Cancelled')
                      <label class="badge badge-danger" title="{{$form_approval->approval_remarks}}">{{ $form_approval->status }}</label>
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
        text: `You are about to ${successMessage.toLowerCase()} ${selectedItems.length} IUR(s).`,
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
      sendAjax('/approve-iur-all', 'Processed');
    });

    $disapproveBtn.on('click', function() {
      sendAjax('/disapprove-iur-all', 'Disapproved');
    });

    console.log('jQuery version:', $.fn.jquery);
    console.log('Checkbox count:', $checkboxItems.length);
  });
</script>

@foreach ($iurs as $form_approval)
  @php $canManageCurrentIur = $canManageIurRequest($form_approval); @endphp
  @if($canManageCurrentIur && $form_approval->user)
    @include('for-approval.remarks.iur_approved_remarks', ['form_approval' => $form_approval])
    @include('for-approval.remarks.iur_declined_remarks', ['form_approval' => $form_approval])
    @include('for-approval.remarks.iur_partial_remarks', ['form_approval' => $form_approval])
    @include('for-approval.remarks.iur_received_remarks', ['form_approval' => $form_approval])
    @include('for-approval.remarks.iur_signature_modal', ['form_approval' => $form_approval])
    @include('for-approval.remarks.iur_release_modal', ['form_approval' => $form_approval])
  @endif
    @include('for-approval.view-iur', [
      'form_approval' => $form_approval,
      'canManageCurrentIur' => $canManageCurrentIur,
    ])
  @if($canManageCurrentIur && $form_approval->user)
    @include('for-approval.print-accountability-iur')
  @endif
@endforeach

@if($approvers)
  @include('for-approval.remarks.approver_signature_modal')
@endif


@endsection
