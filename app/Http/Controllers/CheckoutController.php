<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Mail\NewOrderNotification;
use App\Mail\OrderConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Hiển thị trang thanh toán
     */
    public function index()
    {
        $cart = $this->getCart();
        
        if (!$cart || $cart->items()->count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống');
        }

        $cartItems = $cart->items()->with('product')->get();
        $subtotal = $cartItems->sum('total');
        $shippingFee = 30000; // Phí ship mặc định
        $total = $subtotal + $shippingFee;

        $user = auth()->user();

        return view('frontend.checkout.index', compact('cartItems', 'subtotal', 'shippingFee', 'total', 'user'));
    }

    /**
     * Xử lý đặt hàng
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|in:cod,bank,vnpay'
        ], [
            'customer_name.required' => 'Vui lòng nhập họ tên',
            'customer_email.required' => 'Vui lòng nhập email',
            'customer_email.email' => 'Email không hợp lệ',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại',
            'shipping_address.required' => 'Vui lòng nhập địa chỉ giao hàng',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán'
        ]);

        $cart = $this->getCart();
        
        if (!$cart || $cart->items()->count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống');
        }

        $cartItems = $cart->items()->with('product')->get();

        // Kiểm tra tồn kho
        foreach ($cartItems as $item) {
            $product = $item->product;
            if ($product->stock_quantity !== null && $product->stock_quantity < $item->quantity) {
                return redirect()->back()->with('error', "Sản phẩm '{$product->name}' chỉ còn {$product->stock_quantity} trong kho");
            }
        }

        DB::beginTransaction();
        try {
            $subtotal = $cartItems->sum('total');
            $shippingFee = 30000;
            $discount = 0;
            $total = $subtotal + $shippingFee - $discount;

            // Tạo đơn hàng
            $order = Order::create([
                'order_number' => 'DH' . date('Ymd') . strtoupper(Str::random(6)),
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'unpaid',
                'status' => 'pending'
            ]);

            // Tạo chi tiết đơn hàng
            foreach ($cartItems as $item) {
                $product = $item->product;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_image' => $product->image,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total' => $item->total
                ]);

                // Trừ tồn kho
                if ($product->stock_quantity !== null) {
                    $product->decrement('stock_quantity', $item->quantity);
                }
            }

            // Xóa giỏ hàng
            $cart->items()->delete();
            $cart->update(['total' => 0]);

            DB::commit();

            // Nếu thanh toán VNPay, chuyển hướng đến VNPay
            if ($request->payment_method === 'vnpay') {
                return $this->createVnpayPayment($order);
            }

            // Gửi email thông báo cho COD hoặc chuyển khoản
            $this->sendOrderEmails($order);

            return redirect()->route('checkout.success', $order->order_number)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại. ' . $e->getMessage());
        }
    }

    /**
     * Tạo link thanh toán VNPay
     */
    private function createVnpayPayment($order)
    {
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_ReturnUrl = route('checkout.vnpay.return'); // Dùng route() để đảm bảo URL đúng

        $vnp_TxnRef = $order->order_number;
        $vnp_OrderInfo = 'Thanh toan don hang ' . $order->order_number; // Bỏ dấu tiếng Việt để tránh lỗi encoding
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = intval($order->total) * 100; // VNPay yêu cầu số tiền x 100, phải là integer
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = request()->ip();
        
        // Fix IP nếu là local
        if ($vnp_IpAddr == '::1') {
            $vnp_IpAddr = '127.0.0.1';
        }

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (strlen($vnp_BankCode) > 0) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect($vnp_Url);
    }

    /**
     * Xử lý callback từ VNPay
     */
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $inputData = array();
        
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        
        $vnpTranId = $request->vnp_TransactionNo;
        $vnpAmount = $request->vnp_Amount / 100;
        $vnpResponseCode = $request->vnp_ResponseCode;
        $orderNumber = $request->vnp_TxnRef;

        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng');
        }

        if ($secureHash == $vnp_SecureHash) {
            if ($vnpResponseCode == '00') {
                // Thanh toán thành công
                $order->update([
                    'payment_status' => 'paid',
                    'vnpay_transaction_id' => $vnpTranId,
                    'paid_at' => now()
                ]);

                // Gửi email xác nhận
                $this->sendOrderEmails($order);

                return redirect()->route('checkout.success', $orderNumber)
                    ->with('success', 'Thanh toán thành công!');
            } else {
                // Thanh toán thất bại
                return redirect()->route('checkout.success', $orderNumber)
                    ->with('error', 'Thanh toán thất bại. Mã lỗi: ' . $vnpResponseCode);
            }
        } else {
            return redirect()->route('home')->with('error', 'Chữ ký không hợp lệ');
        }
    }

    /**
     * Trang đặt hàng thành công
     */
    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        
        // Kiểm tra quyền xem
        if ($order->user_id && auth()->id() != $order->user_id) {
            abort(403);
        }

        return view('frontend.checkout.success', compact('order'));
    }

    /**
     * Gửi email thông báo đơn hàng
     */
    private function sendOrderEmails($order)
    {
        try {
            // Gửi email xác nhận cho khách hàng
            Mail::to($order->customer_email)->send(new OrderConfirmation($order));

            // Gửi email thông báo cho admin
            $adminEmail = config('mail.admin_email', 'duongdinhcuongviajsc@gmail.com');
            Mail::to($adminEmail)->send(new NewOrderNotification($order));

        } catch (\Exception $e) {
            // Log lỗi gửi email nhưng không ảnh hưởng đến đơn hàng
            \Log::error('Lỗi gửi email đơn hàng: ' . $e->getMessage());
        }
    }

    /**
     * Lấy giỏ hàng hiện tại
     */
    private function getCart()
    {
        if (auth()->check()) {
            return Cart::where('user_id', auth()->id())->first();
        } else {
            $sessionId = session()->get('cart_session_id');
            if ($sessionId) {
                return Cart::where('session_id', $sessionId)->first();
            }
        }
        return null;
    }
}
