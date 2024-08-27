<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required',
                'password' => 'required|min:8',
            ]);

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return back()->with('success', 'Login Berhasil');
            }

            return back()->with('error', 'Login Gagal');
        }

        return Inertia::render('Auth/Login');
    }

    public function logout()
    {
        auth()->logout();

        return back()->with('success', 'Logout Berhasil');
    }
}
