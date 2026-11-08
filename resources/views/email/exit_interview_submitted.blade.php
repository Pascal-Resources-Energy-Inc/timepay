<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exit Interview Submitted</title>

    <style>
        body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #f3f4f6 !important;
        }

        table {
            border-collapse: collapse;
        }

        .container {
            width: 100% !important;
            max-width: 680px !important;
            -webkit-text-size-adjust: 100%;
        }

        .card {
            background-color: #ffffff;
            border: 1px solid #e6e6e6;
            border-radius: 8px;
        }

        @media only screen and (max-width: 480px) {
            .outer-pad {
                padding: 8px 4px !important;
            }

            .card {
                border-right: none !important;
                border-left: none !important;
                border-radius: 0 !important;
            }

            .content-pad {
                padding-right: 14px !important;
                padding-left: 14px !important;
            }

            .detail-label,
            .detail-value {
                padding: 8px 10px !important;
                font-size: 12px !important;
            }
        }
    </style>
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f3f4f6">
        <tr>
            <td align="center" class="outer-pad" style="padding:16px 8px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="container" style="max-width:680px; width:100%;">
                    <tr>
                        <td class="card" style="background-color:#ffffff; border:1px solid #e6e6e6; border-radius:8px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-bottom:1px solid #efefef;">
                                <tr>
                                    <td class="content-pad" style="padding:24px 24px 5px 24px; font-family:Roboto, -apple-system, 'Segoe UI', Arial, sans-serif; font-size:17px; font-weight:500; color:#111111;">
                                        PASCAL RESOURCES ENERGY INC.
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-pad" style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td bgcolor="#59c2e5" width="50%" height="6" style="width:50%; height:6px; line-height:6px; font-size:0;">&nbsp;</td>
                                                <td bgcolor="#fe0001" width="50%" height="6" style="width:50%; height:6px; line-height:6px; font-size:0;">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-pad" style="padding:7px 24px 12px 24px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#666666;">
                                        Exit Interview Response
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="content-pad" style="padding:22px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">
                                        Good day,
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-pad" style="padding:12px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">
                                        An employee has submitted an exit interview through HERA. The response is now available for your review.
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="content-pad" style="padding:20px 24px 0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e5e7eb;">
                                            <tr>
                                                <td class="detail-label" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb;">Employee</td>
                                                <td class="detail-value" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $employee ? trim($employee->first_name . ' ' . $employee->last_name) : trim($exitInterview->first_name . ' ' . $exitInterview->last_name) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="detail-label" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb;">Employee Number</td>
                                                <td class="detail-value" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $employee ? $employee->employee_number : ($exitInterview->employee_number ?: 'Not provided') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="detail-label" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb;">Reference Number</td>
                                                <td class="detail-value" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $exitInterview->reference_no }}</td>
                                            </tr>
                                            <tr>
                                                <td class="detail-label" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; border-right:1px solid #e5e7eb;">Submitted</td>
                                                <td class="detail-value" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; word-break:break-word;">{{ optional($exitInterview->submitted_at)->format('F j, Y g:i A') ?: '-' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" class="content-pad" style="padding:24px 24px 26px 24px;">
                                        <a href="{{ $reviewUrl }}" target="_blank" style="display:inline-block; padding:11px 22px; background-color:#2563eb; color:#ffffff; font-family:Helvetica, Arial, sans-serif; font-size:14px; font-weight:600; text-decoration:none; border-radius:5px;">Review Exit Interview</a>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #efefef;">
                                <tr>
                                    <td class="content-pad" style="padding:16px 24px 24px 24px; font-family:Helvetica, Arial, sans-serif; font-size:12px; line-height:1.6; color:#888888; text-align:center;">
                                        This is an automated notification from the HERA system. Please do not reply to this email.
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
