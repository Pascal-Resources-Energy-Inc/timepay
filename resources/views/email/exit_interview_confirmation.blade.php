@php
    $fullName = trim($exitInterview->first_name . ' ' . $exitInterview->last_name);
    $hireDate = \Carbon\Carbon::parse($exitInterview->hire_date)->startOfDay();
    $separationDate = \Carbon\Carbon::parse($exitInterview->separation_date)->startOfDay();
    $serviceRendered = 'Invalid date range';

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

    $ratings = [
        'rating_job_responsibilities' => 'Job responsibilities',
        'rating_personal_goals' => 'Opportunities for achieving personal goals',
        'rating_work_environment' => 'Work environment',
        'rating_immediate_superior' => 'Immediate superior',
        'rating_salary' => 'Salary',
        'rating_benefits' => 'Benefits',
    ];
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exit Interview Submission</title>
    <style>
        body { margin: 0; padding: 0; background: #f3f4f6; }
        table { border-collapse: collapse; }
        .container { width: 100%; max-width: 700px; }
        .card { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; }
        .section-title { color: #1e3a8a; font-size: 14px; font-weight: 700; }
        .label { width: 38%; padding: 9px 12px; color: #64748b; background: #f8fafc; border: 1px solid #e5e7eb; font-weight: 600; vertical-align: top; }
        .value { padding: 9px 12px; color: #1f2937; border: 1px solid #e5e7eb; vertical-align: top; word-break: break-word; }

        @media only screen and (max-width: 520px) {
            .outer-pad { padding: 8px 4px !important; }
            .content-pad { padding-right: 14px !important; padding-left: 14px !important; }
            .label, .value { padding: 8px !important; font-size: 12px !important; }
        }
    </style>
</head>
<body>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f3f4f6">
        <tr>
            <td align="center" class="outer-pad" style="padding:16px 8px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="container">
                    <tr>
                        <td class="card">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-bottom:1px solid #e5e7eb;">
                                <tr>
                                    <td class="content-pad" style="padding:24px 24px 5px; font-family:Arial, sans-serif; font-size:17px; font-weight:700; color:#111827;">
                                        PASCAL RESOURCES ENERGY INC.
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-pad" style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td bgcolor="#59c2e5" width="50%" height="6" style="font-size:0; line-height:6px;">&nbsp;</td>
                                                <td bgcolor="#fe0001" width="50%" height="6" style="font-size:0; line-height:6px;">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-pad" style="padding:8px 24px 14px; font-family:Arial, sans-serif; font-size:13px; color:#64748b;">
                                        Exit Interview Submission Confirmation
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="content-pad" style="padding:22px 24px 0; font-family:Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">
                                        Good day,
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-pad" style="padding:10px 24px 0; font-family:Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">
                                        Your exit interview has been submitted successfully. Below is a copy of the answers you provided for your reference.
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="content-pad" style="padding:20px 24px 0; font-family:Arial, sans-serif;">
                                        <div class="section-title" style="margin-bottom:8px;">Submission details</div>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:13px;">
                                            <tr><td class="label">Reference number</td><td class="value"><strong>{{ $exitInterview->reference_no }}</strong></td></tr>
                                            <tr><td class="label">Submitted</td><td class="value">{{ optional($exitInterview->submitted_at)->format('F d, Y, h:i A') ?: '—' }}</td></tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="content-pad" style="padding:20px 24px 0; font-family:Arial, sans-serif;">
                                        <div class="section-title" style="margin-bottom:8px;">Personal and employment information</div>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:13px;">
                                            <tr><td class="label">Employee number</td><td class="value">{{ $exitInterview->employee_number ?: 'Not provided' }}</td></tr>
                                            <tr><td class="label">Name</td><td class="value">{{ $fullName }}</td></tr>
                                            <tr><td class="label">Email</td><td class="value">{{ $exitInterview->email }}</td></tr>
                                            <tr><td class="label">Job title</td><td class="value">{{ $exitInterview->job_title }}</td></tr>
                                            <tr><td class="label">Department</td><td class="value">{{ $exitInterview->department }}</td></tr>
                                            <tr><td class="label">Direct supervisor</td><td class="value">{{ $exitInterview->direct_supervisor_name }}</td></tr>
                                            <tr><td class="label">Work location</td><td class="value">{{ $exitInterview->work_location }}</td></tr>
                                            <tr><td class="label">Hire date</td><td class="value">{{ $hireDate->format('F d, Y') }}</td></tr>
                                            <tr><td class="label">Separation date</td><td class="value">{{ $separationDate->format('F d, Y') }}</td></tr>
                                            <tr><td class="label">Total service rendered</td><td class="value"><strong>{{ $serviceRendered }}</strong></td></tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="content-pad" style="padding:20px 24px 0; font-family:Arial, sans-serif;">
                                        <div class="section-title" style="margin-bottom:8px;">Reason for leaving</div>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:13px;">
                                            <tr>
                                                <td class="label">Voluntary separation</td>
                                                <td class="value">{{ is_null($exitInterview->is_separation_voluntary) ? 'Not specified' : ($exitInterview->is_separation_voluntary ? 'Yes' : 'No') }}</td>
                                            </tr>
                                            @if($exitInterview->is_separation_voluntary)
                                                <tr><td class="label">Leaving for a new job</td><td class="value">{{ $exitInterview->is_leaving_for_new_job ? 'Yes' : 'No' }}</td></tr>
                                                <tr><td class="label">Reasons for leaving</td><td class="value">{{ $exitInterview->reason_of_leave ?: 'Not provided' }}</td></tr>
                                                @if($exitInterview->is_leaving_for_new_job)
                                                    <tr><td class="label">What the new job offers</td><td class="value">{!! nl2br(e($exitInterview->new_job_offer_text ?: 'Not provided')) !!}</td></tr>
                                                @endif
                                            @else
                                                <tr><td class="label">Type of separation</td><td class="value">{{ $exitInterview->separation_type ?: 'Not provided' }}</td></tr>
                                                <tr><td class="label">Separation comment</td><td class="value">{!! nl2br(e($exitInterview->separation_comment ?: 'Not provided')) !!}</td></tr>
                                            @endif
                                            <tr><td class="label">Important career opportunities</td><td class="value">{{ $exitInterview->career_type_importance ?: 'Not provided' }}</td></tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="content-pad" style="padding:20px 24px 0; font-family:Arial, sans-serif;">
                                        <div class="section-title" style="margin-bottom:8px;">Ratings</div>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:13px;">
                                            @foreach($ratings as $field => $label)
                                                <tr><td class="label">{{ $label }}</td><td class="value"><strong>{{ $exitInterview->$field }}/5</strong></td></tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="content-pad" style="padding:20px 24px 0; font-family:Arial, sans-serif;">
                                        <div class="section-title" style="margin-bottom:8px;">Experience and feedback</div>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:13px;">
                                            <tr><td class="label">Most liked</td><td class="value">{!! nl2br(e($exitInterview->most_liked_text)) !!}</td></tr>
                                            <tr><td class="label">Least liked</td><td class="value">{!! nl2br(e($exitInterview->least_liked_text)) !!}</td></tr>
                                            <tr><td class="label">Understands company goals</td><td class="value">{{ $exitInterview->understands_company_goals ? 'Yes' : 'No' }}</td></tr>
                                            @if(!$exitInterview->understands_company_goals && $exitInterview->goals_explanation)
                                                <tr><td class="label">Goals explanation</td><td class="value">{!! nl2br(e($exitInterview->goals_explanation)) !!}</td></tr>
                                            @endif
                                            <tr><td class="label">Company lives its core values</td><td class="value">{{ $exitInterview->company_lives_core_values ? 'Yes' : 'No' }}</td></tr>
                                            @if(!$exitInterview->company_lives_core_values && $exitInterview->core_values_explanation)
                                                <tr><td class="label">Core values explanation</td><td class="value">{!! nl2br(e($exitInterview->core_values_explanation)) !!}</td></tr>
                                            @endif
                                            <tr><td class="label">Performance feedback frequency</td><td class="value">{{ $exitInterview->frequently_performance_feedback }}</td></tr>
                                            <tr><td class="label">Suggestion for the company</td><td class="value">{!! nl2br(e($exitInterview->suggestion_company ?: 'Not provided')) !!}</td></tr>
                                            <tr><td class="label">Suggestion for the department</td><td class="value">{!! nl2br(e($exitInterview->suggestion_department ?: 'Not provided')) !!}</td></tr>
                                            <tr><td class="label">Suggestion for other employees</td><td class="value">{!! nl2br(e($exitInterview->suggestion_other_employees ?: 'Not provided')) !!}</td></tr>
                                            <tr><td class="label">Other feedback</td><td class="value">{!! nl2br(e($exitInterview->other_feedback_text ?: 'Not provided')) !!}</td></tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="content-pad" style="padding:22px 24px; font-family:Arial, sans-serif; font-size:13px; line-height:1.6; color:#475569;">
                                        Thank you for sharing your feedback. Your responses have been received and will be handled confidentially.
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #e5e7eb;">
                                <tr>
                                    <td class="content-pad" style="padding:16px 24px 22px; font-family:Arial, sans-serif; font-size:12px; line-height:1.6; color:#94a3b8; text-align:center;">
                                        <!-- This is an automated confirmation from the HERA system. Please do not reply to this email. -->
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
