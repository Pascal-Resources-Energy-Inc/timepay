@php
  $ratings = [
    'rating_job_responsibilities' => 'Job Responsibilities',
    'rating_personal_goals' => 'Opportunities for Achieving Personal Goals',
    'rating_work_environment' => 'Work Environment',
    'rating_immediate_superior' => 'Immediate Superior',
    'rating_salary' => 'Salary',
    'rating_benefits' => 'Benefits',
  ];

  $viewerEmployee = optional(auth()->user())->employee;
  $canEditEmploymentDates = $viewerEmployee
    && $viewerEmployee->status === 'Active'
    && in_array($viewerEmployee->position, [
      'HR Head',
      'Organizational Development - HR Assistant',
      'Audit Head / PQD Head',
    ], true)
    && $exit_interview->submission_source === 'public';
@endphp

@extends('layouts.header')
@section('content')
<style>
  .exit-interview-details {
    overflow-wrap: anywhere;
    word-break: break-word;
  }

  .exit-interview-details .table {
    width: 100%;
    max-width: 100%;
    table-layout: fixed;
  }

  .exit-interview-details .table td {
    min-width: 0;
    white-space: normal !important;
    overflow-wrap: anywhere;
    word-break: break-word;
  }

  .exit-interview-details .exit-interview-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
    min-width: 0;
    max-width: 100%;
  }

  .exit-interview-details .exit-interview-badges .badge {
    max-width: 100%;
    white-space: normal;
    overflow-wrap: anywhere;
    word-break: break-word;
    line-height: 1.25;
  }
</style>
<div class="main-panel">
  <div class="content-wrapper exit-interview-details">
    <div class="row mb-3 align-items-center">
      <div class="col-md-6">
        <h4 class="pt-3 mb-0 font-weight-bold">
          <i class="ti-file mr-1"></i> Exit Interview Details
        </h4>
      </div>
      <div class="col-md-6 text-right">
        <a href="{{ url('exit-interviews') }}" class="btn btn-outline-secondary btn-sm">
          Back to List
        </a>
      </div>
    </div>

    {{-- PERSONAL INFO --}}
    <div class="card border shadow-sm mb-3">
      <div class="card-header rounded-top bg-light py-2 d-flex align-items-center">
        <i class="ti-user text-primary mr-2"></i>
        <h6 class="mb-0 font-weight-bold">Personal Information</h6>
        <span class="ml-auto badge badge-info">{{ date('M d, Y h:i A', strtotime($exit_interview->submitted_at)) }}</span>
      </div>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-4 text-muted">Reference No.:</div>
          <div class="col-md-6">
            {{ $exit_interview->reference_no }}
            @if ($exit_interview->legacy_reference_no)
              <small class="d-block text-muted">Legacy: {{ $exit_interview->legacy_reference_no }}</small>
            @endif
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4 text-muted">Name:</div>
          <div class="col-md-6">
              @if ($exit_interview->full_name)
                  {{ $exit_interview->full_name }}
              @else 
                  {{ $exit_interview->first_name }} {{ $exit_interview->last_name }}
              @endif
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4 text-muted">Email:</div>
          <div class="col-md-6">{{ $exit_interview->email }}</div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4 text-muted">Job Title:</div>
          <div class="col-md-6">{{ $exit_interview->job_title }}</div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4 text-muted">Department:</div>
          <div class="col-md-6">{{ $exit_interview->submission_source === 'legacy_import' && (!$exit_interview->department || $exit_interview->department === 'Legacy data unavailable') ? 'From import (data unavailable)' : ($exit_interview->department ?: '-') }}</div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4 text-muted">Direct Supervisor:</div>
          <div class="col-md-6">{{ $exit_interview->submission_source === 'legacy_import' && (!$exit_interview->direct_supervisor_name || $exit_interview->direct_supervisor_name === 'Legacy data unavailable') ? 'From import (data unavailable)' : ($exit_interview->direct_supervisor_name ?: '-') }}</div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4 text-muted">Work Location:</div>
          <div class="col-md-6">{{ $exit_interview->submission_source === 'legacy_import' && (!$exit_interview->work_location || $exit_interview->work_location === 'Legacy data unavailable') ? 'From import (data unavailable)' : ($exit_interview->work_location ?: '-') }}</div>
        </div>
        @if($canEditEmploymentDates)
          <form
            method="POST"
            action="{{ route('exit-interviews.dates.update', $exit_interview->id) }}"
            class="border rounded bg-light p-3 mt-2"
          >
            @csrf
            @method('PATCH')

            <div class="mb-3">
              <div class="font-weight-bold">Employee Details</div>
              <small class="text-muted">Verify the details below.</small>
            </div>

            <div class="row align-items-end">
              <div class="col-md-4 mb-3 mb-md-0">
                <label for="designation" class="form-label">Designation <span class="text-danger">*</span></label>
                <input
                  type="text"
                  id="designation"
                  name="job_title"
                  class="form-control form-control-sm {{ $errors->has('job_title') ? 'is-invalid' : '' }}"
                  value="{{ old('job_title', $exit_interview->job_title) }}"
                  required
                >
                @if($errors->has('job_title'))
                  <div class="invalid-feedback">{{ $errors->first('job_title') }}</div>
                @endif
              </div>

              <div class="col-md-4 mb-3 mb-md-0">
                <label for="hire-date" class="form-label">Hire Date <span class="text-danger">*</span></label>
                <input
                  type="date"
                  id="hire-date"
                  name="hire_date"
                  class="form-control form-control-sm {{ $errors->has('hire_date') ? 'is-invalid' : '' }}"
                  value="{{ old('hire_date', $exit_interview->hire_date->format('Y-m-d')) }}"
                  max="{{ now()->format('Y-m-d') }}"
                  required
                >
                @if($errors->has('hire_date'))
                  <div class="invalid-feedback">{{ $errors->first('hire_date') }}</div>
                @endif
              </div>

              <div class="col-md-4 mb-3 mb-md-0">
                <label for="separation-date" class="form-label">Separation Date <span class="text-danger">*</span></label>
                <input
                  type="date"
                  id="separation-date"
                  name="separation_date"
                  class="form-control form-control-sm {{ $errors->has('separation_date') ? 'is-invalid' : '' }}"
                  value="{{ old('separation_date', $exit_interview->separation_date->format('Y-m-d')) }}"
                  required
                >
                @if($errors->has('separation_date'))
                  <div class="invalid-feedback">{{ $errors->first('separation_date') }}</div>
                @endif
              </div>

              <div class="col-12 col-sm-auto ml-sm-auto mt-2">
                <button type="submit" class="btn btn-primary btn-sm btn-block">
                  Save changes
                </button>
              </div>
            </div>
          </form>
        @else
          <div class="row mb-3">
            <div class="col-md-4 text-muted">Hire Date:</div>
            <div class="col-md-6">{{ date('M d, Y', strtotime($exit_interview->hire_date)) }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4 text-muted">Separation Date:</div>
            <div class="col-md-6">{{ date('M d, Y', strtotime($exit_interview->separation_date)) }}</div>
          </div>
        @endif
      </div>
    </div>

    {{-- REASON FOR LEAVING --}}
    <div class="card border shadow-sm mb-3">
      <div class="card-header rounded-top bg-light py-2">
        <i class="ti-flag-alt text-primary mr-1"></i>
        <h6 class="mb-0 font-weight-bold d-inline">Reason for Leaving</h6>
      </div>
      <div class="card-body">
        @php
          // Null supports records created before the separation fields existed.
          $isInvoluntary = $exit_interview->is_separation_voluntary === false;
        @endphp

        <table class="table table-bordered mb-0">
          <tr>
            <td class="text-muted" style="width:40%">Is the separation voluntary?</td>
            <td class="font-weight-bold">
              @if (is_null($exit_interview->is_separation_voluntary))
                Not specified
              @elseif ($exit_interview->is_separation_voluntary)
                Yes - Employee submitted a resignation letter
              @else
                No - Employee received a separation letter from Pascal Resources Energy, Inc.
              @endif
            </td>
          </tr>

          @if ($isInvoluntary)
            <tr>
              <td class="text-muted">Type of separation</td>
              <td class="font-weight-bold">{{ $exit_interview->separation_type ?: '-' }}</td>
            </tr>
            <tr>
              <td class="text-muted">Comment</td>
              <td>{{ $exit_interview->separation_comment ?: '-' }}</td>
            </tr>
          @else
            <tr>
              <td class="text-muted">Leaving for a new job?</td>
              <td class="font-weight-bold">
                @if (is_null($exit_interview->is_leaving_for_new_job))
                  Not specified
                @elseif ($exit_interview->is_leaving_for_new_job)
                  Yes
                @else
                  No
                @endif
              </td>
            </tr>

            @if ($exit_interview->new_job_offer_text)
              <tr>
                <td class="text-muted">What does your new job have to offer that your current one does not?</td>
                <td class="font-weight-bold">{{ $exit_interview->new_job_offer_text }}</td>
              </tr>
            @endif

            <tr>
              <td class="text-muted">What made you decide to leave?</td>
              <td>
                @if ($exit_interview->reason_of_leave)
                  <div class="exit-interview-badges">
                    @foreach (array_map('trim', explode(',', $exit_interview->reason_of_leave)) as $reason)
                      <span class="badge badge-info font-weight-bold px-2 py-1">{{ $reason }}</span>
                    @endforeach
                  </div>
                @else
                  —
                @endif
              </td>
            </tr>
          @endif

          <tr>
            <td class="text-muted">Career opportunities important to you</td>
            <td>
              @if ($exit_interview->career_type_importance)
                <div class="exit-interview-badges">
                  @foreach (array_map('trim', explode(',', $exit_interview->career_type_importance)) as $career)
                    <span class="badge badge-info font-weight-bold px-2 py-1">{{ $career }}</span>
                  @endforeach
                </div>
              @else
                —
              @endif
            </td>
          </tr>
        </table>
      </div>
    </div>

    {{-- RATINGS --}}
    <div class="card border shadow-sm mb-3">
      <div class="card-header rounded-top bg-light py-2">
        <i class="ti-stats-up text-primary mr-1"></i>
        <h6 class="mb-0 font-weight-bold d-inline">Ratings</h6>
      </div>
      <div class="card-body">
        <div class="row">
          @foreach ($ratings as $field => $label)
          @php $val = $exit_interview->$field; @endphp
          <div class="col-md-4 mb-2">
            <small class="text-muted d-block">{{ $label }}</small>
            @if (is_null($val))
              <span class="text-muted">Not answered</span>
            @else
              <span class="font-weight-bold">
                @for ($i = 1; $i <= 5; $i++)
                  @if ($i <= $val)
                    <i class="fa fa-star text-warning"></i>
                  @else
                    <i class="fa fa-star-o text-muted"></i>
                  @endif
                @endfor
                <span class="ml-1">({{ $val }}/5)</span>
              </span>
            @endif
          </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- FEEDBACK --}}
    <div class="card border shadow-sm mb-3">
      <div class="card-header rounded-top bg-light py-2">
        <i class="ti-thought text-primary mr-1"></i>
        <h6 class="mb-0 font-weight-bold d-inline">Feedback</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 mb-3">
            <p class="text-muted d-block font-weight-bold mb-1">What did you like MOST about working here?</p>
            <div class="p-3 bg-light rounded">
              {{ $exit_interview->most_liked_text }}
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <p class="text-muted d-block font-weight-bold mb-1">What did you like LEAST about working here?</p>
            <div class="p-3 bg-light rounded">
              {{ $exit_interview->least_liked_text }}
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- COMPANY ALIGNMENT --}}
    <div class="card border shadow-sm mb-3">
      <div class="card-header rounded-top bg-light py-2">
        <i class="ti-check-box text-primary mr-1"></i>
        <h6 class="mb-0 font-weight-bold d-inline">Company Alignment</h6>
      </div>
      <div class="card-body">
        <table class="table table-bordered mb-0">
          <tr>
            <td class="text-muted" style="width:40%">Clear understanding of company goals?</td>
            <td>
              @if ($exit_interview->understands_company_goals) Yes @else No @endif
            </td>
          </tr>
          @if (!$exit_interview->understands_company_goals && $exit_interview->goals_explanation)
          <tr>
            <td class="text-muted">Explanation</td>
            <td>{{ $exit_interview->goals_explanation }}</td>
          </tr>
          @endif
          <tr>
            <td class="text-muted">Company lives up to core values?</td>
            <td>
              @if ($exit_interview->company_lives_core_values) Yes @else No @endif
            </td>
          </tr>
          @if (!$exit_interview->company_lives_core_values && $exit_interview->core_values_explanation)
          <tr>
            <td class="text-muted">Explanation</td>
            <td>{{ $exit_interview->core_values_explanation }}</td>
          </tr>
          @endif
        </table>
      </div>
    </div>

    {{-- PERFORMANCE FEEDBACK & SUGGESTIONS --}}
    <div class="card border shadow-sm mb-3">
      <div class="card-header rounded-top bg-light py-2">
        <i class="ti-comments text-primary mr-1"></i>
        <h6 class="mb-0 font-weight-bold d-inline">Performance Feedback & Suggestions</h6>
      </div>
      <div class="card-body">
        <div class="row mb-2">
          <div class="col-md-5 text-muted font-weight-bold">Frequency of performance feedback:</div>
          <div class="col-md-7 ">{{ $exit_interview->frequently_performance_feedback }}</div>
        </div>
        @if ($exit_interview->suggestion_company || $exit_interview->suggestion_department || $exit_interview->suggestion_other_employees)
        <div class="mt-3 mb-2 text-muted font-weight-bold">Employee's Recommendation:</div>
        <table class="table table-bordered mb-0">
          <tr>
            <td class="text-muted" style="width:30%">Company</td>
            <td>{{ $exit_interview->suggestion_company ?: '-' }}</td>
          </tr>
          <tr>
            <td class="text-muted">Department</td>
            <td>{{ $exit_interview->suggestion_department ?: '-' }}</td>
          </tr>
          <tr>
            <td class="text-muted">Employees</td>
            <td>{{ $exit_interview->suggestion_other_employees ?: '-' }}</td>
          </tr>
        </table>
        @endif
        @if ($exit_interview->other_feedback_text)
        <div class="mt-3 mb-2">
          <p class="text-muted d-block font-weight-bold mb-1">Any other feedback you'd like to give us?</p>
          <div class="p-3 bg-light rounded">
            {{ $exit_interview->other_feedback_text }}
          </div>
        </div>
        @endif
      </div>
    </div>

    <div class="text-right mt-4">
      <a href="{{ url('exit-interviews') }}" class="btn btn-light border px-4">Close</a>
    </div>

  </div>
</div>
@endsection
