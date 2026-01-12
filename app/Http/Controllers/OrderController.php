<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Lịch sử đơn hàng
     */
    public function index(Request $request)
    {
        $query = Order::where('user_id', auth()->id())
            ->with('items');

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        return view('frontend.orders.index', compact('orders'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with('items')
            ->firstOrFail();

        return view('frontend.orders.show', compact('order'));
    }

    /**
     * Hủy đơn hàng
     */
    public function cancel($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Chỉ cho hủy đơn hàng ở trạng thái pending hoặc confirmed
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng ở trạng thái này');
        }

        $order->update([
            'status' => 'cancelled'
        ]);

        // Hoàn lại tồn kho
        foreach ($order->items as $item) {
            if ($item->product && $item->product->stock_quantity !== null) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
        }

        return redirect()->back()->with('success', 'Đã hủy đơn hàng thành công');
    }
}
