<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;
use App\Models\HealthKnowledge;

class ImportHealthKnowledge extends Command
{
    protected $signature = 'health:import';

    protected $description = 'Import Health Knowledge Dataset';

    public function handle()
    {
        // COUNSEL CHAT
        $csv = Reader::createFromPath(
            storage_path('app/private/counselchat-data.csv'),
            'r'
        );

        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {

            HealthKnowledge::create([
                'category' => 'Mental Health',

                'question' =>
                    $row['questionTitle']
                    ?? $row['questionText']
                    ?? '',

                'answer' =>
                    $row['answerText']
                    ?? '',

                'source' => 'CounselChat'
            ]);
        }

        $this->info('CounselChat imported');

        // MEDQUAD
        $csv = Reader::createFromPath(
            storage_path('app/private/medquad.csv'),
            'r'
        );

        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {

            HealthKnowledge::create([
                'category' => 'General Health',

                'question' =>
                    $row['question']
                    ?? '',

                'answer' =>
                    $row['answer']
                    ?? '',

                'source' =>
                    $row['source']
                    ?? 'MedQuad'
            ]);
        }

        $this->info('MedQuad imported');
    }
}