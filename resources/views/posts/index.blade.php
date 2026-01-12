@extends('layouts.master')

@section('title', 'Danh sách bài viết')

@section('content')
<h2>Danh sách bài viết</h2>

@if(session('success'))
    <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
        {{ session('success') }}
    </div>
@endif

<a href="{{ route('posts.create') }}" style="display: inline-block; background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-bottom: 20px;">Tạo bài viết mới</a>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #f4f4f4;">
            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Tên bài viết</th>
            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Hình ảnh</th>
            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Chuyên mục</th>
            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Mô tả</th>
            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Ngày tạo</th>
            <th style="border: 1px solid #ddd; padding: 12px; text-align: center;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @forelse($posts as $post)
        <tr>
            <td style="border: 1px solid #ddd; padding: 12px;">{{ $post->name }}</td>
            <td style="border: 1px solid #ddd; padding: 12px;">
                <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->name }}" style="width: 80px; height: 60px; object-fit: cover;">
            </td>
            <td style="border: 1px solid #ddd; padding: 12px;">
                @foreach($post->categories as $category)
                    <span style="background-color: #e3f2fd; padding: 3px 8px; border-radius: 3px; margin-right: 5px; display: inline-block; margin-bottom: 3px;">{{ $category->name }}</span>
                @endforeach
            </td>
            <td style="border: 1px solid #ddd; padding: 12px;">{{ Str::limit($post->description, 100) }}</td>
            <td style="border: 1px solid #ddd; padding: 12px;">{{ $post->created_at->format('d/m/Y') }}</td>
            <td style="border: 1px solid #ddd; padding: 12px; text-align: center;">
                <a href="{{ route('posts.edit', $post->id) }}" style="background-color: #ffc107; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; margin-right: 5px;">Sửa</a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" style="background-color: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">Xóa</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="border: 1px solid #ddd; padding: 12px; text-align: center;">Chưa có bài viết nào</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
