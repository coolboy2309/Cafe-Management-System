<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class WaiterController extends Controller
{
    public function waiter()
    {
        $wai = User::all()->where('role', '=', 'waiter');
        return view('admin.waiter', compact('wai'));
    }
}
