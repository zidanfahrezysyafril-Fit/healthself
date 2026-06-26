<?php

namespace App\Http\Controllers;

use App\Models\HealthData;

class ImportController extends Controller
{
    public function import()
    {
        set_time_limit(300);

        $file = fopen(storage_path('app/public/health.csv'), 'r');

        // Header CSV
        $headers = fgetcsv($file);

        $count = 0;

        while (($row = fgetcsv($file, 10000, ',')) !== FALSE) {

            // LIMIT DATA
            if ($count >= 300) {
                break;
            }

            $disease = $row[0];

            $symptoms = [];

            for ($i = 1; $i < count($headers); $i++) {

                $symptoms[$headers[$i]] = $row[$i];
            }

            HealthData::create([

                'disease' => $disease,

                'symptoms' => json_encode($symptoms)

            ]);

            $count++;
        }

        fclose($file);

        return "Import Success: {$count} data";
    }
}