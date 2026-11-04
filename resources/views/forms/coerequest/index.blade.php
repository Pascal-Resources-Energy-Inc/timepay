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
        line-height: 1.35;
        overflow-wrap: anywhere;
    }

    .coe-status {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 0.35rem;
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
    .coe-status--released { color: #198754; }
    .coe-status--processing { color: #087990; }
    .coe-status--declined { color: #dc3545; }
</style>
    
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row grid-margin'>
          <div class='col-lg-2 mt-2'>
            <div class="card card-tale">
              <div class="card-body">
                <div class="media">
                  <div class="media-body">
                    <h6 class="mb-4 text-truncate">Pending</h6>
                    <a href="/coe-request?status=Pending" class="h2 card-text text-white text-truncate d-block">{{ ($coes_all->where('status','Pending'))->count() }}</a>
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
                    <a href="/coe-request?status=Processing" class="h2 card-text text-white text-truncate d-block">{{ ($coes_all->where('status','Processing'))->count() }}</a>
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
                    <a href="/coe-request?status=Approved" class="h2 card-text text-white text-truncate d-block">{{ ($coes_all->where('status','Approved'))->count() }}</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class='col-lg-2 mt-2'>
            <div class="card card-light-danger text-white">
              <div class="card-body">
                <div class="media">
                  <div class="media-body" style="min-width: 0;">
                    <h6 class="mb-4 text-truncate" title="Declined/Cancelled">Declined/Cancelled</h6>
                    <a href="/coe-request?status=Declined" class="h2 card-text text-white text-truncate d-block">{{ ($coes_all->where('status','Cancelled'))->count() + ($coes_all->where('status','Declined'))->count() }}</a>
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
                <div class="d-flex justify-content-between">
                <h4 class="card-title">COE Request</h4>
                  <button type="button" class="btn btn-sm btn-outline-info btn-icon-text d-flex align-items-center" data-toggle="modal" data-target="#coeRequestModal">
                    <i class="ti-plus btn-icon-prepend mr-2"></i>
                    Request
                  </button>

                </div>
                <form method='get' onsubmit='show();' enctype="multipart/form-data" class="border rounded bg-light p-3 mt-3 mb-4">
                  <div class="row align-items-end">
                    <div class='col-12 col-md-6 col-xl-3'>
                      <div class="form-group mb-3 mb-xl-0">
                        <label class="text-right">From</label>
                        <input type="date" value='{{$from}}' class="form-control form-control-sm" name="from"
                            max='{{ date('Y-m-d') }}' onchange='get_min(this.value);' required />
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
                          <option value="Pending" @if ('Pending' == $status) selected @endif>Pending</option>
                          <option value="Processing" @if ('Processing' == $status) selected @endif>Processing</option>
                          <option value="Approved" @if ('Approved' == $status) selected @endif>Released</option>
                          <option value="Cancelled" @if ('Cancelled' == $status) selected @endif>Cancelled</option>
                          <option value="Declined" @if ('Declined' == $status) selected @endif>Declined</option>
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

                <div class="table-responsive">
                  <table id="coeTable" class="table table-hover table-bordered">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Reference No.</th>
                        <th>Date Filed</th>
                        <th>Request</th>
                        <th>Employment Status</th>
                        <th>Purpose</th>
                        <th>Receive Method</th>
                        <th>Approver</th>
                        <th>Remarks</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($coes as $coe)
                      <tr>
                        <td>
                          @if ($coe->status == 'Pending' && $coe->level == 0)
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-coe-{{ $coe->id }}" title="View request">
                              <i class="ti-eye btn-icon-prepend"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit-coe-{{ $coe->id }}" title="Edit request">
                              <i class="ti-pencil-alt"></i>
                            </button>
                            <button type="button" title="Cancel request" onclick="cancel({{ $coe->id }})" class="btn btn-danger btn-sm">
                              <i class="fa fa-ban"></i>
                            </button>
                          @elseif ($coe->status == 'Pending' && $coe->level > 0)
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-coe-{{ $coe->id }}" title="View request">
                              <i class="ti-eye"></i>
                            </button>
                          @elseif ($coe->status == 'Approved')
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-coe-{{ $coe->id }}" title="View released request">
                                <i class="ti-eye"></i>
                            </button>
                          @else
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#view-coe-{{ $coe->id }}" title="View request">
                              <i class="ti-eye btn-icon-prepend"></i>
                            </button>
                          @endif
                        </td>

                        <td>
                          <span class="coe-reference">{{ $coe->reference_number }}</span>
                          @if ($coe->status === 'Pending')
                            <span class="coe-status coe-status--pending">Pending</span>
                          @elseif ($coe->status === 'Processing')
                            <span class="coe-status coe-status--processing">Processing</span>
                          @elseif ($coe->status === 'Approved')
                            <span class="coe-status coe-status--released">Released</span>
                          @elseif (in_array($coe->status, ['Declined', 'Cancelled']))
                            <span class="coe-status coe-status--declined">{{ $coe->status }}</span>
                          @endif
                        </td>

                        <td>{{ date('M d, Y', strtotime($coe->created_at)) }}</td>
                        <td>{{ $coe->reason_for_request ?? 'N/A' }}</td>
                        <td>{{ $coe->employment_status ?? 'N/A' }}</td>
                        <td>{{ $coe->purpose ?? 'N/A' }}</td>
                        <td>{{ $coe->receive_method === 'Hard Copy' ? 'Pick up' : ($coe->receive_method ?? 'N/A') }}</td>
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
                            <div class="text-danger">No approver</div>
                          @endif
                          </td>
                          <td style="max-width: 250px; white-space: normal;">
                              <!-- e() keeps the output safe  -->
                              <!-- nl2br preserves new lines -->
                              {!! nl2br(e($coe->approval_remarks ?? '-')) !!}
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
        $(document).ready(function() {
            var coeTable = $('#coeTable');
            if (coeTable.length) {
                coeTable.DataTable().destroy();
                coeTable.DataTable({
                    dom: 'Bfrtip',
                    order: []
                });
            }
        });

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

                        const statusCell = document.querySelector(`tr:has(button[onclick="cancel(${id})"]) .coe-status`);
                        if (statusCell) {
                            statusCell.className = "coe-status coe-status--declined";
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

@include('forms.coerequest.create')

@foreach ($coes as $coe)
 @include('forms.coerequest.show')
 @include('forms.coerequest.edit')
@endforeach
