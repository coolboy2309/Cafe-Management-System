<style>
/* ========== ORDER SYSTEM STYLES - FIXED ========== */
.order-system-wrapper {
    margin-top: 1.5rem;
    font-family: inherit;
    width: 100%;
    position: relative;
}

/* ---------- 3 COLUMN LAYOUT - NO WHITE SPACE ---------- */
.order-three-column {
    display: grid;
    grid-template-columns: 240px 1fr 450px;  /* Right column from 340px → 380px */
    gap: 1.2rem;
    align-items: start;
    width: 100%;
}

/* ---------- LEFT COLUMN - CATEGORIES ---------- */
.categories-card {
    background: rgb(77, 149, 250);
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(73, 72, 72, 0.04);
    border: 1px solid #ffffff;
    overflow: hidden;
    width: 100%;
    margin-left: 10px;
}

.categories-header {
    padding: 1.2rem 1.2rem 0.8rem;
    border-bottom: 1px solid #25272a;
    font-weight: 600;
    color: #fafafa;
}

.category-items {
    padding: 1.4rem 1.2rem;
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.category-btn {
    width: 100%;
    text-align: left;
    padding: 0.85rem 1.2rem;  /* More padding */
    border-radius: 10px;       /* Slightly more rounded */
    background: transparent;
    border: none;
    color: #f7f7f7;
    font-weight: 500;
    font-size: 1.05rem;       /* Bigger font */
    transition: all 0.2s;
    cursor: pointer;
}

.category-btn:hover {
    background: #f8fafc;
    color: #004cff;
}

.category-btn.active {
    background: #fdfdff;
    color: #000000;
}
.menu-card {
    background: white;
    border: 1px solid #edf2f7;
    border-radius: 10px;
    padding: 0.6rem;
    transition: all 0.2s;
    cursor: pointer;
    width: 100%;
    position: relative;  /* 👈 ADD THIS - for absolute positioning */
}

.menu-badge {
    position: absolute;
    top: 5px;
    right: 5px;
    background: #ef4444;
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
    min-width: 20px;
    height: 20px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 5px;
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
}

/* ---------- MIDDLE COLUMN - MENU ITEMS (4 COLUMNS) ---------- */
.menu-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.menu-header h4 {
    margin: 0;
    font-weight: 600;
}

.menu-count-badge {
    background: #e2e8f0;
    padding: 0.3rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
}

.menu-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.5rem;  /* Reduced from 0.9rem → 0.7rem */
    max-height: calc(100vh - 200px);
    overflow-y: auto;
    padding-right: 0.3rem;
}
.menu-card {
    background: white;
    border: 1px solid #edf2f7;
    border-radius: 10px;  /* 12px → 10px */
    padding: 0.6rem;      /* 1rem → 0.8rem */
    transition: all 0.2s;
    cursor: pointer;
    width: 100%;
}

.menu-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    background: #fafafa;
}

.menu-name {
    font-weight: 600;
    color: #0f172a;
    font-size: 0.95rem;
    margin-bottom: 0.3rem;
    line-height: 1.3rem;
    
    /* 2 LINES MAX */
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    
    /* FIXED HEIGHT PREVENTS CARD SHIFTING */
    min-height: 2.6rem;
    max-height: 2.6rem;
}

.menu-price {
    color: #2563eb;
    font-weight: 600;
    font-size: 0.9rem;
}

.menu-desc {
    font-size: 0.7rem;
    color: #64748b;
    margin-top: 0.4rem;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ---------- RIGHT COLUMN - ORDER CART (PULLED LEFT) ---------- */
.order-card {
    background: rgb(103, 160, 239);
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(255, 255, 255, 0.04);
    border: 1px solid #ffffff;
    display: flex;
    flex-direction: column;
    height: fit-content;
    max-height: calc(100vh - 200px);
    position: sticky;
    top: 90px;
    width: 100%;
    margin-left: -10px;  /* Pull slightly left to eliminate white line */
}

.order-content {
    padding: 1rem;        /* 1.2rem → 1rem */
    overflow-y: auto;
}

.order-title {
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.8rem;
    border-bottom: 1px solid #ffffff;
    font-size: 1rem;
    color: white;
}

/* Order Info Fields - More Compact */
.order-fields {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;          /* 0.6rem → 0.5rem */
    padding: 0.8rem;      /* 0.9rem → 0.8rem */
    border-radius: 8px;   /* 10px → 8px */
    margin-bottom: 1rem;  /* 1.2rem → 1rem */
}

.field-group {
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
}

.field-group label {
    font-size: 0.65rem;
    text-transform: uppercase;
    font-weight: 600;
    color: #ffffff;
    letter-spacing: 0.3px;
}

.field-group input {
    padding: 0.5rem 0.5rem;
    border: 1px solid #fefefe;
    border-radius: 6px;
    font-size: 0.85rem;
    background: transparent;
    color:white;
}

/* Ordered Items - More Compact */
.ordered-items-container {
    max-height: 700px;
    overflow-y: auto;
    margin-bottom: 0.9rem;
}

.ordered-item {
    background: #f8fafc;
    border-radius: 6px;   /* 8px → 6px */
    padding: 0.7rem;      /* 0.8rem → 0.7rem */
    margin-bottom: 0.5rem; /* 0.6rem → 0.5rem */
}

.item-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.6rem;
}

.item-name {
    font-weight: 600;
    font-size: 0.9rem;
}

.item-price-small {
    font-size: 0.7rem;
    color: #64748b;
}

.item-controls {
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.item-qty-input {
    width: 55px;
    padding: 0.3rem 0.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 5px;
    font-size: 0.85rem;
}

.item-subtotal {
    margin-left: auto;
    font-weight: 600;
    font-size: 0.9rem;
}

.remove-btn {
    color: #94a3b8;
    background: transparent;
    border: none;
    font-size: 1rem;
    padding: 0.2rem 0.5rem;
    border-radius: 5px;
    cursor: pointer;
}

.remove-btn:hover {
    background: #fee2e2;
    color: #ef4444;
}

/* Empty Cart */
.empty-cart {
    text-align: center;
    padding: 1.5rem;
    color: #94a3b8;
    background: #f8fafc;
    border-radius: 8px;
    font-size: 0.9rem;
}

/* Total Section */
.total-section {
    display: flex;
    justify-content: space-between;
    font-weight: 600;
    padding-top: 0.8rem;
    border-top: 1px solid #edf2f7;
    color:white;
    font-size: 1rem;
}

/* Place Order Button */
.place-order-btn {
    background: #0f172a;
    color: white;
    border: none;
    padding: 0.8rem;
    border-radius: 0 0 12px 12px;
    font-weight: 600;
    transition: all 0.2s;
    cursor: pointer;
    font-size: 0.95rem;
}

.place-order-btn:hover {
    background: #75a5f3;
}

/* No Items Message */
.no-items-message {
    grid-column: 1/-1;
    text-align: center;
    padding: 2rem;
    color: #64748b;
}

/* ---------- RESPONSIVE ---------- */
@media (max-width: 1400px) {
    .menu-grid {
        grid-template-columns: repeat(3, 1fr);  /* 3 columns on smaller screens */
    }
}

@media (max-width: 1200px) {
    .order-three-column {
    display: grid;
    grid-template-columns: 260px 1fr 380px;  /* 220px → 260px */
}
    
    .menu-grid {
        grid-template-columns: repeat(2, 1fr);  /* 2 columns on tablet */
    }
}

@media (max-width: 992px) {
    .order-three-column {
        grid-template-columns: 1fr;
        gap: 1.2rem;
    }
    
    .order-card {
        position: relative;
        top: 0;
        margin-left: 0;  /* Reset margin on mobile */
    }
    
    .menu-grid {
        grid-template-columns: repeat(3, 1fr);  /* Back to 3 on mobile */
    }
}

@media (max-width: 768px) {
    .menu-grid {
        grid-template-columns: repeat(2, 1fr);  /* 2 columns on small mobile */
    }
}

/* Icons fallback */
[class*="icon-"] {
    display: inline-block;
    margin-right: 6px;
}

.icon-list:before { content: "📋"; }
.icon-basket:before { content: "🛒"; }
.icon-check:before { content: "✓"; }

/* Remove any extra white space */
.container-fluid {
    padding-left: 0 !important;
    padding-right: 0 !important;
}

.page-content .container-fluid {
    width: 100%;
    overflow-x: hidden;
}

/* Force no horizontal scroll */
body {
    overflow-x: hidden;
}

.item-qty-input {
    width: 70px;
    padding: 0.4rem 0.6rem;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 0.85rem;
    /* PREVENT FOCUS LOSS STYLE */
    transition: none;
}

.item-qty-input:focus {
    outline: 2px solid #0f172a;
    outline-offset: 1px;
}
/* ========== END ORDER SYSTEM STYLES ========== */
</style>