<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportDataset extends Command
{
    protected $signature = 'dataset:import {file?}';
    protected $description = 'Import dataset dari CSV ke tabel health_knowledge';

    public function handle()
    {
        $filename = $this->argument('file') ?? 'counselchat-data.csv';
        $path = storage_path('app/private/' . $filename);

        if (!file_exists($path)) {
            $this->error('File tidak ditemukan: ' . $path);
            return Command::FAILURE;
        }

        $this->info("Membaca file CSV: {$filename}");
        $file = fopen($path, 'r');
        
        $header = fgetcsv($file);
        $count = 0;
        
        // Asumsi format counselchat-data.csv:
        // questionID, questionTitle, questionText, questionUrl, topics, therapistName, therapistUrl, answerText, upvotes
        
        $idxTitle = array_search('questionTitle', $header);
        $idxText = array_search('questionText', $header);
        $idxAnswer = array_search('answerText', $header);

        if ($idxTitle === false || $idxAnswer === false) {
            $this->error('Header CSV tidak cocok dengan counselchat format.');
            return Command::FAILURE;
        }

        $records = [];
        DB::table('health_knowledge')->truncate(); // Bersihkan data lama

        while (($row = fgetcsv($file)) !== false) {
            $title = $row[$idxTitle] ?? '';
            $text = $row[$idxText] ?? '';
            $answer = $row[$idxAnswer] ?? '';
            
            // Buang tag HTML simpel dari jawaban jika ada
            $answer = strip_tags($answer);
            
            if (empty($title) && empty($text)) continue;

            $records[] = [
                'category' => 'General',
                'question' => trim($title . "\n" . $text),
                'answer' => trim($answer),
                'source' => 'counselchat-data',
                'embedding' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $count++;
            
            // Insert per 100 baris untuk efisiensi RAM
            if (count($records) >= 100) {
                DB::table('health_knowledge')->insert($records);
                $records = [];
            }
            
            // Batasi 2000 data agar sinkronisasi ke Pinecone tidak terlalu lama saat uji coba awal
            if ($count >= 2000) break; 
        }

        if (count($records) > 0) {
            DB::table('health_knowledge')->insert($records);
        }

        fclose($file);
        
        $this->info("Berhasil mengimpor {$count} baris data ke tabel health_knowledge.");
        $this->info("Silakan jalankan 'php artisan pinecone:sync' kembali.");
        
        return Command::SUCCESS;
    }
}
