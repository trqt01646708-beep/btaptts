<h1>Xác nhận đơn hàng</h1>
<p>Xin chào {{ $order->user->name }},</p>
<p>Cảm ơn bạn đã đặt hàng! Mã đơn hàng của bạn là #{{ $order->id }}.</p>
<p>Tổng tiền: {{ number_format($order->total_amount) }}đ</p>
<p>Chúng tôi đang xử lý đơn hàng của bạn và sẽ thông báo cho bạn khi hàng được giao.</p>
