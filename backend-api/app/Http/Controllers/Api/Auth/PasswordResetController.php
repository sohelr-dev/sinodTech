<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    /**
     * Send a password reset link to the user's email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetRequest(Request $request)
    {
        // Validate the email input
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.exists' => 'We could not find a user with that email address.',
        ]);
        $code =random_int(100000, 999999);
        $expiresAt = now()->addMinutes(15);
        $token = Str::random(60);
        // store the reset code in the database
        DB::table('password_reset_codes')->updateOrInsert(
            ['email'=>$request->email],
            [
                'code'=>$code,
                'expires_at'=>$expiresAt,
                'created_at'=>now(),
                'updated_at'=>now(),
                'is_used'=>false,
                'token'=>$token,
            ]
        );
        $restLink = config('app.frontend_url')."/reset-password?token=".$token."&email=".$request->email;
        Mail::send(
            'emails.password_reset',
        [
            'code'=>$code,
            'link'=>$restLink
        ],
        function($message) use ($request){
         $message->to($request->email);
         $message->subject('Password Reset Request Code & Link');
        }
        );
        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Password reset link has been sent to your email address.',
            'email' => $request->email,
            'token'=>$token
        ], 200);

    }

    /**
     * Reset the user's password.
     */
    public function Verify(Request $request)
    {
        // Validate the reset request
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
        ]);

        // otp verification
        $record = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        //fail if no record found
        if(!$record){
            return response()->json([
                'success'=>false,
                'message'=>'Invaild or expired reset code.',
            ],422);
        }
        return response()->json([
            'success'=>true,
            'message'=>'Reset code verfied. You can now reset your password.',
        ],200);

    }
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.exists' => 'We could not find a user with that email address.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        // Find the password reset record
        $record = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token.',
            ], 422);
        }

        // Reset the user's password
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Mark the token as used
        DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->update(['is_used' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Your password has been reset successfully.',
        ], 200);
    }
}
