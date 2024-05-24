<?php

use App\Models\Url;
use App\Livewire\EditUrl;
use App\Livewire\CreateUrl;
use Illuminate\Support\Facades\Route;

Route::get('/', CreateUrl::class)->name('url.create');
Route::get('/{url}/edit', EditUrl::class)->name('url.edit');

Route::get('/{url}', function(Url $url) {
	$url->increment('redirect_count');
	$url->update(['last_redirected_at' => now()]);
	return redirect($url->long_url);
})->name('site.redirect');
