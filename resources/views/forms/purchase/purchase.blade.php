@extends('layouts.header')

@section('content')
@php
use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

<div class="main-panel">
    <div class="content-wrapper">
        <div class='row grid-margin'>
            <div class='col-lg-3'>
                <div class="card card-tale">
                    <div class="card-body">
                        <div class="media">                
                            <div class="media-body">
                                <h4 class="mb-4">Total Items Purchased</h4>
                                <h2 class="card-text">{{ $stats['total_purchase'] ?? 0 }}</h2>
                                <small class="mb-4">All time</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-lg-3'>
                <div class="card text-info">
                    <div class="card-body">
                        <div class="media">                
                            <div class="media-body">
                                <h4 class="mb-4">Items This Month</h4>
                                <h2 class="card-text">{{ $stats['total_items_sum'] ?? 0 }}/10</h2>
                                <small class="text-muted">{{ date('F Y') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Monthly Limit Alert --}}
        @if(($stats['total_items_sum'] ?? 0) >= 10)
        <div class='row'>
            <div class='col-lg-12'>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="ti-alert"></i> Monthly Limit Reached!</strong> 
                    You have purchased {{ $stats['total_items_sum'] }} items this month. The limit will reset on {{ date('F 1, Y', strtotime('first day of next month')) }}.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
        @elseif(($stats['total_items_sum'] ?? 0) >= 8)
        <div class='row'>
            <div class='col-lg-12'>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong><i class="ti-info"></i> Almost at Limit!</strong> 
                    You have {{ 10 - ($stats['total_items_sum'] ?? 0) }} items remaining for this month.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- Place Order Section --}}
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Place Order</h4>
                        <p class="card-description">
                            <button type="button" 
                                    class="btn btn-outline-success btn-icon-text" 
                                    data-toggle="modal" 
                                    data-target="#addPurchaseModal" 
                                    id="addPurchaseBtn">
                                <i class="ti-plus btn-icon-prepend"></i> Add Purchase
                            </button>
                        </p>

                        <form method='get' action="{{ route('purchase') }}" enctype="multipart/form-data">
                            <div class='row'>
                                <div class='col-md-2'>
                                    <div class="form-group">
                                        <label class="text-right">From</label>
                                        <input type="date" value='{{ $from ?? date('Y-m-d') }}' class="form-control form-control-sm" name="from"
                                               max='{{ date('Y-m-d') }}' required />
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <div class="form-group">
                                        <label class="text-right">To</label>
                                        <input type="date" value='{{ $to ?? date('Y-m-d') }}' class="form-control form-control-sm" id='to' name="to" required />
                                    </div>
                                </div>
                                <div class='col-md-2 mr-2'>
                                    <div class="form-group">
                                        <label class="text-right">Status</label>
                                        <select class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status'>
                                            <option value="">-- All Status --</option>
                                            <option value="Claimed" {{ ($status ?? '') == 'Claimed' ? 'selected' : '' }}>Claimed</option>
                                            <option value="Processing" {{ ($status ?? '') == 'Processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="Forfeited" {{ ($status ?? '') == 'Forfeited' ? 'selected' : '' }}>Forfeited</option>
                                        </select>
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <div class="form-group">
                                        <label class="invisible">Filter</label>
                                        <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered tablewithSearch">
                                <thead>
                                    <tr>
                                        <th>Date Filed</th>
                                        <th>Order #</th>
                                        <th>Employee Number</th>
                                        <th>Employee Name</th>
                                        <th>Work Location</th>
                                        <th>Total Items</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Expires At</th>
                                        <th>QR Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchases ?? [] as $purchase)
                                    @php
                                        $createdAt = new \DateTime($purchase->created_at);
                                        $expiresAt = addBusinessDays($createdAt, 3);
                                        $now = new \DateTime();
                                        $isExpired = isOrderExpired($purchase->created_at);
                                        
                                        // Calculate remaining time
                                        $interval = $now->diff($expiresAt);
                                        $daysLeft = $interval->days;
                                        $hoursLeft = ($daysLeft * 24) + $interval->h;
                                    @endphp
                                    <tr>
                                        <td>{{ date('M. d, Y', strtotime($purchase->created_at)) }}</td>
                                        <td>{{ $purchase->order_number }}</td>
                                        <td>{{ $purchase->employee_number ?? 'N/A' }}</td>
                                        <td>{{ $purchase->purchaser_name ?? 'N/A' }}</td>
                                        <td>{{ $purchase->employee_work_place ?? 'N/A' }}</td>
                                        <td>{{ $purchase->total_items }}</td>
                                        <td>₱ {{ number_format($purchase->total_amount, 2) }}</td>
                                        <td>
                                            @if($purchase->status == 'Claimed')
                                                <label class="badge badge-success">Claimed</label>
                                            @elseif($purchase->status == 'Processing')
                                                @if($isExpired)
                                                    <label class="badge badge-danger" title="Expired - should be forfeited">
                                                        <i class="ti-alert"></i> Expired
                                                    </label>
                                                @else
                                                    <label class="badge badge-warning">Processing</label>
                                                @endif
                                            @elseif($purchase->status == 'Forfeited')
                                                <label class="badge badge-danger">Forfeited</label>
                                            @else
                                                <label class="badge badge-secondary">{{ $purchase->status }}</label>
                                            @endif
                                        </td>
                                        <td>
                                            @if($purchase->status == 'Processing')
                                                @if($isExpired)
                                                    <span class="text-danger" style="font-size: 12px;">
                                                        <i class="ti-close"></i> Expired
                                                    </span>
                                                @elseif($hoursLeft <= 24)
                                                    <span class="text-danger" style="font-size: 12px;">
                                                        <i class="ti-time"></i> {{ max(0, $hoursLeft) }}h left
                                                    </span>
                                                @elseif($daysLeft <= 1)
                                                    <span class="text-warning" style="font-size: 12px;">
                                                        <i class="ti-time"></i> 1 day left
                                                    </span>
                                                @else
                                                    <span class="text-muted" style="font-size: 12px;">
                                                        {{ $expiresAt->format('M d, Y h:i A') }}
                                                    </span>
                                                @endif
                                            @elseif($purchase->status == 'Claimed')
                                                <span class="text-success" style="font-size: 12px;">
                                                    <i class="ti-check"></i> Claimed
                                                </span>
                                            @elseif($purchase->status == 'Forfeited')
                                                <span class="text-muted" style="font-size: 12px;">
                                                    <i class="ti-na"></i> N/A
                                                </span>
                                            @else
                                                <span class="text-muted" style="font-size: 12px;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($purchase->status != 'Claimed' && !$isExpired)
                                                @if($purchase->qr_code)
                                                    <button type="button" class="btn btn-sm btn-info"
                                                        onclick="viewQRCode(
                                                            '{{ $purchase->order_number }}',
                                                            '{{ route('purchase.claim', $purchase->qr_code) }}',
                                                            {{ $purchase->total_items }},
                                                            '{{ $purchase->status }}',
                                                            {{ $isExpired ? 'true' : 'false' }},
                                                            '{{ $expiresAt->format('M d, Y h:i A') }}'
                                                        )">
                                                        <i class="ti-eye"></i> View QR
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            @elseif($isExpired && $purchase->status == 'Processing')
                                                <span class="text-danger"><i class="ti-alert"></i> Expired</span>
                                            @elseif($purchase->status == 'Claimed')
                                                <span class="text-success"><i class="ti-check"></i> Claimed</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No purchase orders found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('forms.purchase.add_purchase')
        
        {{-- QR Code Modal --}}
        <div class="modal fade" id="qrCodeModal" tabindex="-1" role="dialog" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="qrCodeModalLabel">QR Code - <span id="orderNumberDisplay"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div id="qrCodeDisplay" class="mb-3"></div>
                        <div class="mb-3">
                            <span id="qrCodeText" class="badge badge-info" style="font-size: 14px;"></span>
                        </div>
                        <div class="alert alert-info">
                            <strong>Total Items Purchased:</strong> <span id="totalItemsDisplay"></span>
                        </div>
                        <div id="statusAlert" style="display: none;"></div>
                        <div id="expiryInfo" style="display: none;" class="alert alert-warning">
                            <strong><i class="ti-time"></i> Expires At:</strong> <span id="expiryDate"></span><br>
                            <small class="text-muted">Must be claimed within 3 business days (excluding weekends)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('obScript')
<script>
// Force button to always be enabled
document.addEventListener('DOMContentLoaded', function() {
    const addPurchaseBtn = document.getElementById('addPurchaseBtn');
    if (addPurchaseBtn) {
        // Remove disabled attribute if it exists
        addPurchaseBtn.removeAttribute('disabled');
        // Ensure it's always clickable
        addPurchaseBtn.style.pointerEvents = 'auto';
        addPurchaseBtn.style.opacity = '1';
        
        // Re-attach the modal trigger
        addPurchaseBtn.setAttribute('data-toggle', 'modal');
        addPurchaseBtn.setAttribute('data-target', '#addPurchaseModal');
    }
});

function viewQRCode(orderNumber, qrUrl, totalItems, status, isExpired, expiryDate) {
    // Extract QR code from URL (last segment)
    const qrCode = qrUrl.split('/').pop();
    
    // Generate QR code image
    document.getElementById('qrCodeDisplay').innerHTML = '';
    const qrCodeDiv = document.createElement('div');
    
    const qrCodeImg = document.createElement('img');
    qrCodeImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(qrUrl)}`;
    qrCodeImg.alt = 'QR Code';
    qrCodeImg.style.maxWidth = '250px';
    qrCodeImg.style.border = '2px solid #ddd';
    qrCodeImg.style.padding = '10px';
    qrCodeImg.style.borderRadius = '8px';
    
    qrCodeDiv.appendChild(qrCodeImg);
    document.getElementById('qrCodeDisplay').appendChild(qrCodeDiv);
    
    // Update modal content
    document.getElementById('orderNumberDisplay').textContent = orderNumber;
    document.getElementById('qrCodeText').textContent = qrCode;
    document.getElementById('totalItemsDisplay').textContent = totalItems;
    
    // Show status alerts
    const statusAlert = document.getElementById('statusAlert');
    const expiryInfo = document.getElementById('expiryInfo');
    
    if (status === 'Claimed') {
        statusAlert.style.display = 'block';
        statusAlert.className = 'alert alert-success';
        statusAlert.innerHTML = '<strong><i class="ti-check"></i> This order has been claimed successfully!</strong>';
        expiryInfo.style.display = 'none';
    } else if (status === 'Forfeited' || isExpired) {
        statusAlert.style.display = 'block';
        statusAlert.className = 'alert alert-danger';
        statusAlert.innerHTML = '<strong><i class="ti-close"></i> This order has expired/forfeited and can no longer be claimed.</strong>';
        expiryInfo.style.display = 'none';
    } else if (status === 'Processing') {
        statusAlert.style.display = 'block';
        statusAlert.className = 'alert alert-warning';
        statusAlert.innerHTML = '<strong><i class="ti-time"></i> This order is pending claim.</strong>';
        expiryInfo.style.display = 'block';
        document.getElementById('expiryDate').textContent = expiryDate;
    } else {
        statusAlert.style.display = 'none';
        expiryInfo.style.display = 'none';
    }
    
    // Show modal
    $('#qrCodeModal').modal('show');
}

function approvePurchase(id) {
    Swal.fire({
        title: "Approve Purchase?",
        text: "This will mark the purchase as claimed.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: 'Yes, approve it!',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#57B657',
        cancelButtonColor: '#d33',
    }).then((result) => { 
        if (result.isConfirmed) {
            const loader = document.getElementById("loader");
            if (loader) loader.style.display = "block";
            
            $.ajax({
                url: "{{ url('purchases') }}/" + id + "/approve",
                method: "POST",
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (loader) loader.style.display = "none";
                    Swal.fire({
                        title: "Approved!",
                        text: response.message,
                        icon: "success"
                    }).then(function() { location.reload(); });
                },
                error: function(xhr) {
                    if (loader) loader.style.display = "none";
                    Swal.fire({
                        title: "Error!",
                        text: xhr.responseJSON?.message || "Failed to approve purchase. Please try again.",
                        icon: "error"
                    });
                }
            });
        }
    });
}

// Date validation
document.addEventListener('DOMContentLoaded', function() {
    const fromDate = document.querySelector('input[name="from"]');
    const toDate = document.querySelector('input[name="to"]');
    
    if (fromDate && toDate) {
        fromDate.addEventListener('change', function() {
            toDate.min = this.value;
        });
    }
});
</script>
@endsection