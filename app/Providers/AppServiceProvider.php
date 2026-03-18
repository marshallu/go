<?php

namespace App\Providers;

use App\Models\Url;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Azure\Provider;
use SocialiteProviders\Manager\Helpers\ConfigRetriever;

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
        $this->app->booted(function () {
            $providerClass = Provider::class;
            $config = (new ConfigRetriever)
                ->fromServices('azure', $providerClass::additionalConfigKeys());

            Socialite::extend('azure', function ($app) use ($config, $providerClass) {
                $provider = $app->make(Factory::class)
                    ->buildProvider($providerClass, $config->get());
                $provider->setConfig($config);

                return $provider;
            });
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
