{{-- ========== ORDER SYSTEM HTML ========== --}}
<div class="order-three-column">

    {{-- LEFT COLUMN - CATEGORIES --}}
    <div class="categories-card">
        <div class="categories-header">
            <i class="icon-list"></i> Categories
        </div>
        <div class="category-items" id="categoryList">
            
            <button class="category-btn"> Makanan</button>
            <button class="category-btn"> Minuman</button>
            <button class="category-btn"> Snack</button>
        </div>
    </div>

    {{-- MIDDLE COLUMN - MENU ITEMS --}}
    <div>
        <div class="menu-header">
            <h4 id="activeCategoryTitle">All Items</h4>
            <span class="menu-count-badge" id="menuCount">0 items</span>
        </div>
        <div class="menu-grid" id="menuGrid">
            <div class="menu-card" onclick="addToOrder('Nasi Goreng', 45000)">
                <div class="menu-name">Nasi Goreng</div>
                <div class="menu-price">Rp 45,000</div>
                <div class="menu-desc">Bumbu spesial, telur</div>
            </div>
            <div class="menu-card"  onclick="addToOrder('shdsdsd ', 1000000)">
                <div class="menu-name">Mie Goreng</div>
                <div class="menu-price">Rp 42,000</div>
                <div class="menu-desc">Mie kuning, sayur</div>
            </div>
            <div class="menu-card">
                <div class="menu-name">Ayam Bakar</div>
                <div class="menu-price">Rp 55,000</div>
                <div class="menu-desc">Ayam bakar madu</div>
            </div>
            <div class="menu-card">
                <div class="menu-name">Es Teh</div>
                <div class="menu-price">Rp 8,000</div>
                <div class="menu-desc">Teh manis dingin</div>
            </div>
            
        </div>
    </div>

    {{-- RIGHT COLUMN - ORDER CART --}}
    <div class="order-card">
        <div class="order-content">
            <h5 class="order-title">
                <i class="icon-basket"></i> Current Order
            </h5>

            {{-- Order Information Fields --}}
            <div class="order-fields">
                <div class="field-group">
                    <label>Table No.</label>
                    <input type="text" id="tableNo" placeholder="Table #" value="5">
                </div>
                <div class="field-group">
                    <label>Waiter</label>
                    <input type="text" id="waiterName" placeholder="Name" value="Budi">
                </div>
                <div class="field-group">
                    <label>Customer</label>
                    <input type="text" id="customerName" placeholder="Optional">
                </div>
                <div class="field-group">
                    <label>Notes</label>
                    <input type="text" id="orderNotes" placeholder="Special req">
                </div>
            </div>

            {{-- Ordered Items List --}}
            <div class="ordered-items-container" id="orderedItems">
                {{-- Will be populated by JavaScript --}}
            </div>

            {{-- Total Amount --}}
            <div class="total-section">
                <span>Total</span>
                <span id="totalAmount">Rp 0</span>
            </div>
        </div>

        <button class="place-order-btn">
            <i class="icon-check"></i> Place Order
        </button>
    </div>
</div>
{{-- ========== END ORDER SYSTEM HTML ========== --}}