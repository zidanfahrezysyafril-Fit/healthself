<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            Schema::table('artikel', function (Blueprint $table) {
                $table->index('status');
                $table->index('slug');
            });
        } catch (\Exception $e) {
            // Abaikan jika index sudah ada akibat kegagalan migrasi sebelumnya
        }
        
        try {
            Schema::table('login_attempts', function (Blueprint $table) {
                $table->index('identifier');
            });
        } catch (\Exception $e) {
            // Abaikan jika index sudah ada
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['slug']);
        });

        Schema::table('login_attempts', function (Blueprint $table) {
            $table->dropIndex(['identifier']);
        });
    }
};
