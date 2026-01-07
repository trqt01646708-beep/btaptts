@extends('layouts.admin')

@section('title', 'Chỉnh sửa vai trò')
@section('header', 'Chỉnh sửa vai trò')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-warning">
            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tên vai trò</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ $role->name }}" required>
                    </div>
                    <div class="form-group">
                        <label>Quyền hạn</label>
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                        {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ $permission->name }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">Cập nhật</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-default">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
