# 📦 Upload File Nâng Cao + Optimize Image

## Mục tiêu đã hoàn thành ✅

-   ✅ Làm quen với Laravel Storage
-   ✅ Tạo image thumbnail 300x300 bằng PHP GD Library
-   ✅ Hiểu public disk / s3 disk
-   ✅ Tạo form upload multiple images
-   ✅ Lưu vào `/storage/app/public/uploads`
-   ✅ Tạo thumbnail 300x300
-   ✅ Tạo auto slug filename unique
-   ✅ Xóa hình khi xóa bài

## Cấu trúc dự án

```
app/
├── Http/Controllers/
│   └── ProductController.php      # Controller xử lý upload & CRUD
├── Models/
│   └── Product.php                # Model với auto-delete images
database/
├── migrations/
│   └── 2026_01_13_000001_create_products_table.php
resources/
└── views/
    └── product/
        ├── index.blade.php        # Danh sách sản phẩm
        ├── create.blade.php       # Form tạo sản phẩm
        ├── edit.blade.php         # Form sửa sản phẩm
        └── show.blade.php         # Chi tiết sản phẩm
routes/
└── web.php                        # Routes cho CRUD
storage/
└── app/
    └── public/
        └── uploads/               # Thư mục lưu ảnh
            └── thumbnails/        # Thư mục lưu thumbnail
```

## Chi tiết tính năng

### 1. Upload Multiple Images 📸

Form upload hỗ trợ chọn nhiều ảnh cùng lúc với preview trước khi upload.

**File:** [create.blade.php](resources/views/product/create.blade.php)

```html
<input type="file" name="images[]" accept="image/*" multiple />
```

### 2. Auto Slug Filename Unique 🔤

Tên file được tạo tự động từ title + timestamp để đảm bảo unique:

**File:** [ProductController.php](app/Http/Controllers/ProductController.php) - Line ~44

```php
$slug = Str::slug($request->title);
$timestamp = time();
$filename = $slug . '-' . $timestamp . '.' . $extension;
```

Ví dụ: `my-product-1736769600.jpg`

### 3. Tạo Thumbnail 300x300 🖼️

Sử dụng PHP GD Library để tạo thumbnail với kích thước 300x300px:

**File:** [ProductController.php](app/Http/Controllers/ProductController.php) - Line ~140

```php
private function createThumbnail($file, $slug, $timestamp, $extension)
{
    // Tạo thumbnail 300x300
    $thumbWidth = 300;
    $thumbHeight = 300;

    // Resize và center image
    // Hỗ trợ JPEG, PNG, GIF
    // Giữ nguyên transparency cho PNG/GIF
}
```

**Tính năng:**

-   Resize ảnh vừa khung 300x300
-   Center alignment
-   Giữ nguyên tỷ lệ khung hình
-   Bảo toàn transparency cho PNG/GIF
-   Nền trắng cho JPEG

### 4. Laravel Storage - Public Disk 💾

**Cấu hình:** [config/filesystems.php](config/filesystems.php)

```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

**Lưu ảnh:**

```php
$imagePath = $firstImage->storeAs('uploads', $filename, 'public');
// Lưu vào: storage/app/public/uploads/
```

**Truy cập ảnh:**

```blade
{{ asset('storage/' . $product->image) }}
// URL: http://localhost:8000/storage/uploads/my-product-123.jpg
```

### 5. Xóa ảnh khi xóa bài 🗑️

Sử dụng Model Events để tự động xóa ảnh khi xóa product:

**File:** [Product.php](app/Models/Product.php) - Line ~23

```php
protected static function boot()
{
    parent::boot();

    static::deleting(function ($product) {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }
    });
}
```

## Cách sử dụng 🚀

### 1. Khởi động server

```bash
php artisan serve
```

### 2. Truy cập ứng dụng

Mở trình duyệt: `http://localhost:8000`

### 3. Tạo sản phẩm mới

1. Click "Add New Product"
2. Nhập thông tin:
    - Title (bắt buộc)
    - Description
    - Price (bắt buộc)
3. Chọn ảnh (hỗ trợ multiple)
4. Click "Create Product"

### 4. Kết quả

-   Ảnh gốc được lưu tại: `storage/app/public/uploads/`
-   Thumbnail 300x300 tại: `storage/app/public/uploads/thumbnails/`
-   Tên file: `slug-timestamp.extension`

## Routes 🛣️

**File:** [web.php](routes/web.php)

| Method | URI                   | Action  | Route Name       |
| ------ | --------------------- | ------- | ---------------- |
| GET    | `/`                   | index   | products.index   |
| GET    | `/products`           | index   | products.index   |
| GET    | `/products/create`    | create  | products.create  |
| POST   | `/products`           | store   | products.store   |
| GET    | `/products/{id}`      | show    | products.show    |
| GET    | `/products/{id}/edit` | edit    | products.edit    |
| PUT    | `/products/{id}`      | update  | products.update  |
| DELETE | `/products/{id}`      | destroy | products.destroy |

## Database Schema 🗄️

**Table:** `products`

| Column      | Type          | Attributes                  |
| ----------- | ------------- | --------------------------- |
| id          | bigint        | primary key, auto increment |
| title       | varchar(255)  | not null                    |
| description | text          | nullable                    |
| price       | decimal(10,2) | default 0                   |
| image       | varchar(255)  | nullable                    |
| thumbnail   | varchar(255)  | nullable                    |
| created_at  | timestamp     |                             |
| updated_at  | timestamp     |                             |

## Tính năng bổ sung 🌟

### 1. Image Preview

Form upload có preview ảnh trước khi submit:

-   Hiển thị thumbnail của ảnh đã chọn
-   Hỗ trợ multiple preview
-   JavaScript real-time preview

### 2. Validation

-   File phải là ảnh (jpeg, png, jpg, gif)
-   Kích thước tối đa: 2MB
-   Bắt buộc khi tạo mới
-   Tùy chọn khi update

### 3. UI/UX

-   Responsive design
-   Gradient background
-   Card-based layout
-   Hover effects
-   Smooth transitions
-   Alert messages

## So sánh: GD Library vs Intervention Image

### GD Library (Đang dùng) ✅

-   **Ưu điểm:**
    -   Built-in PHP, không cần cài đặt
    -   Nhẹ, nhanh
    -   Hỗ trợ đầy đủ JPEG, PNG, GIF
-   **Nhược điểm:**
    -   Code dài hơn
    -   API phức tạp hơn

### Intervention Image

-   **Ưu điểm:**

    -   API đơn giản, dễ sử dụng
    -   Nhiều tính năng filter, effects
    -   Code ngắn gọn

-   **Nhược điểm:**
    -   Cần cài đặt package
    -   Dependency thêm

**Ví dụ với Intervention Image:**

```php
// Cài đặt: composer require intervention/image
use Intervention\Image\Laravel\Facades\Image;

$image = Image::read($file);
$image->resize(300, 300);
$image->save($path);
```

## Storage Disk Options 💿

### Public Disk (Đang dùng)

```php
Storage::disk('public')->put('uploads/file.jpg', $file);
// Lưu tại: storage/app/public/uploads/
// URL: /storage/uploads/file.jpg
```

### S3 Disk (Cloud Storage)

```php
// .env
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket

// Code
Storage::disk('s3')->put('uploads/file.jpg', $file);
// Lưu lên AWS S3
// URL: https://bucket.s3.amazonaws.com/uploads/file.jpg
```

### Local Disk (Private)

```php
Storage::disk('local')->put('private/file.jpg', $file);
// Lưu tại: storage/app/private/
// Không public, cần route để access
```

## Testing 🧪

### Test upload

1. Truy cập: `http://localhost:8000/products/create`
2. Upload ảnh test
3. Check folders:
    - `storage/app/public/uploads/` - ảnh gốc
    - `storage/app/public/uploads/thumbnails/` - thumbnail

### Test delete

1. Xóa 1 product
2. Check folders - ảnh phải tự động bị xóa

## Troubleshooting 🔧

### Lỗi: "The file could not be uploaded"

**Giải pháp:**

1. Check permissions: `chmod -R 775 storage/`
2. Check symbolic link: `php artisan storage:link`

### Lỗi: Image không hiển thị

**Giải pháp:**

1. Check symbolic link exists: `public/storage -> ../storage/app/public`
2. Check file permissions
3. Check `.env` APP_URL đúng

### Lỗi: GD Library not found

**Giải pháp:**

```bash
# Ubuntu/Debian
sudo apt-get install php-gd

# Windows XAMPP
# Enable extension=gd in php.ini
```

## Best Practices 📚

1. **Validate file size:** Giới hạn 2MB để tránh quá tải
2. **Validate file type:** Chỉ cho phép image types
3. **Unique filename:** Dùng slug + timestamp
4. **Auto cleanup:** Xóa ảnh khi xóa record
5. **Use storage facade:** Dễ chuyển đổi giữa local/s3
6. **Create thumbnails:** Giảm bandwidth, tăng performance

## Mở rộng 🚀

### Thêm tính năng:

1. **Multiple images per product:**

    - Tạo table `product_images`
    - Relationship hasMany

2. **Image compression:**

    - Thêm quality parameter
    - Optimize file size

3. **CDN Integration:**

    - Upload lên AWS S3
    - CloudFront distribution

4. **Image filters:**

    - Grayscale
    - Blur
    - Watermark

5. **Lazy loading:**
    - Implement lazy load
    - Improve page speed

---

**Tác giả:** GitHub Copilot
**Ngày:** 13/01/2026
**Laravel Version:** 12.0
**PHP Version:** 8.2+
