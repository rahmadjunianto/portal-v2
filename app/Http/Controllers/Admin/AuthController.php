<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show admin login form.
     */
    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email atau username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Try to find user by email or username
        $user = \App\Models\User::where('email', $credentials['email'])
            ->orWhere('username', $credentials['email'])
            ->first();

        if ($user) {
            // Check if user is active
            if (!$user->is_active) {
                return back()->withInput()->with('error', 'Akun Anda tidak aktif. Hubungi administrator.');
            }

            // Attempt login with plain password (old database password)
            if (Auth::attempt(['email' => $user->email, 'password' => $credentials['password']], $request->boolean('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang, ' . auth()->user()->name . '!');
            }
        }

        return back()->withInput()->with('error', 'Email/Username atau password salah.');
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah keluar dari sistem.');
    }
}
