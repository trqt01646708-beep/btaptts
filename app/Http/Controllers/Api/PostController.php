<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    /**
     * Get all posts
     * GET /api/posts
     */
    public function index(Request $request)
    {
        try {
            $query = Post::query();

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
            }

            // Sorting
            $sort = $request->get('sort', 'newest');
            if ($sort === 'newest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc');
            } elseif ($sort === 'popular') {
                $query->orderBy('views', 'desc');
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $posts = $query->paginate($perPage);

            return response()->json([
                'status' => true,
                'message' => 'Posts retrieved successfully',
                'data' => [
                    'posts' => $posts->items(),
                    'pagination' => [
                        'total' => $posts->total(),
                        'per_page' => $posts->perPage(),
                        'current_page' => $posts->currentPage(),
                        'last_page' => $posts->lastPage(),
                        'from' => $posts->firstItem(),
                        'to' => $posts->lastItem(),
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new post
     * POST /api/posts
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:posts',
                'slug' => 'required|string|max:255|unique:posts',
                'excerpt' => 'nullable|string|max:500',
                'content' => 'required|string',
                'featured_image' => 'nullable|url',
                'category_id' => 'nullable|exists:categories,id',
            ]);

            $post = Post::create([
                'title' => $validated['title'],
                'slug' => $validated['slug'],
                'excerpt' => $validated['excerpt'] ?? null,
                'content' => $validated['content'],
                'featured_image' => $validated['featured_image'] ?? null,
                'category_id' => $validated['category_id'] ?? null,
                'user_id' => auth('api')->id(),
                'published_at' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Post created successfully',
                'data' => $post
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single post
     * GET /api/posts/{id}
     */
    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Post retrieved successfully',
                'data' => $post
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a post
     * PUT /api/posts/{id}
     */
    public function update(Request $request, $id)
    {
        try {
            $post = Post::findOrFail($id);

            // Check authorization
            if ($post->user_id !== auth('api')->id() && !auth('api')->user()->isAdmin()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized to update this post'
                ], 403);
            }

            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255|unique:posts,title,' . $id,
                'slug' => 'sometimes|required|string|max:255|unique:posts,slug,' . $id,
                'excerpt' => 'nullable|string|max:500',
                'content' => 'sometimes|required|string',
                'featured_image' => 'nullable|url',
                'category_id' => 'nullable|exists:categories,id',
            ]);

            $post->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Post updated successfully',
                'data' => $post
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a post
     * DELETE /api/posts/{id}
     */
    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);

            // Check authorization
            if ($post->user_id !== auth('api')->id() && !auth('api')->user()->isAdmin()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized to delete this post'
                ], 403);
            }

            $post->delete();

            return response()->json([
                'status' => true,
                'message' => 'Post deleted successfully',
                'data' => null
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting post',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
