<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
// use Carbon\Carbon;

class OtpController extends Controller
{
    public function form()
    {
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $user = User::find(session('otp_user_id'));

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->otp != $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP salah']);
        }

        if ($user->otp_expires_at < now()) {
            return back()->withErrors(['otp' => 'OTP sudah kadaluarsa']);
        }

        // bersihkan OTP
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        session()->forget('otp_user_id');

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}