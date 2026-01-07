<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::where('status', 'active')->latest()->paginate(12);
        return view('products.index', compact('products'));
    }

    public function show($slug)
    {
        $product = \App\Models\Product::where('slug', $slug)->where('status', 'active')->firstOrFail();
        return view('products.show', compact('product'));
    }
}
