<?php

$dir = __DIR__ . '/database/migrations/';
$files = scandir($dir);

// Xóa các file trùng lặp trước
$toDelete = [
    '2026_01_07_090849_create_users_table.php',
    'create_posts_table.php'
];

foreach ($toDelete as $file) {
    if (file_exists($dir . $file)) {
        unlink($dir . $file);
        echo "Deleted: $file\n";
    }
}

// Danh sách đổi tên theo thứ tự
$renames = [
    '2026_01_07_090100_create_users_table.php' => 'create_users_table.php',
    '2026_01_07_090101_create_sessions_table.php' => 'create_sessions_table.php',
    '2026_01_07_090105_create_posts_table.php' => 'create_posts_table.php',
    '2026_01_07_090106_create_products_table.php' => 'create_products_table.php',
    '2026_01_07_090107_create_roles_table.php' => 'create_roles_table.php',
    '2026_01_07_090108_create_permissions_table.php' => 'create_permissions_table.php',
    '2026_01_07_090109_create_role_user_table.php' => 'create_role_user_table.php',
    '2026_01_07_090110_create_orders_table.php' => 'create_orders_table.php',
    '2026_01_07_090110_create_permission_role_table.php' => 'create_permission_role_table.php',
    '2026_01_07_090111_create_cart_table.php' => 'create_cart_table.php',
    '2026_01_07_090112_create_order_items_table.php' => 'create_order_items_table.php',
    '2026_01_07_090113_create_cart_items_table.php' => 'create_cart_items_table.php',
    '2026_01_07_090114_create_categories_table.php' => 'create_categories_table.php',
    '2026_01_07_090115_create_category_post_table.php' => 'create_category_post_table.php',
];

foreach ($renames as $old => $new) {
    if (file_exists($dir . $old)) {
        rename($dir . $old, $dir . $new);
        echo "Renamed: $old -> $new\n";
    }
}

echo "\nAll files renamed successfully!\n";
