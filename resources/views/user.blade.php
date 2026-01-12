<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin Người Dùng</title>
  
</head>
<body>
    <div class="container">
        <h1>Thông tin Người Dùng</h1>

        @if ($user)
            <div class="success-message">
                ✓ Tìm thấy thông tin người dùng
            </div>
            <div class="user-info">
                <div class="info-item">
                    <span class="info-label">ID:</span> {{ $user['id'] }}
                </div>
                <div class="info-item">
                    <span class="info-label">Tên:</span> {{ $user['name'] }}
                </div>
                <div class="info-item">
                    <span class="info-label">Giới tính:</span> {{ $user['gender'] }}
                </div>
            </div>
        @else
            <div class="error-message">
                ✗ Không tìm thấy người dùng có ID: {{ request()->route('id') }}
            </div>
        @endif

    </div>
</body>
</html>
