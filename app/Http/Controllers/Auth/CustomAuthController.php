<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomAuthController extends Controller
{
    public function login(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {

            $credentials = $request->only('email', 'password');
            $remember = $request->boolean('remember');

            // Attempt login
            if (!Auth::attempt($credentials, $remember)) {
                return response()->json(['message' => __('Invalid credentials')], 401);
            }

            // Update last_login timestamp
            $user = Auth::user();
            $user->update(['last_login' => now()]);
            
            $token = $user->createToken('API-Token')->plainTextToken;

            // Return response
            return response()->json([
                'message' => __('Login successful, Please wait redirect to dashboard.'),
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
            
        } catch (Exception $e) {
            $res['errors'] = ['login' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }
}

