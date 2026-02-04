<?php

use App\Models\Url;
use App\Models\User;

test('url belongs to a user', function () {
    $user = User::factory()->create();
    $url = Url::factory()->create(['user_id' => $user->id]);

    expect($url->user)->toBeInstanceOf(User::class);
    expect($url->user->id)->toBe($user->id);
});

test('user has many urls', function () {
    $user = User::factory()->create();
    Url::factory()->count(3)->create(['user_id' => $user->id]);

    expect($user->urls)->toHaveCount(3);
    expect($user->urls->first())->toBeInstanceOf(Url::class);
});

test('url uses string primary key', function () {
    $url = Url::factory()->create(['id' => 'abc123']);

    expect($url->id)->toBe('abc123');
    expect($url->getKeyType())->toBe('string');
    expect($url->getIncrementing())->toBeFalse();
});

test('url can be created with custom id', function () {
    $user = User::factory()->create();

    $url = Url::create([
        'id' => 'custom',
        'base_url' => 'https://www.marshall.edu/test',
        'long_url' => 'https://www.marshall.edu/test',
        'user_id' => $user->id,
        'last_redirected_at' => now(),
    ]);

    expect($url->id)->toBe('custom');
});

test('url stores utm parameters', function () {
    $url = Url::factory()->withUtm()->create();

    expect($url->utm_source)->toBe('salesforce');
    expect($url->utm_medium)->toBe('email');
    expect($url->utm_campaign)->toBe('spring2024');
});

test('url tracks redirect count', function () {
    $url = Url::factory()->create(['redirect_count' => 0]);

    $url->increment('redirect_count');
    $url->refresh();

    expect($url->redirect_count)->toBe(1);
});
