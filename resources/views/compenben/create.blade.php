@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper compenben-page">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10 grid-margin stretch-card">
                <div class="card compenben-card">
                    <div class="card-body p-3 p-md-4 p-xl-5">
                        <div class="compenben-header">
                            <div>
                                <div class="d-inline-block mb-2">
                                    <h4 class="card-title mb-1">Compensation and Benefits</h4>
                                    <div class="d-flex">
                                        <div class="w-100" style="border-top:3px solid #59c2e5;"></div>
                                        <div class="w-100" style="border-top:3px solid #fe0001;"></div>
                                    </div>
                                </div>
                                <p class="compenben-intro mb-0">
                                    Raise a concern or request a form related to compensation and benefits.
                                    HR will review your submission and contact you within 3 to 5 working days.
                                </p>
                            </div>
                            <!-- <span class="compenben-private-note"> -->
                            <!--     <i class="ti-lock mr-1" aria-hidden="true"></i> -->
                            <!--     Confidential submission -->
                            <!-- </span> -->
                        </div>

                        <form method="POST" action="{{ route('compenben.store') }}" id="compenbenForm">
                            @csrf

                            @if ($errors->any())
                                <div class="alert alert-danger compenben-alert" role="alert">
                                    <div class="font-weight-bold mb-1">Please review the highlighted fields.</div>
                                    <ul class="mb-0 pl-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <section class="compenben-section" aria-labelledby="employee-information-heading">
                                <div class="compenben-section-heading">
                                    <span class="compenben-section-icon" aria-hidden="true">
                                        <i class="ti-user"></i>
                                    </span>
                                    <div>
                                        <h5 id="employee-information-heading" class="compenben-section-title">Employee information</h5>
                                        <small class="compenben-section-description">
                                            These details are prefilled from your employee profile. 
                                        </small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="first" class="form-label">First name <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            id="first"
                                            name="first"
                                            value="{{ old('first', $employeeData['first'] ?? '') }}"
                                            maxlength="255"
                                            autocomplete="given-name"
                                            class="form-control {{ $errors->has('first') ? 'is-invalid' : '' }}"
                                            required
                                        >
                                        @if ($errors->has('first'))
                                            <div class="invalid-feedback">{{ $errors->first('first') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="last" class="form-label">Last name <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            id="last"
                                            name="last"
                                            value="{{ old('last', $employeeData['last'] ?? '') }}"
                                            maxlength="255"
                                            autocomplete="family-name"
                                            class="form-control {{ $errors->has('last') ? 'is-invalid' : '' }}"
                                            required
                                        >
                                        @if ($errors->has('last'))
                                            <div class="invalid-feedback">{{ $errors->first('last') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            value="{{ old('email', $employeeData['email'] ?? '') }}"
                                            maxlength="255"
                                            autocomplete="email"
                                            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                            required
                                        >
                                        @if ($errors->has('email'))
                                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="phone" class="form-label">Cellphone number <span class="text-danger">*</span></label>
                                        <input
                                            type="tel"
                                            id="phone"
                                            name="phone"
                                            value="{{ old('phone', $employeeData['phone'] ?? '') }}"
                                            maxlength="11"
                                            inputmode="numeric"
                                            autocomplete="tel"
                                            pattern="09[0-9]{9}"
                                            placeholder="09XXXXXXXXX"
                                            aria-describedby="phoneHelp"
                                            class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                            required
                                        >
                                        <small id="phoneHelp" class="form-text text-muted">Use an 11-digit Philippine mobile number.</small>
                                        @if ($errors->has('phone'))
                                            <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-md-6 form-group mb-md-0">
                                        <label for="designation" class="form-label">Designation / Position <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            id="designation"
                                            name="designation"
                                            value="{{ old('designation', $employeeData['designation'] ?? '') }}"
                                            maxlength="255"
                                            autocomplete="organization-title"
                                            class="form-control {{ $errors->has('designation') ? 'is-invalid' : '' }}"
                                            required
                                        >
                                        @if ($errors->has('designation'))
                                            <div class="invalid-feedback">{{ $errors->first('designation') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-md-6 form-group mb-0">
                                        <label for="work_location" class="form-label">Work location <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            id="work_location"
                                            name="work_location"
                                            value="{{ old('work_location', $employeeData['work_location'] ?? '') }}"
                                            maxlength="255"
                                            placeholder="Hub, Plant, Warehouse, Field, or Head Office"
                                            autocomplete="organization"
                                            class="form-control {{ $errors->has('work_location') ? 'is-invalid' : '' }}"
                                            required
                                        >
                                        @if ($errors->has('work_location'))
                                            <div class="invalid-feedback">{{ $errors->first('work_location') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </section>

                            <section class="compenben-section" aria-labelledby="concern-information-heading">
                                <div class="compenben-section-heading">
                                    <span class="compenben-section-icon" aria-hidden="true">
                                        <i class="ti-help-alt"></i>
                                    </span>
                                    <div>
                                        <h5 id="concern-information-heading" class="compenben-section-title">
                                            Concern
                                            <span class="text-danger">*</span>
                                        </h5>
                                        <small class="compenben-section-description">
                                            Select the category that best matches your concern.
                                        </small>
                                    </div>
                                </div>

                                <fieldset class="form-group">
                                    <div class="row mx-n2">
                                        @foreach ($issueCategories as $index => $category)
                                            <div class="col-md-6 px-2 mb-3">
                                                <label class="compenben-choice {{ (string) old('issue') === (string) $category ? 'is-selected' : '' }}" for="issue_{{ $index }}">
                                                    <input
                                                        type="radio"
                                                        id="issue_{{ $index }}"
                                                        name="issue"
                                                        value="{{ $category }}"
                                                        {{ (string) old('issue') === (string) $category ? 'checked' : '' }}
                                                        required
                                                    >
                                                    <span class="compenben-choice-indicator" aria-hidden="true"></span>
                                                    <span>{{ $category }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if ($errors->has('issue'))
                                        <div class="text-danger small mt-n1">{{ $errors->first('issue') }}</div>
                                    @endif
                                </fieldset>

                                <div
                                    id="otherIssueGroup"
                                    class="form-group {{ old('issue') === 'Others' ? '' : 'd-none' }}"
                                >
                                    <label for="other_issue" class="form-label">
                                        Please specify <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="other_issue"
                                        name="other_issue"
                                        value="{{ old('other_issue') }}"
                                        maxlength="240"
                                        placeholder="Enter the issue or form request category"
                                        class="form-control {{ $errors->has('other_issue') ? 'is-invalid' : '' }}"
                                    >
                                    @if ($errors->has('other_issue'))
                                        <div class="invalid-feedback">{{ $errors->first('other_issue') }}</div>
                                    @endif
                                </div>

                                <div class="form-group mb-0">
                                    <div class="d-flex justify-content-between align-items-end mb-2">
                                        <label for="details" class="form-label mb-0">
                                            Details of issue / Purpose of request <span class="text-danger">*</span>
                                        </label>
                                        <small class="text-muted"><span id="detailsCount">0</span>/500</small>
                                    </div>
                                    <textarea
                                        id="details"
                                        name="details"
                                        rows="6"
                                        maxlength="500"
                                        placeholder="Describe what happened, when it occurred, and any relevant payroll period. For a form request, include its purpose."
                                        aria-describedby="detailsHelp"
                                        class="form-control {{ $errors->has('details') ? 'is-invalid' : '' }}"
                                        style="resize: none;"
                                        required
                                    >{{ old('details') }}</textarea>
                                    @if ($errors->has('details'))
                                        <div class="invalid-feedback">{{ $errors->first('details') }}</div>
                                    @endif
                                </div>
                            </section>

                            <div class="d-flex mt-3 justify-content-end">
                                <button type="submit" class="d-flex align-items-center justify-content-center btn btn-primary compenben-submit-button" id="submitCompenben">
                                    <i class="ti-email mr-2" aria-hidden="true"></i>
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

<style>
    .compenben-page {
        background: #f4f7fb;
    }
    .compenben-card {
        overflow: hidden;
        border: 0;
        border-radius: .9rem;
        box-shadow: 0 8px 28px rgba(23, 43, 77, .08);
    }
    .compenben-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid #e6ebf2;
    }
    .compenben-intro {
        max-width: 720px;
        color: #667085;
        line-height: 1.55;
    }
    .compenben-private-note {
        flex: 0 0 auto;
        padding: .45rem .7rem;
        color: #31547e;
        background: #eef6ff;
        border: 1px solid #cfe2f8;
        border-radius: 2rem;
        font-size: .78rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .compenben-alert {
        margin-top: 1rem;
        border: 0;
        border-radius: .65rem;
    }
    .compenben-required-note {
        margin: 1rem 0 0;
        color: #667085;
        font-size: .82rem;
        text-align: right;
    }
    .compenben-section {
        margin-top: 1.25rem;
        padding: 1.25rem;
        background: #fff;
        border: 1px solid #e3e9f1;
        border-radius: .75rem;
    }
    .compenben-section-heading {
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #edf0f5;
    }
    .compenben-section-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 38px;
        width: 38px;
        height: 38px;
        color: #2468b3;
        background: #eaf3ff;
        border-radius: .55rem;
    }
    .compenben-section-title {
        margin: 0;
        color: #17233c;
        font-size: 1.05rem;
        font-weight: 700;
    }
    .compenben-section-description {
        display: block;
        margin-top: .2rem;
        color: #7a8699;
    }
    #compenbenForm .form-label {
        color: #27364f;
        font-weight: 600;
    }
    #compenbenForm .form-control {
        min-height: 44px;
        border-color: #ccd5e2;
        border-radius: .45rem;
    }
    #compenbenForm textarea.form-control {
        min-height: 140px;
        resize: vertical;
    }
    #compenbenForm .form-control:focus {
        border-color: #4b8ee8;
        box-shadow: 0 0 0 .15rem rgba(75, 142, 232, .15);
    }
    .compenben-choice {
        position: relative;
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        min-height: 54px;
        height: 100%;
        margin: 0;
        padding: .85rem .9rem;
        color: #334155;
        background: #fff;
        border: 1px solid #d9e1ec;
        border-radius: .6rem;
        cursor: pointer;
        line-height: 1.4;
        transition: border-color .15s ease, background-color .15s ease, box-shadow .15s ease;
    }
    .compenben-choice:hover {
        border-color: #8eb8e8;
        background: #f8fbff;
    }
    .compenben-choice:focus-within {
        border-color: #4b8ee8;
        box-shadow: 0 0 0 .15rem rgba(75, 142, 232, .15);
    }
    .compenben-choice.is-selected {
        color: #1d4f91;
        background: #eef6ff;
        border-color: #4b8ee8;
    }
    .compenben-choice input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
    }
    .compenben-choice-indicator {
        flex: 0 0 18px;
        width: 18px;
        height: 18px;
        margin-top: .05rem;
        background: #fff;
        border: 2px solid #aab7c7;
        border-radius: 50%;
        box-shadow: inset 0 0 0 4px #fff;
    }
    .compenben-choice input:checked + .compenben-choice-indicator {
        background: #3277c8;
        border-color: #3277c8;
    }
    .compenben-submit-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 1.25rem;
        padding-top: 1.25rem;
        border-top: 1px solid #e6ebf2;
    }
    .compenben-submit-button {
        border-radius: .5rem;
        font-weight: 600;
    }

    @media (max-width: 767.98px) {
        .compenben-header,
        .compenben-submit-row {
            align-items: stretch;
            flex-direction: column;
        }
        .compenben-private-note {
            align-self: flex-start;
        }
        .compenben-submit-button {
            width: 100%;
        }
    }
</style>

<script>
    (function () {
        var form = document.getElementById('compenbenForm');
        var details = document.getElementById('details');
        var detailsCount = document.getElementById('detailsCount');
        var submitButton = document.getElementById('submitCompenben');
        var choices = document.querySelectorAll('.compenben-choice input[type="radio"]');
        var otherIssueGroup = document.getElementById('otherIssueGroup');
        var otherIssue = document.getElementById('other_issue');

        function updateDetailsCount() {
            detailsCount.textContent = details.value.length;
        }

        function updateChoices() {
            var otherSelected = false;

            Array.prototype.forEach.call(choices, function (choice) {
                choice.parentNode.classList.toggle('is-selected', choice.checked);

                if (choice.checked && choice.value === 'Others') {
                    otherSelected = true;
                }
            });

            otherIssueGroup.classList.toggle('d-none', !otherSelected);
            otherIssue.disabled = !otherSelected;
            otherIssue.required = otherSelected;
        }

        details.addEventListener('input', updateDetailsCount);
        Array.prototype.forEach.call(choices, function (choice) {
            choice.addEventListener('change', updateChoices);
        });

        form.addEventListener('submit', function () {
            if (!form.checkValidity()) {
                return;
            }

            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fa fa-spinner fa-spin mr-2" aria-hidden="true"></i>Submitting...';
        });

        updateDetailsCount();
        updateChoices();
    }());
</script>
@endsection
