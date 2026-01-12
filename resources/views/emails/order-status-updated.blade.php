<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật đơn hàng</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
        }
        .header {
            background-color: #17a2b8;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .status-update {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            margin: 5px;
        }
        .status-pending { background-color: #ffc107; color: #000; }
        .status-confirmed { background-color: #17a2b8; color: #fff; }
        .status-processing { background-color: #007bff; color: #fff; }
        .status-shipped { background-color: #6c757d; color: #fff; }
        .status-delivered { background-color: #28a745; color: #fff; }
        .status-cancelled { background-color: #dc3545; color: #fff; }
        .arrow {
            font-size: 24px;
            margin: 0 10px;
        }
        .order-info {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .order-info p {
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Cập nhật đơn hàng</h1>
        </div>
        
        <div class="content">
            <p>Xin chào <strong>{{ $order->customer_name }}</strong>,</p>
            
            <p>Đơn hàng của bạn đã được cập nhật trạng thái mới.</p>
            
            <div class="status-update">
                <p><strong>Trạng thái đơn hàng:</strong></p>
                @php
                    $statusLabels = [
                        'pending' => 'Chờ xử lý',
                        'confirmed' => 'Đã xác nhận',
                        'processing' => 'Đang xử lý',
                        'shipped' => 'Đang giao',
                        'delivered' => 'Đã giao',
                        'cancelled' => 'Đã hủy'
                    ];
                @endphp
                <span class="status-badge status-{{ $oldStatus }}">{{ $statusLabels[$oldStatus] ?? $oldStatus }}</span>
                <span class="arrow">→</span>
                <span class="status-badge status-{{ $newStatus }}">{{ $statusLabels[$newStatus] ?? $newStatus }}</span>
            </div>

            <div class="order-info">
                <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Tổng giá trị:</strong> <strong style="color: #dc3545;">{{ number_format($order->total) }}đ</strong></p>
            </div>

            @if($newStatus == 'shipped')
            <div style="background-color: #d4edda; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <strong>🚚 Đơn hàng đang được vận chuyển!</strong>
                <p>Vui lòng chuẩn bị nhận hàng. Nhân viên giao hàng sẽ liên hệ với bạn trước khi giao.</p>
            </div>
            @endif

            @if($newStatus == 'delivered')
            <div style="background-color: #d4edda; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <strong>✅ Đơn hàng đã được giao thành công!</strong>
                <p>Cảm ơn bạn đã mua hàng. Hy vọng bạn hài lòng với sản phẩm!</p>
            </div>
            @endif

            @if($newStatus == 'cancelled')
            <div style="background-color: #f8d7da; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <strong>❌ Đơn hàng đã bị hủy</strong>
                <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi.</p>
            </div>
            @endif

            <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi.</p>
            
            <p>Trân trọng,<br>{{ config('app.name') }}</p>
        </div>
        
        <div class="footer">
            <p>Email này được gửi tự động từ hệ thống. Vui lòng không trả lời email này.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
