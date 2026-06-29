<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChatRequest;
use App\Http\Resources\Api\ChatResource;
use App\Helpers\ApiResponse;
use Illuminate\Support\Str;
use App\Models\RiwayatChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ChatController extends Controller
{
    public function history()
    {
        // For simplicity, retrieving the last 20 chats
        $userId = auth()->check() ? auth()->id() : null;
        
        $chats = collect();
        if ($userId) {
            $chats = RiwayatChat::where('id_user', $userId)
                ->orderBy('created_at', 'desc')
                ->take(20)
                ->get();
        }

        return ApiResponse::success(ChatResource::collection($chats), 'Chat history retrieved successfully.');
    }

    public function send(ChatRequest $request)
    {
        $message = trim($request->validated('message'));
        $startTime = microtime(true);

        try {
            /*
            |--------------------------------------------------------------------------
            | TRANSLATE INDONESIA -> ENGLISH
            |--------------------------------------------------------------------------
            */
            $messageForSearch = $message;

            try {
                $translateResponse = Http::timeout(20)
                    ->retry(2, 1000)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                        'Content-Type' => 'application/json',
                    ])
                    ->post('https://api.groq.com/openai/v1/chat/completions', [
                        'model' => 'llama-3.1-8b-instant',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => 'Translate the user message into English. Return only the translation.'
                            ],
                            [
                                'role' => 'user',
                                'content' => $message
                            ]
                        ],
                        'temperature' => 0
                    ]);

                if (isset($translateResponse['choices'][0]['message']['content'])) {
                    $messageForSearch = trim($translateResponse['choices'][0]['message']['content']);
                }
            } catch (\Exception $e) {
                Log::warning('Translation failed, using original message', ['error' => $e->getMessage()]);
                $messageForSearch = $message;
            }

            /*
            |--------------------------------------------------------------------------
            | RAG SEARCH
            |--------------------------------------------------------------------------
            */
            $pythonPath = base_path('python/venv/bin/python');
            if (!file_exists($pythonPath)) {
                // Fallback for Windows local development
                $pythonPath = 'C:\\Users\\Pongo\\AppData\\Local\\Programs\\Python\\Python311\\python.exe';
            }

            $process = new Process([
                $pythonPath,
                base_path('python/search.py'),
                $messageForSearch
            ]);

            $process->run();
            $ragContext = trim($process->getOutput());

            if (empty($ragContext)) {
                $ragContext = 'Tidak ditemukan informasi yang relevan.';
            }

            Log::info('RAG CONTEXT (API)', [
                'query' => $message,
                'context' => substr($ragContext, 0, 1000)
            ]);

            /*
            |--------------------------------------------------------------------------
            | CHAT HISTORY (MEMORY)
            |--------------------------------------------------------------------------
            */
            // You might want to use session, or retrieve from DB. Using session for consistency with web:
            $chatHistory = session()->get('api_chat_history', []);

            $messages = [
                [
                    'role' => 'system',
                    'content' => "
Kamu adalah HealthSelf AI.

Gunakan informasi berikut sebagai sumber utama jawaban:

{$ragContext}

ATURAN:
- Jawab dalam Bahasa Indonesia.
- Gunakan context sebagai referensi utama.
- Jangan memberikan diagnosa medis pasti.
- Jika kondisi terlihat serius, sarankan konsultasi ke dokter atau psikolog.
- Jika informasi tidak ditemukan dalam context, katakan dengan jujur.
- Berikan jawaban yang ramah dan mudah dipahami.
- Ingat percakapan sebelumnya.
"
                ]
            ];

            foreach ($chatHistory as $chat) {
                $messages[] = $chat;
            }

            $messages[] = [
                'role' => 'user',
                'content' => $message
            ];

            /*
            |--------------------------------------------------------------------------
            | GROQ RESPONSE
            |--------------------------------------------------------------------------
            */
            $response = Http::timeout(60)
                ->retry(3, 2000)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.1-8b-instant',
                    'messages' => $messages,
                    'temperature' => 0.3
                ]);

            if ($response->failed()) {
                throw new \Exception('Failed to communicate with AI Provider.');
            }

            $reply = $response['choices'][0]['message']['content'] ?? 'Maaf, saya tidak dapat menjawab saat ini.';

            /*
            |--------------------------------------------------------------------------
            | SAVE CHAT
            |--------------------------------------------------------------------------
            */
            $chatRecord = RiwayatChat::create([
                'id_user' => auth()->id() ?? 1, // Fallback if no auth during development
                'pesan_user' => $message,
                'respon_bot' => $reply,
                'waktu_chat' => now(),
            ]);

            $responseTime = round((microtime(true) - $startTime) * 1000) . 'ms';
            $tokenUsage = $response['usage'] ?? null;
            $modelName = $response['model'] ?? 'llama-3.1-8b-instant';
            $conversationUuid = session()->get('conversation_uuid');
            if (!$conversationUuid) {
                $conversationUuid = (string) Str::uuid();
                session()->put('conversation_uuid', $conversationUuid);
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE MEMORY
            |--------------------------------------------------------------------------
            */
            $chatHistory[] = ['role' => 'user', 'content' => $message];
            $chatHistory[] = ['role' => 'assistant', 'content' => $reply];

            if (count($chatHistory) > 20) {
                $chatHistory = array_slice($chatHistory, -20);
            }
            session()->put('api_chat_history', $chatHistory);

            /*
            |--------------------------------------------------------------------------
            | RETURN JSON RESPONSE
            |--------------------------------------------------------------------------
            */
            $resource = (new ChatResource($chatRecord))->additional([
                'conversation_uuid' => $conversationUuid,
                'response_time' => $responseTime,
                'model_name' => $modelName,
                'token_usage' => $tokenUsage,
                'sources' => ['rag_python_engine'],
            ]);

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
