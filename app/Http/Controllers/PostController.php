<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('categories')->get();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'thumbnail' => 'required|image',
            'description' => 'required'
        ]);

        $path = $request->file('thumbnail')->store('posts', 'public');

        $post = Post::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'thumbnail' => $path,
            'description' => $request->description,
            'content' => $request->content
        ]);

        $post->categories()->sync($request->categories ?? []);

        return redirect()->route('posts.index');
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('posts.edit', compact('post','categories'));
    }

    public function update(Request $request, Post $post)
    {
        $post->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'content' => $request->content
        ]);

        $post->categories()->sync($request->categories ?? []);
        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return back();
    }
}
