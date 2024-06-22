<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\notification;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::all();
        return response()->json($comments);
    }



    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        $validator = Validator::make($request->all(), [
            'comment_content' => 'required|string',
            'post_id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            // Get all the error messages as an array
            $errors = $validator->errors()->all();

            // Join the error messages into a single string, separated by commas (or any other separator you prefer)
            $errorMessage = implode(', ', $errors);

            // Return the single error message
            return response()->json(['message' => $errorMessage], 400);
        }
        $comment = new Comment();
        $comment->comment_content = strip_tags($request->comment_content);
        $comment->post_id = $request->post_id;
        $comment->user_id = $user->id;
        $comment->save();
// Get the post owner
        $post = Post::find($request->post_id);
        $postOwner = $post->user;

// Create a notification for the post owner
        notification::create([
        'user_id' => $postOwner->id,
        'title' => 'New Comment on Your Post',
        'message' =>Auth::guard('api')->user()->name . ' commented on your post.',
        'type' => 'comment',
        'is_read' => false,
        'created_at' => now(config('app.timezone')),
        ]);
        $comment->load('post', 'user');

        return response()->json(['message' => 'Comment created successfully','data'=>$comment]);

    }

    public function update(Request $request, Comment $comment)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        if ($comment->user_id != $user->id) {
            return response()->json(['error' => 'You are not authorized to update this comment.'], 403);
        }
        $validator = Validator::make($request->all(), [
            'comment_content' => 'required|string',
        ]);


        if ($validator->fails()) {
            // Get all the error messages as an array
            $errors = $validator->errors()->all();

            // Join the error messages into a single string, separated by commas (or any other separator you prefer)
            $errorMessage = implode(', ', $errors);

            // Return the single error message
            return response()->json(['message' => $errorMessage], 400);
        }

        $comment->update($request->all());

        return response()->json(['message' => 'Comment updated successfully']);
    }

    public function destroy(Comment $comment)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        // Check if the user is authorized to delete the comment
        if ($comment->user_id != $user->id) {
            return response()->json(['error' => 'You are not authorized to delete this comment.'], 403);
        }

        // Delete the comment
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }

    public function showCommentsByPost($postId)
    {
        $comments = Comment::where('post_id', $postId)->get();
        return response()->json($comments);
    }
}

