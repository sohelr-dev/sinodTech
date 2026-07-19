<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Support\Facades\DB;

class ResetPasswordNotification extends BaseResetPassword
{
    public function toMail($notifiable)
    {
        // Generate 6 digit code
        $code = rand(100000, 999999);

        // Save code in DB
        DB::table('password_reset_tokens')
            ->where('email', $notifiable->email)
            ->update(['code' => $code]);

        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email
        ], false));

        return (new MailMessage)
            ->subject('Reset Your FNF Trip Password 🔐')
            ->greeting('Hello ' . $notifiable->name . ' 👋')
            ->line('You requested a password reset.')
            ->line('Reset Code: **' . $code . '**')
            ->action('Reset Password', $url)
            ->line('This link will expire in 60 minutes.')
            ->line('If you did not request, ignore this email.');
    }
}
