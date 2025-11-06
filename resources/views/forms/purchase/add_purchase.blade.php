<!-- Add Purchase Modal -->
<div class="modal fade" id="addPurchaseModal" tabindex="-1" role="dialog" aria-labelledby="addPurchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-black">
                <h5 class="modal-title" id="addPurchaseModalLabel">
                    <i class="ti-shopping-cart"></i> Confirm Order
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="purchaseForm">
                @csrf
                <div class="modal-body">
                    <!-- Dynamic Limit Warning -->
                    <div class="alert alert-info" role="alert">
                        <i class="ti-info-alt"></i> <strong>Note:</strong> 
                        You have <strong id="itemsAlreadyPurchased">{{ $stats['total_items_sum'] ?? 0 }}</strong> items already purchased. 
                        You can order up to <strong id="remainingLimit">{{ 10 - ($stats['total_items_sum'] ?? 0) }}</strong> more items.
                    </div>

                    <!-- Cart Items -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0"><i class="ti-shopping-cart"></i> Cart Items</h6>
                                <span class="badge badge-primary" id="totalItems">0/{{ 10 - ($stats['total_items_sum'] ?? 0) }} items</span>
                            </div>

                            <!-- Product 1: 330g LPG Cylinder -->
                            <div class="product-item border-bottom pb-3 mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center">
                                        <div class="product-image bg-light rounded p-2">
                                            <img src="{{ asset('images/330g.png') }}" alt="330g LPG Cylinder" style="width: 55px; height: auto;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-1">330g LPG Cylinder - Refill</h6>
                                        <p class="text-primary mb-0 font-weight-bold">₱ 57.00</p>
                                        <small class="text-muted">Price per item</small>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-primary btn-sm" type="button" onclick="decreaseQty('qty330')">
                                                    <i class="ti-minus"></i>
                                                </button>
                                            </div>
                                            <input type="number" class="form-control form-control-sm text-center" 
                                                   id="qty330" name="qty_330g" value="0" min="0" max="10" 
                                                   onchange="updateTotal()" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary btn-sm" type="button" onclick="increaseQty('qty330')">
                                                    <i class="ti-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" id="price330" value="57.00">
                                    </div>
                                </div>
                            </div>

                            <!-- Product 2: 230g LPG Cylinder -->
                            <div class="product-item">
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center">
                                        <div class="product-image bg-light rounded p-2">
                                            <img src="{{ asset('images/230g.png') }}" alt="230g LPG Cylinder" style="width: 55px; height: auto;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-1">230g LPG Cylinder - Refill</h6>
                                        <p class="text-primary mb-0 font-weight-bold">₱ 40.00</p>
                                        <small class="text-muted">Price per item</small>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-primary btn-sm" type="button" onclick="decreaseQty('qty230')">
                                                    <i class="ti-minus"></i>
                                                </button>
                                            </div>
                                            <input type="number" class="form-control form-control-sm text-center" 
                                                   id="qty230" name="qty_230g" value="0" min="0" max="10" 
                                                   onchange="updateTotal()" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary btn-sm" type="button" onclick="increaseQty('qty230')">
                                                    <i class="ti-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" id="price230" value="40.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="mb-3"><i class="ti-receipt"></i> Order Summary</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotal">₱ 0.00</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total Amount:</strong>
                                <strong class="text-primary" id="totalAmount">₱ 0.00</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="ti-close"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="placeOrderBtn" disabled>
                        <i class="ti-shopping-cart"></i> Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.product-image {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.input-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

.input-group-sm input {
    max-width: 60px;
}

.card {
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
</style>

<script>
// Get the already purchased items count from the server
const ITEMS_ALREADY_PURCHASED = {{ $stats['total_items_sum'] ?? 0 }};
const MAX_TOTAL_ITEMS = 10;
const MAX_ALLOWED_ITEMS = MAX_TOTAL_ITEMS - ITEMS_ALREADY_PURCHASED;

function increaseQty(inputId) {
    const input = document.getElementById(inputId);
    const qty330 = parseInt(document.getElementById('qty330').value) || 0;
    const qty230 = parseInt(document.getElementById('qty230').value) || 0;
    const currentTotal = qty330 + qty230;
    
    // Check against remaining limit
    if (currentTotal >= MAX_ALLOWED_ITEMS) {
        Swal.fire({
            icon: 'warning',
            title: 'Maximum Limit Reached',
            html: `You have already purchased <strong>${ITEMS_ALREADY_PURCHASED}</strong> items.<br>` +
                  `You can only order <strong>${MAX_ALLOWED_ITEMS}</strong> more items to reach the limit of ${MAX_TOTAL_ITEMS} items.`,
            confirmButtonColor: '#4B49AC'
        });
        return;
    }
    
    let value = parseInt(input.value);
    input.value = value + 1;
    updateTotal();
}

function decreaseQty(inputId) {
    const input = document.getElementById(inputId);
    let value = parseInt(input.value);
    if (value > 0) {
        input.value = value - 1;
        updateTotal();
    }
}

function updateTotal() {
    // Get quantities
    const qty330 = parseInt(document.getElementById('qty330').value) || 0;
    const qty230 = parseInt(document.getElementById('qty230').value) || 0;
    
    // Get prices
    const price330 = parseFloat(document.getElementById('price330').value);
    const price230 = parseFloat(document.getElementById('price230').value);
    
    // Calculate totals
    const total330 = qty330 * price330;
    const total230 = qty230 * price230;
    const subtotal = total330 + total230;
    const totalAmount = subtotal;
    
    // Update display
    document.getElementById('subtotal').textContent = '₱ ' + subtotal.toFixed(2);
    document.getElementById('totalAmount').textContent = '₱ ' + totalAmount.toFixed(2);
    
    // Update total items with dynamic limit
    const totalItems = qty330 + qty230;
    document.getElementById('totalItems').textContent = totalItems + '/' + MAX_ALLOWED_ITEMS + ' item' + (totalItems !== 1 ? 's' : '');
    
    // Change badge color based on limit
    const badge = document.getElementById('totalItems');
    if (totalItems >= MAX_ALLOWED_ITEMS) {
        badge.classList.remove('badge-primary', 'badge-warning');
        badge.classList.add('badge-danger');
    } else if (totalItems >= MAX_ALLOWED_ITEMS * 0.8) {
        badge.classList.remove('badge-primary', 'badge-danger');
        badge.classList.add('badge-warning');
    } else {
        badge.classList.remove('badge-danger', 'badge-warning');
        badge.classList.add('badge-primary');
    }
    
    // Enable/disable place order button
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    if (totalItems > 0 && totalItems <= MAX_ALLOWED_ITEMS) {
        placeOrderBtn.disabled = false;
    } else {
        placeOrderBtn.disabled = true;
    }
}

// Form submission
document.getElementById('purchaseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const qty330 = parseInt(document.getElementById('qty330').value) || 0;
    const qty230 = parseInt(document.getElementById('qty230').value) || 0;
    const totalItems = qty330 + qty230;
    
    if (totalItems === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Items Selected',
            text: 'Please add at least one item to your cart.',
            confirmButtonColor: '#4B49AC'
        });
        return;
    }
    
    // Validate against remaining limit
    if (totalItems > MAX_ALLOWED_ITEMS) {
        Swal.fire({
            icon: 'error',
            title: 'Limit Exceeded',
            html: `You have already purchased <strong>${ITEMS_ALREADY_PURCHASED}</strong> items.<br>` +
                  `You can only order <strong>${MAX_ALLOWED_ITEMS}</strong> more items.`,
            confirmButtonColor: '#4B49AC'
        });
        return;
    }
    
    const totalAmount = document.getElementById('totalAmount').textContent;
    
    let itemsHtml = '<div class="text-left"><ul style="list-style: none; padding-left: 0;">';
    if (qty330 > 0) {
        itemsHtml += `<li>• 330g LPG Cylinder: <strong>${qty330} pcs</strong></li>`;
    }
    if (qty230 > 0) {
        itemsHtml += `<li>• 230g LPG Cylinder: <strong>${qty230} pcs</strong></li>`;
    }
    itemsHtml += '</ul>';
    itemsHtml += `<p><strong>Total Items:</strong> ${totalItems}</p>`;
    itemsHtml += `<p><strong>Total Amount:</strong> ${totalAmount}</p>`;
    itemsHtml += `<p class="text-muted" style="font-size: 0.9em;">After this order, you will have <strong>${ITEMS_ALREADY_PURCHASED + totalItems}</strong> out of ${MAX_TOTAL_ITEMS} items.</p></div>`;
    
    Swal.fire({
        title: 'Confirm Order',
        html: itemsHtml,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4B49AC',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Place Order!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loader
            const loader = document.getElementById("loader");
            if (loader) loader.style.display = "block";
            
            // Prepare data
            const subtotalValue = parseFloat(document.getElementById('subtotal').textContent.replace('₱ ', '').replace(',', ''));
            const totalAmountValue = parseFloat(document.getElementById('totalAmount').textContent.replace('₱ ', '').replace(',', ''));
            
            const formData = {
                qty_330g: qty330,
                qty_230g: qty230,
                total_items: totalItems,
                subtotal: subtotalValue,
                discount: 0,
                total_amount: totalAmountValue,
                payment_method: 'Cash',
                _token: '{{ csrf_token() }}'
            };

            // Submit via AJAX
            $.ajax({
                url: "{{ route('purchases.store') }}",
                method: "POST",
                data: formData,
                success: function(response) {
                    if (loader) loader.style.display = "none";
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Placed Successfully!',
                        text: response.message,
                        confirmButtonColor: '#4B49AC'
                    }).then(() => {
                        $('#addPurchaseModal').modal('hide');
                        document.getElementById('purchaseForm').reset();
                        document.getElementById('qty330').value = 0;
                        document.getElementById('qty230').value = 0;
                        updateTotal();
                        location.reload();
                    });
                },
                error: function(xhr) {
                    if (loader) loader.style.display = "none";
                    
                    let errorMessage = 'Failed to place order. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage,
                        confirmButtonColor: '#4B49AC'
                    });
                }
            });
        }
    });
});

// Initialize on modal open
$('#addPurchaseModal').on('shown.bs.modal', function () {
    updateTotal();
});

// Check if user has reached the limit on page load
if (MAX_ALLOWED_ITEMS <= 0) {
    // Disable the add purchase button if it exists
    const addPurchaseBtn = document.querySelector('[data-target="#addPurchaseModal"]');
    if (addPurchaseBtn) {
        addPurchaseBtn.disabled = true;
        addPurchaseBtn.title = 'You have reached the maximum limit of 10 items';
    }
}
</script>