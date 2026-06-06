<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CatagoryController;
use App\Http\Controllers\Admin\DrinkController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\KitchenController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\WaiterController;
use App\Http\Controllers\Casher\CasherController;
use App\Http\Controllers\Admin\StoreController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
/// auth routes
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/user_register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
////admin routes
Route::middleware(['auth', 'no-cache', 'role:admin'])->group(function () {
    Route::get('/admin/home', [AdminController::class, 'index'])->name('admin.home');
    Route::get('/user', [AdminController::class, 'user_show'])->name('admin.user');
    Route::get('/home', [AdminController::class, 'index'])->name('admin.home');
    Route::get('/active/{id}', [AdminController::class, 'active'])->name('admin.active');
    Route::get('/deactive/{id}', [AdminController::class, 'deactive'])->name('admin.deactive');
    Route::get('/delete_user/{id}', [AdminController::class, 'delete'])->name('admin.delete');
    Route::get('/edit_user/{id}', [AdminController::class, 'edit'])->name('admin.edit');
    Route::post('/update_user/{id}', [AdminController::class, 'update'])->name('admin.userupdate');
    Route::get('/change_pass/{id}', [AdminController::class, 'change_pass'])->name('admin.change_pass');
    Route::post('/pass_change/{id}', [AdminController::class, 'pass'])->name('admin.pass');
    Route::get('/pro', [ProfileController::class, 'profile'])->name('admin.pro');
    Route::post('/profile_update/{id}', [ProfileController::class, 'profile_update'])->name('admin.pro');
    Route::post('/admin_pass/{id}', [ProfileController::class, 'admin_pass'])->name('admin.pro_pass');
    Route::get('/waiter', [WaiterController::class, 'waiter'])->name('admin.waiter');
    Route::get('/menu', [MenuController::class, 'menu'])->name('admin.menu');
    Route::post('/create_cat', [CatagoryController::class, 'create_cat'])->name('admin.create_cat');
    Route::get('/category', [CatagoryController::class, 'category'])->name('admin.cat');
    Route::post('/update_cat/{id}', [CatagoryController::class, 'update_cat']);
    Route::post('/create_menu', [MenuController::class, 'create_menu']);
    // Route::get('/menu_cat', [AdminController::class, 'menu_cat']);
    Route::get('/order', [StoreController::class, 'order'])->name('admin.order');
    Route::get('/waiting', [StoreController::class, 'waiting'])->name('admin.waiting');
    Route::get('/menus/category/{id}', [MenuController::class, 'menusByCategory']);
    Route::get('/menu/{id}', [MenuController::class, 'menuDetail']);
    Route::post('/menu/update/{id}', [MenuController::class, 'updateMenu']);
    Route::delete('menu/delete/{id}', [MenuController::class, 'delete_menu']);
    Route::get('store', [StoreController::class, 'store'])->name('admin.store');
    // Route::get('unit', [StoreController::class, 'unit'])->name('admin.unit');
    Route::post('/create_unit', [StoreController::class, 'create_unit']);
    Route::get('/unit', [StoreController::class, 'c_unit'])->name('admin.unit');
    Route::post('/update_unit/{id}', [StoreController::class, 'update_unit']);
    Route::get('/delete_unit/{id}', [StoreController::class, 'delete_u']);
    Route::post('/create_s', [StoreController::class, 'create_s']);
    Route::get('/supplier', [StoreController::class, 'supplier'])->name('admin.su');
    Route::post('/update_s/{id}', [StoreController::class, 'update_s']);
    Route::get('/delete_s/{id}', [StoreController::class, 'delete_s']);
    Route::get('/stock', [StoreController::class, 'stock'])->name('admin.stock');
    Route::post('/storeAdd', [StoreController::class, 'storeAdd']);
    Route::get('/active_i/{id}', [StoreController::class, 'active_i']);
    Route::get('/deactive_i/{id}', [StoreController::class, 'deactive_i']);
    Route::get('/deleitem/{id}', [StoreController::class, 'deleitem']);
    Route::get('/drinks/category/{cat_id}', [MenuController::class, 'drinksByCategory']);
    Route::post('/drink/update/{id}', [MenuController::class, 'updateDrink']);
    Route::get('/drink/{id}', [MenuController::class, 'getDrink']);
    Route::get('/products/category/{id}', [StoreController::class, 'productsByCategory']);
    Route::post('/orders/store', [StoreController::class, 'order_store'])->name('orders.store');
    Route::get('/orders/{orderNumber}', [StoreController::class, 'fetchOrder']);
    // Route::get('/order/{order}/departments', [StoreController::class, 'departments']); 
    // Route::get('/order/{order}/ticket/{department}', [StoreController::class, 'printTicketsByDepartment']); 
    // Route::get('/order/{order}/tickets-by-departments', [StoreController::class, 'ticketsByDepartments']); 
    /// flagged order
    // Route::get('/flag/{id}', [StoreController::class, 'flag_individual']);
    // Route::post('/orders/void-item/{id}', [StoreController::class, 'voidItem']);
    Route::post('/orders/void-item', [StoreController::class, 'voidItem']);
    // Route::get('/flag_all/{id}', [StoreController::class, 'flag_all']);
    //flaged page
    Route::get('/flagged', [StoreController::class, 'flaggedOrders'])->name('admin.flag');
    Route::get('/flagged_order_items/{id}', [StoreController::class, 'flaggedOrderItems'])->name('admin.flagged.items');

    //// add order in the exist order
    Route::get('/orders/add/{order}', [StoreController::class, 'addOrder'])->name('orders.add');
    Route::post('/orders/store', [StoreController::class, 'addstore'])->name('orders.store');

    ////kitchen
    Route::get('/kitchen', [KitchenController::class, 'index'])->name('admin.kitchen');
    Route::get('/kitchen/orders', [KitchenController::class, 'getOrders']);
    Route::post('/kitchen/orders/{order_number}/status', [KitchenController::class, 'updateStatus']);
    Route::get('/kitchen/late-orders', [KitchenController::class, 'lateOrders'])->name('admin.lateOrders');

    Route::post('/orders/bill/{id}', [StoreController::class, 'billOrder']);
    Route::get('/orders/print/{id}', [StoreController::class, 'printReceipt']);
    Route::post('/update-table/{id}', [StoreController::class, 'updateTable']);
    // Route::post('/process-payment/{id}', [StoreController::class, 'processPayment'])->name('process.payment');
    Route::post('/process-payment/{id}', [StoreController::class, 'processPayment'])->name('process.payment');
    Route::get('/order-finish', [StoreController::class, 'showOrderfinish'])->name('admin.orderfinished');
    Route::get('/finish_order_items/{id}', [StoreController::class, 'showOrderfinishes'])->name('admin.order.items');

    Route::get('/location', [StoreController::class, 'location'])->name('admin.location');
    Route::post('/create_loc', [StoreController::class, 'create_loc']);
    Route::post('/update_loc/{id}', [StoreController::class, 'update_loc']);
    Route::get('/delete_loc/{id}', [StoreController::class, 'delete_loc']);
    Route::get('/delete_loc/{id}', [StoreController::class, 'addStockMovement']);
    Route::post('/transferStock', [StoreController::class, 'transferStock']);
    Route::post('/addQty', [StoreController::class, 'addQty']);

    Route::get('/stock-food', [StoreController::class, 'stockfood'])->name('admin.stockfood');

    Route::get('menu_ingredients', [MenuController::class, 'index'])->name('admin.menu_ingredient');
    Route::post('menu_ingredient_store_all', [MenuController::class, 'storeAll']);
    Route::get('menu_ingredient_edit/{id}', [MenuController::class, 'edit']);
    Route::post('menu_ingredient_update/{id}', [MenuController::class, 'update']);
    Route::get('menu_ingredient_delete/{id}', [MenuController::class, 'destroy']);
    Route::get('/stock_food', [MenuController::class, 'stock_index'])->name('admin.stock_reporrt');

    Route::Post('/data_expense', [ExpenseController::class, 'data_expense']);
    Route::get('/expense', [ExpenseController::class, 'view_ex'])->name('admin.expense');
    Route::get('/Report', [ReportController::class, 'index'])->name('admin.report');
    Route::get('/get-item/{id}', [StoreController::class, 'getItem']);
    Route::post('/updateItem', [StoreController::class, 'updateItem']);
    Route::get('/Drink', [DrinkController::class, 'drink_view'])->name('admin.drink');  
});
////cashier routes
// Route::middleware(['auth', 'no-cache', 'role:cashier'])->group(function () {
//     Route::get('/cashier/home', [CasherController::class, 'index'])->name('casher.home');
// });

//// example of route with multiple middleware
// Route::middleware(['auth', 'no-cache', 'role:admin,manager'])->group(function () {
//     Route::get('/reports', [ReportController::class, 'index']);
// });
