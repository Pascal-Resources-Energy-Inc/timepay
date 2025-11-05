@extends('layouts.header')

@section('content')
@php
use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

<div class="main-panel">
    <div class="content-wrapper">
        <div class='row grid-margin'>
            <div class='col-lg-2 '>
                <div class="card card-tale">
                    <div class="card-body">
                        <div class="media">                
                            <div class="media-body">
                                <h4 class="mb-4">Total Purchase</h4>
                                <h2 class="card-text">{{ $stats['total_purchase'] ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            <div class='col-lg-2'>
                <div class="card card-light-danger">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body">
                                <h5 class="mb-4">Total This Month</h5>
                                <h2 class="card-text">{{ $stats['total_this_month'] ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-lg-2'>
                <div class="card text-success">
                    <div class="card-body">
                        <div class="media">                
                            <div class="media-body">
                                <h4 class="mb-4">Remaining</h4>
                                <h2 class="card-text">{{ $stats['remaining'] ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
        {{-- Place Order Section --}}
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Place Order</h4>
                        <p class="card-description">
                            <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#addPurchaseModal">
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
                                        <th>Employee #</th>
                                        <th>Employee Name</th>
                                        <th>Total Items</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>QR Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchases ?? [] as $purchase)
                                    <tr>
                                        <td>{{ date('M. d, Y', strtotime($purchase->created_at)) }}</td>
                                        <td>{{ $purchase->order_number }}</td>
                                        <td>{{ $purchase->employee_number ?? 'N/A' }}</td>
                                        <td>{{ $purchase->employee_name ?? 'N/A' }}</td>
                                        <td>{{ $purchase->total_items }}</td>
                                        <td>₱ {{ number_format($purchase->total_amount, 2) }}</td>
                                        <td>
                                            @if($purchase->status == 'Claimed')
                                                <label class="badge badge-success">Claimed</label>
                                            @elseif($purchase->status == 'Processing')
                                                <label class="badge badge-warning">Processing</label>
                                            @elseif($purchase->status == 'Forfeited')
                                                <label class="badge badge-danger">Forfeited</label>
                                            @else
                                                <label class="badge badge-secondary">{{ $purchase->status }}</label>
                                            @endif
                                        </td>
                                        <td>
                                            @if($purchase->qr_code)
                                                <div class="qr-code-container" style="text-align: center;">
                                                    {!! QrCode::size(80)->generate($purchase->qr_code) !!}
                                                    <br>
                                                </div>
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
    </div>
</div>
@endsection

@section('obScript')
<script>
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

function cancelPurchase(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to cancel this purchase?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: 'Yes, cancel it!',
        cancelButtonText: 'No, keep it',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
    }).then((result) => { 
        if (result.isConfirmed) {
            const loader = document.getElementById("loader");
            if (loader) loader.style.display = "block";
            
            $.ajax({
                url: "{{ url('disable-planning') }}/" + id,
                method: "GET",
                data: { id: id },
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(data) {
                    if (loader) loader.style.display = "none";
                    Swal.fire({
                        title: "Cancelled!",
                        text: "Purchase has been cancelled!",
                        icon: "success"
                    }).then(function() { location.reload(); });
                },
                error: function(xhr, status, error) {
                    if (loader) loader.style.display = "none";
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to cancel purchase. Please try again.",
                        icon: "error"
                    });
                }
            });
        } else {
            Swal.fire({ text: "Purchase cancellation was stopped.", icon: "info" });
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