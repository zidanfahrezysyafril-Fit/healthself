<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Kategori;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin default
        User::create([
            'name'     => 'Administrator',
            'email'    => env('ADMIN_EMAIL', 'admin@healthself.id'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'Admin@123')),
            'role'     => 'admin',
            'email_verified_at' => now(),
        ]);

        // Konselor contoh
        User::create([
            'name'     => 'Dr. Sari Dewi, M.Psi',
            'email'    => 'konselor@healthself.id',
            'password' => Hash::make('Konselor@123'),
            'role'     => 'konselor',
            'nim_nip'  => 'NIP-12345678',
            'prodi'    => 'Psikologi',
            'email_verified_at' => now(),
        ]);

        // Kategori artikel
        $kategoris = [
            ['nama_kategori' => 'Kesehatan Mental', 'icon' => '🧠'],
            ['nama_kategori' => 'Psikologi', 'icon' => '💭'],
            ['nama_kategori' => 'Manajemen Stres', 'icon' => '🧘'],
            ['nama_kategori' => 'Kesehatan Fisik', 'icon' => '💪'],
            ['nama_kategori' => 'Nutrisi', 'icon' => '🥗'],
            ['nama_kategori' => 'Hubungan Sosial', 'icon' => '🤝'],
            ['nama_kategori' => 'Motivasi & Produktivitas', 'icon' => '🚀'],
        ];

        foreach ($kategoris as $k) {
            Kategori::create($k);
        }
    }
}
