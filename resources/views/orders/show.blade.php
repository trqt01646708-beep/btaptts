@extends('frontend.layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Chi tiết đơn hàng</h1>
                <a href="{{ route('orders.history') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            <!-- Thông tin đơn hàng -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Tên khách hàng:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                            <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
                            @php
                                $statusClass = [
                                    'pending' => 'warning',
                                    'confirmed' => 'info',
                                    'shipping' => 'primary',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger'
                                ][$order->status] ?? 'secondary';
                                
                                $statusLabel = [
                                    'pending' => 'Chờ xác nhận',
                                    'confirmed' => 'Đã xác nhận',
                                    'shipping' => 'Đang giao',
                                    'delivered' => 'Đã giao',
                                    'cancelled' => 'Đã hủy'
                                ][$order->status] ?? $order->status;
                            @endphp
                            <p><strong>Trạng thái:</strong> <span class="badge bg-{{ $statusClass }}">{{ $statusLabel }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Danh sách sản phẩm</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product_image)
                                                    <img src="{{ asset('storage/' . $item->product_image) }}" alt="{{ $item->product_name }}" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                                @endif
                                                <div>
                                                    <p class="mb-0"><strong>{{ $item->product_name }}</strong></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->total, 0, ',', '.') }}₫</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tóm tắt thanh toán -->
            <div class="row">
                <div class="col-md-6 offset-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                            </div>
                            @if($order->shipping_fee > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Phí vận chuyển:</span>
                                    <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</span>
                                </div>
                            @endif
                            @if($order->discount > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Giảm giá:</span>
                                    <span>-{{ number_format($order->discount, 0, ',', '.') }}₫</span>
                                </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Tổng cộng:</strong>
                                <strong class="text-danger">{{ number_format($order->total, 0, ',', '.') }}₫</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
