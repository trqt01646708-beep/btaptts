@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn hàng')
@section('page-title', 'Chi tiết đơn hàng #' . $order->order_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
    <li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <!-- Order Items -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sản phẩm đặt mua</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="80">Ảnh</th>
                                <th>Sản phẩm</th>
                                <th class="text-right">Đơn giá</th>
                                <th class="text-center">SL</th>
                                <th class="text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    @if($item->product_image)
                                        <img src="{{ asset('storage/' . $item->product_image) }}" width="60" height="60" class="img-thumbnail">
                                    @else
                                        <img src="https://via.placeholder.com/60" width="60" height="60" class="img-thumbnail">
                                    @endif
                                </td>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-right">{{ number_format($item->price) }}đ</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right font-weight-bold">{{ number_format($item->total) }}đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right">Tạm tính:</td>
                                <td class="text-right">{{ number_format($order->subtotal) }}đ</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right">Phí vận chuyển:</td>
                                <td class="text-right">{{ number_format($order->shipping_fee) }}đ</td>
                            </tr>
                            @if($order->discount > 0)
                            <tr>
                                <td colspan="4" class="text-right">Giảm giá:</td>
                                <td class="text-right text-danger">-{{ number_format($order->discount) }}đ</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">Tổng cộng:</td>
                                <td class="text-right font-weight-bold text-danger" style="font-size: 1.2em;">{{ number_format($order->total) }}đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin khách hàng</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                            <p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Địa chỉ giao hàng:</strong></p>
                            <p>{{ $order->shipping_address }}</p>
                        </div>
                    </div>
                    @if($order->notes)
                    <div class="mt-3">
                        <p><strong>Ghi chú:</strong></p>
                        <p class="text-muted">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Order Status -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Trạng thái đơn hàng</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Trạng thái đơn hàng</label>
                            <select name="status" class="form-control">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i> Cập nhật trạng thái
                        </button>
                    </form>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Trạng thái thanh toán</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-payment', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Phương thức thanh toán</label>
                            <p class="form-control-static">
                                @switch($order->payment_method)
                                    @case('cod') Thanh toán khi nhận hàng (COD) @break
                                    @case('bank') Chuyển khoản ngân hàng @break
                                    @default {{ $order->payment_method }}
                                @endswitch
                            </p>
                        </div>
                        <div class="form-group">
                            <label>Trạng thái thanh toán</label>
                            <select name="payment_status" class="form-control">
                                <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-save mr-1"></i> Cập nhật thanh toán
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin đơn hàng</h3>
                </div>
                <div class="card-body">
                    <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Cập nhật lần cuối:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                    @if($order->user)
                        <p><strong>Tài khoản:</strong> <a href="{{ route('admin.users.edit', $order->user_id) }}">{{ $order->user->email }}</a></p>
                    @endif
                </div>
            </div>

            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-block">
                <i class="fas fa-arrow-left mr-1"></i> Quay lại danh sách
            </a>
        </div>
    </div>
@endsection
