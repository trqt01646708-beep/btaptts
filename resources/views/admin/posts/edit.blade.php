@extends('layouts.admin')

@section('title', 'Chỉnh sửa bài viết')
@section('header', 'Chỉnh sửa bài viết')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-warning">
            <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tiêu đề</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ $post->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Hình ảnh</label>
                        @if($post->image)
                            <div class="mb-2">
                                <img src="{{ asset($post->image) }}" width="100" alt="">
                            </div>
                        @endif
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="image">
                                <label class="custom-file-label" for="image">Chọn tệp</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả ngắn</label>
                        <textarea name="description" class="form-control" id="description" rows="3">{{ $post->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="content">Nội dung</label>
                        <textarea name="content" class="form-control" id="content" rows="10" required>{{ $post->content }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select name="status" id="status" class="form-control">
                            <option value="active" {{ $post->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $post->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">Cập nhật</button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-default">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
