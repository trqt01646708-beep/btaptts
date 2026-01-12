@extends('frontend.layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Giỏ hàng</li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="container py-4">
        <h2 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Giỏ hàng của bạn</h2>

        @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="100">Ảnh</th>
                                    <th>Sản phẩm</th>
                                    <th class="text-center" width="120">Đơn giá</th>
                                    <th class="text-center" width="150">Số lượng</th>
                                    <th class="text-right" width="120">Thành tiền</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr data-item-id="{{ $item->id }}">
                                    <td>
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-thumbnail" width="80">
                                        @else
                                            <img src="https://via.placeholder.com/80" alt="Product" class="img-thumbnail" width="80">
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->product)
                                            <a href="{{ route('products.show', $item->product->slug) }}" class="text-decoration-none fw-bold">
                                                {{ $item->product->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">Sản phẩm không còn tồn tại</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ number_format($item->price) }}đ</td>
                                    <td class="text-center">
                                        <div class="input-group input-group-sm" style="width: 120px; margin: 0 auto;">
                                            <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity({{ $item->id }}, -1)">-</button>
                                            <input type="number" class="form-control text-center item-quantity" value="{{ $item->quantity }}" min="1" onchange="updateQuantityDirect({{ $item->id }}, this.value)">
                                            <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity({{ $item->id }}, 1)">+</button>
                                        </div>
                                    </td>
                                    <td class="text-right fw-bold item-total">{{ number_format($item->total) }}đ</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem({{ $item->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Tiếp tục mua hàng
                    </a>
                    <button type="button" class="btn btn-outline-danger" onclick="clearCart()">
                        <i class="fas fa-trash me-1"></i> Xóa giỏ hàng
                    </button>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Tổng đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Tạm tính:</span>
                            <span class="fw-bold" id="cart-total">{{ number_format($total) }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Phí vận chuyển:</span>
                            <span class="text-muted">Tính ở bước sau</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="h5">Tổng cộng:</span>
                            <span class="h5 text-danger fw-bold" id="cart-total-final">{{ number_format($total) }}đ</span>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-credit-card me-1"></i> Tiến hành đặt hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
            <h4>Giỏ hàng của bạn đang trống</h4>
            <p class="text-muted mb-4">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag me-1"></i> Tiếp tục mua hàng
            </a>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    function updateQuantity(itemId, change) {
        var row = document.querySelector('tr[data-item-id="' + itemId + '"]');
        var quantityInput = row.querySelector('.item-quantity');
        var newQuantity = parseInt(quantityInput.value) + change;
        
        if (newQuantity < 1) return;
        
        updateQuantityDirect(itemId, newQuantity);
    }
    
    function updateQuantityDirect(itemId, quantity) {
        if (quantity < 1) return;
        
        fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                item_id: itemId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                var row = document.querySelector('tr[data-item-id="' + itemId + '"]');
                row.querySelector('.item-quantity').value = quantity;
                row.querySelector('.item-total').textContent = data.item_total;
                document.getElementById('cart-total').textContent = data.cart_total;
                document.getElementById('cart-total-final').textContent = data.cart_total;
                document.getElementById('cart-count').textContent = data.cart_count;
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra, vui lòng thử lại');
        });
    }
    
    function removeItem(itemId) {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
        
        fetch('{{ route("cart.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                item_id: itemId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra, vui lòng thử lại');
        });
    }
    
    function clearCart() {
        if (!confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) return;
        
        fetch('{{ route("cart.clear") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra, vui lòng thử lại');
        });
    }
</script>
@endpush
