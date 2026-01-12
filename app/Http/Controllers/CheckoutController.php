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
            'payment_method' => 'required|in:cod,bank'
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

            // Gửi email thông báo
            $this->sendOrderEmails($order);

            return redirect()->route('checkout.success', $order->order_number)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại. ' . $e->getMessage());
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
