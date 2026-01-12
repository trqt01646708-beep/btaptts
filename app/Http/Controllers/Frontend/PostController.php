<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Danh sách bài viết
     */
    public function index(Request $request)
    {
        $query = Post::with('categories')
            ->where('status', 'published')
            ->where('published_at', '<=', now());

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
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.slug', $request->category);
            });
        }

        $posts = $query->orderBy('published_at', 'desc')
            ->paginate(12)
            ->appends($request->query());

        // Lấy danh mục
        $categories = Category::where('status', 'active')
            ->withCount(['posts' => function($q) {
                $q->where('status', 'published')
                  ->where('published_at', '<=', now());
            }])
            ->having('posts_count', '>', 0)
            ->get();

        // Bài viết phổ biến (sắp xếp theo ngày đăng gần nhất)
        $popularPosts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        return view('frontend.posts.index', compact('posts', 'categories', 'popularPosts'));
    }

    /**
     * Chi tiết bài viết
     */
    public function show($slug)
    {
        $post = Post::with('categories')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        // Bài viết liên quan
        $relatedPosts = Post::with('categories')
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->whereHas('categories', function($q) use ($post) {
                $categoryIds = $post->categories->pluck('id');
                $q->whereIn('categories.id', $categoryIds);
            })
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        // Bài viết mới nhất
        $latestPosts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        return view('frontend.posts.show', compact('post', 'relatedPosts', 'latestPosts'));
    }

    /**
     * Danh sách bài viết theo danh mục
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $posts = Post::with('categories')
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->whereHas('categories', function($q) use ($category) {
                $q->where('categories.id', $category->id);
            })
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Lấy danh mục
        $categories = Category::where('status', 'active')
            ->withCount(['posts' => function($q) {
                $q->where('status', 'published')
                  ->where('published_at', '<=', now());
            }])
            ->having('posts_count', '>', 0)
            ->get();

        return view('frontend.posts.category', compact('posts', 'category', 'categories'));
    }
}
