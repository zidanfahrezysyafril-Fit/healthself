<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artikel', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->longText('isi_konten');
            $table->string('thumbnail')->nullable();
            $table->foreignId('id_kategori')->constrained('kategori')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade'); // pembuat
            $table->foreignId('id_konselor')->nullable()->constrained('users')->onDelete('set null'); // validator
            $table->enum('status', ['draft', 'pending', 'published', 'rejected'])->default('pending');
            $table->text('catatan_validasi')->nullable();
            $table->timestamp('tanggal_publish')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artikel');
    }
};
