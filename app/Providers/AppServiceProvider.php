<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Models\Url;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('azure', \SocialiteProviders\Azure\Provider::class);
        });

        $admins = ['bajus@marshall.edu', 'davis220@marshall.edu', 'traube3@marshall.edu', 'madden24@marshall.edu', 'cmccomas@marshall.edu'];

        Gate::define('edit-url', function ($user, Url $url) use ($admins) {
            return in_array($user->email, $admins) || $user->id === $url->user_id;
        });

        Gate::define('super-admin', function ($user) use ($admins) {
            return in_array($user->email, $admins);
        });
    }
}
