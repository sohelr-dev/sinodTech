<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasswordVerifyCodeController extends Controller
{
    public function index(Request $request)
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request')->withErrors(['email' => 'Please request a password reset first.']);
        }
        return view('auth.password-verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|array',
            'code.*' => 'required|numeric',
        ]);

        $inputCode = implode('', $request->code);
        $email = session('reset_email');

        $resetData = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if ($resetData && $resetData->code == $inputCode) {
            // code is valid, redirect to reset password form with token
            return redirect()->route('password.reset', [
                'token' => $resetData->token,
                'email' =>$email
            ]);
        }

        return back()->withErrors(['code' => 'The verification code is invalid.']);
    }
}
