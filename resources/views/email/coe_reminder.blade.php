<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Certificate of Employment - Reminder</title>

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #222;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        .container {
            width: 100%;
            -webkit-text-size-adjust: 100%;
        }

        .card {
            max-width: 680px;
            margin: 0 auto;
            border: 1px solid #e6e6e6;
            border-radius: 6px;
            padding: 20px;
        }

        .header {
            border-bottom: 1px solid #efefef;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .brand {
            font-size: 1.6rem;
            font-weight: bold;
            color: #111;
        }

        .subtitle {
            font-size: 13px;
            color: #666;
            margin-top: 4px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            margin: 20px 0;
            color: #111;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        tr {
            display: flex;
        }

        td.label {
            width: 36%;
            padding: 8px 0;
            color: #444;
            font-weight: 600;
            vertical-align: top;
        }

        td.value {
            padding: 8px 0;
            color: #333;
        }

        .footer {
            font-size: 12px;
            color: #888;
            text-align: center;
            padding-top: 16px;
        }

        @media (max-width:600px) {
            .card {
                padding: 16px;
            }

            td.label {
                display: block;
                width: 100%;
                font-size: 13px;
            }

            td.value {
                display: block;
                width: 100%;
                margin-bottom: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <div class="brand" style="font-family: Roboto, -apple-system, 'Segoe UI', Arial, sans-serif;">PASCAL RESOURCES ENERGY INC.</div>
                <div class="color" aria-hidden="true">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="margin:8px 0; border-collapse:collapse;">
                        <tr style="display:table-row;">
                            <td style="display:table-cell; width:50%; height:6px; line-height:6px; font-size:0; mso-line-height-rule:exactly;" bgcolor="#59c2e5" width="50%" height="6">&nbsp;</td>
                            <td style="display:table-cell; width:50%; height:6px; line-height:6px; font-size:0; mso-line-height-rule:exactly;" bgcolor="#fe0001" width="50%" height="6">&nbsp;</td>
                        </tr>
                    </table>
                </div>
                <div class="subtitle">Certificate of Employment Request</div>
            </div>

            <div style="text-align:center;margin: 10px 0 25px 0;font-size:10px;color:#555;">
                Reference No: <strong style="color:#333;">{{ $coe_reference }}</strong>
            </div>

            <p style="margin:0 0 20px 0;color:#333;">Dear HR Team,</p>

            <p style="margin:20px 0 20px 0;color:#333;">I hope you're doing well. I'd like to follow up on the <b>Certificate of Employment (COE)</b> request below, which has been pending for a few days. Could you kindly check on its status when you have a moment?</p>

            <div class="section-title">Request Summary</div>

            <table role="presentation">
                <tr>
                    <td class="label">Reference No:</td>
                    <td class="value">{{ $coe_reference }}</td>
                </tr>
                <tr>
                    <td class="label">Name:</td>
                    <td class="value">{{ $name }}</td>
                </tr>
                <tr>
                    <td class="label">Purpose:</td>
                    <td class="value">{{ $purpose }}</td>
                </tr>
                <tr>
                    <td class="label">Request:</td>
                    <td class="value">{{ $reason_for_request }}</td>
                </tr>
                <tr>
                    <td class="label">Date Filed:</td>
                    <td class="value">{{ $created_at->format('M. d, Y') }}</td>
                </tr>
            </table>

            <p style="text-align:center; margin:20px 0 0 0;">
                <a href="{{ url('/coe-approval') }}" style="display:inline-block;padding:10px 24px;background:#007bff;color:#fff;text-decoration:none;border-radius:4px;font-size:14px;">View in Approval</a>
            </p>

            <div class="footer">This is an automated confirmation. For questions, contact your HR representatives.</div>
        </div>
    </div>
</body>

</html>
