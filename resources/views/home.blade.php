@extends('layouts.user')

@section('title', 'Chào mừng bạn đến với Cửa hàng của tôi')

@section('content')
<div class="p-5 mb-4 bg-light rounded-3 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="container-fluid py-5 text-center">
        <h1 class="display-5 fw-bold">Trải Nghiệm Mua Sắm Cao Cấp</h1>
        <p class="col-md-12 fs-4">Khám phá những sản phẩm mới nhất và những bài viết hữu ích của chúng tôi.</p>
        <a href="{{ route('products.index') }}" class="btn btn-light btn-lg px-4 rounded-pill">Mua Sắm Ngay</a>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Sản Phẩm Nổi Bật</h2>
        <a href="{{ route('products.index') }}" class="text-decoration-none">Xem Tất Cả <i class="fas fa-arrow-right ms-1"></i></a>
    </div>
    @foreach($products as $product)
    <div class="col-md-3 mb-4">
        <div class="card h-100 shadow-sm border-0">
            @if($product->image)
                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
            @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">Không có hình ảnh</div>
            @endif
            <div class="card-body text-center">
                <h5 class="card-title fw-bold text-truncate">{{ $product->name }}</h5>
                <p class="card-text text-primary fs-5 fw-bold">
                    @if($product->sale_price)
                        <span class="text-decoration-line-through text-muted fs-6">{{ number_format($product->regular_price) }}đ</span>
                        {{ number_format($product->sale_price) }}đ
                    @else
                        {{ number_format($product->regular_price) }}đ
                    @endif
                </p>
                <div class="d-grid">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary rounded-pill w-100">Thêm vào giỏ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row mt-5">
    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Bài Viết Mới Nhất</h2>
        <a href="{{ route('posts.index') }}" class="text-decoration-none">Đọc Thêm <i class="fas fa-arrow-right ms-1"></i></a>
    </div>
    @foreach($posts as $post)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0">
            @if($post->image)
                <img src="{{ asset($post->image) }}" class="card-img-top" alt="{{ $post->name }}" style="height: 180px; object-fit: cover;">
            @endif
            <div class="card-body">
                <h5 class="card-title fw-bold">{{ $post->name }}</h5>
                <p class="card-text text-muted">{{ Str::limit($post->description, 100) }}</p>
                <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-link p-0 text-decoration-none fw-bold">Xem chi tiết</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
