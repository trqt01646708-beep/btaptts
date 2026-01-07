<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Post;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Posts
        Post::create([
            'name' => 'Chào mừng đến với cửa hàng của chúng tôi',
            'slug' => 'chao-mung-den-voi-cua-hang',
            'description' => 'Đây là bài viết đầu tiên trên hệ thống.',
            'content' => 'Nội dung chi tiết bài viết chào mừng...',
            'status' => 'active',
            'published_at' => now(),
        ]);

        Post::create([
            'name' => 'Xu hướng thời trang 2026',
            'slug' => 'xu-huong-thoi-trang-2026',
            'description' => 'Cập nhật những mẫu mới nhất.',
            'content' => 'Chi tiết các mẫu thời trang mới nhất...',
            'status' => 'active',
            'published_at' => now(),
        ]);

        // Products
        Product::create([
            'name' => 'Sản phẩm mẫu 1',
            'slug' => 'san-pham-mau-1',
            'regular_price' => 200000,
            'sale_price' => 150000,
            'quantity' => 50,
            'description' => 'Mô tả sản phẩm mẫu 1',
            'content' => 'Nội dung chi tiết sản phẩm 1',
            'status' => 'active',
        ]);

        Product::create([
            'name' => 'Sản phẩm mẫu 2',
            'slug' => 'san-pham-mau-2',
            'regular_price' => 500000,
            'sale_price' => 450000,
            'quantity' => 20,
            'description' => 'Mô tả sản phẩm mẫu 2',
            'content' => 'Nội dung chi tiết sản phẩm 2',
            'status' => 'active',
        ]);
    }
}
