<?php

use App\Models\Url;

test('fixes urls with multiple question marks', function () {
    Url::factory()->create([
        'id' => 'fix123',
        'long_url' => 'https://www.marshall.edu/page?param1=a?param2=b',
    ]);

    $this->artisan('go:fix-urls')
        ->expectsOutputToContain('Total URLs fixed: 1')
        ->assertSuccessful();

    $url = Url::find('fix123');
    expect($url->long_url)->toBe('https://www.marshall.edu/page?param1=a&param2=b');
});

test('fixes urls with many question marks', function () {
    Url::factory()->create([
        'id' => 'many12',
        'long_url' => 'https://www.marshall.edu/page?a=1?b=2?c=3?d=4',
    ]);

    $this->artisan('go:fix-urls')->assertSuccessful();

    $url = Url::find('many12');
    expect($url->long_url)->toBe('https://www.marshall.edu/page?a=1&b=2&c=3&d=4');
});

test('does not modify correct urls', function () {
    Url::factory()->create([
        'id' => 'good12',
        'long_url' => 'https://www.marshall.edu/page?param1=a&param2=b',
    ]);

    $this->artisan('go:fix-urls')
        ->expectsOutputToContain('Total URLs fixed: 0')
        ->assertSuccessful();

    $url = Url::find('good12');
    expect($url->long_url)->toBe('https://www.marshall.edu/page?param1=a&param2=b');
});

test('does not modify urls without query params', function () {
    Url::factory()->create([
        'id' => 'noqry1',
        'long_url' => 'https://www.marshall.edu/page',
    ]);

    $this->artisan('go:fix-urls')
        ->expectsOutputToContain('Total URLs fixed: 0')
        ->assertSuccessful();

    $url = Url::find('noqry1');
    expect($url->long_url)->toBe('https://www.marshall.edu/page');
});
