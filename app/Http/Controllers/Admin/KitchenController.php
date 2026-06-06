<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\KitchenStat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KitchenController extends Controller
{
    public function index()
    {
        return view('admin.kitchen');
    }

    public function getOrders()
    {
        $items = OrderItem::with(['order.waiter'])
            ->where('product_type', 'menu')
            ->where('status', '!=', 'served')
            ->get();

        // Group by order_number + status
        $grouped = $items->groupBy(function ($item) {
            return $item->order->order_number . '_' . $item->status;
        });

        $result = $grouped->map(function ($group) {

            $firstItem = $group->first();
            $order = $firstItem->order;

            return [
                'order_number' => $order->order_number,
                'table_no'     => $order->table_no,
                'waiter'       => $order->waiter->name ?? '-',
                'status'       => $firstItem->status,
                'created_at'   => $order->created_at,


                'items' => $group->map(function ($item) {
                    return [
                        'id'       => $item->id,
                        'name'     => $item->name,
                        'quantity' => $item->quantity,
                        'notes'    => $item->notes,
                        'status'   => $item->status,
                        'created_at' => $item->created_at,
                        'prep_time' => $item->prep_time,
                        'updated_at' => $item->updated_at,
                        'is_flagged' => $item->is_flagged,
                    ];
                })->values()
            ];
        })->values();

        return response()->json($result);
    }

    public function updateStatus(Request $request)
    {
        $orderNumber = $request->order_number;
        $currentStatus = $request->current_status;

        $nextStatus = match ($currentStatus) {
            'pending' => 'cooking',
            'cooking' => 'ready',
            'ready'   => 'served',
            default   => null,
        };

        if (!$nextStatus) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        // Get the specific item IDs from the request
        $itemIds = $request->item_ids ?? [];

        // Update only the items that are inside this card
        OrderItem::whereIn('id', $itemIds)
            ->where('status', $currentStatus)
            ->update(['status' => $nextStatus]);


        /* ---------------------------------------
       CREATE STAT WHEN COOKING STARTS
         --------------------------------------- */
        if ($currentStatus === 'pending' && $nextStatus === 'cooking') {

            $totalItems = OrderItem::whereHas('order', function ($q) use ($orderNumber) {
                $q->where('order_number', $orderNumber);
            })->count();

            KitchenStat::updateOrCreate(
                ['order_number' => $orderNumber],
                [
                    'total_items' => $totalItems,
                    'on_time_items' => 0,
                    'late_items' => 0,
                    'chef_name' => Auth::user()->name,
                    'date_created' => now()
                ]
            );
        }
        /* ---------------------------------------
       UPDATE STAT WHEN ORDER IS READY
        --------------------------------------- */
        if ($currentStatus === 'cooking' && $nextStatus === 'ready') {

            // FIRST: move ALL cooking items of this order to READY
            OrderItem::whereHas('order', function ($q) use ($orderNumber) {
                $q->where('order_number', $orderNumber);
            })
                ->where('status', 'cooking')
                ->update(['status' => 'ready']);

            // THEN: calculate late items
            $items = OrderItem::whereHas('order', function ($q) use ($orderNumber) {
                $q->where('order_number', $orderNumber);
            })->get();

            $lateItems = 0;

            foreach ($items as $item) {
                $expectedFinish = $item->created_at->addMinutes($item->prep_time);
                if (now()->gt($expectedFinish)) {
                    $lateItems++;
                }
            }

            KitchenStat::updateOrCreate(
                ['order_number' => $orderNumber],
                [
                    'total_items' => $items->count(),
                    'on_time_items' => $items->count() - $lateItems,
                    'late_items' => $lateItems,
                    'finished_at' => now(),
                    'chef_name' => Auth::user()->name
                ]
            );
        }

        return response()->json(['success' => true]);
    }


    public function lateOrders()
    {
        // Fetch today's orders
        $todayOrders = Order::with('items')->whereDate('created_at', now())->get();

        $lateOrders = [];

        foreach ($todayOrders as $order) {
            $maxPrepTime = 0;
            $lateItemsCount = 0;

            foreach ($order->items as $item) {
                $itemFinishTime = $item->created_at->addMinutes($item->prep_time);
                if (now()->gt($itemFinishTime)) {
                    $lateItemsCount++;
                }
                if ($item->prep_time > $maxPrepTime) {
                    $maxPrepTime = $item->prep_time;
                }
            }

            $stats = KitchenStat::where('order_number', $order->order_number)->first();
            if (!$stats || !$stats->finished_at) {
                KitchenStat::updateOrCreate(
                    ['order_number' => $order->order_number, 'date_created' => now()->format('Y-m-d')],
                    [
                        'total_items' => $order->items->count(),
                        'on_time_items' => $order->items->count() - $lateItemsCount,
                        'late_items' => $lateItemsCount,
                        'chef_name' => Auth::user()->name,
                    ]
                );
            }

            // $later = KitchenStat::all();
        }

        return view('admin.late_orders', compact('lateOrders', 'stats'));
    }
}
