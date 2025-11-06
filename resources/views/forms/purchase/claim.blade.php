<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Your Order - HERA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* SweetAlert Button Center */
        .swal-button-container {
            text-align: center !important;
        }
        
        .swal-footer {
            text-align: center !important;
        }
    </style>
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

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
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

        /* Form Styles */
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
            margin-top: 25px;
            border: 1px solid #e9ecef;
        }

        .form-header {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 600;
            color: #4B49AC;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4B49AC;
            box-shadow: 0 0 0 3px rgba(75, 73, 172, 0.1);
        }

        .form-group input:disabled,
        .form-group textarea:disabled {
            background: #e9ecef;
            cursor: not-allowed;
        }

        .location-info {
            background: #fff;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            margin-top: 10px;
        }

        .location-info p {
            font-size: 13px;
            color: #6c757d;
            margin: 5px 0;
        }

        .location-info strong {
            color: #212529;
        }

        .address-display {
            background: #e7f3ff;
            padding: 12px;
            border-radius: 4px;
            border-left: 3px solid #4B49AC;
            margin-top: 8px;
        }

        .address-display p {
            font-size: 14px;
            color: #212529;
            font-weight: 500;
            line-height: 1.5;
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary {
            background: #4B49AC;
            color: white;
        }

        .btn-primary:hover {
            background: #3d3a8a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(75, 73, 172, 0.3);
        }

        .btn-primary:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }

        .btn-location {
            background: #28a745;
            color: white;
            margin-bottom: 15px;
        }

        .btn-location:hover {
            background: #218838;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #4B49AC;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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

                        @if($purchase->employee_work_place)
                        <div class="info-item">
                            <div class="info-label">Work Location</div>
                            <div class="info-value">{{ $purchase->employee_work_place }}</div>
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
                        
                        @if($purchase->qty_330g > 0)
                        <div class="item-row">
                            <span class="item-label">
                                <i class="fas fa-fire"></i> 330g LPG Cylinder - Refill
                            </span>
                            <span class="item-value">{{ $purchase->qty_330g }} pcs × ₱57.00 = ₱{{ number_format($purchase->qty_330g * 57, 2) }}</span>
                        </div>
                        @endif
                        
                        @if($purchase->qty_230g > 0)
                        <div class="item-row">
                            <span class="item-label">
                                <i class="fas fa-fire"></i> 230g LPG Cylinder - Refill
                            </span>
                            <span class="item-value">{{ $purchase->qty_230g }} pcs × ₱40.00 = ₱{{ number_format($purchase->qty_230g * 40, 2) }}</span>
                        </div>
                        @endif
                        
                        <div class="item-row" style="border-top: 2px solid #e9ecef; margin-top: 10px; padding-top: 15px;">
                            <span class="item-label"><strong>Total Items</strong></span>
                            <span class="item-value"><strong>{{ $purchase->total_items }} pcs</strong></span>
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

                        @if($purchase->giver_name)
                        <div class="info-grid" style="margin-top: 20px;">
                            <div class="info-item">
                                <div class="info-label">Given By</div>
                                <div class="info-value">{{ $purchase->giver_name }} ({{ $purchase->giver_position }})</div>
                            </div>
                            @if($purchase->claim_address)
                            <div class="info-item">
                                <div class="info-label">Claim Location</div>
                                <div class="info-value">{{ $purchase->claim_address }}</div>
                                @if($purchase->claim_latitude && $purchase->claim_longitude)
                                <a href="https://www.google.com/maps?q={{ $purchase->claim_latitude }},{{ $purchase->claim_longitude }}" target="_blank" style="color: #4B49AC; text-decoration: none; font-size: 13px; display: inline-block; margin-top: 5px;">
                                    <i class="fas fa-map-marker-alt"></i> View on Map
                                </a>
                                @endif
                            </div>
                            @endif
                        </div>
                        @endif
                    @elseif($purchase->status == 'Processing')
                        @php
                            $createdAt = new \DateTime($purchase->created_at);
                            $now = new \DateTime();
                            
                            // Calculate business days (excluding weekends)
                           
                            
                            $businessDaysPassed = getBusinessDays($createdAt, $now);
                            $expiresAt = addBusinessDays($createdAt, 3);
                            $isExpired = $now > $expiresAt;
                            
                            // Calculate remaining time
                            $remainingBusinessDays = 3 - $businessDaysPassed;
                            $hoursUntilExpiry = null;
                            
                            if (!$isExpired) {
                                $interval = $now->diff($expiresAt);
                                $totalHours = ($interval->days * 24) + $interval->h;
                                $hoursUntilExpiry = $totalHours;
                            }
                        @endphp
                        
                        @if($isExpired)
                            {{-- Order has expired - should be forfeited --}}
                            <div class="alert-message alert-danger" style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>This order has expired and can no longer be claimed. Orders must be claimed within 3 business days (excluding weekends).</span>
                            </div>
                            
                            <div class="alert-message alert-info" style="margin-top: 15px;">
                                <i class="fas fa-info-circle"></i>
                                <span>This order was placed on {{ date('F d, Y - h:i A', strtotime($purchase->created_at)) }} and expired on {{ $expiresAt->format('F d, Y - h:i A') }}.</span>
                            </div>
                        @else
                            {{-- Order is still valid - show claim form --}}
                            
                            <!-- Expiration Warning -->
                            @if($remainingBusinessDays <= 0 && $hoursUntilExpiry <= 24)
                                <div class="alert-message alert-danger" style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; margin-bottom: 20px;">
                                    <i class="fas fa-clock"></i>
                                    <span><strong>Urgent!</strong> This order will expire in approximately {{ max(0, (int)$hoursUntilExpiry) }} hour(s). Please claim it before {{ $expiresAt->format('F d, Y - h:i A') }}.</span>
                                </div>
                            @elseif($remainingBusinessDays == 1)
                                <div class="alert-message alert-warning" style="margin-bottom: 20px;">
                                    <i class="fas fa-clock"></i>
                                    <span>This order will expire in 1 business day. Please claim it before {{ $expiresAt->format('F d, Y - h:i A') }}.</span>
                                </div>
                            @else
                                <div class="alert-message alert-info" style="margin-bottom: 20px;">
                                    <i class="fas fa-info-circle"></i>
                                    <span>This order must be claimed by {{ $expiresAt->format('F d, Y - h:i A') }} (within 3 business days, excluding weekends).</span>
                                </div>
                            @endif

                            <!-- Claim Form -->
                            <div class="form-section">
                                <div class="form-header">
                                    <i class="fas fa-clipboard-check"></i>
                                    <span>Claim This Order</span>
                                </div>

                                <form id="claimForm">
                                    <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                    <input type="hidden" name="latitude" id="latitude">
                                    <input type="hidden" name="longitude" id="longitude">
                                    <input type="hidden" name="address" id="address">

                                    <button type="button" class="btn btn-location" id="getLocationBtn">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Get My Location</span>
                                    </button>

                                    <div class="location-info" id="locationInfo" style="display: none;">
                                        <p><strong><i class="fas fa-map-pin"></i> Location Details:</strong></p>
                                        <p style="font-size: 12px; color: #6c757d;">Coordinates: <span id="coordsText"></span></p>
                                        <div class="address-display" id="addressDisplay">
                                            <p id="addressText">Fetching address...</p>
                                        </div>
                                    </div>

                                     <div class="form-group">
                                        <label for="si">SI *</label>
                                        <input type="text" id="si" name="si" placeholder="Name of staff giving the product">
                                    </div>

                                    <div class="form-group">
                                        <label for="giver_name">Given By (Staff Name) *</label>
                                        <input type="text" id="giver_name" name="giver_name" required placeholder="Name of staff giving the product">
                                    </div>

                                    <div class="form-group">
                                        <label for="giver_position">Giver Job Position *</label>
                                        <input type="text" id="giver_position" name="giver_position" required placeholder="Job title/position of staff">
                                    </div>

                                    <button type="submit" class="btn btn-primary" id="proceedBtn" disabled>
                                        <i class="fas fa-check"></i>
                                        <span>Proceed to Claim</span>
                                    </button>
                                </form>

                                <div class="loading" id="loadingSpinner">
                                    <div class="spinner"></div>
                                    <p>Processing your claim...</p>
                                </div>
                            </div>

                            <div class="alert-message alert-warning">
                                <i class="fas fa-info-circle"></i>
                                <span>Please capture your location and fill in all required information to claim this order. <strong>Note:</strong> Weekends (Saturday & Sunday) are not counted in the 3-day claim period.</span>
                            </div>
                        @endif
                    @endif
                    <!-- Footer Info -->
                    <div class="footer-info">
                        <p>HERA LPG Refill System • Keep this page for your records</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @php
     function getBusinessDays($startDate, $endDate) {
                                $businessDays = 0;
                                $currentDate = clone $startDate;
                                
                                while ($currentDate <= $endDate) {
                                    $dayOfWeek = $currentDate->format('N'); // 1 (Monday) to 7 (Sunday)
                                    if ($dayOfWeek < 6) { // 1-5 are weekdays (Mon-Fri)
                                        $businessDays++;
                                    }
                                    $currentDate->modify('+1 day');
                                }
                                
                                return $businessDays;
    }
   
    @endphp
    <script>
        let locationCaptured = false;
        let currentAddress = '';

        // Get Location and Reverse Geocode
        document.getElementById('getLocationBtn')?.addEventListener('click', function() {
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Getting Location...';

            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lng;
                        
                        document.getElementById('locationInfo').style.display = 'block';
                        document.getElementById('coordsText').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                        
                        btn.innerHTML = '<i class="fas fa-check"></i> Location Captured';
                        btn.style.background = '#28a745';
                        locationCaptured = true;
                        
                        // Fetch address from coordinates using Nominatim (OpenStreetMap)
                        fetchAddress(lat, lng);
                    },
                    function(error) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-map-marker-alt"></i> Get My Location';
                        
                        let errorMsg = 'Unable to get location. ';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMsg += 'Please allow location access in your browser settings.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMsg += 'Location information is unavailable.';
                                break;
                            case error.TIMEOUT:
                                errorMsg += 'Location request timed out. Please try again.';
                                break;
                        }
                        swal({
                            title: "Location Error",
                            text: errorMsg,
                            icon: "error",
                            button: "OK",
                        });
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                swal({
                    title: "Not Supported",
                    text: "Geolocation is not supported by your browser.",
                    icon: "warning",
                    button: "OK",
                });
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-map-marker-alt"></i> Get My Location';
            }
        });

        // Fetch address using reverse geocoding
        function fetchAddress(lat, lng) {
            const addressDisplay = document.getElementById('addressText');
            addressDisplay.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Fetching address...';
            
            // Using Nominatim API for reverse geocoding
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.address) {
                        const addr = data.address;
                        let fullAddress = [];
                        
                        // Build detailed address
                        if (addr.house_number) fullAddress.push(addr.house_number);
                        if (addr.road) fullAddress.push(addr.road);
                        if (addr.suburb || addr.neighbourhood) fullAddress.push(addr.suburb || addr.neighbourhood);
                        if (addr.city || addr.municipality) fullAddress.push(addr.city || addr.municipality);
                        if (addr.state || addr.province) fullAddress.push(addr.state || addr.province);
                        if (addr.postcode) fullAddress.push(addr.postcode);
                        if (addr.country) fullAddress.push(addr.country);
                        
                        currentAddress = fullAddress.join(', ');
                        
                        if (!currentAddress) {
                            currentAddress = data.display_name || 'Address not available';
                        }
                        
                        document.getElementById('address').value = currentAddress;
                        addressDisplay.innerHTML = `<i class="fas fa-location-dot"></i> ${currentAddress}`;
                        
                        // Enable submit button
                        document.getElementById('proceedBtn').disabled = false;
                    } else {
                        currentAddress = `Location: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                        document.getElementById('address').value = currentAddress;
                        addressDisplay.innerHTML = `<i class="fas fa-map-marker-alt"></i> ${currentAddress}`;
                        document.getElementById('proceedBtn').disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Geocoding error:', error);
                    currentAddress = `Location: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                    document.getElementById('address').value = currentAddress;
                    addressDisplay.innerHTML = `<i class="fas fa-map-marker-alt"></i> ${currentAddress}`;
                    document.getElementById('proceedBtn').disabled = false;
                });
        }

        // Handle Form Submission
        document.getElementById('claimForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!locationCaptured) {
                swal({
                    title: "Location Required",
                    text: "Please capture your location first.",
                    icon: "warning",
                    button: "OK",
                });
                return;
            }

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            // Show loading
            document.getElementById('claimForm').style.display = 'none';
            document.getElementById('loadingSpinner').style.display = 'block';

            // Submit via AJAX
            fetch('{{ route("purchase.processClaim") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    swal({
                        title: "Success!",
                        text: "Order claimed successfully!",
                        icon: "success",
                        button: "OK",
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Failed to claim order');
                }
            })
            .catch(error => {
                swal({
                    title: "Error!",
                    text: error.message,
                    icon: "error",
                    button: "OK",
                });
                document.getElementById('claimForm').style.display = 'block';
                document.getElementById('loadingSpinner').style.display = 'none';
            });
        });
    </script>
</body>
</html>