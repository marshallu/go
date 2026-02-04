<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Http;

class SendSlackNotificationOnLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        $timestamp = now()->toDateTimeString();

        $message = "*New Login Detected*\n".
                   "👤 *User:* {$user->name} ({$user->email})\n".
                   "⏰ *Time:* {$timestamp}\n".
                   '🔍 *IP Address:* '.request()->ip();

        Http::post(config('services.slack.webhook_url'), [
            'text' => $message,
            'mrkdwn' => true, // Enables Slack markdown formatting
        ]);
    }
}
