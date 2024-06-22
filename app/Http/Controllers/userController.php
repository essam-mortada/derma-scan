<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\comment;
use App\Models\Post;
use App\Models\User;
use App\Models\notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class userController extends Controller
{
    public function showHome()
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Fetch unread notifications for the authenticated user
        $notifications = Notification::where('user_id', Auth::id())
            ->unread()
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch posts, comments, and users
        $posts = Post::orderBy('created_at', 'desc')->get();
        $comments = Comment::all();
        $users = User::all();
        $user = Auth::user();

        // Pass notifications to the view based on user type
        if ($user->type == 'user' || $user->type == 'doctor') {
            return view('welcome', compact('posts', 'comments', 'user', 'notifications'));
        } elseif ($user->type == 'admin') {
            return view('admin.admin-home', compact('posts', 'users', 'notifications'));
        }

        return redirect()->route('login');
    }

    public function showCommunity()
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Fetch unread notifications for the authenticated user
        $notifications = Notification::where('user_id', Auth::id())
        ->where('is_read', 0)
        ->orderBy('created_at', 'desc')
        ->get();

        // Fetch posts, comments, and users
        $posts = Post::orderBy('created_at', 'desc')->get();
        $comments = Comment::all();
        $users = User::all();
        $user = Auth::user();

        return view('community', compact('posts', 'comments', 'user', 'notifications'));
    }
    /**
     * Display the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('register');
    }
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle user registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validate user input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|alpha_dash',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed|alpha_dash|unique:users',
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
            return redirect()->back()->withErrors($validator)->withInput();
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
        $user->api_token = Str::random(60);
        $user->save();


        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            return redirect()->intended('home');
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }


    public function show(User $user)
{
    // Retrieve the user
    $user = User::find($user->id);

    // Check if the user exists
    if (!$user) {
        return redirect()->route('home')->with('error', 'User not found.');
    }

    $userImage = null;
    if ($user->profile_picture) {
        $userImage = Storage::url($user->profile_picture);
    }
    // Retrieve the user's posts
    $posts = $user->posts;

    // Retrieve the user's comments
    $comments = $user->comments;

    // Pass the user, posts, and comments to the view
    return view('profile', compact('user', 'posts', 'comments'));
}

    public function edit(User $user)
    {
        return view('profile-edit', compact('user'));

    }

    public function update(Request $request, User $user)
    {
        $validator= Validator::make($request->all() ,[
            'name' => 'required|string|max:255|alpha_dash',
            'email' => 'required|string|email|max:255|exists:users',
            'display_name' => 'required|string|max:20|alpha_dash',
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








        return redirect()->route('users.show',$user);
    }




    public function search(Request $request)
    {
        $query = $request->input('query');

        $users = user::where('display_name', 'like', "%{$query}%")
            ->paginate(10);

        return view('admin.users', compact('users', 'query'));
    }


    public function showChangePasswordForm(User $user)
    {
        return view('change-password',compact('user'));
    }

    public function changePassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('password.change.form',$user->id)
                ->withErrors($validator)
                ->withInput();
        }


        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('password.change',$user->id)
                ->withErrors(['current_password' => 'Current password is incorrect'])
                ->withInput();
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('password.change.form',$user->id)->with('status', 'Password changed successfully');
    }



}


