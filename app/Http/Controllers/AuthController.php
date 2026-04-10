<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Login gagal.']);
        }

        $request->session()->regenerate();

        // Log activity (cukup sekali)
        ActivityLog::record('Login', 'Pengguna melakukan login');

        // Redirect berdasarkan role
        return match (Auth::user()->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            default   => redirect()->route('peminjam.dashboard'),
        };
        return redirect($redirect)->with('Login', 'Selamat datang ' . $user->name);
    }
    //Menampilkan halaman Register(Daftar akun khusus)
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    //
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'peminjam', // Default role for self-registration
        ]);

        Auth::login($user);

        ActivityLog::record('Register', 'Pengguna baru mendaftar sebagai peminjam');

        return redirect('/peminjam/dashboard')->with('success', 'Selamat Datang Pengguna Baru');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('message', 'Anda telah logout berhasil');
    }
}
