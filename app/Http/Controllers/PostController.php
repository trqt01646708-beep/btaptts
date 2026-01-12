<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('categories')->latest()->get();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'thumbnail' => 'required|image',
            'description' => 'required',
            'content' => 'nullable',
            'categories' => 'required|array'
        ]);

        // Xử lý upload thumbnail
        $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');

        // Tạo slug từ name
        $slug = Str::slug($request->name);

        // Tạo post mới
        $post = Post::create([
            'name' => $request->name,
            'slug' => $slug,
            'thumbnail' => $thumbnailPath,
            'description' => $request->description,
            'content' => $request->content
        ]);

        // Gắn categories
        $post->categories()->attach($request->categories);

        return redirect()->route('posts.index')->with('success', 'Tạo bài viết thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'thumbnail' => 'nullable|image',
            'description' => 'required',
            'content' => 'nullable',
            'categories' => 'required|array'
        ]);

        $post = Post::findOrFail($id);

        // Xử lý upload thumbnail nếu có
        $thumbnailPath = $post->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // Tạo slug từ name
        $slug = Str::slug($request->name);

        // Cập nhật post
        $post->update([
            'name' => $request->name,
            'slug' => $slug,
            'thumbnail' => $thumbnailPath,
            'description' => $request->description,
            'content' => $request->content
        ]);

        // Cập nhật categories
        $post->categories()->sync($request->categories);

        return redirect()->route('posts.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Xóa bài viết thành công!');
    }
}
