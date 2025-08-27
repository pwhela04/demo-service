<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Login user and return token
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Get users from file storage
        $storageFile = storage_path('app/users.json');
        $users = [];
        if (file_exists($storageFile)) {
            $data = file_get_contents($storageFile);
            $users = json_decode($data, true) ?: [];
        }
        
        $user = null;
        foreach ($users as $u) {
            if ($u['email'] === $request->email && Hash::check($request->password, $u['password'])) {
                $user = $u;
                break;
            }
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Create a simple token (in a real app, you'd use Sanctum)
        $token = 'token_' . $user['id'] . '_' . time();

        // Remove password from response
        unset($user['password']);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }

    /**
     * Logout user and revoke token
     */
    public function logout(Request $request)
    {
        // In a real app, you'd revoke the Sanctum token
        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        // For demo purposes, return a mock user
        // In a real app, you'd get the user from the Sanctum token
        return response()->json([
            'success' => true,
            'data' => [
                'id' => 1,
                'name' => 'Demo User',
                'email' => 'demo@example.com',
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString(),
            ]
        ]);
    }
} 