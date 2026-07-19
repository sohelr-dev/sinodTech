<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            //user data handleing
            //check if user already exists
            $user = User::where('google_id', $googleUser->id)->first();
            //if not check if user with same email exists
            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();
                if ($user) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar ?? null,
                    ]);
                } else {
                    //create new user
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar ?? null,
                        'password' => bcrypt(uniqid()), // generate a random password since it's required, but user won't use it for login

                    ]);
                    //role assigning
                    $user->assignRole('customer');
                }
            }
            //login the user
            Auth::login($user);

            return redirect()->intended('/');
        } catch (Exception $e) {
            return redirect()->route('login')->with('status', 'failed to login with google. Please try again.');
        }
    }
}
