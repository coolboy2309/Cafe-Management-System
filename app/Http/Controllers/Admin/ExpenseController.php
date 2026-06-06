<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function view_ex(){
        $data = Expense::all();
        return view('admin.expense',compact('data'));
    }
    public function data_expense(Request $request){
        $data = new Expense();

        $data->name = $request->name;
        $data->type = $request->type;
        $data->amt = $request->amount;

        $data->save();

        return redirect()->back()->with('message','Reported!!');
    }
}
