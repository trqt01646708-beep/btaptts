@extends('frontend.layouts.app')

@section('title', 'Thanh toán')

@section('content')
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
                    <li class="breadcrumb-item active">Thanh toán</li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="container py-4">
        <h2 class="mb-4"><i class="fas fa-credit-card me-2"></i>Thanh toán</h2>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-7">
                    <!-- Customer Info -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Thông tin khách hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" 
                                           value="{{ old('customer_name', $user->name ?? '') }}" required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" 
                                           value="{{ old('customer_phone', $user->phone ?? '') }}" required>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror" 
                                       value="{{ old('customer_email', $user->email ?? '') }}" required>
                                @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                                <textarea name="shipping_address" rows="3" class="form-control @error('shipping_address') is-invalid @enderror" 
                                          required>{{ old('shipping_address', $user->address ?? '') }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="notes" rows="2" class="form-control" placeholder="Ghi chú về đơn hàng, ví dụ: thời gian hay chỉ dẫn địa điểm giao hàng chi tiết hơn.">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-wallet me-2"></i>Phương thức thanh toán</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                <label class="form-check-label" for="cod">
                                    <i class="fas fa-money-bill-wave me-2 text-success"></i>
                                    Thanh toán khi nhận hàng (COD)
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="vnpay" value="vnpay">
                                <label class="form-check-label" for="vnpay">
                                    <i class="fas fa-credit-card me-2 text-danger"></i>
                                    Thanh toán qua VNPay (ATM/VISA/MasterCard/QR)
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="bank" value="bank">
                                <label class="form-check-label" for="bank">
                                    <i class="fas fa-university me-2 text-primary"></i>
                                    Chuyển khoản ngân hàng
                                </label>
                            </div>
                            
                            <div id="vnpay-info" class="mt-3 p-3 rounded" style="display: none; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border: 1px solid #fca5a5;">
                                <p class="mb-2"><strong><i class="fas fa-info-circle me-2"></i>Thanh toán VNPay:</strong></p>
                                <p class="mb-1">✓ Hỗ trợ thẻ ATM nội địa</p>
                                <p class="mb-1">✓ Thẻ tín dụng Visa/MasterCard/JCB</p>
                                <p class="mb-1">✓ Quét mã QR thanh toán</p>
                                <p class="mb-0 text-danger"><small><i class="fas fa-shield-alt me-1"></i>Giao dịch an toàn, bảo mật cao</small></p>
                            </div>
                            
                            <div id="bank-info" class="mt-3 p-3 bg-light rounded" style="display: none;">
                                <p class="mb-2"><strong>Thông tin chuyển khoản:</strong></p>
                                <p class="mb-1">Ngân hàng: Vietcombank</p>
                                <p class="mb-1">Số tài khoản: 0123456789</p>
                                <p class="mb-1">Chủ tài khoản: CONG TY ABC</p>
                                <p class="mb-0 text-danger"><small>Nội dung CK: [Mã đơn hàng] + [SĐT]</small></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <!-- Order Summary -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>Đơn hàng của bạn</h5>
                        </div>
                        <div class="card-body">
                            <div class="order-items mb-3">
                                @foreach($cartItems as $item)
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->image && strpos($item->product->image, 'http') === 0)
                                            <img src="{{ $item->product->image }}" alt="" width="50" height="50" class="me-2 rounded" style="object-fit: cover;">
                                        @elseif($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="" width="50" height="50" class="me-2 rounded" style="object-fit: cover;">
                                        @else
                                            <div width="50" height="50" class="me-2 rounded" style="width: 50px; height: 50px; background: linear-gradient(135deg, #e0e7ff 0%, #dbeafe 100%); display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-image" style="color: #2563eb; font-size: 0.8rem;"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="d-block" style="font-size: 0.9rem;">{{ $item->product->name ?? 'Sản phẩm' }}</span>
                                            <small class="text-muted">x{{ $item->quantity }}</small>
                                        </div>
                                    </div>
                                    <span class="fw-bold">{{ number_format($item->total) }}đ</span>
                                </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($subtotal) }}đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span>
                                <span>{{ number_format($shippingFee) }}đ</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="h5">Tổng cộng:</span>
                                <span class="h5 text-danger fw-bold">{{ number_format($total) }}đ</span>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-check me-1"></i> Đặt hàng
                            </button>
                            
                            <p class="text-muted text-center mt-3 mb-0" style="font-size: 0.85rem;">
                                <i class="fas fa-lock me-1"></i> Thông tin của bạn được bảo mật
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[name="payment_method"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var bankInfo = document.getElementById('bank-info');
            var vnpayInfo = document.getElementById('vnpay-info');
            
            // Ẩn tất cả thông tin
            bankInfo.style.display = 'none';
            vnpayInfo.style.display = 'none';
            
            // Hiển thị thông tin tương ứng
            if (this.value === 'bank') {
                bankInfo.style.display = 'block';
            } else if (this.value === 'vnpay') {
                vnpayInfo.style.display = 'block';
            }
        });
    });
</script>
@endpush
