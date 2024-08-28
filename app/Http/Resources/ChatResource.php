<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'partner' => UserResource::make($this->partner()),
            'initiator' => UserResource::make($this->whenExistsLoaded('initiator')),
            'recipient' => UserResource::make($this->whenExistsLoaded('recipient')),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
