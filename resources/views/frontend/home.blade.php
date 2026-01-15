@extends('frontend.layouts.app')

@section('title', 'Trang chủ')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section py-5" style="background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-white">
                    <h1 class="display-4 fw-bold mb-4">Mua sắm thông minh</h1>
                    <p class="lead mb-4">Khám phá hàng nghìn sản phẩm chất lượng với giá cả hợp lý. Miễn phí vận chuyển cho đơn hàng trên 500.000đ.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i> Mua ngay
                    </a>
                </div>
                <div class="col-md-6 text-center">
                    <img src="https://via.placeholder.com/500x400" alt="Hero Image" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Categories -->
    @if($categories->count() > 0)
    <section class="categories-section py-5">
        <div class="container">
            <h2 class="section-title">Danh mục sản phẩm</h2>
            <div class="row">
                @foreach($categories as $category)
                <div class="col-6 col-md-3 col-lg-2 mb-4">
                    <a href="{{ route('products.category', $category->slug) }}" class="text-decoration-none">
                        <div class="category-card text-center p-3 bg-white rounded shadow-sm">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid rounded mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <i class="fas fa-folder fa-3x text-primary mb-2"></i>
                            @endif
                            <h6 class="mb-1">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->products_count }} sản phẩm</small>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Products -->
    @if($featuredProducts->count() > 0)
    <section class="featured-products-section py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title mb-0">Sản phẩm giảm giá</h2>
                <a href="{{ route('products.sale') }}" class="btn btn-outline-primary">Xem tất cả</a>
            </div>
            <div class="row">
                @foreach($featuredProducts as $product)
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                    <div class="product-card">
                        <div class="product-image">
                            <a href="{{ route('products.show', $product->slug) }}">
                                @if($product->image && strpos($product->image, 'http') === 0)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" style="width: 100%; height: 250px; object-fit: cover;">
                                @elseif($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 250px; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 250px; background: linear-gradient(135deg, #e0e7ff 0%, #dbeafe 100%); display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <div style="text-align: center; color: #2563eb;">
                                            <i class="fas fa-image" style="font-size: 3rem; margin-bottom: 10px;"></i>
                                            <p style="margin: 0; font-weight: 600;">Không có ảnh</p>
                                        </div>
                                    </div>
                                @endif
                            </a>
                            @if($product->sale_price && $product->regular_price > $product->sale_price)
                                @php
                                    $discount = round(($product->regular_price - $product->sale_price) / $product->regular_price * 100);
                                @endphp
                                <span class="sale-badge">-{{ $discount }}%</span>
                            @endif
                        </div>
                        <div class="product-info">
                            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                                <h5 class="product-name">{{ $product->name }}</h5>
                            </a>
                            <div class="product-price">
                                @if($product->sale_price)
                                    <span class="current-price">{{ number_format($product->sale_price) }}đ</span>
                                    <span class="original-price">{{ number_format($product->regular_price) }}đ</span>
                                @else
                                    <span class="current-price">{{ number_format($product->regular_price) }}đ</span>
                                @endif
                            </div>
                            <button onclick="addToCart({{ $product->id }})" class="btn btn-primary btn-sm w-100 mt-2">
                                <i class="fas fa-cart-plus me-1"></i> Thêm vào giỏ
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Latest Products -->
    @if($latestProducts->count() > 0)
    <section class="latest-products-section py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title mb-0">Sản phẩm mới nhất</h2>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Xem tất cả</a>
            </div>
            <div class="row">
                @foreach($latestProducts as $product)
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                    <div class="product-card">
                        <div class="product-image">
                            <a href="{{ route('products.show', $product->slug) }}">
                                @if($product->image && strpos($product->image, 'http') === 0)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" style="width: 100%; height: 250px; object-fit: cover;">
                                @elseif($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 250px; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 250px; background: linear-gradient(135deg, #e0e7ff 0%, #dbeafe 100%); display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <div style="text-align: center; color: #2563eb;">
                                            <i class="fas fa-image" style="font-size: 3rem; margin-bottom: 10px;"></i>
                                            <p style="margin: 0; font-weight: 600;">Không có ảnh</p>
                                        </div>
                                    </div>
                                @endif
                            </a>
                            @if($product->sale_price && $product->regular_price > $product->sale_price)
                                @php
                                    $discount = round(($product->regular_price - $product->sale_price) / $product->regular_price * 100);
                                @endphp
                                <span class="sale-badge">-{{ $discount }}%</span>
                            @endif
                        </div>
                        <div class="product-info">
                            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                                <h5 class="product-name">{{ $product->name }}</h5>
                            </a>
                            <div class="product-price">
                                @if($product->sale_price)
                                    <span class="current-price">{{ number_format($product->sale_price) }}đ</span>
                                    <span class="original-price">{{ number_format($product->regular_price) }}đ</span>
                                @else
                                    <span class="current-price">{{ number_format($product->regular_price) }}đ</span>
                                @endif
                            </div>
                            <button onclick="addToCart({{ $product->id }})" class="btn btn-primary btn-sm w-100 mt-2">
                                <i class="fas fa-cart-plus me-1"></i> Thêm vào giỏ
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Latest Posts -->
    @if($latestPosts->count() > 0)
    <section class="latest-posts-section py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title mb-0">Bài viết mới nhất</h2>
                <a href="{{ route('posts.index') }}" class="btn btn-outline-primary">Xem tất cả</a>
            </div>
            <div class="row">
                @foreach($latestPosts as $post)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="post-card">
                        <div class="post-image">
                            <a href="{{ route('posts.show', $post->slug) }}">
                                @if($post->thumbnail)
                                    <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->name }}">
                                @else
                                    <img src="https://via.placeholder.com/400x200" alt="{{ $post->name }}">
                                @endif
                            </a>
                        </div>
                        <div class="post-info">
                            <div class="post-meta">
                                <i class="fas fa-calendar me-1"></i> {{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}
                                <span class="ms-3"><i class="fas fa-eye me-1"></i> {{ $post->views ?? 0 }}</span>
                            </div>
                            <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none">
                                <h5 class="post-title">{{ $post->name }}</h5>
                            </a>
                            <p class="post-excerpt">{{ $post->description }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Features -->
    <section class="features-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="feature-item text-center">
                        <i class="fas fa-truck fa-3x text-primary mb-3"></i>
                        <h5>Miễn phí vận chuyển</h5>
                        <p class="text-muted mb-0">Đơn hàng từ 500.000đ</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="feature-item text-center">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h5>Đảm bảo chất lượng</h5>
                        <p class="text-muted mb-0">Sản phẩm chính hãng 100%</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="feature-item text-center">
                        <i class="fas fa-undo fa-3x text-primary mb-3"></i>
                        <h5>Đổi trả dễ dàng</h5>
                        <p class="text-muted mb-0">Trong vòng 7 ngày</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="feature-item text-center">
                        <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                        <h5>Hỗ trợ 24/7</h5>
                        <p class="text-muted mb-0">Hotline: 0123 456 789</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

