<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $artikel = \App\Models\Artikel::create([
        'judul' => 'Test', 
        'isi_konten' => 'Test', 
        'id_kategori' => 1, 
        'id_user' => 1, 
        'status' => 'pending'
    ]);
    echo "Success: Created article with ID " . $artikel->id . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
