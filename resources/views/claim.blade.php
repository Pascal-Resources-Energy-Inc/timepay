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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            width: 100%;
        }

        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .card-header i {
            font-size: 60px;
            margin-bottom: 15px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .card-header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .card-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .card-body {
            padding: 30px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .status-claimed {
            background: #10b981;
            color: white;
        }

        .status-processing {
            background: #f59e0b;
            color: white;
        }

        .status-forfeited {
            background: #ef4444;
            color: white;
        }

        .info-group {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        .info-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }

        .items-list {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .items-title {
            font-size: 14px;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item-name {
            color: #4b5563;
        }

        .item-qty {
            font-weight: 600;
            color: #1f2937;
        }

        .total-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }

        .total-amount {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
        }

        .qr-section {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-top: 20px;
        }

        .qr-code {
            display: inline-block;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .qr-text {
            margin-top: 10px;
            font-size: 14px;
            color: #6b7280;
            font-weight: 600;
            letter-spacing: 2px;
        }

        .error-container {
            text-align: center;
            padding: 40px 20px;
        }

        .error-icon {
            font-size: 80px;
            color: #ef4444;
            margin-bottom: 20px;
        }

        .error-title {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .error-message {
            font-size: 16px;
            color: #6b7280;
            line-height: 1.6;
        }

        .claimed-message {
            background: #d1fae5;
            color: #065f46;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            text-align: center;
            font-weight: 600;
        }

        .processing-message {
            background: #fef3c7;
            color: #92400e;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            text-align: center;
            font-weight: 600;
        }

        @media (max-width: 480px) {
            .card-header h1 {
                font-size: 24px;
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
                <i class="fas fa-shopping-bag"></i>
                <h1>LPG Order Details</h1>
                <p>HERA Discounted LPG Refill</p>
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
                    <div style="text-align: center;">
                        @if($purchase->status == 'Claimed')
                            <span class="status-badge status-claimed">
                                <i class="fas fa-check-circle"></i> CLAIMED
                            </span>
                        @elseif($purchase->status == 'Processing')
                            <span class="status-badge status-processing">
                                <i class="fas fa-clock"></i> PROCESSING
                            </span>
                        @elseif($purchase->status == 'Forfeited')
                            <span class="status-badge status-forfeited">
                                <i class="fas fa-times-circle"></i> FORFEITED
                            </span>
                        @endif
                    </div>

                    <div class="info-group">
                        <div class="info-label">Order Number</div>
                        <div class="info-value">{{ $purchase->order_number }}</div>
                    </div>

                    @if($purchase->employee_name)
                    <div class="info-group">
                        <div class="info-label">Employee Name</div>
                        <div class="info-value">{{ $purchase->employee_name }}</div>
                    </div>
                    @endif

                    @if($purchase->employee_number)
                    <div class="info-group">
                        <div class="info-label">Employee Number</div>
                        <div class="info-value">{{ $purchase->employee_number }}</div>
                    </div>
                    @endif

                    <div class="info-group">
                        <div class="info-label">Date Ordered</div>
                        <div class="info-value">{{ date('F d, Y - h:i A', strtotime($purchase->created_at)) }}</div>
                    </div>

                    @if($purchase->claimed_at)
                    <div class="info-group">
                        <div class="info-label">Date Claimed</div>
                        <div class="info-value">{{ date('F d, Y - h:i A', strtotime($purchase->claimed_at)) }}</div>
                    </div>
                    @endif

                    <div class="items-list">
                        <div class="items-title">
                            <i class="fas fa-box"></i>
                            Order Items
                        </div>
                        <div class="item">
                            <span class="item-name">Total Items</span>
                            <span class="item-qty">{{ $purchase->total_items }} pcs</span>
                        </div>
                    </div>

                    <div class="total-section">
                        <div class="total-label">Total Amount:</div>
                        <div class="total-amount">₱ {{ number_format($purchase->total_amount, 2) }}</div>
                    </div>

                    <div class="qr-section">
                        <div class="qr-code">
                            {!! QrCode::size(150)->generate($purchase->qr_code) !!}
                        </div>
                        <div class="qr-text">{{ $purchase->qr_code }}</div>
                    </div>

                    @if($purchase->status == 'Claimed')
                        <div class="claimed-message">
                            <i class="fas fa-check-circle"></i>
                            This order has been claimed and processed successfully!
                        </div>
                    @elseif($purchase->status == 'Processing')
                        <div class="processing-message">
                            <i class="fas fa-hourglass-half"></i>
                            Please present this QR code to claim your order.
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <script>
        // Add smooth animations on load
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>