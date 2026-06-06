@extends('admin.layout')
@section('title','Finish Order')
@section('content')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="block">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="voidOrdersTable">
                        <thead class="thead">
                            <tr>
                                <th>Order #</th>
                                <th>Table No</th>
                                <th>Waiter</th>
                                <th>Cashier</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->table_no }}</td>
                                <td>{{ $order->waiter->name ?? '-' }}</td>
                                <td>{{ $order->user->name ?? '-' }}</td>
                                <td>Br {{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-success" style="color:white;">Payed Orders</span>
                                </td>
                                <td>
                                    <button class="btn btn-outline-secondary btn-sm"
                                        onclick="showFlaggedModal('{{ $order->id }}')">
                                        View Items
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No Payed Orders</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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
                                    <div style="color: #fff; font-size: 1.4rem; font-weight: 600; line-height: 1.2;"><span id="modalCashier"></span></div>
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
                <div style="
                            background: linear-gradient(135deg, rgba(154,160,168,0.08) 0%, rgba(0,0,0,0.35) 100%);
                            border-radius: 16px;
                            padding: 1.4rem;
                            border: 1px solid rgba(255,215,0,0.12);
                        ">

                    <!-- Header -->
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                        <i class="bi bi-cash-stack" style="color:#FFD700; font-size:1.4rem;"></i>
                                            <span style="
                                color:#9aa0a8;
                                font-size:1rem;
                                text-transform:uppercase;
                                letter-spacing:0.6px;
                            ">
                            Payment Summary
                        </span>
                    </div>

                    <!-- Content -->
                    <div style="display:flex; justify-content:space-between; align-items:flex-end;">

                        <!-- LEFT: breakdown -->
                        <div style="display:flex; flex-direction:column; gap:6px;">

                            <div style="color:#9aa0a8; font-size:0.95rem;">
                                Bank:
                                <span id="modalBank" style="color:#4da3ff; font-weight:600;">0</span>
                            </div>

                            <div style="color:#9aa0a8; font-size:0.95rem;">
                                Cash:
                                <span id="modalCash" style="color:#7ee787; font-weight:600;">0</span>
                            </div>

                            <div style="color:#9aa0a8; font-size:0.95rem;">
                                Return:
                                <span id="modalReturn" style="color:#ff6b6b; font-weight:600;">0</span>
                            </div>

                        </div>

                        <!-- RIGHT: total -->
                        <div style="text-align:right;">
                            <div style="
                                        color:#9aa0a8;
                                        font-size:0.8rem;
                                        text-transform:uppercase;
                                        letter-spacing:0.5px;
                                        margin-bottom:2px;
                                    ">
                                Total
                            </div>

                            <div style="
                                        color:#FFD700;
                                        font-size:2rem;
                                        font-weight:700;
                                        letter-spacing:-0.5px;
                                    ">
                                Br <span id="modalTotalAmount">0</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Modal Footer (Optional - add if needed) -->
            <!-- <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.08); padding: 1.25rem 1.5rem;">
                <button type="button" class="btn print-receipt-btn" id="printReceiptBtn" style="background: linear-gradient(135deg, #FFD700 0%, #FDB931 100%); border: none; padding: 0.6rem 1.8rem; border-radius: 12px; color: #2d3035; font-weight: 600; letter-spacing: 0.3px; box-shadow: 0 8px 16px -4px rgba(255,215,0,0.2);">
                    <i class="bi bi-printer" style="margin-right: 8px;"></i>
                    Print Receipt
                </button>
            </div> -->
        </div>
    </div>
</div>

<script>
    function showFlaggedModal(orderId) {
        fetch(`/finish_order_items/${orderId}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('modalOrderNumber').innerText = data.order_number;
                document.getElementById('modalTableNo').innerText = data.table_no;
                document.getElementById('modalWaiter').innerText = data.waiter_name;
                document.getElementById('modalCashier').innerText = data.cashier_name || '-';
                document.getElementById('modalBank').innerText = Number(data.payment.bank).toLocaleString();
                document.getElementById('modalCash').innerText = Number(data.payment.cash).toLocaleString();
                document.getElementById('modalReturn').innerText = Number(data.payment.return).toLocaleString();

                const tbody = document.querySelector('#modalOrderItemsTable tbody');
                tbody.innerHTML = '';
                let total = 0;

                data.items.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>Br ${Number(item.unit_price).toLocaleString()}</td>
                    <td>Br ${Number(item.subtotal).toLocaleString()}</td>
                `;
                    tbody.appendChild(tr);
                    total += parseFloat(item.subtotal);
                });

                document.getElementById('modalTotalAmount').innerText = total.toLocaleString();

                const modal = new bootstrap.Modal(document.getElementById('viewOrderModal'));
                modal.show();
            })
            .catch(err => {
                console.error(err);
                alert('Cannot fetch order items');
            });
    }
</script>

@endsection