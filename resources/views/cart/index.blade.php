@extends('layouts.user')

@section('title', 'Giỏ hàng')

@section('content')
<h2 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Giỏ hàng của bạn</h2>

@if(count($cart) > 0)
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0 @endphp
                    @foreach($cart as $id => $details)
                        @php $total += $details['price'] * $details['quantity'] @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    @if($details['image'])
                                        <img src="{{ asset($details['image']) }}" width="60" class="rounded me-3">
                                    @endif
                                    <span class="fw-bold">{{ $details['name'] }}</span>
                                </div>
                            </td>
                            <td>{{ number_format($details['price']) }}đ</td>
                            <td>
                                <input type="number" value="{{ $details['quantity'] }}" class="form-control quantity update-cart" data-id="{{ $id }}" min="1" style="width: 80px;">
                            </td>
                            <td class="fw-bold">{{ number_format($details['price'] * $details['quantity']) }}đ</td>
                            <td class="text-center">
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white p-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Tiếp tục mua sắm</a>
            </div>
            <div class="col-md-6 text-end">
                <h4 class="mb-3">Tổng cộng: <span class="text-primary fw-bold">{{ number_format($total) }}đ</span></h4>
                <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg rounded-pill px-5">Tiến hành thanh toán</a>
            </div>
        </div>
    </div>
</div>
@else
<div class="text-center py-5">
    <i class="fas fa-shopping-basket fa-4x text-muted mb-3"></i>
    <h3>Giỏ hàng của bạn đang trống</h3>
    <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Bắt đầu mua sắm</a>
</div>
@endif
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
    $(".update-cart").change(function (e) {
        e.preventDefault();
        var ele = $(this);
        $.ajax({
            url: "{{ route('cart.update') }}",
            method: "post",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele.attr("data-id"), 
                quantity: ele.parents("tr").find(".quantity").val()
            },
            success: function (response) {
               window.location.reload();
            }
        });
    });
</script>
@endsection
