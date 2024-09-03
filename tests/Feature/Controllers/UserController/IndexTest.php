<?php

use App\Http\Resources\UserResource;
use App\Models\User;

use function Pest\Laravel\get;

it('should return the correct component', function () {
    get(route('users.index'))->assertComponent('Users/Index');
});

it('passes users to the view', function () {
    $users = User::factory(3)->create();

    get(route('users.index'))->assertHasPaginatedResource(
        'users',
        UserResource::collection($users->reverse())
    );
});
