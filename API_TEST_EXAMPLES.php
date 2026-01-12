<?php

/**
 * API Testing & Demonstration File
 * 
 * Hướng dẫn: Sử dụng file này để test các API endpoints
 * 
 * Có 3 cách test:
 * 1. Postman (recommend) - import Blog_API_Postman.postman_collection.json
 * 2. cURL command line
 * 3. Laravel Artisan tinker
 */

// ============================================================================
// EXAMPLE 1: Using Laravel Tinker
// ============================================================================

/**
 * php artisan tinker
 * 
 * // Register
 * $data = [
 *     'name' => 'John Doe',
 *     'email' => 'john@example.com',
 *     'password' => 'password123',
 *     'password_confirmation' => 'password123'
 * ];
 * $response = Http::post('http://localhost/api/auth/register', $data);
 * $token = $response['data']['access_token'];
 * 
 * // Login
 * $response = Http::post('http://localhost/api/auth/login', [
 *     'email' => 'john@example.com',
 *     'password' => 'password123'
 * ]);
 * $token = $response['data']['access_token'];
 * 
 * // Get profile
 * Http::withToken($token)->get('http://localhost/api/auth/me');
 * 
 * // Create post
 * Http::withToken($token)->post('http://localhost/api/posts', [
 *     'title' => 'My First Post',
 *     'slug' => 'my-first-post',
 *     'content' => 'This is my first post',
 *     'category_id' => 1
 * ]);
 * 
 * // Get all posts
 * Http::get('http://localhost/api/posts?sort=newest&per_page=10');
 * 
 * // Update post
 * Http::withToken($token)->put('http://localhost/api/posts/1', [
 *     'title' => 'Updated Title'
 * ]);
 * 
 * // Delete post
 * Http::withToken($token)->delete('http://localhost/api/posts/1');
 */

// ============================================================================
// EXAMPLE 2: Using cURL Command Line
// ============================================================================

/*

# 1. REGISTER
curl -X POST http://localhost/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Response:
# {
#   "status": true,
#   "message": "User registered successfully",
#   "data": {
#     "user": {
#       "id": 1,
#       "name": "John Doe",
#       "email": "john@example.com"
#     },
#     "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
#     "token_type": "Bearer",
#     "expires_in": 3600
#   }
# }

# Save token: TOKEN="eyJ0eXAiOiJKV1QiLCJhbGc..."

---

# 2. LOGIN
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'

# Response same as register

---

# 3. GET PROFILE (requires token)
curl -X GET http://localhost/api/auth/me \
  -H "Authorization: Bearer {TOKEN}"

# Response:
# {
#   "status": true,
#   "message": "Profile retrieved successfully",
#   "data": {
#     "id": 1,
#     "name": "John Doe",
#     "email": "john@example.com",
#     "created_at": "2024-01-12T10:00:00Z",
#     "updated_at": "2024-01-12T10:00:00Z"
#   }
# }

---

# 4. GET ALL POSTS (public, no token needed)
curl -X GET "http://localhost/api/posts?search=laravel&sort=newest&per_page=10&page=1"

# Response:
# {
#   "status": true,
#   "message": "Posts retrieved successfully",
#   "data": {
#     "posts": [...],
#     "pagination": {...}
#   }
# }

---

# 5. CREATE POST (requires token)
curl -X POST http://localhost/api/posts \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "My First Post",
    "slug": "my-first-post",
    "excerpt": "This is my first post",
    "content": "Full content of my first post goes here...",
    "featured_image": "https://via.placeholder.com/600x400",
    "category_id": 1
  }'

# Response:
# {
#   "status": true,
#   "message": "Post created successfully",
#   "data": {
#     "id": 1,
#     "title": "My First Post",
#     ...
#   }
# }

---

# 6. GET SINGLE POST (public, no token needed)
curl -X GET http://localhost/api/posts/1

# Response:
# {
#   "status": true,
#   "message": "Post retrieved successfully",
#   "data": {...}
# }

---

# 7. UPDATE POST (requires token, must be owner)
curl -X PUT http://localhost/api/posts/1 \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Updated Title",
    "slug": "updated-slug",
    "content": "Updated content..."
  }'

# Response:
# {
#   "status": true,
#   "message": "Post updated successfully",
#   "data": {...}
# }

---

# 8. DELETE POST (requires token, must be owner)
curl -X DELETE http://localhost/api/posts/1 \
  -H "Authorization: Bearer {TOKEN}"

# Response:
# {
#   "status": true,
#   "message": "Post deleted successfully",
#   "data": null
# }

---

# 9. REFRESH TOKEN (requires token)
curl -X POST http://localhost/api/auth/refresh \
  -H "Authorization: Bearer {TOKEN}"

# Response:
# {
#   "status": true,
#   "message": "Token refreshed successfully",
#   "data": {
#     "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
#     "token_type": "Bearer",
#     "expires_in": 3600
#   }
# }

---

# 10. LOGOUT (requires token)
curl -X POST http://localhost/api/auth/logout \
  -H "Authorization: Bearer {TOKEN}"

# Response:
# {
#   "status": true,
#   "message": "Logout successful"
# }

*/

// ============================================================================
// EXAMPLE 3: Using Postman
// ============================================================================

/*

1. Open Postman
2. File → Import
3. Select: Blog_API_Postman.postman_collection.json
4. Create Environment:
   - name: base_url
   - value: http://localhost
   
   - name: token
   - value: (leave empty, auto-filled on login)

5. Run requests in order:
   ✓ Auth → Register
   ✓ Auth → Login (auto-saves token)
   ✓ Auth → Get Profile (uses token)
   ✓ Posts → Get All Posts
   ✓ Posts → Create Post (uses token)
   ✓ Posts → Get Single Post
   ✓ Posts → Update Post (uses token)
   ✓ Posts → Delete Post (uses token)

*/

// ============================================================================
// VALIDATION RULES REFERENCE
// ============================================================================

/*

### Register Request:
{
  "name": "required|string|max:255",
  "email": "required|string|email|max:255|unique:users",
  "password": "required|string|min:8|confirmed",
  "password_confirmation": "required (for password confirmation)"
}

### Login Request:
{
  "email": "required|string|email",
  "password": "required|string"
}

### Create Post Request:
{
  "title": "required|string|max:255|unique:posts",
  "slug": "required|string|max:255|unique:posts|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/",
  "excerpt": "nullable|string|max:500",
  "content": "required|string|min:10",
  "featured_image": "nullable|url",
  "category_id": "nullable|exists:categories,id"
}

### Update Post Request:
Same as Create Post but:
- All fields are "sometimes" (optional)
- unique validation excludes current post ID
- Slug must be kebab-case format

### Error Response Example:
{
  "status": false,
  "message": "Validation failed",
  "errors": {
    "email": ["Email must be unique"],
    "slug": ["Slug format is invalid"]
  }
}

*/

// ============================================================================
// AUTHENTICATION FLOW
// ============================================================================

/*

1. USER REGISTRATION
   POST /api/auth/register
   → User created
   → Token generated
   → Token expires in 1 hour

2. USER LOGIN
   POST /api/auth/login
   → Token generated
   → Token expires in 1 hour

3. USE TOKEN
   GET /api/auth/me
   Header: Authorization: Bearer {token}
   → Get user profile

4. REFRESH TOKEN (before expiry)
   POST /api/auth/refresh
   Header: Authorization: Bearer {token}
   → New token issued
   → Use new token for next requests

5. LOGOUT
   POST /api/auth/logout
   Header: Authorization: Bearer {token}
   → Token blacklisted
   → Cannot reuse token

6. TOKEN EXPIRED
   → 401 Unauthorized
   → Call refresh endpoint
   → Get new token
   → Retry original request

*/

// ============================================================================
// ERROR CODES REFERENCE
// ============================================================================

/*

### 200 OK
- Successful GET, PUT request
- Example: Get profile, update post

### 201 Created
- Successful POST (resource created)
- Example: Register user, create post

### 400 Bad Request
- Malformed request
- Missing required fields
- Example: Invalid JSON syntax

### 401 Unauthorized
- Missing or invalid token
- Token expired
- Example: Missing Authorization header

### 403 Forbidden
- User not authorized
- Not post owner
- Example: Trying to delete someone else's post

### 404 Not Found
- Resource doesn't exist
- Example: Post with ID 999 doesn't exist

### 422 Unprocessable Entity
- Validation failed
- Example: Email not unique, slug format invalid

### 500 Server Error
- Internal server error
- Database connection failed
- Example: Database error

*/

// ============================================================================
// RESPONSE FORMAT
// ============================================================================

/*

### Success Response Format:
{
  "status": true,
  "message": "Description of success",
  "data": {
    "key": "value",
    ...
  }
}

### Error Response Format:
{
  "status": false,
  "message": "Error description",
  "errors": {
    "field": ["Error message 1", "Error message 2"]
  }
}

### Paginated Response Format:
{
  "status": true,
  "message": "Posts retrieved successfully",
  "data": {
    "posts": [...items...],
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

*/

// ============================================================================
// TROUBLESHOOTING
// ============================================================================

/*

PROBLEM: "Token has expired"
SOLUTION: Call POST /api/auth/refresh to get new token

PROBLEM: "Token is invalid"
SOLUTION: Login again to get new token

PROBLEM: "Authorization token missing"
SOLUTION: Add header: Authorization: Bearer {token}

PROBLEM: "User not found"
SOLUTION: Token is invalid, login again

PROBLEM: "Validation failed"
SOLUTION: Check "errors" field in response for details

PROBLEM: "Unauthorized to update this post"
SOLUTION: Only post owner or admin can edit/delete

PROBLEM: "Post not found"
SOLUTION: Check if post ID exists in database

*/

// ============================================================================
// QUICK TEST SCRIPT
// ============================================================================

/**
 * Save as test_api.php and run: php test_api.php
 * 
 * $client = new \GuzzleHttp\Client();
 * $baseUrl = 'http://localhost/api';
 * 
 * // Register
 * $response = $client->post("$baseUrl/auth/register", [
 *     'json' => [
 *         'name' => 'John Doe',
 *         'email' => 'john@example.com',
 *         'password' => 'password123',
 *         'password_confirmation' => 'password123'
 *     ]
 * ]);
 * $data = json_decode($response->getBody(), true);
 * $token = $data['data']['access_token'];
 * echo "✓ Registered and got token\n";
 * 
 * // Create post
 * $response = $client->post("$baseUrl/posts", [
 *     'headers' => ['Authorization' => "Bearer $token"],
 *     'json' => [
 *         'title' => 'Test Post',
 *         'slug' => 'test-post',
 *         'content' => 'This is a test post'
 *     ]
 * ]);
 * echo "✓ Created post\n";
 * 
 * // Get all posts
 * $response = $client->get("$baseUrl/posts");
 * echo "✓ Retrieved posts\n";
 */
