# 🚀 API RESTful + JWT Authentication

Đây là một API chuẩn RESTful với JWT Authentication cho ứng dụng Blog.

---

## 📚 Thành Phần

### 1. **Controllers** (`app/Http/Controllers/Api/`)

-   **AuthController.php** - Xử lý authentication (register, login, profile, refresh, logout)
-   **PostController.php** - CRUD operations cho bài viết (index, store, show, update, destroy)

### 2. **Form Requests** (`app/Http/Requests/Api/`)

-   **StorePostRequest.php** - Validation rules cho tạo bài viết
-   **UpdatePostRequest.php** - Validation rules cho cập nhật bài viết

### 3. **Resources** (`app/Http/Resources/`)

-   **UserResource.php** - Transform user data
-   **PostResource.php** - Transform post data

### 4. **Middleware** (`app/Http/Middleware/`)

-   **JwtMiddleware.php** - Verify JWT token

### 5. **Routes** (`routes/api.php`)

-   Public routes: Register, Login
-   Protected routes: Require JWT token

### 6. **Documentation**

-   **API_DOCUMENTATION.md** - Chi tiết API endpoints
-   **API_SETUP_GUIDE.md** - Hướng dẫn cài đặt & sử dụng
-   **API_IMPLEMENTATION_NOTES.md** - Ghi chú chi tiết implementation
-   **Blog_API_Postman.postman_collection.json** - Postman collection
-   **API_TEST_EXAMPLES.php** - Ví dụ testing

---

## 🎯 API Endpoints

### Public Routes (Không cần token)

| Method | Endpoint             | Mô Tả              |
| ------ | -------------------- | ------------------ |
| `POST` | `/api/auth/register` | Đăng ký tài khoản  |
| `POST` | `/api/auth/login`    | Đăng nhập          |
| `GET`  | `/api/posts`         | Danh sách bài viết |
| `GET`  | `/api/posts/{id}`    | Chi tiết bài viết  |

### Protected Routes (Cần JWT token)

| Method   | Endpoint            | Mô Tả              |
| -------- | ------------------- | ------------------ |
| `GET`    | `/api/auth/me`      | Lấy thông tin user |
| `POST`   | `/api/auth/refresh` | Làm mới token      |
| `POST`   | `/api/auth/logout`  | Đăng xuất          |
| `POST`   | `/api/posts`        | Tạo bài viết       |
| `PUT`    | `/api/posts/{id}`   | Cập nhật bài viết  |
| `DELETE` | `/api/posts/{id}`   | Xóa bài viết       |

---

## 🔒 Authentication

### JWT Token Structure

```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

### Token Lifecycle

1. **Nhận Token**: Login → `access_token` + `expires_in`
2. **Sử Dụng**: Gửi với header `Authorization: Bearer {token}`
3. **Hết Hạn**: Token hết hạn sau 1 giờ (3600 giây)
4. **Làm Mới**: `POST /api/auth/refresh` → Token mới
5. **Đăng Xuất**: `POST /api/auth/logout` → Token invalid

---

## 📝 Request/Response Format

### Success Response (200, 201)

```json
{
  "status": true,
  "message": "Success message",
  "data": {
    "id": 1,
    "name": "John Doe",
    ...
  }
}
```

### Error Response (400, 401, 403, 404, 422, 500)

```json
{
    "status": false,
    "message": "Error description",
    "errors": {
        "field": ["Error message"]
    }
}
```

### Paginated Response

```json
{
  "status": true,
  "message": "Success",
  "data": {
    "posts": [...],
    "pagination": {
      "total": 50,
      "per_page": 10,
      "current_page": 1,
      "last_page": 5,
      "from": 1,
      "to": 10
    }
  }
}
```

---

## 🧪 Testing

### Cách 1: Postman (Recommended)

```bash
1. Mở Postman
2. File → Import → Blog_API_Postman.postman_collection.json
3. Tạo Environment: base_url = http://localhost
4. Test từng request (token auto-save)
```

### Cách 2: cURL

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

# Get Profile (with token)
curl -X GET http://localhost/api/auth/me \
  -H "Authorization: Bearer {TOKEN}"
```

### Cách 3: Insomnia

Tương tự Postman, import collection và cấu hình token.

---

## 📋 Validation Rules

### Register Request

-   `name`: required, string, max 255
-   `email`: required, email, unique
-   `password`: required, min 8, confirmed
-   `password_confirmation`: required

### Login Request

-   `email`: required, email
-   `password`: required

### Create Post Request

-   `title`: required, string, max 255, unique
-   `slug`: required, string, max 255, unique, kebab-case
-   `excerpt`: nullable, string, max 500
-   `content`: required, string, min 10
-   `featured_image`: nullable, valid URL
-   `category_id`: nullable, exists in categories

### Update Post Request

-   Same rules as Create but all fields are optional

---

## 🔐 Security Features

✅ **Password Hashing**

-   Bcrypt hashing (Laravel default)
-   60-character encoded passwords

✅ **JWT Token**

-   Signed with secret key from `.env`
-   Token expiration (1 hour default)
-   Refresh mechanism

✅ **Authorization**

-   Check post ownership before update/delete
-   Admin bypass support

✅ **Input Validation**

-   Server-side validation
-   Custom error messages
-   Unique constraints

✅ **Error Handling**

-   No sensitive info leaked
-   Consistent error format
-   Proper HTTP status codes

---

## 🛠️ Installation & Setup

### 1. Install JWT Package

```bash
composer require tymon/jwt-auth
```

### 2. Generate JWT Secret

```bash
php artisan jwt:secret
```

### 3. Run Migrations

```bash
php artisan migrate
```

### 4. Clear Cache

```bash
php artisan config:cache
```

### 5. Test API

Use Postman collection or cURL commands

---

## 📖 Documentation Files

| File                                       | Mô Tả                                    |
| ------------------------------------------ | ---------------------------------------- |
| `API_DOCUMENTATION.md`                     | Đầy đủ API endpoints & examples          |
| `API_SETUP_GUIDE.md`                       | Hướng dẫn cài đặt & troubleshooting      |
| `API_IMPLEMENTATION_NOTES.md`              | Chi tiết implementation & best practices |
| `API_TEST_EXAMPLES.php`                    | Ví dụ testing với cURL, Tinker, Postman  |
| `Blog_API_Postman.postman_collection.json` | Postman collection ready-to-use          |

---

## 🚨 Common Errors

### 401 Unauthorized - "Token has expired"

**Cause**: Token hết hạn  
**Solution**: Gọi `POST /api/auth/refresh`

### 401 Unauthorized - "Authorization token missing"

**Cause**: Quên thêm header  
**Solution**: Sử dụng format: `Authorization: Bearer {token}`

### 403 Forbidden - "Unauthorized to update this post"

**Cause**: Không phải chủ sở hữu post  
**Solution**: Chỉ owner hoặc admin mới có thể edit/delete

### 422 Validation failed

**Cause**: Input không hợp lệ  
**Solution**: Kiểm tra `errors` field trong response

### 404 Not Found

**Cause**: Resource không tồn tại  
**Solution**: Kiểm tra post ID hoặc user existence

---

## 📊 Status Codes

| Code | Meaning           | Example                      |
| ---- | ----------------- | ---------------------------- |
| 200  | OK                | GET request thành công       |
| 201  | Created           | POST tạo resource thành công |
| 400  | Bad Request       | Request format sai           |
| 401  | Unauthorized      | Token invalid hoặc expired   |
| 403  | Forbidden         | Không được phép              |
| 404  | Not Found         | Resource không tồn tại       |
| 422  | Validation Failed | Input validation error       |
| 500  | Server Error      | Internal server error        |

---

## 🎓 Learning Resources

### RESTful Standards

-   HTTP Methods: GET, POST, PUT, DELETE
-   Status Codes: 200, 201, 400, 401, 403, 404, 422, 500
-   Response Format: Consistent JSON structure

### JWT Authentication

-   Token-based, stateless authentication
-   No server-side session needed
-   Secure signing with secret key
-   Automatic expiration & refresh

### Laravel API Development

-   Form Request Validation
-   API Resources for data transformation
-   Middleware for request handling
-   Route groups & protection

---

## ✨ Features Implemented

✅ JWT Authentication  
✅ User Registration & Login  
✅ Token Refresh Mechanism  
✅ CRUD Operations for Posts  
✅ Form Request Validation  
✅ API Resources  
✅ Authorization Checks  
✅ Error Handling  
✅ Pagination Support  
✅ Search & Sort Features  
✅ Comprehensive Documentation  
✅ Postman Collection

---

## 🔄 API Flow Example

```
1. User Register/Login
   POST /api/auth/register or /api/auth/login
   → Receives access_token

2. User Uses Token
   GET /api/auth/me
   Header: Authorization: Bearer {token}
   → Gets user profile

3. User Creates Post
   POST /api/posts
   Header: Authorization: Bearer {token}
   Body: { title, slug, content, ... }
   → Post created with user_id

4. Token About to Expire
   POST /api/auth/refresh
   Header: Authorization: Bearer {token}
   → Receives new access_token

5. User Logout
   POST /api/auth/logout
   Header: Authorization: Bearer {token}
   → Token blacklisted

6. Next Request Without Valid Token
   GET /api/posts
   → Returns public posts (no token needed)

   GET /api/auth/me
   → 401 Unauthorized (token required)
```

---

## 📦 Tech Stack

-   **Laravel 11.x** - Web Framework
-   **PHP 8.1+** - Programming Language
-   **JWT Auth** - Authentication
-   **MySQL** - Database
-   **Postman** - API Testing
-   **cURL** - Command Line Testing

---

## 🎯 Next Steps

1. ✅ Xây dựng API RESTful
2. ✅ Implement JWT Authentication
3. ✅ Create CRUD Operations
4. ✅ Validation & Error Handling
5. ✅ Documentation & Testing

### Possible Enhancements

-   [ ] Rate Limiting
-   [ ] Caching Strategy
-   [ ] API Versioning (v1, v2)
-   [ ] WebHooks
-   [ ] GraphQL Support
-   [ ] OAuth2 Integration
-   [ ] API Documentation (Swagger/OpenAPI)

---

## 📞 Support

Untuk bantuan lebih lanjut:

1. Baca file dokumentasi
2. Lihat contoh ví dụ
3. Test dengan Postman collection
4. Check API implementation notes

---

**Selamat! API Anda siap digunakan! 🎉**

Mulai dari authentication, kemudian gunakan token untuk akses protected routes.

Enjoy! 🚀
