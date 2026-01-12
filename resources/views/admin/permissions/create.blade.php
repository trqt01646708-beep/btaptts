@extends('admin.layouts.app')

@section('title', 'Thêm quyền')
@section('page-title', 'Thêm quyền mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Quyền hạn</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin quyền</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Tên quyền (slug) <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" placeholder="vd: posts.create, products.edit" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Format: module.action (vd: posts.create, users.delete)</small>
                        </div>

                        <div class="form-group">
                            <label for="display_name">Tên hiển thị</label>
                            <input type="text" name="display_name" id="display_name" class="form-control @error('display_name') is-invalid @enderror" 
                                   value="{{ old('display_name') }}" placeholder="vd: Tạo bài viết">
                            @error('display_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="group_name">Nhóm quyền</label>
                            <input type="text" name="group_name" id="group_name" class="form-control @error('group_name') is-invalid @enderror" 
                                   value="{{ old('group_name') }}" placeholder="vd: Quản lý bài viết" list="groups">
                            <datalist id="groups">
                                @foreach($groups as $group)
                                    <option value="{{ $group }}">
                                @endforeach
                            </datalist>
                            @error('group_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea name="description" id="description" rows="3" 
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Lưu quyền
                        </button>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i> Hủy
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
