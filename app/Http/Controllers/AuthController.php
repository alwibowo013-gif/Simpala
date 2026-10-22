<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input: nis_nip wajib diisi
        $credentials = $request->validate([
            'nis_nip' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
    return redirect()->intended('/admin/dashboard')
                     ->with('login_success', 'Selamat datang kembali, ' . Auth::user()->name . '! Anda berhasil masuk ke Panel Kontrol Admin SIMPALA.');
}

// TAMBAHAN UNTUK SISWA/USER BIASA
return redirect()->intended('/dashboard')
                 ->with('login_success', 'Halo, ' . Auth::user()->name . '! Selamat datang di Portal Peminjaman Alat SIMPALA.');

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'nis_nip' => 'NIS/NIP atau password yang dimasukkan salah.',
        ])->onlyInput('nis_nip');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}