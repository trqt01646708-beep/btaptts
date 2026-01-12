@extends('admin.layouts.app')

@section('title', 'Quản lý quyền hạn')
@section('page-title', 'Quản lý quyền hạn')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Quyền hạn</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách quyền hạn</h3>
            <div class="card-tools">
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Thêm quyền
                </a>
            </div>
        </div>
        <div class="card-body">
            @foreach($permissions as $groupName => $groupPermissions)
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">{{ $groupName ?? 'Khác' }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th width="50">ID</th>
                                    <th>Tên quyền</th>
                                    <th>Tên hiển thị</th>
                                    <th>Mô tả</th>
                                    <th width="120">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupPermissions as $permission)
                                <tr>
                                    <td>{{ $permission->id }}</td>
                                    <td><code>{{ $permission->name }}</code></td>
                                    <td>{{ $permission->display_name ?? '-' }}</td>
                                    <td>{{ $permission->description ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn btn-info btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
