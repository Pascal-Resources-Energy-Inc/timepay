@php
  $ratings = [
    'rating_job_responsibilities' => 'Job Responsibilities',
    'rating_personal_goals' => 'Opportunities for Achieving Personal Goals',
    'rating_work_environment' => 'Work Environment',
    'rating_immediate_superior' => 'Immediate Superior',
    'rating_salary' => 'Salary',
    'rating_benefits' => 'Benefits',
  ];
@endphp

@extends('layouts.header')
@section('content')
<style>
    .dataTables_wrapper .dataTables_filter input.form-control-sm {
        height: calc(1.5em + 0.5rem + 2px);
        padding: 0.25rem 0.5rem;
    }

  .exit-interview-motivations {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.25rem;
    width: 10rem;
    max-width: 14rem;
    min-width: 0;
    white-space: normal;
  }

  .exit-interview-motivations .badge {
    max-width: 100%;
    white-space: normal;
    overflow-wrap: anywhere;
    word-break: break-word;
    line-height: 1.25;
    text-align: left;
  }

  .exit-interviews-page .exit-interviews-header {
    gap: 1rem;
  }

  .exit-interviews-page .exit-interviews-title {
    color: #17203d;
    font-size: 1.75rem;
    letter-spacing: -0.02em;
  }

  .exit-interviews-page .exit-response-stat .stat-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 3.25rem;
    height: 3.25rem;
    color: #315bea;
    background: #eef3ff;
    border-radius: 50%;
    font-size: 1.35rem;
  }

  .exit-interviews-page .exit-responses-card {
    border: 1px solid #e4e9f2;
    border-radius: 0.85rem;
    box-shadow: 0 0.35rem 1rem rgba(31, 45, 86, 0.05);
  }

  .exit-interviews-page .responses-heading {
    color: #315bea;
    font-size: 1rem;
    border-bottom: 3px solid #315bea;
  }

  .exit-interviews-page .exit-interview-toolbar {
    display: flex;
    flex-wrap: wrap;
    flex-direction: column;
    align-items: stretch;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }

  .exit-interviews-page .exit-date-filters {
    width: 100%;
  }

  .exit-interviews-page .exit-interview-search {
    margin-left: auto;
  }

  .exit-interviews-page .exit-interview-search .dataTables_filter {
    margin: 0;
    text-align: left;
  }

  .exit-interviews-page .exit-interview-search .dataTables_filter label {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin: 0;
  }

  .exit-interviews-page .exit-interview-search .dataTables_filter input {
    width: 16rem;
    height: 2.65rem;
    margin: 0.5rem 0 0 !important;
  }

  .exit-interviews-page .table thead th {
    color: #293044;
    background: #f8fafc;
    font-weight: 600;
    vertical-align: middle;
  }

  .exit-interviews-page .exit-service-rendered {
    margin-top: 0.55rem;
    padding-top: 0.45rem;
    border-top: 1px solid #e5eaf2;
    line-height: 1.35;
  }

  .exit-interviews-page .exit-service-rendered-value {
    color: #315bea;
    font-size: 0.75rem;
  }

  .exit-interviews-page .exit-invitation-notice {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.65rem 0.75rem;
    color: #64748b;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-left: 3px solid #93c5fd;
    border-radius: 0.4rem;
  }

  .exit-interviews-page .exit-invitation-notice-icon {
    display: inline-flex;
    flex: 0 0 auto;
    align-items: center;
    justify-content: center;
    width: auto;
    height: auto;
    color: #64748b;
    font-size: 0.85rem;
  }

  .exit-interviews-page .exit-invitation-notice-title {
    color: #1e3a8a;
    font-size: 0.95rem;
    font-weight: 700;
  }

  .exit-interviews-page .exit-invitation-notice-text {
    color: #64748b;
    font-size: 0.78rem;
    line-height: 1.4;
  }

  @media (max-width: 767.98px) {
    .exit-interviews-page .exit-interviews-header {
      align-items: stretch !important;
    }

    .exit-interviews-page .exit-interview-toolbar,
    .exit-interviews-page .exit-date-filters,
    .exit-interviews-page .exit-interview-search,
    .exit-interviews-page .exit-interview-search .dataTables_filter,
    .exit-interviews-page .exit-interview-search .dataTables_filter label,
    .exit-interviews-page .exit-interview-search .dataTables_filter input {
      width: 100%;
    }
  }
</style>
<div class="main-panel">
  <div class="content-wrapper exit-interviews-page">
    <!-- <div class="exit-interviews-header d-flex flex-wrap align-items-center justify-content-between mb-4"> -->
    <!--   <div> -->
    <!--     <h3 class="exit-interviews-title mb-1 font-weight-bold">Exit Interviews</h3> -->
    <!--   </div> -->
    <!-- </div> -->

    @php
      $isHrHead = optional(auth()->user()->employee)->position === 'HR Head';
      $canImportLegacyExitInterviews = $isHrHead || auth()->id() === 856;
    @endphp

    @if ($isHrHead)
      <div class="modal fade" id="sendExitInterviewLinkModal" tabindex="-1" role="dialog" aria-labelledby="sendExitInterviewLinkModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <form method="post" action="{{ route('exit-interview.invitation.send') }}" onsubmit="show()">
            @csrf
            <div class="modal-content border-0">
              <div class="modal-header">
                <div>
                  <h5 class="modal-title mb-1" id="sendExitInterviewLinkModalTitle">Send Exit Interview Form Link</h5>
                </div>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">
                <!-- <div class="exit-invitation-notice mb-4" role="note"> -->
                <!--   <span class="exit-invitation-notice-icon" aria-hidden="true"> -->
                <!--     <i class="ti-link"></i> -->
                <!--   </span> -->
                <!--   <div> -->
                <!--     <div class="exit-invitation-notice-text"> -->
                <!--       <strong>Note:</strong> Enter the employee's registered email address to send the exit interview link. -->
                <!--     </div> -->
                <!--   </div> -->
                <!-- </div> -->

                <div class="form-group">
                  <label for="exitInvitationEmail">Email Address <span class="text-danger">*</span></label>
                  <input
                    type="email"
                    name="recipient_email"
                    id="exitInvitationEmail"
                    class="form-control"
                    value="{{ old('recipient_email') }}"
                    placeholder="Enter valid email address"
                    maxlength="255"
                    required
                    autocomplete="email"
                  >
                  @if ($errors->has('recipient_email'))
                    <small class="text-danger">{{ $errors->first('recipient_email') }}</small>
                  @endif
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light border" data-dismiss="modal">Cancel</button>
                <button type="submit" class="d-flex align-items-center btn btn-sm btn-primary">
                  <i class="ti-email mr-2"></i> Send Form Link
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    @endif

    @if ($canImportLegacyExitInterviews)
      <div class="modal fade" id="importLegacyExitInterviewsModal" tabindex="-1" role="dialog" aria-labelledby="importLegacyExitInterviewsModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <form method="post" action="{{ route('exit-interviews.import.preview') }}" enctype="multipart/form-data" onsubmit="show()">
            @csrf
            <div class="modal-content border-0">
              <div class="modal-header">
                <div>
                  <h5 class="modal-title mb-1" id="importLegacyExitInterviewsModalTitle">Import Legacy Exit Interviews</h5>
                  <small class="text-muted">Review valid and skipped records before anything is saved.</small>
                </div>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group mb-0">
                  <label for="legacyExitInterviewFile">Legacy Excel workbook <span class="text-danger">*</span></label>
                  <input
                    type="file"
                    name="legacy_file"
                    id="legacyExitInterviewFile"
                    class="form-control-file border rounded p-2"
                    accept=".xlsx,.xls"
                    required
                  >
                  <small class="form-text text-muted">Accepted formats: .xlsx or .xls, up to 10 MB.</small>
                  @if ($errors->has('legacy_file'))
                    <small class="text-danger">{{ $errors->first('legacy_file') }}</small>
                  @endif
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light border" data-dismiss="modal">Cancel</button>
                <button type="submit" class="d-flex align-items-center btn btn-sm btn-primary">
                  <i class="ti-search mr-2"></i> Preview Import
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    @endif

    <div class="row mb-4">
      <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3 mb-2">
        <div class="card card-tale">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h5 class="mb-4 text-nowrap">Total Responses</h5>
                <span class="h4 card-text text-white text-truncate d-block">{{ $exit_interviews->count() }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      {{-- Awaiting Response card is temporarily hidden.
      @if (optional(auth()->user()->employee)->position === 'HR Head')
        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
          <div class="card bg-warning text-white border-0 h-100">
            <div class="card-body">
              <h4 class="card-title text-white mb-4">Awaiting Response</h4>
              <h2 class="mb-0 font-weight-normal">{{ $awaitingResponseCount }}</h2>
            </div>
          </div>
        </div>
      @endif
      --}}
    </div>

    <div class='row'>
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card exit-responses-card">
          <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 border-bottom">
              <h4 class="responses-heading mb-0 px-3 pb-3 font-weight-bold">Exit Interview</h4>
              <div class="d-flex flex-wrap align-items-center px-3 pb-2">
                @php
                  $currentEmployee = optional(auth()->user())->employee;
                  $hasActiveSeparationLetter = $currentEmployee
                    && $currentEmployee->employeeDocuments->contains(function ($document) {
                      return (string) $document->document_type === '17'
                        && is_null($document->retracted_at);
                    });
                @endphp
                @if ($isHrHead)
                  <button type="button" class="btn btn-outline-info d-flex align-items-center btn-sm mr-2 mb-1" data-toggle="modal" data-target="#sendExitInterviewLinkModal">
                    <i class="ti-email mr-2"></i>
                    Send Form Link
                  </button>
                @endif
                @if ($canImportLegacyExitInterviews)
                  <button type="button" class="btn btn-outline-success d-flex align-items-center btn-sm mr-2 mb-1" data-toggle="modal" data-target="#importLegacyExitInterviewsModal">
                    <i class="ti-upload mr-2"></i>
                    Import Legacy Data
                  </button>
                @endif
                  <a href="{{ url('exit-interviews/create') }}" rel="noopener" class="d-flex align-items-center btn btn-sm btn-outline-primary mb-1">
                    <i class="ti-plus mr-2"></i>
                    Fill out Exit Interview
                  </a>
              </div>
            </div>

            <div class="exit-interview-toolbar">
              <form method="get" onsubmit="show();" class="exit-date-filters border rounded bg-light p-3 mt-3 mb-4">
                <div class="row align-items-end">
                  <div class="col-12 col-md-6 col-xl-3">
                    <div class="form-group mb-3 mb-xl-0">
                      <label>From</label>
                      <input type="date" value="{{ $from }}" class="form-control form-control-sm" name="from" max="{{ date('Y-m-d') }}" required />
                    </div>
                  </div>
                  <div class="col-12 col-md-6 col-xl-3">
                    <div class="form-group mb-3 mb-xl-0">
                      <label>To</label>
                      <input type="date" value="{{ $to }}" class="form-control form-control-sm" name="to" required />
                    </div>
                  </div>
                  <div class="col-12 col-sm-auto pl-sm-1">
                    <div class="form-group mb-3 mb-xl-0">
                      <button type="submit" class="d-flex align-items-center form-control-sm filter-btn btn btn-primary btn-sm" title="Apply Filters" aria-label="Apply filters">
                        <i class="ti-filter"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </form>
              <div id="exitInterviewSearch" class="exit-interview-search"></div>
            </div>

            <div class="table-responsive">
              <table id="exitInterviewTable" class="table table-hover table-bordered tablewithSearch">
                <thead>
                  <tr>
                    <th style="width: fit-content;">Action</th>
                    <th>Submission Date</th>
                    <th>Reference No.</th>
                    <th>Name</th>
                    <th>Ratings</th>
                    <th>Exit Motivation</th>
                    <th>Most Liked</th>
                    <th>Least Liked</th>
                    <th>Is Voluntary</th>
                    <th>Leaving for New Job</th>
                    <th>Employment Period</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($exit_interviews as $item)
                  <tr>
                    <td style="text-align: center;">
                      <a href="{{ url('exit-interviews/' . $item->id) }}">
                        <button type="button" class="btn btn-info btn-rounded btn-icon" title="View">
                          <i class="ti-eye"></i>
                        </button>
                      </a>
                    </td>
                    <td>
                      <small>
                        {{ date('M d, Y h:i A', strtotime($item->created_at)) }}
                      </small>
                    </td>

                    <!-- <td @if ($item->employee && $item->employee->status === 'Active') style="background-color: #fde8e8;" @endif>{{ $item->reference_no }}</td> -->
                    <td>
                      {{ $item->reference_no }}
                      @if ($item->legacy_reference_no)
                        <small class="d-block mt-1 text-muted">Old: {{ $item->legacy_reference_no }}</small>
                      @endif
                      @if($item->submission_source === 'public')
                        <small class="d-flex align-items-center mt-1 text-info text-nowrap">
                          <span class="d-inline-block text-danger" style="width: 0.4rem; height: 0.4rem;">Public submission</span>
                        </small>
                      @endif
                    </td>
                    <td>
                        <!-- handles legacy data -->
                        <div class="mb-1">
                            @if ($item->full_name)
                                {{ $item->full_name }}
                            @else 
                                {{ $item->first_name }} {{ $item->last_name }}
                            @endif
                        </div>
                        <small class="d-block mb-1 text-muted">{{ $item->job_title }}</small>
                        <small class="d-block mb-1 text-muted">
                          {{ $item->submission_source === 'legacy_import' && (!$item->department || $item->department === 'Legacy data unavailable')
                            ? 'From import (data unavailable)'
                            : ($item->department ?: '-') }}
                        </small>
                        <small class="d-block mb-1 text-info">{{ $item->employee_number }}</small>
                    </td>
                    <td>
                      @foreach ($ratings as $field => $label)
                        <small class="d-block mb-1" style="font-size: 0.7rem; line-height: 1.2;">
                          <span class="d-block text-muted">{{ $label === 'Opportunities for Achieving Personal Goals' ? 'Growth Opportunities' : $label }}</span>
                          @if (is_null($item->$field))
                            <span class="text-muted">Not answered</span>
                          @else
                            <span class="text-nowrap" title="{{ $item->$field }} out of 5">
                              @for ($i = 1; $i <= 5; $i++)
                                <i class="fa {{ $i <= $item->$field ? 'fa-star text-warning' : 'fa-star-o text-muted' }}"></i>
                              @endfor
                              <span class="ml-1">({{ $item->$field }}/5)</span>
                            </span>
                          @endif
                        </small>
                      @endforeach
                    </td>
                    <td>
                      @if ($item->reason_of_leave)
                        <div class="exit-interview-motivations" title="{{ $item->reason_of_leave }}">
                          @foreach (array_filter(array_map('trim', explode(',', $item->reason_of_leave))) as $reason)
                            <span class="badge badge-info font-weight-bold px-2 py-1">{{ $reason }}</span>
                          @endforeach
                        </div>
                      @else
                        <span class="text-muted">--</span>
                      @endif
                    </td>
                    <td>
                      <div style="width: 14rem; max-width: 20rem; white-space: normal; overflow-wrap: anywhere; word-break: break-word; line-height: 1.4;">
                        {{ $item->most_liked_text }}
                      </div>
                    </td>
                    <td>
                      <div style="width: 14rem; max-width: 20rem; white-space: normal; overflow-wrap: anywhere; word-break: break-word; line-height: 1.4;">
                        {{ $item->least_liked_text }}
                      </div>
                    </td>
                    <td>
                      @if (is_null($item->is_separation_voluntary))
                        <span class="text-muted">--</span>
                      @elseif ($item->is_separation_voluntary)
                        Yes
                      @else
                        No
                      @endif
                    </td>
                    <td>
                      @if ($item->is_leaving_for_new_job)
                        Yes
                      @else
                        No
                      @endif
                    </td>
                    <td>
                      @php
                        $hireDate = \Carbon\Carbon::parse($item->hire_date)->startOfDay();
                        $separationDate = \Carbon\Carbon::parse($item->separation_date)->startOfDay();
                        $serviceRendered = null;

                        if ($separationDate->gte($hireDate)) {
                          $serviceYears = $separationDate->year - $hireDate->year;

                          if ($hireDate->copy()->addYearsNoOverflow($serviceYears)->gt($separationDate)) {
                            $serviceYears--;
                          }

                          $serviceYearCursor = $hireDate->copy()->addYearsNoOverflow($serviceYears);
                          $serviceMonths = 0;

                          while (
                            $serviceMonths < 11
                            && $serviceYearCursor->copy()->addMonthsNoOverflow($serviceMonths + 1)->lte($separationDate)
                          ) {
                            $serviceMonths++;
                          }

                          $serviceMonthCursor = $serviceYearCursor->copy()->addMonthsNoOverflow($serviceMonths);
                          $serviceDays = $serviceMonthCursor->diffInDays($separationDate);
                          $serviceParts = [];

                          if ($serviceYears > 0) {
                            $serviceParts[] = $serviceYears . ($serviceYears === 1 ? ' year' : ' years');
                          }

                          if ($serviceMonths > 0) {
                            $serviceParts[] = $serviceMonths . ($serviceMonths === 1 ? ' month' : ' months');
                          }

                          $serviceParts[] = $serviceDays . ($serviceDays === 1 ? ' day' : ' days');
                          $serviceRendered = implode(', ', $serviceParts);
                        }
                      @endphp

                      <div class="d-flex align-items-center mb-1">
                        <small class="text-uppercase text-muted mr-2" style="min-width: 3rem; font-size: 0.65rem; letter-spacing: 0.04em;">Hire Date:</small>
                        <small class="font-weight-bold text-nowrap">{{ date('M d, Y', strtotime($item->hire_date)) }}</small>
                      </div>
                      <div class="d-flex align-items-center">
                        <small class="text-uppercase text-muted mr-2" style="min-width: 3rem; font-size: 0.65rem; letter-spacing: 0.04em;">Resign Date:</small>
                        <small class="font-weight-bold text-nowrap">{{ date('M d, Y', strtotime($item->separation_date)) }}</small>
                      </div>
                      <div class="exit-service-rendered">
                        <small class="d-block text-muted" style="font-size: 0.65rem; letter-spacing: 0.02em;">
                          <i class="ti-time mr-1"></i> Service rendered
                        </small>
                        <small class="exit-service-rendered-value font-weight-bold text-nowrap">
                          {{ $serviceRendered ?: 'Invalid date range' }}
                        </small>
                      </div>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="11" class="text-center text-muted">No exit interviews found.</td>
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
<script>
  $('#exitInterviewTable').on('init.dt', function (event, settings) {
    $(settings.nTableWrapper)
      .find('.dataTables_filter')
      .appendTo('#exitInterviewSearch');
  });
</script>
@endsection

@section('js')
<script>
  $(function () {
    var modal = $('#sendExitInterviewLinkModal');

    @if ($errors->has('recipient_email'))
      modal.modal('show');
    @endif
  });
</script>
@endsection
