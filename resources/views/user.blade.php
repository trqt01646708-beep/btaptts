<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin người dùng</title>
</head>
<body>
    <h1>Thông tin người dùng</h1>
    <p><strong>ID: </strong>{{ $user['id'] }}</p>
    <p><strong>Tên: </strong>{{ $user['name'] }}</p>
    <p><strong>Giới tính: </strong>{{ $user['gender'] }}</p>
</body>
</html>