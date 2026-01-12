@extends('admin.layouts.app')

@section('title', 'Thêm sản phẩm')
@section('page-title', 'Thêm sản phẩm mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin sản phẩm</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="regular_price">Giá gốc <span class="text-danger">*</span></label>
                                    <input type="number" name="regular_price" id="regular_price" 
                                           class="form-control @error('regular_price') is-invalid @enderror" 
                                           value="{{ old('regular_price') }}" min="0" required>
                                    @error('regular_price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sale_price">Giá khuyến mãi</label>
                                    <input type="number" name="sale_price" id="sale_price" 
                                           class="form-control @error('sale_price') is-invalid @enderror" 
                                           value="{{ old('sale_price') }}" min="0">
                                    @error('sale_price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="stock_quantity">Số lượng tồn kho <span class="text-danger">*</span></label>
                                    <input type="number" name="stock_quantity" id="stock_quantity" 
                                           class="form-control @error('stock_quantity') is-invalid @enderror" 
                                           value="{{ old('stock_quantity', 0) }}" min="0" required>
                                    @error('stock_quantity')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="3" 
                                      class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content">Nội dung chi tiết</label>
                            <textarea name="content" id="content" rows="10" 
                                      class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                            @error('content')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Hình ảnh & Tùy chọn</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="image">Ảnh sản phẩm <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" name="image" id="image" 
                                       class="custom-file-input @error('image') is-invalid @enderror" accept="image/*" required>
                                <label class="custom-file-label" for="image">Chọn ảnh</label>
                            </div>
                            @error('image')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                            <div id="image-preview" class="mt-2"></div>
                        </div>

                        <div class="form-group">
                            <label for="thumbnail">Ảnh thumbnail</label>
                            <div class="custom-file">
                                <input type="file" name="thumbnail" id="thumbnail" 
                                       class="custom-file-input" accept="image/*">
                                <label class="custom-file-label" for="thumbnail">Chọn ảnh nhỏ</label>
                            </div>
                            <div id="thumbnail-preview" class="mt-2"></div>
                        </div>

                        <div class="form-group">
                            <label for="category_id">Danh mục</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">Trạng thái <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="published_at">Ngày xuất bản</label>
                            <input type="datetime-local" name="published_at" id="published_at" class="form-control" 
                                   value="{{ old('published_at') }}">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Lưu sản phẩm
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i> Hủy
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.custom-file-input').forEach(function(input) {
        input.addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            var label = e.target.nextElementSibling;
            label.textContent = fileName;

            var previewId = e.target.id + '-preview';
            var preview = document.getElementById(previewId);
            var file = e.target.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = '<img src="' + e.target.result + '" class="img-fluid img-thumbnail" style="max-height: 150px;">';
            }
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
