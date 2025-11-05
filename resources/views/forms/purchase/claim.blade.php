<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Your Order - HERA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f5f7;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            width: 100%;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            background: #4B49AC;
            color: white;
            padding: 25px 30px;
            border-bottom: 3px solid #3d3a8a;
        }

        .card-header-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-icon {
            font-size: 40px;
            opacity: 0.9;
        }

        .header-text h1 {
            font-size: 24px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .header-text p {
            font-size: 13px;
            opacity: 0.85;
            margin: 0;
        }

        .card-body {
            padding: 30px;
        }

        .status-section {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f4f5f7;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-claimed {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-processing {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-forfeited {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .info-grid {
            display: grid;
            gap: 15px;
            margin-bottom: 25px;
        }

        .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            border-left: 3px solid #4B49AC;
        }

        .info-label {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .info-value {
            font-size: 16px;
            font-weight: 500;
            color: #212529;
        }

        .items-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
        }

        .items-header {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #4B49AC;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
        }

        .item-label {
            color: #495057;
            font-size: 14px;
        }

        .item-value {
            font-weight: 600;
            color: #212529;
            font-size: 14px;
        }

        .total-section {
            background: #fff;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 25px;
            border: 2px solid #4B49AC;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            font-size: 16px;
            font-weight: 600;
            color: #212529;
        }

        .total-amount {
            font-size: 28px;
            font-weight: 700;
            color: #4B49AC;
        }

        .qr-section {
            text-align: center;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 4px;
            border: 1px solid #e9ecef;
        }

        .qr-code-wrapper {
            display: inline-block;
            padding: 15px;
            background: white;
            border-radius: 4px;
            border: 2px solid #dee2e6;
            margin-bottom: 15px;
        }

        .qr-text {
            font-size: 16px;
            color: #495057;
            font-weight: 600;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
        }

        .alert-message {
            padding: 15px 20px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .error-container {
            text-align: center;
            padding: 50px 30px;
        }

        .error-icon {
            font-size: 70px;
            color: #dc3545;
            margin-bottom: 20px;
        }

        .error-title {
            font-size: 24px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 12px;
        }

        .error-message {
            font-size: 15px;
            color: #6c757d;
            line-height: 1.6;
        }

        .footer-info {
            text-align: center;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 1px solid #e9ecef;
            font-size: 12px;
            color: #6c757d;
        }

        @media (max-width: 480px) {
            .card-header {
                padding: 20px;
            }

            .card-header-content {
                flex-direction: column;
                text-align: center;
            }

            .header-icon {
                font-size: 35px;
            }

            .header-text h1 {
                font-size: 20px;
            }

            .card-body {
                padding: 20px;
            }

            .total-amount {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-header-content">
                    <div class="header-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="header-text">
                        <h1>LPG Order Details</h1>
                        <p>HERA Discounted LPG Refill Program</p>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if(isset($error))
                    <div class="error-container">
                        <div class="error-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="error-title">{{ $error }}</div>
                        <div class="error-message">{{ $message }}</div>
                    </div>
                @else
                    <!-- Status Badge -->
                    <div class="status-section">
                        @if($purchase->status == 'Claimed')
                            <span class="status-badge status-claimed">
                                <i class="fas fa-check-circle"></i> Claimed
                            </span>
                        @elseif($purchase->status == 'Processing')
                            <span class="status-badge status-processing">
                                <i class="fas fa-clock"></i> Processing
                            </span>
                        @elseif($purchase->status == 'Forfeited')
                            <span class="status-badge status-forfeited">
                                <i class="fas fa-times-circle"></i> Forfeited
                            </span>
                        @endif
                    </div>

                    <!-- Order Information -->
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Order Number</div>
                            <div class="info-value">{{ $purchase->order_number }}</div>
                        </div>

                        @if($purchase->employee_name)
                        <div class="info-item">
                            <div class="info-label">Employee Name</div>
                            <div class="info-value">{{ $purchase->employee_name }}</div>
                        </div>
                        @endif

                        @if($purchase->employee_number)
                        <div class="info-item">
                            <div class="info-label">Employee Number</div>
                            <div class="info-value">{{ $purchase->employee_number }}</div>
                        </div>
                        @endif

                        <div class="info-item">
                            <div class="info-label">Date Ordered</div>
                            <div class="info-value">{{ date('F d, Y - h:i A', strtotime($purchase->created_at)) }}</div>
                        </div>

                        @if($purchase->claimed_at)
                        <div class="info-item">
                            <div class="info-label">Date Claimed</div>
                            <div class="info-value">{{ date('F d, Y - h:i A', strtotime($purchase->claimed_at)) }}</div>
                        </div>
                        @endif
                    </div>

                    <!-- Items Section -->
                    <div class="items-section">
                        <div class="items-header">
                            <i class="fas fa-box"></i>
                            <span>Order Items</span>
                        </div>
                        <div class="item-row">
                            <span class="item-label">Total Items</span>
                            <span class="item-value">{{ $purchase->total_items }} pcs</span>
                        </div>
                    </div>

                    <!-- Total Amount -->
                    <div class="total-section">
                        <div class="total-row">
                            <div class="total-label">Total Amount:</div>
                            <div class="total-amount">₱ {{ number_format($purchase->total_amount, 2) }}</div>
                        </div>
                    </div>

                    <!-- QR Code -->
                    <div class="qr-section">
                        <div class="qr-code-wrapper">
                            {!! QrCode::size(150)->generate(route('purchase.claim', $purchase->qr_code)) !!}
                        </div>
                        <div class="qr-text">{{ $purchase->qr_code }}</div>
                    </div>

                    <!-- Status Messages -->
                    @if($purchase->status == 'Claimed')
                        <div class="alert-message alert-success">
                            <i class="fas fa-check-circle"></i>
                            <span>This order has been claimed and processed successfully!</span>
                        </div>
                    @elseif($purchase->status == 'Processing')
                        <div class="alert-message alert-warning">
                            <i class="fas fa-hourglass-half"></i>
                            <span>Please present this QR code to claim your order.</span>
                        </div>
                    @endif

                    <!-- Footer Info -->
                    <div class="footer-info">
                        <p>HERA LPG Refill System • Keep this page for your records</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>