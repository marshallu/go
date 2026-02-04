<?php

use App\Models\Url;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

test('admin can edit any url', function () {
    $admin = User::factory()->create(['email' => 'cmccomas@marshall.edu']);
    $otherUser = User::factory()->create();
    $url = Url::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($admin);

    expect(Gate::allows('edit-url', $url))->toBeTrue();
});

test('user can edit their own url', function () {
    $user = User::factory()->create();
    $url = Url::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    expect(Gate::allows('edit-url', $url))->toBeTrue();
});

test('user cannot edit another users url', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $url = Url::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($user);

    expect(Gate::denies('edit-url', $url))->toBeTrue();
});

test('admin has super-admin access', function () {
    $admin = User::factory()->create(['email' => 'cmccomas@marshall.edu']);

    $this->actingAs($admin);

    expect(Gate::allows('super-admin'))->toBeTrue();
});

test('regular user does not have super-admin access', function () {
    $user = User::factory()->create(['email' => 'regular@marshall.edu']);

    $this->actingAs($user);

    expect(Gate::denies('super-admin'))->toBeTrue();
});

test('all admins have super-admin access', function () {
    $adminEmails = [
        'bajus@marshall.edu',
        'davis220@marshall.edu',
        'traube3@marshall.edu',
        'madden24@marshall.edu',
        'cmccomas@marshall.edu',
    ];

    foreach ($adminEmails as $email) {
        $admin = User::factory()->create(['email' => $email]);

        $this->actingAs($admin);

        expect(Gate::allows('super-admin'))->toBeTrue();
    }
});
