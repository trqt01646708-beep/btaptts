@extends('frontend.layouts.app')

@section('title', 'Trang chủ')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5 mb-5" style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); min-height: 500px; display: flex; align-items: center;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div style="animation: slideInLeft 0.8s ease-out;">
                    <h1 class="display-3 fw-900 mb-3 text-white" style="font-size: 3.5rem; font-weight: 800; line-height: 1.2;">
                        🎉 Chào mừng đến với cửa hàng
                    </h1>
                    <p class="lead mb-4 text-white" style="font-size: 1.3rem; font-weight: 500; opacity: 0.95;">
                        Khám phá các sản phẩm chất lượng cao với giá tốt nhất. Mua sắm thông minh, tiết kiệm tối đa!
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('products.index') }}" class="btn btn-light btn-lg" style="padding: 12px 30px; font-weight: 700; border-radius: 8px;">
                            <i class="fas fa-shopping-bags me-2"></i> Mua sắm ngay
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg" style="padding: 12px 30px; font-weight: 700; border-radius: 8px; border-width: 2px;">
                            <i class="fas fa-envelope me-2"></i> Liên hệ
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" style="animation: slideInRight 0.8s ease-out;">
                <img src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?w=600&h=400&fit=crop" alt="Hero" class="img-fluid rounded-4" style="box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
            </div>
        </div>
    </div>
</section>

<style>
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section class="featured-products mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title mb-0">🔥 Sản phẩm khuyến mãi</h2>
                <p class="text-muted" style="font-weight: 500;">Các sản phẩm được giảm giá đặc biệt trong tuần này</p>
            </div>
            <a href="{{ route('products.sale') }}" class="btn btn-outline-primary" style="border-radius: 8px;">Xem tất cả <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-md-6 col-lg-3">
                <div class="product-card">
                    @if($product->sale_price)
                    <span class="sale-badge">
                        -{{ number_format((($product->regular_price - $product->sale_price) / $product->regular_price) * 100, 0) }}%
                    </span>
                    @endif
                    <div class="product-image">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}">
                    </div>
                    <div class="product-info">
                        <h5 class="product-name">{{ $product->name }}</h5>
                        <div class="product-price">
                            @if($product->sale_price)
                            <span class="current-price">{{ number_format($product->sale_price, 0, ',', '.') }}đ</span>
                            <span class="original-price">{{ number_format($product->regular_price, 0, ',', '.') }}đ</span>
                            @else
                            <span class="current-price">{{ number_format($product->regular_price, 0, ',', '.') }}đ</span>
                            @endif
                        </div>
                        <button onclick="addToCart({{ $product->id }})" class="btn btn-primary w-100">
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
<section class="latest-products mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title mb-0">🆕 Sản phẩm mới nhất</h2>
                <p class="text-muted" style="font-weight: 500;">Những sản phẩm vừa được cập nhật</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary" style="border-radius: 8px;">Xem tất cả <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
        <div class="row g-4">
            @foreach($latestProducts as $product)
            <div class="col-md-6 col-lg-3">
                <div class="product-card">
                    @if($product->sale_price)
                    <span class="sale-badge">
                        -{{ number_format((($product->regular_price - $product->sale_price) / $product->regular_price) * 100, 0) }}%
                    </span>
                    @endif
                    <div class="product-image">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}">
                    </div>
                    <div class="product-info">
                        <h5 class="product-name">{{ $product->name }}</h5>
                        <div class="product-price">
                            @if($product->sale_price)
                            <span class="current-price">{{ number_format($product->sale_price, 0, ',', '.') }}đ</span>
                            <span class="original-price">{{ number_format($product->regular_price, 0, ',', '.') }}đ</span>
                            @else
                            <span class="current-price">{{ number_format($product->regular_price, 0, ',', '.') }}đ</span>
                            @endif
                        </div>
                        <button onclick="addToCart({{ $product->id }})" class="btn btn-primary w-100">
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

<!-- Categories -->
@if($categories->count() > 0)
<section class="categories mb-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%); padding: 60px 0; border-radius: 12px;">
    <div class="container">
        <h2 class="section-title text-center mb-5">📁 Danh mục sản phẩm</h2>
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-md-4 col-lg-2">
                <div style="background: #fff; border-radius: 12px; padding: 24px; text-align: center; transition: all 0.3s; cursor: pointer; border: 2px solid transparent; box-shadow: 0 4px 15px rgba(0,0,0,0.08);" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 35px rgba(37, 99, 235, 0.2)'; this.style.borderColor='#2563eb';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.borderColor='transparent';">
                    <h5 style="font-weight: 700; margin-bottom: 10px; color: #1f2937;">{{ $category->name }}</h5>
                    <p class="text-muted" style="font-weight: 500; margin-bottom: 15px;">
                        <span style="display: block;">{{ $category->products_count }} sản phẩm</span>
                    </p>
                    <a href="{{ route('products.category', $category->slug) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 6px;">Xem <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Latest Posts -->
@if($latestPosts->count() > 0)
<section class="latest-posts mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title mb-0">📰 Tin tức mới nhất</h2>
                <p class="text-muted" style="font-weight: 500;">Cập nhật thông tin sản phẩm, khuyến mãi và mẹo mua sắm</p>
            </div>
            <a href="{{ route('posts.index') }}" class="btn btn-outline-primary" style="border-radius: 8px;">Xem tất cả <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
        <div class="row g-4">
            @foreach($latestPosts as $post)
            <div class="col-md-6 col-lg-4">
                <div class="post-card">
                    @if($post->featured_image)
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" style="height: 220px; object-fit: cover;">
                    @endif
                    <div class="post-info">
                        <p class="post-meta">
                            <i class="fas fa-calendar me-2"></i>{{ $post->published_at->format('d/m/Y') }}
                        </p>
                        <h5 class="post-title">{{ $post->title }}</h5>
                        <p class="post-excerpt">{{ Str::limit($post->excerpt, 100) }}</p>
                        <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-sm btn-primary" style="border-radius: 6px;">
                            Đọc thêm <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Features -->
<section class="features py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%); border-radius: 12px;">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div style="animation: fadeInUp 0.6s ease-out 0.1s both;">
                    <div style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); width: 80px; height: 80px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 8px 20px rgba(37, 99, 235, 0.2);">
                        <i class="fas fa-shipping-fast fa-2x" style="color: #2563eb;"></i>
                    </div>
                    <h5 style="font-weight: 700; color: #1f2937; margin-bottom: 10px;">Giao hàng nhanh</h5>
                    <p class="text-muted" style="font-weight: 500;">Miễn phí vận chuyển cho đơn hàng trên 500k</p>
                </div>
            </div>
            <div class="col-md-3">
                <div style="animation: fadeInUp 0.6s ease-out 0.2s both;">
                    <div style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); width: 80px; height: 80px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.2);">
                        <i class="fas fa-shield-alt fa-2x" style="color: #10b981;"></i>
                    </div>
                    <h5 style="font-weight: 700; color: #1f2937; margin-bottom: 10px;">Thanh toán an toàn</h5>
                    <p class="text-muted" style="font-weight: 500;">Bảo mật thông tin 100%</p>
                </div>
            </div>
            <div class="col-md-3">
                <div style="animation: fadeInUp 0.6s ease-out 0.3s both;">
                    <div style="background: linear-gradient(135deg, #fcd34d, #fbbf24); width: 80px; height: 80px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 8px 20px rgba(245, 158, 11, 0.2);">
                        <i class="fas fa-undo-alt fa-2x" style="color: #f59e0b;"></i>
                    </div>
                    <h5 style="font-weight: 700; color: #1f2937; margin-bottom: 10px;">Đổi trả dễ dàng</h5>
                    <p class="text-muted" style="font-weight: 500;">Đổi trả trong vòng 7 ngày</p>
                </div>
            </div>
            <div class="col-md-3">
                <div style="animation: fadeInUp 0.6s ease-out 0.4s both;">
                    <div style="background: linear-gradient(135deg, #fecaca, #fca5a5); width: 80px; height: 80px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 8px 20px rgba(239, 68, 68, 0.2);">
                        <i class="fas fa-headset fa-2x" style="color: #ef4444;"></i>
                    </div>
                    <h5 style="font-weight: 700; color: #1f2937; margin-bottom: 10px;">Hỗ trợ 24/7</h5>
                    <p class="text-muted" style="font-weight: 500;">Luôn sẵn sàng hỗ trợ khách hàng</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
