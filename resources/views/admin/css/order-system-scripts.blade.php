<script>
    // ========== ORDER CART JAVASCRIPT ==========
    let orderItems = [];

    // ADD TO ORDER FUNCTION
    function addToOrder(name, price) {
        const existingItem = orderItems.find(item => item.name === name);

        if (existingItem) {
            removeFromOrder(existingItem.id);
        } else {
            orderItems.push({
                id: name.replace(/\s+/g, '_'),
                name: name,
                price: Number(price), // STORE AS NUMBER
                quantity: 1
            });
        }

        renderOrderCart();
    }
    
    // REMOVE FROM ORDER
    function removeFromOrder(itemId) {
        orderItems = orderItems.filter(item => item.id !== itemId);
        renderOrderCart();
    }

    // UPDATE QUANTITY - NO RE-RENDER, just update values
    function updateQuantity(itemId, inputElement) {
        const item = orderItems.find(item => item.id === itemId);
        if (item) {
            let qty = parseInt(inputElement.value);
            
            // Handle empty input or NaN
            if (isNaN(qty)) {
                return;
            }
            
            // Handle zero or negative
            if (qty <= 0) {
                removeFromOrder(itemId);
                return;
            }
            
            // Update quantity in memory
            item.quantity = qty;
            
            // Update ONLY the subtotal text for THIS item
            const itemElement = inputElement.closest('.ordered-item');
            if (itemElement) {
                const subtotalSpan = itemElement.querySelector('.item-subtotal');
                if (subtotalSpan) {
                    const subtotal = Number(item.price) * Number(item.quantity);
                    subtotalSpan.innerText = `Br ${subtotal.toLocaleString('id-ID')}`;
                }
            }
            
            // Update total
            updateTotalOnly();
        }
    }

    // Update only total, no re-render
    function updateTotalOnly() {
        const totalAmountSpan = document.getElementById('totalAmount');
        if (totalAmountSpan) {
            const total = orderItems.reduce((sum, item) => {
                return sum + (Number(item.price) * Number(item.quantity));
            }, 0);
            totalAmountSpan.innerText = `Br ${total.toLocaleString('id-ID')}`;
        }
    }

    // RENDER ORDER CART
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
                        <span class="item-subtotal">
                            Br ${subtotal.toLocaleString('id-ID')}
                        </span>
                    </div>
                </div>
            `;
        }).join('');

        // Update total
        updateTotalOnly();
    }

    // PLACE ORDER
    function placeOrder() {
        if (orderItems.length === 0) {
            alert('No items in order!');
            return;
        }

        const tableNo = document.getElementById('tableNo')?.value || '-';
        const waiter = document.getElementById('waiterName')?.value || '-';
        const customer = document.getElementById('customerName')?.value || '-';
        const notes = document.getElementById('orderNotes')?.value || '-';

        const total = orderItems.reduce((sum, item) => {
            return sum + (Number(item.price) * Number(item.quantity));
        }, 0);

        // Format order summary
        let orderSummary = '✅ ORDER PLACED\n\n';
        orderSummary += `Table: ${tableNo}\n`;
        orderSummary += `Waiter: ${waiter}\n`;
        orderSummary += `Customer: ${customer || '-'}\n`;
        orderSummary += `Notes: ${notes || '-'}\n\n`;
        orderSummary += `Items:\n`;

        orderItems.forEach(item => {
            const subtotal = Number(item.price) * Number(item.quantity);
            orderSummary += `- ${item.name} x${item.quantity} = Br ${subtotal.toLocaleString('id-ID')}\n`;
        });

        orderSummary += `\nTOTAL: Br ${total.toLocaleString('id-ID')}`;

        alert(orderSummary);

        // Clear order
        orderItems = [];
        renderOrderCart();

        // Reset optional fields
        if (document.getElementById('customerName')) document.getElementById('customerName').value = '';
        if (document.getElementById('orderNotes')) document.getElementById('orderNotes').value = '';
    }

    // Initialize empty cart on page load
    document.addEventListener('DOMContentLoaded', function() {
        renderOrderCart();
    });
</script>