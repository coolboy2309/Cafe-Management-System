<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function monthlyReport(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);

        $start = Carbon::create($year, $month, 1)->startOfDay();
        $end   = $start->copy()->endOfMonth()->endOfDay();

        // Orders
        $orders = Order::whereBetween('created_at', [$start, $end])
            ->where('Payment_status', 1)
            ->with('orderItems')
            ->get();

        // KPI
        $totalOrders = $orders->count();
        $totalSales = $orders->sum('total_amount');

        $grossProfit = $orders->sum(function ($o) {
            return $o->orderItems->sum(function ($item) {
                return $item->subtotal - ($item->total_cost ?? 0);
            });
        });

        $expense = Expense::whereBetween('created_at', [$start, $end])->sum('amt');

        $netProfit = $grossProfit - $expense;

        $profitMargin = $totalSales > 0
            ? ($netProfit / $totalSales) * 100
            : 0;

        // DAILY DATA (FOR GRAPH)
        $days = $start->daysInMonth;

        $labels = [];
        $sales = [];
        $net = [];
        $expenses = [];

        for ($d = 1; $d <= $days; $d++) {
            $date = Carbon::create($year, $month, $d);

            $labels[] = $date->format('d');

            $dayOrders = $orders->filter(fn($o) => $o->created_at->isSameDay($date));

            $sales[] = $dayOrders->sum('total_amount');

            $dayExpense = Expense::whereDate('created_at', $date)->sum('amt');
            $expenses[] = $dayExpense;

            $net[] = $dayOrders->sum('total_amount') - $dayExpense;
        }

        // PAYMENT PIE
        $payments = Payment::whereBetween('created_at', [$start, $end])->get();

        $cash = $payments->where('method', 'cash')->sum('amount');
        $bank = $payments->where('method', 'bank')->sum('amount');

        return response()->json([
            "status" => true,

            "kpi" => [
                "totalSales" => $totalSales,
                "totalOrders" => $totalOrders,
                "grossProfit" => $grossProfit,
                "netProfit" => $netProfit,
                "expense" => $expense,
                "profitMargin" => $profitMargin,
            ],

            "chart" => [
                "labels" => $labels,
                "sales" => $sales,
                "net" => $net,
                "expense" => $expenses,
            ],

            "payment" => [
                "cash" => $cash,
                "bank" => $bank,
            ]
        ]);
    }
}