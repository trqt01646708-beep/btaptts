<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $posts = \App\Models\Post::where('status', 'active')->latest()->take(3)->get();
        $products = \App\Models\Product::where('status', 'active')->latest()->take(4)->get();
        return view('home', compact('posts', 'products'));
    }
}
