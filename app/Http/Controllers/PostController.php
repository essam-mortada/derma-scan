<?php

namespace App\Http\Controllers;

use App\Models\comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;

use function Symfony\Component\Clock\now;

class PostController extends Controller
{
    /**
     * Display a listing of the post.
     */
    public function index()
    {
        $posts = Post::all();
        $comments = [];
        foreach ($posts as $post) {
            $comments[$post->id] = $post->comments; // Assuming you have defined the relationship in the Post model
        }
        return view('home', compact('posts', 'comments'));
    }
    
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
        $postPicturePath = null;
        if ($request->hasFile('attachments')) {
            $postPicture = $request->file('attachments');
            $postPictureName = time() . '_' . $postPicture->getClientOriginalName();
            $postPicturePath = $postPicture->storeAs('post_pictures', $postPictureName);
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

    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        //
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
        $request->validate($request, [
            'post_text' => 'required|string',
            'attachments' => 'nullable|string',
            'privacy' => 'required|string',
            'post_type' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|time',
        ]);
    
        $post->update($request->all());
    
        return redirect()->route('posts.index');    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        
    $post = Post::findOrFail($post->id); 
    if ($post->attachments && file_exists(storage_path('../storage/app/' . $post->attachments))) {
        unlink('../storage/app/'.  $post->attachments);
    }

    // Delete the doctor record
    $post->delete();
    return redirect()->back()->with('success', 'post deleted successfully.');
    }  
  }

