<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = $cart ? $cart->items()->with('product')->get() : collect();
        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->price;
        });

        return view('frontend.cart.index', compact('cartItems', 'total'));
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function add(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Luôn trả về JSON nếu là request JSON hoặc có X-Requested-With header
            if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        try {
            $product = Product::findOrFail($request->product_id);
            
            // Kiểm tra tồn kho
            if ($product->stock_quantity !== null && $product->stock_quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng sản phẩm trong kho không đủ'
                ], 400);
            }

            $cart = $this->getOrCreateCart();
            
            // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
            $cartItem = $cart->items()->where('product_id', $product->id)->first();
            
            $price = $product->sale_price ?: $product->regular_price;
            
            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $request->quantity;
                
                // Kiểm tra tồn kho
                if ($product->stock_quantity !== null && $product->stock_quantity < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Số lượng sản phẩm trong kho không đủ'
                    ], 400);
                }
                
                $cartItem->update([
                    'quantity' => $newQuantity,
                    'price' => $price,
                    'total' => $newQuantity * $price
                ]);
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'price' => $price,
                    'total' => $request->quantity * $price
                ]);
            }

            // Cập nhật tổng giỏ hàng
            $this->updateCartTotal($cart);

            $cartCount = $cart->items()->sum('quantity');

            // Luôn trả về JSON nếu có X-Requested-With header
            if ($request->header('X-Requested-With') === 'XMLHttpRequest' || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã thêm sản phẩm vào giỏ hàng',
                    'cart_count' => $cartCount
                ]);
            }

            return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng');
        } catch (\Exception $e) {
            // Luôn trả về JSON nếu là request JSON hoặc có X-Requested-With header
            if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật số lượng
     */
    public function update(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getCart();
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng không tồn tại'
            ], 400);
        }

        $cartItem = $cart->items()->find($request->item_id);
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không có trong giỏ hàng'
            ], 400);
        }

        // Kiểm tra tồn kho
        $product = $cartItem->product;
        if ($product->stock_quantity !== null && $product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng sản phẩm trong kho không đủ'
            ], 400);
        }

        $cartItem->update([
            'quantity' => $request->quantity,
            'total' => $request->quantity * $cartItem->price
        ]);

        $this->updateCartTotal($cart);

        $itemTotal = $cartItem->total;
        $cartTotal = $cart->fresh()->total;
        $cartCount = $cart->items()->sum('quantity');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật giỏ hàng',
                'item_total' => number_format($itemTotal) . 'đ',
                'cart_total' => number_format($cartTotal) . 'đ',
                'cart_count' => $cartCount
            ]);
        }

        return redirect()->back()->with('success', 'Đã cập nhật giỏ hàng');
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function remove(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id'
        ]);

        $cart = $this->getCart();
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng không tồn tại'
            ], 400);
        }

        $cartItem = $cart->items()->find($request->item_id);
        if ($cartItem) {
            $cartItem->delete();
            $this->updateCartTotal($cart);
        }

        $cartTotal = $cart->fresh()->total;
        $cartCount = $cart->items()->sum('quantity');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
                'cart_total' => number_format($cartTotal) . 'đ',
                'cart_count' => $cartCount
            ]);
        }

        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng');
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        $cart = $this->getCart();
        if ($cart) {
            $cart->items()->delete();
            $cart->update(['total' => 0]);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa toàn bộ giỏ hàng'
            ]);
        }

        return redirect()->back()->with('success', 'Đã xóa toàn bộ giỏ hàng');
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

    /**
     * Lấy hoặc tạo giỏ hàng
     */
    private function getOrCreateCart()
    {
        $cart = $this->getCart();
        
        if (!$cart) {
            if (auth()->check()) {
                $cart = Cart::create([
                    'user_id' => auth()->id(),
                    'total' => 0
                ]);
            } else {
                $sessionId = Str::uuid()->toString();
                session()->put('cart_session_id', $sessionId);
                $cart = Cart::create([
                    'session_id' => $sessionId,
                    'total' => 0
                ]);
            }
        }

        return $cart;
    }

    /**
     * Cập nhật tổng giỏ hàng
     */
    private function updateCartTotal($cart)
    {
        $total = $cart->items()->sum('total');
        $cart->update(['total' => $total]);
    }

    /**
     * Lấy số lượng trong giỏ hàng (API)
     */
    public function getCount()
    {
        $cart = $this->getCart();
        $count = $cart ? $cart->items()->sum('quantity') : 0;

        return response()->json([
            'count' => $count
        ]);
    }
}
