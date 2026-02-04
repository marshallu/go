<?php

use App\Models\Url;
use App\Models\User;

test('visiting short url redirects to long url', function () {
    $url = Url::factory()->create([
        'id' => 'test12',
        'long_url' => 'https://www.marshall.edu/admissions',
    ]);

    $response = $this->get('/test12');

    $response->assertRedirect('https://www.marshall.edu/admissions');
});

test('visiting short url increments redirect count', function () {
    $url = Url::factory()->create([
        'id' => 'count1',
        'redirect_count' => 5,
    ]);

    $this->get('/count1');

    $url->refresh();
    expect($url->redirect_count)->toBe(6);
});

test('visiting short url updates last_redirected_at', function () {
    $url = Url::factory()->create([
        'id' => 'time12',
        'last_redirected_at' => now()->subDays(30),
    ]);

    $oldTime = $url->last_redirected_at;

    $this->travel(1)->minutes();
    $this->get('/time12');

    $url->refresh();
    expect(strtotime($url->last_redirected_at))->toBeGreaterThan(strtotime($oldTime));
});

test('visiting non-existent short url returns 404', function () {
    $response = $this->get('/notfnd');

    $response->assertNotFound();
});

test('authenticated routes require login', function () {
    $this->get('/')->assertRedirect('/login');
    $this->get('/urls')->assertRedirect('/login');
});

test('authenticated user can access create page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/')
        ->assertOk();
});

test('only admin can access urls index', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/urls')
        ->assertForbidden();
});

test('admin can access urls index', function () {
    $admin = User::factory()->create(['email' => 'cmccomas@marshall.edu']);

    $this->actingAs($admin)
        ->get('/urls')
        ->assertOk();
});
