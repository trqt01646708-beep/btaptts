<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $dbCart = CartItem::where('user_id', Auth::id())->with('product')->get();
            $cart = [];
            foreach ($dbCart as $item) {
                $cart[$item->product_id] = [
                    "name" => $item->product->name,
                    "quantity" => $item->quantity,
                    "price" => $item->product->sale_price ?? $item->product->regular_price,
                    "image" => $item->product->image
                ];
            }
        } else {
            $cart = session()->get('cart', []);
        }
        
        return view('cart.index', compact('cart'));
    }

    public function add($id)
    {
        $product = Product::findOrFail($id);
        
        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())
                                ->where('product_id', $id)
                                ->first();
            
            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $id,
                    'quantity' => 1
                ]);
            }
        } else {
            $cart = session()->get('cart', []);

            if(isset($cart[$id])) {
                $cart[$id]['quantity']++;
            } else {
                $cart[$id] = [
                    "name" => $product->name,
                    "quantity" => 1,
                    "price" => $product->sale_price ?? $product->regular_price,
                    "image" => $product->image
                ];
            }

            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
    }

    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            if (Auth::check()) {
                CartItem::where('user_id', Auth::id())
                        ->where('product_id', $request->id)
                        ->update(['quantity' => $request->quantity]);
            } else {
                $cart = session()->get('cart');
                if (isset($cart[$request->id])) {
                    $cart[$request->id]["quantity"] = $request->quantity;
                    session()->put('cart', $cart);
                }
            }
            session()->flash('success', 'Giỏ hàng đã được cập nhật');
        }
    }

    public function remove($id)
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())
                    ->where('product_id', $id)
                    ->delete();
        } else {
            $cart = session()->get('cart');
            if(isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
        }
        return redirect()->back()->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng');
    }
}
