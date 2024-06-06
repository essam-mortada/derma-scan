<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\comment;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function showHome(Request $request)
{
    if (Auth::check()) {
        $posts = Post::all();
        $comments = comment::all();
        $users = User::all();
        $user = Auth::user();
        
        if ($user->type == 'user' || $user->type == 'doctor') {
            return response()->json(['posts' => $posts, 'comments' => $comments, 'user' => $user]);
        } elseif ($user->type == 'admin') {
            return response()->json(['posts' => $posts, 'users' => $users]);
        } else {
            return response()->json(['message' => 'Invalid user type'], 403);
        }
    } else {
        return response()->json(['message' => 'User not authenticated'], 401);
    }
}

public function getAllData()
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
            $posts = Post::all();
            $comments = Comment::all();
            $users = User::all();
            $user = Auth::user();

            return response()->json([
                'data'=>
                [
                'posts' => $posts,
                'comments' => $comments,
                'users' => $users,
                'authenticated_user' => $user,
                ]
            ]);
        
           
    }


    public function index()
    {
        
        // Retrieve all users
        $users = User::all();
        $users->map(function($user) {
            $user->profile_picture_url = $user->profile_picture 
            ? asset('storage/' . $user->profile_picture) 
            : null;
            return $user;
        });
        return response()->json($users);
    }

    public function show(User $user)
    {
        $auth_user = Auth::guard('api')->user();

        if (!$auth_user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        $user->profile_picture_url = $user->profile_picture 
                ? asset('storage/' . $user->profile_picture) 
                : null;
        // Return the specified user as JSON response
        return response()->json($user);
    }

    public function store(Request $request)
    {
        // Validate user input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|alpha_dash',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed|alpha_dash',
            'display_name' => 'required|string|max:20|alpha_dash',
            'gender'=> 'required|string',
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'type'=> 'in:user,admin,doctor|required|string|alpha_dash',
            'medical_license' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);
        $validator->after(function ($validator) use ($request) {
            $request->merge([
                'name' => strip_tags($request->name),
                'email' => strip_tags($request->email),
                'password' => strip_tags($request->password),
                'display_name' => strip_tags($request->display_name),
            ]);
        });
        if ($validator->fails()) {
            // Get all the error messages as an array
            $errors = $validator->errors()->all();
            
            // Join the error messages into a single string, separated by commas (or any other separator you prefer)
            $errorMessage = implode(', ', $errors);
            
            // Return the single error message
            return response()->json(['message' => $errorMessage], 400);
        }
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            $profilePictureName = time() . '_' . $profilePicture->getClientOriginalName();
            $profilePicturePath = $profilePicture->storeAs('profile_pictures', $profilePictureName,'public');
        }
        $medicalLicensePath = null;
        if ($request->hasFile('medical_license')) {
            $medicalLicense = $request->file('medical_license');
            $medicalLicenseName = time() . '_' . $medicalLicense->getClientOriginalName();
            $medicalLicensePath = $medicalLicense->storeAs('medical_licenses', $medicalLicenseName,'public');
        }

        // Create a new user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->display_name = $request->display_name;
        $user->gender = $request->gender;
        $user->profile_picture = $profilePicturePath;
        $user->medical_license= $medicalLicensePath;
        $user-> type= $request ->type;
        if ($request->hasFile('medical_license')) {
        $user->status='pending';
        }
        $user->password = Hash::make($request->password);
        $user->api_token =null;

        $user->save();
        // Return success message as JSON response
        return response()->json(['message' => 'User created successfully'], 201);
    }


    public function changePassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            // Get all the error messages as an array
            $errors = $validator->errors()->all();
            
            // Join the error messages into a single string, separated by commas (or any other separator you prefer)
            $errorMessage = implode(', ', $errors);
            
            // Return the single error message
            return response()->json(['message' => $errorMessage], 400);
        }

        $user = User::find($user->id);
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }

    
    public function update(Request $request, User $user)
    {
       if (Auth::guard('api')->user()->id!= $user->id) {
        return response()->json(["message"=>'you are not authenticated']);
       }
        $validator= Validator::make($request->all() ,[
            'name' => 'string|max:255|',
            'email' => 'string|email|max:255|exists:users',
            'display_name' => 'string|max:20|alpha_dash',
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $validator->after(function ($validator) use ($request) {
            $request->merge([
                'name' => strip_tags($request->name),
                'email' => strip_tags($request->email),
                'display_name' => strip_tags($request->display_name),
            ]);
        });
        if ($validator->fails()) {
            // Get all the error messages as an array
            $errors = $validator->errors()->all();
            
            // Join the error messages into a single string, separated by commas (or any other separator you prefer)
            $errorMessage = implode(', ', $errors);
            
            // Return the single error message
            return response()->json(['message' => $errorMessage], 400);
        }

         $user->update($request->except(['profile_picture']));
        if ($request->hasFile('profile_picture')) {
            // Remove the old picture from storage
            if ($user->profile_picture) {
                $oldImage = $user->profile_picture;
                unlink('.../storage/app/public/'.$oldImage);
                // Upload the new picture to storage
                $profilePicture = $request->file('profile_picture');
                $profilePictureName = time(). '_'. $profilePicture->getClientOriginalName();
                $profilePicturePath = $profilePicture->storeAs('profile_pictures', $profilePictureName,'public');
                $user->profile_picture = $profilePicturePath;
                $user->save();
            }

        // Return success message as JSON response
    }
    return response()->json(['message' => 'User updated successfully']);

}

    


public function login(Request $request)
{
    // Validate the login request
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    // Attempt to authenticate the user
    $credentials = $request->only('email', 'password');
    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Get the authenticated user
    $user = Auth::user();
    $newApiToken = Str::random(60);
    $user->api_token = $newApiToken;
    $user->save(); 
    
    // Return the user's information along with a success message
    return response()->json([
        'message' => 'Login successful',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'display_name' => $user->display_name,
            'profile_picture' => $user->profile_picture,
            'gender' => $user->gender,
            'type' => $user->type,
            'api_token'=>$user->api_token
        ],
    ], 200);
}



    public function destroy(Request $request, $id)
    {
        // Check if the user is authorized to delete the user
      /*  $user= Auth::guard('api')->user();
        if ($user->id != $id) {
            return response()->json(['message' => 'you are Unauthorized to delete this account'], 403);
        }*/
        $user = User::findOrFail($id);
        if ($user->profile_picture && file_exists(asset('storage/' . $user->profile_picture))) {
            unlink('.../storage/app/public/'.  $user->profile_picture);
        }
        if ($user->medical_license && file_exists(asset('storage/' . $user->medical_license))) {
            unlink('.../storage/app/public/'.  $user->medical_license);
        }

        // Delete the doctor record
        $user->delete();
        return response()->json(['message' => ' account deleted succesfully'], 401);
    }

    public function getUserPosts(User $user)
    {
        // Retrieve posts for the specific user
        $posts = Post::where('user_id', $user->id)->get();
        $posts->map(function($post) {
            $post->image_url = $post->attachments ? asset('storage/' . $post->attachments) : null;
            $post->user->profile_picture_url = $post->user->profile_picture 
            ? asset('storage/' . $post->user->profile_picture) 
            : null;
            return $post;
        });

    return response()->json(['data'=>$posts]);
    }


    public function logout(Request $request)
    {
        $user= Auth::guard('api')->user();
        // Revoke the token that was used to authenticate the current request
        if ($user) {
            $user->api_token = null;
            $user->save();
            return response()->json(['message' => 'Logout successful'], 200);

            }else{
        

        // Return a success response
        return response()->json(['message' => 'you are not authenticated'], 200);
        }
    }
   
}
