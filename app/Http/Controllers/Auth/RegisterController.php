<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm(){
        return view('auth.register');
    }
   public function register(Request $request){
    $data = new User;

    $pass = Hash::make($request->psword);

    $data->name = $request->flname;
    $data->email = $request->email;
    $data->username = $request->urname;
    $data->phone = $request->phone;
    $data->role = $request->role;
    $data->salary = $request->salary;
    $data->active = 1;
    $data->password = $pass;

    $data->save();

    if($data){
        return redirect()->back()->with('message', 'You have done');
    }else{
        return redirect()->back()->with('message', 'There was error! Pls Try Again');
    }

   }
}
