<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Symfony\Component\Process\Process;
$pythonPath = 'C:\\Users\\Pongo\\AppData\\Local\\Programs\\Python\\Python311\\python.exe';
$process = new Process([$pythonPath, base_path('python/search.py'), 'fever is']);
try {
    $process->run();
    print_r([
        'success' => $process->isSuccessful(),
        'output' => $process->getOutput(),
        'error' => $process->getErrorOutput()
    ]);
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage();
}
