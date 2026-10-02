<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@extends('layouts.header')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    {{-- Summary Cards --}}
    <div class='row grid-margin'>
      <div class='col-lg-2 mt-2'>
        <div class="card card-tale">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h6 class="mb-4 text-truncate">For Approval</h6>
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
        <div class="card card-dark-blue">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h6 class="mb-4 text-truncate">Approved</h6>
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
                    <select class="form-control form-control-sm required js-example-basic-single" name='status' required>
                      <option value="">-- Select Status --</option>
                      <option value="Approved" @if ('Approved'==$status) selected @endif>Approved</option>
                      <option value="Pending" @if ('Pending'==$status) selected @endif>Pending</option>
                      <option value="Processing" @if ('Processing'==$status) selected @endif>Processing</option>
                      <option value="Declined" @if ('Declined'==$status) selected @endif>Declined</option>
                      <option value="Cancelled" @if ('Cancelled'==$status) selected @endif>Cancelled</option>
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
                    <button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Filter</button>
                  </div>
                </div>
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
              <table class="table table-hover table-bordered tablewithSearch">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Employee Name</th>
                    <th>Reference No.</th>
                    @if ($status !== 'Pending' || $status === 'All')
                        <th>Attachment</th>
                    @endif
                    @if ($status === 'Approved' || $status === 'All')
                        <th>Proof of Delivery</th>
                    @endif
                    <th>Delivery Method</th>
                    <th>Date</th>
                    <th>Purpose</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($coes as $form_approval)
                  <tr>
                    <td align="center" id="tdActionId{{ $form_approval->id }}" data-id="{{ $form_approval->id }}">
                    @if ($user_role === 'coe_approver' && $form_approval->status === 'Approved' && empty($form_approval->proof))
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#coe-proof-upload-{{ $form_approval->id }}" title="Proof of Delivery">
                          <i class="ti-upload btn-icon-prepend"></i>
                        </button>
                    @endif

                    @if($user_role === 'coe_approver' && $form_approval->status === 'Processing')
                      @if(empty($form_approval->attachment))
                      <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#coe-upload-{{ $form_approval->id }}" title="Attach document">
                        <i class="ti-upload btn-icon-prepend"></i>
                      </button>
                      @else
                      <button type="button" class="btn btn-success btn-sm" data-target="#coe-approved-remarks-{{ $form_approval->id }}" data-toggle="modal" title="Approve and send to requestor">
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
                      <button type="button" class="btn btn-primary btn-sm"
                        data-target="#coe-processing-remarks-{{ $form_approval->id }}"
                        data-toggle="modal" title="Process">
                        <i class="ti-arrow-right btn-icon-prepend"></i>
                      </button>
                      <button type="button" class="btn btn-danger btn-sm" data-target="#coe-declined-remarks-{{ $form_approval->id }}" data-toggle="modal" title="Decline">
                        <i class="ti-close btn-icon-prepend"></i>
                      </button>
                      @elseif($form_approval->status == 'Processing' && $user_role == 'coe_approver')
                      {{-- Current user can approve this processing request --}}
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View">
                        <i class="ti-eye btn-icon-prepend"></i>
                      </button>

                      <a href="/coe-print/{{ $form_approval->id }}" target="_blank" class="btn btn-primary btn-sm" title="Print Certificate">
                          <i class="ti-printer btn-icon-prepend text-white"></i>
                      </a>

                      @else
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}" title="View Only">
                        <i class="ti-eye btn-icon-prepend"></i> View Only
                      </button>
                      @endif
                    </td>
                    <td style="line-height: 1.2;">
                      <strong>{{$form_approval->user->name ?? $form_approval->first_name . ' ' . $form_approval->last_name}}</strong> <br>
                      @if ($form_approval->user)
                      <small>Position: {{$form_approval->user->employee->position }}</small> <br>
                      <small>Location: {{$form_approval->user->employee->location }}</small> <br>
                      <small>Department: {{ $form_approval->user->employee->department ? $form_approval->user->employee->department->name : "N/A"}}</small> <br>
                      <small>
                          Request: 
                         <span class="text-info"> {{ $form_approval->reason_for_request }}</span>
                      </small>
                      @else
                          <small>Public submission</small> <br>
                          <small>
                              Request: 
                             <span class="text-info"> {{ $form_approval->reason_for_request }}</span>
                          </small>
                      @endif
                    </td>
                    <td>{{$form_approval->reference_number}}</td>
                    @if ($status !== 'Pending' || $status === 'All')
                    <td>
                    @if (in_array($form_approval->status, ['Processing', 'Approved', 'Declined', 'Cancelled']))
                            @if (in_array($form_approval->receive_method, ['Viber', 'Email', 'Hard Copy']))
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
                                @else
                                    <span class="text-danger" style="font-size: 12px; text-align: center;">Attachment required</span>
                                @endif
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
                            <a href="#" data-toggle="modal" data-target="#coe-proof-upload-{{ $form_approval->id }}" style="font-size: 12px; display: flex; justify-content: center; align-items: center; color: #0d6efd; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'" title="View proof of delivery">
                                <i class="ti-image btn-icon-prepend mr-1"></i> Proof of delivery
                            </a>
                            @else
                            <span class="text-danger" style="font-size: 12px;">Upload proof of delivery</span>
                            @endif
                    @else
                        <span class="text-muted">—</span>
                    @endif
                    </td>
                    @endif
                    <td>{{ $form_approval->receive_method === 'Hard Copy' ? 'Pick up' : $form_approval->receive_method }}</td>
                    <td>
                        @php
                            $dueDate = \Carbon\Carbon::parse($form_approval->created_at)->addDays(7);
                        @endphp
                        <table style="color: #6c757d;">
                            <tr style="border: none;"> 
                                <td style="font-size: 11px; border: none; padding: 0 6px 7px 0; white-space: nowrap;">Date Filed:</td>
                                <td style="font-size: 11px; border:none; padding: 0 0 7px 0;">{{ date('M d, Y', strtotime($form_approval->created_at)) }}</td>
                            </tr>
                            <tr style="border:none;">
                                <td style="font-size: 11px; border: none; padding: 0 6px 7px 0; white-space: nowrap;">Due Date:</td>
                                <td style="font-size: 11px; border: none; padding: 0 0 7px 0; {{ now()->gt($dueDate) && in_array($form_approval->status, ['Pending', 'Processing']) ? 'color: #dc3545; font-weight: bold;' : '' }}">
                                    {{ $dueDate->format('M d, Y') }}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>{{$form_approval->purpose ?? $form_approval->type ?? 'General COE'}}</td>
                    <td>
                      @if ($form_approval->status == 'Pending')
                      <label class="badge badge-warning">{{ $form_approval->status }}</label>
                      @elseif($form_approval->status == 'Approved')
                      <label class="badge badge-success" title="{{$form_approval->approval_remarks}}">{{ $form_approval->status }}</label>
                      @elseif($form_approval->status == 'Processing')
                      <label class="badge badge-info">{{ $form_approval->status }}</label>
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
@include('for-approval.remarks.coe_processing_remarks')
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
                <h5 class="modal-title">Proof of Delivery</h5>
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
                            <i class="ti-image mr-1"></i> Proof of Delivery
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
                        <div class="col-md-12 form-group">
                            <label>Upload proof of delivery <span class="text-danger">*</span></label>
                            <input type="file" name="proof_of_delivery" class="form-control" accept="image/*" required>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
                    @if(empty($coe->proof))
                    <button type="submit" class="btn btn-primary">Upload</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach


@endsection
