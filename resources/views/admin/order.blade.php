@extends('admin.layout')
@section('title','Admin Order')
@section('content')
@include('admin.css.order-system-styles')

<script>
    const existingOrderId = "{{ $order_id ?? '' }}" || null;
</script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    #add_new_item_btn {
        width: 100%;
    }

    .cl {
        color: white;
    }

    /* Ensure the print modal is hidden normally */
    #printContent {
        display: none;
    }

    @media print {

        /* Hide everything by default */
        body * {
            display: none !important;
        }

        /* Show ONLY the print area and its children */
        .print-area,
        .print-area * {
            display: block !important;
            visibility: visible !important;
        }

        .print-area {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .ticket {
            width: 100%;
            /* Force the break */
            page-break-after: always !important;
            break-after: page !important;
            /* Remove margins that cause the "middle" appearance */
            margin: 0 0 20px 0 !important;
            padding-top: 5px !important;
            border-bottom: 1px dashed #ccc;
            /* optional visual separator */
        }

        .center {
            text-align: center;
            width: 100%;
        }

        .row-line {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        /* Remove any headers/footers the browser adds */
        @page {
            margin: 0;
            size: auto;
        }
    }

    .error-msg {
        font-size: 0.90rem;
        color: #f60000;
        /* red */
        margin-top: 2px;
        padding: 8px;
        display: none;
        font-weight: bolder;
        background-color: white;
        border-radius: 10px;
    }

    .menu-card {
        position: relative;
        /* Needed for positioning the badge */
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 15px;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .menu-card .badge {
        position: absolute;
        /* Position relative to the menu-card */
        top: 5px;
        right: 5px;
        font-size: 0.8rem;
        padding: 0.25em 0.5em;
        border-radius: 25%;
        background-color: #4b4b4b;
        /* Bootstrap info color */
        color: white;
    }
</style>
<div class="order-system-wrapper">

    <div class="order-three-column">

        <div class="categories-card">
            <div class="categories-header">
                <i class="icon-list"></i> Categories
            </div>

            <div class="category-items">
                @foreach($categories as $cat)
                <button class="category-btn"
                    data-cat-id="{{ $cat->id }}">
                    {{ $cat->name }}
                </button>
                @endforeach
            </div>
        </div>

        <div>
            <div class="menu-header">
                <h4 id="activeCategoryTitle">All Items</h4>
                <span class="menu-count-badge" id="menuCount">0 items</span>
            </div>
            <div class="menu-grid" id="menuGrid">

            </div>
        </div>

        <div class="order-card">
            <div class="order-content">
                <h5 class="order-title">
                    <i class="icon-basket"></i> Current Order
                </h5>

                <div class="order-fields">

                    <!-- Normal Order Fields -->
                    <!-- <div class="field-group">
                        <label>Table No.</label>
                        <input type="number" id="table_no" value="{{ $table_no ?? '' }}" required>
                    </div> -->
                    <div class="field-group">
                        <label>Table No.</label>
                        <input type="number" id="table_no" value="{{ $table_no ?? '' }}" min="1" required>

                    </div>

                    <div class="field-group">
                        <label>Select Waiter</label>
                        <select name="waiter_id" style="border: 1px solid #fefefe; border-radius:5px;background:rgb(103, 160, 239);color:white"class="form-control mb-8 mb-8" id="waiter_id" required>
                            <option value="" disabled selected>Select Waiter</option>
                            @foreach($waiter as $user)
                            <option class="" value="{{$user->id}}" {{ ($waiter_id ?? null) == $user->id ? 'selected' : '' }}>
                                {{$user->name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <span class="error-msg text-red-500 text-sm" id="table_no_error" style="display:none;"></span>

                </div>

                <div class="ordered-items-container" id="orderedItems">
                </div>

                <div class="total-section">
                    <span>Total</span>
                    <span id="totalAmount">BR 0</span>
                </div>
            </div>

            <button class="place-order-btn">
                <i class="icon-check"></i> Place Order
            </button>
        </div>
    </div>

</div>
<div class="modal fade" id="printModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body print-area" id="printContent">
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="window.print()">Print</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // document.querySelector('.place-order-btn').addEventListener('click', placeOrder);

        const categoryButtons = document.querySelectorAll('.category-btn');
        const menuGrid = document.getElementById('menuGrid');
        const menuCount = document.getElementById('menuCount');
        const activeTitle = document.getElementById('activeCategoryTitle');

        // Automatically load first category on page load
        const firstCategoryBtn = document.querySelector('.category-btn');
        if (firstCategoryBtn) {
            const catId = firstCategoryBtn.dataset.catId;
            const catName = firstCategoryBtn.innerText;
            activeTitle.innerText = catName;
            loadCategoryProducts(catId);
        }
        categoryButtons.forEach(btn => {
            btn.addEventListener('click', function() {

                const catId = this.dataset.catId;
                const catName = this.innerText;

                activeTitle.innerText = catName;

                loadCategoryProducts(catId);
            });
        });


        function loadCategoryProducts(catId) {

            fetch(`/products/category/${catId}`)
                .then(res => res.json())
                .then(products => {

                    const menuGrid = document.getElementById('menuGrid');
                    const menuCount = document.getElementById('menuCount');

                    if (!products || products.length === 0) {
                        menuGrid.innerHTML = `<p style="opacity:.6">No items found</p>`;
                        menuCount.innerText = `0 items`;
                        return;
                    }

                    menuCount.innerText = `${products.length} items`;

                    menuGrid.innerHTML = products.map(product => {

                        return `
                    <div class="menu-card" 
                        data-id="${product.id}"
                        data-name="${product.name}"
                        data-price="${product.price}">
                        ${product.current_bar_stock !== undefined ? `<span class="badge badge-info">${product.current_bar_stock ?? 0}</span>` : ''}
                        <div class="menu-name">${product.name}</div>
                        <div class="menu-price">Br ${Number(product.price).toLocaleString()}</div>
                    </div>
                `;

                    }).join('');

                    attachMenuClickEvents();

                })
                .catch(err => console.error(err));
        }

        function attachMenuClickEvents() {

            const cards = document.querySelectorAll('.menu-card');

            cards.forEach(card => {

                card.addEventListener('click', function() {

                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const price = parseFloat(this.dataset.price);

                    addToOrder(id, name, price);

                });

            });

        }
    });
    let orderItems = [];
    document.addEventListener('DOMContentLoaded', function() {

        // Preload existing items
        // @if(!empty($existingItemsForJs))
        //     orderItems = @json($existingItemsForJs);
        // @endif

        renderOrderCart();

        // Load first category automatically
        const firstCategoryBtn = document.querySelector('.category-btn');
        if (firstCategoryBtn) {
            const catId = firstCategoryBtn.dataset.catId;
            const catName = firstCategoryBtn.innerText;
            document.getElementById('activeCategoryTitle').innerText = catName;
            loadCategoryProducts(catId);
        }

        // Category buttons
        const categoryButtons = document.querySelectorAll('.category-btn');
        categoryButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const catId = this.dataset.catId;
                const catName = this.innerText;
                document.getElementById('activeCategoryTitle').innerText = catName;
                loadCategoryProducts(catId);
            });
        });

    });
    renderOrderCart();

    function addToOrder(id, name, price, ) {
        // Make sure id is string (for 422 error)
        id = id.toString();

        // Check if item already exists in the cart
        const existingItem = orderItems.find(item => item.id === id);

        if (existingItem) {
            // Increase quantity by 1
            existingItem.quantity += 1;
        } else {
            // Add new item
            orderItems.push({
                id: id,
                name: name,
                price: Number(price),
                quantity: 1,
                product_type: 'menu', // default
                to_make: 1 // default kitchen flag
            });
        }

        // Re-render cart
        renderOrderCart();
    }

    function removeFromOrder(itemId) {
        orderItems = orderItems.filter(item => item.id !== itemId);
        renderOrderCart();
    }

    function updateQuantity(id, input) {

        let value = input.value;

        // Allow empty while typing
        if (value === "") return;

        value = parseInt(value);

        if (isNaN(value) || value < 1) {
            input.value = 1;
            value = 1;
        }

        let item = orderItems.find(i => i.id == id);
        if (!item) return;

        item.quantity = value;

        //  Update only THIS subtotal
        const subtotal = item.price * item.quantity;
        const subtotalEl = document.getElementById(`subtotal-${id}`);

        if (subtotalEl) {
            subtotalEl.innerText = "Br " + subtotal.toLocaleString('id-ID');
        }

        //  Update total only
        updateTotalOnly();
    }

    function updateTotalOnly() {
        const totalAmountSpan = document.getElementById('totalAmount');
        if (totalAmountSpan) {
            const total = orderItems.reduce((sum, item) => {
                return sum + (Number(item.price) * Number(item.quantity));
            }, 0);
            totalAmountSpan.innerText = `Br ${total.toLocaleString('id-ID')}`;
        }
    }

    function renderOrderCart() {
        const orderedItemsDiv = document.getElementById('orderedItems');
        const totalAmountSpan = document.getElementById('totalAmount');

        if (!orderedItemsDiv) return;

        // Empty cart
        if (orderItems.length === 0) {
            orderedItemsDiv.innerHTML = `
                <div class="empty-cart">
                    <i class="icon-basket"></i>
                    No items ordered yet<br>
                    <span>Click on menu items to add</span>
                </div>
            `;
            if (totalAmountSpan) totalAmountSpan.innerText = 'Br 0';
            return;
        }

        // Render ordered items
        orderedItemsDiv.innerHTML = orderItems.map(item => {
            const subtotal = Number(item.price) * Number(item.quantity);
            return `
                <div class="ordered-item">
                    <div class="item-header">
                        <div>
                            <div class="item-name">${item.name}</div>
                            <div class="item-price-small">Br ${Number(item.price).toLocaleString('id-ID')} each</div>
                        </div>
                        <button class="remove-btn" onclick="removeFromOrder('${item.id}')">✕</button>
                    </div>
                    <div class="item-controls">
                        <span style="font-size: 0.85rem; color: #475569;">Qty:</span>
                        <input type="number" min="1" value="${item.quantity}" 
                               class="item-qty-input" 
                              oninput="updateQuantity('${item.id}', this)">
                        <span class="item-subtotal" id="subtotal-${item.id}">
                            Br ${subtotal.toLocaleString('id-ID')}
                        </span>
                    </div>
                </div>
            `;
        }).join('');

        // Update total
        updateTotalOnly();
    }

    function placeOrder() {
        const tableNoInput = document.getElementById('table_no');
        const waiterSelect = document.getElementById('waiter_id');

        // Create or select error message spans
        let tableError = document.getElementById('table_no_error');
        if (!tableError) {
            tableError = document.createElement('span');
            tableError.id = 'table_no_error';
            tableError.className = 'error-msg';
            tableError.style.display = 'none';
            tableNoInput.parentNode.appendChild(tableError);
        }

        let waiterError = document.getElementById('waiter_id_error');
        if (!waiterError) {
            waiterError = document.createElement('span');
            waiterError.id = 'waiter_id_error';
            waiterError.className = 'error-msg';
            waiterError.style.display = 'none';
            waiterSelect.parentNode.appendChild(waiterError);
        }

        // Clear previous errors
        tableError.style.display = 'none';
        tableError.innerText = '';
        waiterError.style.display = 'none';
        waiterError.innerText = '';

        // Validate order items
        if (orderItems.length === 0) {
            tableError.innerText = 'No items in the order!';
            tableError.style.display = 'block';
            return;
        }

        const tableNo = tableNoInput.value.trim();
        const waiterId = waiterSelect.value;

        // Validate table number
        if (!tableNo || Number(tableNo) <= 0) {
            tableError.innerText = 'Please enter a valid table number';
            tableError.style.display = 'block';
            tableNoInput.focus();
            return;
        }

        // Validate waiter
        if (!waiterId || waiterId === "") {
            waiterError.innerText = 'Please select a waiter';
            waiterError.style.display = 'block';
            waiterSelect.focus();
            return;
        }

        const payload = {
            order_id: existingOrderId,
            table_no: tableNo,
            waiter_id: waiterId,
            items: orderItems.map(item => ({
                id: item.id?.toString(),
                price: item.price,
                quantity: item.quantity,
                name: item.name,
            }))
        };

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch('/orders/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })

            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = "{{ route('admin.order') }}";
                } else {
                    if (data.message && data.message.includes('active order')) {
                        tableError.innerText = data.message;
                        tableError.style.display = 'block';
                        tableNoInput.focus();
                    } else {
                        console.error(data.message);
                    }
                }
            })
            .catch(err => console.error(err));
    }

    function generatePrintPreview(data) {
        const container = document.getElementById('printContent');
        container.innerHTML = ''; // Reset everything

        // 1. Loop through departments
        data.print_data.forEach((group) => {
            let itemsHtml = '';

            // Ensure we only get items for THIS department group
            group.items.forEach(item => {
                itemsHtml += `
                <div class="row-line" style="display:flex; justify-content:space-between;">
                    <span>${item.qty}x ${item.name}</span>
                </div>`;
            });

            // 2. Create the HTML for one ticket
            const ticketHtml = `
            <div class="ticket">
                <div class="center">
                    <strong style="font-size: 1.2em;">${group.department.toUpperCase()}</strong>
                </div>
                <hr style="border: 0; border-top: 1px dashed #000;">
                <div class="row-line"><span>Order:</span> <span>${data.order_number}</span></div>
                <div class="row-line"><span>Table:</span> <span>${data.table_no}</span></div>
                <div class="row-line"><span>Waiter:</span> <span>${data.waiter_name || ''}</span></div>
                <div class="row-line"><span>Date:</span> <span>${new Date().toLocaleString()}</span></div>
                <hr style="border: 0; border-top: 1px dashed #000;">
                ${itemsHtml}
                <hr style="border: 0; border-top: 1px dashed #000;">
                <div class="center">*** END OF ${group.department.toUpperCase()} ***</div>
            </div>
        `;

            container.innerHTML += ticketHtml;
        });

        // 3. Open Modal and trigger print
        $('#printModal').modal('show');
    }
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.place-order-btn').addEventListener('click', placeOrder);
    });
    document.addEventListener('DOMContentLoaded', function() {
        renderOrderCart();
    });
</script>
@endsection