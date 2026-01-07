@extends('layouts.admin')

@section('title', 'Thêm Sản phẩm mới')
@section('header', 'Thêm Sản phẩm')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên sản phẩm</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nhập tên sản phẩm..." required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả ngắn</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Nhập mô tả ngắn cho khách hàng...">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nội dung chi tiết</label>
                        <textarea name="content" class="form-control" rows="8" placeholder="Nhập nội dung chi tiết bài viết/sản phẩm...">{{ old('content') }}</textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Giá bán gốc (đ)</label>
                                <input type="number" name="regular_price" step="0.01" class="form-control @error('regular_price') is-invalid @enderror" value="{{ old('regular_price') }}" required>
                                @error('regular_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Giá khuyến mãi (đ)</label>
                                <input type="number" name="sale_price" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" value="{{ old('sale_price') }}">
                                @error('sale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Số lượng trong kho</label>
                                <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" required>
                                @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Đang bán</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ngừng bán</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ảnh sản phẩm chính</label>
                        <input type="file" name="image" class="form-control mb-2">
                        <small class="text-muted">Dung lượng tối đa 2MB (jpg, png, jpeg)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ảnh thu nhỏ (Thumbnail)</label>
                        <input type="file" name="thumbnail" class="form-control mb-2">
                        <small class="text-muted">Dung lượng tối đa 1MB</small>
                    </div>
                </div>
            </div>

            <div class="mt-4 border-top pt-4 text-end">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4 me-2">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary rounded-pill px-5">Lưu sản phẩm</button>
            </div>
        </form>
    </div>
</div>
@endsection
