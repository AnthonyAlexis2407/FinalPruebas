@extends('layouts.app')

@section('page_title', 'Punto de Venta')

@section('content')
    <div class="row" id="pos-app">
        <!-- Header Controls -->
        <div class="col-12 mb-4">
            <div class="card p-3 border-0 shadow-sm">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="text-muted mb-0 small uppercase fw-bold">Búsqueda Rápida</p>
                    </div>
                    <div class="col-auto">
                        <div class="input-group" style="width: 350px;">
                            <span class="input-group-text bg-light border-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control bg-light border-0 ps-0"
                                placeholder="Nombre o código..." id="search-product" autocomplete="off">
                            <button class="btn btn-light border-0 text-muted d-none" type="button" id="btn-clear-search">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-lg-8">
            <div class="row g-3" id="product-list">
                <!-- No Results State (Hidden by default) -->
                <div id="no-results" class="col-12 text-center py-5 d-none">
                    <div class="mb-3">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-search display-6 text-muted opacity-50"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-muted">No encontramos productos</h5>
                    <p class="text-muted small">Intenta buscar con otro término o código.</p>
                </div>

                @foreach ($products as $product)
                    @php
                        $hasStock = $product->sizes->sum('stock') > 0;
                    @endphp
                    <div class="col-md-4 col-sm-6 product-card" data-name="{{ strtolower($product->name) }}"
                        data-code="{{ strtolower($product->code) }}">
                        <div
                            class="card h-100 border-0 shadow-sm hover-shadow transition-all {{ !$hasStock ? 'opacity-75' : '' }}">
                            <div class="card-body p-3 position-relative">
                                @if (!$hasStock)
                                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                                        style="background: rgba(255, 255, 255, 0.6); z-index: 10;">
                                        <span class="badge bg-danger transform rotate-45 shadow-sm px-3 py-2">AGOTADO</span>
                                    </div>
                                @endif

                                <div class="d-flex gap-3">
                                    @if ($product->image_path)
                                        <img src="{{ asset('storage/' . $product->image_path) }}" class="rounded-3"
                                            alt="{{ $product->name }}"
                                            style="width: 70px; height: 70px; object-fit: cover; {{ !$hasStock ? 'filter: grayscale(100%);' : '' }}">
                                    @else
                                        <div class="rounded-3 bg-light d-flex align-items-center justify-content-center"
                                            style="width: 70px; height: 70px;">
                                            <i class="bi bi-cup-straw text-muted fs-4"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h6 class="fw-bold text-truncate mb-1" title="{{ $product->name }}">
                                            {{ $product->name }}</h6>
                                        <p class="text-primary fw-bold mb-2">S/. {{ number_format($product->price, 2) }}</p>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <select class="form-select form-select-sm size-selector mb-2 bg-light border-0"
                                        {{ !$hasStock ? 'disabled' : '' }}>
                                        <option value="">
                                            {{ $hasStock ? 'Seleccionar Envase/Tamaño' : 'Sin Stock Disponible' }}
                                        </option>
                                        @foreach ($product->sizes as $productSize)
                                            <option value="{{ $productSize->id }}" data-stock="{{ $productSize->stock }}"
                                                {{ $productSize->stock <= 0 ? 'disabled' : '' }}>
                                                {{ $productSize->size->number ?? $productSize->name }}
                                                {{ $productSize->stock <= 0 ? '(Agotado)' : '(Stock: ' . $productSize->stock . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary btn-sm w-100 add-to-cart rounded-pill"
                                        data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                        data-price="{{ $product->price }}" {{ !$hasStock ? 'disabled' : '' }}>
                                        <i class="bi bi-cart-plus me-1"></i> Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Cart -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg sticky-top" style="top: 100px; border-radius: 20px;">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-0">Ticket de Venta</h5>
                </div>
                <div class="card-body px-4">
                    <div class="table-responsive my-3" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-borderless align-middle">
                            <thead class="text-muted small text-uppercase">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cant</th>
                                    <th class="text-end">Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cart-items">
                                <!-- Items appear here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div id="empty-cart" class="text-center py-5 bg-light rounded-3 mb-3">
                        <i class="bi bi-basket display-4 text-muted mb-2 d-block"></i>
                        <p class="text-muted mb-0 small">El carrito está vacío</p>
                    </div>

                    <hr class="border-secondary border-opacity-10">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="text-muted">Total a Pagar</span>
                        <span class="h3 fw-bold mb-0 text-primary" id="cart-total">S/. 0.00</span>
                    </div>

                    <button id="btn-checkout"
                        class="btn btn-dark w-100 py-3 rounded-xl fw-bold d-flex align-items-center justify-content-center gap-2 mb-2"
                        disabled>
                        <i class="bi bi-credit-card"></i> Procesar Pago
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Options Modal -->
    <div class="modal fade" id="printModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-check-circle-fill me-2"></i>Venta Exitosa</h5>
                </div>
                <div class="modal-body text-center p-5">
                    <h4 class="mb-4">¿Qué comprobante deseas imprimir?</h4>
                    <div class="d-grid gap-3 col-8 mx-auto">
                        <a href="#" id="btn-print-ticket"
                            class="btn btn-outline-dark btn-lg py-3 rounded-pill fw-bold" target="_blank">
                            <i class="bi bi-receipt me-2"></i> Ticket (80mm)
                        </a>
                        <a href="#" id="btn-print-a4"
                            class="btn btn-outline-primary btn-lg py-3 rounded-pill fw-bold" target="_blank">
                            <i class="bi bi-file-earmark-pdf me-2"></i> Factura A4
                        </a>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-link text-muted text-decoration-none"
                        onclick="location.reload()">
                        Omitir y Nueva Venta
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let cart = [];

            // Normalized Search Function
            function normalizeText(text) {
                return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            }

            // Search Logic
            $('#search-product').on('input', function() {
                let rawVal = $(this).val();
                let val = normalizeText(rawVal);
                let hasResults = false;

                // Toggle Clear Button
                if (rawVal.length > 0) {
                    $('#btn-clear-search').removeClass('d-none');
                } else {
                    $('#btn-clear-search').addClass('d-none');
                }

                $('.product-card').each(function() {
                    let name = normalizeText($(this).data('name'));
                    let code = normalizeText($(this).data('code').toString());

                    if (name.includes(val) || code.includes(val)) {
                        $(this).show();
                        hasResults = true;
                    } else {
                        $(this).hide();
                    }
                });

                // Show/Hide No Results
                if (hasResults) {
                    $('#no-results').addClass('d-none');
                } else {
                    $('#no-results').removeClass('d-none');
                }
            });

            // Clear Search
            $('#btn-clear-search').click(function() {
                $('#search-product').val('').trigger('input').focus();
            });

            // Add to Cart
            $('.add-to-cart').click(function() {
                let card = $(this).closest('.card-body');
                let sizeSelect = card.find('.size-selector');
                let productSizeId = sizeSelect.val();
                let sizeName = sizeSelect.find('option:selected').text();

                if (!productSizeId) {
                    alert("⚠️ Selecciona una presentación primero");
                    return;
                }

                let maxStock = parseInt(sizeSelect.find('option:selected').data('stock'));
                let productId = $(this).data('id');
                let name = $(this).data('name');
                let price = parseFloat($(this).data('price'));

                let existing = cart.find(i => i.productSizeId === productSizeId);

                if (existing) {
                    if (existing.quantity < maxStock) {
                        existing.quantity++;
                    } else {
                        alert("🚫 Stock insuficiente para agregar más items.");
                        return;
                    }
                } else {
                    cart.push({
                        productSizeId: productSizeId,
                        name: name,
                        sizeLabel: sizeName.split('(')[0].trim(),
                        price: price,
                        quantity: 1,
                        maxStock: maxStock
                    });
                }
                renderCart();
            });

            // Remove item
            $(document).on('click', '.remove-item', function() {
                let id = $(this).data('id');
                cart = cart.filter(i => i.productSizeId != id);
                renderCart();
            });

            // Increment Quantity
            $(document).on('click', '.qty-inc', function() {
                let id = $(this).data('id');
                let item = cart.find(i => i.productSizeId == id);
                if (item) {
                    if (item.quantity < item.maxStock) {
                        item.quantity++;
                        renderCart();
                    } else {
                        alert("🚫 No puedes agregar más de este producto (Stock limitado).");
                    }
                }
            });

            // Decrement Quantity
            $(document).on('click', '.qty-dec', function() {
                let id = $(this).data('id');
                let item = cart.find(i => i.productSizeId == id);
                if (item) {
                    if (item.quantity > 1) {
                        item.quantity--;
                        renderCart();
                    } else {
                        // Optional: Confirm removal or just stop at 1? User probably expects removal if < 1
                        // But usually (-) stops at 1 and trash icon removes. Let's stop at 1.
                    }
                }
            });

            function renderCart() {
                let tbody = $('#cart-items');
                tbody.empty();
                let total = 0;

                if (cart.length === 0) {
                    $('#empty-cart').removeClass('d-none');
                    $('#btn-checkout').prop('disabled', true);
                } else {
                    $('#empty-cart').addClass('d-none');
                    $('#btn-checkout').prop('disabled', false);
                    cart.forEach(item => {
                        let subtotal = item.price * item.quantity;
                        total += subtotal;
                        tbody.append(`
                    <tr>
                        <td style="width: 40%">
                            <div class="fw-bold text-dark text-truncate" style="max-width: 120px;" title="${item.name}">${item.name}</div>
                            <small class="text-muted">Presentación: ${item.sizeLabel}</small>
                        </td>
                        <td class="text-center" style="width: 30%">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                <button class="btn btn-xs btn-outline-secondary qty-dec py-0 px-1" data-id="${item.productSizeId}"><i class="bi bi-dash"></i></button>
                                <span class="badge bg-light text-dark border">${item.quantity}</span>
                                <button class="btn btn-xs btn-outline-primary qty-inc py-0 px-1" data-id="${item.productSizeId}"><i class="bi bi-plus"></i></button>
                            </div>
                        </td>
                        <td class="text-end fw-bold" style="width: 20%">S/. ${subtotal.toFixed(2)}</td>
                        <td class="text-end" style="width: 10%">
                            <button class="btn btn-link text-danger p-0 opacity-50 hover-opacity-100 remove-item" data-id="${item.productSizeId}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
                    });
                }
                $('#cart-total').text('S/. ' + total.toFixed(2));
            }

            // Checkout
            $('#btn-checkout').click(function() {
                if (!confirm('¿Confirmar venta por ' + $('#cart-total').text() + '?')) return;

                let items = cart.map(i => ({
                    product_size_id: i.productSizeId,
                    quantity: i.quantity
                }));

                $.ajax({
                    url: "{{ route('sales.store') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        items: items
                    },
                    success: function(res) {
                        $('#btn-print-ticket').attr('href', res.ticket_url);
                        $('#btn-print-a4').attr('href', res.a4_url);

                        var printModal = new bootstrap.Modal(document.getElementById('printModal'));
                        printModal.show();

                        cart = [];
                        renderCart();
                    },
                    error: function(err) {
                        alert('❌ Error: ' + (err.responseJSON.message || 'Error desconocido'));
                    }
                });
            });
        </script>
        <style>
            .rounded-xl {
                border-radius: 12px;
            }

            .transition-all {
                transition: all 0.2s ease;
            }

            .hover-shadow:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
            }
        </style>
    @endpush
@endsection
