@extends('admin.layout')
@section('title','Waiting Orders')
@section('content')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="block">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="waitingOrdersTable">
                        <thead class="thead">
                            <tr>
                                <th>Order #</th>
                                <th>Table No</th>
                                <th>Waiter</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Create At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->table_no }}</td>
                                <td>{{ $order->waiter->name ?? '-' }}</td>
                                <td>Br {{ number_format($order->total_amount) }}</td>
                                <td>
                                    @if($order->is_billed == 0)
                                    <span style="color:black;font-size:14px" class="badge bg-warning">Waiting</span>
                                    @endif
                                    @if($order->is_billed == 1)
                                    <span style="color:white;font-size:14px" class="badge bg-success">Served</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('Y-m-d  g:i:s A') }}</td>
                                <td><button data-toggle="dropdown" type="button" class="btn btn-outline-secondary dropdown-toggle">Actions</button>
                                    <div style="color:black;background-color: #ffffff" class="dropdown-menu">
                                        @if($order->is_billed == 0)<a href="{{ route('orders.add', $order->id) }}" class="dropdown-item"> Add Order </a>@endif
                                        <a href="javascript:void(0);" class="dropdown-item" onclick="showOrderModal('{{ $order->order_number }}','{{ $order->id }}')"> View Order</a>
                                        <!-- @if($order->is_billed == 0)<a href="{{ url('flag_all/'.$order->id) }}" class="dropdown-item">Flag</a>@endif -->
                                        @if($order->is_billed == 0)<a href="javascript:void(0)"
                                            class="dropdown-item"
                                            onclick="openEditTableModal('{{ $order->id }}','{{ $order->table_no }}','{{ $order->waiter_id }}')">
                                            Edit Table
                                        </a>@endif
                                        <a href="javascript:void(0);"
                                            class="dropdown-item"
                                            data-order-id="{{ $order->id }}"
                                            data-total="{{ $order->total_amount }}"
                                            onclick="openPaymentModal(this)">
                                            Make Payment
                                        </a>

                                    </div>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No waiting orders</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Payment</h5>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="payment_order_id">

                <div class="mb-3">
                    <label>Total Amount</label>
                    <input type="text" style="background-color:#83b9f8;color:white" id="payment_total" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label>Cash Amount</label>
                    <input type="number" id="cash_amount" class="form-control" value="0">
                </div>

                <div class="mb-3">
                    <label>Bank Amount</label>
                    <input type="number" id="bank_amount" class="form-control" value="0">
                </div>

                <div class="mb-3">
                    <label>Remaining</label>
                    <input type="text" style="background-color: #83b9f8; color:white" id="remaining_amount" class="form-control" readonly>
                    <span id="payment_error" style="color:red;font-size:13px"></span>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-success" onclick="confirmPayment()">Confirm Payment</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="editTableModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="editTableForm">
                @csrf
                <input type="hidden" id="edit_order_id">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Table</h5>
                    <button type="button" class="btn-close" data-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label>Table No</label>
                        <input type="text" id="edit_table_no" class="form-control" required>
                        <span id="table_error" style="color:red;font-size:13px"></span>
                    </div>

                    <div class="form-group">
                        <label>Waiter</label>
                        <select id="edit_waiter" class="form-control" required>
                            @foreach($waiters as $waiter)
                            <option value="{{ $waiter->id }}">{{ $waiter->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="updateTable()" class="btn btn-primary">Update</button>
                </div>

            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: linear-gradient(145deg, #2d3035 0%, #222529 100%); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.02) inset;">

            <!-- Modal Header -->
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding: 1.5rem 1.5rem 1rem 1.5rem;">
                <h5 class="modal-title" style="color: #fff; font-weight: 600; font-size: 1.35rem; letter-spacing: 0.3px;">
                    <i class="bi bi-receipt" style="margin-right: 10px; color: #9aa0a8;"></i>
                    Order Details - <span id="modalOrderNumber" style="color: #fff; font-weight: 500;"></span>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body" style="padding: 1.5rem;">
                <!-- Order Info Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div style="background: rgba(0,0,0,0.25); border-radius: 16px; padding: 1rem; border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(5px);">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.08); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #9aa0a8;">
                                    <i class="bi bi-table" style="font-size: 1.2rem;"></i>
                                </div>
                                <div>
                                    <div style="color: #9aa0a8; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Table No</div>
                                    <div style="color: #fff; font-size: 1.4rem; font-weight: 600; line-height: 1.2;"><span id="modalTableNo"></span></div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="background: rgba(0,0,0,0.25); border-radius: 16px; padding: 1rem; border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(5px);">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.08); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #9aa0a8;">
                                    <i class="bi bi-person-badge" style="font-size: 1.2rem;"></i>
                                </div>
                                <div>
                                    <div style="color: #9aa0a8; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Waiter</div>
                                    <div style="color: #fff; font-size: 1.4rem; font-weight: 600; line-height: 1.2;"><span id="modalWaiter"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="background: rgba(0,0,0,0.25); border-radius: 16px; padding: 1rem; border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(5px);">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.08); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #9aa0a8;">
                                    <i class="bi bi-person-badge" style="font-size: 1.2rem;"></i>
                                </div>
                                <div>
                                    <div style="color: #9aa0a8; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Casher</div>
                                    <div style="color: #fff; font-size: 1.4rem; font-weight: 600; line-height: 1.2;"><span id="modalCasher"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items Table -->
                <div style="background: rgba(0,0,0,0.2); border-radius: 16px; padding: 1.25rem; margin-bottom: 1.5rem; border: 1px solid rgba(255,255,255,0.05);">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 1.25rem;">
                        <i class="bi bi-bag-check" style="color: #9aa0a8; font-size: 1.2rem;"></i>
                        <h6 style="color: #fff; margin: 0; font-weight: 600; letter-spacing: 0.3px;">ORDER ITEMS</h6>
                    </div>

                    <table class="table" style="color: #fff; margin-bottom: 0;" id="modalOrderItemsTable">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.08);">
                                <th style="color: #9aa0a8; font-weight: 500; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 0.75rem 0.5rem; border: none;">Item Name</th>
                                <th style="color: #9aa0a8; font-weight: 500; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 0.75rem 0.5rem; border: none;">Qty</th>
                                <th style="color: #9aa0a8; font-weight: 500; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 0.75rem 0.5rem; border: none;">Unit Price</th>
                                <th style="color: #9aa0a8; font-weight: 500; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 0.75rem 0.5rem; border: none;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Items will be populated via JS -->
                        </tbody>
                    </table>
                </div>

                <!-- Total Amount -->
                <div style="background: linear-gradient(135deg, rgba(154,160,168,0.1) 0%, rgba(0,0,0,0.3) 100%); border-radius: 16px; padding: 1.25rem; border: 1px solid rgba(255,215,0,0.15);">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="bi bi-cash-stack" style="color: #FFD700; font-size: 1.5rem;"></i>
                            <span style="color: #9aa0a8; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 0.5px;">Total Amount</span>
                        </div>
                        <div style="color: #FFD700; font-size: 2rem; font-weight: 700; letter-spacing: -0.5px;">
                            Br <span id="modalTotalAmount" style="color: #FFD700;">0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer (Optional - add if needed) -->
            <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.08); padding: 1.25rem 1.5rem;">
                <button type="button" class="btn print-receipt-btn" id="printReceiptBtn" style="background: linear-gradient(135deg, #FFD700 0%, #FDB931 100%); border: none; padding: 0.6rem 1.8rem; border-radius: 12px; color: #2d3035; font-weight: 600; letter-spacing: 0.3px; box-shadow: 0 8px 16px -4px rgba(255,215,0,0.2);">
                    <i class="bi bi-printer" style="margin-right: 8px;"></i>
                    Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="voidModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content p-3">

            <h5>Void Item</h5>

            <p id="voidItemName"></p>

            <input type="number" id="voidQty" class="form-control" min="1">

            <input type="hidden" id="voidItemId">
            <input type="hidden" id="voidOrderId">
            <input type="hidden" id="voidProductId">

            <div class="mt-3">
                <button class="btn btn-danger" onclick="confirmVoid()">Confirm Void</button>
                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>

        </div>
    </div>
</div>

<script>
    function showOrderModal(orderNumber, orderId) {
        document.getElementById('printReceiptBtn').dataset.orderId = orderId;


        fetch(`/orders/${orderNumber}`) // <-- this now matches your controller
            .then(res => {
                if (!res.ok) throw new Error('Network response was not OK');
                return res.json();
            })
            .then(data => {
                console.log('is_billed:', data.order.is_billed, typeof data.order.is_billed);
                document.getElementById('modalOrderNumber').innerText = data.order.order_number;
                document.getElementById('modalTableNo').innerText = data.order.table_no;
                document.getElementById('modalWaiter').innerText = data.order.waiter.name || '-';
                document.getElementById('modalCasher').innerText = data.order.casher.name || '-';

                const tbody = document.getElementById('modalOrderItemsTable').querySelector('tbody');
                tbody.innerHTML = '';

                const mergedItems = {};
                data.items.forEach(item => {

                    const key = item.product_id + '_' + item.product_type;

                    if (!mergedItems[key]) {
                        mergedItems[key] = {
                            ...item,
                            quantity: 0,
                            subtotal: 0
                        };
                    }
                    mergedItems[key].quantity += Number(item.quantity || 0);
                    mergedItems[key].subtotal += Number(item.subtotal || 0);
                });

                let total = 0;
                const isBilled = Number(data.order.is_billed) || 0;
                Object.values(mergedItems).forEach(item => {
                    const tr = document.createElement('tr');

                    // Check if order is billed
                    const flagButton = (Number(data.order.is_billed) === 0) ? `
                        <button type="button" class="btn btn-danger btn-sm"
                           onclick="openVoidModal('${data.order.id}', '${item.product_id}', '${item.name}', ${item.quantity})">
                            Void
                        </button>` : '';

                    tr.innerHTML = `
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>Br ${Number(item.unit_price).toLocaleString()}</td>
                    <td>Br ${Number(item.subtotal).toLocaleString()}</td>
                    <td>${flagButton}</td>
                `;
                    tbody.appendChild(tr);
                    total += Number(item.subtotal);
                });

                document.getElementById('modalTotalAmount').innerText = total.toLocaleString();

                const modal = new bootstrap.Modal(document.getElementById('viewOrderModal'));
                modal.show();
            })
            .catch(err => {
                console.error('Error fetching order:', err);
                alert('Cannot fetch order data. Check console for details.');
            });
    }

    function flagItem(button, itemId) {
        fetch(`/flag/${itemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id: itemId
                })
            })
            .then(res => {
                if (!res.ok) throw new Error('Network error');
                // disable the button after flagging
                button.disabled = true;
                button.innerText = 'Flagged';
            })
            .catch(err => {
                console.error(err);
                alert('Could not flag item. Check console.');
            });
    }

    function openVoidModal(orderId, productId, name, qty) {
        document.getElementById('voidOrderId').value = orderId;
        document.getElementById('voidProductId').value = productId;
        document.getElementById('voidItemName').innerText = `${name} (Max: ${qty})`;
        document.getElementById('voidQty').value = qty;

        const modal = new bootstrap.Modal(document.getElementById('voidModal'));
        modal.show();
    }

    function confirmVoid() {
        const orderId = document.getElementById('voidOrderId').value;
        const productId = document.getElementById('voidProductId').value;
        const qty = parseInt(document.getElementById('voidQty').value);

        fetch(`/orders/void-item`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    order_id: orderId,
                    product_id: productId,
                    quantity: qty
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // refresh everything
                } else {
                    alert(data.message);
                }
            })
            .catch(err => console.error(err));
    }

    document.getElementById('printReceiptBtn').addEventListener('click', function() {

        const orderId = this.dataset.orderId;

        if (!orderId) {
            alert("Order ID missing");
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch(`/orders/bill/${orderId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {

                    window.open(`/orders/print/${orderId}`, "_blank");

                    location.reload();

                }

            })
            .catch(err => console.error(err));

    });

    function openEditTableModal(orderId, tableNo, waiterId) {
        document.getElementById('edit_order_id').value = orderId;
        document.getElementById('edit_table_no').value = tableNo;
        document.getElementById('edit_waiter').value = waiterId;

        const modal = new bootstrap.Modal(document.getElementById('editTableModal'));
        modal.show();
    }

    function updateTable() {
        const orderId = document.getElementById('edit_order_id').value;
        const tableNo = document.getElementById('edit_table_no').value;
        const waiterId = document.getElementById('edit_waiter').value;

        // clear old error
        document.getElementById('table_error').innerText = "";

        fetch(`/update-table/${orderId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    table_no: tableNo,
                    waiter_id: waiterId
                })
            })
            .then(res => res.json())
            .then(data => {

                if (data.status === "error") {
                    document.getElementById('table_error').innerText = data.message;
                    return;
                }

                location.reload();
            });
    }

    function openPaymentModal(element) {
        const orderId = element.dataset.orderId;
        const totalAmount = Number(element.dataset.total);

        document.getElementById('payment_order_id').value = orderId;
        document.getElementById('payment_total').value = totalAmount;

        document.getElementById('cash_amount').value = 0;
        document.getElementById('bank_amount').value = 0;
        document.getElementById('remaining_amount').value = totalAmount;

        const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
        modal.show();
    }

    // Update remaining / change dynamically
    document.getElementById('cash_amount').addEventListener('input', calculatePayment);
    document.getElementById('bank_amount').addEventListener('input', calculatePayment);

    function calculatePayment() {
        const total = parseFloat(document.getElementById('payment_total').value) || 0;
        const cash = parseFloat(document.getElementById('cash_amount').value) || 0;
        const bank = parseFloat(document.getElementById('bank_amount').value) || 0;

        const paid = Number(cash) + Number(bank); // force numeric addition

        if (paid >= total) {
            document.getElementById('remaining_amount').value = "Change: " + (paid - total).toFixed(2);
        } else {
            document.getElementById('remaining_amount').value = "Remaining: " + (total - paid).toFixed(2);
        }

        // clear error when typing
        document.getElementById('payment_error').innerText = "";
    }

    function confirmPayment() {
        const total = parseFloat(document.getElementById('payment_total').value) || 0;
        const cash = parseFloat(document.getElementById('cash_amount').value) || 0;
        const bank = parseFloat(document.getElementById('bank_amount').value) || 0;
        const orderId = document.getElementById('payment_order_id').value;

        const paid = Number(cash) + Number(bank);

        if (paid < total) {
            document.getElementById('payment_error').innerText = "Payment does not cover total amount";
            return;
        }

        document.getElementById('payment_error').innerText = "";

        fetch(`/process-payment/${orderId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    cash: Number(cash),
                    bank: Number(bank)
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log('Payment success, reloading...');

                    // Hide the modal manually with fallback
                    const modalEl = document.getElementById('paymentModal');

                    // For Bootstrap 5
                    if (bootstrap && bootstrap.Modal) {
                        try {
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();
                        } catch (e) {
                            console.warn('Bootstrap modal hide failed:', e);
                        }
                    }

                    // Force reload after 100ms just to give modal a tiny time to close
                    setTimeout(() => {
                        window.location.reload();
                    }, 100);
                } else {
                    document.getElementById('payment_error').innerText = data.message;
                }
            })
            .catch(err => {
                console.error('Payment error:', err);
                document.getElementById('payment_error').innerText = "An error occurred during payment.";

                // Still force reload just in case backend succeeded
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            });
    }
</script>

@endsection