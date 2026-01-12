@extends('frontend.layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('orders.history') }}">Đơn hàng của tôi</a></li>
                    <li class="breadcrumb-item active">{{ $order->order_number }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-receipt me-2"></i>Đơn hàng #{{ $order->order_number }}</h2>
            @if(in_array($order->status, ['pending', 'confirmed']))
            <form action="{{ route('orders.cancel', $order->order_number) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="fas fa-times me-1"></i> Hủy đơn hàng
                </button>
            </form>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Order Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Trạng thái đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            @php
                                $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                                $currentIndex = array_search($order->status, $statuses);
                                if ($order->status == 'cancelled') $currentIndex = -1;
                            @endphp
                            @foreach($statuses as $index => $status)
                            <div class="text-center flex-fill">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2 
                                    {{ $currentIndex >= $index ? 'bg-success text-white' : 'bg-light text-muted' }}" 
                                    style="width: 40px; height: 40px;">
                                    @if($currentIndex >= $index)
                                        <i class="fas fa-check"></i>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>
                                <p class="mb-0 small {{ $currentIndex >= $index ? 'fw-bold' : 'text-muted' }}">
                                    @switch($status)
                                        @case('pending') Chờ xử lý @break
                                        @case('confirmed') Đã xác nhận @break
                                        @case('processing') Đang xử lý @break
                                        @case('shipped') Đang giao @break
                                        @case('delivered') Đã giao @break
                                    @endswitch
                                </p>
                            </div>
                            @if($index < count($statuses) - 1)
                            <div class="flex-fill d-flex align-items-center">
                                <div class="w-100" style="height: 2px; background-color: {{ $currentIndex > $index ? '#28a745' : '#dee2e6' }};"></div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        
                        @if($order->status == 'cancelled')
                        <div class="alert alert-danger mt-3 mb-0">
                            <i class="fas fa-times-circle me-1"></i> Đơn hàng đã bị hủy
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Chi tiết sản phẩm</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="80">Ảnh</th>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Đơn giá</th>
                                    <th class="text-center">SL</th>
                                    <th class="text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        @if($item->product_image)
                                            <img src="{{ asset('storage/' . $item->product_image) }}" alt="" width="60" height="60" class="rounded" style="object-fit: cover;">
                                        @else
                                            <img src="https://via.placeholder.com/60" alt="" width="60" height="60" class="rounded">
                                        @endif
                                    </td>
                                    <td>{{ $item->product_name }}</td>
                                    <td class="text-center">{{ number_format($item->price) }}đ</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-right fw-bold">{{ number_format($item->total) }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end">Tạm tính:</td>
                                    <td class="text-right">{{ number_format($order->subtotal) }}đ</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end">Phí vận chuyển:</td>
                                    <td class="text-right">{{ number_format($order->shipping_fee) }}đ</td>
                                </tr>
                                @if($order->discount > 0)
                                <tr>
                                    <td colspan="4" class="text-end">Giảm giá:</td>
                                    <td class="text-right text-danger">-{{ number_format($order->discount) }}đ</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td class="text-right text-danger fw-bold h5">{{ number_format($order->total) }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Order Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Thông tin đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        <p>
                            <strong>Trạng thái:</strong>
                            @switch($order->status)
                                @case('pending') <span class="badge bg-warning">Chờ xử lý</span> @break
                                @case('confirmed') <span class="badge bg-info">Đã xác nhận</span> @break
                                @case('processing') <span class="badge bg-primary">Đang xử lý</span> @break
                                @case('shipped') <span class="badge bg-secondary">Đang giao</span> @break
                                @case('delivered') <span class="badge bg-success">Đã giao</span> @break
                                @case('cancelled') <span class="badge bg-danger">Đã hủy</span> @break
                            @endswitch
                        </p>
                        <p>
                            <strong>Thanh toán:</strong>
                            @switch($order->payment_status)
                                @case('unpaid') <span class="badge bg-warning">Chưa thanh toán</span> @break
                                @case('paid') <span class="badge bg-success">Đã thanh toán</span> @break
                                @case('refunded') <span class="badge bg-info">Đã hoàn tiền</span> @break
                            @endswitch
                        </p>
                        <p class="mb-0">
                            <strong>Phương thức TT:</strong>
                            @if($order->payment_method == 'cod')
                                Thanh toán khi nhận hàng
                            @else
                                Chuyển khoản ngân hàng
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Thông tin nhận hàng</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
                        <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                        <p><strong>SĐT:</strong> {{ $order->customer_phone }}</p>
                        <p class="mb-0"><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                        @if($order->notes)
                        <p class="mt-2 mb-0"><strong>Ghi chú:</strong> {{ $order->notes }}</p>
                        @endif
                    </div>
                </div>

                <a href="{{ route('orders.history') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
@endsection
