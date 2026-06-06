<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Cat;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Item;
use App\Models\Expense;

class AdminController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();
        $now = now();

        // Get all today's orders with payment_status = 1
        $orders = Order::whereBetween('created_at', [$today, $now])
            ->where('Payment_status', 1)
            ->get();

        $expenses = Expense::whereBetween('created_at', [$today, $now])->get();
        $expense = $expenses->sum('amt');

        $gross_pro = $orders->sum(function ($o) {
            return $o->orderItems->sum(fn($oi) => $oi->subtotal - $oi->total_cost);
        });

        $profit = $gross_pro - $expenses->sum('amt');



        // Low stock items
        $lowStock = Item::whereColumn('qty', '<=', 'min_stock')->count();

        // Combine cash and bank in one query to reduce DB hits
        $payments = Payment::whereBetween('created_at', [$today, $now])
            ->selectRaw("method, type, SUM(amount) as total_amount")
            ->groupBy('method', 'type')
            ->get();

        // Initialize
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

        $hourlyGrossData = [];
        $hourlyNetData   = [];
        $hourlyExpenseData = [];
        $hourlyTotalSaleData = [];
        $hourlyLabels = [];
        for ($i = 0; $i < 24; $i++) {
            $hourlyLabels[] = $i . ":00";
        }
        for ($i = 0; $i < 24; $i++) {
            $ordersInHour = $orders->filter(fn($o) => $o->created_at->hour == $i);

            $hourlyTotalSaleData[] = $ordersInHour->sum('total_amount');

            $hourlyGrossData[] = $ordersInHour->sum(
                fn($o) =>
                $o->orderItems->sum(fn($oi) => $oi->subtotal - $oi->total_cost)
            );

            $hourlyExpenseData[] = $expenses->filter(fn($e) => $e->created_at->hour == $i)->sum('amt');

            $hourlyNetData[] = end($hourlyGrossData) - end($hourlyExpenseData);
        }

        $pieData = [
            'labels' => ['Cash', 'Bank'],
            'values' => [$cash, $bank],
        ];
        $item = Item::with(['unit', 'category'])->whereColumn('qty', '<=', 'min_stock')->get(); // items with unit & category
        $totalSales = $orders->sum('total_amount');  // total revenue
        $totalOrders = $orders->count();
        return view('admin.home', compact(
            'totalSales',
            'totalOrders',
            'profit',
            'lowStock',
            'cash',
            'bank',
            'cashIn',
            'cashOut',
            'bankIn',
            'bankOut',
            'hourlyLabels',
            'gross_pro',
            'expense',
            'pieData',
            'item',
            'hourlyTotalSaleData',
            'hourlyGrossData',
            'hourlyNetData',
            'hourlyExpenseData',
        ));
    }


    public function user_show()
    {
        $data = User::all()->where('role', '!=', 'admin');
        $count = User::count();
        return view('admin.user', compact('data', 'count'));
    }
    public function active($id)
    {
        $active = User::find($id);
        $active->active = 1;
        $active->save();
        return redirect()->back()->with('message', 'You activate the User');
    }
    public function deactive($id)
    {
        $data = User::find($id);
        $data->active = 0;
        $data->save();
        return redirect()->back()->with('message', 'You have Deactivate the user');
    }
    public function delete($id)
    {
        $delete = User::find($id);
        $delete->delete();
        return redirect()->back()->with('message', 'You have delete the user');
    }
    public function edit($id)
    {
        $edit = User::find($id);
        return view('admin.edit', compact('edit'));
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $user->name = $request->flname;
        $user->email = $request->email;
        $user->username = $request->urname;
        $user->phone = $request->phone;
        $user->role = $request->role;
        $user->salary = $request->salary;

        $user->save();

        if ($user) {
            return redirect()->route('admin.user')->with('message', 'You have successfully update the user');
        } else {
            return redirect()->back();
        }
    }
    public function change_pass($id)
    {
        $user = User::find($id);
        return view('admin.change_pass', compact('user'));
    }
    public function pass(Request $request, $id)
    {
        $pass = User::find($id);
        $passs = Hash::make($request->password);
        $pass->password = $passs;
        $pass->save();

        return redirect()->route('admin.user')->with('message', 'You have chnage the password!!!');
    }
}
