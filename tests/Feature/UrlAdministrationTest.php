<?php

use App\Models\Url;
use App\Models\User;
use Livewire\Livewire;

test('the URL index is restricted to administrators', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/urls')->assertForbidden();
});

test('administrators can search, filter, sort, paginate, and delete URLs', function () {
    $admin = User::factory()->create(['email' => 'cmccomas@marshall.edu']);
    $firstUser = User::factory()->create();
    $secondUser = User::factory()->create();
    $first = Url::factory()->create([
        'id' => 'first1',
        'long_url' => 'https://www.marshall.edu/admissions',
        'user_id' => $firstUser->id,
        'created_at' => now()->subDay(),
    ]);
    Url::factory()->create([
        'id' => 'second',
        'long_url' => 'https://www.marshall.edu/library',
        'user_id' => $secondUser->id,
    ]);
    Url::factory()->count(25)->create(['user_id' => $firstUser->id]);

    $this->actingAs($admin);

    Livewire::test('pages::urls.index')
        ->set('paginators.page', 2)
        ->set('search', 'admissions')
        ->assertSet('paginators.page', 1)
        ->assertSee('first1')
        ->set('search', '')
        ->set('userSearch', $secondUser->id)
        ->assertSee('second')
        ->call('sort', 'last_redirected_at')
        ->assertSet('sortBy', 'last_redirected_at')
        ->assertSet('sortDirection', 'asc')
        ->call('sort', 'last_redirected_at')
        ->assertSet('sortDirection', 'desc')
        ->call('deleteUrl', 'second');

    expect(Url::find('second'))->toBeNull();
    expect(Url::find($first->id))->not->toBeNull();
});
