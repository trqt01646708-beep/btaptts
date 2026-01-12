@extends('frontend.layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Lịch sử đơn hàng</h1>

            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <strong>{{ $order->order_number }}</strong>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ number_format($order->subtotal, 0, ',', '.') }}₫</td>
                                    <td>
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
                                        <span class="badge bg-{{ $statusClass }}">{{ $statusLabel }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <div class="d-flex justify-content-center">
                    {{ $orders->render() }}
                </div>
            @else
                <div class="alert alert-info text-center" role="alert">
                    <h5>Chưa có đơn hàng nào</h5>
                    <p>Bạn chưa đặt hàng. <a href="{{ route('products.index') }}">Tiếp tục mua sắm</a></p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
