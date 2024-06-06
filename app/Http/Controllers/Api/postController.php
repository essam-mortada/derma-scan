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
    // Eager load the user and comments relationships
    $posts = Post::with('user') // Assuming a user relationship exists
                ->withCount('comments') // Assuming a comments relationship exists
                ->get()
                ->map(function($post) {
                    $post->image_url = $post->attachments ? asset('storage/' . $post->attachments) : null;
                    $post->user->profile_picture_url = $post->user->profile_picture 
                    ? asset('storage/' . $post->user->profile_picture) 
                    : null;
                    return $post;
                });

    return response()->json(['data'=>$posts]);
}


    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        // Eager load the user and comments relationships
        $post->load('user', 'comments.user');
    
        // Format the post data
        $post->image_url = $post->attachments ? asset('storage/' . $post->attachments) : null;
        if ($post->user) {
            $post->user->profile_picture_url = $post->user->profile_picture 
                ? asset('storage/' . $post->user->profile_picture) 
                : null;
        }
    
        // Format the comments data and add comments count
        $comments = $post->comments->map(function ($comment) {
            if ($comment->user) {
                $comment->user->profile_picture_url = $comment->user->profile_picture 
                    ? asset('storage/' . $comment->user->profile_picture) 
                    : null;
            }
            return $comment;
        });
        $comments_count = $comments->count();
    
        return response()->json([
            'post' => [
                'id' => $post->id,
                'user' => $post->user,
                'post_text' => $post->post_text,
                'attachments' => $post->attachments,
                'image_url' => $post->image_url,
                'privacy' => $post->privacy,
                'post_type' => $post->post_type,
                'upvotes' => $post->upvotes,
                'downvotes' => $post->downvotes,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
                'comments_count' => $comments_count,
            ],
            'comments' => $comments,
        ]);
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
            'privacy' => 'string',
            'post_type' => 'string',

        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Get all the error messages as an array
            $errors = $validator->errors()->all();
            
            // Join the error messages into a single string, separated by commas (or any other separator you prefer)
            $errorMessage = implode(', ', $errors);
            
            // Return the single error message
            return response()->json(['message' => $errorMessage], 400);
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
        $post->privacy = "public";
        $post->post_type = "article";
        $post->save();

        // Return a success response
        return response()->json(['message' => 'Post created successfully',"user"=>$post->user], 201);
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
            'post_text' => 'string',
            'attachments' => 'nullable',
            'privacy' => 'string',
            'post_type' => 'string',
        ]);

        if ($validator->fails()) {
            // Get all the error messages as an array
            $errors = $validator->errors()->all();
            
            // Join the error messages into a single string, separated by commas (or any other separator you prefer)
            $errorMessage = implode(', ', $errors);
            
            // Return the single error message
            return response()->json(['message' => $errorMessage], 400);
        }
        // Update the post
        $post->update($request->except(['attachments']));

        if ($request->hasFile('attachments')) {
            $oldAttachment = $post->attachments;
            unlink('.../storage/app/public/'.$oldAttachment);
            $postPictureName = time() . '_' . $request->file('attachments')->getClientOriginalName();
            $postPicturePath = $request->file('attachments')->storeAs('post_pictures', $postPictureName,'public');
    
            $post->attachments = $postPicturePath;
            $post->save();
           
        }
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
        $post->downvotes--;
        $post->save();

        return response()->json(['message' => 'Post upvoted successfully']);
    }

    /**
     * Downvote the specified post.
     */
    public function downvote(Post $post)
    {
        $post->downvotes++;
        $post->upvotes--;
        $post->save();

        return response()->json(['message' => 'Post downvoted successfully']);
    }

    public function showCommentsByPost($postId)
    {
        $comments = comment::with(['user', 'post'])->where('post_id', $postId)->get();

        $comments = $comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'comment_content' => $comment->comment_content,
                'created_at' => $comment->created_at,
                'updated_at' => $comment->updated_at,
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                    'email' => $comment->user->email,
                    'display_name' => $comment->user->display_name,
                    'profile_picture' => $comment->user->profile_picture,
                    'profile_picture_url' =>$comment->user->profile_picture 
                    ? asset('storage/' . $comment->user->profile_picture)
                    : null,
                                        
                ],
            ];
        });
        // Return comments as JSON response
        return response()->json(['data'=>$comments]);
    }


    public function search(Request $request)
{
    // Validate the request
    $validator = Validator::make($request->all(), [
        'query' => 'required|string'
    ]);

    if ($validator->fails()) {
        // Get all the error messages as an array
        $errors = $validator->errors()->all();
        
        // Join the error messages into a single string, separated by commas (or any other separator you prefer)
        $errorMessage = implode(', ', $errors);
        
        // Return the single error message
        return response()->json(['message' => $errorMessage], 400);
    }

    // Get the query parameter
    $query = $request->input('query');

    // Perform the search with pagination
    $posts = Post::where('post_text', 'like', "%{$query}%")
        ->paginate(10);

    // Include the user and comments count in the response
    $posts->load('user', 'comments', 'comments.user');

    $posts = $posts->map(function ($post) {
        $post->image_url = $post->attachments ? asset('storage/' . $post->attachments) : null;
        $post->user->profile_picture_url = $post->user->profile_picture 
                                             ? asset('storage/' . $post->user->profile_picture) 
                                             : null;
    
        $post->comments->each(function ($comment) {
            $comment->user->profile_picture_url = $comment->user->profile_picture 
                                                    ? asset('storage/' . $comment->user->profile_picture) 
                                                    : null;
        });
    
        return $post;
    });
    
    return response()->json([
        'message' => 'Search results retrieved successfully',
        'data' => [
            'posts' => $posts
        ]
    ], 200);
    
}

}
