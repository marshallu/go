<?php

use App\Models\Url;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('create page renders for authenticated user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/')
        ->assertOk()
        ->assertSeeLivewire('pages::urls.create');
});

test('can create url with valid marshall.edu domain', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('pages::urls.create')
        ->set('form.base_url', 'https://www.marshall.edu/admissions')
        ->call('store');

    expect(Url::count())->toBe(1);
    expect(Url::first()->base_url)->toBe('https://www.marshall.edu/admissions');
    expect(Url::first()->user_id)->toBe($user->id);
});

test('can create url with custom alias', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('pages::urls.create')
        ->set('form.base_url', 'https://www.marshall.edu/admissions')
        ->set('form.customAlias', 'apply')
        ->call('store');

    expect(Url::find('apply'))->not->toBeNull();
    Storage::disk('public')->assertExists('qr_codes/apply.svg');
});

test('adds UTM parameters after an existing query string', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('pages::urls.create')
        ->set('form.base_url', 'https://www.marshall.edu/admissions?source=site')
        ->set('form.utm_source', 'salesforce')
        ->call('store');

    expect(Url::first()->long_url)->toBe('https://www.marshall.edu/admissions?source=site&utm_source=salesforce');
});

test('can generate a black QR code for the full destination URL', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('pages::urls.create')
        ->set('form.base_url', 'https://www.marshall.edu/admissions')
        ->set('form.customAlias', 'fullqr')
        ->set('form.linksTo', 'full')
        ->set('form.foreground_color', 'black')
        ->call('store')
        ->assertRedirect(route('urls.edit', 'fullqr'));

    expect(Storage::disk('public')->get('qr_codes/fullqr.svg'))->toContain('fill="#000000"');
});

test('enforces custom alias and UTM length boundaries', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('pages::urls.create')
        ->set('form.base_url', 'https://www.marshall.edu/admissions')
        ->set('form.customAlias', str_repeat('a', 25))
        ->set('form.utm_campaign', str_repeat('a', 51))
        ->call('store')
        ->assertHasErrors(['form.customAlias', 'form.utm_campaign']);
});

test('can create url with utm parameters', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('pages::urls.create')
        ->set('form.base_url', 'https://www.marshall.edu/admissions')
        ->set('form.utm_source', 'salesforce')
        ->set('form.utm_medium', 'email')
        ->set('form.utm_campaign', 'spring2024')
        ->call('store');

    $url = Url::first();
    expect($url->utm_source)->toBe('salesforce');
    expect($url->utm_medium)->toBe('email');
    expect($url->utm_campaign)->toBe('spring2024');
    expect($url->long_url)->toContain('utm_source=salesforce');
    expect($url->long_url)->toContain('utm_medium=email');
    expect($url->long_url)->toContain('utm_campaign=spring2024');
});

test('cannot create url with invalid domain', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('pages::urls.create')
        ->set('form.base_url', 'https://www.google.com')
        ->call('store')
        ->assertHasErrors(['form.base_url']);

    expect(Url::count())->toBe(0);
});

test('cannot create url with duplicate custom alias', function () {
    $user = User::factory()->create();
    Url::factory()->create(['id' => 'taken']);

    $this->actingAs($user);

    Livewire::test('pages::urls.create')
        ->set('form.base_url', 'https://www.marshall.edu/admissions')
        ->set('form.customAlias', 'taken')
        ->call('store')
        ->assertHasErrors(['form.customAlias']);
});

test('utm parameters must be alphanumeric', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('pages::urls.create')
        ->set('form.base_url', 'https://www.marshall.edu/admissions')
        ->set('form.utm_source', 'invalid-source!')
        ->call('store')
        ->assertHasErrors(['form.utm_source']);
});

test('base url is required', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('pages::urls.create')
        ->set('form.base_url', '')
        ->call('store')
        ->assertHasErrors(['form.base_url']);
});
