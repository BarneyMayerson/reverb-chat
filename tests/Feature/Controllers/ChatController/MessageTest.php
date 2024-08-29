<?php

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('requires authentication', function () {
    post(route('chats.message', 1))->assertRedirect(route('login'));
});

it('requires a valid data', function (array $badData, string $errors) {
    $chatMember = User::factory()->create();

    $chat = Chat::factory()->create([
        'initiator_id' => $chatMember->id,
    ]);

    actingAs($chatMember)
        ->post(route('chats.message', $chat), ['text' => 'Hello there!', ...$badData])
        ->assertInvalid($errors);
})->with([
    [['text' => null], 'text'],
    [['text' => true], 'text'],
    [['text' => 1], 'text'],
    [['text' => 1.5], 'text'],
    [['text' => str_repeat('a', 241)], 'text'],
]);

it('can store a message for the chat member', function () {
    $chatMember = User::factory()->create();

    $chat = Chat::factory()->create([
        'initiator_id' => $chatMember->id,
    ]);

    assertDatabaseEmpty('messages');

    actingAs($chatMember)
        ->post(route('chats.message', $chat), ['text' => 'Hello there!']);

    assertDatabaseCount('messages', 1);
    assertDatabaseHas(Message::class, [
        'chat_id' => $chat->id,
        'sender_id' => $chatMember->id,
        'message' => 'Hello there!',
    ]);
});

it('cannot store a message for the stranger', function () {
    $chat = Chat::factory()->create();

    actingAs(User::factory()->create())
        ->post(route('chats.message', $chat), ['text' => 'Hello there!'])
        ->assertForbidden();
});
