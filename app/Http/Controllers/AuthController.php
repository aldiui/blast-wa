<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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

        return redirect()->route('login')->with('success', 'Logout Berhasil');
    }
}