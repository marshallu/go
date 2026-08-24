<?php

use App\Models\Url;
use App\Models\User;

test('backfills URL users from legacy created by values', function () {
    $existing = User::factory()->create(['email' => 'existing@marshall.edu']);
    $existingUrl = Url::factory()->create(['user_id' => null, 'created_by' => 'existing']);
    $newUrl = Url::factory()->create(['user_id' => null, 'created_by' => 'newperson']);
    $missingAuthor = Url::factory()->create(['user_id' => null, 'created_by' => null]);
    $linked = Url::factory()->create();

    $this->artisan('go:fix-url-users')->assertSuccessful();

    expect($existingUrl->fresh()->user_id)->toBe($existing->id);
    expect($newUrl->fresh()->user->email)->toBe('newperson@marshall.edu');
    expect($missingAuthor->fresh()->user->email)->toBe('cnmccomas@marshall.edu');
    expect($linked->fresh()->user_id)->not->toBeNull();
});
