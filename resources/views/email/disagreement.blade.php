<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #248AFD;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 100px;
            height: auto;
            margin-bottom: 10px;
        }
        .content {
            padding: 20px 0;
        }
        .alert-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .policy-list {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }
        .policy-list li {
            margin: 10px 0;
            padding: 8px 0;
        }
        .policy-list li:last-child {
            border-bottom: none;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #ddd;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #248AFD;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header with Logo -->
        <div class="header">
            <img src="{{ asset('login_css/images/email_logo.png') }}" alt="Company Logo" width="400" height="auto" style="width: 400px !important; height: auto !important; display: block;">
            {{-- <h1 style="margin: 10px 0; color: #248AFD;">Pascal Resources Energy, Inc.</h1> --}}
        </div>

        <!-- Main Content -->
        <div class="content">
            {{-- <h2 style="color: #333;">Employee Policy Disagreement Alert</h2>

            <div class="alert-box">
                <strong>⚠️ Important:</strong> An employee has submitted a disagreement on company policies and requires immediate review.
            </div> --}}
            <b>Good day.</b>
            <p>This is to inform you that an employee has submitted a disagreement regarding certain company policies. Please see the details below:</p>

            <div style="background-color: #f0f8ff; padding: 15px; border-radius: 4px; margin: 20px 0;">
                <p><strong>Employee Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>
                <p><strong>Submission Date:</strong> {{ now()->format('F d, Y h:i A') }}</p>
            </div>

            <!-- Policy Responses -->
            <h3>Policy Acknowledgment Status:</h3>
            <div class="policy-list">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li>
                        <strong>Drug and Alcohol Abuse Policy (DABP):</strong>
                        <span style="color: {{ str_contains($user->dabp, "doesn't agree") ? '#dc3545' : '#28a745' }}; font-weight: bold;">
                            {{ $user->dabp }}
                        </span>
                    </li>
                    <li>
                        <strong>Attendance & Timekeeping Policy (ATKP):</strong>
                        <span style="color: {{ str_contains($user->atkp, "doesn't agree") ? '#dc3545' : '#28a745' }}; font-weight: bold;">
                            {{ $user->atkp }}
                        </span>
                    </li>
                    <li>
                        <strong>Code of Conduct (COC):</strong>
                        <span style="color: {{ str_contains($user->coc, "doesn't agree") ? '#dc3545' : '#28a745' }}; font-weight: bold;">
                            {{ $user->coc }}
                        </span>
                    </li>
                </ul>
            </div>
            <p>Kindly review this matter and advise on the appropriate next steps. Please let us know if further information or action is required.</p>
            <div style="text-align: center;">
                <a href="{{ url('account-setting-hr', ['id' => $user->id]) }}" class="button">
                    Review Employee Profile
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ now()->year }} Pascal Resources Energy, Inc. All rights reserved.</p>
            <p>This is an automated email. Please do not reply directly.</p>
        </div>
    </div>
</body>
</html>