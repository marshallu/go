<?php

use App\Models\Url;
use Illuminate\Support\Facades\Storage;

test('exports URLs to a downloadable CSV', function () {
    Storage::fake('public');
    $url = Url::factory()->create([
        'id' => 'export',
        'long_url' => 'https://www.marshall.edu/admissions?test=one',
        'created_by' => 'marshall',
        'redirect_count' => 4,
    ]);
    $timestamp = now()->format('Y-m-d_H-i-s');

    $this->artisan('go:export')
        ->expectsOutputToContain('Download URL:')
        ->assertSuccessful();

    $path = 'exports/urls_export_'.$timestamp.'.csv';
    Storage::disk('public')->assertExists($path);
    expect(Storage::disk('public')->get($path))
        ->toContain('ID,"Long URL"')
        ->toContain($url->id)
        ->toContain($url->long_url);
});

test('imports a CSV without replacing existing URLs', function () {
    Storage::fake('public');
    Url::factory()->create(['id' => 'keepit', 'long_url' => 'https://www.marshall.edu/keep']);
    Storage::disk('public')->put('exports/import.csv', implode("\n", [
        'ID,Long URL,Base URL,UTM Source,UTM Medium,UTM Campaign,Created By,Redirect Count,Last Redirected At,Created At,Updated At',
        'update1,https://www.marshall.edu/new,https://www.marshall.edu/new,,,,legacy,not-a-number,,,',
    ]));

    $this->artisan('go:import', ['--file' => 'import.csv'])
        ->expectsConfirmation('Do you want to delete existing URLS?', 'no')
        ->expectsOutput('Import successful!')
        ->assertSuccessful();

    $this->assertDatabaseHas('urls', ['id' => 'keepit']);
    $this->assertDatabaseHas('urls', ['id' => 'update1', 'redirect_count' => 0, 'created_by' => 'legacy']);
});

test('imports nullable CSV fields and replaces existing URLs when requested', function () {
    Storage::fake('public');
    Url::factory()->create(['id' => 'replace']);
    Storage::disk('public')->put('exports/replace.csv', implode("\n", [
        'ID,Long URL,Base URL,UTM Source,UTM Medium,UTM Campaign,Created By,Redirect Count,Last Redirected At,Created At,Updated At',
        'import2,https://www.marshall.edu/import,https://www.marshall.edu/import,,,,,3,,,',
    ]));

    $this->artisan('go:import', ['--file' => 'replace.csv', '--replace' => true])
        ->expectsOutput('Import successful!')
        ->assertSuccessful();

    expect(Url::pluck('id')->all())->toBe(['import2']);
    $this->assertDatabaseHas('urls', ['id' => 'import2', 'redirect_count' => 3, 'utm_source' => null]);
});

test('rejects an empty CSV file', function () {
    Storage::fake('public');
    Storage::disk('public')->put('exports/empty.csv', '');

    $this->artisan('go:import', ['--file' => 'empty.csv'])
        ->expectsConfirmation('Do you want to delete existing URLS?', 'no')
        ->expectsOutput('The CSV file is empty.')
        ->assertFailed();
});
