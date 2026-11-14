<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Bus\Queueable;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Contoh: http://localhost:5173/reset-password?token=xxx&email=xxx
        $frontendUrl = rtrim(config('app.frontend_url'), '/');
        $url = "{$frontendUrl}/reset-password?" . http_build_query([
            'token' => $this->token,
            'email' => $notifiable->email,
        ]);

        return (new MailMessage)
            ->subject('Reset Password - Structify')
            ->greeting("Halo {$notifiable->name}!")
            ->line('Kami menerima permintaan reset password untuk akunmu.')
            ->action('Reset Password', $url)
            ->line('Link ini akan expired dalam 60 menit.')
            ->line('Kalau kamu tidak meminta ini, abaikan email ini.');
    }
}