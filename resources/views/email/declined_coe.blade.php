<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Employment - Declined</title>

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
                                    <td style="padding:10px 24px 0 24px; text-align:center; font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#555555;">Reference No: <strong style="color:#333333;">{{ $data['coe_reference'] }}</strong></td>
                                </tr>
                            </table>

                            <!-- MESSAGE -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:22px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">Dear {{ $data['name'] }},</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">Your Certificate of Employment (COE) request. After review, we regret to inform you that it could not be processed at this time for the following reason.</td>
                                </tr>
                            </table>

                            <!-- REASON -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td style="padding:14px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;"><b>Reason:</b> {{ $data['approval_remarks'] }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">If you believe this was made in error or would like to resubmit your request with updated information, please don't hesitate to reach out to us. We appreciate your understanding.</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#555555;">Thank you!</td>
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
