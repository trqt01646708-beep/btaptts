@extends('admin.layouts.app')

@section('title', 'Quản lý đơn hàng')
@section('page-title', 'Quản lý đơn hàng')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Đơn hàng</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách đơn hàng</h3>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <form action="{{ route('admin.orders.index') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Mã đơn, tên, email, SĐT..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="">-- Trạng thái --</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    @switch($status)
                                        @case('pending') Chờ xử lý @break
                                        @case('confirmed') Đã xác nhận @break
                                        @case('processing') Đang xử lý @break
                                        @case('shipped') Đang giao @break
                                        @case('delivered') Đã giao @break
                                        @case('cancelled') Đã hủy @break
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="payment_status" class="form-control">
                            <option value="">-- Thanh toán --</option>
                            @foreach($paymentStatuses as $ps)
                                <option value="{{ $ps }}" {{ request('payment_status') == $ps ? 'selected' : '' }}>
                                    @switch($ps)
                                        @case('unpaid') Chưa thanh toán @break
                                        @case('paid') Đã thanh toán @break
                                        @case('refunded') Đã hoàn tiền @break
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="from_date" class="form-control" placeholder="Từ ngày" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="to_date" class="form-control" placeholder="Đến ngày" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="120">Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Ngày đặt</th>
                            <th width="150">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="font-weight-bold">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>
                                <strong>{{ $order->customer_name }}</strong>
                                <br><small class="text-muted">{{ $order->customer_email }}</small>
                                <br><small class="text-muted">{{ $order->customer_phone }}</small>
                            </td>
                            <td class="font-weight-bold text-danger">{{ number_format($order->total) }}đ</td>
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
                            <td>
                                @switch($order->payment_status)
                                    @case('unpaid')
                                        <span class="badge badge-warning">Chưa thanh toán</span>
                                        @break
                                    @case('paid')
                                        <span class="badge badge-success">Đã thanh toán</span>
                                        @break
                                    @case('refunded')
                                        <span class="badge badge-info">Đã hoàn tiền</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm" title="Chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có đơn hàng nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
