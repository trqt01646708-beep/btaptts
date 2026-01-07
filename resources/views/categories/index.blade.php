@extends('layouts.master')

@section('title', 'Danh sách danh mục')

@section('content')
<h2>Danh sách danh mục</h2>

@if(session('success'))
    <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
        {{ session('success') }}
    </div>
@endif

<a href="{{ route('categories.create') }}" style="display: inline-block; background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-bottom: 20px;">Tạo danh mục mới</a>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #f4f4f4;">
            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Tên danh mục</th>
            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Ngày tạo</th>
            <th style="border: 1px solid #ddd; padding: 12px; text-align: center;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $category)
        <tr>
            <td style="border: 1px solid #ddd; padding: 12px;">{{ $category->name }}</td>
            <td style="border: 1px solid #ddd; padding: 12px;">{{ $category->created_at->format('d/m/Y H:i') }}</td>
            <td style="border: 1px solid #ddd; padding: 12px; text-align: center;">
                <a href="{{ route('categories.edit', $category->id) }}" style="background-color: #ffc107; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; margin-right: 5px;">Sửa</a>
                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" style="background-color: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">Xóa</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" style="border: 1px solid #ddd; padding: 12px; text-align: center;">Chưa có danh mục nào</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection