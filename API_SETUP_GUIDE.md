# Hướng Dẫn Cài Đặt và Sử Dụng API

## 📋 Yêu Cầu Hệ Thống

-   PHP 8.1+
-   Laravel 11.x
-   Composer
-   JWT Authentication

---

## 🚀 Bước 1: Cài Đặt JWT Authentication

Nếu chưa cài, chạy lệnh:

```bash
composer require tymon/jwt-auth
```

### Tạo JWT Secret Key:

```bash
php artisan jwt:secret
```

Lệnh này sẽ tạo key trong file `.env`:

```
JWT_SECRET=your_secret_key_here
```

---

## 🔧 Bước 2: Cấu Hình

### File: `config/auth.php`

Đã được cập nhật với:

```php
'guards' => [
    'web' => [...],
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],
```

### File: `bootstrap/app.php`

Đã được cấu hình middleware JWT

---

## 📁 Cấu Trúc Thư Mục API

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── AuthController.php    (Xử lý Auth)
│   │       └── PostController.php    (CRUD Post)
│   ├── Middleware/
│   │   └── JwtMiddleware.php         (Xác thực JWT)
│   ├── Requests/
│   │   └── Api/
│   │       ├── StorePostRequest.php  (Validation)
│   │       └── UpdatePostRequest.php (Validation)
│   └── Resources/
│       ├── UserResource.php          (User API Resource)
│       └── PostResource.php          (Post API Resource)
routes/
└── api.php                           (Routes API)
```

---

## 🔐 API Endpoints

### Authentication Routes (Không cần token)

| Method | Endpoint             | Mô Tả             |
| ------ | -------------------- | ----------------- |
| POST   | `/api/auth/register` | Đăng ký tài khoản |
| POST   | `/api/auth/login`    | Đăng nhập         |

### Protected Routes (Cần JWT Token)

| Method | Endpoint            | Mô Tả              |
| ------ | ------------------- | ------------------ |
| GET    | `/api/auth/me`      | Lấy thông tin user |
| POST   | `/api/auth/refresh` | Làm mới token      |
| POST   | `/api/auth/logout`  | Đăng xuất          |
| GET    | `/api/posts`        | Danh sách bài viết |
| POST   | `/api/posts`        | Tạo bài viết       |
| GET    | `/api/posts/{id}`   | Chi tiết bài viết  |
| PUT    | `/api/posts/{id}`   | Cập nhật bài viết  |
| DELETE | `/api/posts/{id}`   | Xóa bài viết       |

---

## 📝 Hướng Dẫn Sử Dụng

### 1️⃣ Đăng Ký (Register)

```bash
POST /api/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response:**

```json
{
  "status": true,
  "message": "User registered successfully",
  "data": {
    "user": {...},
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "Bearer",
    "expires_in": 3600
  }
}
```

---

### 2️⃣ Đăng Nhập (Login)

```bash
POST /api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**

```json
{
  "status": true,
  "message": "Login successful",
  "data": {
    "user": {...},
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "Bearer",
    "expires_in": 3600
  }
}
```

💡 **Lưu token này để sử dụng cho các request tiếp theo!**

---

### 3️⃣ Sử Dụng Token

Thêm token vào header của mọi protected request:

```bash
Authorization: Bearer {token_từ_login}
```

### Ví dụ:

```bash
GET /api/auth/me
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

---

### 4️⃣ Tạo Bài Viết (Create Post)

```bash
POST /api/posts
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Getting Started with Laravel",
  "slug": "getting-started-with-laravel",
  "excerpt": "Learn the basics",
  "content": "Full content here...",
  "featured_image": "https://example.com/image.jpg",
  "category_id": 1
}
```

---

### 5️⃣ Danh Sách Bài Viết (Get Posts)

```bash
GET /api/posts?search=laravel&sort=newest&per_page=10&page=1
```

**Query Parameters:**

-   `search`: Tìm kiếm theo title/content
-   `sort`: newest | oldest | popular
-   `per_page`: Số item trên trang (default: 15)
-   `page`: Số trang

---

### 6️⃣ Cập Nhật Bài Viết (Update Post)

```bash
PUT /api/posts/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Updated Title",
  "content": "Updated content..."
}
```

---

### 7️⃣ Xóa Bài Viết (Delete Post)

```bash
DELETE /api/posts/{id}
Authorization: Bearer {token}
```

---

## 🧪 Testing API

### Cách 1: Sử dụng Postman

1. **Import collection:**

    - Mở Postman
    - File → Import
    - Chọn `Blog_API_Postman.postman_collection.json`

2. **Cấu hình Environment:**

    - Tạo Environment mới
    - Variable `base_url` = `http://localhost`
    - Variable `token` = (để trống, sẽ auto fill)

3. **Test API:**
    - Login để lấy token (auto save)
    - Dùng token cho protected routes

### Cách 2: Sử dụng cURL

```bash
# Register
curl -X POST http://localhost/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Login
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'

# Get Profile (với token)
curl -X GET http://localhost/api/auth/me \
  -H "Authorization: Bearer {token}"
```

### Cách 3: Sử dụng Insomnia

Tương tự như Postman, import collection và cấu hình token.

---

## 🔄 Token Lifecycle

1. **Nhận Token:** Login → lấy `access_token`
2. **Sử Dụng:** Gửi với mọi request: `Authorization: Bearer {token}`
3. **Hết Hạn:** Token hết hạn sau 1 giờ
4. **Làm Mới:** `POST /api/auth/refresh` → nhận token mới
5. **Đăng Xuất:** `POST /api/auth/logout` → invalid token

---

## ⚠️ Error Handling

### Unauthorized (401)

```json
{
    "status": false,
    "message": "Token has expired"
}
```

**Xử lý:** Gọi `/api/auth/refresh` để lấy token mới

### Validation Error (422)

```json
{
    "status": false,
    "message": "Validation failed",
    "errors": {
        "title": ["Title is required"],
        "slug": ["Slug format is invalid"]
    }
}
```

**Xử lý:** Kiểm tra input theo yêu cầu

### Forbidden (403)

```json
{
    "status": false,
    "message": "Unauthorized to update this post"
}
```

**Xử lý:** Chỉ có chủ sở hữu post mới có thể edit/delete

### Not Found (404)

```json
{
    "status": false,
    "message": "Post not found"
}
```

---

## 🛡️ Security Best Practices

1. **Giữ Secret Key an toàn:**

    - Không commit `.env` vào git
    - Sử dụng environment variables

2. **Token Storage:**

    - Lưu token trong localStorage hoặc sessionStorage
    - KHÔNG lưu trong cookie (CSRF risk)

3. **CORS Configuration:**

    ```php
    // config/cors.php
    'allowed_origins' => ['http://localhost:3000'],
    ```

4. **Refresh Token:**
    - Gọi refresh trước khi token hết hạn
    - Hoặc handle 401 error bằng refresh

---

## 📊 Response Format

Tất cả responses theo format chuẩn:

```json
{
  "status": true/false,
  "message": "Description",
  "data": {...},
  "errors": {...} // Nếu có validation error
}
```

---

## 🐛 Troubleshooting

### "Token blacklisted" Error

-   Token đã bị logout
-   Đăng nhập lại để lấy token mới

### "Token has expired"

-   Token hết hạn
-   Gọi `/api/auth/refresh`

### "Authorization token missing"

-   Quên thêm header `Authorization`
-   Format: `Bearer {token}` (có space)

### "User not found"

-   Token không hợp lệ
-   Đăng nhập lại

---

## 📚 Tài Liệu Thêm

-   [JWT Auth Documentation](https://jwt-auth.readthedocs.io/)
-   [Laravel API Resources](https://laravel.com/docs/11.x/eloquent-resources)
-   [Postman Documentation](https://learning.postman.com/)

---

**Enjoy your API! 🎉**
