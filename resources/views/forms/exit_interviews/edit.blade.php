@extends('layouts.header')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row mb-3 align-items-center">
      <div class="col-md-6">
        <h4 class="pt-3 mb-0 font-weight-bold">
          Edit Exit Interview
        </h4>
      </div>
      <div class="col-md-6 text-right">
        <a href="{{ url('exit-interviews/'.$exit_interview->id) }}" class="btn btn-outline-secondary btn-sm">
          Back to Details
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <form action="{{ url('exit-interviews/'.$exit_interview->id) }}" method="POST" id="exitInterviewForm">
              @csrf
              @method('PUT')

              @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
              </div>
              @endif

              @if (session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
              @endif

              {{-- SECTION 1: Personal Information --}}
              <h5 class="font-weight-bold text-primary mb-3">
                <i class="ti-user mr-1"></i> Personal Information
              </h5>
              <hr class="mt-0">
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label class="form-label">First Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $exit_interview->first_name) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Last Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $exit_interview->last_name) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" name="email" value="{{ old('email', $exit_interview->email) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Job Title <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="job_title" value="{{ old('job_title', $exit_interview->job_title) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Department <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="department" value="{{ old('department', $exit_interview->department) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Direct Supervisor <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="direct_supervisor_name" value="{{ old('direct_supervisor_name', $exit_interview->direct_supervisor_name) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Work Location <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="work_location" value="{{ old('work_location', $exit_interview->work_location) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Hire Date <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" name="hire_date" value="{{ old('hire_date', $exit_interview->hire_date) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Separation Date <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" name="separation_date" value="{{ old('separation_date', $exit_interview->separation_date) }}" required>
                </div>
              </div>

              {{-- SECTION 2: Reason for Leaving --}}
              <h5 class="font-weight-bold text-primary mb-3 mt-4">
                <i class="ti-flag-alt mr-1"></i> Reason for Leaving
              </h5>
              <hr class="mt-0">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Is your reason for leaving a new job opportunity?</label>
                  <select class="form-control" name="is_leaving_for_new_job" id="newJobSelect" required>
                    <option value="">-- Select --</option>
                    <option value="1" @if($exit_interview->is_leaving_for_new_job == 1) selected @endif>Yes</option>
                    <option value="0" @if($exit_interview->is_leaving_for_new_job == 0) selected @endif>No</option>
                  </select>
                </div>
                <div class="col-md-12 mb-3 {{ $exit_interview->is_leaving_for_new_job == 1 ? '' : 'd-none' }}" id="newJobOfferInput">
                  <label class="form-label">What does your new job have to offer that your current one does not? <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="new_job_offer_text" value="{{ old('new_job_offer_text', $exit_interview->new_job_offer_text) }}" placeholder="e.g., Higher salary, Better benefits, Growth...">
                </div>
                <div class="col-md-12 mb-3">
                  <label class="form-label">What types of career opportunities are important to you? <span class="text-danger">*</span></label>
                  @php
                    $careerOptions = [
                      'Promotional',
                      'Training',
                      'Position Rotation',
                      'Increased Salary and Benefits',
                      'Overseas',
                    ];
                    $careerOld = old('career_type_importance');
                    $savedCareers = $careerOld !== null ? $careerOld : ($exit_interview->career_type_importance ? array_map('trim', explode(',', $exit_interview->career_type_importance)) : []);
                    if ($careerOld === null && $exit_interview->career_type_importance) {
                      $savedCareers = array_map('trim', explode(',', $exit_interview->career_type_importance));
                    } else {
                      $savedCareers = $careerOld ?? [];
                    }
                    $careerOtherText = '';
                    foreach ($savedCareers as $c) {
                      if (!in_array($c, $careerOptions)) { $careerOtherText = $c; break; }
                    }
                  @endphp
                  <div class="row">
                    @foreach ($careerOptions as $career)
                    <div class="col-md-6">
                      <div class="form-check">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input career-importance" name="career_type_importance[]" value="{{ $career }}" @if(in_array($career, $savedCareers)) checked @endif>
                          {{ $career }}
                        </label>
                      </div>
                    </div>
                    @endforeach
                    <div class="col-md-6">
                      <div class="form-check">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input career-importance" name="career_type_importance[]" value="Others" id="careerOthersCheck" @if($careerOtherText !== '') checked @endif>
                          Others
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="row mt-2 {{ $careerOtherText !== '' ? '' : 'd-none' }}" id="careerOthersInput">
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="career_type_importance_other" value="{{ $careerOtherText }}" placeholder="Please specify...">
                    </div>
                  </div>
                </div>
                <div class="col-md-12 mb-3">
                  <label class="form-label">What made you decide to leave? <span class="text-danger">*</span></label>
                  @php
                    $leaveReasons = [
                      'Type of work',
                      'Returning to School',
                      'Rate of Pay',
                      'Lack of Recognition',
                      'Relocation',
                      'Conflict with Other Employees',
                      'Supervision',
                      'Commuting Distance',
                      'Family Circumstance',
                      'Personal Circumstance',
                      'Retirement',
                      'Retrenchment',
                      'Terminated',
                      'End of Contract',
                    ];
                    $savedReasons = $exit_interview->reason_of_leave ? array_map('trim', explode(',', $exit_interview->reason_of_leave)) : [];
                    $othersUsed = !empty($exit_interview->reason_of_leave_other);
                  @endphp
                  <div class="row">
                    @foreach ($leaveReasons as $reason)
                    <div class="col-md-6">
                      <div class="form-check">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input reason-of-leave" name="reason_of_leave[]" value="{{ $reason }}" @if(in_array($reason, $savedReasons)) checked @endif>
                          {{ $reason }}
                        </label>
                      </div>
                    </div>
                    @endforeach
                    <div class="col-md-6">
                      <div class="form-check">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input reason-of-leave" name="reason_of_leave[]" value="Others" id="reasonOthersCheck" @if(in_array('Others', $savedReasons) || $othersUsed) checked @endif>
                          Others
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="row mt-2 {{ in_array('Others', $savedReasons) || $othersUsed ? '' : 'd-none' }}" id="reasonOthersInput">
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="reason_of_leave_other" value="{{ old('reason_of_leave_other', $exit_interview->reason_of_leave_other) }}" placeholder="Please specify...">
                    </div>
                  </div>
                </div>
              </div>

              {{-- SECTION 3: Ratings --}}
              <h5 class="font-weight-bold text-primary mb-3 mt-4">
                <i class="ti-stats-up mr-1"></i> Rate Your Experience
              </h5>
              <hr class="mt-0">
              <p class="text-muted mb-3">Rate each area from 1 (Very Dissatisfied) to 5 (Very Satisfied).</p>
              <div class="row">
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
                @foreach ($ratings as $field => $label)
                <div class="col-md-4 mb-3">
                  <label class="form-label d-block">{{ $label }} <span class="text-danger">*</span></label>
                  <div class="rating-group d-flex gap-2" style="gap: 0.5rem;">
                    @for ($i = 1; $i <= 5; $i++)
                    <label class="rating-label btn btn-outline-secondary btn-sm rounded px-3 @if($exit_interview->$field == $i) btn-primary @endif" style="cursor:pointer;">
                      <input type="radio" name="{{ $field }}" value="{{ $i }}" class="d-none" @if($exit_interview->$field == $i) checked @endif required>
                      {{ $i }}
                    </label>
                    @endfor
                  </div>
                </div>
                @endforeach
              </div>

              {{-- SECTION 4: What You Liked --}}
              <h5 class="font-weight-bold text-primary mb-3 mt-4">
                <i class="ti-thought mr-1"></i> Your Feedback
              </h5>
              <hr class="mt-0">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">What did you like MOST about working here? <span class="text-danger">*</span></label>
                  <textarea class="form-control" name="most_liked_text" rows="3" required>{{ old('most_liked_text', $exit_interview->most_liked_text) }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">What did you like LEAST about working here? <span class="text-danger">*</span></label>
                  <textarea class="form-control" name="least_liked_text" rows="3" required>{{ old('least_liked_text', $exit_interview->least_liked_text) }}</textarea>
                </div>
              </div>

              {{-- SECTION 5: Company Alignment --}}
              <h5 class="font-weight-bold text-primary mb-3 mt-4">
                <i class="ti-check-box mr-1"></i> Company Alignment
              </h5>
              <hr class="mt-0">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">
                    Did you have a strong, clear understanding of the main goals and direction of your department and its contribution to the company's overall vision?
                    <span class="text-danger">*</span>
                  </label>
                  <select class="form-control" name="understands_company_goals" required>
                    <option value="">-- Select --</option>
                    <option value="1" @if($exit_interview->understands_company_goals == 1) selected @endif>Yes</option>
                    <option value="0" @if($exit_interview->understands_company_goals == 0) selected @endif>No</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">
                    Do you believe PREI lives up to its Core Values (Honesty & Integrity, Innovation, Teamwork, Commitment to Serve, Communication and Respect, Hard work)?
                    <span class="text-danger">*</span>
                  </label>
                  <select class="form-control" name="company_lives_core_values" required>
                    <option value="">-- Select --</option>
                    <option value="1" @if($exit_interview->company_lives_core_values == 1) selected @endif>Yes</option>
                    <option value="0" @if($exit_interview->company_lives_core_values == 0) selected @endif>No</option>
                  </select>
                </div>
              </div>

              {{-- SECTION 6: Performance Feedback & Suggestions --}}
              <h5 class="font-weight-bold text-primary mb-3 mt-4">
                <i class="ti-comments mr-1"></i> Performance Feedback & Suggestions
              </h5>
              <hr class="mt-0">
              <div class="row">
                <div class="col-md-12 mb-3">
                  <label class="form-label">How frequently did you receive performance feedback? <span class="text-danger">*</span></label>
                  <select class="form-control" name="frequently_performance_feedback" required>
                    <option value="">-- Select --</option>
                    <option value="Frequently" @if($exit_interview->frequently_performance_feedback == 'Frequently') selected @endif>Frequently</option>
                    <option value="Occasionally" @if($exit_interview->frequently_performance_feedback == 'Occasionally') selected @endif>Occasionally</option>
                    <option value="Rarely" @if($exit_interview->frequently_performance_feedback == 'Rarely') selected @endif>Rarely</option>
                    <option value="Never" @if($exit_interview->frequently_performance_feedback == 'Never') selected @endif>Never</option>
                  </select>
                </div>
                <div class="col-md-12 mb-3">
                  <label class="form-label">Any changes you would like to suggest? Give us your recommendations! <span style="color: gray;">(optional)</span></label>
                  <textarea class="form-control" name="suggestion_company" rows="2" placeholder="For the company...">{{ old('suggestion_company', $exit_interview->suggestion_company) }}</textarea>
                  <textarea class="form-control mt-2" name="suggestion_department" rows="2" placeholder="For your department...">{{ old('suggestion_department', $exit_interview->suggestion_department) }}</textarea>
                  <textarea class="form-control mt-2" name="suggestion_other_employees" rows="2" placeholder="Regarding other employees or teams...">{{ old('suggestion_other_employees', $exit_interview->suggestion_other_employees) }}</textarea>
                </div>
                <div class="col-md-12 mb-3">
                  <label class="form-label">Any other feedback you'd like to give us? <span style="color: gray;">(optional)</span></label>
                  <textarea class="form-control" name="other_feedback_text" rows="2" placeholder="Your additional feedback...">{{ old('other_feedback_text', $exit_interview->other_feedback_text) }}</textarea>
                </div>
              </div>

              <hr>
              <div class="text-right">
                <a href="{{ url('exit-interviews/'.$exit_interview->id) }}" class="btn btn-light border px-4">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">
                  Update
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
  $(document).ready(function() {
    let toggleOthers = function(checkId, wrapId) {
      if ($(checkId).is(':checked')) {
        $(wrapId).removeClass('d-none');
      } else {
        $(wrapId).addClass('d-none').find('input').val('');
      }
    };

    $('#reasonOthersCheck').on('change', function() {
      toggleOthers('#reasonOthersCheck', '#reasonOthersInput');
    });

    $('#careerOthersCheck').on('change', function() {
      toggleOthers('#careerOthersCheck', '#careerOthersInput');
    });

    let toggleNewJobOffer = function() {
      if ($('#newJobSelect').val() === '1') {
        $('#newJobOfferInput').removeClass('d-none');
        $('input[name="new_job_offer_text"]').prop('required', true);
      } else {
        $('#newJobOfferInput').addClass('d-none');
        $('input[name="new_job_offer_text"]').prop('required', false).val('');
      }
    };

    $('#newJobSelect').on('change', toggleNewJobOffer);
    toggleNewJobOffer();

    $('#exitInterviewForm').on('submit', function(e) {
      if ($('.reason-of-leave:checked').length === 0) {
        e.preventDefault();
        alert('Please select at least one reason for leaving.');
        return;
      }
      if ($('#reasonOthersCheck').is(':checked') && !$('input[name="reason_of_leave_other"]').val().trim()) {
        e.preventDefault();
        alert('Please specify your other reason for leaving.');
        return;
      }
      if ($('.career-importance:checked').length === 0) {
        e.preventDefault();
        alert('Please select at least one career opportunity.');
        return;
      }
      if ($('#careerOthersCheck').is(':checked') && !$('input[name="career_type_importance_other"]').val().trim()) {
        e.preventDefault();
        alert('Please specify your other career opportunity.');
        return;
      }
      if ($('#newJobSelect').val() === '1' && !$('input[name="new_job_offer_text"]').val().trim()) {
        e.preventDefault();
        alert('Please specify what your new job has to offer.');
        return;
      }
    });

    $('.rating-label').click(function() {
      let group = $(this).closest('.rating-group');
      group.find('.rating-label').removeClass('btn-primary').addClass('btn-outline-secondary');
      $(this).removeClass('btn-outline-secondary').addClass('btn-primary');
      $(this).find('input[type="radio"]').prop('checked', true);
    });
  });
</script>
<style>
  .rating-group .rating-label input[type="radio"]:checked + .rating-label {
    background-color: #007bff;
    color: #fff;
  }
  .rating-label.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
  }
</style>
@endsection
