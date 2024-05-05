<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\comment;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\User;
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
        if (Auth::check()) {
            $posts = Post::all();
            $comments = Comment::all();
            $users = User::all();
            $user = Auth::user();

            return response()->json([
                'posts' => $posts,
                'comments' => $comments,
                'users' => $users,
                'authenticated_user' => $user,
            ]);
        } else {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
    }


    public function index()
    {
        // Retrieve all users
        $users = User::all();

        // Return users as JSON response
        return response()->json($users);
    }

    public function show(User $user)
    {
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
            return response()->json(['errors' => $validator->errors()], 400);
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
        $user->save();
        // Return success message as JSON response
        return response()->json(['message' => 'User created successfully'], 201);
    }

    public function update(Request $request, User $user)
    {
        $validator= Validator::make($request->all() ,[
            'name' => 'required|string|max:255|',
            'email' => 'required|string|email|max:255|exists:users',
            'password' => 'required|string|min:8|confirmed|alpha_dash',
            'display_name' => 'required|string|max:20|alpha_dash',
            'gender'=> 'required|string',
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $validator->after(function ($validator) use ($request) {
            $request->merge([
                'name' => strip_tags($request->name),
                'email' => strip_tags($request->email),
                'password' => strip_tags($request->password),
                'display_name' => strip_tags($request->display_name),
            ]);
        });
        $user->update($request->all());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Update the user
        if ($request->hasFile('profile_picture')) {
            // Remove the old picture from storage
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture);
            }
    
            $profilePicture = $request->file('profile_picture');
            $profilePictureName = time(). '_'. $profilePicture->getClientOriginalName();
            $profilePicturePath = $profilePicture->storeAs('profile_pictures', $profilePictureName,'public');
            $user->profile_picture = $profilePicturePath;
            $user->save();
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Return success message as JSON response
        return response()->json(['message' => 'User updated successfully']);
    }

    


    public function login(Request $request)
    {
    // Validate user input
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    // Attempt to authenticate the user
    if (Auth::attempt($request->only('email', 'password'))) {
        // Authentication successful, return the authenticated user
        return response()->json(['user' => Auth::user()]);
    }

    // Authentication failed, return error message
    return response()->json(['message' => 'Invalid credentials'], 401);
    }

}
