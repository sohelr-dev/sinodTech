<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // first try the default password reset system
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // if default system works, then redirect to login with success message
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        // if default system fails, then try to manually verify the token from our custiom otp system
        $manualCheck = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token) // otp token check 
            ->first();

        if ($manualCheck) {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();

                DB::table('password_reset_tokens')->where('email', $request->email)->delete();

                return redirect()->route('login')->with('status', 'Password reset successfully!');
            }
        }

        // error,redirect back with error mgs
        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'This password reset token is invalid or expired.']);
    }
}
