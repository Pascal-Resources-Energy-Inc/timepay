@php
    $fullName = trim($compenben->first . ' ' . $compenben->last);
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New CompenBen Issue Submission</title>
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
                                    <td class="content-pad" style="padding:24px 24px 5px; font-family:Arial, sans-serif; font-size:17px; color:#111827;">
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
                                        Compensation and Benefits
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
                                        A new Compensation and Benefits concern has been submitted. Please review the information below.
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="content-pad" style="padding:20px 24px 0; font-family:Arial, sans-serif;">
                                        <div class="section-title" style="margin-bottom:8px;">Submission details</div>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:13px;">
                                            <tr><td class="label">Reference number</td><td class="value"><strong>{{ $compenben->reference_no }}</strong></td></tr>
                                            <tr><td class="label">Submitted</td><td class="value">{{ optional($compenben->created_at)->format('F d, Y, h:i A') ?: '—' }}</td></tr>
                                            <tr><td class="label">Issue category</td><td class="value"><strong>{{ $compenben->issue }}</strong></td></tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="content-pad" style="padding:20px 24px 0; font-family:Arial, sans-serif;">
                                        <div class="section-title" style="margin-bottom:8px;">Employee information</div>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:13px;">
                                            <tr><td class="label">Employee code</td><td class="value">{{ $compenben->employee_code }}</td></tr>
                                            <tr><td class="label">Name</td><td class="value">{{ $fullName }}</td></tr>
                                            <tr><td class="label">Email</td><td class="value"><a href="mailto:{{ $compenben->email }}" style="color:#1d4ed8;">{{ $compenben->email }}</a></td></tr>
                                            <tr><td class="label">Cellphone number</td><td class="value">{{ $compenben->phone }}</td></tr>
                                            <tr><td class="label">Designation / Position</td><td class="value">{{ $compenben->designation }}</td></tr>
                                            <tr><td class="label">Work location</td><td class="value">{{ $compenben->work_location }}</td></tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="content-pad" style="padding:20px 24px 22px; font-family:Arial, sans-serif;">
                                        <div class="section-title" style="margin-bottom:8px;">Details of issue / purpose of request</div>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:13px;">
                                            <tr><td class="value">{!! nl2br(e($compenben->details)) !!}</td></tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="content-pad" style="padding:0 24px 24px; font-family:Arial, sans-serif; font-size:14px;">
                                        <a href="{{ route('compenben.show', $compenben->id) }}" style="color:#3f6fa8;">View this request</a>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #e5e7eb;">
                                <tr>
                                    <td class="content-pad" style="padding:16px 24px 22px; font-family:Arial, sans-serif; font-size:12px; line-height:1.6; color:#94a3b8; text-align:center;">
                                        This is an automated email notification from the system. Please do not reply to this email.
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
