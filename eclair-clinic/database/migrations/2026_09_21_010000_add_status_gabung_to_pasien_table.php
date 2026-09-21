<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            // Data pasien duplikat TIDAK dihapus (rekam medis wajib disimpan
            // sesuai UU Rekam Medis), cukup dinonaktifkan.
            $table->boolean('is_aktif')->default(true)->after('jenis_kelamin');

            // Menunjuk ke data pasien tujuan penggabungan, kalau baris ini
            // adalah hasil merge (nonaktif).
            $table->foreignId('merged_ke_id')
                  ->nullable()
                  ->after('is_aktif')
                  ->constrained('pasien')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->dropForeign(['merged_ke_id']);
            $table->dropColumn(['is_aktif', 'merged_ke_id']);
        });
    }
};
