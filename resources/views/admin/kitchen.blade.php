@extends('admin.layout')
@section('title','Kitchen Order')
@section('content')
@include('admin.css.kitchen')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
    .kitchen-item.flagged-item {
        color: red;
        font-weight: bold;
    }
</style>
<div class="kitchen-wrapper">
    <!-- NEW ORDERS -->
    <div class="kitchen-column">
        <div class="column-header new">
            NEW ORDERS
            <span class="order-count" id="new-count">0</span>
        </div>
        <div class="search-wrapper">
            <input type="text" class="order-search" data-target="#pending-orders" placeholder="Search New Orders...">
        </div>
        <div id="pending-orders" class="order-list">

        </div>
    </div>
    <!-- COOKING -->
    <div class="kitchen-column">
        <div class="column-header cooking">
            COOKING
            <span class="order-count" id="cooking-count">0</span>
        </div>
        <div class="search-wrapper">
            <input type="text" class="order-search" data-target="#preparing-orders" placeholder="Search Cooking Orders...">
        </div>
        <div id="preparing-orders" class="order-list">
        </div>
    </div>
    <!-- READY -->
    <div class="kitchen-column">
        <div class="column-header ready">
            READY
            <span class="order-count" id="ready-count"></span>
        </div>
        <div class="search-wrapper">
            <input type="text" class="order-search" data-target="#ready-orders" placeholder="Search Ready Orders...">
        </div>
        <div id="ready-orders" class="order-list">
        </div>
    </div>
</div>


<script>
    let readyOrderNumber = null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const orderTimes = {};

    // Global function
    async function loadKitchenOrders() {
        try {
            const res = await fetch('/kitchen/orders');
            const orders = await res.json();

            const newContainer = document.getElementById('pending-orders');
            const cookingContainer = document.getElementById('preparing-orders');
            const readyContainer = document.getElementById('ready-orders');

            if (!newContainer || !cookingContainer || !readyContainer) return;

            orders.forEach(order => {

                const pendingItems = order.items.filter(i => i.status === 'pending');
                const cookingItems = order.items.filter(i => i.status === 'cooking');
                const readyItems = order.items.filter(i => i.status === 'ready');

                // NEW
                if (pendingItems.length) {
                    let card = document.querySelector(`[data-order="${order.order_number}-pending"]`);
                    if (!card) {
                        card = createKitchenCard(order, pendingItems, 'pending');
                        card.dataset.order = order.order_number + '-pending';
                        card.dataset.status = 'pending';
                        newContainer.appendChild(card);
                        startUrgentTimer(card);
                    }
                }

                // COOKING
                if (cookingItems.length) {
                    let card = document.querySelector(`[data-order="${order.order_number}-cooking"]`);
                    if (!card) {
                        card = createKitchenCard(order, cookingItems, 'cooking');
                        card.dataset.order = order.order_number + '-cooking';
                        cookingContainer.appendChild(card);
                    }
                }

                // READY
                if (readyItems.length) {
                    let card = document.querySelector(`[data-order="${order.order_number}-ready"]`);
                    if (!card) {
                        card = createKitchenCard(order, readyItems, 'ready');
                        card.dataset.order = order.order_number + '-ready';
                        readyContainer.appendChild(card);
                    }
                }
                updateOrderCounts();


            });

        } catch (err) {
            console.error('Error loading kitchen orders:', err);
        }
    }

    updateOrderCounts();
    document.addEventListener('DOMContentLoaded', () => {
        loadKitchenOrders();
        setInterval(loadKitchenOrders, 2000);
    });

    function createKitchenCard(order, items, status) {
        const card = document.createElement('div');
        card.classList.add('order-card');
        card.dataset.order = order.order_number;
        card.dataset.status = status;
        card.dataset.items = JSON.stringify(items); // store items for timer

        if (status === 'ready') {
            const readyTimes = items.map(i => new Date(i.updated_at).getTime());
            const latestReadyTime = Math.max(...readyTimes);
            card.dataset.readyTime = new Date(latestReadyTime).toISOString();
        }

        card.dataset.tableNo = order.table_no; // store table_no for KitchenStat

        const earliestItemTime = new Date(Math.min(...items.map(i => new Date(i.created_at).getTime())));
        card.dataset.createdAt = earliestItemTime.toISOString();

        const finishTimes = items.map(item => {
            const created = new Date(item.created_at).getTime();
            const prep = item.prep_time * 60000; // convert minutes → milliseconds
            return created + prep;
        });

        const orderFinishTime = Math.max(...finishTimes);
        card.dataset.finishTime = new Date(orderFinishTime).toISOString();



        card.dataset.createdAt = earliestItemTime.toISOString(); // set it here
        // Merge items
        // Separate normal and flagged/void items
        const normalItems = [];
        const specialItems = [];

        items.forEach(item => {
            if (item.is_flagged || item.is_void) {
                specialItems.push(item);
            } else {
                normalItems.push(item);
            }
        });

        // Merge normal items by name
        const mergedNormalItems = normalItems.reduce((acc, item) => {
            if (acc[item.name]) {
                acc[item.name] += item.quantity;
            } else {
                acc[item.name] = item.quantity;
            }
            return acc;
        }, {});

        // Merge flagged/void items by name
        const mergedSpecialItems = specialItems.reduce((acc, item) => {
            const key = item.name + (item.is_flagged ? '-flag' : '') + (item.is_void ? '-void' : '');
            if (acc[key]) {
                acc[key].quantity += item.quantity;
            } else {
                acc[key] = {
                    ...item
                }; // copy the item object
            }
            return acc;
        }, {});

        // Build HTML: normal items first
        let itemsHtml = '';
        for (const [name, qty] of Object.entries(mergedNormalItems)) {
            itemsHtml += `<div class="kitchen-item">${qty} × ${name}</div>`;
        }

        // Then flagged/void items at the bottom
        for (const key in mergedSpecialItems) {
            const item = mergedSpecialItems[key];
            itemsHtml += `<div class="kitchen-item flagged-item" style="color:red;">
        ${item.quantity} × ${item.name} ${item.is_flagged ? '(FLAGGED)' : ''}${item.is_void ? '(VOID)' : ''}
         </div>`;
        }

        // Decide button
        let buttonText = '';
        if (status === 'pending') buttonText = 'Start';
        else if (status === 'cooking') buttonText = 'Ready';
        else if (status === 'ready') buttonText = 'Serve';

        // HTML
        card.innerHTML = `
        <div class="kitchen-card-header">
            <strong>Table: ${order.table_no}</strong>
            <span class="order-time" style="float:right;">0:00</span><br><br>
        
            Waiter: ${order.waiter}
        </div>
        <div class="kitchen-card-body">${itemsHtml}</div><br>
        <div class="kitchen-card-footer">
            <button class="btn btn-primary btn-sm" onclick="updateOrderStatus('${order.order_number}', '${status}', this.closest('.order-card'))">
                ${buttonText}
            </button>
        </div>
        <div class="urgent-badge" style="display:none;">URGENT</div>
         `;

        // Start Cooking timer immediately if status = cooking
        if (status === 'cooking') {
            startCookingTimer(card, items);
        } else if (status === 'pending') {
            startUrgentTimer(card);
        } else if (status === 'ready') {
            startReadyTimer(card);
        }

        return card;
    }

    function startReadyTimer(card) {

        if (card.dataset.timerStarted) return;
        card.dataset.timerStarted = "true";

        const readyTime = new Date(card.dataset.readyTime);
        const timerSpan = card.querySelector('.order-time');
        const urgentBadge = card.querySelector('.urgent-badge');

        setInterval(() => {

            const now = new Date();
            const diffMs = now - readyTime;

            const min = Math.floor(diffMs / 60000);
            const sec = Math.floor((diffMs % 60000) / 1000);

            timerSpan.textContent =
                `${min}:${sec.toString().padStart(2,'0')}`;

            // after 5 minutes show serve late
            if (diffMs >= 5 * 60 * 1000) {

                timerSpan.textContent =
                    `Serve Late: ${min}:${sec.toString().padStart(2,'0')}`;

                card.style.border = "2px solid orange";
                urgentBadge.style.display = "block";
            }

        }, 1000);
    }

    function startUrgentTimer(card) {

        if (card.dataset.timerStarted) return; // ✅ prevent duplicates
        card.dataset.timerStarted = "true";

        const orderCreatedAt = new Date(card.dataset.createdAt);
        const orderTimeSpan = card.querySelector('.order-time');
        const urgentBadge = card.querySelector('.urgent-badge');

        setInterval(() => {
            const now = new Date();
            const diffMs = now - orderCreatedAt;
            const diffMin = Math.floor(diffMs / 60000);
            const diffSec = Math.floor((diffMs % 60000) / 1000);

            orderTimeSpan.textContent =
                `${diffMin}:${diffSec.toString().padStart(2,'0')}`;

            if (diffMs >= 5 * 60 * 1000) {
                card.classList.add('urgent');
                urgentBadge.style.display = 'block';
            }
        }, 1000);
    }

    // Attach start / ready button events
    function attachButtonEvents() {
        document.querySelectorAll('.btn-start').forEach(btn => {
            btn.onclick = async () => {
                const card = btn.closest('.order-card');
                const orderNumber = card.dataset.orderNumber;

                try {
                    const res = await fetch(`/kitchen/orders/${orderNumber}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            status: 'cooking'
                        })
                    });

                    if (!res.ok) {
                        console.error("Failed to update status", res.status, await res.text());
                        return;
                    }

                    // Refresh kitchen orders
                    loadKitchenOrders();
                } catch (err) {
                    console.error("Error updating order:", err);
                }
            };
        });


        document.querySelectorAll('.btn-ready').forEach(btn => {
            btn.onclick = () => {
                const card = btn.closest('.order-card');
                const orderNumber = card.dataset.orderNumber;
                const status = card.dataset.status;

                if (status === 'new') {
                    readyOrderNumber = orderNumber;
                    document.getElementById('readyConfirmModal').style.display = 'flex';
                } else if (status === 'cooking') {
                    updateOrderStatus(orderNumber, 'ready');
                }
            };
        });


        document.querySelectorAll('.btn-serve').forEach(btn => {
            btn.onclick = () => {
                const card = btn.closest('.order-card');
                const orderNumber = card.dataset.orderNumber;

                updateOrderStatus(orderNumber, 'served');
            };
        });
    }

    async function createKitchenStat(orderNumber, tableNo, items) {
        try {
            const totalItems = items.reduce((sum, i) => sum + i.quantity, 0);
            await fetch(`/kitchen/stats`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    order_number: orderNumber,
                    table_no: tableNo,
                    total_items: totalItems,
                    chef_name: "{{ Auth::user()->name }}",
                    date_created: new Date().toISOString()
                })
            });
        } catch (err) {
            console.error("Error creating KitchenStat:", err);
        }
    }
    // Helper function for POST
    async function updateOrderStatus(orderNumber, currentStatus, card) {
        // Get the item IDs from the card dataset
        const items = JSON.parse(card.dataset.items || '[]');
        const itemIds = items.map(i => i.id);

        try {
            const res = await fetch(`/kitchen/orders/${orderNumber}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    order_number: orderNumber,
                    current_status: currentStatus,
                    item_ids: itemIds // ✅ send the IDs to the server
                })
            });

            if (!res.ok) {
                console.error("Failed to update status", res.status, await res.text());
                return;
            }

            // Remove old cards and reload
            document.querySelectorAll('.order-card').forEach(c => {
                if (c.dataset.order && c.dataset.order.startsWith(orderNumber + '-')) c.remove();
            });

            await loadKitchenOrders();

            // Start cooking timer & create KitchenStat
            if (currentStatus === 'pending') {
                const newCard = document.querySelector(`[data-order="${orderNumber}-cooking"]`);
                if (newCard) {
                    const newItems = JSON.parse(newCard.dataset.items);
                    startCookingTimer(newCard, newItems);
                    createKitchenStat(orderNumber, newCard.dataset.tableNo, newItems);
                }
            }

        } catch (err) {
            console.error("Error updating order:", err);
        }
    }

    async function startCooking(orderNumber, tableNo, items) {
        try {
            // 1️⃣ Update status to 'cooking'
            await fetch(`/kitchen/orders/${orderNumber}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    current_status: 'pending'
                })
            });

            // 2️⃣ Create KitchenStat
            const totalItems = items.reduce((sum, i) => sum + i.quantity, 0);
            await fetch(`/kitchen/stats`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    order_number: tableNo,
                    total_items: totalItems,
                    chef_name: "{{ Auth::user()->name }}",
                    date_created: new Date().toISOString()
                })
            });

            // 3️⃣ Reload kitchen orders
            await loadKitchenOrders();

            // 4️⃣ Start timer for this order card
            const card = document.querySelector(`[data-order="${orderNumber}-cooking"]`);
            if (card) startCookingTimer(card, items);

        } catch (err) {
            console.error("Error starting cooking:", err);
        }
    }

    function startCookingTimer(card) {

        if (card.dataset.timerStarted) return; // 
        card.dataset.timerStarted = "true";

        const items = JSON.parse(card.dataset.items);
        if (!items || items.length === 0) return;

        // Calculate remaining time based on created_at + prep_time
        const now = new Date().getTime();
        const finishTimes = items.map(item => {
            const created = new Date(item.updated_at).getTime();
            const prep = (item.prep_time || 0) * 60000; // prep_time → ms
            return created + prep;
        });
        const latestFinishTime = Math.max(...finishTimes);
        let remainingMs = latestFinishTime - now; // ms left

        const timerSpan = card.querySelector('.order-time');
        const urgentBadge = card.querySelector('.urgent-badge');

        setInterval(() => {

            if (remainingMs > 0) {

                const min = Math.floor(remainingMs / 60000);
                const sec = Math.floor((remainingMs % 60000) / 1000);

                timerSpan.textContent =
                    `${min}:${sec.toString().padStart(2,'0')}`;

                remainingMs -= 1000;

            } else {

                const lateMs = Math.abs(remainingMs);
                const min = Math.floor(lateMs / 60000);
                const sec = Math.floor((lateMs % 60000) / 1000);

                timerSpan.textContent =
                    `Late: ${min}:${sec.toString().padStart(2,'0')}`;

                card.style.border = '2px solid red';
                card.style.animation = 'flashRed 0.5s infinite alternate';

                urgentBadge.style.display = 'block';

                remainingMs -= 1000;
            }

        }, 1000);
    }

    function updateOrderCounts() {
        const newCount = Array.from(document.querySelectorAll('#pending-orders .order-card'))
            .filter(c => c.style.display !== 'none').length;
        const cookingCount = Array.from(document.querySelectorAll('#preparing-orders .order-card'))
            .filter(c => c.style.display !== 'none').length;
        const readyCount = Array.from(document.querySelectorAll('#ready-orders .order-card'))
            .filter(c => c.style.display !== 'none').length;

        document.getElementById('new-count').textContent = newCount;
        document.getElementById('cooking-count').textContent = cookingCount;
        document.getElementById('ready-count').textContent = readyCount;
    }

    document.querySelectorAll('.order-search').forEach(input => {

        input.addEventListener('input', function() {

            const searchValue = this.value.toLowerCase();
            const target = document.querySelector(this.dataset.target);
            const cards = target.querySelectorAll('.order-card');

            cards.forEach(card => {

                const text = card.innerText.toLowerCase();

                if (text.includes(searchValue)) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }

            });

        });

    });
</script>


@endsection