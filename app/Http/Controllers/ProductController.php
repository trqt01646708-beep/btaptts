<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Danh sách sản phẩm
     */
    public function index(Request $request)
    {
        $query = Product::where('status', 'published');

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Lọc theo danh mục
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Lọc theo giá
        if ($request->has('min_price') && $request->min_price) {
            $query->where(function($q) use ($request) {
                $q->where('sale_price', '>=', $request->min_price)
                  ->orWhere(function($q2) use ($request) {
                      $q2->whereNull('sale_price')
                         ->where('regular_price', '>=', $request->min_price);
                  });
            });
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where(function($q) use ($request) {
                $q->where('sale_price', '<=', $request->max_price)
                  ->orWhere(function($q2) use ($request) {
                      $q2->whereNull('sale_price')
                         ->where('regular_price', '<=', $request->max_price);
                  });
            });
        }

        // Sắp xếp
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderByRaw('IFNULL(sale_price, regular_price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('IFNULL(sale_price, regular_price) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->appends($request->query());

        // Lấy danh mục
        $categories = Category::where('status', 'active')
            ->withCount(['products' => function($q) {
                $q->where('status', 'published');
            }])
            ->having('products_count', '>', 0)
            ->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Chi tiết sản phẩm
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Tăng lượt xem
        $product->increment('views');

        // Sản phẩm liên quan
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('status', 'published')
            ->where('category_id', $product->category_id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Sản phẩm mới nhất
        $latestProducts = Product::where('status', 'published')
            ->where('id', '!=', $product->id)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts', 'latestProducts'));
    }

    /**
     * Sản phẩm theo danh mục
     */
    public function category($slug, Request $request)
    {
        $category = Category::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $query = Product::where('status', 'published')
            ->where('category_id', $category->id);

        // Sắp xếp
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderByRaw('IFNULL(sale_price, regular_price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('IFNULL(sale_price, regular_price) DESC');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->appends($request->query());

        // Lấy danh mục
        $categories = Category::where('status', 'active')
            ->withCount(['products' => function($q) {
                $q->where('status', 'published');
            }])
            ->having('products_count', '>', 0)
            ->get();

        return view('products.category', compact('products', 'category', 'categories'));
    }

    /**
     * Sản phẩm giảm giá
     */
    public function sale(Request $request)
    {
        $query = Product::where('status', 'published')
            ->whereNotNull('sale_price')
            ->where('sale_price', '>', 0);

        // Sắp xếp
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('sale_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('sale_price', 'desc');
                break;
            case 'discount':
                $query->orderByRaw('((regular_price - sale_price) / regular_price * 100) DESC');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->appends($request->query());

        return view('products.sale', compact('products'));
    }
}
