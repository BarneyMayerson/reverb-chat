<?php

use App\Broadcasting\ChatChannel;
use App\Broadcasting\OnlineUsersChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('Chat.{chat}', ChatChannel::class);

Broadcast::channel('OnlineUsers', OnlineUsersChannel::class);
