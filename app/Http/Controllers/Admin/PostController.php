<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = \App\Models\Post::latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $post = new \App\Models\Post($request->all());
        $post->slug = \Illuminate\Support\Str::slug($request->name);

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/posts'), $imageName);
            $post->image = 'images/posts/' . $imageName;
        }

        $post->published_at = now();
        $post->save();

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(\App\Models\Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, \App\Models\Post $post)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $post->fill($request->all());
        $post->slug = \Illuminate\Support\Str::slug($request->name);

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/posts'), $imageName);
            $post->image = 'images/posts/' . $imageName;
        }

        $post->save();

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(\App\Models\Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }
}
