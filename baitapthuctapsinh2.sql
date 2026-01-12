-- Database Export for MySQL
-- Database: baitapthuctapsinh2
-- Exported on: 2026-01-07
-- 
-- Hướng dẫn import:
-- 1. Mở phpMyAdmin
-- 2. Tạo database mới tên 'baitapthuctapsinh2'
-- 3. Click vào database đó
-- 4. Chọn tab "Import"
-- 5. Chọn file này và click "Go"

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Database structure
CREATE DATABASE IF NOT EXISTS `baitapthuctapsinh2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `baitapthuctapsinh2`;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(255) NULL,
    `address` TEXT NULL,
    `avatar` VARCHAR(255) NULL,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: password_reset_tokens
-- --------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: sessions
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: posts
-- --------------------------------------------------------
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `thumbnail` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `content` LONGTEXT NULL,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `published_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `posts_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: products
-- --------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `image` VARCHAR(255) NOT NULL,
    `thumbnail` VARCHAR(255) NULL,
    `regular_price` DECIMAL(12, 0) NOT NULL,
    `sale_price` DECIMAL(12, 0) NULL,
    `description` TEXT NOT NULL,
    `content` LONGTEXT NULL,
    `stock_quantity` INT UNSIGNED NOT NULL DEFAULT 0,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `published_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `products_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: roles
-- --------------------------------------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `display_name` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: permissions
-- --------------------------------------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `display_name` VARCHAR(255) NULL,
    `group_name` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: role_user
-- --------------------------------------------------------
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `role_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `role_user_user_id_foreign` (`user_id`),
    KEY `role_user_role_id_foreign` (`role_id`),
    CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: permission_role
-- --------------------------------------------------------
DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE `permission_role` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `permission_id` BIGINT UNSIGNED NOT NULL,
    `role_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `permission_role_permission_id_foreign` (`permission_id`),
    KEY `permission_role_role_id_foreign` (`role_id`),
    CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
    CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: orders
-- --------------------------------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_number` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `subtotal` DECIMAL(12, 0) NOT NULL DEFAULT 0,
    `shipping_fee` DECIMAL(12, 0) NOT NULL DEFAULT 0,
    `discount` DECIMAL(12, 0) NOT NULL DEFAULT 0,
    `total` DECIMAL(12, 0) NOT NULL,
    `status` ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
    `payment_method` VARCHAR(50) NOT NULL DEFAULT 'cod',
    `payment_status` ENUM('unpaid', 'paid', 'refunded') NOT NULL DEFAULT 'unpaid',
    `customer_name` VARCHAR(255) NOT NULL,
    `customer_email` VARCHAR(255) NOT NULL,
    `customer_phone` VARCHAR(255) NOT NULL,
    `shipping_address` TEXT NOT NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `orders_order_number_unique` (`order_number`),
    KEY `orders_user_id_foreign` (`user_id`),
    CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: order_items
-- --------------------------------------------------------
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `product_image` VARCHAR(255) NULL,
    `quantity` INT NOT NULL,
    `price` DECIMAL(12, 0) NOT NULL,
    `total` DECIMAL(12, 0) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `order_items_order_id_foreign` (`order_id`),
    KEY `order_items_product_id_foreign` (`product_id`),
    CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
    CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: carts
-- --------------------------------------------------------
DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `session_id` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `carts_user_id_foreign` (`user_id`),
    KEY `carts_session_id_index` (`session_id`),
    CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: cart_items
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE `cart_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `cart_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `cart_items_cart_id_foreign` (`cart_id`),
    KEY `cart_items_product_id_foreign` (`product_id`),
    CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: categories
-- --------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `image` VARCHAR(255) NULL,
    `parent_id` BIGINT UNSIGNED NULL,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `categories_parent_id_foreign` (`parent_id`),
    CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: category_post (pivot)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `category_post`;
CREATE TABLE `category_post` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `post_id` BIGINT UNSIGNED NOT NULL,
    `category_id` BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `category_post_post_id_foreign` (`post_id`),
    KEY `category_post_category_id_foreign` (`category_id`),
    CONSTRAINT `category_post_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `category_post_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: category_product (pivot)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `category_product`;
CREATE TABLE `category_product` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `category_id` BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `category_product_product_id_foreign` (`product_id`),
    KEY `category_product_category_id_foreign` (`category_id`),
    CONSTRAINT `category_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
    CONSTRAINT `category_product_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: cache
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: cache_locks
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: jobs
-- --------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: job_batches
-- --------------------------------------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
    `id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` INT NOT NULL,
    `pending_jobs` INT NOT NULL,
    `failed_jobs` INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` MEDIUMTEXT NULL,
    `cancelled_at` INT NULL,
    `created_at` INT NOT NULL,
    `finished_at` INT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: failed_jobs
-- --------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(255) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Insert default data
-- --------------------------------------------------------

-- Insert Roles
INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Quản trị viên', 'Quyền truy cập đầy đủ hệ thống', NOW(), NOW()),
(2, 'user', 'Người dùng', 'Người dùng thông thường', NOW(), NOW());

-- Insert Permissions
INSERT INTO `permissions` (`id`, `name`, `display_name`, `group_name`, `description`, `created_at`, `updated_at`) VALUES
-- User permissions
(1, 'users.view', 'Xem danh sách người dùng', 'Quản lý người dùng', 'Xem danh sách tất cả người dùng', NOW(), NOW()),
(2, 'users.create', 'Thêm người dùng', 'Quản lý người dùng', 'Tạo người dùng mới', NOW(), NOW()),
(3, 'users.edit', 'Sửa người dùng', 'Quản lý người dùng', 'Chỉnh sửa thông tin người dùng', NOW(), NOW()),
(4, 'users.delete', 'Xóa người dùng', 'Quản lý người dùng', 'Xóa người dùng khỏi hệ thống', NOW(), NOW()),
-- Role permissions
(5, 'roles.view', 'Xem danh sách vai trò', 'Quản lý vai trò', 'Xem danh sách tất cả vai trò', NOW(), NOW()),
(6, 'roles.create', 'Thêm vai trò', 'Quản lý vai trò', 'Tạo vai trò mới', NOW(), NOW()),
(7, 'roles.edit', 'Sửa vai trò', 'Quản lý vai trò', 'Chỉnh sửa thông tin vai trò', NOW(), NOW()),
(8, 'roles.delete', 'Xóa vai trò', 'Quản lý vai trò', 'Xóa vai trò khỏi hệ thống', NOW(), NOW()),
-- Permission permissions
(9, 'permissions.view', 'Xem danh sách quyền', 'Quản lý quyền', 'Xem danh sách tất cả quyền', NOW(), NOW()),
(10, 'permissions.create', 'Thêm quyền', 'Quản lý quyền', 'Tạo quyền mới', NOW(), NOW()),
(11, 'permissions.edit', 'Sửa quyền', 'Quản lý quyền', 'Chỉnh sửa thông tin quyền', NOW(), NOW()),
(12, 'permissions.delete', 'Xóa quyền', 'Quản lý quyền', 'Xóa quyền khỏi hệ thống', NOW(), NOW()),
-- Post permissions
(13, 'posts.view', 'Xem danh sách bài viết', 'Quản lý bài viết', 'Xem danh sách tất cả bài viết', NOW(), NOW()),
(14, 'posts.create', 'Thêm bài viết', 'Quản lý bài viết', 'Tạo bài viết mới', NOW(), NOW()),
(15, 'posts.edit', 'Sửa bài viết', 'Quản lý bài viết', 'Chỉnh sửa nội dung bài viết', NOW(), NOW()),
(16, 'posts.delete', 'Xóa bài viết', 'Quản lý bài viết', 'Xóa bài viết khỏi hệ thống', NOW(), NOW()),
-- Product permissions
(17, 'products.view', 'Xem danh sách sản phẩm', 'Quản lý sản phẩm', 'Xem danh sách tất cả sản phẩm', NOW(), NOW()),
(18, 'products.create', 'Thêm sản phẩm', 'Quản lý sản phẩm', 'Tạo sản phẩm mới', NOW(), NOW()),
(19, 'products.edit', 'Sửa sản phẩm', 'Quản lý sản phẩm', 'Chỉnh sửa thông tin sản phẩm', NOW(), NOW()),
(20, 'products.delete', 'Xóa sản phẩm', 'Quản lý sản phẩm', 'Xóa sản phẩm khỏi hệ thống', NOW(), NOW()),
-- Category permissions
(21, 'categories.view', 'Xem danh sách danh mục', 'Quản lý danh mục', 'Xem danh sách tất cả danh mục', NOW(), NOW()),
(22, 'categories.create', 'Thêm danh mục', 'Quản lý danh mục', 'Tạo danh mục mới', NOW(), NOW()),
(23, 'categories.edit', 'Sửa danh mục', 'Quản lý danh mục', 'Chỉnh sửa thông tin danh mục', NOW(), NOW()),
(24, 'categories.delete', 'Xóa danh mục', 'Quản lý danh mục', 'Xóa danh mục khỏi hệ thống', NOW(), NOW()),
-- Order permissions
(25, 'orders.view', 'Xem danh sách đơn hàng', 'Quản lý đơn hàng', 'Xem danh sách tất cả đơn hàng', NOW(), NOW()),
(26, 'orders.create', 'Thêm đơn hàng', 'Quản lý đơn hàng', 'Tạo đơn hàng mới', NOW(), NOW()),
(27, 'orders.edit', 'Sửa đơn hàng', 'Quản lý đơn hàng', 'Chỉnh sửa thông tin đơn hàng', NOW(), NOW()),
(28, 'orders.delete', 'Xóa đơn hàng', 'Quản lý đơn hàng', 'Xóa đơn hàng khỏi hệ thống', NOW(), NOW());

-- Assign all permissions to admin role
INSERT INTO `permission_role` (`permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, NOW(), NOW()),
(2, 1, NOW(), NOW()),
(3, 1, NOW(), NOW()),
(4, 1, NOW(), NOW()),
(5, 1, NOW(), NOW()),
(6, 1, NOW(), NOW()),
(7, 1, NOW(), NOW()),
(8, 1, NOW(), NOW()),
(9, 1, NOW(), NOW()),
(10, 1, NOW(), NOW()),
(11, 1, NOW(), NOW()),
(12, 1, NOW(), NOW()),
(13, 1, NOW(), NOW()),
(14, 1, NOW(), NOW()),
(15, 1, NOW(), NOW()),
(16, 1, NOW(), NOW()),
(17, 1, NOW(), NOW()),
(18, 1, NOW(), NOW()),
(19, 1, NOW(), NOW()),
(20, 1, NOW(), NOW()),
(21, 1, NOW(), NOW()),
(22, 1, NOW(), NOW()),
(23, 1, NOW(), NOW()),
(24, 1, NOW(), NOW()),
(25, 1, NOW(), NOW()),
(26, 1, NOW(), NOW()),
(27, 1, NOW(), NOW()),
(28, 1, NOW(), NOW());

-- Insert Admin User (password: password)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `address`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@admin.com', NOW(), '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5NANh1yEn0IJ2', '0123456789', 'Hà Nội, Việt Nam', NULL, NOW(), NOW()),
(2, 'Người dùng Test', 'user@test.com', NOW(), '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5NANh1yEn0IJ2', '0987654321', 'TP.HCM, Việt Nam', NULL, NOW(), NOW());

-- Link Admin to Admin Role
INSERT INTO `role_user` (`user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, NOW(), NOW()),
(2, 2, NOW(), NOW());

-- Insert sample categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Tin tức', 'tin-tuc', 'Các tin tức mới nhất', 'active', NOW(), NOW()),
(2, 'Công nghệ', 'cong-nghe', 'Tin tức công nghệ', 'active', NOW(), NOW()),
(3, 'Điện thoại', 'dien-thoai', 'Sản phẩm điện thoại', 'active', NOW(), NOW()),
(4, 'Laptop', 'laptop', 'Sản phẩm laptop', 'active', NOW(), NOW()),
(5, 'Phụ kiện', 'phu-kien', 'Phụ kiện công nghệ', 'active', NOW(), NOW());

-- Insert sample posts
INSERT INTO `posts` (`id`, `name`, `slug`, `thumbnail`, `description`, `content`, `status`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Bài viết mẫu 1', 'bai-viet-mau-1', 'thumbnails/sample1.jpg', 'Đây là mô tả bài viết mẫu số 1', '<p>Nội dung chi tiết bài viết mẫu số 1</p>', 'active', NOW(), NOW(), NOW()),
(2, 'Bài viết mẫu 2', 'bai-viet-mau-2', 'thumbnails/sample2.jpg', 'Đây là mô tả bài viết mẫu số 2', '<p>Nội dung chi tiết bài viết mẫu số 2</p>', 'active', NOW(), NOW(), NOW());

-- Link posts to categories
INSERT INTO `category_post` (`post_id`, `category_id`) VALUES
(1, 1),
(1, 2),
(2, 1);

-- Insert sample products
INSERT INTO `products` (`id`, `name`, `slug`, `image`, `thumbnail`, `regular_price`, `sale_price`, `description`, `content`, `stock_quantity`, `status`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'iPhone 15 Pro Max', 'iphone-15-pro-max', 'products/iphone15.jpg', 'products/iphone15_thumb.jpg', 34990000, 32990000, 'iPhone 15 Pro Max - Điện thoại cao cấp từ Apple', '<p>iPhone 15 Pro Max với chip A17 Pro mạnh mẽ</p>', 50, 'active', NOW(), NOW(), NOW()),
(2, 'Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 'products/s24ultra.jpg', 'products/s24ultra_thumb.jpg', 31990000, 29990000, 'Samsung Galaxy S24 Ultra - Flagship Android', '<p>Samsung Galaxy S24 Ultra với S-Pen và camera 200MP</p>', 30, 'active', NOW(), NOW(), NOW()),
(3, 'MacBook Pro M3', 'macbook-pro-m3', 'products/macbookm3.jpg', 'products/macbookm3_thumb.jpg', 52990000, NULL, 'MacBook Pro M3 - Laptop chuyên nghiệp', '<p>MacBook Pro với chip M3 siêu nhanh</p>', 20, 'active', NOW(), NOW(), NOW());

-- Link products to categories
INSERT INTO `category_product` (`product_id`, `category_id`) VALUES
(1, 3),
(2, 3),
(3, 4);

COMMIT;
