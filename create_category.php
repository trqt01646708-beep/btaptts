<?php
echo "Creating category...\n";
$ch = curl_init('http://127.0.0.1:8000/api/categories');

$data = json_encode([
    'name' => 'Test Category',
    'slug' => 'test-category',
    'description' => 'Test category description'
]);

curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json'
    ]
]);

$result = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Response: " . $result . "\n";
