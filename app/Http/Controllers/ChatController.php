<?php

namespace App\Http\Controllers;

use App\Events\Chat\MessageSent;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::allByUser(Auth::id());

        return Inertia::render('Chats/Index', [
            'chats' => ChatResource::collection($chats),
        ]);
    }

    public function show(Chat $chat)
    {
        $this->authorize('view', $chat);

        $chat->load('messages');

        return Inertia::render('Chats/Show', [
            'chat' => ChatResource::make($chat),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => ['required', 'integer', 'exists:users,id', Rule::notIn([Auth::id()])],
            'message' => 'required|string|min:1|max:240',
        ]);

        $chat = Chat::findWith($request->input('recipient_id'));

        if ($chat) {
            return to_route('chats.show', $chat);
        }

        $chat = DB::transaction(function () use ($chat, $request) {
            $chat = Chat::create([
                'initiator_id' => Auth::id(),
                'recipient_id' => $request->input('recipient_id'),
            ]);

            $chat->messages()->create([
                'sender_id' => Auth::id(),
                'message' => $request->input('message'),
            ]);

            return $chat;
        });

        return to_route('chats.show', $chat);
    }

    public function message(Request $request, Chat $chat)
    {
        $this->authorize('message', $chat);

        $request->validate([
            'text' => 'required|string|min:1|max:240',
        ]);

        $message = $chat->messages()->create([
            'sender_id' => Auth::user()->id,
            'message' => $request->input('text'),
        ]);

        event(MessageSent::make(ChatResource::make($chat), MessageResource::make($message)));

        return to_route('chats.show', $chat);
    }
}
