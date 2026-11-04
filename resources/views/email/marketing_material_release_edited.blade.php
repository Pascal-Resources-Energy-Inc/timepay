<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketing Material Release Updated</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f6f8;">
        <tr>
            <td align="center" style="padding:28px 12px;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="width:100%; max-width:600px; background-color:#ffffff; border:1px solid #e5e7eb; border-radius:8px;">
                    <tr>
                        <td style="padding:24px 28px 8px; font-family:Helvetica, Arial, sans-serif;">
                            <h2 style="margin:0; color:#1f2937; font-size:20px;">Marketing Material Release Updated</h2>
                            <div style="display:flex; margin-top:10px;">
                                <span style="display:block; width:50%; border-top:4px solid #59c2e5;"></span>
                                <span style="display:block; width:50%; border-top:4px solid #fe0001;"></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px; font-family:Helvetica, Arial, sans-serif; color:#374151; font-size:14px; line-height:1.6;">
                            <p style="margin:0 0 16px;">Good day,</p>
                            <p style="margin:0 0 18px;">
                                The participant updated their consent and authorization selections.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
                                <tr>
                                    <td style="padding:9px 12px; border:1px solid #e5e7eb; background:#f8fafc; font-weight:600; width:38%;">Reference No.</td>
                                    <td style="padding:9px 12px; border:1px solid #e5e7eb;">{{ $release->reference_no }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:9px 12px; border:1px solid #e5e7eb; background:#f8fafc; font-weight:600;">Name</td>
                                    <td style="padding:9px 12px; border:1px solid #e5e7eb;">{{ $release->first_name }} {{ $release->last_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:9px 12px; border:1px solid #e5e7eb; background:#f8fafc; font-weight:600;">Edited At</td>
                                    <td style="padding:9px 12px; border:1px solid #e5e7eb;">{{ optional($release->edited_at)->format('F j, Y, g:i a') }}</td>
                                </tr>
                            </table>

                            <p style="margin:22px 0 0;">
                                <a href="{{ $requestUrl }}" style="color:#0369a1; text-decoration:underline;">View updated request</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px 24px; border-top:1px solid #efefef; font-family:Helvetica, Arial, sans-serif; font-size:12px; line-height:1.5; color:#888888; text-align:center;">
                            This is an automated notification from HERA. Please do not reply to this email.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
