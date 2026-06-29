<?php

namespace App\Services;

use App\Models\RiwayatChat;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Illuminate\Support\Str;

class ChatService
{
    public function getHistory(int $userId, int $limit = 20)
    {
        return RiwayatChat::where('id_user', $userId)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    public function processChatMessage(string $message, int $userId, array $chatHistory = [], ?string $conversationUuid = null)
    {
        $startTime = microtime(true);
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
                        ['role' => 'system', 'content' => 'Translate the user message into English. Return only the translation.'],
                        ['role' => 'user', 'content' => $message]
                    ],
                    'temperature' => 0
                ]);

            if (isset($translateResponse['choices'][0]['message']['content'])) {
                $messageForSearch = trim($translateResponse['choices'][0]['message']['content']);
            }
        } catch (\Exception $e) {
            Log::warning('Translation failed, using original message', ['error' => $e->getMessage()]);
        }

        $pythonPath = base_path('python/venv/bin/python');
        if (!file_exists($pythonPath)) {
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

        $messages[] = ['role' => 'user', 'content' => $message];

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

        $chatRecord = RiwayatChat::create([
            'id_user' => $userId,
            'pesan_user' => $message,
            'respon_bot' => $reply,
            'waktu_chat' => now(),
        ]);

        $chatHistory[] = ['role' => 'user', 'content' => $message];
        $chatHistory[] = ['role' => 'assistant', 'content' => $reply];
        if (count($chatHistory) > 20) {
            $chatHistory = array_slice($chatHistory, -20);
        }

        return [
            'record' => $chatRecord,
            'history' => $chatHistory,
            'meta' => [
                'conversation_uuid' => $conversationUuid ?? (string) Str::uuid(),
                'response_time' => round((microtime(true) - $startTime) * 1000) . 'ms',
                'model_name' => $response['model'] ?? 'llama-3.1-8b-instant',
                'token_usage' => $response['usage'] ?? null,
                'sources' => ['rag_python_engine']
            ]
        ];
    }
}
