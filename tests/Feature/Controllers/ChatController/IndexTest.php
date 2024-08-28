<?php

use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('requires authentication', function () {
    get(route('chats.index'))->assertRedirect(route('login'));
});

it('should return the correct component', function () {
    actingAs(User::factory()->create());

    get(route('chats.index'))
        ->assertComponent('Chats/Index');
});

it("passes user's chats to the view", function () {
    $user = User::factory()->create();

    $chats = Chat::factory()
        ->count(3)
        ->create([
            'initiator_id' => $user->id,
        ]);

    actingAs($user);

    get(route('chats.index'))->assertHasResource(
        'chats',
        ChatResource::collection($chats)
    );
});
