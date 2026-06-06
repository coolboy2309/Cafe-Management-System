<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
     public function profile()
    {
        $admin = User::all()->where('role', '=', 'admin');
        return view('admin.profile', compact('admin'));
    }
    public function profile_update(Request $request, $id)
    {
        $pro = User::find($id);
        $pro->name = $request->flname;
        $pro->email = $request->email;
        $pro->username = $request->urname;
        $pro->phone = $request->phone;

        $pro->save();

        if ($pro) {
            return redirect()->back()->with('message', 'You have succesfully updated Your Profile');
        } else {
            return redirect()->back()->with('message', 'Something Wrong! Try Again');
        }
    }
    public function admin_pass(Request $request, $id)
    {
        $admin = User::find($id);
        $adminn = Hash::make($request->password);
        $admin->password = $adminn;
        $admin->save();
        return redirect()->back()->with('message', 'Password has been change');
    }
}
