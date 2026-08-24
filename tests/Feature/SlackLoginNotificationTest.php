<?php

use App\Listeners\SendSlackNotificationOnLogin;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Http;

test('a login sends the configured Slack webhook the user, time, and request IP', function () {
    config()->set('services.slack.webhook_url', 'https://hooks.slack.test/login');
    Http::fake();
    $user = User::factory()->create(['name' => 'Taylor User', 'email' => 'taylor@marshall.edu']);

    $this->app['request']->server->set('REMOTE_ADDR', '203.0.113.8');
    (new SendSlackNotificationOnLogin)->handle(new Login('web', $user, false));

    Http::assertSent(function ($request) use ($user): bool {
        return $request->url() === 'https://hooks.slack.test/login'
            && $request['mrkdwn'] === true
            && str_contains($request['text'], $user->name)
            && str_contains($request['text'], $user->email)
            && str_contains($request['text'], '203.0.113.8');
    });
});
