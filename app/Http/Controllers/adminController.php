<?php

namespace App\Http\Controllers;

use App\Models\admin;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator ;
use Illuminate\Validation\Validator as ValidationValidator;

class adminController extends Controller
{

    public function showHome()
    {
        $users = User::all();

        return view('admin.admin-home', compact('users', 'doctors'));
    }




    public function deleteUser(Request $request, $id)
    {
    $user = User::findOrFail($id);
    if ($user->profile_picture && file_exists(storage_path('../storage/app/' . $user->profile_picture))) {
        unlink('../storage/app/'.  $user->profile_picture);
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

    


    
}
