<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Redirect if already logged in
        if (Auth::check()) {
            return redirect(route('dashboard'));
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by username
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            Log::warning('Login failed: Username not found', ['username' => $request->username]);
            return back()->withInput($request->only('username', 'remember'))
                ->withErrors(['username' => 'Username tidak ditemukan.']);
        }

        // Check password
        if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            Log::warning('Login failed: Invalid password', ['username' => $request->username, 'user_id' => $user->id]);
            return back()->withInput($request->only('username', 'remember'))
                ->withErrors(['password' => 'Password salah.']);
        }

        // Login user - this will set the session
        Auth::login($user, $request->filled('remember'));
        
        Log::info('User logged in successfully', ['user_id' => $user->id, 'username' => $user->username]);

        // Regenerate session to prevent session fixation attacks
        $request->session()->regenerate();
        
        Log::info('Session regenerated', ['user_id' => Auth::id()]);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Log::info('User logged out', ['user_id' => Auth::id()]);
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        return redirect('/');
    }
}

