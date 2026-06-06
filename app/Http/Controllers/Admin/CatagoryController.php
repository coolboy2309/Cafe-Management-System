<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatagoryController extends Controller
{

     public function create_cat(Request $request)
    {
        $cat = new Cat;
        $cat->name = $request->cat_name;
        $cat->type = $request->cat_type;
        $cat->user_role = Auth::id();

        $cat->save();

        return redirect()->back()->with('message', 'Done!!');
    }

    public function category(Request $request)
    {
        $cat = Cat::all();
        $editCat = null;

        if ($request->has('edit')) {
            $editCat = Cat::findOrFail($request->edit);
        }

        return view('admin.cat', compact('cat', 'editCat'));
    }

   
    

    public function update_cat(Request $request, $id)
    {
        $request->validate([
            'cat_name' => 'required',
            'cat_type' => 'required',
        ]);

        $cat = Cat::findOrFail($id);
        $cat->name = $request->cat_name;
        $cat->type = $request->cat_type;
        $cat->user_role = Auth::id();
        $cat->save();

        return redirect()->back()->with('message', 'Category updated!');
    }
}
