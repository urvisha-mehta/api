<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Requests\Login\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(AuthRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'user' => $user
        ], 200);
    }

    public function login(LoginRequest $request)
    {
        // attempt use for checking field inside the database
        // auth class method (attempt)
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authUser = Auth::user();
            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                // createToken is method of Sanctum inside this method any key passed
                'token' => $authUser->createToken('API Token')->plainTextToken,
                'token_type' => 'bearer'
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Email Or Password Dose Not Match',
        ], 401);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'user' => $user,
            'message' => 'User Logged Out Successfully',
        ], 200);
    }
}
