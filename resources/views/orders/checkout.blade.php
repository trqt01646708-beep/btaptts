@extends('layouts.user')

@section('title', 'Thanh toán')

@section('content')
<h2 class="mb-4">Hoàn tất đơn hàng của bạn</h2>
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold py-3">Thông tin giao hàng</div>
            <div class="card-body">
                <form action="{{ route('order.place') }}" method="POST" id="checkout-form">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required placeholder="Nhập họ và tên của bạn">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Địa chỉ Email</label>
                            <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required placeholder="Nhập địa chỉ email của bạn">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" required placeholder="Nhập số điện thoại của bạn">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Địa chỉ giao hàng</label>
                            <textarea name="address" class="form-control" rows="3" required placeholder="Nhập địa chỉ giao hàng của bạn"></textarea>
                        </div>
                        <div class="col-md-12 mt-4 text-center">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">Đặt hàng ngay</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold py-3">Tóm tắt đơn hàng</div>
            <div class="card-body">
                <ul class="list-group list-group-flush mb-3">
                    @php $total = 0 @endphp
                    @foreach($cart as $id => $details)
                        @php $total += $details['price'] * $details['quantity'] @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <h6 class="my-0">{{ $details['name'] }}</h6>
                                <small class="text-muted">SL: {{ $details['quantity'] }}</small>
                            </div>
                            <span class="text-muted">{{ number_format($details['price'] * $details['quantity']) }}đ</span>
                        </li>
                    @endforeach
                    <li class="list-group-item d-flex justify-content-between px-0 fw-bold border-top mt-2">
                        <span>Tổng cộng (VND)</span>
                        <h4 class="text-primary mb-0">{{ number_format($total) }}đ</h4>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
