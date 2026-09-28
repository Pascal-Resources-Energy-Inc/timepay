<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            color: #2c3e50;
        }
        .details {
            margin-top: 15px;
        }
        .details li {
            margin-bottom: 8px;
        }
        .footer {
            margin-top: 25px;
            font-size: 12px;
            color: #777;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #28a745;
            color: #fff !important;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <h2 class="title">🚗 New MTA Request Submitted</h2>
    </div>

    <p>Hello <strong>{{ $approver->name }}</strong>,</p>

    <p>A new <strong>Monetized Transportation Allowance (MTA)</strong> request has been submitted and is awaiting your review.</p>

    <ul class="details">
        <li><strong>Employee:</strong> {{ $mta->user->name ?? 'N/A' }}</li>
        <li><strong>Transaction Date:</strong> {{ date('F d, Y', strtotime($mta->mta_date)) }}</li>
        <li><strong>Work Location:</strong> {{ $mta->work_location }}</li>
        <li><strong>Amount:</strong> ₱{{ number_format($mta->mta_amount, 2) }}</li>
        <li><strong>Reference No:</strong> {{ $mta->mta_reference }}</li>
    </ul>

    <p>Please review and take appropriate action in the system.</p>

    <a href="{{ url('/for-mta') }}" class="btn btn-primary text-white btn-rounded">Review Request</a>

    <div class="footer">
        <p>This is an automated email. Please do not reply.</p>
    </div>

</div>

</body>
</html>