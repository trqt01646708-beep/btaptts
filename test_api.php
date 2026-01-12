<?php
$ch = curl_init('http://127.0.0.1:8000/api/auth/register');

$time = time();
$data = json_encode([
    'name' => 'Test User ' . $time,
    'email' => 'test' . $time . '@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123'
]);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data)
));

$result = curl_exec($ch);
curl_close($ch);

echo "Response:\n";
echo $result . "\n";
