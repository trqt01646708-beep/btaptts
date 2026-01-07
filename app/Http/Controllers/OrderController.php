<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;

class OrderController extends Controller
{
    public function history()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->paginate(10);
        return view('orders.history', compact('orders'));
    }

    public function show($id)
    {
        $query = Order::query();
        // If not admin, only show own orders
        if (!Auth::user()->hasRole('admin')) {
            $query->where('user_id', Auth::id());
        }
        
        $order = $query->with('orderItems.product')->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function checkout()
    {
        // For authenticated users, we get cart from DB
        $dbCart = CartItem::where('user_id', Auth::id())->with('product')->get();
        
        if($dbCart->isEmpty()) {
            return redirect()->route('home')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $cart = [];
        foreach ($dbCart as $item) {
            $cart[$item->product_id] = [
                "name" => $item->product->name,
                "quantity" => $item->quantity,
                "price" => $item->product->sale_price ?? $item->product->regular_price,
                "image" => $item->product->image
            ];
        }

        return view('orders.checkout', compact('cart'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        $dbCart = CartItem::where('user_id', Auth::id())->with('product')->get();
        
        if($dbCart->isEmpty()) {
            return redirect()->route('home');
        }

        $total = 0;
        foreach($dbCart as $item) {
            $price = $item->product->sale_price ?? $item->product->regular_price;
            $total += $price * $item->quantity;
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $request->name,
            'customer_email' => $request->email,
            'customer_phone' => $request->phone,
            'customer_address' => $request->address,
            'total_amount' => $total,
            'status' => 'pending'
        ]);

        foreach($dbCart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->product->sale_price ?? $item->product->regular_price
            ]);
            
            // Optional: Reduce product quantity
            $item->product->decrement('quantity', $item->quantity);
        }

        // Clear DB cart
        CartItem::where('user_id', Auth::id())->delete();

        // Send Email
        try {
            // To Customer (Email from form)
            Mail::to($request->email)->send(new OrderPlaced($order));
            
            // To specific recipient requested
            Mail::to('jjjooo2747x@gmail.com')->send(new OrderPlaced($order));
            
            // To site admin
            $admin = User::whereHas('roles', function($q){ $q->where('name', 'admin'); })->first();
            if ($admin) {
                Mail::to($admin->email)->send(new OrderPlaced($order));
            }

        } catch (\Exception $e) {
            // Log error if needed: \Log::error("Mail failed: " . $e->getMessage());
        }

        return redirect()->route('orders.history')->with('success', 'Đơn hàng của bạn đã được đặt thành công!');
    }
}
