@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($stats['users']) }}</h3>
                    <p>Người dùng</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.users.index') }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($stats['products']) }}</h3>
                    <p>Sản phẩm</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <a href="{{ route('admin.products.index') }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($stats['orders']) }}</h3>
                    <p>Đơn hàng</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($stats['total_revenue']) }}đ</h3>
                    <p>Doanh thu</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- Pending Orders -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Đơn hàng chờ xử lý</h3>
                    <div class="card-tools">
                        <span class="badge badge-warning">{{ $stats['pending_orders'] }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_orders as $order)
                                <tr>
                                    <td><a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->order_number }}</a></td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ number_format($order->total) }}đ</td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge badge-warning">Chờ xử lý</span>
                                                @break
                                            @case('confirmed')
                                                <span class="badge badge-info">Đã xác nhận</span>
                                                @break
                                            @case('processing')
                                                <span class="badge badge-primary">Đang xử lý</span>
                                                @break
                                            @case('shipped')
                                                <span class="badge badge-secondary">Đang giao</span>
                                                @break
                                            @case('delivered')
                                                <span class="badge badge-success">Đã giao</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge badge-danger">Đã hủy</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Không có đơn hàng nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary float-right">Xem tất cả</a>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Người dùng mới đăng ký</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="users-list clearfix">
                        @foreach($recent_users as $user)
                        <li>
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                            @else
                                <img src="https://via.placeholder.com/128" alt="{{ $user->name }}">
                            @endif
                            <a class="users-list-name" href="#">{{ $user->name }}</a>
                            <span class="users-list-date">{{ $user->created_at->diffForHumans() }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.users.index') }}">Xem tất cả người dùng</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-newspaper"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Bài viết</span>
                    <span class="info-box-number">{{ $stats['posts'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Đơn hàng hoàn thành</span>
                    <span class="info-box-number">{{ \App\Models\Order::where('status', 'delivered')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Đơn hàng chờ xử lý</span>
                    <span class="info-box-number">{{ $stats['pending_orders'] }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
