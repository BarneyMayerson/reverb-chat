<?php

use App\Models\Chat;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('has an initiator', function () {
    $chat = Chat::factory()->make();

    expect($chat->initiator)->not()->toBeNull();
});

it('can detemine whether a user is the member', function () {
    $chat = Chat::factory()->make();

    $user = User::factory()->create();

    expect($chat->isMember($user->id))->toBeFalsy();
    expect($chat->isMember($chat->initiator->id))->toBeTruthy();
    expect($chat->isMember($chat->recipient->id))->toBeTruthy();
});

it('can determine the partner for the authenticated user', function () {
    $chat = Chat::factory()->make();

    expect($chat->partner())->toBeNull();

    actingAs($chat->initiator);

    expect($chat->partner())->toEqual($chat->recipient);

    actingAs($chat->recipient);

    expect($chat->partner())->toEqual($chat->initiator);

    actingAs(User::factory()->create());

    expect($chat->partner())->toBeNull();
});
