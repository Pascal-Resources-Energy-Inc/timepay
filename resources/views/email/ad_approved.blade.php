<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authority to Deduct - Submission Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            background-color: #28a745;
            color:rgb(255, 255, 255);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-badges {
            display: inline-block;
            background-color: #FF4C4C;
            color:rgb(255, 255, 255);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .details-section {
            margin-bottom: 25px;
        }
        .details-section h3 {
            color: #007bff;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
        }
        .detail-label {
            font-weight: bold;
            color: #495057;
            flex: 1;
        }
        .detail-value {
            flex: 2;
            text-align: right;
        }
        .amount-highlight {
            color: #28a745;
            font-weight: bold;
            font-size: 16px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .company-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .note {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .note h4 {
            margin: 0 0 10px 0;
            color: #0c5460;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .container {
                padding: 20px;
            }
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .detail-value {
                text-align: left;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Authority to Deduct</h1>
            <p>Form Reference: HRD-CBD-FOR-002-000</p>
            <div style="margin-top: 15px;">
                @if($employeeAd->status === 'Approved' || (isset($newStatus) && $newStatus === 'Approved'))
                <span class="status-badge status-{{ strtolower($employeeAd->status) }}">{{ $employeeAd->status }}</span>
                @elseif($employeeAd->status === 'Declined' || (isset($newStatus) && $newStatus === 'Declined'))
                <span class="status-badges status-{{ strtolower($employeeAd->status) }}">{{ $employeeAd->status }}</span>
                @endif
            </div>
        </div>

        <div class="company-info">
            <strong>PASCAL RESOURCES ENERGY INC.</strong><br>
            Human Resources Department
        </div>

        <p>Dear <strong>{{ $employeeAd->name }}</strong>,</p>

        <p>Your Authority to Deduct request has been <strong>{{ strtolower($employeeAd->status) }}</strong>. 
        Below are the details:</p>

        <!-- <p>We have successfully received your Authority to Deduct form. Your submission has been recorded and is currently under review.</p> -->
        <div class="details-section">
            <h3>📋 Submission Details</h3>
            <div class="detail-row">
                <span class="detail-label">Reference Number:</span>
                <span class="detail-value"><strong>{{ $employeeAd->ad_number }}</strong></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Submission Date:</span>
                <span class="detail-value">{{ $employeeAd->applied_date }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Employee Name:</span>
                <span class="detail-value">{{ $employeeAd->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Department:</span>
                <span class="detail-value">{{ $employeeAd->department }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Work Location:</span>
                <span class="detail-value">{{ $employeeAd->location }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Remarks:</span>
                <span class="detail-value">{{ $employeeAd->remarks }}</span>
            </div>
        </div>

        <div class="details-section">
            <h3>💰 Deduction Information</h3>
            <div class="detail-row">
                <span class="detail-label">Type of Deduction:</span>
                <span class="detail-value">{{ $employeeAd->type_of_deduction }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Particular:</span>
                <span class="detail-value">{{ $employeeAd->particular }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Amount:</span>
                <span class="detail-value amount-highlight">₱{{ number_format($employeeAd->amount, 2) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Number of Deductions:</span>
                <span class="detail-value">{{ $employeeAd->frequency }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Amount Per Cutoff:</span>
                <span class="detail-value">₱{{ number_format($employeeAd->deductible, 2) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Start of Deduction:</span>
                <span class="detail-value">{{ date('M. d, Y', strtotime($employeeAd->start_date)) }}</span>
            </div>
        </div>

        @if($employeeAd->status === 'Approved' || (isset($newStatus) && $newStatus === 'Approved'))
            <div class="note" style="background-color: #d4edda; border-color: #c3e6cb; color: #155724;">
                <h4>✅ Request Approved!</h4>
                <p><strong>Great news!</strong> Your Authority to Deduct request has been approved by the HR department.</p>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Deductions will begin on: <strong>{{ date('F d, Y', strtotime($employeeAd->start_date)) }}</strong></li>
                    <li>Amount per cutoff: <strong>₱{{ number_format($employeeAd->deductible, 2) }}</strong></li>
                    <li>Total deductions: <strong>{{ $employeeAd->frequency }}</strong></li>
                    <li>The payroll team will implement the deductions as scheduled.</li>
                </ul>
            </div>
        @elseif($employeeAd->status === 'Declined' || (isset($newStatus) && $newStatus === 'Declined'))
            <div class="note" style="background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;">
                <h4>❌ Request Declined</h4>
                <p>Unfortunately, your Authority to Deduct request has been declined by the HR department.</p>
                @if($employeeAd->remarks)
                    <p><strong>Reason:</strong> {{ $employeeAd->remarks }}</p>
                @endif
                <p>Please contact the HR department if you need clarification or would like to discuss this decision.</p>
            </div>
        @endif

        <div style="margin-top: 30px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
            <p style="margin: 0; font-size: 14px; color: #666; text-align: center;">
                <strong>Remember:</strong> This is a voluntary program and is not a condition of employment with Pascal Resources Energy Inc.
            </p>
        </div>

        <div class="footer">
            <p><strong>Pascal Resources Energy Inc.</strong><br>
            Human Resources Department<br>
            This is an automated email. Please do not reply to this message.</p>
            <p style="margin-top: 10px; color: #999;">
                Email sent on {{ date('F d, Y \a\t g:i A') }}
            </p>
        </div>
    </div>
</body>
</html>