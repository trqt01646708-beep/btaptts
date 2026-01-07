@extends('layouts.admin')

@section('title', 'Thêm bài viết mới')
@section('header', 'Thêm bài viết mới')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tiêu đề</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Nhập tiêu đề" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Hình ảnh</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="image">
                                <label class="custom-file-label" for="image">Chọn tệp</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả ngắn</label>
                        <textarea name="description" class="form-control" id="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="content">Nội dung</label>
                        <textarea name="content" class="form-control" id="content" rows="10" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select name="status" id="status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-default">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
