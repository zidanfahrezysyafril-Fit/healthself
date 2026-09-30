<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use App\Models\RiwayatChat;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        // Harus login
        if (!auth()->check()) {
            return response()->json([
                'error' => true,
                'reply' => 'Silakan login terlebih dahulu untuk menggunakan chatbot.',
                'redirect' => route('login'),
            ], 401);
        }

        $request->validate([
            'message' => 'required|string'
        ]);

        $message = trim($request->message);

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
                    ->post(
                        'https://api.groq.com/openai/v1/chat/completions',
                        [
                            'model' => 'openai/gpt-oss-20b',
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
                        ]
                    );

                if (
                    isset(
                        $translateResponse['choices'][0]['message']['content']
                    )
                ) {
                    $messageForSearch = trim(
                        $translateResponse['choices'][0]['message']['content']
                    );
                }
            } catch (\Exception $e) {

                $messageForSearch = $message;
            }

            /*
            |--------------------------------------------------------------------------
            | RAG SEARCH (PINECONE)
            |--------------------------------------------------------------------------
            */

            $pineconeApiKey = env('PINECONE_API_KEY');
            $pineconeHost = env('PINECONE_HOST');
            
            $ragContext = '';

            if ($pineconeApiKey && $pineconeHost) {
                // 1. Create Embeddings using Pinecone Inference
                $embedResponse = Http::withHeaders([
                    'Api-Key' => $pineconeApiKey,
                    'Content-Type' => 'application/json',
                    'X-Pinecone-API-Version' => '2024-10'
                ])->post('https://api.pinecone.io/embed', [
                    'model' => 'multilingual-e5-large',
                    'parameters' => [
                        'input_type' => 'query'
                    ],
                    'inputs' => [
                        ['text' => $messageForSearch]
                    ]
                ]);

                if ($embedResponse->successful() && isset($embedResponse['data'][0]['values'])) {
                    $queryEmbedding = $embedResponse['data'][0]['values'];

                    // 2. Query Pinecone Index
                    $queryResponse = Http::withHeaders([
                        'Api-Key' => $pineconeApiKey,
                        'Content-Type' => 'application/json',
                    ])->post('https://' . $pineconeHost . '/query', [
                        'vector' => $queryEmbedding,
                        'topK' => 5,
                        'includeMetadata' => true
                    ]);

                    if ($queryResponse->successful() && isset($queryResponse['matches'])) {
                        foreach ($queryResponse['matches'] as $match) {
                            $metadata = $match['metadata'] ?? [];
                            $q = $metadata['question'] ?? '';
                            $a = $metadata['answer'] ?? '';
                            
                            $ragContext .= "Question:\n{$q}\n\nAnswer:\n{$a}\n\n---------------------\n";
                        }
                    } else {
                        \Log::error('Pinecone Query Error:', ['res' => $queryResponse->body()]);
                    }
                } else {
                    \Log::error('Pinecone Embed Error:', ['res' => $embedResponse->body()]);
                }
            }

            $ragContext = trim($ragContext);

            \Log::info('RAG CONTEXT:', [
                'query' => $message,
                'context' => $ragContext
            ]);

            if (empty($ragContext)) {
                $ragContext = 'Tidak ditemukan informasi yang relevan.';
            }

            /*
            |--------------------------------------------------------------------------
            | DEBUG LOG
            |--------------------------------------------------------------------------
            */

            Log::info('HealthSelf Chat', [
                'original_message' => $message,
                'translated_message' => $messageForSearch,
                'rag_context' => substr($ragContext, 0, 2000),
            ]);

            /*
            |--------------------------------------------------------------------------
            | CHAT HISTORY
            |--------------------------------------------------------------------------
            */

            $chatHistory = session()->get('chat_history', []);

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
                ->post(
                    'https://api.groq.com/openai/v1/chat/completions',
                    [
                        'model' => 'openai/gpt-oss-20b',
                        'messages' => $messages,
                        'temperature' => 0.3
                    ]
                );

            if ($response->failed()) {

                return response()->json([
                    'reply' => 'AI sedang sibuk. Silakan coba lagi beberapa saat.'
                ], 500);
            }

            $reply =
                $response['choices'][0]['message']['content']
                ?? 'Maaf, saya tidak dapat menjawab saat ini.';

            /*
            |--------------------------------------------------------------------------
            | AUTOMATED ESCALATION (DETEKSI SENSITIF)
            |--------------------------------------------------------------------------
            */
            $sensitiveKeywords = [
                'bunuh diri', 'ingin mati', 'akhiri hidup', 'menyakiti diri',
                'depresi berat', 'tidak kuat lagi', 'putus asa',
                'sayat', 'overdosis', 'tidak ada gunanya hidup'
            ];

            $isFlagged = false;
            $flagReason = null;
            $lowerMessage = strtolower($message);

            foreach ($sensitiveKeywords as $keyword) {
                if (str_contains($lowerMessage, $keyword)) {
                    $isFlagged = true;
                    $flagReason = 'Sistem Otomatis: Terdeteksi kata kunci bahaya ("' . $keyword . '").';
                    break;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE CHAT
            |--------------------------------------------------------------------------
            */

            RiwayatChat::create([
                'id_user' => auth()->id(),
                'pesan_user' => $message,
                'respon_bot' => $reply,
                'waktu_chat' => now(),
                'is_flagged' => $isFlagged,
                'flag_reason' => $flagReason,
            ]);

            /*
            |--------------------------------------------------------------------------
            | MEMORY SESSION
            |--------------------------------------------------------------------------
            */

            $chatHistory[] = [
                'role' => 'user',
                'content' => $message
            ];

            $chatHistory[] = [
                'role' => 'assistant',
                'content' => $reply
            ];

            if (count($chatHistory) > 20) {
                $chatHistory = array_slice($chatHistory, -20);
            }

            session()->put('chat_history', $chatHistory);

            return response()->json([
                'reply' => $reply
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'reply' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
