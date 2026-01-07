@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')
@section('header', 'Đơn hàng #' . $order->id)

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="invoice p-3 mb-3 shadow-sm border rounded bg-white">
            <div class="row mb-4">
              <div class="col-12">
                <h4 class="text-primary fw-bold border-bottom pb-2">
                  <i class="fas fa-shopping-bag me-2"></i> Chi tiết hóa đơn
                  <small class="float-end text-muted fs-6">Ngày đặt: {{ $order->created_at->format('d/m/Y') }}</small>
                </h4>
              </div>
            </div>
            
            <div class="row invoice-info mb-4">
              <div class="col-sm-4 invoice-col border-end">
                <p class="text-muted text-uppercase small fw-bold mb-2">Khách hàng</p>
                <address>
                  <strong class="fs-5">{{ $order->customer_name }}</strong><br>
                  Số điện thoại: {{ $order->customer_phone }}<br>
                  Email: {{ $order->customer_email }}<br>
                  Địa chỉ: {{ $order->customer_address }}
                </address>
              </div>
              <div class="col-sm-4 invoice-col border-end">
                <p class="text-muted text-uppercase small fw-bold mb-2">Thông tin vận chuyển</p>
                <address>
                  <b>Mã đơn hàng:</b> #{{ $order->id }}<br>
                  <b>Hợp đồng số:</b> {{ time() }}<br>
                  <b>Hình thức:</b> Thanh toán khi nhận hàng (COD)
                </address>
              </div>
              <div class="col-sm-4 invoice-col">
                <p class="text-muted text-uppercase small fw-bold mb-2">Trạng thái đơn hàng</p>
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-tasks text-info"></i></span>
                        <select name="status" onchange="this.form.submit()" class="form-select fw-bold border-start-0">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <small class="text-muted mt-1 d-block italic">* Chọn trạng thái mới để tự động cập nhật</small>
                </form>
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-12 table-responsive">
                <table class="table table-striped table-bordered align-middle text-center">
                  <thead class="bg-primary text-white">
                  <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($order->orderItems as $item)
                  <tr>
                    <td class="text-start ps-4">
                        @if($item->product && $item->product->image)
                            <img src="{{ asset($item->product->image) }}" width="40" class="rounded me-2 shadow-sm">
                        @endif
                        <strong>{{ $item->product_name }}</strong>
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price) }}đ</td>
                    <td class="fw-bold">{{ number_format($item->quantity * $item->price) }}đ</td>
                  </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
            </div>

            <div class="row">
              <div class="col-7">
                <p class="lead text-muted small mt-4 italic">Cảm ơn bạn đã tin tưởng sử dụng dịch vụ của chúng tôi!</p>
              </div>
              <div class="col-5">
                <div class="table-responsive">
                  <table class="table table-borderless">
                    <tr>
                      <th style="width:50%" class="text-end">Tạm tính:</th>
                      <td class="text-end">{{ number_format($order->total_amount) }}đ</td>
                    </tr>
                    <tr>
                      <th class="text-end">Phí vận chuyển:</th>
                      <td class="text-end">0đ</td>
                    </tr>
                    <tr class="border-top">
                      <th class="text-end fs-4">Tổng cộng:</th>
                      <td class="text-end fs-4 fw-bold text-primary">{{ number_format($order->total_amount) }}đ</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>

            <div class="row no-print mt-4 pt-3 border-top">
              <div class="col-12">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
                <button type="button" class="btn btn-success float-end rounded-pill px-4" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> In hóa đơn
                </button>
              </div>
            </div>
          </div>
    </div>
</div>
@endsection
