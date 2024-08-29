<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function message(Request $request, Chat $chat)
    {
        $this->authorize('message', $chat);

        $request->validate([
            'text' => 'required|string|min:1|max:240',
        ]);

        $chat->messages()->create([
            'sender_id' => Auth::user()->id,
            'message' => $request->input('text'),
        ]);

        return to_route('chats.show', $chat);
    }
}
