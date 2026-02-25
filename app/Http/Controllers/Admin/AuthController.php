<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminLoginOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.posts.index');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Validate credentials but DO NOT log in yet
        if (Auth::attempt($credentials)) { // Auth::attempt logs in automatically
             Auth::logout(); // Log out immediately to enforce OTP
             
             // Get the user ID (we know credentials are valid)
             $user = \App\Models\User::where('email', $request->email)->first();

             // Generate OTP
             $otp = rand(100000, 999999);
             
             // Store in session
             session([
                 'admin_otp' => $otp,
                 'admin_otp_user_id' => $user->id,
                 'admin_otp_expires_at' => now()->addMinutes(5)
             ]);

             // Send OTP
             $recipient = env('ADMIN_OTP_EMAIL', $user->email); // Use env or fallback to user email
             Mail::to($recipient)->send(new AdminLoginOtp((string)$otp));

             return redirect()->route('admin.otp');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showOtp()
    {
        if (!session()->has('admin_otp')) {
            return redirect()->route('admin.login');
        }
        return view('admin.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        if (!session()->has('admin_otp')) {
             return redirect()->route('admin.login')->withErrors(['email' => 'Session expired. Please login again.']);
        }

        if (now()->greaterThan(session('admin_otp_expires_at'))) {
            return redirect()->route('admin.login')->withErrors(['email' => 'OTP expired. Please login again.']);
        }

        if ($request->otp == session('admin_otp')) {
            // Success
            $userId = session('admin_otp_user_id');
            Auth::loginUsingId($userId);
            session()->forget(['admin_otp', 'admin_otp_user_id', 'admin_otp_expires_at']);
            
            $request->session()->regenerate();
            return redirect()->intended(route('admin.posts.index'));
        }

        return back()->withErrors(['otp' => 'Invalid OTP code.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('blog.index');
    }
}
