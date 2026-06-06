<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm(){
        return view('auth.login');
    }
    public function login(Request $request){
        $credentials = $request->validate([
            'name' => 'required|string',
            'password' => 'required',
        ]);
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $user = Auth::user();

            switch ($user->role){
                case 'admin';
                return redirect()->route('admin.home');
                case 'cashier';
                return redirect()->route('casher.home');
                case 'manager';
                return redirect()->route('manager.home');
                case 'Store';
                return redirect()->route('store.home');
                default:
                Auth::logout();
                return redirect()->route('login.form')->with(['role'=>'Unauthorized Role']);
            }
        }
        return back()->withErrors([
            'name' => 'Invalid Credentials! Try Again..',
        ]);
    }
    public function Logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form');
    }
}
