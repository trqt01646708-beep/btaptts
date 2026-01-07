<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        $orderCount = Order::count();
        $productCount = Product::count();
        $userCount = User::count();
        $postCount = Post::count();

        return view('admin.dashboard', compact('orderCount', 'productCount', 'userCount', 'postCount'));
    }
}
