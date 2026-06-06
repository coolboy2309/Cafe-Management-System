<!DOCTYPE html>
<html>

<head>
    <title>Receipt</title>
    <style>
        body {
            font-family: monospace;
            width: 300px;
            /* slightly wider than before */
            margin: 20px auto;
            color: #000;
        }

        .center {
            text-align: center;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        table {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 4px 6px;
        }

        th {
            text-align: left;
        }

        td.qty {
            text-align: center;
            width: 40px;
        }

        td.price {
            text-align: right;
            width: 70px;
        }

        .total-row td {
            font-weight: bold;
            font-size: 14px;
        }

        .receipt-container {
            padding: 0 10px;
            /* spacing left/right */
        }

        .thank-you {
            margin-top: 15px;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <div class="center">
            <h3>Abenu Cafe & Restaurants</h3>
            <p>AB Technologiest.plc</p>
            <p>+251900129798</p>
        </div>

        <hr>

        <p>Table : {{ $order->table_no }}<span style="float: right;">{{ $order->created_at->isoFormat('MMM D YYYY, h:mm A') ?? '-' }}</p>
        <p>Casher : {{ $order->casher->name ?? '-' }} <span style="float: right;"> Waiter : {{ $order->waiter->name ?? '-' }}</span></p>
        <!-- <p>Waiter : {{ $order->waiter->name ?? '-' }}</p> -->

        <hr>

        <table>
            <tr>
                <th>Item</th>
                <th class="qty">Qty</th>
                <th class="price">Price</th>
            </tr>

            @php
            $mergedItems = [];
            foreach ($order->items as $item) {
            if (!isset($mergedItems[$item->name])) {
            $mergedItems[$item->name] = [
            'quantity' => 0,
            'subtotal' => 0,
            ];
            }
            $mergedItems[$item->name]['quantity'] += $item->quantity;
            $mergedItems[$item->name]['subtotal'] += $item->subtotal;
            }
            $total = 0;
            @endphp

            @foreach($mergedItems as $name => $data)
            <tr>
                <td>{{ $name }}</td>
                <td class="qty">{{ $data['quantity'] }}</td>
                <td class="price"> {{ number_format($data['subtotal']) }}</td>
            </tr>
            @php $total += $data['subtotal']; @endphp
            @endforeach
        </table>

        <hr>

        <table>
            <tr class="total-row">
                <td>Total</td>
                <td></td>
                <td class="price">Br {{ number_format($total) }}</td>
            </tr>
        </table>

        <hr>

        <div class="center thank-you">
            <p>Thank You!</p>
        </div>

        <div class="center">
            <button onclick="window.print()">Print</button>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 800);
        }
    </script>
</body>

</html>