<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Employment Request</title>

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
            .card {
                border-radius: 0 !important;
                border-left: none !important;
                border-right: none !important;
            }

            .outer-pad {
                padding: 8px 4px !important;
            }
        }
    </style>
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f3f4f6" style="width:100%; background-color:#f3f4f6;">
        <tr>
            <td align="center" class="outer-pad" style="padding:16px 8px;">
                <!--[if mso]>
                <table role="presentation" width="680" cellpadding="0" cellspacing="0" border="0" align="center"><tr><td>
                <![endif]-->

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="container" style="width:100%; max-width:680px;">
                    <tr>
                        <td class="card" style="background-color:#ffffff; border:1px solid #e6e6e6; border-radius:8px;">

                            <!-- HEADER -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%; border-bottom:1px solid #efefef;">
                                <tr>
                                    <td style="padding:24px 24px 5px 24px; font-family:Roboto, -apple-system, 'Segoe UI', Arial, sans-serif; font-size:17px; font-weight: 500; color:#111111;">PASCAL RESOURCES ENERGY INC.</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                            <tr>
                                                <td bgcolor="#59c2e5" width="50%" height="6" style="width:50%; height:6px; line-height:6px; font-size:0; mso-line-height-rule:exactly;">&nbsp;</td>
                                                <td bgcolor="#fe0001" width="50%" height="6" style="width:50%; height:6px; line-height:6px; font-size:0; mso-line-height-rule:exactly;">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:7px 24px 12px 24px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#666666;">Certificate of Employment Request</td>
                                </tr>
                            </table>

                            <!-- REFERENCE NO -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:10px 24px 0 24px; text-align:center; font-family: Arial, Helvetica, sans-serif;font-size:10px; color:#555555;">Reference No: <strong style="color:#333333;">{{ $coe_reference }}</strong></td>
                                </tr>
                            </table>

                            <!-- MESSAGE -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:22px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">Dear HR Team,</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">I would like to formally request a Certificate of Employment (COE). Please find the details submitted through the request form below:</td>
                                </tr>
                            </table>

                            <!-- REQUEST SUMMARY SECTION -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:24px 24px 10px 24px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; color:#111111; text-transform:uppercase; letter-spacing:.4px;">Request Summary</td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%; border:1px solid #e5e7eb;">
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Reference No</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $coe_reference }}</td>
                                            </tr>
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Name</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;"><strong>{{ $name ?? 'N/A' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Purpose</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $purpose ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Request Type</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $reason_for_request ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Employment Status</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $employment_status ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Designation</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $designation ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Hiring Date</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ \Carbon\Carbon::parse($hire_date)->format('F j, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Delivery Method</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $receive_method ?? 'N/A' }}</td>
                                            </tr>
                                            @if(($receive_method ?? '') === 'Viber')
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; {{ !($personal_number ?? null) ? '' : 'border-bottom:1px solid #e5e7eb;' }} white-space:nowrap;">Viber Number</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; {{ !($personal_number ?? null) ? '' : 'border-bottom:1px solid #e5e7eb;' }} word-break:break-word;">{{ $viber_number ?? 'N/A' }}</td>
                                            </tr>
                                            @if($personal_number ?? null)
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; white-space:nowrap;">Personal Number</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; word-break:break-word;">{{ $personal_number }}</td>
                                            </tr>
                                            @endif
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- ADDITIONAL NOTES SECTION -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:24px 24px 10px 24px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; color:#111111; text-transform:uppercase; letter-spacing:.4px;">Additional Notes</td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%; border:1px solid #e5e7eb;">
                                            <tr>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; background-color:#fafafa; word-break:break-word;">{{ $additional_notes ?: 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:20px 24px 0 24px; text-align:center; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#555555;">View this request on approval.</td>
                                </tr>
                            </table>

                            <!-- SPACER -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td height="26" style="height:26px; line-height:26px; font-size:0;">&nbsp;</td>
                                </tr>
                            </table>

                            <!-- FOOTER -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%; border-top:1px solid #efefef;">
                                <tr>
                                    <td style="padding:16px 24px 24px 24px; font-family:Helvetica, Arial, sans-serif; font-size:12px; color:#888888; text-align:center;">This is an automated confirmation. For questions, contact your HR representatives.</td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>

                <!--[if mso]>
                </td></tr></table>
                <![endif]-->
            </td>
        </tr>
    </table>
</body>

</html>
