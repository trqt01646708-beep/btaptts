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

    <div class="container mb-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <!-- Categories -->
                <div class="card mb-4" style="border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
                    <div class="card-header" style="background: linear-gradient(135deg, #2563eb, #1e40af); border: none; border-radius: 12px 12px 0 0; padding: 16px;">
                        <h5 class="mb-0" style="color: #fff; font-weight: 700;">
                            <i class="fas fa-list me-2"></i>Danh mục
                        </h5>
                    </div>
                    <div class="card-body" style="padding: 16px;">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <a href="{{ route('products.index') }}" style="text-decoration: none; font-weight: 600; padding: 8px 12px; display: block; border-radius: 6px; transition: all 0.3s; color: #4b5563;" 
                                   onmouseover="this.style.background='#f8fafc'; this.style.color='#2563eb'" 
                                   onmouseout="this.style.background=''; this.style.color='#4b5563'">
                                    <i class="fas fa-list me-2"></i>Tất cả sản phẩm
                                </a>
                            </li>
                            @foreach($categories as $cat)
                            <li class="mb-2">
                                <a href="{{ route('products.category', $cat->slug) }}" style="text-decoration: none; font-weight: 600; padding: 8px 12px; display: block; border-radius: 6px; transition: all 0.3s; {{ $cat->id == $category->id ? 'background: linear-gradient(135deg, #e0e7ff, #dbeafe); color: #2563eb;' : 'color: #4b5563;' }}" 
                                   onmouseover="!this.style.background && (this.style.background='#f8fafc', this.style.color='#2563eb')" 
                                   onmouseout="!{{ $cat->id == $category->id ? 'true' : 'false' }} && (this.style.background='', this.style.color='#4b5563')">
                                    <i class="fas fa-tag me-2" style="color: #2563eb;"></i>{{ $cat->name }} 
                                    <span class="text-muted" style="font-weight: 400;">({{ $cat->products_count }})</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Price Filter -->
                <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
                    <div class="card-header" style="background: linear-gradient(135deg, #2563eb, #1e40af); border: none; border-radius: 12px 12px 0 0; padding: 16px;">
                        <h5 class="mb-0" style="color: #fff; font-weight: 700;">
                            <i class="fas fa-filter me-2"></i>Lọc theo giá
                        </h5>
                    </div>
                    <div class="card-body" style="padding: 16px;">
                        <form action="{{ route('products.category', $category->slug) }}" method="GET">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 700; color: #1f2937;">Giá từ</label>
                                <input type="number" name="min_price" class="form-control" style="border-radius: 6px; border: 2px solid #e2e8f0;" value="{{ request('min_price') }}" placeholder="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 700; color: #1f2937;">Đến</label>
                                <input type="number" name="max_price" class="form-control" style="border-radius: 6px; border: 2px solid #e2e8f0;" value="{{ request('max_price') }}" placeholder="10000000">
                            </div>
                            <button type="submit" class="btn btn-primary w-100" style="border-radius: 6px; font-weight: 700;">
                                <i class="fas fa-search me-2"></i>Tìm kiếm
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="col-lg-9">
                <!-- Sort & View -->
                <div class="card mb-4" style="border: none; background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); padding: 20px;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <p class="mb-0" style="font-weight: 600; color: #1f2937;">
                            <i class="fas fa-cubes me-2" style="color: #2563eb;"></i>Hiển thị <strong style="color: #2563eb;">{{ $products->count() }}</strong> / <strong>{{ $products->total() }}</strong> sản phẩm
                        </p>
                        <form action="" method="GET" class="d-flex align-items-center gap-2">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if(request('min_price'))
                                <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                            @endif
                            @if(request('max_price'))
                                <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                            @endif
                            <label class="mb-0" style="font-weight: 700; color: #1f2937;">
                                <i class="fas fa-sort me-2" style="color: #2563eb;"></i>Sắp xếp:
                            </label>
                            <select name="sort" class="form-select" style="width: auto; border-radius: 6px; border: 2px solid #e2e8f0; font-weight: 600;" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp → Cao</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao → Thấp</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên: A → Z</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến nhất</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="row">
                    @forelse($products as $product)
                    <div class="col-6 col-md-4 mb-4">
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
