<?php

namespace App\Http\Controllers;

use App\Models\comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator ;
use Illuminate\Validation\Validator as ValidationValidator;

class adminController extends Controller
{

    public function showHome()
    {

        return view('admin.admin-home', compact('users', 'doctors'));
    }

    public function showPosts()
    {
        $posts= Post::all();
        return view('admin.posts', compact('posts'));
    }
    public function showComments()
    {
        $comments= comment::all();
        return view('admin.comments', compact('comments'));
    }
    public function showUsers()
    {
        $users= User::all();
        return view('admin.users', compact('users'));
    }

    public function showDoctors()
    {
    
        $users = user::where('status','pending')->get();
    
        return view('admin.doctor-requests', compact('users'));
    }
    public function showChangePasswordForm()
    {
        return view('admin.change-password-admin');
    }
    public function deletePost(Post $post)
    {
       
    $post = Post::findOrFail($post->id); 
    

    if ($post->attachments && file_exists(asset('storage/' . $post->attachments))) {
        unlink(asset('storage/'.  $post->attachments));
    }

    // Delete the doctor record
    $post->delete();
    return redirect()->back()->with('success', 'post deleted successfully.');
    }  


    public function deleteComment(comment $comment)
    {
       
    $comment = comment::findOrFail($comment->id); 
    $comment->delete();
    return redirect()->back()->with('success', 'comment deleted successfully.');
    }  


    public function deleteUser(Request $request, $id)
    {
    $user = User::findOrFail($id);
    if ($user->profile_picture && file_exists(asset('storage/' . $user->profile_picture))) {
        unlink('storage/'.  $user->profile_picture);
    }
    if ($user->medical_license && file_exists(asset('storage/' . $user->medical_license))) {
        unlink('storage/'.  $user->medical_license);
    }

    // Delete the doctor record
    $user->delete();
    return redirect()->back()->with('success', 'Doctor deleted successfully.');
    }


    public function showLoginForm()
    {
        return view('login');
    }
    public function showAddAdminForm()
    {
        return view('admin/add-admin');
    }
    public function edit(User $user)
    {
        return view('admin/admin-edit', compact('user'));

    }
    public function addAdmin(Request $request)
    {
        // Validate user input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|alpha_dash',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed|alpha_dash',
            'display_name' => 'required|string|max:20|alpha_dash',
            'gender'=> 'required|string',
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'permissions' => 'required|string|max:100|alpha_dash',


        ]);
        $validator->after(function ($validator) use ($request) {
            $request->merge([
                'name' => strip_tags($request->name),
                'email' => strip_tags($request->email),
                'password' => strip_tags($request->password),
                'display_name' => strip_tags($request->display_name),
                'permissions' => strip_tags($request->permissions),
            ]);
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            $profilePictureName = time() . '_' . $profilePicture->getClientOriginalName();
            $profilePicturePath = $profilePicture->storeAs('profile_pictures', $profilePictureName);
        }
        // Create a new user
        $admin = new user();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->display_name = $request->display_name;
        $admin->permissions = $request->permissions;
        $admin->gender = $request->gender;
        $admin->profile_picture = $profilePicturePath; 
        $admin->password = Hash::make($request->password);
        $admin->save();


        // Redirect after successful registration
        return redirect()->route('admin.home')->with('success', 'Registration successful. Please login.');
    }

    
    public function update(Request $request, User $user)
    {
        $validator= Validator::make($request->all() ,[
            'name' => 'required|string|max:255|alpha_dash',
            'email' => 'required|string|email|max:255|unique:users',
            'display_name' => 'required|string|max:20|alpha_dash',
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $validator->after(function ($validator) use ($request) {
            $request->merge([
                'name' => strip_tags($request->name),
                'email' => strip_tags($request->email),
                'display_name' => strip_tags($request->display_name),
            ]);
        });
        $user->update($request->except(['profile_picture']));
        if ($request->hasFile('profile_picture')) {
            // Remove the old picture from storage
            if ($user->profile_picture) {
                $oldImage = $user->profile_picture;
                unlink('../storage/app/public/'.$oldImage);}
                // Upload the new picture to storage
                $profilePicture = $request->file('profile_picture');
                $profilePictureName = time(). '_'. $profilePicture->getClientOriginalName();
                $profilePicturePath = $profilePicture->storeAs('profile_pictures', $profilePictureName,'public');
                $user->profile_picture = $profilePicturePath;
                $user->save();
                }
      
        
        
    
        return redirect()->route('home');    
    }



    public function approve(User $user)
    {
        $user->update(['status' => 'approved']);
    
        return redirect()->back()->with('success', 'User approved successfully.');
    }
    
    public function decline(User $user)
    {
        $user->update(['status' => 'declined']);
    
        return redirect()->back()->with('success', 'User declined successfully.');
    }
    
}
