# API Documentation - RESTful + JWT Authentication

## Mô tả chung

-   Base URL: `http://localhost/api`
-   Format response: JSON
-   Authentication: JWT Bearer Token

---

## 1. Authentication API

### 1.1 Register (Đăng ký)

**Endpoint:** `POST /api/auth/register`

**Request:**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Success Response (201):**

```json
{
    "status": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "created_at": "2024-01-12T10:00:00Z"
        },
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "Bearer",
        "expires_in": 3600
    }
}
```

**Error Response (422):**

```json
{
    "status": false,
    "message": "Validation failed",
    "errors": {
        "email": ["Email must be unique"]
    }
}
```

---

### 1.2 Login (Đăng nhập)

**Endpoint:** `POST /api/auth/login`

**Request:**

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Success Response (200):**

```json
{
    "status": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "Bearer",
        "expires_in": 3600
    }
}
```

**Error Response (401):**

```json
{
    "status": false,
    "message": "Invalid credentials"
}
```

---

### 1.3 Get Profile (Lấy thông tin người dùng)

**Endpoint:** `GET /api/auth/me`

**Headers:**

```
Authorization: Bearer {access_token}
```

**Success Response (200):**

```json
{
    "status": true,
    "message": "Profile retrieved successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2024-01-12T10:00:00Z",
        "updated_at": "2024-01-12T10:00:00Z"
    }
}
```

---

### 1.4 Refresh Token (Làm mới token)

**Endpoint:** `POST /api/auth/refresh`

**Headers:**

```
Authorization: Bearer {access_token}
```

**Success Response (200):**

```json
{
    "status": true,
    "message": "Token refreshed successfully",
    "data": {
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "Bearer",
        "expires_in": 3600
    }
}
```

---

### 1.5 Logout (Đăng xuất)

**Endpoint:** `POST /api/auth/logout`

**Headers:**

```
Authorization: Bearer {access_token}
```

**Success Response (200):**

```json
{
    "status": true,
    "message": "Logout successful"
}
```

---

## 2. Posts API (CRUD)

### 2.1 Get All Posts (Danh sách bài viết)

**Endpoint:** `GET /api/posts`

**Query Parameters:**

-   `search` (optional): Tìm kiếm theo tiêu đề hoặc nội dung
-   `sort` (optional): Sắp xếp - `newest` | `oldest` | `popular`
-   `per_page` (optional): Số bài viết trên trang (mặc định: 15)
-   `page` (optional): Trang hiện tại

**Example:**

```
GET /api/posts?search=laravel&sort=newest&per_page=10&page=1
```

**Success Response (200):**

```json
{
    "status": true,
    "message": "Posts retrieved successfully",
    "data": {
        "posts": [
            {
                "id": 1,
                "title": "Getting Started with Laravel",
                "slug": "getting-started-with-laravel",
                "excerpt": "Learn the basics...",
                "content": "Full content here...",
                "featured_image": "https://example.com/image.jpg",
                "views": 150,
                "category_id": 2,
                "user_id": 1,
                "published_at": "2024-01-12T10:00:00Z",
                "created_at": "2024-01-12T10:00:00Z",
                "updated_at": "2024-01-12T10:00:00Z"
            }
        ],
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

### 2.2 Create Post (Tạo bài viết)

**Endpoint:** `POST /api/posts`

**Headers:**

```
Authorization: Bearer {access_token}
Content-Type: application/json
```

**Request:**

```json
{
    "title": "Getting Started with Laravel",
    "slug": "getting-started-with-laravel",
    "excerpt": "Learn the basics of Laravel framework",
    "content": "Full content of the blog post...",
    "featured_image": "https://example.com/image.jpg",
    "category_id": 2
}
```

**Success Response (201):**

```json
{
    "status": true,
    "message": "Post created successfully",
    "data": {
        "id": 1,
        "title": "Getting Started with Laravel",
        "slug": "getting-started-with-laravel",
        "excerpt": "Learn the basics...",
        "content": "Full content here...",
        "featured_image": "https://example.com/image.jpg",
        "views": 0,
        "category_id": 2,
        "user_id": 1,
        "published_at": "2024-01-12T10:00:00Z",
        "created_at": "2024-01-12T10:00:00Z",
        "updated_at": "2024-01-12T10:00:00Z"
    }
}
```

**Error Response (422):**

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

---

### 2.3 Get Single Post (Chi tiết bài viết)

**Endpoint:** `GET /api/posts/{id}`

**Success Response (200):**

```json
{
    "status": true,
    "message": "Post retrieved successfully",
    "data": {
        "id": 1,
        "title": "Getting Started with Laravel",
        "slug": "getting-started-with-laravel",
        "excerpt": "Learn the basics...",
        "content": "Full content here...",
        "featured_image": "https://example.com/image.jpg",
        "views": 150,
        "category_id": 2,
        "user_id": 1,
        "published_at": "2024-01-12T10:00:00Z",
        "created_at": "2024-01-12T10:00:00Z",
        "updated_at": "2024-01-12T10:00:00Z"
    }
}
```

**Error Response (404):**

```json
{
    "status": false,
    "message": "Post not found"
}
```

---

### 2.4 Update Post (Cập nhật bài viết)

**Endpoint:** `PUT /api/posts/{id}`

**Headers:**

```
Authorization: Bearer {access_token}
Content-Type: application/json
```

**Request:**

```json
{
    "title": "Updated Title",
    "slug": "updated-slug",
    "excerpt": "Updated excerpt",
    "content": "Updated content",
    "featured_image": "https://example.com/new-image.jpg",
    "category_id": 3
}
```

**Success Response (200):**

```json
{
  "status": true,
  "message": "Post updated successfully",
  "data": {
    "id": 1,
    "title": "Updated Title",
    "slug": "updated-slug",
    ...
  }
}
```

**Error Response (403):**

```json
{
    "status": false,
    "message": "Unauthorized to update this post"
}
```

---

### 2.5 Delete Post (Xóa bài viết)

**Endpoint:** `DELETE /api/posts/{id}`

**Headers:**

```
Authorization: Bearer {access_token}
```

**Success Response (200):**

```json
{
    "status": true,
    "message": "Post deleted successfully",
    "data": null
}
```

**Error Response (403):**

```json
{
    "status": false,
    "message": "Unauthorized to delete this post"
}
```

---

## Status Codes

| Code | Meaning                                  |
| ---- | ---------------------------------------- |
| 200  | OK - Request successful                  |
| 201  | Created - Resource created successfully  |
| 400  | Bad Request - Invalid request            |
| 401  | Unauthorized - Missing or invalid token  |
| 403  | Forbidden - User not authorized          |
| 404  | Not Found - Resource not found           |
| 422  | Unprocessable Entity - Validation failed |
| 500  | Internal Server Error                    |

---

## JWT Token

### Token Format

```
Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

### How to Use

1. Get token từ `/api/auth/login` hoặc `/api/auth/register`
2. Thêm vào header: `Authorization: Bearer {token}`
3. Gửi request đến protected routes

### Token Expiration

-   Mặc định: 1 giờ (3600 giây)
-   Refresh token: `POST /api/auth/refresh`

---

## Error Handling

Tất cả error responses có format:

```json
{
    "status": false,
    "message": "Error message",
    "errors": {} // Validation errors if any
}
```

---

## Testing with cURL

### Register

```bash
curl -X POST http://localhost/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Login

```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### Get Profile (with token)

```bash
curl -X GET http://localhost/api/auth/me \
  -H "Authorization: Bearer {token}"
```

### Create Post (with token)

```bash
curl -X POST http://localhost/api/posts \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "My Post",
    "slug": "my-post",
    "content": "Post content here",
    "category_id": 1
  }'
```

---

## Testing with Postman

1. Tạo collection mới: "Blog API"
2. Set base URL: `{{base_url}}/api`
3. Tạo environment variable: `base_url = http://localhost`
4. Tạo requests cho từng endpoint
5. Lưu token từ login vào variable: `{{token}}`
6. Thêm vào header: `Authorization: Bearer {{token}}`
