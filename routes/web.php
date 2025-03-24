<?php

use App\Http\Controllers\DeployController;
use App\Models\Url;
use App\Models\User;
use Livewire\Volt\Volt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Volt::route('/', 'urls.create')->name('urls.create')->middleware('auth');
Volt::route('/{url}/edit', 'urls.edit')->name('urls.edit')->middleware('auth');
Volt::route('/urls', 'urls.index')->name('urls.index')->middleware('auth');

Route::get('/cmm', function () {
	return 'hi';
});

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
