@extends('layouts.admin')

@section('title', 'Quản lý bài viết')
@section('header', 'Bài viết')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách bài viết</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">Thêm mới</a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                             <th>Hình ảnh</th>
                            <th>Tiêu đề</th>
                            <th>Trạng thái</th>
                            <th>Ngày đăng</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>
                                @if($post->image)
                                    <img src="{{ asset($post->image) }}" width="50" alt="">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td>{{ $post->name }}</td>
                            <td>
                                <span class="badge {{ $post->status == 'active' ? 'badge-success' : 'badge-danger' }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td>{{ $post->published_at }}</td>
                            <td>
                                <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                {{ $posts->links() }}
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection
