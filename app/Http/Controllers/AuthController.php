<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Show the login page
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Process the login attempt
    public function login(Request $request)
    {
        // Check if the user actually typed an email and password
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt to log them in
        if (Auth::attempt($credentials)) {
            // Success! Generate a secure session token to prevent fixation attacks
            $request->session()->regenerate();
            
            // Send them to the admin panel
            return redirect()->intended('/admin');
        }

        // Failure! Kick them back to the login page with an error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // 3. Log the user out
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Destroy the secure session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}