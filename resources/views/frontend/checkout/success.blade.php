@extends('frontend.layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-center">
                    <div class="card-body py-5">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                        </div>
                        <h2 class="mb-3">Đặt hàng thành công!</h2>
                        <p class="lead mb-4">Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đang được xử lý.</p>
                        
                        <div class="bg-light p-4 rounded mb-4">
                            <h5 class="mb-3">Thông tin đơn hàng</h5>
                            <div class="row text-start">
                                <div class="col-md-6 mb-2">
                                    <strong>Mã đơn hàng:</strong> {{ $order->order_number }}
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Tổng tiền:</strong> <span class="text-danger fw-bold">{{ number_format($order->total) }}đ</span>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Phương thức TT:</strong>
                                    @if($order->payment_method == 'cod')
                                        Thanh toán khi nhận hàng
                                    @else
                                        Chuyển khoản ngân hàng
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($order->payment_method == 'bank')
                        <div class="alert alert-warning text-start mb-4">
                            <h6><i class="fas fa-info-circle me-1"></i> Thông tin chuyển khoản:</h6>
                            <p class="mb-1">Ngân hàng: Vietcombank</p>
                            <p class="mb-1">Số tài khoản: 0123456789</p>
                            <p class="mb-1">Chủ tài khoản: CONG TY ABC</p>
                            <p class="mb-0"><strong>Nội dung CK: {{ $order->order_number }} - {{ $order->customer_phone }}</strong></p>
                        </div>
                        @endif

                        <p class="text-muted mb-4">
                            Chúng tôi đã gửi email xác nhận đến <strong>{{ $order->customer_email }}</strong>.<br>
                            Vui lòng kiểm tra hộp thư để biết thêm chi tiết.
                        </p>

                        <div class="d-flex justify-content-center gap-3">
                            @auth
                            <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-primary">
                                <i class="fas fa-eye me-1"></i> Xem chi tiết đơn hàng
                            </a>
                            @endauth
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-home me-1"></i> Về trang chủ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
