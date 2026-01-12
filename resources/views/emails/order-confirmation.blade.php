<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng</title>
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
            background-color: #007bff;
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
        .order-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .order-info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f8f9fa;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #dc3545;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #ddd;
        }
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Xác nhận đơn hàng</h1>
        </div>
        
        <div class="content">
            <p>Xin chào <strong>{{ $order->customer_name }}</strong>,</p>
            
            <p>Cảm ơn bạn đã đặt hàng! Đơn hàng của bạn đã được tiếp nhận và đang chờ xử lý.</p>
            
            <div class="order-info">
                <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Trạng thái:</strong> 
                    @switch($order->status)
                        @case('pending') Chờ xử lý @break
                        @case('confirmed') Đã xác nhận @break
                        @case('processing') Đang xử lý @break
                        @case('shipped') Đang giao @break
                        @case('delivered') Đã giao @break
                        @case('cancelled') Đã hủy @break
                    @endswitch
                </p>
                <p><strong>Phương thức thanh toán:</strong> 
                    @switch($order->payment_method)
                        @case('cod') Thanh toán khi nhận hàng (COD) @break
                        @case('bank') Chuyển khoản ngân hàng @break
                    @endswitch
                </p>
            </div>

            <h3>Chi tiết đơn hàng:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th style="text-align: center;">SL</th>
                        <th style="text-align: right;">Đơn giá</th>
                        <th style="text-align: right;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">{{ number_format($item->price) }}đ</td>
                        <td style="text-align: right;">{{ number_format($item->total) }}đ</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;">Tạm tính:</td>
                        <td style="text-align: right;">{{ number_format($order->subtotal) }}đ</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right;">Phí vận chuyển:</td>
                        <td style="text-align: right;">{{ number_format($order->shipping_fee) }}đ</td>
                    </tr>
                    @if($order->discount > 0)
                    <tr>
                        <td colspan="3" style="text-align: right;">Giảm giá:</td>
                        <td style="text-align: right; color: #dc3545;">-{{ number_format($order->discount) }}đ</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Tổng cộng:</strong></td>
                        <td style="text-align: right;" class="total">{{ number_format($order->total) }}đ</td>
                    </tr>
                </tfoot>
            </table>

            <h3>Thông tin giao hàng:</h3>
            <div class="order-info">
                <p><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
                <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                <p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                @if($order->notes)
                <p><strong>Ghi chú:</strong> {{ $order->notes }}</p>
                @endif
            </div>

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
