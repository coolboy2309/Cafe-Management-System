<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Expense;

class AdminController extends Controller
{
    public function dailyReport()
    {
        $today = now()->startOfDay();
        $now = now();

        $orders = Order::with('orderItems')
            ->whereBetween('created_at', [$today, $now])
            ->where('Payment_status', 1)
            ->get();

        $expenses = Expense::whereBetween('created_at', [$today, $now])->get();

        $expense = $expenses->sum('amt');

        $gross_pro = $orders->sum(function ($o) {
            return $o->orderItems->sum(fn($oi) => $oi->subtotal - $oi->total_cost);
        });

        $profit = $gross_pro - $expense;

        $payments = Payment::whereBetween('created_at', [$today, $now])
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

        $hourly = [];

        for ($i = 0; $i < 24; $i++) {
            $ordersInHour = $orders->filter(fn($o) => $o->created_at->hour == $i);

            $sales = $ordersInHour->sum('total_amount');

            $gross = $ordersInHour->sum(
                fn($o) => $o->orderItems->sum(fn($oi) => $oi->subtotal - $oi->total_cost)
            );

            $exp = $expenses->filter(fn($e) => $e->created_at->hour == $i)->sum('amt');

            $hourly[] = [
                'hour' => $i,
                'sales' => $sales,
                'gross' => $gross,
                'net' => $gross - $exp,
                'expense' => $exp,
            ];
        }

        return response()->json([
            'totalSales' => $orders->sum('total_amount'),
            'totalOrders' => $orders->count(),
            'profit' => $profit,
            'cash' => $cash,
            'bank' => $bank,
            'expense' => $expense,
            'gross' => $gross_pro,

            'cashIn' => $cashIn,
            'cashOut' => $cashOut,
            'bankIn' => $bankIn,
            'bankOut' => $bankOut,

            'hourly' => $hourly,
        ]);
    }
}
