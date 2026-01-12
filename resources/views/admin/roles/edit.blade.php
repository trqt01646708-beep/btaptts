@extends('admin.layouts.app')

@section('title', 'Sửa vai trò')
@section('page-title', 'Sửa vai trò')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Vai trò</a></li>
    <li class="breadcrumb-item active">Sửa</li>
@endsection

@section('content')
    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin vai trò</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Tên vai trò (slug) <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $role->name) }}" required {{ $role->name === 'admin' ? 'readonly' : '' }}>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="display_name">Tên hiển thị</label>
                            <input type="text" name="display_name" id="display_name" class="form-control @error('display_name') is-invalid @enderror" 
                                   value="{{ old('display_name', $role->display_name) }}">
                            @error('display_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea name="description" id="description" rows="3" 
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Cập nhật
                        </button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i> Hủy
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Phân quyền</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="checkAll()">Chọn tất cả</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="uncheckAll()">Bỏ chọn tất cả</button>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach($permissions as $groupName => $groupPermissions)
                            <div class="card mb-3">
                                <div class="card-header bg-light py-2">
                                    <strong>{{ $groupName ?? 'Khác' }}</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($groupPermissions as $permission)
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                           class="custom-control-input permission-checkbox" id="permission_{{ $permission->id }}"
                                                           {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="permission_{{ $permission->id }}">
                                                        {{ $permission->display_name ?? $permission->name }}
                                                        <br><small class="text-muted">{{ $permission->name }}</small>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    function checkAll() {
        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
            checkbox.checked = true;
        });
    }
    
    function uncheckAll() {
        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
            checkbox.checked = false;
        });
    }
</script>
@endpush
