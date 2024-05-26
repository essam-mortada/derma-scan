<?php

namespace App\Http\Controllers;

use App\Models\comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use function Symfony\Component\Clock\now;

class PostController extends Controller
{
    /**
     * Display a listing of the post.
     */
    
    
    public function showCommentsByPost($postId)
    {
        return Comment::where('post_id', $postId)->get();
    
    }
    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * 
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $validator= Validator::make($request->all() ,[
            'user_id' => 'nullable|integer',
            'post_text' => 'required|string|alpha_dash',
            'attachments' => 'nullable|string|alpha_dash',
            'privacy' => 'required|nullable|string|alpha_dash',
            'post_type' => 'required|nullable|string|alpha_dash',
            
        ]);
        $validator->after(function ($validator) use ($request) {
            $request->merge([
                'user_id' => strip_tags($request->user_id),
                'post_text' => strip_tags($request->post_text),
                'attachments' => strip_tags($request->attachments),
                'post_type' => strip_tags($request->post_type),
                'privacy' => strip_tags($request->post_type),
            ]);
        });
        $postPicturePath = null;
        if ($request->hasFile('attachments')) {
            $postPicture = $request->file('attachments');
            $postPictureName = time() . '_' . $postPicture->getClientOriginalName();
            $postPicturePath = $postPicture->storeAs('post_pictures', $postPictureName,'public');
        }
        $post = new Post();
        $post->user_id = auth()->user()->id ?? $request->user_id; 
        $post->post_text = $request->post_text;
        $post->post_type = $request->post_type;
        $post->privacy = $request->privacy;
        $post->attachments = $postPicturePath;
        $post->created_at = Date::now(config('app.timezone'))->format('Y-m-d H:i:s');
 
        $post->save();
    
        return redirect()->route('home');    
    }

    public function upvote(Request $request, $postId)
    {
        $post = Post::find($postId);
        $post->upvotes++;
        $post->save();

        return redirect()->back()->with('success', 'Post upvoted successfully.');
    }

    public function downvote(Request $request, $postId)
    {
        $post = Post::find($postId);
        $post->downvotes++;
        $post->save();

        return redirect()->back()->with('success', 'Post downvoted successfully.');
    }


    /**
     * Display the specified post.
     */
    public function show(Post $post)
{
    // Retrieve the post
    $post = Post::find($post->id);

    // Check if the post exists
    if (!$post) {
        return redirect()->route('home')->with('error', 'Post not found.');
    }
    $postImage = null;
    if ($post->attachments) {
        $postImage = Storage::url($post->attachments);
    }
    // Retrieve the comments for the post
    $comments = $post->comments;

    // Pass the post and comments to the view
    return view('posts.show', compact('post', 'comments','postImage'));
}

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));

    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validator= Validator::make($request->all() ,[
            'post_text' => 'required|string',
            'attachments' => 'nullable|string',
            'privacy' => 'required|string',
           
        ]);
        $validator->after(function ($validator) use ($request) {
            $request->merge([
                'post_text' => strip_tags($request->post_text),
                'attachments' => strip_tags($request->attachments),
                'privacy' => strip_tags($request->post_type),
            ]);
        });
        $post->update($request->except(['attachments']));

    if ($request->hasFile('attachments')) {
        $oldAttachment = $post->attachments;
        unlink('../storage/app/public/'.$oldAttachment);
        $postPictureName = time() . '_' . $request->file('attachments')->getClientOriginalName();
        $postPicturePath = $request->file('attachments')->storeAs('post_pictures', $postPictureName,'public');

        $post->attachments = $postPicturePath;
        $post->save();
       
    }

        return redirect()->route('home');    
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
       
    $post = Post::findOrFail($post->id); 
    if ($post->user_id!= auth()->id()) {
        return redirect()->back()->with('error', 'You are not authorized to delete this post.');
    }

    if ($post->attachments && file_exists(storage_path('../storage/app/' . $post->attachments))) {
        unlink('../storage/app/public'.  $post->attachments);
    }

    // Delete the doctor record
    $post->delete();
    return redirect()->back()->with('success', 'post deleted successfully.');
    } 
    
    
    public function search(Request $request)
    {
        $query = $request->input('query');
    
        $posts = Post::where('post_text', 'like', "%{$query}%")
            ->paginate(10);
    
        return view('admin.posts', compact('posts', 'query'));
    }
  }

