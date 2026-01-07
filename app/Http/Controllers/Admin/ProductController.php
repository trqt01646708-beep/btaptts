<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'thumbnail' => 'nullable|image|max:1024',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'content' => 'required',
        ]);

        $product = new Product($request->all());
        $product->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $imageName = 'img_'.time().'.'.$request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $product->image = 'images/products/' . $imageName;
        }

        if ($request->hasFile('thumbnail')) {
            $thumbName = 'thumb_'.time().'.'.$request->thumbnail->extension();
            $request->thumbnail->move(public_path('images/products'), $thumbName);
            $product->thumbnail = 'images/products/' . $thumbName;
        }

        $product->published_at = now();
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được tạo thành công.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'thumbnail' => 'nullable|image|max:1024',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:regular_price',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'content' => 'required',
        ]);

        $product->fill($request->all());
        $product->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $imageName = 'img_'.time().'.'.$request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $product->image = 'images/products/' . $imageName;
        }

        if ($request->hasFile('thumbnail')) {
            $thumbName = 'thumb_'.time().'.'.$request->thumbnail->extension();
            $request->thumbnail->move(public_path('images/products'), $thumbName);
            $product->thumbnail = 'images/products/' . $thumbName;
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
    }
}
