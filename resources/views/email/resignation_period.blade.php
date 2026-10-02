<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Resignation Period</title>

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

            .brand-td {
                padding: 18px 14px 10px 14px !important;
                font-size: 18px !important;
            }

            .pad24 {
                padding-left: 14px !important;
                padding-right: 14px !important;
            }

            .sub-td {
                padding: 4px 14px 12px 14px !important;
                font-size: 12px !important;
            }

            .content-td {
                padding-left: 14px !important;
                padding-right: 14px !important;
                font-size: 13px !important;
            }

            td.lbl,
            td.val {
                padding: 8px 10px !important;
                font-size: 12px !important;
            }

            td.lbl {
                width: 36% !important;
                white-space: normal !important;
            }

            .foot-td {
                padding: 12px 14px 20px 14px !important;
                font-size: 11px !important;
            }
        }
    </style>
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f3f4f6" style="background-color:#f3f4f6;">
        <tr>
            <td align="center" class="outer-pad" style="padding:16px 8px;">
                <!--[if mso]>
                <table role="presentation" width="680" cellpadding="0" cellspacing="0" border="0" align="center"><tr><td>
                <![endif]-->

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="container" style="max-width:680px; width:100%;">
                    <tr>
                        <td class="card" style="background-color:#ffffff; border:1px solid #e6e6e6; border-radius:8px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-bottom:1px solid #efefef;">
                                <tr>
                                    <td class="brand-td" style="padding:24px 24px 5px 24px; font-family:Roboto, -apple-system, 'Segoe UI', Arial, sans-serif; font-size:17px; font-weight:500; color:#111111;">
                                        PASCAL RESOURCES ENERGY INC.
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pad24" style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td bgcolor="#59c2e5" width="50%" height="6" style="width:50%; height:6px; line-height:6px; font-size:0; mso-line-height-rule:exactly;">&nbsp;</td>
                                                <td bgcolor="#fe0001" width="50%" height="6" style="width:50%; height:6px; line-height:6px; font-size:0; mso-line-height-rule:exactly;">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pad24 sub-td" style="padding:7px 24px 12px 24px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#666666;">
                                        Employee Resignation Period
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="content-td" style="padding:20px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">
                                        Dear HR Team,
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-td" style="padding:12px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">
                                        This is a heads-up that the following employee has reached 30 counted days from the resignation date.
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td class="pad24" style="padding:20px 24px 0 24px;">
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; border:1px solid #e5e7eb; border-right:none; border-bottom:none;">Employee</td>
                                                    <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;"><strong>{{ trim($employee->first_name . ' ' . $employee->middle_name . ' ' . $employee->last_name) }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; border:1px solid #e5e7eb; border-right:none; border-bottom:none;">Employee Number</td>
                                                    <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{ $employee->employee_number }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; border:1px solid #e5e7eb; border-right:none; border-bottom:none;">Position</td>
                                                    <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{ $employee->position }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; border:1px solid #e5e7eb; border-right:none; border-bottom:none;">Resignation Date</td>
                                                    <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{ \Carbon\Carbon::parse($employee->date_resigned)->format('F d, Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; border:1px solid #e5e7eb; border-right:none;">Date Completed</td>
                                                    <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; border:1px solid #e5e7eb; word-break:break-word;">{{ \Carbon\Carbon::parse($employee->resignation_completion_date)->format('F d, Y') }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                            </table>

                            <!-- <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"> -->
                            <!--     <tr> -->
                            <!--         <td class="pad24" style="padding:20px 24px 0 24px;"> -->
                            <!--             <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e5e7eb; background-color:#f0f7ff; border-left:4px solid #59c2e5;"> -->
                            <!--                 <tr> -->
                            <!--                     <td style="padding:14px 16px; font-family:Helvetica, Arial, sans-serif; font-size:12px; line-height:1.6; color:#475569;"> -->
                            <!--                         This notification is for awareness only. No immediate action is required. -->
                            <!--                     </td> -->
                            <!--                 </tr> -->
                            <!--             </table> -->
                            <!--         </td> -->
                            <!--     </tr> -->
                            <!-- </table> -->

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:24px; border-top:1px solid #efefef;">
                                <tr>
                                    <td class="foot-td" style="padding:16px 24px 24px 24px; font-family:Helvetica, Arial, sans-serif; font-size:12px; line-height:1.6; color:#888888; text-align:center;">
                                        This is an automated notification from the HERA system. Please do not reply to this email.
                                    </td>
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
