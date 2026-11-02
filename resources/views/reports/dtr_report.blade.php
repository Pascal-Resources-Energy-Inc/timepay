@extends('layouts.header')

@section('content')
<style>
  .dtr-report-page .dtr-filter-panel {
    padding: 1.25rem;
    margin-bottom: 1.5rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: .75rem;
  }

  .dtr-report-page .dtr-filter-panel .form-group {
    margin-bottom: 0;
  }

  .dtr-report-page .dtr-form-label {
    display: block;
    margin-bottom: .5rem;
    color: #475569;
    font-size: .875rem;
    font-weight: 600;
  }

  .dtr-report-page .dtr-filter-panel .form-control {
    min-height: 42px;
  }

  .dtr-report-page .dtr-filter-action {
    display: flex;
    align-items: flex-end;
  }

  .dtr-report-page .dtr-filter-action .btn {
    width: 100%;
    min-height: 42px;
  }

  .dtr-report-page .dtr-report-toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
  }

  .dtr-report-page .dtr-report-toolbar h5 {
    margin-bottom: .25rem;
    font-weight: 600;
  }

  .dtr-report-page .dtr-report-toolbar p {
    margin: 0;
    color: #64748b;
    font-size: .875rem;
  }

  .dtr-report-page .dtr-attachment-link {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    margin-top: .5rem;
    font-size: .8rem;
    font-weight: 600;
  }

  .dtr-report-page .dtr-status,
  .dtr-report-modal .dtr-status {
    display: inline-flex;
    align-items: center;
    padding: .3rem .6rem;
    border-radius: 999px;
    font-size: .75rem;
    font-weight: 700;
    line-height: 1;
  }

  .dtr-report-page .dtr-status.is-approved,
  .dtr-report-modal .dtr-status.is-approved {
    background: #dcfce7;
    color: #166534;
  }

  .dtr-report-page .dtr-status.is-pending,
  .dtr-report-modal .dtr-status.is-pending {
    background: #fef3c7;
    color: #92400e;
  }

  .dtr-report-page .dtr-status.is-declined,
  .dtr-report-page .dtr-status.is-cancelled,
  .dtr-report-modal .dtr-status.is-declined,
  .dtr-report-modal .dtr-status.is-cancelled {
    background: #fee2e2;
    color: #991b1b;
  }

  .dtr-report-page .dtr-empty-state {
    padding: 3rem 1rem !important;
    color: #64748b !important;
    text-align: center;
  }

  .dtr-report-page .dtr-empty-state i {
    display: block;
    margin-bottom: .75rem;
    color: #94a3b8;
    font-size: 1.75rem;
  }

  .dtr-report-page .dtr-view-button {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
  }

  .dtr-report-modal .modal-dialog {
    width: calc(100% - 2rem);
    max-width: 820px;
    margin: 1.75rem auto;
  }

  .dtr-report-modal .modal-content {
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 3.5rem);
    overflow: hidden;
    border: 0;
    border-radius: .75rem;
    box-shadow: 0 20px 45px rgba(15, 23, 42, .18);
  }

  .dtr-report-modal .modal-header {
    flex: 0 0 auto;
    align-items: flex-start;
    padding: 1rem 1.25rem;
    color: #ffffff;
    background: linear-gradient(135deg, #54abe6 0%, #3498db 100%);
    border-bottom: 0;
  }

  .dtr-report-modal .modal-title {
    color: #ffffff !important;
    font-weight: 700;
    line-height: 1.35;
  }

  .dtr-report-modal .modal-header .text-muted {
    color: rgba(255, 255, 255, .85) !important;
  }

  .dtr-report-modal .modal-header .close {
    margin: -.25rem -.25rem -.25rem auto;
    padding: .5rem;
    color: #ffffff !important;
    opacity: .85;
    text-shadow: none;
  }

  .dtr-report-modal .modal-header .close:hover,
  .dtr-report-modal .modal-header .close:focus {
    color: #ffffff !important;
    opacity: 1;
  }

  .dtr-report-modal .modal-body {
    flex: 1 1 auto;
    padding: 1.25rem;
    overflow-y: auto;
  }

  .dtr-report-modal .modal-footer {
    flex: 0 0 auto;
    padding: .875rem 1.25rem;
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
  }

  .dtr-report-modal .dtr-modal-section + .dtr-modal-section {
    padding-top: 1.25rem;
    margin-top: 1.25rem;
    border-top: 1px solid #e2e8f0;
  }

  .dtr-report-modal .dtr-modal-section-title {
    margin-bottom: 1rem;
    color: #1e293b;
    font-size: .9rem;
    font-weight: 700;
  }

  .dtr-report-modal .dtr-detail-label {
    display: block;
    margin-bottom: .25rem;
    color: #64748b;
    font-size: .75rem;
    font-weight: 600;
    letter-spacing: .02em;
    text-transform: uppercase;
  }

  .dtr-report-modal .dtr-detail-value {
    margin-bottom: 0;
    color: #1e293b;
    line-height: 1.5;
    overflow-wrap: anywhere;
  }

  .dtr-report-modal .dtr-detail-item {
    margin-bottom: 1rem;
  }

  .dtr-report-modal .dtr-detail-item:last-child {
    margin-bottom: 0;
  }

  .dtr-report-modal .dtr-modal-attachment {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    margin: .25rem .4rem .25rem 0;
  }

  .dtr-report-page .dataTables_wrapper .dt-buttons {
    display: inline-flex;
    gap: .5rem;
    margin: 0;
  }

  .dtr-report-page .dataTables_wrapper .dt-button {
    padding: .45rem .85rem;
    margin: 0;
    color: #334155;
    background: #fff;
    border: 1px solid #cbd5e1;
    border-radius: .375rem;
    box-shadow: none;
  }

  .dtr-report-page .dataTables_wrapper .dt-button:hover {
    color: #1d4ed8;
    background: #eff6ff;
    border-color: #93c5fd;
  }

  .dtr-report-page .dataTables_wrapper .dataTables_filter {
    margin: 0;
    color: #475569;
    font-size: .875rem;
  }

  .dtr-report-page .dataTables_wrapper .dataTables_filter input {
    min-height: 38px;
    margin-left: .5rem;
    border: 1px solid #cbd5e1;
    border-radius: .375rem;
  }

  .dtr-report-page .dtr-table-toolbar,
  .dtr-report-page .dtr-table-footer {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
  }

  .dtr-report-page .dtr-table-toolbar {
    padding: .75rem 0;
  }

  .dtr-report-page .dtr-table-footer {
    padding-top: 1rem;
  }

  .dtr-report-page .dtr-table-footer .dataTables_info,
  .dtr-report-page .dtr-table-footer .dataTables_paginate {
    padding-top: 0;
  }

  @media (max-width: 767.98px) {
    .dtr-report-modal .modal-dialog {
      width: auto;
      margin: .75rem;
    }

    .dtr-report-modal .modal-content {
      max-height: calc(100vh - 1.5rem);
    }

    .dtr-report-modal .modal-header,
    .dtr-report-modal .modal-body,
    .dtr-report-modal .modal-footer {
      padding: 1rem;
    }

    .dtr-report-modal .modal-footer .btn {
      width: 100%;
    }

    .dtr-report-page .dtr-filter-panel {
      padding: 1rem;
    }

    .dtr-report-page .dtr-report-toolbar,
    .dtr-report-page .dtr-report-toolbar .btn {
      width: 100%;
    }

    .dtr-report-page .dataTables_wrapper .dataTables_filter,
    .dtr-report-page .dataTables_wrapper .dataTables_filter input {
      width: 100%;
      margin-left: 0;
    }

    .dtr-report-page .dtr-table-toolbar,
    .dtr-report-page .dtr-table-footer,
    .dtr-report-page .dtr-table-search,
    .dtr-report-page .dtr-table-actions,
    .dtr-report-page .dataTables_wrapper .dt-buttons {
      width: 100%;
      align-items: stretch;
    }

    .dtr-report-page .dataTables_wrapper .dt-button {
      flex: 1;
    }
  }
</style>

<div class="main-panel dtr-report-page">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title mb-1">DTR Report</h4>

            <div class="dtr-filter-panel">
              <form method="get" onsubmit="show();">
                <div class="row">
                  <div class="col-xl-3 col-md-6 mb-3">
                    <div class="form-group">
                      <label class="dtr-form-label" for="company">Company</label>
                      <select id="company"
                        data-placeholder="Select Company"
                        class="form-control required js-example-basic-single"
                        style="width: 100%;"
                        name="company"
                        required>
                        <option value="">-- Select Company --</option>
                        @foreach ($companies as $comp)
                          <option value="{{ $comp->id }}" {{ (string) $comp->id === (string) $company ? 'selected' : '' }}>
                            {{ $comp->company_code }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-xl-2 col-md-6 mb-3">
                    <div class="form-group">
                      <label class="dtr-form-label" for="from">From</label>
                      <input type="date"
                        id="from"
                        value="{{ $from }}"
                        class="form-control"
                        name="from"
                        max="{{ date('Y-m-d') }}"
                        onchange="setDtrReportMinimumDate(this.value);"
                        required>
                    </div>
                  </div>

                  <div class="col-xl-2 col-md-6 mb-3">
                    <div class="form-group">
                      <label class="dtr-form-label" for="to">To</label>
                      <input type="date"
                        id="to"
                        value="{{ $to }}"
                        class="form-control"
                        name="to"
                        min="{{ $from }}"
                        max="{{ date('Y-m-d') }}"
                        required>
                    </div>
                  </div>

                  <div class="col-xl-3 col-md-6 mb-3">
                    <div class="form-group">
                      <label class="dtr-form-label" for="status">Status</label>
                      <select id="status"
                        data-placeholder="Select Status"
                        class="form-control required js-example-basic-single"
                        style="width: 100%;"
                        name="status"
                        required>
                        <option value="">-- Select Status --</option>
                        @foreach (['Approved', 'Pending', 'Cancelled', 'Declined'] as $statusOption)
                          <option value="{{ $statusOption }}" {{ $statusOption === $status ? 'selected' : '' }}>
                            {{ $statusOption }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-xl-2 col-md-6 mb-3 dtr-filter-action">
                    <button type="submit" class="btn btn-primary">
                      Apply filters
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <div class="dtr-report-toolbar">
              <div>
                <h5>DTR correction records</h5>
                <p>{{ count($employee_dtrs) }} {{ count($employee_dtrs) === 1 ? 'record' : 'records' }} found</p>
              </div>

              @if ($company && $from && $to)
                <a href="{{ url('/dtr-report-export') }}?company={{ $company }}&from={{ $from }}&to={{ $to }}&status={{ $status }}"
                  class="btn btn-outline-primary btn-sm"
                  title="Export DTR report">
                  <i class="ti-arrow-down mr-2" aria-hidden="true"></i>
                  Export report
                </a>
              @endif
            </div>

            <div class="table-responsive">
              <table id="dtrReportTable" class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Date</th>
                    <th>Employee Code</th>
                    <th>Name</th>
                    <th>Correction Type</th>
                    <th>Reason / Attachment</th>
                    <th>Status</th>
                    <th>Approver</th>
                    <th>Approver Remarks</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($employee_dtrs as $form_approval)
                    @php
                      $statusClass = 'is-' . strtolower((string) $form_approval->status);
                    @endphp
                    <tr>
                      <td>
                        <button type="button"
                          class="btn btn-sm btn-info dtr-view-button"
                          data-toggle="modal"
                          data-target="#dtrReportDetails{{ $form_approval->id }}"
                          title="View complete request details">
                          <i class="ti-eye" aria-hidden="true"></i>
                        </button>
                      </td>
                      <td>
                        {{ $form_approval->dtr_date ? date('M d, Y', strtotime($form_approval->dtr_date)) : '-' }}
                      </td>
                      <td>{{ data_get($form_approval, 'employee.employee_code', '-') ?: '-' }}</td>
                      <td>{{ data_get($form_approval, 'user.name', '-') ?: '-' }}</td>
                      <td>{{ $form_approval->correction ?: '-' }}</td>
                      <td style="max-width: 250px; white-space: normal;">
                        <div>{{ $form_approval->remarks ?: '-' }}</div>
                        @forelse ($form_approval->available_attachments as $index => $attachment)
                          <a href="{{ url($attachment['path']) }}"
                            target="_blank"
                            rel="noopener"
                            class="dtr-attachment-link">
                            <i class="ti-clip" aria-hidden="true"></i>
                            Attachment {{ $index + 1 }}
                          </a>
                        @empty
                        @endforelse
                      </td>
                      <td>
                        <span class="dtr-status {{ $statusClass }}">{{ $form_approval->status }}</span>
                      </td>
                      <td>
                        @forelse ($form_approval->approver as $approver)
                          <div>{{ data_get($approver, 'approver_info.name', '-') ?: '-' }}</div>
                        @empty
                          -
                        @endforelse
                      </td>
                      <td>
                          {{ $form_approval->approval_remarks ?? '-' }}
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="9" class="dtr-empty-state">
                        <i class="ti-clipboard" aria-hidden="true"></i>
                        @if ($company && $from && $to)
                          No DTR correction records match the selected filters.
                        @else
                          Select a company and date range to generate the report.
                        @endif
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@foreach ($employee_dtrs as $form_approval)
  @php
    $modalStatusClass = 'is-' . strtolower((string) $form_approval->status);
    $employeeDepartment = data_get($form_approval, 'employee.department.name');
    $employeeCompany = data_get($form_approval, 'employee.company.company_code');
    $adjustmentTypes = \App\EmployeeDtr::adjustmentTypes();
    $adjustmentImpact = $form_approval->adjustment_type
      ? ($adjustmentTypes[$form_approval->adjustment_type] ?? null)
      : null;
  @endphp
  <div class="modal fade dtr-report-modal"
    id="dtrReportDetails{{ $form_approval->id }}"
    tabindex="-1"
    role="dialog"
    aria-labelledby="dtrReportDetailsLabel{{ $form_approval->id }}"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div>
            <h5 class="modal-title mb-1" id="dtrReportDetailsLabel{{ $form_approval->id }}">DTR Correction Details</h5>
            <small class="text-muted">
              {{ data_get($form_approval, 'employee.employee_code', '-') ?: '-' }}
              &middot;
              {{ data_get($form_approval, 'user.name', '-') ?: '-' }}
            </small>
          </div>
          <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <section class="dtr-modal-section" aria-labelledby="dtrEmployeeDetails{{ $form_approval->id }}">
            <h6 class="dtr-modal-section-title" id="dtrEmployeeDetails{{ $form_approval->id }}">Employee details</h6>
            <div class="row">
              <div class="col-md-6 dtr-detail-item">
                <span class="dtr-detail-label">Employee code</span>
                <p class="dtr-detail-value">{{ data_get($form_approval, 'employee.employee_code', '-') ?: '-' }}</p>
              </div>
              <div class="col-md-6 dtr-detail-item">
                <span class="dtr-detail-label">Employee name</span>
                <p class="dtr-detail-value">{{ data_get($form_approval, 'user.name', '-') ?: '-' }}</p>
              </div>
              <div class="col-md-6 dtr-detail-item">
                <span class="dtr-detail-label">Position</span>
                <p class="dtr-detail-value">{{ data_get($form_approval, 'employee.position', '-') ?: '-' }}</p>
              </div>
              <div class="col-md-6 dtr-detail-item">
                <span class="dtr-detail-label">Department</span>
                <p class="dtr-detail-value">{{ $employeeDepartment ?: '-' }}</p>
              </div>
              <div class="col-md-6 dtr-detail-item">
                <span class="dtr-detail-label">Company</span>
                <p class="dtr-detail-value">{{ $employeeCompany ?: '-' }}</p>
              </div>
            </div>
          </section>

          <section class="dtr-modal-section" aria-labelledby="dtrRequestDetails{{ $form_approval->id }}">
            <h6 class="dtr-modal-section-title" id="dtrRequestDetails{{ $form_approval->id }}">Request details</h6>
            <div class="row">
              <div class="col-md-6 dtr-detail-item">
                <span class="dtr-detail-label">Date filed</span>
                <p class="dtr-detail-value">
                  {{ $form_approval->created_at ? date('M d, Y h:i A', strtotime($form_approval->created_at)) : '-' }}
                </p>
              </div>
              <div class="col-md-6 dtr-detail-item">
                <span class="dtr-detail-label">DTR date</span>
                <p class="dtr-detail-value">
                  {{ $form_approval->dtr_date ? date('M d, Y', strtotime($form_approval->dtr_date)) : '-' }}
                </p>
              </div>
              <div class="col-md-6 dtr-detail-item">
                <span class="dtr-detail-label">Correction type</span>
                <p class="dtr-detail-value">{{ $form_approval->correction ?: '-' }}</p>
              </div>
              <div class="col-md-6 dtr-detail-item">
                <span class="dtr-detail-label">Adjustment type</span>
                <p class="dtr-detail-value">
                  {{ $form_approval->adjustment_type ?: '-' }}
                  @if ($adjustmentImpact)
                    <small class="d-block text-muted">{{ $adjustmentImpact }}</small>
                  @endif
                </p>
              </div>
              <div class="col-md-6 dtr-detail-item">
                <span class="dtr-detail-label">Time In</span>
                <p class="dtr-detail-value">
                  {{ $form_approval->time_in ? date('M d, Y h:i A', strtotime($form_approval->time_in)) : '-' }}
                </p>
              </div>
              <div class="col-md-6 dtr-detail-item">
                <span class="dtr-detail-label">Time Out</span>
                <p class="dtr-detail-value">
                  {{ $form_approval->time_out ? date('M d, Y h:i A', strtotime($form_approval->time_out)) : '-' }}
                </p>
              </div>
              <div class="col-12 dtr-detail-item">
                <span class="dtr-detail-label">Reason</span>
                <p class="dtr-detail-value">{{ $form_approval->remarks ?: '-' }}</p>
              </div>
              <div class="col-12 dtr-detail-item">
                <span class="dtr-detail-label">Supporting documents</span>
                <div>
                  @forelse ($form_approval->available_attachments as $index => $attachment)
                    <a href="{{ url($attachment['path']) }}"
                      target="_blank"
                      rel="noopener"
                      class="btn btn-sm btn-outline-info dtr-modal-attachment">
                      <i class="ti-clip" aria-hidden="true"></i>
                      Attachment {{ $index + 1 }}
                    </a>
                  @empty
                    <span class="text-muted">No supporting documents attached.</span>
                  @endforelse
                </div>
              </div>
            </div>
          </section>

          <section class="dtr-modal-section" aria-labelledby="dtrApprovalDetails{{ $form_approval->id }}">
            <h6 class="dtr-modal-section-title" id="dtrApprovalDetails{{ $form_approval->id }}">Approval details</h6>
            <div class="row">
              <div class="col-md-6 dtr-detail-item">
                <span class="dtr-detail-label">Status</span>
                <p class="dtr-detail-value">
                  <span class="dtr-status {{ $modalStatusClass }}">{{ $form_approval->status ?: 'Unknown' }}</span>
                </p>
              </div>
              <div class="col-md-6 dtr-detail-item">
                <span class="dtr-detail-label">Approved date</span>
                <p class="dtr-detail-value">
                  {{ $form_approval->approved_date ? date('M d, Y', strtotime($form_approval->approved_date)) : '-' }}
                </p>
              </div>
              <div class="col-md-6 dtr-detail-item">
                <span class="dtr-detail-label">Approver</span>
                <div class="dtr-detail-value">
                  @forelse ($form_approval->approver as $approver)
                    <div>{{ data_get($approver, 'approver_info.name', '-') ?: '-' }}</div>
                  @empty
                    -
                  @endforelse
                </div>
              </div>
              <div class="col-md-6 dtr-detail-item">
                <span class="dtr-detail-label">Approval remarks</span>
                <p class="dtr-detail-value">{{ $form_approval->approval_remarks ?: '-' }}</p>
              </div>
            </div>
          </section>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endforeach

<script>
  @if (count($employee_dtrs) > 0)
  $(document).ready(function () {
    $('#dtrReportTable').DataTable({
      dom: '<"dtr-table-toolbar"<"dtr-table-search"f><"dtr-table-actions"B>>rt<"dtr-table-footer"ip>',
      buttons: ['copy', 'excel'],
      order: []
    });
  });
  @endif

  function setDtrReportMinimumDate(value) {
    var toInput = document.getElementById('to');

    if (toInput) {
      toInput.min = value;

      if (toInput.value && toInput.value < value) {
        toInput.value = value;
      }
    }
  }
</script>
@endsection
