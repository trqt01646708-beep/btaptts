<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     */
    public function index(Request $request)
    {
        $query = Post::with('categories');

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
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        $posts = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'featured_image' => 'required|image|max:2048',
            'excerpt' => 'required',
            'content' => 'nullable',
            'status' => 'required|in:draft,published,archived',
            'categories' => 'nullable|array',
            'published_at' => 'nullable|date',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề bài viết.',
            'featured_image.required' => 'Vui lòng chọn ảnh đại diện.',
            'featured_image.image' => 'File phải là ảnh.',
            'excerpt.required' => 'Vui lòng nhập trích dẫn.',
        ]);

        // Upload featured image
        $imagePath = $request->file('featured_image')->store('posts', 'public');

        // Create slug
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $post = Post::create([
            'title' => $request->title,
            'slug' => $slug,
            'featured_image' => $imagePath,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'status' => $request->status,
            'published_at' => $request->published_at,
        ]);

        // Attach categories
        if ($request->has('categories')) {
            $post->categories()->attach($request->categories);
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Tạo bài viết thành công!');
    }

    /**
     * Show the form for editing the post.
     */
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        $categories = Category::all();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified post.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'featured_image' => 'nullable|image|max:2048',
            'excerpt' => 'required',
            'content' => 'nullable',
            'status' => 'required|in:draft,published,archived',
            'categories' => 'nullable|array',
            'published_at' => 'nullable|date',
        ]);

        // Handle featured image upload
        $imagePath = $post->featured_image;
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $imagePath = $request->file('featured_image')->store('posts', 'public');
        }

        // Update slug if title changed
        $slug = $post->slug;
        if ($request->title !== $post->title) {
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $count = 1;
            while (Post::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
        }

        $post->update([
            'title' => $request->title,
            'slug' => $slug,
            'featured_image' => $imagePath,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'status' => $request->status,
            'published_at' => $request->published_at,
        ]);

        // Sync categories
        $post->categories()->sync($request->categories ?? []);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Cập nhật bài viết thành công!');
    }

    /**
     * Remove the specified post.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        // Delete thumbnail
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        $post->categories()->detach();
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Xóa bài viết thành công!');
    }
}
