<?php

namespace App\Providers;

use App\Models\Url;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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

        TrustProxies::at('*'); // or an array of IPs/CIDRs
        TrustProxies::withHeaders(
            Request::HEADER_X_FORWARDED_FOR
            | Request::HEADER_X_FORWARDED_HOST
            | Request::HEADER_X_FORWARDED_PORT
            | Request::HEADER_X_FORWARDED_PROTO
            | Request::HEADER_X_FORWARDED_PREFIX
            | Request::HEADER_X_FORWARDED_AWS_ELB
        );
    }
}
