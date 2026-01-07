@extends('layouts.admin')

@section('title', 'Thêm quyền mới')
@section('header', 'Thêm quyền mới')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <form action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tên quyền hạn</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Nhập tên quyền hạn" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-default">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
