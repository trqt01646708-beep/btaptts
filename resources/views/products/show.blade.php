@extends('layouts.user')

@section('title', $product->name)

@section('content')
<div class="row">
    <div class="col-md-6 mb-4">
        @if($product->image)
            <img src="{{ asset($product->image) }}" class="img-fluid rounded shadow-sm w-100" alt="{{ $product->name }}">
        @endif
        @if($product->thumbnail)
            <div class="mt-3">
                <img src="{{ asset($product->thumbnail) }}" width="100" class="img-thumbnail" alt="">
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
          </ol>
        </nav>
        
        <h1 class="display-5 fw-bold mb-3">{{ $product->name }}</h1>
        
        <div class="mb-4">
            @if($product->sale_price)
                <h3 class="text-primary fw-bold mb-0">
                    {{ number_format($product->sale_price) }}đ
                    <small class="text-decoration-line-through text-muted fw-normal ms-2 fs-5">{{ number_format($product->regular_price) }}đ</small>
                </h3>
            @else
                <h3 class="text-primary fw-bold">{{ number_format($product->regular_price) }}đ</h3>
            @endif
        </div>
        
        <div class="mb-4">
            <h5>Mô tả ngắn:</h5>
            <p class="text-secondary">{{ $product->description }}</p>
        </div>
        
        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-5">
            @csrf
            <div class="row align-items-center">
                <div class="col-auto">
                    <input type="number" name="quantity" value="1" min="1" class="form-control" style="width: 80px;">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">Thêm vào giỏ hàng</button>
                </div>
            </div>
        </form>
        
        <div class="product-details">
            <ul class="nav nav-tabs" id="productTab">
              <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc">Mô tả sản phẩm</button>
              </li>
            </ul>
            <div class="tab-content p-3 border border-top-0 rounded-bottom bg-white" id="productTabContent">
              <div class="tab-pane fade show active" id="desc">
                  {!! nl2br(e($product->content)) !!}
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
