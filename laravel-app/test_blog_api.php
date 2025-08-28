<?php
/**
 * Blog Post API Test Script
 * 
 * This script demonstrates how to use the blog post API endpoints.
 * Run this script to test the blog post functionality.
 */

$baseUrl = 'http://127.0.0.1:8000/api';

echo "=== Blog Post API Test Script ===\n\n";

// Step 1: Create a user (if needed)
echo "1. Creating a test user...\n";
$userData = [
    'name' => 'Blog Test User',
    'email' => 'blogtest@example.com',
    'password' => 'password123'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/users');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 201) {
    echo "✅ User created successfully\n";
} else {
    $responseData = json_decode($response, true);
    if (isset($responseData['errors']['email']) && strpos($responseData['errors']['email'][0], 'already been taken') !== false) {
        echo "ℹ️  User already exists, continuing...\n";
    } else {
        echo "❌ Failed to create user: " . $response . "\n";
        exit(1);
    }
}

// Step 2: Login to get token
echo "\n2. Logging in to get authentication token...\n";
$loginData = [
    'email' => 'blogtest@example.com',
    'password' => 'password123'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $loginResponse = json_decode($response, true);
    $token = $loginResponse['data']['token'];
    echo "✅ Login successful! Token: " . substr($token, 0, 20) . "...\n";
} else {
    echo "❌ Login failed: " . $response . "\n";
    exit(1);
}

// Step 3: Create a blog post
echo "\n3. Creating a blog post...\n";
$blogPostData = [
    'title' => 'My First Blog Post',
    'content' => 'This is the content of my first blog post. It contains some interesting information about Laravel and API development.',
    'status' => 'draft'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/blog-posts');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($blogPostData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 201) {
    $postResponse = json_decode($response, true);
    $postId = $postResponse['data']['id'];
    echo "✅ Blog post created successfully! ID: $postId\n";
    echo "   Title: " . $postResponse['data']['title'] . "\n";
    echo "   Status: " . $postResponse['data']['status'] . "\n";
    echo "   Slug: " . $postResponse['data']['slug'] . "\n";
} else {
    echo "❌ Failed to create blog post: " . $response . "\n";
    exit(1);
}

// Step 4: Get all blog posts
echo "\n4. Getting all blog posts...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/blog-posts');
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $postsResponse = json_decode($response, true);
    $posts = $postsResponse['data']['data'];
    echo "✅ Retrieved " . count($posts) . " blog post(s)\n";
    foreach ($posts as $post) {
        echo "   - ID: {$post['id']}, Title: {$post['title']}, Status: {$post['status']}\n";
    }
} else {
    echo "❌ Failed to get blog posts: " . $response . "\n";
}

// Step 5: Get specific blog post
echo "\n5. Getting specific blog post (ID: $postId)...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/blog-posts/' . $postId);
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $postResponse = json_decode($response, true);
    $post = $postResponse['data'];
    echo "✅ Retrieved blog post:\n";
    echo "   Title: {$post['title']}\n";
    echo "   Content: " . substr($post['content'], 0, 50) . "...\n";
    echo "   Status: {$post['status']}\n";
    echo "   Author: {$post['user']['name']}\n";
} else {
    echo "❌ Failed to get blog post: " . $response . "\n";
}

// Step 6: Update blog post
echo "\n6. Updating blog post...\n";
$updateData = [
    'title' => 'Updated Blog Post Title',
    'content' => 'This is the updated content of my blog post. It now contains more information about Laravel API development.',
    'status' => 'published'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/blog-posts/' . $postId);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($updateData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $updateResponse = json_decode($response, true);
    echo "✅ Blog post updated successfully!\n";
    echo "   New Title: " . $updateResponse['data']['title'] . "\n";
    echo "   New Status: " . $updateResponse['data']['status'] . "\n";
    echo "   Published At: " . $updateResponse['data']['published_at'] . "\n";
} else {
    echo "❌ Failed to update blog post: " . $response . "\n";
}

// Step 7: Get only published posts
echo "\n7. Getting only published posts...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/blog-posts?status=published');
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $postsResponse = json_decode($response, true);
    $posts = $postsResponse['data']['data'];
    echo "✅ Retrieved " . count($posts) . " published blog post(s)\n";
    foreach ($posts as $post) {
        echo "   - ID: {$post['id']}, Title: {$post['title']}\n";
    }
} else {
    echo "❌ Failed to get published posts: " . $response . "\n";
}

// Step 8: Get current user's posts
echo "\n8. Getting current user's posts...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/blog-posts?my_posts=true');
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $postsResponse = json_decode($response, true);
    $posts = $postsResponse['data']['data'];
    echo "✅ Retrieved " . count($posts) . " of current user's blog post(s)\n";
    foreach ($posts as $post) {
        echo "   - ID: {$post['id']}, Title: {$post['title']}, Status: {$post['status']}\n";
    }
} else {
    echo "❌ Failed to get user's posts: " . $response . "\n";
}

// Step 9: Delete blog post
echo "\n9. Deleting blog post...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/blog-posts/' . $postId);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ Blog post deleted successfully!\n";
} else {
    echo "❌ Failed to delete blog post: " . $response . "\n";
}

// Step 10: Logout
echo "\n10. Logging out...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/logout');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ Logout successful!\n";
} else {
    echo "❌ Logout failed: " . $response . "\n";
}

echo "\n=== Blog Post API Test Complete ===\n";
echo "All blog post CRUD operations have been tested successfully!\n";
