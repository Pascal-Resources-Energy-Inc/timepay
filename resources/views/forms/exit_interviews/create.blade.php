@extends('layouts.header')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row mb-3 align-items-center">
      <div class="col-md-6">
        <h4 class="pt-3 mb-0 font-weight-bold">
          Exit Interview Form
        </h4>
      </div>
      <div class="col-md-6 text-right">
        <a href="{{ url('exit-interviews') }}" class="btn btn-outline-secondary btn-sm">
          <i class="ti-arrow-left mr-1"></i> Back to List
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <form action="{{ url('exit-interviews') }}" method="POST" id="exitInterviewForm">
              @csrf

              @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
              </div>
              @endif

              @if (session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
              @endif

              <div class="alert alert-info">
                <i class="ti-info-alt mr-1"></i>
                We would appreciate you taking a few minutes to answer the following questions as honestly as possible. Your individual responses are treated as confidential, and will not become part of your personnel file. Thank you for your cooperation!
              </div>

              {{-- SECTION 1: Personal Information --}}
              <h5 class="font-weight-bold text-primary mb-3">
                <i class="ti-user mr-1"></i> Personal Information
              </h5>
              <hr class="mt-0">
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label class="form-label">First Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="first_name" value="{{ auth()->user()->employee->first_name }}">
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Last Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="last_name" value="{{ auth()->user()->employee->last_name }}" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Job Title <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="job_title" value="{{ auth()->user()->employee->position }}" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Department <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="department" value="{{ auth()->user()->employee->department->name }}" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Direct Supervisor <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="direct_supervisor_name" value="{{ auth()->user()->employee->immediateSupervisor->first_name }} {{ auth()->user()->employee->immediateSupervisor->last_name }}"  required>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Work Location <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="work_location" value="{{ auth()->user()->employee->location }}" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Hire Date <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" name="hire_date" value="{{ auth()->user()->employee->original_date_hired }}" readonly required>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Separation Date <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" name="separation_date" value="{{ old('separation_date', auth()->user()->employee->date_resigned) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                  <div
                    id="totalServiceSummary"
                    class="d-none rounded px-3 py-2 h-100"
                    style="background-color: #f8f9fc; border-left: 3px solid #4b49ac;"
                  >
                    <small class="d-block text-muted mb-1">
                      <i class="ti-time mr-1"></i> Total Service Rendered
                    </small>
                    <p
                      id="totalServiceRendered"
                      class="mb-0 font-weight-bold"
                      style="color: #595959;"
                      aria-live="polite"
                    ></p>
                  </div>
                </div>
              </div>

              {{-- SECTION 2: Reason for Leaving --}}
              <h5 class="font-weight-bold text-primary mb-3 mt-4">
                <i class="ti-flag-alt mr-1"></i> Reason for Leaving
              </h5>
              <hr class="mt-0">
              <div class="row">
                <div class="col-md-12 mb-3">
                  <label class="form-label">
                    Is your separation voluntary? <span class="text-danger">*</span>
                    <small class="d-block text-muted">Kusang-loob ba ang iyong pag-alis sa kumpanya?</small>
                  </label>
                  <div class="row">
                    <div class="col-md-6 mb-2 d-flex">
                      <label class="form-check border rounded px-3 py-2 m-0 w-100 d-flex align-items-center">
                        <input type="radio" class="form-check-input ml-0" name="is_separation_voluntary" value="1" {{ old('is_separation_voluntary') === '1' ? 'checked' : '' }} required>
                        <span class="d-block pl-4">Yes, I submitted my resignation letter</span>
                      </label>
                    </div>
                    <div class="col-md-6 mb-2 d-flex">
                      <label class="form-check border rounded px-3 py-2 m-0 w-100 d-flex align-items-center">
                        <input type="radio" class="form-check-input ml-0" name="is_separation_voluntary" value="0" {{ old('is_separation_voluntary') === '0' ? 'checked' : '' }} required>
                        <span class="d-block pl-4">No, I received a separation letter from Pascal Resources Energy, Inc.</span>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="col-md-12 mb-3 d-none" id="involuntarySeparationFields">
                  <div class="border rounded bg-light p-3">
                    <div class="form-group">
                      <label class="form-label">Choose the type of separation <span class="text-danger">*</span></label>
                      <div class="row">
                        @foreach ([
                          'Retrenchment',
                          'Redundancy',
                          'End of Probationary Period due to Performance Gap',
                          'Termination due to Just Cause'
                        ] as $type)
                          <div class="col-md-6 mb-2 d-flex">
                            <label class="form-check border rounded bg-white px-3 py-2 m-0 w-100 d-flex align-items-center">
                              <input type="radio" class="form-check-input ml-0" name="separation_type" value="{{ $type }}" {{ old('separation_type') === $type ? 'checked' : '' }}>
                              <span class="d-block pl-4">{{ $type }}</span>
                            </label>
                          </div>
                        @endforeach
                      </div>
                    </div>
                    <div class="form-group mb-0">
                      <label class="form-label">Comment <span class="text-danger">*</span></label>
                      <textarea class="form-control" name="separation_comment" rows="3" maxlength="1000" placeholder="Add your comment">{{ old('separation_comment') }}</textarea>
                    </div>
                  </div>
                </div>

                <div class="col-md-12 d-none" id="voluntarySeparationFields">
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label class="form-label">
                        Is your reason for leaving a new job opportunity? <span class="text-danger">*</span>
                        <small class="d-block text-muted">Ang dahilan ba ng iyong pag-alis ay dahil sa bagong oportunidad sa trabaho?</small>
                      </label>
                      <div class="row">
                        @foreach (['1' => 'Yes', '0' => 'No'] as $value => $label)
                          <div class="col-md-6 mb-2 d-flex">
                            <label class="form-check border rounded px-3 py-2 m-0 w-100 d-flex align-items-center">
                              <input type="radio" class="form-check-input ml-0" name="is_leaving_for_new_job" value="{{ $value }}" {{ old('is_leaving_for_new_job') === $value ? 'checked' : '' }}>
                              <span class="d-block pl-4">{{ $label }}</span>
                            </label>
                          </div>
                        @endforeach
                      </div>
                    </div>
                    <div class="col-md-12 mb-3 d-none" id="newJobOfferInput">
                      <label class="form-label">
                        What does your new job have to offer that your current one does not? <span class="text-danger">*</span>
                        <small class="d-block text-muted">Ano ang maiaalok ng bago mong trabaho na wala sa kasalukuyan mong trabaho?</small>
                      </label>
                      <input type="text" class="form-control" name="new_job_offer_text" value="{{ old('new_job_offer_text') }}" maxlength="255" placeholder="e.g., Higher salary, Better benefits, Growth...">
                    </div>
                    <div class="col-md-12 mb-3">
                      <label class="form-label">
                        What made you decide to leave? <span class="text-danger">*</span>
                        <small class="d-block text-muted">Ano ang nag-udyok sayo na umalis?</small>
                      </label>
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
                        ];
                      @endphp
                      <div class="row">
                        @foreach ($leaveReasons as $reason)
                        <div class="col-md-6">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input reason-of-leave" name="reason_of_leave[]" value="{{ $reason }}" {{ in_array($reason, old('reason_of_leave', [])) ? 'checked' : '' }}>
                              {{ $reason }}
                            </label>
                          </div>
                        </div>
                        @endforeach
                        <div class="col-md-6">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input reason-of-leave" name="reason_of_leave[]" value="Others" id="reasonOthersCheck" {{ in_array('Others', old('reason_of_leave', [])) ? 'checked' : '' }}>
                              Others
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="row mt-2 d-none" id="reasonOthersInput">
                        <div class="col-md-6">
                          <input type="text" class="form-control" name="reason_of_leave_other" value="{{ old('reason_of_leave_other') }}" maxlength="255" placeholder="Please specify...">
                        </div>
                      </div>
                    </div>
                  </div>
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
                  @endphp
                  <div class="row">
                    @foreach ($careerOptions as $career)
                    <div class="col-md-6">
                      <div class="form-check">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input career-importance" name="career_type_importance[]" value="{{ $career }}">
                          {{ $career }}
                        </label>
                      </div>
                    </div>
                    @endforeach
                    <div class="col-md-6">
                      <div class="form-check">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input career-importance" name="career_type_importance[]" value="Others" id="careerOthersCheck">
                          Others
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="row mt-2 d-none" id="careerOthersInput">
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="career_type_importance_other" placeholder="Please specify...">
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
                  <div class="rating-group d-flex" style="gap: 0.25rem;">
                    @for ($i = 1; $i <= 5; $i++)
                    <label class="rating-label" style="cursor:pointer; font-size: 1.5rem;">
                      <input type="radio" name="{{ $field }}" value="{{ $i }}" class="d-none" required>
                      <i class="fa fa-star-o text-muted"></i>
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
                  <label class="form-label">
                    What did you like MOST about working here? <span class="text-danger">*</span>
                    <small class="d-block text-muted">Ano ang pinaka nagustuhan mo dito sa PREI?</small>
                  </label>
                  <textarea class="form-control" name="most_liked_text" rows="3" placeholder="Tell us what you enjoyed most..." required></textarea>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">
                    What did you like LEAST about working here? <span class="text-danger">*</span>
                    <small class="d-block text-muted">Ano ang pinaka HINDI mo nagustuhan dito sa PREI?</small>
                  </label>
                  <textarea class="form-control" name="least_liked_text" rows="3" placeholder="Tell us what could be improved..." required></textarea>
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
                    <small class="d-block text-muted">Ikaw ba ay nagkaroon ng malinaw na pagkaintindi sa kung ano ang goal at direction ng iyong Department at kung ano ang contribution nito sa vision ng Kumpanya?</small>
                  </label>
                  <select class="form-control" name="understands_company_goals" id="goalsSelect" required>
                    <option value="">-- Select --</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                  </select>
                </div>
                <div class="col-md-12 mb-3 d-none" id="goalsExplainInput">
                  <label class="form-label">If no, please explain <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="goals_explanation" placeholder="Why don't you have a clear understanding...">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">
                    Do you believe PREI lives up to its Core Values (Honesty & Integrity, Innovation, Teamwork, Commitment to Serve, Communication and Respect, Hard work)?
                    <span class="text-danger">*</span>
                    <small class="d-block text-muted">Ikaw ba ay naniniwala na isinasabuhay ng PREI ang mga nasabing core values?</small>
                  </label>
                  <select class="form-control" name="company_lives_core_values" id="coreValuesSelect" required>
                    <option value="">-- Select --</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                  </select>
                </div>
                <div class="col-md-12 mb-3 d-none" id="coreValuesExplainInput">
                  <label class="form-label">If no, please explain <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="core_values_explanation" placeholder="Why don't you believe PREI lives up to its Core Values...">
                </div>
              </div>

              {{-- SECTION 6: Performance Feedback & Suggestions --}}
              <h5 class="font-weight-bold text-primary mb-3 mt-4">
                <i class="ti-comments mr-1"></i> Performance Feedback & Suggestions
              </h5>
              <hr class="mt-0">
              <div class="row">
                <div class="col-md-12 mb-3">
                  <label class="form-label">
                    How frequently did you receive performance feedback? <span class="text-danger">*</span>
                    <small class="d-block text-muted">Gaano kadalas kayo nag-uusap ng Immediate Superior para sa performance result?</small>
                  </label>
                  <select class="form-control" name="frequently_performance_feedback" required>
                    <option value="">-- Select --</option>
                    @foreach (['Frequently - Madalas', 'Occasionally - Minsan', 'Rarely - Bihira', 'Never - Hindi Kahit Minsan'] as $frequency)
                      <option value="{{ $frequency }}" {{ old('frequently_performance_feedback') === $frequency ? 'selected' : '' }}>{{ $frequency }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-12 mb-3">
                  <label class="form-label">
                    Any changes you would like to suggest? Give us your recommendations! <span style="color: gray;">(optional)</span>
                    <small class="d-block text-muted">Magbigay ng mungkahi patungkol sa mga sumusunod:</small>
                  </label>
                  <textarea class="form-control" name="suggestion_company" rows="2" placeholder="For the company..."></textarea>
                  <textarea class="form-control mt-2" name="suggestion_department" rows="2" placeholder="For your department..."></textarea>
                  <textarea class="form-control mt-2" name="suggestion_other_employees" rows="2" placeholder="Employees"></textarea>
                </div>
                <div class="col-md-12 mb-3">
                  <label class="form-label">Any other feedback you'd like to give us?</label>
                  <textarea class="form-control" name="other_feedback_text" rows="2" placeholder="Your additional feedback..."></textarea>
                </div>
              </div>

              <hr>
              <div class="text-right">
                <a href="{{ url('exit-interviews') }}" class="btn btn-light border px-4">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">
                  Submit
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
    let selectedRadioValue = function(name) {
      return $('input[name="' + name + '"]:checked').val();
    };

    let addCalendarYears = function(date, years) {
      let targetYear = date.getFullYear() + years;
      let targetMonth = date.getMonth();
      let lastDay = new Date(targetYear, targetMonth + 1, 0).getDate();

      return new Date(targetYear, targetMonth, Math.min(date.getDate(), lastDay));
    };

    let addCalendarMonths = function(date, months) {
      let targetMonthIndex = date.getMonth() + months;
      let targetYear = date.getFullYear() + Math.floor(targetMonthIndex / 12);
      let targetMonth = targetMonthIndex % 12;
      let lastDay = new Date(targetYear, targetMonth + 1, 0).getDate();

      return new Date(targetYear, targetMonth, Math.min(date.getDate(), lastDay));
    };

    let updateTotalServiceRendered = function() {
      let hireDateValue = $('input[name="hire_date"]').val();
      let separationDateValue = $('input[name="separation_date"]').val();
      let $totalService = $('#totalServiceRendered');
      let $totalServiceSummary = $('#totalServiceSummary');

      if (!hireDateValue || !separationDateValue) {
        $totalServiceSummary.addClass('d-none');
        $totalService.text('');
        return;
      }

      $totalServiceSummary.removeClass('d-none');

      let hireDate = new Date(hireDateValue + 'T00:00:00');
      let separationDate = new Date(separationDateValue + 'T00:00:00');

      if (separationDate < hireDate) {
        $totalService
          .removeClass('text-muted')
          .addClass('text-danger')
          .text('Separation date must be after hire date');
        return;
      }

      let years = separationDate.getFullYear() - hireDate.getFullYear();

      if (addCalendarYears(hireDate, years) > separationDate) {
        years--;
      }

      let yearCursor = addCalendarYears(hireDate, years);
      let months = 0;

      while (months < 11 && addCalendarMonths(yearCursor, months + 1) <= separationDate) {
        months++;
      }

      let monthCursor = addCalendarMonths(yearCursor, months);
      let days = Math.floor((separationDate - monthCursor) / (1000 * 60 * 60 * 24));

      let durationParts = [];

      if (years > 0) {
        durationParts.push(years + (years === 1 ? ' year' : ' years'));
      }

      if (months > 0) {
        durationParts.push(months + (months === 1 ? ' month' : ' months'));
      }

      durationParts.push(days + (days === 1 ? ' day' : ' days'));

      $totalService
        .removeClass('text-muted text-danger')
        .text(durationParts.join(', '));
    };

    $('input[name="separation_date"]').on('change input', updateTotalServiceRendered);
    updateTotalServiceRendered();

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

    let toggleSeparationFields = function() {
      let selection = selectedRadioValue('is_separation_voluntary');
      let isVoluntary = selection === '1';
      let isInvoluntary = selection === '0';

      $('#voluntarySeparationFields')
        .toggleClass('d-none', !isVoluntary)
        .find(':input')
        .prop('disabled', !isVoluntary);

      $('#involuntarySeparationFields')
        .toggleClass('d-none', !isInvoluntary)
        .find(':input')
        .prop('disabled', !isInvoluntary);

      $('input[name="is_leaving_for_new_job"]').prop('required', isVoluntary);
      $('input[name="separation_type"]').prop('required', isInvoluntary);
      $('textarea[name="separation_comment"]').prop('required', isInvoluntary);

      toggleNewJobOffer();
    };

    $('input[name="is_separation_voluntary"]').on('change', toggleSeparationFields);

    let toggleNewJobOffer = function() {
      if (selectedRadioValue('is_separation_voluntary') === '1' && selectedRadioValue('is_leaving_for_new_job') === '1') {
        $('#newJobOfferInput').removeClass('d-none');
        $('input[name="new_job_offer_text"]').prop('required', true);
      } else {
        $('#newJobOfferInput').addClass('d-none');
        $('input[name="new_job_offer_text"]').prop('required', false).val('');
      }
    };

    $('input[name="is_leaving_for_new_job"]').on('change', toggleNewJobOffer);

    let toggleGoalsExplain = function() {
      if ($('#goalsSelect').val() === '0') {
        $('#goalsExplainInput').removeClass('d-none');
        $('input[name="goals_explanation"]').prop('required', true);
      } else {
        $('#goalsExplainInput').addClass('d-none');
        $('input[name="goals_explanation"]').prop('required', false).val('');
      }
    };
    $('#goalsSelect').on('change', toggleGoalsExplain);

    let toggleCoreValuesExplain = function() {
      if ($('#coreValuesSelect').val() === '0') {
        $('#coreValuesExplainInput').removeClass('d-none');
        $('input[name="core_values_explanation"]').prop('required', true);
      } else {
        $('#coreValuesExplainInput').addClass('d-none');
        $('input[name="core_values_explanation"]').prop('required', false).val('');
      }
    };
    $('#coreValuesSelect').on('change', toggleCoreValuesExplain);

    toggleOthers('#reasonOthersCheck', '#reasonOthersInput');
    toggleOthers('#careerOthersCheck', '#careerOthersInput');
    toggleSeparationFields();
    toggleGoalsExplain();
    toggleCoreValuesExplain();

    $('#exitInterviewForm').on('submit', function(e) {
      let isVoluntary = selectedRadioValue('is_separation_voluntary') === '1';

      if (isVoluntary && selectedRadioValue('is_leaving_for_new_job') === '1' && !$('input[name="new_job_offer_text"]').val().trim()) {
        e.preventDefault();
        alert('Please specify what your new job has to offer.');
        return;
      }
      if (isVoluntary && $('.reason-of-leave:checked').length === 0) {
        e.preventDefault();
        alert('Please select at least one reason for leaving.');
        return;
      }
      if (isVoluntary && $('#reasonOthersCheck').is(':checked') && !$('input[name="reason_of_leave_other"]').val().trim()) {
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
      if ($('#goalsSelect').val() === '0' && !$('input[name="goals_explanation"]').val().trim()) {
        e.preventDefault();
        alert('Please explain why you don\'t have a clear understanding of the company goals.');
        return;
      }
      if ($('#coreValuesSelect').val() === '0' && !$('input[name="core_values_explanation"]').val().trim()) {
        e.preventDefault();
        alert('Please explain why you don\'t believe PREI lives up to its Core Values.');
        return;
      }
    });

    let fillStars = function(group, upto) {
      group.find('.rating-label').each(function() {
        let star = parseInt($(this).find('input').val());
        let icon = $(this).find('i');
        if (star <= upto) {
          icon.removeClass('fa-star-o text-muted').addClass('fa-star text-warning');
        } else {
          icon.removeClass('fa-star text-warning').addClass('fa-star-o text-muted');
        }
      });
    };

    let restoreStars = function(group) {
      let checked = group.find('input[type="radio"]:checked').val();
      fillStars(group, checked || 0);
    };

    $('.rating-group').each(function() {
      restoreStars($(this));
    });

    $('.rating-label').on('mouseenter', function() {
      fillStars($(this).closest('.rating-group'), parseInt($(this).find('input').val()));
    });

    $('.rating-group').on('mouseleave', function() {
      restoreStars($(this));
    });

    $('.rating-label').click(function() {
      let group = $(this).closest('.rating-group');
      group.find('input[type="radio"]').prop('checked', false);
      $(this).find('input').prop('checked', true);
      fillStars(group, parseInt($(this).find('input').val()));
    });
  });
</script>
@endsection
