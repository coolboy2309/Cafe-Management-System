<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DrinkController extends Controller
{
    public function drink_view(){
        return view('admin.drinkorder');
    }
}
