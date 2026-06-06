<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cat;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\MenuIngredient;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use App\Models\Location;

class MenuController extends Controller
{    // Main page
    public function menu()
    {
        $categories = Cat::whereNotIn('type', ['Drink','Ingredient'])->get();
        $cattt = Cat::whereNotIn('type', ['Ingredient'])->get();
        return view('admin.menu', compact('categories', 'cattt'));
    }
    // Fetch menus by category
    public function menusByCategory($cat_id)
    {
        $menus = Menu::where('cat_id', $cat_id)->orderBy('index_no')->get();
        return response()->json($menus);
    }
    public function drinksByCategory($cat_id)
    {
        $drinks = Item::where('cat_id', $cat_id)->orderBy('index_no')->get();
        return response()->json($drinks);
    }
    public function getDrink($id)
    {
        $drink = Item::findOrFail($id);
        return response()->json($drink);
    }
    // Fetch single menu for edit
    public function menuDetail($id)
    {
        $menu = Menu::findOrFail($id);
        return response()->json($menu);
    }
    // Update menu
    public function updateMenu(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $menu->update([
            'name' => $request->name,
            'price' => $request->price,
            'index_no' => $request->index_no,
            'to_make' => $request->to_make,
            'prep_time' => $request->prep_time,
        ]);

        return response()->json([
            'message' => 'Menu updated successfully'
        ]);
    }

    public function updateDrink(Request $request, $id)
    {
        $drink = Item::findOrFail($id);

        $drink->update([
            'name'  => $request->name,
            'price' => $request->price,
            'index_no' => $request->index_no,
            'to_make'  => $request->to_make ?? 2,
        ]);

        return response()->json(['success' => true]);
    }

    public function create_menu(Request $request)
    {
        $menu = new Menu;
        $menu->name = $request->menu_name;
        $menu->cat_id = $request->role;
        $menu->price = $request->menu_price;
        $menu->index_no = $request->menu_no;
        $menu->to_make = $request->to_make;
        $menu->prep_time = $request->prep_time;
        $menu->user_type = Auth::id();

        $menu->save();
        return redirect()->back()->with('message', 'Menu Added!!!');
    }

    public function delete_menu($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu deleted successfully'
        ]);
    }

    public function index()
    {
        $menus = Menu::all();
        $cat = Cat::where('type', 'Ingredient')->pluck('id');
        $ingredients = Item::whereIn('cat_id', $cat)->get();
        $menu_ingredients = MenuIngredient::with('menu', 'item')->get();

        return view('admin.menu_ingredient', compact('menus', 'ingredients', 'menu_ingredients'));
    }

    // Store new ingredient for menu
    public function storeAll(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.item_id' => 'required|exists:items,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $menuId = $request->menu_id;
        $ingredients = $request->ingredients;

        foreach ($ingredients as $ingredient) {
            MenuIngredient::create([
                'menu_id' => $menuId,
                'item_id' => $ingredient['item_id'],
                'quantity' => $ingredient['quantity'],
            ]);
        }

        return redirect()->back()->with('message', 'All ingredients added to menu successfully!');
    }

    // Edit form
    public function edit($id)
    {
        $menus = Menu::all();
        $ingredients = Item::where('type', 'raw_material')->get();
        $edit = MenuIngredient::findOrFail($id);
        $menu_ingredients = MenuIngredient::with('menu', 'item')->get();

        return view('admin.menu_ingredient_edit', compact('menus', 'ingredients', 'menu_ingredients', 'edit'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $mi = MenuIngredient::findOrFail($id);
        $mi->menu_id = $request->menu_id;
        $mi->item_id = $request->item_id;
        $mi->quantity = $request->quantity;
        $mi->save();

        return redirect()->back()->with('message', 'Menu ingredient updated!');
    }

    // Delete
    public function destroy($id)
    {
        $mi = MenuIngredient::findOrFail($id);
        $mi->delete();

        return redirect()->back()->with('message', 'Menu ingredient deleted!');
    }

    public function stock_index(Request $request)
    {
        $locationName = $request->get('location', 'Kitchen');
        $location = Location::where('name', $locationName)->first();

        if (!$location) {
            return back()->with('error', 'Location not found!');
        }

        // Only get items that have stock movements at this location
        $itemIds = StockMovement::where('location_id', $location->id)
            ->pluck('item_id')
            ->unique();

        $items = Item::whereIn('id', $itemIds)->get()->map(function ($item) use ($location) {
            $currentStock = StockMovement::where('item_id', $item->id)
                ->where('location_id', $location->id)
                ->sum(DB::raw("CASE WHEN direction = 'IN' THEN quantity ELSE -quantity END"));

            return [
                'id' => $item->id,
                'name' => $item->name,
                'qty' => $currentStock,
            ];
        });

        return view('admin.stock_reporrt', compact('items', 'locationName'));
    }
}
