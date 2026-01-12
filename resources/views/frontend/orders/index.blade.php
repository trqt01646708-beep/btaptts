@extends('frontend.layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Đơn hàng của tôi</li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="container py-4">
        <h2 class="mb-4"><i class="fas fa-list me-2"></i>Đơn hàng của tôi</h2>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('orders.history') }}" method="GET" class="d-flex align-items-center">
                    <label class="me-2">Trạng thái:</label>
                    <select name="status" class="form-select" style="width: auto;" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </form>
            </div>
        </div>

        @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Đơn hàng #{{ $order->order_number }}</strong>
                            <span class="text-muted ms-3">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            @switch($order->status)
                                @case('pending')
                                    <span class="badge bg-warning">Chờ xử lý</span>
                                    @break
                                @case('confirmed')
                                    <span class="badge bg-info">Đã xác nhận</span>
                                    @break
                                @case('processing')
                                    <span class="badge bg-primary">Đang xử lý</span>
                                    @break
                                @case('shipped')
                                    <span class="badge bg-secondary">Đang giao</span>
                                    @break
                                @case('delivered')
                                    <span class="badge bg-success">Đã giao</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger">Đã hủy</span>
                                    @break
                            @endswitch
                            
                            @switch($order->payment_status)
                                @case('unpaid')
                                    <span class="badge bg-warning">Chưa thanh toán</span>
                                    @break
                                @case('paid')
                                    <span class="badge bg-success">Đã thanh toán</span>
                                    @break
                                @case('refunded')
                                    <span class="badge bg-info">Đã hoàn tiền</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                @foreach($order->items->take(3) as $item)
                                <div class="d-flex align-items-center mb-2">
                                    @if($item->product_image)
                                        <img src="{{ asset('storage/' . $item->product_image) }}" alt="" width="50" height="50" class="me-3 rounded" style="object-fit: cover;">
                                    @else
                                        <img src="https://via.placeholder.com/50" alt="" width="50" height="50" class="me-3 rounded">
                                    @endif
                                    <div>
                                        <span class="d-block">{{ $item->product_name }}</span>
                                        <small class="text-muted">{{ number_format($item->price) }}đ x {{ $item->quantity }}</small>
                                    </div>
                                </div>
                                @endforeach
                                @if($order->items->count() > 3)
                                <p class="text-muted mb-0">và {{ $order->items->count() - 3 }} sản phẩm khác...</p>
                                @endif
                            </div>
                            <div class="col-md-4 text-md-end">
                                <p class="mb-2">
                                    <strong>Tổng tiền:</strong>
                                    <span class="text-danger fw-bold h5">{{ number_format($order->total) }}đ</span>
                                </p>
                                <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i> Xem chi tiết
                                </a>
                                @if(in_array($order->status, ['pending', 'confirmed']))
                                <form action="{{ route('orders.cancel', $order->order_number) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-times me-1"></i> Hủy đơn
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-5x text-muted mb-4"></i>
            <h4>Bạn chưa có đơn hàng nào</h4>
            <p class="text-muted mb-4">Hãy đặt hàng ngay để nhận được những ưu đãi tuyệt vời</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag me-1"></i> Mua sắm ngay
            </a>
        </div>
        @endif
    </div>
@endsection
