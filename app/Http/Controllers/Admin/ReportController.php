<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Item;
use App\Models\Expense;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use App\Models\StockMovement;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);
        $start = Carbon::create($year, $month, 1)->startOfDay();
        $end   = $start->copy()->endOfMonth()->endOfDay();
        $orders = Order::whereBetween('created_at', [$start, $end])
            ->where('Payment_status', 1)
            ->with(['orderItems.item'])
            ->get();
        $totalOrders = $orders->count();
        $totalSales = $orders->sum('total_amount');
        $avgOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        $grossProfit = $orders->sum(fn($o) => $o->orderItems->sum(fn($item) => $item->subtotal - ($item->total_cost ?? 0)));
        $expense = Expense::whereBetween('created_at', [$start, $end])->sum('amt');
        $netProfit   = $grossProfit - $expense;
        $profitMargin = $totalSales > 0 ? ($netProfit / $totalSales) * 100 : 0;

        $itemStats = [];
        foreach ($orders as $order) {
            foreach ($order->orderItems as $oi) {
                $itemId = $oi->item_id;
                if ($oi->product_type === 'menu') {
                    $name = $oi->menu->name ?? 'Menu Deleted';
                } else {
                    $name = $oi->item->name ?? 'Item Deleted';
                }
                if (!isset($itemStats[$itemId])) {
                    $itemStats[$itemId] = [
                        'name' => $name,
                        'qty' => 0,
                        'revenue' => 0,
                        'profit' => 0,
                    ];
                }
                $itemStats[$itemId]['qty'] += $oi->qty;
                $itemStats[$itemId]['revenue'] += $oi->subtotal;
                $itemStats[$itemId]['profit'] += ($oi->subtotal - ($oi->total_cost ?? 0));
            }
        }
        $topSelling = null;
        $mostProfitable = null;
        $worstItem = null;

        if (!empty($itemStats)) {
            $topSelling = collect($itemStats)->sortByDesc('qty')->first();
            $mostProfitable = collect($itemStats)->sortByDesc('profit')->first();
            $worstItem = collect($itemStats)->sortBy('profit')->first();
        }
        $barLabels = [];
        $grossData = [];
        $netData = [];
        $expenseData = [];

        $daysInMonth = $start->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::create($year, $month, $day);
            $barLabels[] = $currentDate->format('d M');
            $dailyOrders = $orders->filter(fn($o) => $o->created_at->isSameDay($currentDate));
            // $dailyGross   = $dailyOrders->sum(fn($o) => $o->orderItems->sum('subtotal'));
            $dailyGross = $dailyOrders->sum(
                fn($o) => $o->orderItems->sum(
                    fn($item) =>
                    $item->subtotal - ($item->total_cost ?? 0)
                )
            );
            $dailyExpense = Expense::whereDate('created_at', $currentDate)->sum('amt');
            $dailyNet     = $dailyGross - $dailyExpense;
            $grossData[] = $dailyGross;
            $netData[] = $dailyNet;
            $expenseData[] = $dailyExpense;
            $totalSaleData[] = $dailyOrders->sum('total_amount'); // <- new array
        }
        $bestSalesValue = max($totalSaleData);
        $bestSalesIndex = array_search($bestSalesValue, $totalSaleData);
        $bestSalesDay = $barLabels[$bestSalesIndex] ?? null;
        $payments = Payment::whereBetween('created_at', [$start, $end])
            ->selectRaw("method, type, SUM(amount) as total_amount")
            ->groupBy('method', 'type')
            ->get();
        $cashIn = $cashOut = $bankIn = $bankOut = 0;
        foreach ($payments as $p) {
            if ($p->method === 'cash') {
                if ($p->type === 'in') $cashIn += $p->total_amount;
                if ($p->type === 'out') $cashOut += $p->total_amount;
            } elseif ($p->method === 'bank') {
                if ($p->type === 'in') $bankIn += $p->total_amount;
                if ($p->type === 'out') $bankOut += $p->total_amount;
            }
        }
        $cash = $cashIn - $cashOut;
        $bank = $bankIn - $bankOut;
        $pieData = [
            'labels' => ['Cash', 'Bank'],
            'values' => [$cash, $bank],
        ];
        $totalMoney = $cash + $bank;
        $cashPercent = $totalMoney > 0 ? ($cash / $totalMoney) * 100 : 0;
        $bankPercent = $totalMoney > 0 ? ($bank / $totalMoney) * 100 : 0;
        $lastMonthStart = $start->copy()->subMonth()->startOfMonth();
        $lastMonthEnd   = $start->copy()->subMonth()->endOfMonth();
        $lastMonthOrders = Order::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->where('Payment_status', 1)
            ->with(['orderItems.item', 'orderItems.menu'])
            ->get();

        $lastMonthSales = $lastMonthOrders->sum('total_amount');

        $lastMonthGross = $lastMonthOrders->sum(
            fn($o) => $o->orderItems->sum(
                fn($item) => $item->subtotal - ($item->total_cost ?? 0)
            )
        );

        $lastMonthExpense = Expense::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->sum('amt');

        $lastMonthNet = $lastMonthGross - $lastMonthExpense;
        if ($lastMonthSales > 0) {
            $salesGrowth = (($totalSales - $lastMonthSales) / $lastMonthSales) * 100;
        } else {
            $salesGrowth = null; //  important
        }

        if ($lastMonthNet > 0) {
            $profitGrowth = (($netProfit - $lastMonthNet) / $lastMonthNet) * 100;
        } else {
            $profitGrowth = null; //  important
        }

        $insights = [];

        // Sales insight
        if ($salesGrowth !== null) {
            if ($salesGrowth > 0) {
                $insights[] = "📈 Sales increased by " . number_format($salesGrowth, 1) . "% from last month";
            } elseif ($salesGrowth < 0) {
                $insights[] = "📉 Sales decreased by " . number_format(abs($salesGrowth), 1) . "% from last month";
            }
        }
        if ($profitGrowth !== null) {

            if ($profitGrowth > 0) {
                $insights[] = "📈 Profit is growing steadily";
            } elseif ($profitGrowth < 0) {
                $insights[] = "📉 Profit is dropping, check expenses";
            }
        }

        if ($profitMargin >= 30) {

            $insights[] = "🚀 Excellent profit margin performance this month";
        } elseif ($profitMargin >= 15) {

            $insights[] = "✅ Profit margin is healthy";
        } elseif ($profitMargin < 10) {

            $insights[] = "⚠️ Profit margin is low and needs attention";
        }
        if ($expense > ($totalSales * 0.5)) {
            $insights[] = " Expenses are too high compared to sales";
        }
        $expenseRatio = $totalSales > 0
            ? ($expense / $totalSales) * 100
            : 0;
        // Monthly Report Finishde

        /// Stock report Started

        $totalItem = Item::count();
        $itemStock = Item::sum('qty');
        $lowstock = Item::whereColumn('qty', '<=', 'min_stock')
            ->where('qty', '>', 0)
            ->count();
        $outStock = Item::where('qty', '=', 0)->count();
        $inventoryValue = Item::sum(DB::raw('qty * cost'));
        $stockAlerts = Item::whereColumn('qty', '<=', 'min_stock')
            ->orderBy('qty', 'asc')
            ->get()
            ->map(function ($item) {
                // Difference
                // Status
                if ($item->qty == 0) {

                    $item->status = 'Out of Stock';
                    $item->status_color = 'danger';
                    $item->action = 'Urgent Restock';
                } elseif ($item->qty <= 5) {

                    $item->status = 'Critical';
                    $item->status_color = 'warning';
                    $item->action = 'Restock Soon';
                } else {

                    $item->status = 'Low Stock';
                    $item->status_color = 'info';
                    $item->action = 'Monitor';
                }

                return $item;
            });
        // Total healthy items
        $healthyItems = Item::whereColumn('qty', '>', 'min_stock')->count();
        // Stock Health Percentage
        $stockHealth = $totalItem > 0
            ? (($healthyItems / $totalItem) * 100)
            : 0;
        // Low Stock Percentage
        $lowStockPercent = $totalItem > 0
            ? (($lowstock / $totalItem) * 100)
            : 0;
        // Critical Restock Required
        $restockRequired = Item::where('qty', '<=', 5)->count();
        $stockInsights = [];
        // Low stock insight
        if ($lowstock > 0) {
            $stockInsights[] = "{$lowstock} items are below minimum stock level.";
        }
        // Out of stock insight
        if ($outStock > 0) {
            $stockInsights[] = "{$outStock} items are currently unavailable for sale.";
        }
        // Healthy inventory insight
        if ($stockHealth >= 80) {
            $stockInsights[] = "Most inventory items are currently within healthy stock levels.";
        }
        // Critical restock insight
        if ($restockRequired > 0) {
            $stockInsights[] = "Some products require immediate restocking.";
        }
        // Inventory value insight
        if ($inventoryValue > 0) {
            $stockInsights[] = "Current inventory value is " . number_format($inventoryValue, 2) . " Br.";
        }
        // Low stock percentage warning
        if ($lowStockPercent >= 30) {
            $stockInsights[] = "A high percentage of inventory items are running low.";
        }
        // Safe fallback message
        if (empty($stockInsights)) {
            $stockInsights[] = "Inventory levels are currently stable with no major stock risks detected.";
        }

        $query = StockMovement::with(['item', 'location', 'supplier'])
            ->whereDate('created_at', now()->toDateString());

        $stock = $query->latest()->get();
        return view('admin.reportsheet', compact(
            'totalSales',
            'totalOrders',
            'expenseRatio',
            'grossProfit',
            'netProfit',
            'expense',
            'barLabels',
            'grossData',
            'netData',
            'expenseData',
            'totalSaleData',
            'pieData',
            'month',
            'year',
            'avgOrderValue',
            'profitMargin',
            'salesGrowth',
            'profitGrowth',
            'topSelling',
            'mostProfitable',
            'worstItem',
            'cashPercent',
            'bankPercent',
            'insights',
            'bestSalesDay',
            'bestSalesValue',

            'totalItem',
            'itemStock',
            'lowstock',
            'outStock',
            'inventoryValue',
            'stockAlerts',
            'stockHealth',
            'lowStockPercent',
            'restockRequired',
            'stockInsights',
            'stock',
        ));
    }
}
