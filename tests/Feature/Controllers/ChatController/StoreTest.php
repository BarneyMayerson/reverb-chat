<?php

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(function () {
    $this->validData = fn () => [
        'recipient_id' => User::factory()->create()->getKey(),
        'message' => 'Hello',
    ];
});

it('requires authentication', function () {
    post(route('chats.store', 1))->assertRedirect(route('login'));
});

it('stores a chat', function () {
    withoutExceptionHandling();
    $me = User::factory()->create();

    $data = value($this->validData);

    actingAs($me)->post(route('chats.store', $data));

    assertDatabaseHas(Chat::class, [
        'initiator_id' => $me->id,
        'recipient_id' => $data['recipient_id'],
    ]);
});

it('requires a valid data', function (array $badData, array|string $errors) {
    actingAs(User::factory()->create())
        ->post(route('chats.store'), [...value($this->validData), ...$badData])
        ->assertInvalid($errors);
})->with([
    [['message' => null], 'message'],
    [['message' => true], 'message'],
    [['message' => 1], 'message'],
    [['message' => 1.5], 'message'],
    [['message' => ''], 'message'],
    [['message' => str_repeat('a', 241)], 'message'],
    [['recipient_id' => null], 'recipient_id'],
    [['recipient_id' => true], 'recipient_id'],
    [['recipient_id' => ''], 'recipient_id'],
    [['recipient_id' => 5.5], 'recipient_id'],
    [['recipient_id' => 10000], 'recipient_id'], // not existing user
    [['recipient_id' => 1], 'recipient_id'], // not with myself (assuming we are acting as a user with id equal to 1)
]);

it('stores the first message the chat', function () {
    $me = User::factory()->create();

    $data = value($this->validData);

    actingAs($me)->post(route('chats.store', $data));

    assertDatabaseHas(Message::class, [
        'sender_id' => $me->id,
        'message' => $data['message'],
    ]);
});

it('redirects to the chat show page', function () {
    $data = value($this->validData);

    $response = actingAs(User::factory()->create())->post(route('chats.store', $data));

    $chat = Chat::latest('id')->first();

    $response->assertRedirect(route('chats.show', $chat));
});

it('redirect to the chat show page when the chat is alredy exist', function () {
    $me = User::factory()->create();
    $recipient = User::factory()->create();

    $chat = Chat::create([
        'initiator_id' => $me->id,
        'recipient_id' => $recipient->id,
    ]);

    assertDatabaseCount('chats', 1);

    actingAs($me)->post(route('chats.store', [
        'recipient_id' => $recipient->id,
        'message' => 'Hello again',
    ]))->assertRedirect(route('chats.show', $chat));

    assertDatabaseCount('chats', 1);
    assertDatabaseEmpty('messages');
});
