<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location as FacadesLocation;

class userController extends Controller
{
    public function showHome()
{
    if (Auth::check()) {
        $posts = Post::all();
        $comments = comment::all();
        $users = User::all();
        $user = Auth::user();
        if ($user->type == 'user') {
            return view('home',compact('posts','comments'));
        } elseif ($user->type == 'doctor') {
            return view('home',compact('posts','comments'));
        } elseif ($user->type == 'admin') {
            return view('admin.admin-home',compact('posts','users'));
        } 
        
        else{
            return redirect()->route('login');
        }
    } else {
        return redirect()->route('login');
    }
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            $profilePictureName = time() . '_' . $profilePicture->getClientOriginalName();
            $profilePicturePath = $profilePicture->storeAs('profile_pictures', $profilePictureName);
        }
        $medicalLicensePath = null;
        if ($request->hasFile('medical_license')) {
            $medicalLicense = $request->file('medical_license');
            $medicalLicenseName = time() . '_' . $medicalLicense->getClientOriginalName();
            $medicalLicensePath = $medicalLicense->storeAs('medical_licenses', $medicalLicenseName);
        }

        $userIp = $request->ip();
        
            $data = FacadesLocation::get($userIp);                
            $location = $data->countryName.",".$data->cityName;
        
        
        // Create a new user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->display_name = $request->display_name;
        $user->gender = $request->gender;
        $user->profile_picture = $profilePicturePath;
        $user->medical_license= $medicalLicensePath;
        $user-> type= $request ->type;
        $user->location = $location;
        $user->password = Hash::make($request->password);
        $user->save();

        // You can add additional actions here such as sending email verification, assigning roles, etc.

        // Redirect after successful registration
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
}


