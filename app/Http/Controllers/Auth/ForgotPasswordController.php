<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\SendOtpMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Hash, Mail, Session};

class ForgotPasswordController extends Controller
{

    public function index()
    {
        return view('auth.forgot-password.index');
    }

    public function checkAccount(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) return back()->withErrors(['email' => 'Email tidak terdaftar.']);
        return redirect()->route('password.confirm', $user->id);
    }

    public function confirmAccount($id)
    {
        $user = User::findOrFail($id);
        return view('auth.forgot-password.confirm', compact('user'));
    }

    public function sendOtp(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $otp = rand(111111, 666666);

        Session::put('reset_user_id', $user->id);
        Session::put('reset_otp', $otp);

        Mail::to($user->email)->send(new SendOtpMail($otp, $user));
        return redirect()->route('password.verify');
    }

    public function showVerifyForm()
    {
        return view('auth.forgot-password.verify');
    }

    public function verifyOtp(Request $request)
    {
        if ($request->otp == Session::get('reset_otp')) {
            Session::put('otp_verified', true);
            return redirect()->route('password.reset');
        }
        return back()->withErrors(['otp' => 'Kode OTP salah.']);
    }

    public function showResetForm()
    {
        if (!Session::get('otp_verified')) return redirect()->route('password.request');
        return view('auth.forgot-password.reset');
    }

    public function resetPassword(Request $request)
    {
        $request->validate(['password' => 'required|confirmed|min:6']);
        $user = User::find(Session::get('reset_user_id'));
        $user->update(['password' => Hash::make($request->password)]);

        Session::forget(['reset_user_id', 'reset_otp', 'otp_verified']);
        return redirect()->route('login')->with('success', 'Password berhasil diubah!');
    }
}
