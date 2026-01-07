@extends('layouts.user')

@section('title', 'Lịch sử mua hàng')

@section('content')
<h2 class="mb-4">Lịch sử đơn hàng của bạn</h2>

@if(!$orders->isEmpty())
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="bg-light">
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td class="fw-bold">#{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="fw-bold text-primary">{{ number_format($order->total_amount) }}đ</td>
                        <td>
                            @php
                                $statusMap = [
                                    'pending' => ['class' => 'bg-warning text-dark', 'label' => 'Đang chờ'],
                                    'processing' => ['class' => 'bg-info', 'label' => 'Đang xử lý'],
                                    'shipped' => ['class' => 'bg-primary', 'label' => 'Đang giao'],
                                    'delivered' => ['class' => 'bg-success', 'label' => 'Đã giao'],
                                    'cancelled' => ['class' => 'bg-danger', 'label' => 'Đã hủy']
                                ];
                                $status = $statusMap[$order->status] ?? ['class' => 'bg-secondary', 'label' => $order->status];
                            @endphp
                            <span class="badge {{ $status['class'] }} px-3 py-2 rounded-pill">{{ $status['label'] }}</span>
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">Xem chi tiết</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $orders->links() }}
    </div>
</div>
@else
<div class="text-center py-5 shadow-sm bg-white rounded">
    <i class="fas fa-history fa-4x text-muted mb-3"></i>
    <h3>Bạn chưa có đơn hàng nào</h3>
    <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Mua sắm ngay</a>
</div>
@endif
@endsection
