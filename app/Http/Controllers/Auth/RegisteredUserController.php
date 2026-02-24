<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register-purple');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
        ]);

        // 🚨 JANGAN LOGIN DULU
        // event(new Registered($user)); ← boleh tetap ada kalau mau email verification Laravel

        $otp = random_int(100000, 999999);

        $user->update([
            'otp' => (string) $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        \Mail::raw("Kode OTP Registrasi Anda: $otp", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Kode OTP Registrasi');
        });

        session(['otp_user_id' => $user->id]);

        return redirect()->route('otp.form');
    }
}
