<?php

use App\Models\Url;
use App\Models\User;
use Livewire\Livewire;

test('edit page renders for url owner', function () {
    $user = User::factory()->create();
    $url = Url::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get("/{$url->id}/edit")
        ->assertOk()
        ->assertSeeLivewire('pages::urls.edit');
});

test('edit page renders for admin', function () {
    $admin = User::factory()->create(['email' => 'cmccomas@marshall.edu']);
    $otherUser = User::factory()->create();
    $url = Url::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($admin)
        ->get("/{$url->id}/edit")
        ->assertOk();
});

test('edit page forbidden for non-owner', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $url = Url::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($user)
        ->get("/{$url->id}/edit")
        ->assertForbidden();
});

test('can update url base_url', function () {
    $user = User::factory()->create();
    $url = Url::factory()->create([
        'user_id' => $user->id,
        'base_url' => 'https://www.marshall.edu/old',
    ]);

    $this->actingAs($user);

    Livewire::test('pages::urls.edit', ['url' => $url])
        ->set('form.base_url', 'https://www.marshall.edu/new')
        ->call('update');

    $url->refresh();
    expect($url->base_url)->toBe('https://www.marshall.edu/new');
});

test('can update utm parameters', function () {
    $user = User::factory()->create();
    $url = Url::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    Livewire::test('pages::urls.edit', ['url' => $url])
        ->set('form.utm_source', 'newsource')
        ->set('form.utm_medium', 'newmedium')
        ->set('form.utm_campaign', 'newcampaign')
        ->call('update');

    $url->refresh();
    expect($url->utm_source)->toBe('newsource');
    expect($url->utm_medium)->toBe('newmedium');
    expect($url->utm_campaign)->toBe('newcampaign');
});

test('cannot update to invalid domain', function () {
    $user = User::factory()->create();
    $url = Url::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    Livewire::test('pages::urls.edit', ['url' => $url])
        ->set('form.base_url', 'https://www.google.com')
        ->call('update')
        ->assertHasErrors(['form.base_url']);
});

test('edit form loads existing url data', function () {
    $user = User::factory()->create();
    $url = Url::factory()->create([
        'user_id' => $user->id,
        'base_url' => 'https://www.marshall.edu/test',
        'utm_source' => 'testsource',
        'utm_medium' => 'testmedium',
        'utm_campaign' => 'testcampaign',
    ]);

    $this->actingAs($user);

    Livewire::test('pages::urls.edit', ['url' => $url])
        ->assertSet('form.base_url', 'https://www.marshall.edu/test')
        ->assertSet('form.utm_source', 'testsource')
        ->assertSet('form.utm_medium', 'testmedium')
        ->assertSet('form.utm_campaign', 'testcampaign');
});
