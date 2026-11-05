<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New IUR Request</title>

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
                                    <td style="padding:7px 24px 12px 24px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#666666;">ID & Uniform Request</td>
                                </tr>
                            </table>

                            <!-- REFERENCE NO -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:10px 24px 0 24px; text-align:center; font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#555555;">Reference No: <strong style="color:#333333;">{{ $iur_reference }}</strong></td>
                                </tr>
                            </table>

                            <!-- MESSAGE -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:24px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">
                                        @if($status === 'Processing')
                                            Your request has been approved and is now being processed.
                                        @elseif($status === 'Correction Required')
                                            Your request needs your attention before it can be processed.
                                            <div style="margin-top:10px; padding:14px; background-color:#fff8e1; border-left:4px solid #f59e0b; color:#374151; white-space:pre-wrap; word-break:break-word;"><strong>Message from the approver:</strong><br>{{ $approval_remarks }}</div>
                                        @elseif($status === 'Declined')
                                            Your request has been declined.
                                            @if(!empty($approval_remarks))
                                                <br><div style="margin-top: 10px;">
                                                        <strong>Reason:</strong> {{ $approval_remarks }}
                                                    </div>
                                            @endif
                                        @else
                                            A request has been submitted and it requires your approval.
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <!-- REQUEST DETAILS -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:24px 24px 10px 24px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; color:#111111; text-transform:uppercase; letter-spacing:.4px;">Request Details</td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%; border:1px solid #e5e7eb;">
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Employee</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;"><strong>{{ $employee_name }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Request Type</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $request_for }}</td>
                                            </tr>
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Location</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $work_location }}</td>
                                            </tr>
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Employee Type</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $type }}</td>
                                            </tr>
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; white-space:nowrap;">Details of Work Assignment</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; word-break:break-word;">{{ $details }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- UNIFORM SECTION --}}
                            @if(in_array($request_for, ['Uniform', 'Both']))
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:24px 24px 10px 24px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; color:#111111; text-transform:uppercase; letter-spacing:.4px;">Uniform Request</td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%; border:1px solid #e5e7eb;">
                                            @if ($work_location === 'Plant')
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Prod Shirt</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $prod_qty }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">White</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $white_qty }}</td>
                                            </tr>
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Black</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $black_qty }}</td>
                                            </tr>
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Polo</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $collar_qty }}</td>
                                            </tr>
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Previously Issued</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $issued ?? 'N/A' }}</td>
                                            </tr>
                                            @if($issued === 'Yes')
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Issued Remarks</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $issued_remarks ?? 'N/A' }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Reason for New Set</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $issued_reasons ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Size</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $size ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; white-space:nowrap;">Notes</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; word-break:break-word;">{{ $notes ?: 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            {{-- ID SECTION --}}
                            @if(in_array($request_for, ['ID', 'Both']))
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:24px 24px 10px 24px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; color:#111111; text-transform:uppercase; letter-spacing:.4px;">ID Request</td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%; border:1px solid #e5e7eb;">
                                            <tr>
                                                <td width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border-right:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; white-space:nowrap;">Reason for ID Request</td>
                                                <td style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; vertical-align:top; border-bottom:1px solid #e5e7eb; word-break:break-word;">{{ $id_request ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- CTA -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:24px 24px 0 24px; text-align:center;">
                                        @if(in_array($status, ['Processing', 'Declined', 'Correction Required']))
                                            <a href="{{ url('/iur') }}" style="display:inline-block; color:#0369a1; font-family:Helvetica, Arial, sans-serif; font-size:14px;">View request</a>
                                        @else
                                            <a href="{{ url('/iur-approval') }}" style="display:inline-block; color:#0369a1; font-family:Helvetica, Arial, sans-serif; font-size:14px;">Review request</a>
                                        @endif
                                    </td>
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
                                    <td style="padding:16px 24px 24px 24px; font-family:Helvetica, Arial, sans-serif; font-size:12px; color:#888888; text-align:center;">This is an automated notification. Please do not reply to this email.</td>
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
