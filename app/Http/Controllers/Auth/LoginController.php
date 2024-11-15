<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    
    public function index()
    {
        return view('pages.user-pages.login');
    }

    public function authenticate(Request $request)
    {
        // Validate the incoming request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            // 'g-recaptcha-response' => 'required|captcha', // Validate CAPTCHA field
        ]);

        // Attempt to log the user in
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Regenerate the session to prevent session fixation attacks
            $request->session()->regenerate();

            // Redirect based on user role
            if (auth()->user()->role == 'visitor') {
                return redirect()->intended('/portal');
            } else {
                return redirect()->intended('/dashboard');
            }
        }

        // If authentication fails, redirect back with an error message
        return redirect()->back()->with('error', 'Email or password do not match our records!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

}