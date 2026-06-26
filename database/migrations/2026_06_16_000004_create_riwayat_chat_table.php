<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_chat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->text('pesan_user');
            $table->text('respon_bot');
            $table->boolean('is_flagged')->default(false); // ditandai berbahaya oleh konselor
            $table->string('flag_reason')->nullable();
            $table->timestamp('waktu_chat')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_chat');
    }
};
