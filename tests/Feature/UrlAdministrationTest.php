<?php

use App\Models\Url;
use App\Models\User;
use Livewire\Livewire;

test('non-administrators only see their own URLs on the index', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    Url::factory()->create(['id' => 'mine01', 'user_id' => $user->id]);
    Url::factory()->create(['id' => 'theirs', 'user_id' => $otherUser->id]);

    $this->actingAs($user)
        ->get('/urls')
        ->assertOk()
        ->assertSee('mine01')
        ->assertDontSee('theirs');
});

test('non-administrators cannot see other users URLs by filtering by user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    Url::factory()->create(['id' => 'theirs', 'user_id' => $otherUser->id]);

    $this->actingAs($user);

    Livewire::test('pages::urls.index')
        ->set('userSearch', $otherUser->id)
        ->assertDontSee('theirs');
});

test('non-administrators can delete their own URLs but not others', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    Url::factory()->create(['id' => 'mine01', 'user_id' => $user->id]);
    Url::factory()->create(['id' => 'theirs', 'user_id' => $otherUser->id]);

    $this->actingAs($user);

    Livewire::test('pages::urls.index')->call('deleteUrl', 'mine01');
    expect(Url::find('mine01'))->toBeNull();

    Livewire::test('pages::urls.index')
        ->call('deleteUrl', 'theirs')
        ->assertForbidden();
    expect(Url::find('theirs'))->not->toBeNull();
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
