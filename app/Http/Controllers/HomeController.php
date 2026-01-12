<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Trang chủ
     */
    public function index()
    {
        // Lấy bài viết mới nhất
        $latestPosts = Post::with('categories')
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();

        // Lấy sản phẩm nổi bật (có giảm giá)
        $featuredProducts = Product::where('status', 'published')
            ->whereNotNull('sale_price')
            ->where('sale_price', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Lấy sản phẩm mới nhất
        $latestProducts = Product::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Lấy danh mục chính
        $categories = Category::where('status', 'active')
            ->whereNull('parent_id')
            ->withCount(['posts', 'products'])
            ->get();

        return view('home', compact('latestPosts', 'featuredProducts', 'latestProducts', 'categories'));
    }

    /**
     * Trang giới thiệu
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Trang liên hệ
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Xử lý form liên hệ
     */
    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ], [
            'name.required' => 'Vui lòng nhập họ tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'subject.required' => 'Vui lòng nhập tiêu đề',
            'message.required' => 'Vui lòng nhập nội dung'
        ]);

        // TODO: Gửi email liên hệ

        return redirect()->back()->with('success', 'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất có thể!');
    }
}
