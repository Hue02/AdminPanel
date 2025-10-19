<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Admin;
use App\Models\User;
use App\Models\Trivia;
use App\Models\UserProgress;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find user by email
        $user = Admin::where('email', $request->email)->first();

        // Check if user exists and password matches
        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->route('auth.login')->with('error', 'Invalid credentials. Please try again.');
        }

        // Authenticate user in the 'admin' guard
        Auth::guard('admin')->login($user);

        // Redirect based on role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, Admin!');
        } elseif ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard')->with('success', 'Welcome back, Teacher!');
        }

        // Unauthorized role
        return redirect()->route('auth.login')->with('error', 'Unauthorized role. Access denied.');
    }




    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('auth.login');
    }
}
