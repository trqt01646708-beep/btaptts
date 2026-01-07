@extends('layouts.user')

@section('title', 'Sản phẩm')

@section('content')
<h2 class="mb-4">Cửa hàng sản phẩm</h2>
<div class="row">
    @foreach($products as $product)
    <div class="col-md-3 mb-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="position-absolute top-0 end-0 p-2">
                @if($product->sale_price)
                    <span class="badge bg-danger">Giảm giá</span>
                @endif
            </div>
            @if($product->image)
                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;"><i class="fas fa-image fa-3x text-muted"></i></div>
            @endif
            <div class="card-body text-center">
                <h5 class="card-title fw-bold text-truncate">{{ $product->name }}</h5>
                <p class="card-text">
                    @if($product->sale_price)
                        <span class="text-decoration-line-through text-muted small">{{ number_format($product->regular_price) }}đ</span>
                        <span class="text-primary fw-bold fs-5">{{ number_format($product->sale_price) }}đ</span>
                    @else
                        <span class="text-primary fw-bold fs-5">{{ number_format($product->regular_price) }}đ</span>
                    @endif
                </p>
                <div class="d-grid gap-2">
                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-secondary btn-sm rounded-pill">Chi tiết</a>
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill w-100">Thêm vào giỏ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>
@endsection
