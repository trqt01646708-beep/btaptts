<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = DB::table('roles')->insertGetId([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrator role with full access',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userRole = DB::table('roles')->insertGetId([
            'name' => 'User',
            'slug' => 'user',
            'description' => 'Regular user role',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create Permissions
        $permissions = [
            ['name' => 'View Dashboard', 'slug' => 'view-dashboard'],
            ['name' => 'Manage Users', 'slug' => 'manage-users'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles'],
            ['name' => 'Manage Permissions', 'slug' => 'manage-permissions'],
            ['name' => 'Manage Posts', 'slug' => 'manage-posts'],
            ['name' => 'Manage Products', 'slug' => 'manage-products'],
            ['name' => 'Manage Orders', 'slug' => 'manage-orders'],
            ['name' => 'Manage Categories', 'slug' => 'manage-categories'],
        ];

        $permissionIds = [];
        foreach ($permissions as $permission) {
            $permissionIds[] = DB::table('permissions')->insertGetId([
                'name' => $permission['name'],
                'slug' => $permission['slug'],
                'description' => 'Permission to ' . $permission['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Assign all permissions to Admin role
        foreach ($permissionIds as $permissionId) {
            DB::table('permission_role')->insert([
                'permission_id' => $permissionId,
                'role_id' => $adminRole,
            ]);
        }

        // Create Admin User
        $adminUser = DB::table('users')->insertGetId([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign admin role to admin user
        DB::table('role_user')->insert([
            'role_id' => $adminRole,
            'user_id' => $adminUser,
        ]);

        // Create Regular User
        $regularUser = DB::table('users')->insertGetId([
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign user role
        DB::table('role_user')->insert([
            'role_id' => $userRole,
            'user_id' => $regularUser,
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics', 'status' => 'active'],
            ['name' => 'Fashion', 'slug' => 'fashion', 'status' => 'active'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden', 'status' => 'active'],
            ['name' => 'Sports', 'slug' => 'sports', 'status' => 'active'],
            ['name' => 'News', 'slug' => 'news', 'status' => 'active'],
            ['name' => 'Technology', 'slug' => 'technology', 'status' => 'active'],
        ];

        $categoryIds = [];
        foreach ($categories as $category) {
            $categoryIds[] = DB::table('categories')->insertGetId([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => 'Category for ' . $category['name'],
                'status' => $category['status'],
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create Products
        $products = [
            [
                'category_id' => $categoryIds[0],
                'name' => 'Laptop Dell XPS 15',
                'slug' => 'laptop-dell-xps-15',
                'image' => 'https://via.placeholder.com/800x600?text=Laptop+Dell+XPS+15',
                'regular_price' => 1500.00,
                'sale_price' => 1299.00,
                'description' => 'High-performance laptop with stunning display',
                'status' => 'active',
            ],
            [
                'category_id' => $categoryIds[0],
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'image' => 'https://via.placeholder.com/800x600?text=iPhone+15+Pro',
                'regular_price' => 1199.00,
                'sale_price' => null,
                'description' => 'Latest iPhone with advanced features',
                'status' => 'active',
            ],
            [
                'category_id' => $categoryIds[1],
                'name' => 'Nike Air Max 2024',
                'slug' => 'nike-air-max-2024',
                'image' => 'https://via.placeholder.com/800x600?text=Nike+Air+Max',
                'regular_price' => 180.00,
                'sale_price' => 149.99,
                'description' => 'Comfortable running shoes',
                'status' => 'active',
            ],
            [
                'category_id' => $categoryIds[1],
                'name' => 'Levi\'s Jeans 501',
                'slug' => 'levis-jeans-501',
                'image' => 'https://via.placeholder.com/800x600?text=Levis+Jeans',
                'regular_price' => 89.00,
                'sale_price' => 69.99,
                'description' => 'Classic blue jeans',
                'status' => 'active',
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->insert([
                'category_id' => $product['category_id'],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'image' => $product['image'],
                'regular_price' => $product['regular_price'],
                'sale_price' => $product['sale_price'],
                'description' => $product['description'],
                'content' => '<p>' . $product['description'] . '</p>',
                'status' => $product['status'],
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create Posts
        $posts = [
            [
                'title' => 'Welcome to Our Blog',
                'slug' => 'welcome-to-our-blog',
                'content' => '<p>This is our first blog post. Stay tuned for more updates!</p>',
                'status' => 'published',
                'category_id' => $categoryIds[4],
            ],
            [
                'title' => 'Latest Technology Trends 2024',
                'slug' => 'latest-technology-trends-2024',
                'content' => '<p>Discover the latest technology trends shaping the future.</p>',
                'status' => 'published',
                'category_id' => $categoryIds[5],
            ],
        ];

        foreach ($posts as $post) {
            $postId = DB::table('posts')->insertGetId([
                'title' => $post['title'],
                'slug' => $post['slug'],
                'content' => $post['content'],
                'excerpt' => substr(strip_tags($post['content']), 0, 100),
                'featured_image' => 'https://via.placeholder.com/800x400?text=' . urlencode($post['title']),
                'status' => $post['status'],
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Attach category to post
            DB::table('category_post')->insert([
                'category_id' => $post['category_id'],
                'post_id' => $postId,
            ]);
        }
    }
}
