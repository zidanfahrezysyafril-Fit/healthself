<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChatRequest;
use App\Http\Resources\Api\ChatResource;
use App\Helpers\ApiResponse;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function history()
    {
        $userId = auth()->id();
        
        $chats = collect();
        if ($userId) {
            $chats = $this->chatService->getHistory($userId);
        }

        return ApiResponse::success(ChatResource::collection($chats), 'Chat history retrieved successfully.');
    }

    public function send(ChatRequest $request)
    {
        try {
            $chatHistory = session()->get('api_chat_history', []);
            $uuid = session()->get('conversation_uuid');

            $result = $this->chatService->processChatMessage(
                trim($request->validated('message')),
                auth()->id() ?? 1,
                $chatHistory,
                $uuid
            );

            session()->put('api_chat_history', $result['history']);
            session()->put('conversation_uuid', $result['meta']['conversation_uuid']);

            $resource = (new ChatResource($result['record']))->additional($result['meta']);

            return ApiResponse::success($resource, 'AI response generated');

        } catch (\Exception $e) {
            Log::error('API Chat Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            
            return ApiResponse::error(
                'Terjadi kesalahan saat memproses permintaan chat.',
                500,
                env('APP_DEBUG') ? $e->getMessage() : null
            );
        }
    }
}
