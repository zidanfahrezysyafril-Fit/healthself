<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konselor_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_riwayat')->constrained('riwayat_chat')->onDelete('cascade');
            $table->foreignId('id_konselor')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade'); // penerima komentar
            $table->text('komentar');
            $table->boolean('email_sent')->default(false);
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konselor_comments');
    }
};
