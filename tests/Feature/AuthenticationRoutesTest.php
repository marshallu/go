<?php

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

test('login redirects to the Azure authorization route', function () {
    $this->get('/login')->assertRedirect('/auth/redirect');
});

test('Azure callback creates and authenticates a user', function () {
    $azureUser = (object) ['email' => 'new.user@marshall.edu', 'name' => 'New User'];
    $driver = Mockery::mock();
    $driver->shouldReceive('user')->once()->andReturn($azureUser);
    Socialite::shouldReceive('driver')->with('azure')->once()->andReturn($driver);

    $this->get('/auth/callback')->assertRedirect('/');

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', ['email' => $azureUser->email, 'name' => $azureUser->name]);
});

test('Azure callback updates an existing user', function () {
    $user = User::factory()->create(['email' => 'existing@marshall.edu', 'name' => 'Old Name']);
    $azureUser = (object) ['email' => $user->email, 'name' => 'Updated Name'];
    $driver = Mockery::mock();
    $driver->shouldReceive('user')->once()->andReturn($azureUser);
    Socialite::shouldReceive('driver')->with('azure')->once()->andReturn($driver);

    $this->get('/auth/callback')->assertRedirect('/');

    expect(User::where('email', $user->email)->count())->toBe(1);
    expect($user->fresh()->name)->toBe('Updated Name');
    $this->assertAuthenticatedAs($user->fresh());
});
