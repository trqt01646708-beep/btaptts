@extends('admin.layouts.app')

@section('title', 'Thêm bài viết')
@section('page-title', 'Thêm bài viết mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Bài viết</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin bài viết</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title') }}" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="excerpt">Trích dẫn <span class="text-danger">*</span></label>
                            <textarea name="excerpt" id="excerpt" rows="3" 
                                      class="form-control @error('excerpt') is-invalid @enderror" required>{{ old('excerpt') }}</textarea>
                            @error('excerpt')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content">Nội dung</label>
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
                        <h3 class="card-title">Tùy chọn</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="featured_image">Ảnh đại diện <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" name="featured_image" id="featured_image" 
                                       class="custom-file-input @error('featured_image') is-invalid @enderror" accept="image/*" required>
                                <label class="custom-file-label" for="featured_image">Chọn file</label>
                            </div>
                            @error('featured_image')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                            <div id="featured-image-preview" class="mt-2"></div>
                        </div>

                        <div class="form-group">
                            <label for="categories">Danh mục</label>
                            <select name="categories[]" id="categories" class="form-control select2" multiple>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">Trạng thái <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                                <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
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
                            <i class="fas fa-save mr-1"></i> Lưu bài viết
                        </button>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
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
    // Custom file input label
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        var label = e.target.nextElementSibling;
        label.textContent = fileName;

        // Preview image
        var preview = document.getElementById('featured-image-preview');
        var file = e.target.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" class="img-fluid img-thumbnail" style="max-height: 150px;">';
        }
        reader.readAsDataURL(file);
    });
</script>
@endpush
