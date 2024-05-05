<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
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
        $validator = Validator::make($request->all(), [
            'comment_content' => 'required|string',
            'post_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $comment = new Comment();
        $comment->comment_content = strip_tags($request->comment_content);
        $comment->post_id = $request->post_id;
        $comment->user_id = auth()->id();
        $comment->save();

        return response()->json(['message' => 'Comment created successfully'], 201);
    }

    public function update(Request $request, Comment $comment)
    {
        $validator = Validator::make($request->all(), [
            'comment_content' => 'required|string',
            'post_id' => 'required|integer'
        ]);
            
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $comment->update($request->all());

        return response()->json(['message' => 'Comment updated successfully']);
    }

    public function destroy(Comment $comment)
    {
        // Check if the user is authorized to delete the comment
        if ($comment->user_id != auth()->id()) {
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

