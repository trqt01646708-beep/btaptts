@extends('layouts.admin')

@section('title', 'Chỉnh sửa quyền hạn')
@section('header', 'Chỉnh sửa quyền hạn')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-warning">
            <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tên quyền hạn</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ $permission->name }}" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">Cập nhật</button>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-default">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
