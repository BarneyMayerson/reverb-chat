<?php

use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('requires authentication', function () {
    get(route('chats.show', 1))->assertRedirect(route('login'));
});

it('can be shown to the initiator', function () {
    $chat = Chat::factory()->create();

    actingAs($chat->initiator)
        ->get(route('chats.show', $chat))
        ->assertOk()
        ->assertComponent('Chats/Show');
});

it('can be shown to the recipient', function () {
    $chat = Chat::factory()->create();

    actingAs($chat->recipient)
        ->get(route('chats.show', $chat))
        ->assertOk()
        ->assertComponent('Chats/Show');
});

it('cannot be shown to a stranger', function () {
    $chat = Chat::factory()->create();

    actingAs(User::factory()->create())
        ->get(route('chats.show', $chat))
        ->assertForbidden();
});

it('passes the chat to the view', function () {
    $user = User::factory()->create();

    $chat = Chat::factory()->create([
        'initiator_id' => $user->id,
    ]);

    actingAs($user);

    get(route('chats.show', $chat))
        ->assertHasResource('chat', ChatResource::make($chat->load('messages')));
});
