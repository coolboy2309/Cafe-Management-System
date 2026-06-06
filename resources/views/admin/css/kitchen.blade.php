<style>
    .content-body {
        padding: 0 !important;
    }

    .kitchen-wrapper {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        height: calc(100vh - 120px);
        padding: 20px;
    }

    /* COLUMN */
    .kitchen-column {
        background: #ffffff;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    /* HEADER */
    .column-header {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        padding: 15px;
        color: white;
    }

    /* COLORS */
    .column-header.new {
        background: #e74c3c;
    }

    .column-header.cooking {
        background: #f39c12;
    }

    .column-header.ready {
        background: #27ae60;
    }

    /* ORDER LIST */
    .order-list {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
    }

    /*   .//////////////////////////     */

    .order-card {
        background: #2b2b3c;
        color: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;

        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        transition: 0.3s;
    }

    .order-card:hover {
        transform: scale(1.02);
    }

    /* ORDER INFO */
    .order-title {
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 8px;
    }

    .order-items {
        font-size: 14px;
        margin: 10px 0;
    }

    /* BUTTON AREA */
    .order-actions {
        display: flex;
        gap: 10px;
    }

    .order-actions button {
        flex: 1;
        border: none;
        padding: 8px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }

    /* BUTTON COLORS */
    .btn-start {
        background: #3498db;
        color: white;
    }

    .btn-ready {
        background: #27ae60;
        color: white;
    }

    .btn-serve {
        background: #9b59b6;
        color: white;
    }

    .order-card {
        position: relative;
        /* make positioning for the time work */
    }


    .order-card.moving {
        transition: transform 0.5s ease, opacity 0.5s ease;
        transform: translateY(-10px);
        opacity: 0.7;
    }

    .order-extra {
        font-size: 13px;
        color: #f1c40f;
        /* yellow-ish so it stands out */
        margin-top: 8px;
        font-style: italic;
    }

    .search-wrapper {
        padding: 10px;
        background: #ffffff;
    }

    .search-wrapper input.order-search {
        width: 100%;
        padding: 8px 12px;
        border-radius: 8px;
        border: none;
        outline: none;
        font-size: 14px;
        background: #59b4f5;
        color: white;
    }

    .search-wrapper input.order-search::placeholder {
        color: #ffffff;
    }

    .search-wrapper input.order-search:focus {
        box-shadow: 0 0 8px #3498db;
    }

    @media (max-width: 768px) {
        .kitchen-wrapper {
            padding: 10px;
            gap: 10px;
        }

        .column-header {
            font-size: 18px;
        }

        .order-title {
            font-size: 15px;
        }

        .order-items,
        .order-extra {
            font-size: 13px;
        }

        .order-actions button {
            padding: 6px;
            font-size: 13px;
        }
    }

    .order-actions button:hover {
        transform: scale(1.05);
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.5);
    }

    /* Order count badge */
    .order-count {
        background: #ffffff;
        /* bright red for visibility */
        color: black;
        font-weight: bold;
        border-radius: 50%;
        padding: 4px 10px;
        font-size: 13px;
        margin-left: 8px;
        vertical-align: middle;
    }

    /* Print button in column header */
    .btn-print {
        background: #3498db;
        /* blue button */
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: bold;
        cursor: pointer;
        float: right;
        /* position to the right of header */
        transition: 0.3s;
    }

    .btn-print:hover {
        background: #2980b9;
        /* darker blue on hover */
        transform: scale(1.05);
    }

    /* Card border animation when urgent */
    .order-card.urgent {
        border: 4px solid red;
        animation: pulseBorder 1s infinite alternate;
        position: relative;
    }

    /* Timer top-right */
    .order-time {
        position: absolute;
        top: 5px;
        right: 10px;
        font-weight: bold;
        color: #fffbfb;
    }

    /* Urgent badge bottom-right */
    .urgent-badge {
        display: none;
        /* hidden initially */
        position: absolute;
        bottom: 5px;
        right: 10px;
        background-color: red;
        color: white;
        padding: 2px 6px;
        font-size: 12px;
        font-weight: bold;
        border-radius: 4px;
        animation: pulseBadge 1s infinite alternate;
    }

    /* Pulse animation for border */
    @keyframes pulseBorder {
        0% {
            border-color: red;
        }

        100% {
            border-color: darkred;
        }
    }

    /* Pulse animation for badge */
    @keyframes pulseBadge {
        0% {
            transform: scale(1);
        }

        100% {
            transform: scale(1.1);
        }
    }
    @keyframes flashRed {
    0% { border-color: red; }
    100% { border-color: darkred; }
}
</style>