<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validate input
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Find user
        $user = User::where('name', $request->name)->first();

        // 3. Check user + password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        // 4. Optional: block inactive users
        if ($user->active != 1) {
            return response()->json([
                'status' => false,
                'message' => 'Account is inactive',
            ], 403);
        }

        // 5. Create token (Sanctum)
        $token = $user->createToken('mobile_app_token')->plainTextToken;

        // 6. Response
        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                ],
                'token' => $token,
            ]
        ]);
    }
}
