<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use App\Models\Cat;
use App\Models\Item;
use App\Models\StockMovement;
use App\Models\Unit;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use App\Models\Location;
use App\Models\Stock;
use App\Models\MenuIngredient;
use Illuminate\Support\Facades\Cache;

class StoreController extends Controller
{

    public function order()
    {
        $categories = Cat::whereNotIn('type',['Ingredient'])->get();
        $waiter = User::where('role', 'waiter')->get();
        return view('admin.order', compact('categories', 'waiter'));
    }

    public function store()
    {
        $cat = Cat::where('type', '!=', 'Food')->orderby('type', 'desc')->get(); // all categories
        $unit = Unit::all();                         // all units
        $item = Item::with(['unit', 'category'])->get(); // items with unit & category
        $sup = Supplier::all();
        $locations = Location::all(); // for the transfer modal
        return view('admin.store', compact('cat', 'unit', 'item', 'locations', 'sup'));
    }
    //// item
    public function storeAdd(Request $request)
    {
        $request->validate([
            'storeName' => 'required|string',
            'storeUnit' => 'required|exists:units,id',
            'storeCat' => 'required|exists:cats,id',
            'price' => 'required|numeric',
            'cost' => 'required|numeric',
            'alert' => 'nullable|numeric',

        ]);

        Item::create([
            'name' => $request->storeName,
            'unit_id' => $request->storeUnit,
            'min_stock' => $request->alert,
            'cat_id' => $request->storeCat,
            'price' => $request->price,
            'cost' => $request->cost,
            'to_make' => 2,
            'qty' => 0, // ✅ always start from 0
        ]);

        return redirect()->back()->with('message', 'Item created successfully!');
    }

    public function addStockMovement(Request $request)
    {
        // Validate the input
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'location_id' => 'required|exists:locations,id',
            'quantity' => 'required|numeric',
            'direction' => 'required|in:IN,OUT',
            'reason' => 'required',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        // Create the stock movement
        StockMovement::create([
            'item_id' => $request->item_id,
            'location_id' => $request->location_id,
            'quantity' => $request->quantity,
            'direction' => $request->direction,
            'reason' => $request->reason,
            'supplier_id' => $request->supplier_id,
        ]);

        return redirect()->back()->with('message', 'Stock movement added!');
    }

    public function active_i($id)
    {
        $find = Item::find($id);
        $find->is_active = 1;
        $find->save();
        return redirect()->back();
    }

    public function deactive_i($id)
    {
        $findd = Item::find($id);
        $findd->is_active = 0;
        $findd->save();
        return redirect()->back();
    }

    public function deleitem($id)
    {
        $del = Item::find($id);
        $del->delete();
        return redirect()->back();
    }
    //// unit
    public function c_unit(Request $request)
    {
        $unit = Unit::all();
        $editunit = null;

        if ($request->has('edit')) {
            $editunit = Unit::findOrFail($request->edit);
        }

        return view('admin.unit', compact('unit', 'editunit'));
    }

    public function create_unit(Request $request)
    {
        $u = new Unit;
        $u->name = $request->unit_name;
        $u->symbol = $request->unit_symbol;

        $u->save();

        return redirect()->back()->with('message', 'Done!!');
    }

    public function delete_u($id)
    {
        $de = Unit::find($id);
        $de->delete();
        return redirect()->back();
    }

    public function update_unit(Request $request, $id)
    {
        $request->validate([
            'unit_name' => 'required',
            'unit_symbol' => 'required',
        ]);

        $u = Unit::findOrFail($id);
        $u->name = $request->unit_name;
        $u->symbol = $request->unit_symbol;
        $u->save();

        return redirect()->back()->with('message', 'Unit updated!');
    }
    ///// supplier 
    public function supplier(Request $request)
    {
        $supplier = Supplier::all();
        $edit_s = null;

        if ($request->has('edit')) {
            $edit_s = Supplier::findOrFail($request->edit);
        }

        return view('admin.supplier', compact('supplier', 'edit_s'));
    }

    public function create_s(Request $request)
    {
        $sup = new Supplier;
        $sup->name = $request->s_name;
        $sup->email = $request->s_email;
        $sup->phone = $request->s_phone;

        $sup->save();

        return redirect()->back()->with('message', 'Done!!');
    }

    public function delete_s($id)
    {
        $de = Supplier::find($id);
        $de->delete();
        return redirect()->back();
    }

    public function update_s(Request $request, $id)
    {

        $u = Supplier::findOrFail($id);
        $u->name = $request->s_name;
        $u->email = $request->s_email;
        $u->phone = $request->s_phone;
        $u->save();

        return redirect()->back()->with('message', 'Supplier updated!');
    }
    ////// stock
    public function stock()
    {
        $stock = StockMovement::with(['item'])->get();

        return view('admin.stock', compact('stock'));
    }
    ////// order page
    public function productsByCategory($id)
    {
        $category = Cat::findOrFail($id);
        $barLocation = Location::where('name', 'bar')->first();

        if ($category->type === 'Food') {
            $products = Menu::where('cat_id', $id)
                ->select('id', 'name', 'price')
                ->get();
        } else {
            $products = Item::where('cat_id', $id)
                ->where('is_active', 1)
                ->get()
                ->map(function ($item) use ($barLocation) {
                    $currentStock = 0;

                    if ($barLocation) {
                        $currentStock = StockMovement::where('item_id', $item->id)
                            ->where('location_id', $barLocation->id)
                            ->sum(DB::raw("CASE WHEN direction = 'IN' THEN quantity ELSE -quantity END"));
                    }
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'price' => $item->price,
                        'current_bar_stock' => $currentStock
                    ];
                });
        }

        return response()->json($products);
    }
    ///  new order
    public function order_store(Request $request)
    {
        // Validate required fields
        $request->validate([
            'table_no' => 'required|integer|min:1',
            'waiter_id' => 'required|exists:users,id', // assumes waiter IDs are in users table
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0.01',
        ]);

        $data = $request->all();
        $tableNo = $request->table_no;

        // Only check if this is a new order (not updating existing order)
        if (empty($request->order_id)) {
            $existingOrder = Order::where('table_no', $tableNo)
                ->where('is_billed', 0)
                ->first();

            if ($existingOrder) {
                return response()->json([
                    'success' => false,
                    'message' => "Table number {$tableNo} already has an active order!"
                ], 422);
            }
        }
        DB::beginTransaction();

        try {
            $order = null;
            if (!empty($data['order_id'])) {
                // Existing order: append items
                $order = Order::findOrFail($data['order_id']);
            } else {
                do {
                    $order_noo = 'ORD-' . date('ymd') . '-' . strtoupper(Str::random(3));
                } while (Order::where('order_number', $order_noo)->exists());

                // Create new order
                $order = Order::create([
                    'order_number' => $order_noo,
                    'user_id' => Auth::id(),
                    'waiter_id' => $request->waiter_id,
                    'table_no' => $data['table_no'],
                    'order_type' => 'dine_in',
                    'total_amount' => 0,
                    'is_flagged' => 0,
                    'is_billed' => 0,
                ]);
            }



            $totalAmount = 0;

            foreach ($data['items'] as $item) {

                $isCustom = isset($item['id']) && str_starts_with($item['id'], 'custom_');

                $unitPrice = $item['price'];
                $quantity = $item['quantity'];
                $subtotal = $unitPrice * $quantity;
                $totalAmount += $subtotal;

                $productType = null;
                $toMake = 1; // safe default

                if (!$isCustom) {

                    // Try finding in menu first
                    $menu = Menu::find($item['id']);

                    if ($menu) {
                        $productType = 'menu';
                        $toMake = $menu->to_make;
                        $prep_time = $menu->prep_time;
                    } else {
                        // Try item table
                        $itemModel = Item::find($item['id']);

                        if ($itemModel) {
                            $productType = 'item';
                            $toMake = $itemModel->to_make;
                        } else {
                            continue; // skip invalid product
                        }
                    }
                } else {
                    $productType = 'custom';
                    $toMake = 1; // custom default for now
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $isCustom ? null : $item['id'],
                    'product_type' => $productType,
                    'name' => $item['name'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'to_make' => $toMake,
                    'prep_time' => $prep_time,
                ]);
            }


            $totalAmount = OrderItem::where('order_id', $order->id)
                ->sum('subtotal');

            $order->total_amount = $totalAmount;
            $order->save();
            ///////////////////

            // Get fresh order items
            $order->load('orderItems');

            // Group by to_make
            $grouped = $order->orderItems->groupBy('to_make');

            // Department map (no hardcoding in blade)
            $departments = [
                1 => 'Kitchen',
                2 => 'Bar',
                3 => 'Barista',
                4 => 'Other',
            ];

            $printData = [];

            foreach ($grouped as $key => $items) {
                $printData[] = [
                    'department' => $departments[$key] ?? 'Unknown',
                    'to_make' => $key,
                    'items' => $items->map(function ($i) {
                        return [
                            'name' => $i->name,
                            'qty' => $i->quantity,
                        ];
                    })->values()
                ];
            }


            ////////////////////
            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'table_no' => $order->table_no,
                'waiter_name' => optional($order->waiter)->name,
                'cashier_name' => optional($order->user)->name,
                'print_data' => $printData
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    ///// fetch order in exist order 
    /////GET////
    public function addOrder($orderId)
    {
        $order = Order::with('waiter', 'orderItems')->findOrFail($orderId);

        $categories = Cat::all();
        $waiter = User::where('role', 'waiter')->get();

        $existingItemsForJs = [];

        foreach ($order->orderItems as $item) {
            $existingItemsForJs[] = [
                'id' => (string) $item->product_id,
                'name' => $item->name,
                'price' => (float) $item->unit_price,
                'quantity' => (int) $item->quantity,
                'product_type' => $item->product_type, // <<< ADD THIS
                'to_make' => $item->to_make,
            ];
        }

        return view('admin.order', [
            'categories' => $categories,
            'waiter' => $waiter,
            'order_id' => $order->id,
            'table_no' => $order->table_no,
            'waiter_id' => $order->waiter_id,
            'existingItemsForJs' => $existingItemsForJs,
        ]);
    }
    ////POST
    public function addstore(Request $request)
    {
        $request->validate([
            'table_no' => 'required|integer|min:1',
            'waiter_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1'
        ]);
        $tableNo = $request->table_no;
        if (empty($request->order_id)) {
            $existingOrder = Order::where('table_no', $tableNo)
                ->where('is_billed', 0)
                ->first();

            if ($existingOrder) {
                return response()->json([
                    'success' => false,
                    'message' => "Table number {$tableNo} already has an active order!"
                ], 422);
            }
        }
        DB::beginTransaction();

        try {

            //  Create or get existing order
            if ($request->order_id) {
                $order = Order::findOrFail($request->order_id);

                $order->update([
                    'table_no' => $request->table_no,
                    'waiter_id' => $request->waiter_id,
                ]);
            } else {
                do {
                    $order_noo = 'ORD-' . date('ymd') . '-' . strtoupper(Str::random(4));
                } while (Order::where('order_number', $order_noo)->exists());
                $order = Order::create([
                    'order_number' => $order_noo,
                    'table_no' => $request->table_no,
                    'waiter_id' => $request->waiter_id,
                    'user_id' => auth()->id(),
                ]);
            }

            //  LOOP ITEMS
            foreach ($request->items as $item) {

                // Only merge REAL products (not custom items)
                if (is_numeric($item['id'])) {

                    $productType = 'menu';
                    $menu = Menu::find((int)$item['id']);
                    $itemModel = null;

                    if (!$menu) {
                        $itemModel = Item::find((int)$item['id']);
                        if ($itemModel) $productType = 'item';
                    }

                    $toMake = $menu->to_make ?? $itemModel->to_make ?? 1;
                    $prep_time = $menu->prep_time ?? null;


                    $unitCost = $itemModel->cost ?? 0; // cost per unit
                    $totalCost = $unitCost * $item['quantity'];
                    $profit = ($item['price'] * $item['quantity']) - $totalCost;

                    if ($productType === 'menu' && $menu) {
                        $totalCost = 0;
                        $menuIngredients = MenuIngredient::where('menu_id', $menu->id)->get();
                        foreach ($menuIngredients as $mi) { ///////// it is right here okay, the problem is when the stock is inserted with the reason Order_food okay//////
                            $ingredient = Item::find($mi->item_id);
                            if (!$ingredient) continue;

                            $requiredQty = $mi->quantity * $item['quantity'];

                            $totalCost += $ingredient->cost * $requiredQty;

                        }
                        $unitCost = $item['quantity'] > 0 ? $totalCost / $item['quantity'] : 0;
                        $profit = ($item['price'] * $item['quantity']) - $totalCost;
                    }

                    OrderItem::create([
                        'order_id'     => $order->id,
                        'product_id'   => $item['id'],
                        'product_type' => $productType,
                        'name'         => $item['name'],
                        'unit_price'   => $item['price'],
                        'quantity'     => $item['quantity'],
                        'subtotal'     => $item['price'] * $item['quantity'],
                        'cost'         => $unitCost,
                        'total_cost'   => $totalCost,
                        'profit'       => $profit,
                        'to_make'      => $toMake,
                        'prep_time'    => $prep_time,
                    ]);
                    // ============================
                    //  STOCK LOGIC FOR MENUS (INGREDIENTS → KITCHEN)
                    // ============================
                    $totalCost = 0;
                    $unitCost = 0;
                    if ($productType === 'menu' && $menu) {
                        $kitchenLocation = Location::where('name', 'Kitchen')->first();
                        if (!$kitchenLocation) {
                            throw new \Exception('Kitchen location not found!');
                        }
                        $menuIngredients = MenuIngredient::where('menu_id', $menu->id)->get();
                        foreach ($menuIngredients as $mi) {
                            $requiredQty = $mi->quantity * $item['quantity']; // ✅ recalc here

                            //  NO VALIDATION (allow negative stock)
                            StockMovement::create([
                                'item_id'     => $mi->item_id,
                                'location_id' => $kitchenLocation->id,
                                'direction'   => 'OUT',
                                'quantity'    => $requiredQty,
                                'reason'      => 'Order_Food',
                            ]);
                            // calculate per portion cost
                            if ($item['quantity'] > 0) {
                                $unitCost = $totalCost / $item['quantity'];
                            }
                        }
                        // after calculating totalCost from ingredients
                        $profit = ($item['price'] * $item['quantity']) - $totalCost;
                    }
                    // ============================
                    //  STOCK LOGIC (ONLY FOR ITEMS)
                    // ============================
                    if ($productType === 'item') {

                        $barLocation = Location::where('name', 'Bar')->first();

                        if (!$barLocation) {
                            throw new \Exception('Bar location not found!');
                        }

                        // Calculate current stock in BAR
                        $currentStock = StockMovement::where('item_id', $itemModel->id)
                            ->where('location_id', $barLocation->id)
                            ->sum(DB::raw("CASE WHEN direction = 'IN' THEN quantity ELSE -quantity END"));

                        if ($currentStock < $item['quantity']) {
                            throw new \Exception("Not enough stock for {$itemModel->name}");
                        }

                        // RECORD STOCK OUT
                        StockMovement::create([
                            'item_id' => $itemModel->id,
                            'location_id' => $barLocation->id,
                            'direction' => 'OUT',
                            'quantity' => $item['quantity'],
                            'reason' => 'Order_Drink',
                        ]);
                    }
                } else {
                    // Custom items
                    OrderItem::create([
                        'order_id'     => $order->id,
                        'product_id'   => null,
                        'product_type' => 'custom',
                        'name'         => $item['name'],
                        'unit_price'   => $item['price'],
                        'quantity'     => $item['quantity'],
                        'subtotal'     => $item['price'] * $item['quantity'],
                        'to_make'      => 1, // always 1 for custom items
                    ]);
                }
            }
            $totalAmount = OrderItem::where('order_id', $order->id)
                ->sum('subtotal');

            $order->total_amount = $totalAmount;
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function waiting()
    {
        // Fetch all active orders that are not flagged and not paid
        $orders = Order::with('items', 'waiter')
            ->where('is_flagged', '!=', '1')
            ->where('payment_status', '!=', '1')
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($orders as $order) {
            // Sum subtotal of unflagged items only
            $totalAmount = $order->items->where('is_flagged', 0)->sum('subtotal');
            $order->total_amount = $totalAmount;

            // If all items are flagged (totalAmount = 0), mark order as flagged
            if ($order->items->where('is_flagged', 0)->count() === 0) {
                $order->is_flagged = 1;
                $order->save();
            }
        }

        // Get all waiters
        $waiters = User::where('role', 'waiter')->get();

        // Return the view
        return view('admin.waiting', compact('orders', 'waiters'));
    }

    public function fetchOrder($orderNumber)
    {
        $order = Order::with('items', 'user', 'waiter')->where('order_number', $orderNumber)->firstOrFail();

        // Map order items to include a name
        $items = OrderItem::where('order_id', $order->id)
            ->where('is_flagged', '!=', 1)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name, // fallback name, or use $item->product_type if you want
                    'product_id' => $item->product_id,   // <-- add this if missing
                    'product_type' => $item->product_type, // <-- ADD THIS LINE
                    'quantity' => $item->quantity,
                    'unit_price' => number_format($item->unit_price, 2, '.', ''),
                    'subtotal' => number_format($item->subtotal, 2, '.', ''),
                ];
            });

        return response()->json([
            'order' => [
                'id' => $order->id, // <<< add this
                'order_number' => $order->order_number,
                'table_no' => $order->table_no,
                'waiter' => [
                    'name' => $order->waiter->name ?? '-'
                ],
                'casher' => [
                    'name' => $order->user->name ?? '-'
                ],
                'total_amount' => number_format($order->total_amount, 2, '.', ''),
                'is_billed' => $order->is_billed
            ],
            'items' => $items
        ]);
    }

    public function voidItem(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        $orderId = $request->order_id;
        $productId = $request->product_id;
        $qty = $request->quantity;

        DB::beginTransaction();

        try {

            $items = OrderItem::where('order_id', $orderId)
                ->where('product_id', $productId)
                ->where('is_flagged', 0)
                ->get();

            $totalAvailable = $items->sum('quantity');

            if ($qty > $totalAvailable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Void quantity exceeds available quantity'
                ]);
            }

            if ($items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found'
                ]);
            }

            $remainingQty = $qty;

            foreach ($items as $item) {
                if ($remainingQty <= 0) break;

                if ($item->quantity > $remainingQty) {
                    // Reduce this row by remaining void qty
                    $item->quantity -= $remainingQty;
                    $item->subtotal = $item->quantity * $item->unit_price;
                    $item->save();
                    $remainingQty = 0;
                } else {
                    // This row is fully voided
                    $remainingQty -= $item->quantity;
                    $item->quantity = 0;
                    $item->subtotal = 0;
                    $item->is_flagged = 1; // ✅ mark as flagged when qty is 0
                    $item->save();
                }
            }

            $sample = $items->first();

            //  CREATE VOID ROW
            OrderItem::create([
                'order_id'     => $orderId,
                'product_id'   => $productId,
                'product_type' => $sample->product_type,
                'name'         => $sample->name,
                'unit_price'   => $sample->unit_price,
                'quantity'     => $qty,
                'subtotal'     => $qty * $sample->unit_price,
                'to_make'      => 0,
                'prep_time'    => $sample->prep_time ?? null,
                'is_flagged'   => 1
            ]);

            //  STOCK RETURN (only item)
            if ($sample->product_type === 'item') {
                $bar = Location::where('name', 'Bar')->first();

                StockMovement::create([
                    'item_id' => $productId,
                    'location_id' => $bar->id,
                    'direction' => 'IN',
                    'quantity' => $qty,
                    'reason' => 'void',
                ]);
            }

            //  STOCK RETURN (FOR MENU → INGREDIENTS)
            if ($sample->product_type === 'menu') {

                $kitchen = Location::where('name', 'Kitchen')->first();

                // get menu ingredients
                $ingredients = MenuIngredient::where('menu_id', $productId)->get();

                foreach ($ingredients as $ing) {

                    $returnQty = $ing->quantity * $qty;

                    StockMovement::create([
                        'item_id' => $ing->item_id,
                        'location_id' => $kitchen->id,
                        'direction' => 'IN',
                        'quantity' => $returnQty,
                        'reason' => 'void_food',
                    ]);
                }
            }

            //  Check if order now has any un-flagged items with quantity > 0
            $hasRemaining = OrderItem::where('order_id', $orderId)
                ->where('is_flagged', 0)
                ->where('quantity', '>', 0)
                ->exists();

            if (!$hasRemaining) {
                // All items are voided/0 qty — mark order as fully flagged
                $order = Order::find($orderId);
                $order->is_flagged = 1; // or use a separate column like is_completed
                $order->save();
            }


            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    // public function flag_all($id)
    // {
    //     $order = Order::findOrFail($id);
    //     $order->is_flagged = 1;
    //     $order->save();

    //     OrderItem::where('order_id', $order->id)->update(['is_flagged' => 1]);

    //     return redirect()->route('admin.waiting');
    // }
    public function billOrder($id)
    {
        $order = Order::findOrFail($id);

        $order->is_billed = 1;
        $order->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function printReceipt($id)
    {
        $order = Order::with([
            'items' => function ($query) {
                $query->where('is_flagged', '!=', 1); // only non-flagged items
            },
            'waiter',
            'casher'
        ])->findOrFail($id);


        return view('admin.print_receipt', compact('order'));
    }

    public function updateTable(Request $request, $id)
    {
        $request->validate([
            'table_no' => 'required',
            'waiter_id' => 'required'
        ]);

        $order = Order::findOrFail($id);

        // check if table already used by another active order
        $tableExists = Order::where('table_no', $request->table_no)
            ->where('id', '!=', $id)
            ->where('is_billed', 0)
            ->exists();

        if ($tableExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'The Table Is Taken'
            ]);
        }

        $order->table_no = $request->table_no;
        $order->waiter_id = $request->waiter_id;
        $order->save();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'cash' => 'required|numeric|min:0',
            'bank' => 'required|numeric|min:0'
        ]);

        $order = Order::findOrFail($id);

        if ($order->payment_status == 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'This order is already paid.'
            ]);
        }
        $total = $order->items()->where('is_flagged', 0)->sum('subtotal');
        $cash = floatval($request->cash);
        $bank = floatval($request->bank);

        $paid = $cash + $bank;

        Log::info([
            'total' => $total,
            'cash' => $cash,
            'bank' => $bank,
            'paid' => $paid
        ]);

        if ($paid < $total) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment does not cover total amount.'
            ]);
        }

        $change = $paid - $total;

        // Save cash payment (incoming)
        if ($request->cash > 0) {
            Payment::create([
                'order_id' => $order->id,
                'method' => 'cash',
                'amount' => $request->cash,
                'type' => 'in'
            ]);
        }

        // Save bank payment (incoming)
        if ($request->bank > 0) {
            Payment::create([
                'order_id' => $order->id,
                'method' => 'bank',
                'amount' => $request->bank,
                'type' => 'in'
            ]);
        }

        // Save change returned to customer (outgoing)
        if ($change > 0) {
            Payment::create([
                'order_id' => $order->id,
                'method' => 'cash', // change is always cash returned
                'amount' => $change,
                'type' => 'out'
            ]);
        }

        // Mark order as fully paid
        $order->payment_status = 1;
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Payment completed successfully.',
            'change' => $change // optional, send back to modal
        ]);
    }

    public function flaggedOrders()
    {
        $orders = Order::with(['waiter', 'user', 'items'])
            ->whereDate('created_at', now())
            ->where(function ($query) {
                $query->where('is_flagged', 1)
                    ->orWhereHas('items', function ($q) {
                        $q->where('is_flagged', 1);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($orders as $order) {
            $order->total_amount = $order->items
                ->where('is_flagged', 1)
                ->sum('subtotal');
        }

        return view('admin.flag', compact('orders'));
    }

    public function flaggedOrderItems($id)
    {
        $order = Order::with('waiter', 'user')->findOrFail($id);

        $items = OrderItem::where('order_id', $order->id)->where('is_flagged', '1')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'unit_price' => number_format($item->unit_price, 2, '.', ''),
                    'subtotal' => number_format($item->subtotal, 2, '.', ''),
                ];
            });

        return response()->json([
            'order_number' => $order->order_number,
            'table_no' => $order->table_no,
            'waiter_name' => $order->waiter->name ?? '-',
            'cashier_name' => $order->user->name ?? '-',
            'items' => $items,
        ]);
    }

    public function showOrderfinish()
    {
        $today = now()->format('Y-m-d');

        $orders = Order::with(['waiter', 'user', 'items'])
            ->whereDate('created_at', $today)
            ->where('payment_status', 1) // ONLY paid orders
            ->whereHas('items', function ($q) {
                $q->where('is_flagged', 0); // at least one valid item
            })
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($orders as $order) {
            $order->total_amount = $order->items
                ->where('is_flagged', 0)
                ->sum('subtotal');
        }

        return view('admin.orderfinished', compact('orders'));
    }

    public function showOrderfinishes($id)
    {
        $find = Order::with('waiter', 'user')->findOrFail($id);
        $items = OrderItem::where('order_id', $find->id)->where('is_flagged', 0)->get()->map(function ($item) {
            return [
                'name' => $item->name,
                'quantity' => $item->quantity,
                'unit_price' => number_format($item->unit_price, 2, '.', ''),
                'subtotal' => number_format($item->subtotal, 2, '.', ''),
            ];
        });
        $payments = DB::table('payments')
            ->where('order_id', $find->id)
            ->get();

        // totals
        $bank = 0;
        $cash = 0;
        $return = 0;

        foreach ($payments as $p) {
            if ($p->type === 'in') {
                if ($p->method === 'bank') {
                    $bank += $p->amount;
                }
                if ($p->method === 'cash') {
                    $cash += $p->amount;
                }
            }

            if ($p->type === 'out') {
                $return += $p->amount;
            }
        }
        return response()->json([
            'order_number' => $find->order_number,
            'table_no' => $find->table_no,
            'waiter_name' => $find->waiter->name ?? '-',
            'cashier_name' => $find->user->name ?? '-',
            'items' => $items,

            'payment' => [
                'total' => $find->total_amount,
                'bank' => $bank,
                'cash' => $cash,
                'return' => $return
            ]
        ]);
    }

    public function Location(Request $request)
    {
        $loc = Location::all();
        $editloc = null;

        if ($request->has('edit')) {
            $editloc = Location::findOrFail($request->edit);
        }

        return view('admin.location', compact('loc', 'editloc'));
    }

    public function create_loc(Request $request)
    {
        $u = new Location;
        $u->name = $request->name;
        $u->type = $request->type;

        $u->save();

        return redirect()->back()->with('message', 'Done!!');
    }

    public function delete_loc($id)
    {
        $de = Location::find($id);
        $de->delete();
        return redirect()->back();
    }

    public function update_loc(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);

        $u = Location::findOrFail($id);
        $u->name = $request->name;
        $u->type = $request->type;
        $u->save();

        return redirect()->back()->with('message', 'Unit updated!');
    }

    public function transferStock(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'to_location_id' => 'required',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $item = Item::findOrFail($request->item_id);
        $quantity = $request->quantity;

        if ($item->qty < $quantity) {
            return redirect()->back()->with('error', 'Not enough stock.');
        }

        // Get Main Store
        $mainStore = Location::where('type', 'main')->first();

        if (!$mainStore) {
            return redirect()->back()->with('error', 'Main store not found.');
        }

        // Deduct from main store
        $item->qty -= $quantity;
        $item->save();

        // 🔴 ALWAYS create OUT record (source)
        StockMovement::create([
            'item_id' => $item->id,
            'location_id' => $mainStore->id,
            'direction' => 'OUT',
            'quantity' => $quantity,
            'reason' => $request->to_location_id == 'waste' ? 'waste' : 'transfer',
        ]);

        // 🟢 ONLY create IN if NOT waste
        if ($request->to_location_id != 'waste') {
            StockMovement::create([
                'item_id' => $item->id,
                'location_id' => $request->to_location_id,
                'direction' => 'IN',
                'quantity' => $quantity,
                'reason' => 'transfer',
            ]);
        }

        return redirect()->back()->with('message', 'Operation successful.');
    }

    public function addQty(Request $request)
    {

        $key = 'add_qty_' . auth()->id() . '_' . $request->item_id;

        if (Cache::has($key)) {
            return back()->with('error', 'Duplicate request detected.');
        }

        Cache::put($key, true, 5); // lock for 5 seconds

        // ✅ THEN validation starts
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|numeric|not_in:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|numeric|not_in:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $item = Item::findOrFail($request->item_id);
        $quantity = $request->quantity;

        //  Determine direction
        $direction = $quantity > 0 ? 'IN' : 'OUT';

        //  Reason
        $reason = $quantity > 0 ? 'Purchase' : 'Return';

        //  Always use positive number for movement record
        $movementQty = abs($quantity);

        //  Prevent negative stock (important bro)
        if ($direction === 'OUT' && $item->qty < $movementQty) {
            return redirect()->back()->with('error', 'Not enough stock to remove.');
        }

        //  Update stock
        $item->qty += $quantity; // works for + and -
        $item->save();

        // Get Main Store
        $mainStore = Location::where('type', 'main')->first();

        if (!$mainStore) {
            return redirect()->back()->with('error', 'Main store not found.');
        }

        //  Record stock movement
        StockMovement::create([
            'item_id' => $item->id,
            'location_id' => $mainStore->id,
            'direction' => $direction,
            'quantity' => $movementQty,
            'reason' => $reason,
            'supplier_id' => $request->supplier_id,
        ]);

        return redirect()->back()->with('message', 'Stock updated successfully.');
    }

    public function getItem($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        // fetch categories & units dynamically
        $cats = Cat::where('type', '!=', 'Food')->orderby('type', 'desc')->get();
        $units = Unit::all();

        return response()->json([
            'item' => $item,
            'categories' => $cats,
            'units' => $units
        ]);
    }
    public function updateItem(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'id'          => 'required|exists:items,id',
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:cats,id',
            'unit_id'     => 'required|exists:units,id',
            'price'       => 'required|numeric|min:0',
            'cost'        => 'required|numeric|min:0',
            'min_stock'   => 'required|numeric|min:0',
        ]);

        // Find the item
        $item = Item::find($request->id);

        // Update fields
        $item->name        = $request->name;
        $item->cat_id      = $request->category_id;
        $item->unit_id     = $request->unit_id;
        $item->price       = $request->price;
        $item->cost        = $request->cost;
        $item->min_stock   = $request->min_stock;

        // Save changes
        $item->save();

        // Redirect back with success message
        return redirect()->back()->with('message', 'Item updated successfully');
    }
}
