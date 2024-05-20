<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts);
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        return response()->json($post);
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'post_text' => 'required|string',
            'attachments' => 'nullable',
            'privacy' => 'required|string',
            'post_type' => 'required|string',

        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = Auth::guard('api')->user(); // Authenticate using the 'api' guard

        // Check if the user is authenticated
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $postPicturePath = null;
        if ($request->hasFile('attachments')) {
            $postPicture = $request->file('attachments');
            $postPictureName = time() . '_' . $postPicture->getClientOriginalName();
            $postPicturePath = $postPicture->storeAs('post_pictures', $postPictureName,'public');
        }
        // Create a new post
        $post = new Post();
        $post->user_id = $user->id;;
        $post->post_text = $request->post_text;
        $post->attachments = $postPicturePath;
        $post->privacy = $request->privacy;
        $post->post_type = $request->post_type;
        $post->save();

        // Return a success response
        return response()->json(['message' => 'Post created successfully'], 201);
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, Post $post)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        if ($post->user_id!= $user->id) {
            return response()->json(['message'=>'You are not authorized to update this post.']);
        }
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'post_text' => 'required|string',
            'attachments' => 'nullable',
            'privacy' => 'required|string',
            'post_type' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Update the post
        $post->update($request->all());

        // Return a success response
        return response()->json(['message' => 'Post updated successfully']);
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        if ($post->user_id!= $user->id) {
            return response()->json(['message'=>'You are not authorized to delete this post.']);
        }
        // Delete the post
        $post->delete();

        // Return a success response
        return response()->json(['message' => 'Post deleted successfully']);
    }
    public function upvote(Post $post)
    {
        $post->upvotes++;
        $post->save();

        return response()->json(['message' => 'Post upvoted successfully']);
    }

    /**
     * Downvote the specified post.
     */
    public function downvote(Post $post)
    {
        $post->downvotes++;
        $post->save();

        return response()->json(['message' => 'Post downvoted successfully']);
    }

    public function showCommentsByPost($postId)
    {
        // Retrieve comments by post ID
        $comments = comment::where('post_id', $postId)->get();

        // Return comments as JSON response
        return response()->json($comments);
    }
}
