#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

echo "=== Testing Management Role-Based Access Control ===\n\n";

echo "This script demonstrates the expected behavior of the management flag feature:\n\n";

echo "1. Regular User Accessing GET /users (should get 403)\n";
echo "Request:\n";
echo "GET /api/users\n";
echo "Authorization: Bearer {regular_user_token}\n";
echo "\nExpected Response:\n";
echo json_encode([
    'success' => false,
    'message' => 'Unauthorized to access user list'
], JSON_PRETTY_PRINT);

echo "\n\n" . str_repeat("=", 50) . "\n\n";

echo "2. Management User Accessing GET /users (should succeed)\n";
echo "Request:\n";
echo "GET /api/users\n";
echo "Authorization: Bearer {management_user_token}\n";
echo "\nExpected Response:\n";
echo json_encode([
    'success' => true,
    'data' => [
        [
            'id' => 1,
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'management' => false,
            'created_at' => '2024-01-01T00:00:00.000000Z',
            'updated_at' => '2024-01-01T00:00:00.000000Z'
        ],
        [
            'id' => 2,
            'name' => 'Management User',
            'email' => 'manager@example.com',
            'management' => true,
            'created_at' => '2024-01-01T00:00:00.000000Z',
            'updated_at' => '2024-01-01T00:00:00.000000Z'
        ]
    ]
], JSON_PRETTY_PRINT);

echo "\n\n" . str_repeat("=", 50) . "\n\n";

echo "3. Management User Accessing Other User's Data (should succeed)\n";
echo "Request:\n";
echo "GET /api/users/1\n";
echo "Authorization: Bearer {management_user_token}\n";
echo "\nExpected Response:\n";
echo json_encode([
    'success' => true,
    'data' => [
        'id' => 1,
        'name' => 'Regular User',
        'email' => 'user@example.com',
        'management' => false,
        'created_at' => '2024-01-01T00:00:00.000000Z',
        'updated_at' => '2024-01-01T00:00:00.000000Z'
    ]
], JSON_PRETTY_PRINT);

echo "\n\n" . str_repeat("=", 50) . "\n\n";

echo "4. Regular User Accessing Their Own Data (should still work)\n";
echo "Request:\n";
echo "GET /api/users/1\n";
echo "Authorization: Bearer {regular_user_token}\n";
echo "\nExpected Response:\n";
echo json_encode([
    'success' => true,
    'data' => [
        'id' => 1,
        'name' => 'Regular User',
        'email' => 'user@example.com',
        'management' => false,
        'created_at' => '2024-01-01T00:00:00.000000Z',
        'updated_at' => '2024-01-01T00:00:00.000000Z'
    ]
], JSON_PRETTY_PRINT);

echo "\n\n" . str_repeat("=", 50) . "\n\n";

echo "5. Regular User Accessing Other User's Data (should get 403)\n";
echo "Request:\n";
echo "GET /api/users/2\n";
echo "Authorization: Bearer {regular_user_token}\n";
echo "\nExpected Response:\n";
echo json_encode([
    'success' => false,
    'message' => 'Unauthorized to access this user data'
], JSON_PRETTY_PRINT);

echo "\n\n" . str_repeat("=", 50) . "\n\n";

echo "=== Management Role Authorization Summary ===\n\n";
echo "Management Users (management=1) can:\n";
echo "- ✅ GET /api/users (list all users)\n";
echo "- ✅ GET /api/users/{any_id} (view any user)\n";
echo "- ✅ PUT /api/users/{any_id} (update any user)\n";
echo "- ✅ DELETE /api/users/{any_id} (delete any user)\n\n";

echo "Regular Users (management=0) can:\n";
echo "- ❌ GET /api/users (403 Unauthorized)\n";
echo "- ✅ GET /api/users/{own_id} (view own data)\n";
echo "- ✅ PUT /api/users/{own_id} (update own data)\n";
echo "- ✅ DELETE /api/users/{own_id} (delete own data)\n";
echo "- ❌ GET/PUT/DELETE /api/users/{other_id} (403 Unauthorized)\n\n";

echo "To test this implementation:\n";
echo "1. Run: php artisan migrate\n";
echo "2. Create users with different management flags\n";
echo "3. Test API endpoints with both user types\n";
echo "4. Verify authorization works as expected\n";
