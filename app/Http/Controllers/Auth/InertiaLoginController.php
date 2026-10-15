<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InertiaLoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            return redirect()->to($role === 'admin' ? '/admin/dashboard' : '/member/dashboard');
        }
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            // Revoke previous tokens to keep it clean, then generate new Sanctum token
            $user->tokens()->delete();
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'redirect' => $user->role === 'admin' ? '/admin/dashboard' : '/member/dashboard'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah.'
        ], 422);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to('/login');
    }
}
