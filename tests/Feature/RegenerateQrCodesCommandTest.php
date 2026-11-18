<?php

use App\Models\Url;
use Illuminate\Support\Facades\Storage;

test('regenerates only missing qr codes', function () {
    Storage::fake('public');
    Url::factory()->create(['id' => 'lost12']);
    Url::factory()->create(['id' => 'kept12']);
    Storage::disk('public')->put('qr_codes/kept12.svg', 'existing-qr');

    $this->artisan('go:regenerate-qr-codes')
        ->expectsOutputToContain('Total QR codes regenerated: 1')
        ->assertSuccessful();

    expect(Storage::disk('public')->get('qr_codes/lost12.svg'))->toContain('<svg');
    expect(Storage::disk('public')->get('qr_codes/kept12.svg'))->toBe('existing-qr');
});
