@extends('frontend.layouts.app')

@section('title', $category->name . ' - Sản phẩm')

@section('content')
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <!-- Categories -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Danh mục</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <a href="{{ route('products.index') }}" class="text-decoration-none text-dark">
                                    Tất cả sản phẩm
                                </a>
                            </li>
                            @foreach($categories as $cat)
                            <li class="mb-2">
                                <a href="{{ route('products.category', $cat->slug) }}" class="text-decoration-none {{ $cat->id == $category->id ? 'fw-bold text-primary' : 'text-dark' }}">
                                    {{ $cat->name }} <span class="text-muted">({{ $cat->products_count }})</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Price Filter -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Lọc theo giá</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.category', $category->slug) }}" method="GET">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                            <div class="mb-3">
                                <label class="form-label">Giá từ</label>
                                <input type="number" name="min_price" class="form-control" value="{{ request('min_price') }}" placeholder="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Đến</label>
                                <input type="number" name="max_price" class="form-control" value="{{ request('max_price') }}" placeholder="10000000">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Áp dụng</button>
                        </form>
                    </div>
                </div>

                <!-- Category Description -->
                @if($category->description)
                <div class="card">
                    <div class="card-body">
                        <p class="mb-0">{{ $category->description }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Products -->
            <div class="col-lg-9">
                <!-- Category Header -->
                <div class="mb-4">
                    <h1 class="h3 mb-2">{{ $category->name }}</h1>
                    <p class="text-muted">{{ $products->total() }} sản phẩm</p>
                </div>

                <!-- Sort & View -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="mb-0">Hiển thị {{ $products->count() }} / {{ $products->total() }} sản phẩm</p>
                    <form action="" method="GET" class="d-flex align-items-center">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('min_price'))
                            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        @endif
                        @if(request('max_price'))
                            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                        @endif
                        <label class="me-2">Sắp xếp:</label>
                        <select name="sort" class="form-select" style="width: auto;" onchange="this.form.submit()">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp → Cao</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao → Thấp</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên: A → Z</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến nhất</option>
                        </select>
                    </form>
                </div>

                <!-- Products Grid -->
                <div class="row">
                    @forelse($products as $product)
                    <div class="col-6 col-md-4 mb-4">
                        <div class="product-card">
                            <div class="product-image">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                    @else
                                        <img src="https://via.placeholder.com/300x200" alt="{{ $product->name }}">
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
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-box-open fa-3x mb-3"></i>
                            <p class="mb-0">Không tìm thấy sản phẩm nào trong danh mục này</p>
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
