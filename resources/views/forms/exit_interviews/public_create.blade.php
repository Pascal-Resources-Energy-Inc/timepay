<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<style>
    body {
        font-family: Roboto, sans-serif;
        background: #f8fafc;
    }

    .field {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: .5rem;
        background: #fff;
        padding: .65rem .75rem;
        color: #111827;
        font-size: .875rem;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
    }

    .field:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
    }

    .field.is-invalid {
        border-color: #dc2626;
        background: #fffafa;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, .08);
    }

    .choice-card.is-invalid {
        border-color: #dc2626;
        background: #fffafa;
    }

    .rating-group.is-invalid {
        border: 1px solid #dc2626;
        border-radius: .5rem;
        background: #fffafa;
        padding: .5rem;
    }

    .validation-message {
        display: none;
        margin-top: .5rem;
        color: #dc2626;
        font-size: .75rem;
        font-weight: 500;
    }

    .validation-message.is-visible {
        display: block;
    }

    .choice-card:has(input:checked) {
        border-color: #2563eb;
        background: #eff6ff;
    }

    .rating-star {
        color: #d1d5db;
        cursor: pointer;
        font-size: 1.75rem;
        line-height: 1;
        transition: color .15s, transform .15s;
    }

    @media (min-width: 640px) {
        .rating-star {
            font-size: 2rem;
        }
    }

    .rating-star:hover {
        transform: scale(1.08);
    }

    .rating-star.is-active {
        color: #f59e0b;
    }
</style>

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

    $separationTypes = [
        'Retrenchment',
        'Redundancy',
        'End of Probationary Period due to Performance Gap',
        'Termination due to Just Cause',
    ];

    $careerOptions = [
        'Promotional',
        'Training',
        'Position Rotation',
        'Increased Salary and Benefits',
        'Overseas',
    ];

    $ratings = [
        'rating_job_responsibilities' => 'Job Responsibilities',
        'rating_personal_goals' => 'Opportunities for Achieving Personal Goals',
        'rating_work_environment' => 'Work Environment',
        'rating_immediate_superior' => 'Immediate Superior',
        'rating_salary' => 'Salary',
        'rating_benefits' => 'Benefits',
    ];
@endphp

<div class="max-w-4xl mx-auto px-4 py-6 sm:py-8">
    <header class="mb-6">
        <h1 class="mb-1 text-xl text-gray-900 sm:text-2xl">PASCAL RESOURCES ENERGY, INC.</h1>
        <div class="mb-3 flex w-full">
            <div class="w-full border-[3px] border-[#59c2e5]"></div>
            <div class="w-full border-[3px] border-[#fe0001]"></div>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Exit Interview Form</h2>
        <div class="mt-4 text-sm leading-6 text-gray-700">
            <p>We would appreciate you taking a few minutes to answer the following questions as honestly as possible. Your individual responses are treated as confidential, and will not become part of your personnel file. Thank you for your cooperation!</p>
        </div>
    </header>

    @if (session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700" role="alert">
            <strong>Please review the following:</strong>
            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('exit-interview.public.store') }}" method="POST" id="exitInterviewForm" novalidate>
        @csrf

        <section class="mb-5 rounded-xl border border-gray-200 bg-white p-4 sm:p-6">
            <div class="mb-6 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">A</span>
                <h3 class="text-base font-bold text-gray-900">Personal Information</h3>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-500">Employee Number</label>
                    <input type="text" name="employee_number" value="{{ old('employee_number') }}" placeholder="Enter your employee number" maxlength="50" autocomplete="off" class="field">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-500">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="Juan" maxlength="100" autocomplete="given-name" class="field">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-500">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="Dela Cruz" maxlength="100" autocomplete="family-name" class="field">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-500">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Use your registered email address" maxlength="255" autocomplete="email" class="field">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-500">Job Title <span class="text-red-500">*</span></label>
                    <input type="text" name="job_title" value="{{ old('job_title') }}" required placeholder="e.g., Marketing Supervisor" maxlength="150" class="field">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-500">Department <span class="text-red-500">*</span></label>
                    <select name="department" required class="field">
                        <option value="" disabled {{ old('department') ? '' : 'selected' }}>Select department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->name }}" {{ old('department') === $department->name ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-500">Direct Supervisor <span class="text-red-500">*</span></label>
                    <input type="text" name="direct_supervisor_name" value="{{ old('direct_supervisor_name') }}" placeholder="Name of your Direct Supervisor" required maxlength="255" class="field">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-500">Work Location <span class="text-red-500">*</span></label>
                    <input type="text" name="work_location" value="{{ old('work_location') }}" required placeholder="e.g., HO, Lubao" maxlength="150" class="field">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-500">Hire Date <span class="text-red-500">*</span></label>
                    <input type="date" name="hire_date" value="{{ old('hire_date') }}" max="{{ date('Y-m-d') }}" required class="field">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-500">Separation Date <span class="text-red-500">*</span></label>
                    <input type="date" name="separation_date" value="{{ old('separation_date') }}" required class="field">
                </div>
                <div
                    id="totalServiceSummary"
                    class="hidden rounded-lg border border-blue-100 border-l-4 border-l-blue-600 bg-blue-50 px-4 py-3"
                >
                    <div class="mb-1 flex items-center gap-2 text-xs font-semibold text-gray-500">
                        <svg class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke-width="1.8"></circle>
                            <path d="M12 7v5l3 2" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        Total Service Rendered
                    </div>
                    <p id="totalServiceRendered" class="m-0 text-sm font-bold text-gray-700" aria-live="polite"></p>
                </div>
            </div>
        </section>

        <section class="mb-5 rounded-xl border border-gray-200 bg-white p-4 sm:p-6">
            <div class="mb-6 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">B</span>
                <h3 class="text-base font-bold text-gray-900">Reason for Leaving</h3>
            </div>

            <fieldset class="mb-6">
                <legend class="mb-3 text-sm font-semibold text-gray-700">
                    Is your separation voluntary? <span class="text-red-500">*</span>
                    <span class="mt-1 block text-xs font-normal text-gray-500">Kusang-loob ba ang iyong pag-alis sa kumpanya?</span>
                </legend>
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                    <label class="choice-card flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-3 hover:border-blue-400">
                        <input type="radio" name="is_separation_voluntary" value="1" {{ old('is_separation_voluntary') === '1' ? 'checked' : '' }} required class="mt-1 accent-blue-600">
                        <span>
                            <span class="block text-sm font-medium text-gray-900">Yes, I submitted my resignation letter</span>
                        </span>
                    </label>
                    <label class="choice-card flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-3 hover:border-blue-400">
                        <input type="radio" name="is_separation_voluntary" value="0" {{ old('is_separation_voluntary') === '0' ? 'checked' : '' }} required class="mt-1 accent-blue-600">
                        <span>
                            <span class="block text-sm font-medium text-gray-900">No, I received a separation letter from Pascal Resources Energy, Inc.</span>
                        </span>
                    </label>
                </div>
                <p class="validation-message" data-validation-for="is_separation_voluntary">Please select whether your separation is voluntary.</p>
            </fieldset>

            <div id="involuntarySeparationFields" class="mb-6 hidden rounded-xl border border-amber-200 bg-amber-50 p-4">
                <fieldset>
                    <legend class="mb-3 text-sm font-semibold text-gray-700">
                        Choose the type of separation <span class="text-red-500">*</span>
                    </legend>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                        @foreach ($separationTypes as $type)
                            <label class="choice-card flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 bg-white p-3 hover:border-blue-400">
                                <input type="radio" name="separation_type" value="{{ $type }}" {{ old('separation_type') === $type ? 'checked' : '' }} class="mt-1 accent-blue-600">
                                <span class="text-sm text-gray-800">{{ $type }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="validation-message" data-validation-for="separation_type">Please select your type of separation.</p>
                </fieldset>

                <div class="mt-4">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Comment <span class="text-red-500">*</span>
                    </label>
                    <textarea name="separation_comment" rows="3" maxlength="1000" placeholder="Add your comment" class="field resize-none">{{ old('separation_comment') }}</textarea>
                    <p class="validation-message" data-validation-for="separation_comment">Please add a comment about your separation.</p>
                </div>
            </div>

            <div id="voluntarySeparationFields" class="hidden">
                <div class="mb-6">
                    <p class="mb-3 text-sm font-semibold text-gray-700">
                        Is your reason for leaving a new job opportunity? <span class="text-red-500">*</span>
                        <span class="mt-1 block text-xs font-normal text-gray-500">Ang dahilan ba ng iyong pag-alis ay dahil sa bagong oportunidad sa trabaho?</span>
                    </p>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                        @foreach (['1' => 'Yes', '0' => 'No'] as $value => $label)
                            <label class="choice-card flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-3 hover:border-blue-400">
                                <input type="radio" name="is_leaving_for_new_job" value="{{ $value }}" {{ old('is_leaving_for_new_job') === $value ? 'checked' : '' }} class="accent-blue-600">
                                <span class="text-sm font-medium text-gray-900">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="validation-message" data-validation-for="is_leaving_for_new_job">Please select whether you are leaving for a new job opportunity.</p>
                </div>

                <div id="newJobOfferInput" class="mb-6 hidden">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        What does your new job have to offer that your current one does not? <span class="text-red-500">*</span>
                        <span class="mt-1 block text-xs font-normal text-gray-500">Ano ang maiaalok ng bago mong trabaho na wala sa kasalukuyan mong trabaho?</span>
                    </label>
                    <input type="text" name="new_job_offer_text" value="{{ old('new_job_offer_text') }}" maxlength="255" placeholder="e.g., higher salary, better benefits, or growth opportunities" class="field">
                    <p class="validation-message" data-validation-for="new_job_offer_text">Please describe what your new job offers.</p>
                </div>

                <fieldset class="mb-6">
                    <legend class="mb-3 text-sm font-semibold text-gray-700">
                        What made you decide to leave? <span class="text-red-500">*</span>
                        <span class="mt-1 block text-xs font-normal text-gray-500">Ano ang nag-udyok sayo na umalis?</span>
                    </legend>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                        @foreach ($leaveReasons as $reason)
                            <label class="choice-card flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-3 hover:border-blue-400">
                                <input type="checkbox" name="reason_of_leave[]" value="{{ $reason }}" {{ in_array($reason, old('reason_of_leave', [])) ? 'checked' : '' }} class="reason-of-leave accent-blue-600">
                                <span class="text-sm text-gray-800">{{ $reason }}</span>
                            </label>
                        @endforeach
                        <label class="choice-card flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-3 hover:border-blue-400">
                            <input type="checkbox" name="reason_of_leave[]" value="Others" id="reasonOthersCheck" {{ in_array('Others', old('reason_of_leave', [])) ? 'checked' : '' }} class="reason-of-leave accent-blue-600">
                            <span class="text-sm text-gray-800">Others</span>
                        </label>
                    </div>
                    <p class="validation-message" data-validation-for="reason_of_leave">Please select at least one reason for leaving.</p>
                    <div id="reasonOthersInput" class="mt-3 hidden">
                        <input type="text" name="reason_of_leave_other" value="{{ old('reason_of_leave_other') }}" maxlength="255" placeholder="Please specify your other reason" class="field">
                        <p class="validation-message" data-validation-for="reason_of_leave_other">Please specify your other reason for leaving.</p>
                    </div>
                </fieldset>
            </div>

            <fieldset>
                <legend class="mb-3 text-sm font-semibold text-gray-700">What types of career opportunities are important to you? <span class="text-red-500">*</span></legend>
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                    @foreach ($careerOptions as $career)
                        <label class="choice-card flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-3 hover:border-blue-400">
                            <input type="checkbox" name="career_type_importance[]" value="{{ $career }}" {{ in_array($career, old('career_type_importance', [])) ? 'checked' : '' }} class="career-importance accent-blue-600">
                            <span class="text-sm text-gray-800">{{ $career }}</span>
                        </label>
                    @endforeach
                    <label class="choice-card flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-3 hover:border-blue-400">
                        <input type="checkbox" name="career_type_importance[]" value="Others" id="careerOthersCheck" {{ in_array('Others', old('career_type_importance', [])) ? 'checked' : '' }} class="career-importance accent-blue-600">
                        <span class="text-sm text-gray-800">Others</span>
                    </label>
                </div>
                <p class="validation-message" data-validation-for="career_type_importance">Please select at least one career opportunity.</p>
                <div id="careerOthersInput" class="mt-3 hidden">
                    <input type="text" name="career_type_importance_other" value="{{ old('career_type_importance_other') }}" maxlength="255" placeholder="Please specify the other opportunity" class="field">
                    <p class="validation-message" data-validation-for="career_type_importance_other">Please specify the other career opportunity.</p>
                </div>
            </fieldset>
        </section>

        <section class="mb-5 rounded-xl border border-gray-200 bg-white p-4 sm:p-6">
            <div class="mb-2 flex items-center gap-3">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">C</span>
                <h3 class="text-base font-bold text-gray-900">Rate Your Experience</h3>
            </div>
            <p class="mb-4 mt-3 text-sm leading-6 text-gray-500 sm:mb-6 sm:ml-11 sm:mt-0">Rate each area from 1 (Very Dissatisfied) to 5 (Very Satisfied).</p>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-5">
                @foreach ($ratings as $field => $label)
                    <fieldset class="rounded-lg bg-gray-50 p-3 sm:rounded-none sm:bg-transparent sm:p-0">
                        <legend class="mb-2 text-sm font-semibold text-gray-700">{{ $label }} <span class="text-red-500">*</span></legend>
                        <div class="rating-group flex w-full max-w-[15rem] items-center justify-between" aria-label="{{ $label }} rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <label class="rating-star" title="{{ $i }} out of 5">
                                    <input type="radio" name="{{ $field }}" value="{{ $i }}" {{ (string) old($field) === (string) $i ? 'checked' : '' }} required class="sr-only">
                                    <span aria-hidden="true">&#9733;</span>
                                    <span class="sr-only">{{ $i }} out of 5</span>
                                </label>
                            @endfor
                        </div>
                    </fieldset>
                @endforeach
            </div>
        </section>

        <section class="mb-5 rounded-xl border border-gray-200 bg-white p-4 sm:p-6">
            <div class="mb-6 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">D</span>
                <h3 class="text-base font-bold text-gray-900">Your Feedback</h3>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">What did you like most about working here? <span class="text-red-500">*</span>
                        <span class="mt-1 block text-xs font-normal text-gray-500">Ano ang pinaka nagustuhan mo dito sa PREI?</span>
                        </label>
                    <textarea name="most_liked_text" rows="3" required placeholder="Tell us what you enjoyed most" class="field resize-none">{{ old('most_liked_text') }}</textarea>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">What did you like least about working here? <span class="text-red-500">*</span>
                <span class="mt-1 block text-xs font-normal text-gray-500">Ano ang pinaka HINDI mo nagustuhan dito sa PREI?</span>
                </label>
                    <textarea name="least_liked_text" rows="3" required placeholder="Tell us what could be improved" class="field resize-none">{{ old('least_liked_text') }}</textarea>
                </div>
            </div>
        </section>

        <section class="mb-5 rounded-xl border border-gray-200 bg-white p-4 sm:p-6">
            <div class="mb-6 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">E</span>
                <h3 class="text-base font-bold text-gray-900">Company Alignment</h3>
            </div>

            <div class="mb-6">
                <p class="mb-3 text-sm font-semibold text-gray-700">Did you have a strong, clear understanding of your department's main goals, direction, and contribution to the company's overall vision? <span class="text-red-500">*</span>
                  <span class="mt-1 block text-xs font-normal text-gray-500">Ikaw ba ay nagkaroon ng malinaw na pagkaintindi sa kung ano ang goal at direction ng iyong Department at kung ano ang contribution nito sa vision ng Kumpanya?</span>           
            </p>
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                    @foreach (['1' => 'Yes', '0' => 'No'] as $value => $label)
                        <label class="choice-card flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-3 hover:border-blue-400">
                            <input type="radio" name="understands_company_goals" value="{{ $value }}" {{ old('understands_company_goals') === $value ? 'checked' : '' }} required class="accent-blue-600">
                            <span class="text-sm font-medium text-gray-900">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                <div id="goalsExplainInput" class="mt-3 hidden">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">If no, please explain <span class="text-red-500">*</span></label>
                    <input type="text" name="goals_explanation" value="{{ old('goals_explanation') }}" maxlength="255" class="field">
                </div>
            </div>

            <div>
                <p class="mb-3 text-sm font-semibold text-gray-700">Do you believe PREI lives up to its Core Values: Honesty &amp; Integrity, Innovation, Teamwork, Commitment to Serve, Communication and Respect, and Hard Work? <span class="text-red-500">*</span>
             <span class="mt-1 block text-xs font-normal text-gray-500">Ikaw ba ay naniniwala na isinasabuhay ng PREI ang mga nasabing core values?</span> 
            </p>
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                    @foreach (['1' => 'Yes', '0' => 'No'] as $value => $label)
                        <label class="choice-card flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-3 hover:border-blue-400">
                            <input type="radio" name="company_lives_core_values" value="{{ $value }}" {{ old('company_lives_core_values') === $value ? 'checked' : '' }} required class="accent-blue-600">
                            <span class="text-sm font-medium text-gray-900">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                <div id="coreValuesExplainInput" class="mt-3 hidden">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">If no, please explain <span class="text-red-500">*</span></label>
                    <input type="text" name="core_values_explanation" value="{{ old('core_values_explanation') }}" maxlength="255" class="field">
                </div>
            </div>
        </section>

        <section class="mb-5 rounded-xl border border-gray-200 bg-white p-4 sm:p-6">
            <div class="mb-6 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">F</span>
                <h3 class="text-base font-bold text-gray-900">Performance Feedback &amp; Suggestions</h3>
            </div>


                <span class="mt-1 block text-xs font-normal text-gray-500"></span>
                <span class="mt-1 block text-xs font-normal text-gray-500"></span>

            <div class="mb-5">
                <label class="mb-2 block text-sm font-semibold text-gray-700">How frequently did you receive performance feedback? <span class="text-red-500">*</span>
                <span class="mt-1 block text-xs font-normal text-gray-500">Gaano kadalas kayo nag-uusap ng Immediate Superior para sa performance result?</span>
            </label>
                <select name="frequently_performance_feedback" required class="field">
                    <option value="">Select frequency</option>
                    @foreach (['Frequently - Madalas', 'Occasionally - Minsan', 'Rarely - Bihira', 'Never - Hindi Kahit Minsan'] as $frequency)
                        <option value="{{ $frequency }}" {{ old('frequently_performance_feedback') === $frequency ? 'selected' : '' }}>{{ $frequency }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-5">
                <p class="mb-3 text-sm font-semibold text-gray-700">Any changes you would like to suggest? 
                <!-- <span class="font-normal text-gray-400">(optional)</span> -->
                <span class="mt-1 block text-xs font-normal text-gray-500">Magbigay ng mungkahi patungkol sa mga sumusunod:</span>

            </p>
                <div class="grid grid-cols-1 gap-3">
                    <textarea name="suggestion_company" rows="3" placeholder="Recommendations for the company" class="field resize-none">{{ old('suggestion_company') }}</textarea>
                    <textarea name="suggestion_department" rows="3" placeholder="Recommendations for your department" class="field resize-none">{{ old('suggestion_department') }}</textarea>
                    <textarea name="suggestion_other_employees" rows="3" placeholder="Recommendations for employees" class="field resize-none">{{ old('suggestion_other_employees') }}</textarea>
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Any other feedback you'd like to give us? 
                <!-- <span class="font-normal text-gray-400">(optional)</span> -->
                </label>
                <textarea name="other_feedback_text" rows="3" placeholder="Share any additional feedback" class="field resize-none">{{ old('other_feedback_text') }}</textarea>
            </div>
        </section>

        <div class="flex justify-end">
            <button type="submit" id="submitButton" class="w-full rounded-lg bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 sm:w-auto">
                Submit Exit Interview
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('exitInterviewForm');

        function addCalendarYears(date, years) {
            var targetYear = date.getFullYear() + years;
            var targetMonth = date.getMonth();
            var lastDay = new Date(targetYear, targetMonth + 1, 0).getDate();

            return new Date(targetYear, targetMonth, Math.min(date.getDate(), lastDay));
        }

        function addCalendarMonths(date, months) {
            var targetMonthIndex = date.getMonth() + months;
            var targetYear = date.getFullYear() + Math.floor(targetMonthIndex / 12);
            var targetMonth = targetMonthIndex % 12;
            var lastDay = new Date(targetYear, targetMonth + 1, 0).getDate();

            return new Date(targetYear, targetMonth, Math.min(date.getDate(), lastDay));
        }

        function updateTotalServiceRendered() {
            var hireDateField = form.querySelector('[name="hire_date"]');
            var separationDateField = form.querySelector('[name="separation_date"]');
            var totalServiceSummary = document.getElementById('totalServiceSummary');
            var totalService = document.getElementById('totalServiceRendered');

            if (!hireDateField.value || !separationDateField.value) {
                totalServiceSummary.classList.add('hidden');
                totalService.textContent = '';
                return;
            }

            totalServiceSummary.classList.remove('hidden');

            var hireDate = new Date(hireDateField.value + 'T00:00:00');
            var separationDate = new Date(separationDateField.value + 'T00:00:00');

            if (separationDate < hireDate) {
                totalService.classList.remove('text-gray-700');
                totalService.classList.add('text-red-600');
                totalService.textContent = 'Separation date must be after hire date';
                return;
            }

            var years = separationDate.getFullYear() - hireDate.getFullYear();

            if (addCalendarYears(hireDate, years) > separationDate) {
                years--;
            }

            var yearCursor = addCalendarYears(hireDate, years);
            var months = 0;

            while (months < 11 && addCalendarMonths(yearCursor, months + 1) <= separationDate) {
                months++;
            }

            var monthCursor = addCalendarMonths(yearCursor, months);
            var days = Math.floor((separationDate - monthCursor) / (1000 * 60 * 60 * 24));
            var durationParts = [];

            if (years > 0) {
                durationParts.push(years + (years === 1 ? ' year' : ' years'));
            }

            if (months > 0) {
                durationParts.push(months + (months === 1 ? ' month' : ' months'));
            }

            durationParts.push(days + (days === 1 ? ' day' : ' days'));

            totalService.classList.remove('text-red-600');
            totalService.classList.add('text-gray-700');
            totalService.textContent = durationParts.join(', ');
        }

        form.querySelectorAll('[name="hire_date"], [name="separation_date"]').forEach(function(field) {
            field.addEventListener('input', updateTotalServiceRendered);
            field.addEventListener('change', updateTotalServiceRendered);
        });
        updateTotalServiceRendered();

        function toggleField(controller, wrapper, input, showWhen) {
            var shouldShow = controller ? controller.checked && controller.value === showWhen : false;
            wrapper.classList.toggle('hidden', !shouldShow);
            input.required = shouldShow;
            if (!shouldShow) {
                input.value = '';
                clearValidation(input.name);
            }
        }

        function selectedRadio(name) {
            return document.querySelector('input[name="' + name + '"]:checked');
        }

        function setupRadioToggle(name, wrapperId, inputName, showWhen) {
            var wrapper = document.getElementById(wrapperId);
            var input = document.querySelector('[name="' + inputName + '"]');
            var update = function() {
                toggleField(selectedRadio(name), wrapper, input, showWhen);
            };

            document.querySelectorAll('input[name="' + name + '"]').forEach(function(radio) {
                radio.addEventListener('change', update);
            });
            update();
        }

        function setupCheckboxToggle(checkboxId, wrapperId, inputName) {
            var checkbox = document.getElementById(checkboxId);
            var wrapper = document.getElementById(wrapperId);
            var input = document.querySelector('[name="' + inputName + '"]');
            var update = function() {
                wrapper.classList.toggle('hidden', !checkbox.checked);
                input.required = checkbox.checked;
                if (!checkbox.checked) {
                    input.value = '';
                    clearValidation(input.name);
                }
            };

            checkbox.addEventListener('change', update);
            update();
        }

        setupRadioToggle('is_leaving_for_new_job', 'newJobOfferInput', 'new_job_offer_text', '1');
        setupRadioToggle('understands_company_goals', 'goalsExplainInput', 'goals_explanation', '0');
        setupRadioToggle('company_lives_core_values', 'coreValuesExplainInput', 'core_values_explanation', '0');
        setupCheckboxToggle('reasonOthersCheck', 'reasonOthersInput', 'reason_of_leave_other');
        setupCheckboxToggle('careerOthersCheck', 'careerOthersInput', 'career_type_importance_other');

        var voluntaryFields = document.getElementById('voluntarySeparationFields');
        var involuntaryFields = document.getElementById('involuntarySeparationFields');

        function setFieldsEnabled(wrapper, enabled) {
            wrapper.querySelectorAll('input, select, textarea').forEach(function(field) {
                field.disabled = !enabled;
                if (!enabled && field.name) clearValidation(field.name);
            });
        }

        function updateSeparationFields() {
            var selected = selectedRadio('is_separation_voluntary');
            var isVoluntary = selected && selected.value === '1';
            var isInvoluntary = selected && selected.value === '0';

            voluntaryFields.classList.toggle('hidden', !isVoluntary);
            involuntaryFields.classList.toggle('hidden', !isInvoluntary);
            setFieldsEnabled(voluntaryFields, isVoluntary);
            setFieldsEnabled(involuntaryFields, isInvoluntary);

            document.querySelectorAll('input[name="is_leaving_for_new_job"]').forEach(function(field) {
                field.required = isVoluntary;
            });

            document.querySelectorAll('input[name="separation_type"]').forEach(function(field) {
                field.required = isInvoluntary;
            });

            document.querySelector('[name="separation_comment"]').required = isInvoluntary;
        }

        document.querySelectorAll('input[name="is_separation_voluntary"]').forEach(function(radio) {
            radio.addEventListener('change', updateSeparationFields);
        });
        updateSeparationFields();

        function fillStars(group, rating) {
            group.querySelectorAll('.rating-star').forEach(function(star) {
                var value = parseInt(star.querySelector('input').value, 10);
                star.classList.toggle('is-active', value <= rating);
            });
        }

        function restoreStars(group) {
            var selected = group.querySelector('input:checked');
            fillStars(group, selected ? parseInt(selected.value, 10) : 0);
        }

        document.querySelectorAll('.rating-group').forEach(function(group) {
            restoreStars(group);

            group.querySelectorAll('.rating-star').forEach(function(star) {
                star.addEventListener('mouseenter', function() {
                    fillStars(group, parseInt(star.querySelector('input').value, 10));
                });

                star.querySelector('input').addEventListener('change', function() {
                    restoreStars(group);
                });
            });

            group.addEventListener('mouseleave', function() {
                restoreStars(group);
            });
        });

        function namedFields(name) {
            var normalizedName = name.replace('[]', '');
            return Array.from(form.elements).filter(function(field) {
                return field.name && field.name.replace('[]', '') === normalizedName;
            });
        }

        function validationMessage(name, fallback, anchor) {
            var message = form.querySelector('[data-validation-for="' + name + '"]');

            if (!message) {
                message = document.createElement('p');
                message.className = 'validation-message';
                message.setAttribute('data-validation-for', name);
                anchor.insertAdjacentElement('afterend', message);
            }

            message.textContent = fallback;
            message.setAttribute('role', 'alert');
            message.classList.add('is-visible');
        }

        function clearValidation(name) {
            namedFields(name).forEach(function(field) {
                field.classList.remove('is-invalid');
                field.removeAttribute('aria-invalid');

                var card = field.closest('.choice-card');
                if (card) card.classList.remove('is-invalid');

                var ratingGroup = field.closest('.rating-group');
                if (ratingGroup) ratingGroup.classList.remove('is-invalid');
            });

            var message = form.querySelector('[data-validation-for="' + name + '"]');
            if (message) message.classList.remove('is-visible');
        }

        function clearAllValidation() {
            form.querySelectorAll('.is-invalid').forEach(function(element) {
                element.classList.remove('is-invalid');
            });

            form.querySelectorAll('[aria-invalid="true"]').forEach(function(field) {
                field.removeAttribute('aria-invalid');
            });

            form.querySelectorAll('.validation-message').forEach(function(message) {
                message.classList.remove('is-visible');
            });
        }

        function messageFor(field) {
            var messages = {
                is_separation_voluntary: 'Please select whether your separation is voluntary.',
                separation_type: 'Please select your type of separation.',
                separation_comment: 'Please add a comment about your separation.',
                is_leaving_for_new_job: 'Please select whether you are leaving for a new job opportunity.',
                new_job_offer_text: 'Please describe what your new job offers.',
                reason_of_leave_other: 'Please specify your other reason for leaving.',
                career_type_importance_other: 'Please specify the other career opportunity.',
                employee_number: 'Please enter your employee number.',
                first_name: 'Please enter your first name.',
                last_name: 'Please enter your last name.',
                email: 'Please enter your email address.',
                job_title: 'Please enter your job title.',
                department: 'Please enter your department.',
                direct_supervisor_name: 'Please enter your direct supervisor’s name.',
                work_location: 'Please enter your work location.',
                hire_date: 'Please enter your hire date.',
                separation_date: 'Please enter your separation date.',
                most_liked_text: 'Please tell us what you liked most.',
                least_liked_text: 'Please tell us what could be improved.',
                frequently_performance_feedback: 'Please select how often you received performance feedback.',
                understands_company_goals: 'Please select an answer.',
                goals_explanation: 'Please explain your answer.',
                company_lives_core_values: 'Please select an answer.',
                core_values_explanation: 'Please explain your answer.'
            };

            if (field.type === 'email' && field.validity.typeMismatch) return 'Please enter a valid email address.';
            if (field.name === 'hire_date' && field.validity.rangeOverflow) return 'Hire date cannot be in the future.';
            if (field.name === 'separation_date' && field.validity.rangeUnderflow) return 'Separation date cannot be in the past.';
            if (messages[field.name]) return messages[field.name];
            if (field.type === 'radio' && field.name.indexOf('rating_') === 0) return 'Please provide a rating.';
            if (field.type === 'radio') return 'Please select an option.';
            if (field.tagName === 'SELECT') return 'Please select an option.';
            return 'Please complete this field.';
        }

        function markRequiredField(field) {
            var anchor = field;
            field.setAttribute('aria-invalid', 'true');

            if (field.type === 'radio') {
                namedFields(field.name).forEach(function(radio) {
                    var card = radio.closest('.choice-card');
                    if (card) card.classList.add('is-invalid');
                });

                var ratingGroup = field.closest('.rating-group');
                if (ratingGroup) {
                    ratingGroup.classList.add('is-invalid');
                    anchor = ratingGroup;
                } else {
                    anchor = field.closest('.grid') || field.closest('fieldset') || field;
                }
            } else {
                field.classList.add('is-invalid');
            }

            validationMessage(field.name, messageFor(field), anchor);
        }

        function markCheckboxGroup(name, messageText) {
            var fields = namedFields(name);
            var checked = fields.some(function(field) {
                return !field.disabled && field.checked;
            });

            if (checked) return null;

            fields.forEach(function(field) {
                field.setAttribute('aria-invalid', 'true');
                var card = field.closest('.choice-card');
                if (card) card.classList.add('is-invalid');
            });

            var anchor = fields[0].closest('.grid') || fields[0].closest('fieldset') || fields[0];
            validationMessage(name.replace('[]', ''), messageText, anchor);
            return fields[0];
        }

        form.addEventListener('input', function(event) {
            if (event.target.name) clearValidation(event.target.name.replace('[]', ''));
        });

        form.addEventListener('change', function(event) {
            if (event.target.name) clearValidation(event.target.name.replace('[]', ''));
        });

        form.addEventListener('submit', function(event) {
            clearAllValidation();

            var firstInvalid = null;
            var handledRadioGroups = {};

            form.querySelectorAll('[required]').forEach(function(field) {
                if (field.disabled || field.checkValidity()) return;
                if (field.type === 'radio' && handledRadioGroups[field.name]) return;

                if (field.type === 'radio') handledRadioGroups[field.name] = true;
                markRequiredField(field);
                if (!firstInvalid) firstInvalid = field;
            });

            var voluntarySeparation = selectedRadio('is_separation_voluntary');

            if (voluntarySeparation && voluntarySeparation.value === '1' && !document.querySelector('.reason-of-leave:checked')) {
                var reasonInvalid = markCheckboxGroup('reason_of_leave[]', 'Please select at least one reason for leaving.');
                if (!firstInvalid) firstInvalid = reasonInvalid;
            }

            if (!document.querySelector('.career-importance:checked')) {
                var careerInvalid = markCheckboxGroup('career_type_importance[]', 'Please select at least one career opportunity.');
                if (!firstInvalid) firstInvalid = careerInvalid;
            }

            if (firstInvalid) {
                event.preventDefault();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                window.setTimeout(function() {
                    firstInvalid.focus({ preventScroll: true });
                }, 350);
                return;
            }

            var submitButton = document.getElementById('submitButton');
            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';
            submitButton.classList.add('opacity-70', 'cursor-not-allowed');
        });
    });
</script>

@include('sweetalert::alert')
