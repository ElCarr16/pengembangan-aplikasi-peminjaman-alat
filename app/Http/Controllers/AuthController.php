<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // menampilkan form login
    public function showLoginForm(Request $request)
    {
        return view('auth.login');
    }

    // proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials))
        {
            $request->session()->regenerate();
            // redirect berdasarkan role
            if (Auth::check() &&  Auth::user()->role == "admin");
            {
                return redirect('/admin/dashboard');
            }
            if (Auth::check() && Auth::user()->role == "petugas");
            {
                return redirect('/petugas/dashboard');
            }
            if (Auth::check() && Auth::user()->role == "peminjam");
            {
                return redirect('/pemnjam/dashboard');
            }
        }
    }
}
