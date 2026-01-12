<?php
echo "=== COMPREHENSIVE API TEST ===\n\n";

$baseUrl = 'http://127.0.0.1:8000/api';
$accessToken = null;
$userId = null;

// Helper function for HTTP requests
function makeRequest($method, $url, $data = null, $token = null) {
    $ch = curl_init($url);
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
    ]);
    
    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        $json = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'body' => json_decode($response, true)
    ];
}

// Test 1: Register
echo "1. Testing Register endpoint...\n";
$time = time();
$registerData = [
    'name' => 'Test User ' . $time,
    'email' => 'test' . $time . '@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123'
];
$result = makeRequest('POST', "$baseUrl/auth/register", $registerData);
echo "Response Code: {$result['code']}\n";
if ($result['code'] == 201 && $result['body']['status']) {
    echo "✅ Register Success\n";
    $accessToken = $result['body']['data']['access_token'];
    $userId = $result['body']['data']['user']['id'];
    echo "Token: " . substr($accessToken, 0, 50) . "...\n\n";
} else {
    echo "❌ Register Failed\n";
    print_r($result['body']);
    echo "\n\n";
}

// Test 2: Login
echo "2. Testing Login endpoint...\n";
$loginData = [
    'email' => $registerData['email'],
    'password' => $registerData['password']
];
$result = makeRequest('POST', "$baseUrl/auth/login", $loginData);
echo "Response Code: {$result['code']}\n";
if ($result['code'] == 200 && $result['body']['status']) {
    echo "✅ Login Success\n";
    echo "Token: " . substr($result['body']['data']['access_token'], 0, 50) . "...\n\n";
} else {
    echo "❌ Login Failed\n";
    print_r($result['body']);
    echo "\n\n";
}

// Test 3: Get Profile (requires token)
echo "3. Testing Get Profile endpoint (requires token)...\n";
$result = makeRequest('GET', "$baseUrl/auth/me", null, $accessToken);
echo "Response Code: {$result['code']}\n";
if ($result['code'] == 200 && $result['body']['status']) {
    echo "✅ Get Profile Success\n";
    echo "Profile: " . json_encode($result['body']['data']) . "\n\n";
} else {
    echo "❌ Get Profile Failed\n";
    print_r($result['body']);
    echo "\n\n";
}

// Test 4: Create Post (requires token)
echo "4. Testing Create Post endpoint (requires token)...\n";
$postTime = time();
$postData = [
    'title' => 'Test Post ' . $postTime,
    'slug' => 'test-post-' . $postTime,
    'excerpt' => 'Test excerpt for post',
    'content' => 'This is test content for the post',
    'featured_image' => 'https://via.placeholder.com/600x400'
];
$result = makeRequest('POST', "$baseUrl/posts", $postData, $accessToken);
echo "Response Code: {$result['code']}\n";
if ($result['code'] == 201 && $result['body']['status']) {
    echo "✅ Create Post Success\n";
    $postId = $result['body']['data']['id'];
    echo "Post ID: $postId\n\n";
} else {
    echo "❌ Create Post Failed\n";
    print_r($result['body']);
    echo "\n\n";
}

// Test 5: Get All Posts (no token needed)
echo "5. Testing Get All Posts endpoint (public)...\n";
$result = makeRequest('GET', "$baseUrl/posts");
echo "Response Code: {$result['code']}\n";
if ($result['code'] == 200 && $result['body']['status']) {
    echo "✅ Get Posts Success\n";
    echo "Post Count: " . count($result['body']['data']) . "\n\n";
} else {
    echo "❌ Get Posts Failed\n";
    print_r($result['body']);
    echo "\n\n";
}

echo "=== TEST COMPLETE ===\n";
