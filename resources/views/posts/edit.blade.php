@extends('layouts.master')

@section('title', 'Chỉnh sửa bài viết')

@section('content')
<h2>Chỉnh sửa bài viết</h2>

@if($errors->any())
    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div style="margin-bottom: 15px;">
        <label for="name" style="display: block; margin-bottom: 5px; font-weight: bold;">Tên bài viết <span style="color: red;">*</span></label>
        <input type="text" id="name" name="name" value="{{ old('name', $post->name) }}" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
    </div>

    <div style="margin-bottom: 15px;">
        <label for="thumbnail" style="display: block; margin-bottom: 5px; font-weight: bold;">Hình ảnh</label>
        <input type="file" id="thumbnail" name="thumbnail" accept="image/*" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        @if($post->thumbnail)
            <div style="margin-top: 10px;">
                <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->name }}" style="width: 200px; height: 150px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
            </div>
        @endif
    </div>

    <div style="margin-bottom: 15px;">
        <label for="description" style="display: block; margin-bottom: 5px; font-weight: bold;">Mô tả <span style="color: red;">*</span></label>
        <textarea id="description" name="description" required rows="4" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">{{ old('description', $post->description) }}</textarea>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="content" style="display: block; margin-bottom: 5px; font-weight: bold;">Nội dung</label>
        <textarea id="content" name="content" rows="8" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">{{ old('content', $post->content) }}</textarea>
    </div>

    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Danh mục <span style="color: red;">*</span></label>
        @foreach($categories as $category)
            <div style="margin-bottom: 5px;">
                <label style="display: inline-flex; align-items: center;">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                        {{ in_array($category->id, old('categories', $post->categories->pluck('id')->toArray())) ? 'checked' : '' }} 
                        style="margin-right: 5px;">
                    {{ $category->name }}
                </label>
            </div>
        @endforeach
    </div>

    <div>
        <button type="submit" style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;">Cập nhật</button>
        <a href="{{ route('posts.index') }}" style="background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Hủy</a>
    </div>
</form>
@endsection
