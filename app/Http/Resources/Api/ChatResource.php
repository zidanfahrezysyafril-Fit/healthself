<?php

namespace App\Http\Resources\Api;

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
            'conversation_uuid' => $this->additional['conversation_uuid'] ?? (string) \Illuminate\Support\Str::uuid(),
            'reply' => $this->respon_bot,
            'sources' => $this->additional['sources'] ?? [], 
            'response_time' => $this->additional['response_time'] ?? '0ms',
            'model_name' => $this->additional['model_name'] ?? 'unknown',
            'token_usage' => $this->additional['token_usage'] ?? null,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : now()->toIso8601String(),
        ];
    }
}
