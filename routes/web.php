<?php

use App\Http\Middleware\CreatedByCanEditUrl;
use App\Http\Middleware\IsAdmin;
use App\Models\Url;
use App\Models\User;
use App\Livewire\EditUrl;
use App\Livewire\CreateUrl;
use App\Livewire\UrlsIndex;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

Route::get('/', CreateUrl::class)->name('url.create')->middleware('auth');

Route::get('/{url}/edit', EditUrl::class)->name('url.edit')->middleware('auth');

Route::get('/urls', UrlsIndex::class)->name('url.create')->middleware('auth');

Route::get('/login', function () {
    return redirect('/auth/redirect');
})->name('login');

Route::get('/{url}', function(Url $url) {
    $url->increment('redirect_count');
    $url->update(['last_redirected_at' => now()]);
    return redirect($url->long_url);
})->name('site.redirect');


Route::get('/auth/redirect', function () {
    return Socialite::driver('azure')->redirect();
});

Route::get('/auth/callback', function () {
    $azureUser = Socialite::driver('azure')->user();

    $user = User::updateOrCreate(['email' => $azureUser->email,], [
        'name' => $azureUser->name,
        'password' => bcrypt(Str::random(24)),
    ]);

    Auth::login($user);

    return redirect('/');
});
