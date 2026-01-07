@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm')
@section('header', 'Danh sách Sản phẩm')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-box me-2"></i>Sản phẩm</h5>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-1"></i> Thêm sản phẩm mới
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th width="80">ID</th>
                        <th width="100">Ảnh</th>
                        <th class="text-start">Tên sản phẩm</th>
                        <th>Giá gốc</th>
                        <th>Giá giảm</th>
                        <th>Số lượng</th>
                        <th>Trạng thái</th>
                        <th width="150">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>#{{ $product->id }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" width="50" class="rounded shadow-sm">
                            @else
                                <span class="badge bg-secondary">No img</span>
                            @endif
                        </td>
                        <td class="text-start fs-6 fw-bold text-dark">{{ $product->name }}</td>
                        <td class="text-muted">{{ number_format($product->regular_price) }}đ</td>
                        <td class="text-danger fw-bold">
                            {{ $product->sale_price ? number_format($product->sale_price).'đ' : '-' }}
                        </td>
                        <td>
                            <span class="badge {{ $product->quantity > 5 ? 'bg-success' : 'bg-warning' }} px-3 py-2 rounded-pill">
                                {{ $product->quantity }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $product->status == 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2 rounded-pill">
                                {{ $product->status == 'active' ? 'Đang bán' : 'Ngừng bán' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-top-0 py-3">
        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
