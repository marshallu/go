<?php

use App\Http\Middleware\CreatedByCanEditUrl;
use App\Http\Middleware\IsAdmin;
use App\Models\Url;
use App\Livewire\EditUrl;
use App\Livewire\CreateUrl;
use App\Livewire\UrlsIndex;
use Illuminate\Support\Facades\Route;
use Subfission\Cas\Middleware\CASAuth;

Route::get('/', CreateUrl::class)->name('url.create')->middleware(CASAuth::class);
Route::get('/{url}/edit', EditUrl::class)->name('url.edit')->middleware([CASAuth::class, CreatedByCanEditUrl::class]);

Route::get('/urls', UrlsIndex::class)->name('url.create')->middleware([CASAuth::class, IsAdmin::class]);

Route::get('/{url}', function(Url $url) {
	$url->increment('redirect_count');
	$url->update(['last_redirected_at' => now()]);
	return redirect($url->long_url);
})->name('site.redirect');

// get('/auth/login', function(){
//     cas()->authenticate();
// });
