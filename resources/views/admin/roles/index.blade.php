@extends('admin.layouts.app')

@section('title', 'Quản lý vai trò')
@section('page-title', 'Quản lý vai trò')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Vai trò</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách vai trò</h3>
            <div class="card-tools">
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Thêm vai trò
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="50">ID</th>
                            <th>Tên vai trò</th>
                            <th>Tên hiển thị</th>
                            <th>Mô tả</th>
                            <th>Số người dùng</th>
                            <th>Số quyền</th>
                            <th width="150">Ngày tạo</th>
                            <th width="120">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td><code>{{ $role->name }}</code></td>
                            <td>{{ $role->display_name ?? '-' }}</td>
                            <td>{{ $role->description ?? '-' }}</td>
                            <td><span class="badge badge-info">{{ $role->users_count }}</span></td>
                            <td><span class="badge badge-success">{{ $role->permissions_count }}</span></td>
                            <td>{{ $role->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-info btn-sm" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($role->name !== 'admin')
                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Không có vai trò nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $roles->links() }}
            </div>
        </div>
    </div>
@endsection
