<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private $storageFile;

    public function __construct()
    {
        $this->storageFile = storage_path('app/users.json');
    }

    private function getUsers()
    {
        if (!file_exists($this->storageFile)) {
            return [];
        }
        $data = file_get_contents($this->storageFile);
        return json_decode($data, true) ?: [];
    }

    private function saveUsers($users)
    {
        file_put_contents($this->storageFile, json_encode($users, JSON_PRETTY_PRINT));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->getUsers();
        return response()->json([
            'success' => true,
            'data' => array_values($users)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $users = $this->getUsers();
        $nextId = count($users) + 1;

        // Check if email already exists
        foreach ($users as $user) {
            if ($user['email'] === $request->email) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'email' => ['The email has already been taken.']
                    ]
                ], 422);
            }
        }

        $user = [
            'id' => $nextId,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at' => now()->toISOString(),
            'updated_at' => now()->toISOString(),
        ];

        $users[$nextId] = $user;
        $this->saveUsers($users);

        // Remove password from response
        $userResponse = $user;
        unset($userResponse['password']);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $userResponse
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $users = $this->getUsers();
        
        if (!isset($users[$id])) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user = $users[$id];
        unset($user['password']);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $users = $this->getUsers();
        
        if (!isset($users[$id])) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255',
            'password' => 'sometimes|required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $users[$id];

        // Check if email already exists (excluding current user)
        if ($request->has('email') && $request->email !== $user['email']) {
            foreach ($users as $existingUser) {
                if ($existingUser['email'] === $request->email) {
                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'email' => ['The email has already been taken.']
                        ]
                    ], 422);
                }
            }
        }

        if ($request->has('name')) {
            $user['name'] = $request->name;
        }
        
        if ($request->has('email')) {
            $user['email'] = $request->email;
        }
        
        if ($request->has('password')) {
            $user['password'] = Hash::make($request->password);
        }

        $user['updated_at'] = now()->toISOString();
        $users[$id] = $user;
        $this->saveUsers($users);

        // Remove password from response
        $userResponse = $user;
        unset($userResponse['password']);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $userResponse
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $users = $this->getUsers();
        
        if (!isset($users[$id])) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        unset($users[$id]);
        $this->saveUsers($users);

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }


} 