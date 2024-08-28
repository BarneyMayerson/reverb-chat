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
        return Inertia::render('Chats/Show');
        // return Inertia::render('Chats/Show', [
        //     'chat' => $chat,
        //     'companion' => $chat->companion,
        //     'messages' => $chat->messages,
        // ]);
    }

    public function message(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => 'required|string|min:1|max:240',
        ]);

        $chat->messages()->create([
            'sender_id' => Auth::user()->id,
            'message' => $request('message'),
        ]);

        return to_route('chats.show', $chat);
    }
}
