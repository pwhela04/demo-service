<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BlogPost::with('user:id,name,email');
        
        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by user if provided
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Get current user's posts only if requested
        if ($request->has('my_posts') && $request->my_posts) {
            $query->where('user_id', $request->user()->id);
        }
        
        $posts = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'sometimes|in:draft,published',
            'slug' => 'sometimes|string|unique:blog_posts,slug',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = [
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status ?? 'draft',
        ];

        // Generate slug if not provided
        if ($request->has('slug')) {
            $data['slug'] = $request->slug;
        }

        // Set published_at if status is published
        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        $blogPost = BlogPost::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Blog post created successfully',
            'data' => $blogPost->load('user:id,name,email')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $blogPost = BlogPost::with('user:id,name,email')->find($id);
        
        if (!$blogPost) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $blogPost
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $blogPost = BlogPost::find($id);
        
        if (!$blogPost) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found'
            ], 404);
        }

        // Check if user owns the post
        if ($blogPost->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this blog post'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'status' => 'sometimes|in:draft,published',
            'slug' => 'sometimes|string|unique:blog_posts,slug,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = [];
        
        if ($request->has('title')) {
            $updateData['title'] = $request->title;
        }
        
        if ($request->has('content')) {
            $updateData['content'] = $request->content;
        }
        
        if ($request->has('status')) {
            $updateData['status'] = $request->status;
            
            // Set published_at if status is being changed to published
            if ($request->status === 'published' && $blogPost->status !== 'published') {
                $updateData['published_at'] = now();
            }
        }
        
        if ($request->has('slug')) {
            $updateData['slug'] = $request->slug;
        }

        $blogPost->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Blog post updated successfully',
            'data' => $blogPost->load('user:id,name,email')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $blogPost = BlogPost::find($id);
        
        if (!$blogPost) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found'
            ], 404);
        }

        // Check if user owns the post
        if ($blogPost->user_id !== request()->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this blog post'
            ], 403);
        }

        $blogPost->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog post deleted successfully'
        ]);
    }
}
