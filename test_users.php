<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DANH SÁCH USERS ===\n\n";

$users = DB::table('users')->select('id', 'name', 'email')->get();

if ($users->isEmpty()) {
    echo "Không có user nào trong database!\n";
    echo "Hãy chạy: php artisan db:seed\n";
} else {
    foreach ($users as $user) {
        echo "ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "------------------------\n";
    }
    
    echo "\n=== THÔNG TIN ĐĂNG NHẬP ===\n";
    echo "Admin:\n";
    echo "  Email: admin@admin.com\n";
    echo "  Password: password\n\n";
    echo "User:\n";
    echo "  Email: user@example.com\n";
    echo "  Password: password\n";
}
