<?php
/**
 * Simple API Test Script
 * 
 * This script demonstrates how to test the Laravel User Management API
 * without requiring a database setup. It shows the expected request/response formats.
 */

echo "=== Laravel User Management API Test Examples ===\n\n";

// Test 1: User Registration
echo "1. User Registration (POST /api/users)\n";
echo "Request:\n";
echo "POST /api/users\n";
echo "Content-Type: application/json\n";
echo "Body:\n";
echo json_encode([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'password123'
], JSON_PRETTY_PRINT);

echo "\n\nExpected Response:\n";
echo json_encode([
    'success' => true,
    'message' => 'User created successfully',
    'data' => [
        'id' => 1,
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'created_at' => '2024-01-01T00:00:00.000000Z',
        'updated_at' => '2024-01-01T00:00:00.000000Z'
    ]
], JSON_PRETTY_PRINT);

echo "\n\n" . str_repeat("=", 50) . "\n\n";

// Test 2: User Login
echo "2. User Login (POST /api/login)\n";
echo "Request:\n";
echo "POST /api/login\n";
echo "Content-Type: application/json\n";
echo "Body:\n";
echo json_encode([
    'email' => 'john@example.com',
    'password' => 'password123'
], JSON_PRETTY_PRINT);

echo "\n\nExpected Response:\n";
echo json_encode([
    'success' => true,
    'message' => 'Login successful',
    'data' => [
        'user' => [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'created_at' => '2024-01-01T00:00:00.000000Z',
            'updated_at' => '2024-01-01T00:00:00.000000Z'
        ],
        'token' => '1|abc123def456ghi789...'
    ]
], JSON_PRETTY_PRINT);

echo "\n\n" . str_repeat("=", 50) . "\n\n";

// Test 3: Get All Users (Protected)
echo "3. Get All Users (GET /api/users) - Protected Endpoint\n";
echo "Request:\n";
echo "GET /api/users\n";
echo "Authorization: Bearer {token}\n";

echo "\n\nExpected Response:\n";
echo json_encode([
    'success' => true,
    'data' => [
        [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'created_at' => '2024-01-01T00:00:00.000000Z',
            'updated_at' => '2024-01-01T00:00:00.000000Z'
        ],
        [
            'id' => 2,
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'created_at' => '2024-01-01T00:00:00.000000Z',
            'updated_at' => '2024-01-01T00:00:00.000000Z'
        ]
    ]
], JSON_PRETTY_PRINT);

echo "\n\n" . str_repeat("=", 50) . "\n\n";

// Test 4: Update User (Protected)
echo "4. Update User (PUT /api/users/{id}) - Protected Endpoint\n";
echo "Request:\n";
echo "PUT /api/users/1\n";
echo "Authorization: Bearer {token}\n";
echo "Content-Type: application/json\n";
echo "Body:\n";
echo json_encode([
    'name' => 'John Updated',
    'email' => 'john.updated@example.com'
], JSON_PRETTY_PRINT);

echo "\n\nExpected Response:\n";
echo json_encode([
    'success' => true,
    'message' => 'User updated successfully',
    'data' => [
        'id' => 1,
        'name' => 'John Updated',
        'email' => 'john.updated@example.com',
        'created_at' => '2024-01-01T00:00:00.000000Z',
        'updated_at' => '2024-01-01T00:00:00.000000Z'
    ]
], JSON_PRETTY_PRINT);

echo "\n\n" . str_repeat("=", 50) . "\n\n";

// Test 5: Error Response Example
echo "5. Error Response Example\n";
echo "Request:\n";
echo "POST /api/users\n";
echo "Content-Type: application/json\n";
echo "Body:\n";
echo json_encode([
    'name' => '',
    'email' => 'invalid-email',
    'password' => '123'
], JSON_PRETTY_PRINT);

echo "\n\nExpected Response:\n";
echo json_encode([
    'success' => false,
    'errors' => [
        'name' => ['The name field is required.'],
        'email' => ['The email must be a valid email address.'],
        'password' => ['The password must be at least 8 characters.']
    ]
], JSON_PRETTY_PRINT);

echo "\n\n" . str_repeat("=", 50) . "\n\n";

echo "=== API Endpoints Summary ===\n\n";
echo "Public Endpoints (No Authentication):\n";
echo "- POST /api/users (User Registration)\n";
echo "- POST /api/login (User Login)\n\n";

echo "Protected Endpoints (Require Authentication):\n";
echo "- GET /api/me (Get Current User)\n";
echo "- GET /api/users (Get All Users)\n";
echo "- GET /api/users/{id} (Get Specific User)\n";
echo "- PUT /api/users/{id} (Update User)\n";
echo "- PATCH /api/users/{id} (Update User)\n";
echo "- DELETE /api/users/{id} (Delete User)\n";
echo "- POST /api/logout (Logout)\n\n";

echo "Authentication:\n";
echo "Include the token in the Authorization header:\n";
echo "Authorization: Bearer {token}\n\n";

echo "To run the actual API:\n";
echo "1. Configure your .env file with SQLite database\n";
echo "2. Run: php artisan migrate\n";
echo "3. Run: php artisan serve\n";
echo "4. Test with curl, Postman, or any HTTP client\n"; 