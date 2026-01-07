@extends('layouts.admin')

@section('title', 'Thêm người dùng mới')
@section('header', 'Thêm người dùng mới')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Họ tên</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Nhập họ tên" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Nhập email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                    </div>
                    <div class="form-group">
                        <label>Vai trò</label>
                        <div class="row">
                            @foreach($roles as $role)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}">
                                    <label class="form-check-label">{{ $role->name }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-default">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
