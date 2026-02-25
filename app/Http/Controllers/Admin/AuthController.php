<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminLoginOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

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
        $request->validate([
            'email'    => 'required|email|max:255',
            'password' => 'required|string|max:255',
        ]);

        // Brute-force protection: max 5 attempts per 15 minutes keyed by email+IP
        $throttleKey = 'admin-login:' . strtolower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            Log::warning('Admin login locked out', ['ip' => $request->ip(), 'email' => $request->email]);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->onlyInput('email');
        }

        // Validate credentials but DO NOT complete login yet — enforce OTP next
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            Auth::logout(); // Log out immediately; user must pass OTP
            RateLimiter::clear($throttleKey);

            $user = \App\Models\User::where('email', $request->email)->first();

            // Cryptographically secure random 6-digit OTP
            $otp = random_int(100000, 999999);

            session([
                'admin_otp'            => $otp,
                'admin_otp_user_id'    => $user->id,
                'admin_otp_expires_at' => now()->addMinutes(5),
                'admin_otp_attempts'   => 0,
            ]);

            $recipient = env('ADMIN_OTP_EMAIL', $user->email);
            Mail::to($recipient)->send(new AdminLoginOtp((string) $otp));

            return redirect()->route('admin.otp');
        }

        // Increment brute-force counter, auto-decay after 15 minutes
        RateLimiter::hit($throttleKey, 900);
        Log::warning('Admin login failed', ['ip' => $request->ip(), 'email' => $request->email]);

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
        $request->validate(['otp' => 'required|digits:6']);

        if (!session()->has('admin_otp')) {
            return redirect()->route('admin.login')->withErrors(['email' => 'Session expired. Please login again.']);
        }

        if (now()->greaterThan(session('admin_otp_expires_at'))) {
            session()->forget(['admin_otp', 'admin_otp_user_id', 'admin_otp_expires_at', 'admin_otp_attempts']);
            return redirect()->route('admin.login')->withErrors(['email' => 'OTP expired. Please login again.']);
        }

        // Lock out after 3 failed OTP attempts to prevent brute-force
        $attempts = session('admin_otp_attempts', 0);
        if ($attempts >= 3) {
            session()->forget(['admin_otp', 'admin_otp_user_id', 'admin_otp_expires_at', 'admin_otp_attempts']);
            Log::warning('OTP max attempts exceeded', ['ip' => $request->ip()]);
            return redirect()->route('admin.login')->withErrors(['email' => 'Too many failed OTP attempts. Please login again.']);
        }

        // Constant-time string comparison prevents timing-based side-channel attacks
        if (hash_equals((string) session('admin_otp'), (string) $request->otp)) {
            $userId = session('admin_otp_user_id');
            Auth::loginUsingId($userId);
            session()->forget(['admin_otp', 'admin_otp_user_id', 'admin_otp_expires_at', 'admin_otp_attempts']);
            $request->session()->regenerate();
            return redirect()->intended(route('admin.posts.index'));
        }

        session(['admin_otp_attempts' => $attempts + 1]);
        Log::warning('OTP failed attempt', ['ip' => $request->ip(), 'attempt' => $attempts + 1]);

        $remaining = 2 - $attempts;
        return back()->withErrors(['otp' => "Invalid OTP code. {$remaining} attempt(s) remaining."]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('blog.index');
    }
}
