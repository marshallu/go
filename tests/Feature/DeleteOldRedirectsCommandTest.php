<?php

use App\Models\Url;

test('deletes urls not used in over 2 years', function () {
    Url::factory()->old()->count(3)->create();
    Url::factory()->recent()->count(2)->create();

    expect(Url::count())->toBe(5);

    $this->artisan('go:delete-old')
        ->expectsOutputToContain('Cleared 3 old redirects')
        ->assertSuccessful();

    expect(Url::count())->toBe(2);
});

test('does not delete urls used within 2 years', function () {
    Url::factory()->recent()->count(5)->create();

    $this->artisan('go:delete-old')
        ->expectsOutputToContain('Cleared 0 old redirects')
        ->assertSuccessful();

    expect(Url::count())->toBe(5);
});

test('handles empty database gracefully', function () {
    $this->artisan('go:delete-old')
        ->expectsOutputToContain('Cleared 0 old redirects')
        ->assertSuccessful();
});
