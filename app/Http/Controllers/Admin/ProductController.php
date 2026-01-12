<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('regular_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('regular_price', '<=', $request->max_price);
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'image' => 'required|image|max:2048',
            'thumbnail' => 'nullable|image|max:2048',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:regular_price',
            'description' => 'required',
            'content' => 'nullable',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'category_id' => 'nullable|exists:categories,id',
            'published_at' => 'nullable|date',
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'image.required' => 'Vui lòng chọn ảnh sản phẩm.',
            'image.image' => 'File phải là ảnh.',
            'regular_price.required' => 'Vui lòng nhập giá sản phẩm.',
            'sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
            'description.required' => 'Vui lòng nhập mô tả.',
            'stock_quantity.required' => 'Vui lòng nhập số lượng tồn kho.',
        ]);

        // Upload image
        $imagePath = $request->file('image')->store('products', 'public');
        
        // Upload thumbnail
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        // Create slug
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'image' => $imagePath,
            'thumbnail' => $thumbnailPath,
            'regular_price' => $request->regular_price,
            'sale_price' => $request->sale_price,
            'description' => $request->description,
            'content' => $request->content,
            'stock_quantity' => $request->stock_quantity,
            'status' => $request->status,
            'published_at' => $request->published_at,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Tạo sản phẩm thành công!');
    }

    /**
     * Show the form for editing the product.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'image' => 'nullable|image|max:2048',
            'thumbnail' => 'nullable|image|max:2048',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'description' => 'required',
            'content' => 'nullable',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'category_id' => 'nullable|exists:categories,id',
            'published_at' => 'nullable|date',
        ]);

        // Handle image upload
        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Handle thumbnail upload
        $thumbnailPath = $product->thumbnail;
        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        // Update slug if name changed
        $slug = $product->slug;
        if ($request->name !== $product->name) {
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $count = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
        }

        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'image' => $imagePath,
            'thumbnail' => $thumbnailPath,
            'regular_price' => $request->regular_price,
            'sale_price' => $request->sale_price,
            'description' => $request->description,
            'content' => $request->content,
            'stock_quantity' => $request->stock_quantity,
            'status' => $request->status,
            'published_at' => $request->published_at,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Delete images
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công!');
    }
}
