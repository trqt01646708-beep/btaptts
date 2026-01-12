<?php
echo "=== API Test ===\n";
echo "Testing register endpoint...\n\n";

$ch = curl_init('http://127.0.0.1:8000/api/auth/register');

$time = time();
$data = json_encode([
    'name' => 'Test User ' . $time,
    'email' => 'test' . $time . '@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123'
]);

curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data),
        'Accept: application/json'
    ]
]);

$result = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpcode\n";
echo "Response:\n";
echo json_encode(json_decode($result), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
