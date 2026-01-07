@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')
@section('header', 'Danh sách đơn hàng')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h3 class="card-title fw-bold text-primary"><i class="fas fa-shopping-cart me-2"></i>Đơn hàng</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th class="text-start">Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th width="180">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="fw-bold">#{{ $order->id }}</td>
                            <td class="text-start">
                                <div><strong>{{ $order->customer_name }}</strong></div>
                                <small class="text-muted">{{ $order->customer_email }}</small>
                            </td>
                            <td class="fw-bold text-primary">{{ number_format($order->total_amount) }}đ</td>
                            <td>
                                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="form-select form-select-sm rounded-pill px-3">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                </form>
                            </td>
                            <td>{{ $order->created_at->format('H:i, d/m/Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-top-0 py-3">
                <div class="d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
