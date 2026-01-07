@extends('layouts.admin')

@section('title', 'Thêm vai trò mới')
@section('header', 'Thêm vai trò mới')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tên vai trò</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Nhập tên vai trò" required>
                    </div>
                    <div class="form-group">
                        <label>Quyền hạn</label>
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                                    <label class="form-check-label">{{ $permission->name }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-default">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
