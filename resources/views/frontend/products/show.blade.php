@extends('frontend.layouts.app')

@section('title', $product->name)

@section('content')
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="container py-4">
        <div class="row">
            <!-- Product Images -->
            <div class="col-md-5 mb-4">
                <div class="product-gallery">
                    <div class="main-image mb-3">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded">
                        @else
                            <img src="https://via.placeholder.com/500x500" alt="{{ $product->name }}" class="img-fluid rounded">
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-7">
                <h1 class="h2 mb-3">{{ $product->name }}</h1>
                
                <div class="d-flex align-items-center mb-3">
                    <span class="text-muted me-3"><i class="fas fa-eye me-1"></i> {{ $product->views ?? 0 }} lượt xem</span>
                    @if($product->category)
                        <span class="badge bg-secondary">{{ $product->category->name }}</span>
                    @endif
                </div>

                <div class="product-price mb-4">
                    @if($product->sale_price && $product->regular_price > $product->sale_price)
                        <span class="h3 text-danger fw-bold">{{ number_format($product->sale_price) }}đ</span>
                        <span class="h5 text-muted text-decoration-line-through ms-2">{{ number_format($product->regular_price) }}đ</span>
                        @php
                            $discount = round(($product->regular_price - $product->sale_price) / $product->regular_price * 100);
                        @endphp
                        <span class="badge bg-danger ms-2">-{{ $discount }}%</span>
                    @else
                        <span class="h3 text-danger fw-bold">{{ number_format($product->regular_price) }}đ</span>
                    @endif
                </div>

                @if($product->description)
                <div class="product-description mb-4">
                    <p>{{ $product->description }}</p>
                </div>
                @endif

                <div class="product-meta mb-4">
                    <p class="mb-2">
                        <strong>Tình trạng:</strong>
                        @if($product->stock_quantity === null || $product->stock_quantity > 0)
                            <span class="text-success"><i class="fas fa-check-circle me-1"></i> Còn hàng</span>
                        @else
                            <span class="text-danger"><i class="fas fa-times-circle me-1"></i> Hết hàng</span>
                        @endif
                    </p>
                    @if($product->sku)
                    <p class="mb-2"><strong>SKU:</strong> {{ $product->sku }}</p>
                    @endif
                </div>

                <!-- Add to Cart -->
                <form id="add-to-cart-form" class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <label class="me-3">Số lượng:</label>
                        <div class="input-group" style="width: 150px;">
                            <button type="button" class="btn btn-outline-secondary" onclick="decreaseQty()">-</button>
                            <input type="number" name="quantity" id="quantity" class="form-control text-center" value="1" min="1">
                            <button type="button" class="btn btn-outline-secondary" onclick="increaseQty()">+</button>
                        </div>
                    </div>
                    
                    @if($product->stock_quantity === null || $product->stock_quantity > 0)
                    <button type="button" onclick="addToCart({{ $product->id }}, document.getElementById('quantity').value)" class="btn btn-primary btn-lg">
                        <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ hàng
                    </button>
                    @else
                    <button type="button" class="btn btn-secondary btn-lg" disabled>
                        <i class="fas fa-times me-2"></i> Hết hàng
                    </button>
                    @endif
                </form>

                <!-- Share -->
                <div class="share-buttons">
                    <span class="me-2">Chia sẻ:</span>
                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-sm btn-outline-info"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-sm btn-outline-danger"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
        </div>

        <!-- Product Content -->
        @if($product->content)
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Mô tả chi tiết</h5>
                    </div>
                    <div class="card-body">
                        {!! $product->content !!}
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <section class="related-products mt-5">
            <h3 class="section-title">Sản phẩm liên quan</h3>
            <div class="row">
                @foreach($relatedProducts as $related)
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                    <div class="product-card">
                        <div class="product-image">
                            <a href="{{ route('products.show', $related->slug) }}">
                                @if($related->image)
                                    <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}">
                                @else
                                    <img src="https://via.placeholder.com/300x200" alt="{{ $related->name }}">
                                @endif
                            </a>
                            @if($related->sale_price && $related->regular_price > $related->sale_price)
                                @php
                                    $discount = round(($related->regular_price - $related->sale_price) / $related->regular_price * 100);
                                @endphp
                                <span class="sale-badge">-{{ $discount }}%</span>
                            @endif
                        </div>
                        <div class="product-info">
                            <a href="{{ route('products.show', $related->slug) }}" class="text-decoration-none">
                                <h5 class="product-name">{{ $related->name }}</h5>
                            </a>
                            <div class="product-price">
                                @if($related->sale_price)
                                    <span class="current-price">{{ number_format($related->sale_price) }}đ</span>
                                    <span class="original-price">{{ number_format($related->regular_price) }}đ</span>
                                @else
                                    <span class="current-price">{{ number_format($related->regular_price) }}đ</span>
                                @endif
                            </div>
                            <button onclick="addToCart({{ $related->id }})" class="btn btn-primary btn-sm w-100 mt-2">
                                <i class="fas fa-cart-plus me-1"></i> Thêm vào giỏ
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    function increaseQty() {
        var qty = document.getElementById('quantity');
        qty.value = parseInt(qty.value) + 1;
    }
    
    function decreaseQty() {
        var qty = document.getElementById('quantity');
        if (parseInt(qty.value) > 1) {
            qty.value = parseInt(qty.value) - 1;
        }
    }
</script>
@endpush
