<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$knowledge = [
    [
        'question' => 'Apa itu demam dan bagaimana cara mengatasinya?',
        'answer' => 'Demam adalah kondisi ketika suhu tubuh meningkat di atas normal (biasanya di atas 38 derajat Celcius). Ini adalah tanda bahwa tubuh sedang melawan infeksi atau penyakit. Cara mengatasinya: istirahat yang cukup, minum banyak air putih untuk mencegah dehidrasi, gunakan pakaian tipis, dan Anda bisa minum obat penurun panas seperti Paracetamol atau Ibuprofen jika diperlukan. Jika demam berlangsung lebih dari 3 hari, segera hubungi dokter.'
    ],
    [
        'question' => 'Apa penyebab sakit kepala dan bagaimana cara meredakannya?',
        'answer' => 'Sakit kepala bisa disebabkan oleh banyak hal, termasuk stres, kurang tidur, dehidrasi, kelelahan mata, atau telat makan. Cara meredakannya: minum air putih, istirahat di ruangan yang gelap dan tenang, pijat perlahan area kepala yang sakit, dan konsumsi obat pereda nyeri seperti Paracetamol.'
    ],
    [
        'question' => 'Bagaimana cara mencegah dan mengatasi flu (influenza)?',
        'answer' => 'Flu disebabkan oleh virus. Pencegahan terbaik adalah rajin mencuci tangan, makan makanan bergizi, dan memakai masker jika sedang sakit. Jika sudah terkena flu, perbanyak istirahat, konsumsi makanan hangat, minum vitamin C, dan gunakan obat pereda gejala flu yang dijual bebas. Antibiotik tidak efektif untuk flu karena flu disebabkan oleh virus, bukan bakteri.'
    ],
    [
        'question' => 'Apa gejala asam lambung naik (GERD) dan bagaimana penanganannya?',
        'answer' => 'Gejala asam lambung naik (GERD) meliputi rasa terbakar di dada (heartburn), sering bersendawa, mual, dan rasa asam di mulut. Penanganannya: hindari makanan pedas, asam, dan berlemak; jangan langsung berbaring setelah makan (tunggu 2-3 jam); kurangi stres; dan konsumsi obat antasida jika diperlukan.'
    ],
    [
        'question' => 'Bagaimana cara mengatasi susah tidur atau insomnia?',
        'answer' => 'Untuk mengatasi insomnia, coba atur jadwal tidur yang konsisten setiap hari. Hindari kafein (kopi, teh) setelah sore hari, jauhkan layar gadget setidaknya 1 jam sebelum tidur, buat suasana kamar yang nyaman dan gelap, serta lakukan relaksasi seperti membaca buku atau mendengarkan musik tenang sebelum tidur.'
    ],
    [
        'question' => 'Apa yang harus dilakukan jika terjadi luka bakar ringan?',
        'answer' => 'Untuk luka bakar ringan, segera alirkan air dingin (bukan air es) pada area luka selama 10-15 menit untuk menghentikan proses pembakaran. Jangan mengoleskan pasta gigi atau mentega karena bisa memicu infeksi. Setelah dingin, oleskan salep luka bakar atau lidah buaya, lalu tutup dengan perban steril yang longgar.'
    ],
    [
        'question' => 'Apa itu hipertensi atau darah tinggi?',
        'answer' => 'Hipertensi adalah kondisi tekanan darah di atas batas normal (di atas 130/80 mmHg). Ini sering disebut "pembunuh diam-diam" karena jarang menunjukkan gejala. Cara mencegah dan mengatasinya adalah dengan mengurangi konsumsi garam, rajin berolahraga, menjaga berat badan ideal, dan menghindari stres serta rokok.'
    ],
    [
        'question' => 'Apa gejala diabetes dan bagaimana mencegahnya?',
        'answer' => 'Gejala umum diabetes (kencing manis) meliputi sering buang air kecil (terutama malam hari), mudah haus, cepat lapar, luka sulit sembuh, dan kesemutan. Pencegahannya adalah dengan membatasi konsumsi gula dan karbohidrat sederhana, rutin berolahraga, serta memperbanyak konsumsi serat dari sayur dan buah.'
    ]
];

foreach ($knowledge as $item) {
    // Check if question exists
    $exists = DB::table('health_knowledge')->where('question', $item['question'])->exists();
    if (!$exists) {
        DB::table('health_knowledge')->insert([
            'question' => $item['question'],
            'answer' => $item['answer'],
            'category' => 'Umum'
        ]);
        echo "Inserted: " . $item['question'] . "\n";
    }
}

echo "Database seeded successfully!\n";
echo "Now running python embedding script...\n";

use Symfony\Component\Process\Process;
$pythonPath = 'C:\\Users\\Pongo\\AppData\\Local\\Programs\\Python\\Python311\\python.exe';
$process = new Process([$pythonPath, base_path('python/generate_embeddings.py')]);
$process->setEnv(getenv());
$process->setTimeout(300);
$process->run();

echo $process->getOutput();
if (!$process->isSuccessful()) {
    echo $process->getErrorOutput();
}

