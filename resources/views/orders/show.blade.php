@extends('layouts.user')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Chi tiết đơn hàng #{{ $order->id }}</h2>
            <a href="{{ route('orders.history') }}" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-arrow-left me-1"></i> Quay lại lịch sử</a>
        </div>
        
        <div class="row g-4">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold py-3">Sản phẩm đã mua</div>
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th class="text-end pe-4">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset($item->product->image) }}" width="50" class="rounded me-3">
                                            @endif
                                            <span>{{ $item->product_name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->price) }}đ</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end pe-4 fw-bold">{{ number_format($item->price * $item->quantity) }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold py-3">Tổng giá trị đơn hàng:</td>
                                    <td class="text-end pe-4 fw-bold py-3 text-primary h4">{{ number_format($order->total_amount) }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold py-3">Trạng thái đơn hàng</div>
                    <div class="card-body text-center py-4">
                        @php
                            $statusMap = [
                                'pending' => ['class' => 'bg-warning text-dark', 'icon' => 'fa-clock', 'label' => 'Đang chờ'],
                                'processing' => ['class' => 'bg-info', 'icon' => 'fa-spinner fa-spin', 'label' => 'Đang xử lý'],
                                'shipped' => ['class' => 'bg-primary', 'icon' => 'fa-truck', 'label' => 'Đang giao'],
                                'delivered' => ['class' => 'bg-success', 'icon' => 'fa-check-circle', 'label' => 'Đã giao'],
                                'cancelled' => ['class' => 'bg-danger', 'icon' => 'fa-times-circle', 'label' => 'Đã hủy']
                            ];
                            $statusInfo = $statusMap[$order->status] ?? ['class' => 'bg-secondary', 'icon' => 'fa-question-circle', 'label' => $order->status];
                        @endphp
                        <div class="display-4 text-{{ str_replace('bg-', '', explode(' ', $statusInfo['class'])[0]) }} mb-2">
                            <i class="fas {{ $statusInfo['icon'] }}"></i>
                        </div>
                        <h4 class="fw-bold">{{ $statusInfo['label'] }}</h4>
                        <p class="text-muted small">Cập nhật lúc: {{ $order->updated_at->format('H:i, d/m/Y') }}</p>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">Thông tin khách hàng</div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Họ tên:</strong> {{ $order->user->name }}</p>
                        <p class="mb-0"><strong>Email:</strong> {{ $order->user->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
