<?php

use App\Http\Resources\UserResource;
use App\Models\User;

use function Pest\Laravel\get;

it('should return the correct component', function () {
    $user = User::factory()->create();

    get(route('users.show', $user))->assertComponent('Users/Show');
});

it('passes user to the view', function () {
    $user = User::factory()->create();

    get(route('users.show', $user))->assertHasResource(
        'user',
        UserResource::make($user)
    );
});
