<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
// use Carbon\Carbon;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->email],
            [
                'name' => $googleUser->name,
                'id_google' => $googleUser->id,
                'password' => bcrypt(Str::random(16)),
            ]
        );

        // generate OTP
        $otp = random_int(100000, 999999);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        // kirim Otp ke email
        Mail::raw("Kode OTP anda adalah: $otp", function ($message) use ($user){
            $message->to($user->email)
                    ->subject('Kode OTP untuk Login');
        });

        session(['otp_user_id' => $user->id]);

        return redirect()->route('otp.form');
    }
}
