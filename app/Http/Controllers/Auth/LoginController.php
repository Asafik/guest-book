<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Dapatkan nama user untuk pesan yang lebih personal
            $userName = Auth::user()->name;
            $greeting = now()->format('H') < 12 ? 'Selamat pagi' : (now()->format('H') < 15 ? 'Selamat siang' : 'Selamat sore');

            return redirect()->intended('/dashboard')
                ->with('login_success', true)
                ->with('welcome_message', "{$greeting}, {$userName}! Selamat datang di dashboard.");
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Berhasil keluar. Sampai jumpa!');
    }
}
