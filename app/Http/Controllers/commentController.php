<?php

namespace App\Http\Controllers;

use App\Models\comment;
use App\Models\Post;
use App\Models\notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;

class commentController extends Controller
{


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment_content' => 'required|string',
            'post_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request->merge([
            'comment_content' => strip_tags($request->comment_content),
        ]);

        $comment = new Comment();
        $comment->comment_content = $request->comment_content;
        $comment->post_id = $request->post_id;
        $comment->user_id = auth()->user()->id;
        $comment->created_at = now(config('app.timezone'));
        $comment->save();

        // Get the post owner
        $post = Post::find($request->post_id);
        $postOwner = $post->user;

        // Create a notification for the post owner
        notification::create([
            'user_id' => $postOwner->id,
            'title' => 'New Comment on Your Post',
            'message' => auth()->user()->name . ' commented on your post.',
            'type' => 'comment',
            'is_read' => false,
            'created_at' => now(config('app.timezone')),
        ]);

        return redirect()->route('community')->with('success', 'Comment added successfully!');
    }

    public function show(Comment $comment)
    {
        //
    }

    public function showCommentsByPost($postId)
    {
        $comments = comment::where('post_id', $postId)->get();
        return view('home', compact('comments'));
    }

    public function edit(Comment $comment)
    {
        return view('comment-edit', compact('comment'));

    }

    public function update(Request $request, Comment $comment)
    {
        $validator = Validator::make($request->all(), [
            'comment_content' => 'required|string',
            'post_id' => 'required|integer'
        ]);
        $validator->after(function ($validator) use ($request) {
            $request->merge([
                'comment_content' => strip_tags($request->comment_content),
            ]);
        });
        $comment->update($request->all());


        return redirect()->route('community');
    }

    public function destroy(Comment $comment)
    {

    $comment = comment::findOrFail($comment->id);
    // Check if the user is authorized to delete the comment
    if ($comment->user_id!= auth()->id()) {
        return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
    }

    // Delete the comment
    $comment->delete();

    // Redirect back to the post
    return redirect()->back()->with('success', 'Comment deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $comments = comment::where('comment_content', 'like', "%{$query}%")
            ->paginate(10);

        return view('admin.comments', compact('comments', 'query'));
    }
}
