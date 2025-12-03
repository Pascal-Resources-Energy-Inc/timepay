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
                    <div class="alert alert-info" role="alert">
                        <i class="ti-info-alt"></i> <strong>Note:</strong> 
                        You have <strong id="itemsAlreadyPurchased">{{ $stats['total_items_sum'] ?? 0 }}</strong> items already purchased. 
                        You can order up to <strong id="remainingLimit">{{ 10 - ($stats['total_items_sum'] ?? 0) }}</strong> more items.
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0"><i class="ti-shopping-cart"></i> Discounted Products</h6>
                                <span class="badge badge-primary" id="totalItems">0/{{ 10 - ($stats['total_items_sum'] ?? 0) }} items</span>
                            </div>

                            <div id="mainProductsContainer">
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <button type="button" class="btn btn-outline-primary btn-block" 
                                    data-toggle="collapse" data-target="#additionalProductsCollapse">
                                    Show Additional Products <i class="ti-angle-down"></i>
                            </button>
                            
                            <div class="collapse mt-3" id="additionalProductsCollapse">
                                <h6 class="mb-3"><i class="ti-package"></i> Additional Products</h6>
                                <div id="additionalProductsContainer">
                                </div>
                            </div>
                        </div>
                    </div>

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

.product-item {
    transition: background-color 0.2s;
}

.product-item:hover {
    background-color: #f8f9fa;
}

.collapse-toggle {
    transition: transform 0.3s ease;
}

.collapsed .collapse-toggle {
    transform: rotate(180deg);
}
</style>

<script>
const ITEMS_ALREADY_PURCHASED = {{ $stats['total_items_sum'] ?? 0 }};
const MAX_TOTAL_ITEMS = 10;
const MAX_ALLOWED_ITEMS = MAX_TOTAL_ITEMS - ITEMS_ALREADY_PURCHASED;
let allProducts = [];
let productQuantities = {};

$(document).ready(function() {
    console.log('Document ready. Max allowed items:', MAX_ALLOWED_ITEMS);
    
    $('#addPurchaseModal').on('show.bs.modal', function () {
        console.log('Modal opening...');
        loadProducts();
    });
});

function loadProducts() {
    console.log('Loading products...');
    
    $('#mainProductsContainer').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div><p class="mt-2">Loading products...</p></div>');
    $('#additionalProductsContainer').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');
    
    const url = "{{ url('products/get') }}";
    console.log('AJAX URL:', url);
    
    $.ajax({
        url: url,
        type: "GET",
        dataType: 'json',
        cache: false,
        success: function(response) {
            console.log('SUCCESS! Response:', response);
            
            if (response.success && response.products && response.products.length > 0) {
                allProducts = response.products;
                console.log('Products loaded:', allProducts.length);
                renderProducts();
                updateTotal();
            } else {
                console.error('No products in response');
                showProductError('No products available');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', {xhr, status, error});
            let errorMsg = `Failed to load products. Status: ${xhr.status}`;
            if (xhr.status === 404) {
                errorMsg = 'Route not found. Check if products.get route exists.';
            }
            showProductError(errorMsg);
        }
    });
}

function showProductError(message) {
    const errorHtml = `<div class="alert alert-danger"><strong><i class="ti-alert"></i> Error:</strong> ${message}</div>`;
    $('#mainProductsContainer').html(errorHtml);
    $('#additionalProductsContainer').html('');
}

function renderProducts() {
    console.log('Rendering products...');
    
    const mainContainer = $('#mainProductsContainer');
    const additionalContainer = $('#additionalProductsContainer');
    
    mainContainer.empty();
    additionalContainer.empty();
    
    const mainProducts = allProducts.filter(p => p.is_main === true);
    const additionalProducts = allProducts.filter(p => p.is_main === false);
    
    console.log('Main products:', mainProducts.length);
    console.log('Additional products:', additionalProducts.length);
    
    if (mainProducts.length === 0) {
        mainContainer.html('<div class="alert alert-info">No main products</div>');
    } else {
        mainProducts.forEach((product, index) => {
            mainContainer.append(createProductHTML(product));
            if (index < mainProducts.length - 1) {
                mainContainer.append('<div class="border-bottom my-3"></div>');
            }
            productQuantities[product.id] = 0;
        });
    }
    
    if (additionalProducts.length === 0) {
        additionalContainer.html('<div class="alert alert-info">No additional products</div>');
    } else {
        additionalProducts.forEach((product, index) => {
            additionalContainer.append(createProductHTML(product));
            if (index < additionalProducts.length - 1) {
                additionalContainer.append('<div class="border-bottom my-3"></div>');
            }
            productQuantities[product.id] = 0;
        });
    }
    
    console.log('Products rendered successfully');
}

function createProductHTML(product) {
    const imageUrl = product.image 
        ? `{{ url('products') }}/${product.image}` 
        : `{{ asset('images/default-product.png') }}`;
    
    const isStoveProduct = product.name.includes('EAZY KALAN');
    
    return `
        <div class="product-item pb-3">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <div class="product-image bg-light rounded p-2">
                        <img src="${imageUrl}" alt="${product.name}" 
                             style="width: 55px; height: auto;" 
                             onerror="this.src='{{ asset('images/default-product.png') }}'">
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="mb-1">${product.name}</h6>
                    <p class="text-primary mb-0 font-weight-bold">₱ ${parseFloat(product.price).toFixed(2)}</p>
                    <small class="text-muted">Price per item</small>
                    ${isStoveProduct ? '<small class="d-block text-warning mt-1"><i class="ti-info-alt"></i> Note: Color of stove may vary</small>' : ''}
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-primary btn-sm" type="button" 
                                    onclick="decreaseQty(${product.id})">
                                <i class="ti-minus"></i>
                            </button>
                        </div>
                        <input type="number" class="form-control form-control-sm text-center" 
                               id="qty_${product.id}" value="0" min="0" max="10" 
                               data-price="${product.price}" 
                               data-deposit="${product.deposit || 0}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-sm" type="button" 
                                    onclick="increaseQty(${product.id})">
                                <i class="ti-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function increaseQty(productId) {
    console.log('Increase clicked for product:', productId);
    
    const product = allProducts.find(p => p.id === productId);
    
    if (product && product.is_main) {
        let mainProductTotal = 0;
        allProducts.forEach(p => {
            if (p.is_main && productQuantities[p.id]) {
                mainProductTotal += productQuantities[p.id];
            }
        });
        
        console.log('Main product total:', mainProductTotal, 'Max allowed:', MAX_ALLOWED_ITEMS);
        
        if (mainProductTotal >= MAX_ALLOWED_ITEMS) {
            Swal.fire({
                icon: 'warning',
                title: 'Maximum Limit Reached',
                html: `You have already purchased <strong>${ITEMS_ALREADY_PURCHASED}</strong> main product items this month.<br>` +
                      `You can only order <strong>${MAX_ALLOWED_ITEMS}</strong> more main product items to reach the limit of ${MAX_TOTAL_ITEMS} items.<br><br>` +
                      `<small class="text-muted">Note: Additional products (combo sets) do not count toward this limit and can be ordered freely.</small>`,
                confirmButtonColor: '#4B49AC'
            });
            return;
        }
    }
    
    const input = document.getElementById(`qty_${productId}`);
    if (input) {
        let value = parseInt(input.value) || 0;
        input.value = value + 1;
        productQuantities[productId] = value + 1;
        console.log('Updated quantity for product', productId, ':', productQuantities[productId]);
        updateTotal();
    }
}

function decreaseQty(productId) {
    const input = document.getElementById(`qty_${productId}`);
    if (input) {
        let value = parseInt(input.value) || 0;
        if (value > 0) {
            input.value = value - 1;
            productQuantities[productId] = value - 1;
            updateTotal();
        }
    }
}

function updateTotal() {
    let subtotal = 0;
    let totalItems = 0;
    let mainProductItems = 0;
    
    Object.keys(productQuantities).forEach(productId => {
        const qty = productQuantities[productId];
        const input = document.getElementById(`qty_${productId}`);
        const product = allProducts.find(p => p.id == productId);
        
        if (input && qty > 0) {
            const price = parseFloat(input.dataset.price) || 0;
            const deposit = parseFloat(input.dataset.deposit) || 0;
            subtotal += qty * (price + deposit);
            totalItems += qty;
            
            if (product && product.is_main) {
                mainProductItems += qty;
            }
        }
    });
    
    document.getElementById('subtotal').textContent = '₱ ' + subtotal.toFixed(2);
    document.getElementById('totalAmount').textContent = '₱ ' + subtotal.toFixed(2);
    
    document.getElementById('totalItems').textContent = mainProductItems + '/' + MAX_ALLOWED_ITEMS + ' main items';
    
    const badge = document.getElementById('totalItems');
    if (mainProductItems >= MAX_ALLOWED_ITEMS) {
        badge.classList.remove('badge-primary', 'badge-warning');
        badge.classList.add('badge-danger');
    } else if (mainProductItems >= MAX_ALLOWED_ITEMS * 0.8) {
        badge.classList.remove('badge-primary', 'badge-danger');
        badge.classList.add('badge-warning');
    } else {
        badge.classList.remove('badge-danger', 'badge-warning');
        badge.classList.add('badge-primary');
    }
    
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    if (totalItems > 0 && mainProductItems <= MAX_ALLOWED_ITEMS) {
        placeOrderBtn.disabled = false;
    } else {
        placeOrderBtn.disabled = true;
    }
}

$('#purchaseForm').on('submit', function(e) {
    e.preventDefault();
    
    const totalItems = Object.values(productQuantities).reduce((sum, qty) => sum + qty, 0);
    
    let mainProductItems = 0;
    allProducts.forEach(product => {
        if (product.is_main && productQuantities[product.id]) {
            mainProductItems += productQuantities[product.id];
        }
    });
    
    if (totalItems === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Items Selected',
            text: 'Please add at least one item to your cart.',
            confirmButtonColor: '#4B49AC'
        });
        return;
    }
    
    if (mainProductItems > MAX_ALLOWED_ITEMS) {
        Swal.fire({
            icon: 'error',
            title: 'Main Product Limit Exceeded',
            html: `You have already purchased <strong>${ITEMS_ALREADY_PURCHASED}</strong> main product items this month.<br>` +
                  `You can only order <strong>${MAX_ALLOWED_ITEMS}</strong> more main product items.`,
            confirmButtonColor: '#4B49AC'
        });
        return;
    }
    
    const totalAmount = document.getElementById('totalAmount').textContent;
    
    let itemsHtml = '<div class="text-left"><ul style="list-style: none; padding-left: 0;">';
    
    let hasMainProducts = false;
    allProducts.forEach(product => {
        if (product.is_main && productQuantities[product.id] > 0) {
            if (!hasMainProducts) {
                itemsHtml += '<li><strong>Main Products (Discounted LPG):</strong></li>';
                hasMainProducts = true;
            }
            itemsHtml += `<li class="ml-3">• ${product.name}: <strong>${productQuantities[product.id]} pcs</strong></li>`;
        }
    });
    
    let hasAdditionalProducts = false;
    allProducts.forEach(product => {
        if (!product.is_main && productQuantities[product.id] > 0) {
            if (!hasAdditionalProducts) {
                itemsHtml += '<li class="mt-2"><strong>Additional Products (No Limit):</strong></li>';
                hasAdditionalProducts = true;
            }
            itemsHtml += `<li class="ml-3">• ${product.name}: <strong>${productQuantities[product.id]} pcs</strong></li>`;
        }
    });
    
    itemsHtml += '</ul>';
    itemsHtml += `<p><strong>Main Product Items:</strong> ${mainProductItems} / ${MAX_TOTAL_ITEMS}</p>`;
    itemsHtml += `<p><strong>Total Amount:</strong> ${totalAmount}</p>`;
    itemsHtml += `<p class="text-muted" style="font-size: 0.9em;">After this order, you will have <strong>${ITEMS_ALREADY_PURCHASED + mainProductItems}</strong> out of ${MAX_TOTAL_ITEMS} main product items this month.</p></div>`;
    
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
            submitOrder();
        }
    });
});

function submitOrder() {
    const loader = document.getElementById("loader");
    if (loader) loader.style.display = "block";
    
    const subtotalValue = parseFloat($('#subtotal').text().replace('₱ ', '').replace(',', ''));
    const totalAmountValue = parseFloat($('#totalAmount').text().replace('₱ ', '').replace(',', ''));
    const totalItems = Object.values(productQuantities).reduce((sum, qty) => sum + qty, 0);
    
    const products = [];
    Object.keys(productQuantities).forEach(productId => {
        const qty = productQuantities[productId];
        if (qty > 0) {
            products.push({
                product_id: parseInt(productId),
                quantity: qty
            });
        }
    });
    
    $.ajax({
        url: "{{ route('purchases.store') }}",
        method: "POST",
        data: {
            products: products,
            total_items: totalItems,
            subtotal: subtotalValue,
            discount: 0,
            total_amount: totalAmountValue,
            payment_method: 'Cash',
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (loader) loader.style.display = "none";
            
            Swal.fire({
                icon: 'success',
                title: 'Order Placed Successfully!',
                text: response.message,
                confirmButtonColor: '#4B49AC'
            }).then(() => {
                $('#addPurchaseModal').modal('hide');
                resetForm();
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

function resetForm() {
    $('#purchaseForm')[0].reset();
    productQuantities = {};
    allProducts.forEach(product => {
        const input = document.getElementById(`qty_${product.id}`);
        if (input) {
            input.value = 0;
        }
        productQuantities[product.id] = 0;
    });
    updateTotal();
}

if (MAX_ALLOWED_ITEMS <= 0) {
    $('[data-target="#addPurchaseModal"]').prop('disabled', true)
        .attr('title', 'You have reached the maximum limit of 10 main product items this month');
}
</script>