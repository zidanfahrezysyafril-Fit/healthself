<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SyncToPinecone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pinecone:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync health_knowledge table to Pinecone vector database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pineconeApiKey = env('PINECONE_API_KEY');
        $pineconeHost = env('PINECONE_HOST');

        if (!$pineconeApiKey || !$pineconeHost) {
            $this->error('PINECONE_API_KEY atau PINECONE_HOST belum diatur di .env');
            return Command::FAILURE;
        }

        $this->info('Mengambil data dari tabel health_knowledge...');

        // Ambil semua data (pastikan tidak terlalu besar, atau gunakan chunking)
        $knowledgeData = DB::table('health_knowledge')->get();

        if ($knowledgeData->isEmpty()) {
            $this->warn('Tabel health_knowledge kosong.');
            return Command::SUCCESS;
        }

        $this->info('Total data yang akan di-sync: ' . $knowledgeData->count());

        // Batch processing (Pinecone inference mendukung batching, kita pakai batch 50 agar aman)
        $batches = $knowledgeData->chunk(50);
        $totalBatches = $batches->count();
        $currentBatch = 1;

        foreach ($batches as $batch) {
            $this->info("Memproses batch {$currentBatch} dari {$totalBatches}...");

            $inputs = [];
            $batchData = $batch->values(); // Reset array keys to 0, 1, 2...
            
            foreach ($batchData as $row) {
                // Menggabungkan pertanyaan dan jawaban sebagai konteks utama untuk embedding
                $text = "Pertanyaan: " . ($row->question ?? '') . "\nJawaban: " . ($row->answer ?? '');
                
                // Fix Malformed UTF-8 characters
                $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
                
                $inputs[] = ['text' => mb_substr($text, 0, 1500)]; // Limit panjang teks
            }

            // 1. Generate Embeddings via Pinecone Inference API
            $embedResponse = Http::timeout(120)->withHeaders([
                'Api-Key' => $pineconeApiKey,
                'Content-Type' => 'application/json',
                'X-Pinecone-API-Version' => '2024-10'
            ])->post('https://api.pinecone.io/embed', [
                'model' => 'multilingual-e5-large',
                'parameters' => [
                    'input_type' => 'passage', // Gunakan 'passage' untuk data dokumen (lawan dari 'query')
                    'truncate' => 'END'
                ],
                'inputs' => $inputs
            ]);

            if (!$embedResponse->successful() || !isset($embedResponse['data'])) {
                $this->error('Gagal melakukan embedding pada batch ' . $currentBatch);
                $this->error($embedResponse->body());
                return Command::FAILURE;
            }

            $embeddings = $embedResponse['data'];

            // 2. Siapkan payload untuk di-upsert ke Pinecone
            $vectors = [];
            foreach ($batchData as $index => $row) {
                // Pastikan embedding untuk index ini ada
                if (!isset($embeddings[$index]['values'])) {
                    continue;
                }

                $q = mb_convert_encoding($row->question ?? '', 'UTF-8', 'UTF-8');
                $a = mb_convert_encoding($row->answer ?? '', 'UTF-8', 'UTF-8');

                $vectors[] = [
                    'id' => 'knowledge_' . $row->id,
                    'values' => $embeddings[$index]['values'],
                    'metadata' => [
                        'question' => mb_substr($q, 0, 10000), // Batasi ukuran max
                        'answer' => mb_substr($a, 0, 30000)
                    ]
                ];
            }

            if (empty($vectors)) {
                $this->error("Vektor kosong pada batch {$currentBatch}, melewati upsert...");
                $currentBatch++;
                continue;
            }

            // 3. Upsert Vectors ke Pinecone Host
            $upsertResponse = Http::timeout(60)->withHeaders([
                'Api-Key' => $pineconeApiKey,
                'Content-Type' => 'application/json',
            ])->post('https://' . $pineconeHost . '/vectors/upsert', [
                'vectors' => $vectors
            ]);

            if ($upsertResponse->successful()) {
                $this->info("Berhasil melakukan upsert batch {$currentBatch}");
            } else {
                $this->error("Gagal melakukan upsert batch {$currentBatch}");
                $this->error($upsertResponse->body());
            }

            $currentBatch++;
        }

        $this->info('Sinkronisasi selesai!');
        return Command::SUCCESS;
    }
}
