<?php
/**
 * Live API Test Script
 * 
 * This script tests the actual Laravel User Management API
 */

$baseUrl = 'http://127.0.0.1:8000/api';

echo "=== Testing Laravel User Management API ===\n\n";

// Test 1: Get all users (should be empty initially)
echo "1. Getting all users (should be empty initially):\n";
$response = file_get_contents($baseUrl . '/users');
echo "Response: " . $response . "\n\n";

// Test 2: Create a user
echo "2. Creating a new user:\n";
$userData = json_encode([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'password123'
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $userData
    ]
]);

$response = file_get_contents($baseUrl . '/users', false, $context);
echo "Response: " . $response . "\n\n";

// Test 3: Get all users again (should have one user)
echo "3. Getting all users (should have one user now):\n";
$response = file_get_contents($baseUrl . '/users');
echo "Response: " . $response . "\n\n";

// Test 4: Get specific user
echo "4. Getting user with ID 1:\n";
$response = file_get_contents($baseUrl . '/users/1');
echo "Response: " . $response . "\n\n";

// Test 5: Update user
echo "5. Updating user with ID 1:\n";
$updateData = json_encode([
    'name' => 'John Updated',
    'email' => 'john.updated@example.com'
]);

$context = stream_context_create([
    'http' => [
        'method' => 'PUT',
        'header' => 'Content-Type: application/json',
        'content' => $updateData
    ]
]);

$response = file_get_contents($baseUrl . '/users/1', false, $context);
echo "Response: " . $response . "\n\n";

// Test 6: Get all users again (should show updated user)
echo "6. Getting all users (should show updated user):\n";
$response = file_get_contents($baseUrl . '/users');
echo "Response: " . $response . "\n\n";

// Test 7: Login
echo "7. Testing login:\n";
$loginData = json_encode([
    'email' => 'john.updated@example.com',
    'password' => 'password123'
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $loginData
    ]
]);

$response = file_get_contents($baseUrl . '/login', false, $context);
echo "Response: " . $response . "\n\n";

echo "=== API Test Complete ===\n";
echo "The API is working! You can now test it with:\n";
echo "- curl\n";
echo "- Postman\n";
echo "- Any HTTP client\n\n";
echo "Base URL: http://127.0.0.1:8000/api\n"; 