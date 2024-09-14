<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Chat extends Model
{
    use HasFactory;

    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function isMember(int $userId): bool
    {
        return $this->initiator->id === $userId ||
            $this->recipient->id === $userId;
    }

    public function partner(): ?User
    {
        if (Auth::guest()) {
            return null;
        }

        if (! $this->isMember(Auth::id())) {
            return null;
        }

        return Auth::id() === $this->initiator_id
            ? $this->recipient
            : $this->initiator;
    }

    public static function allByUser(int $userId): Collection
    {
        return Chat::query()
            ->where('initiator_id', $userId)
            ->orWhere('recipient_id', $userId)
            ->get();
    }

    public static function findWith(int $userId): ?self
    {
        return self::query()
            ->where('initiator_id', Auth::id())
            ->where('recipient_id', $userId)
            ->orWhere('initiator_id', $userId)
            ->where('recipient_id', Auth::id())
            ->first();
    }
}
