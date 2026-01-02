<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    public function verifyNationalId(Request $request)
    {
        $request->validate(['national_id' => 'required']);

        $user = User::where('national_id', $request->national_id)->first();

        if(!$user){
            return response()->json(['status' => 'error']);
        }

        // تولید OTP و ذخیره در دیتابیس
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        // ارسال پیامک
        Http::post('https://smsapi.example/send', [
            'to' => '09185165849',
            'message' => "کد ورود شما: $otp",
        ]);

        Log::info("کد OTP برای کاربر {$user->id} ارسال شد: $otp");

        return response()->json(['status' => 'ok']);
    }

    function sendOtpViaBale($mobile, $otp)
    {
        $token = env('BALE_BOT_TOKEN'); // توکن ربات در .env

        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
            'Content-Type' => 'application/json'
        ])->post('https://messenger.bale.ai/api/sendMessage', [
            'chat_id' => $mobile,
            'text' => "کد ورود شما: $otp"
        ]);

        return $response->successful();
    }
}
