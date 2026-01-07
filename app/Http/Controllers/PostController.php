<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = \App\Models\Post::where('status', 'active')->latest()->paginate(9);
        return view('posts.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = \App\Models\Post::where('slug', $slug)->where('status', 'active')->firstOrFail();
        return view('posts.show', compact('post'));
    }
}
