<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class CustomAuthController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'message' => __('Invalid credentials.'),
                'errors' => ['error' => $validator->errors()],
            ], 422);
        }        

        try {

            $credentials = $request->only('email', 'password');

            $remember = $request->boolean('remember');

            // Attempt login
            if (!Auth::attempt($credentials, $remember)) {
                return response()->json([
                    'message' => __('Invalid credentials.'),
                    'errors' => ['error' => $validator->errors()],
                ], 401);
            }

            // Update last_login timestamp
            $user = Auth::user();

            $user->update(['last_login' => now()]);
            
            $token = $user->createToken('API-Token')->plainTextToken;

            // Return response
            return response()->json([
                'message' => __('Login successful. Redirecting to the dashboard.'),
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

