<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->boolean('is_aktif')->default(true)->after('jenis_kelamin');
            $table->foreignId('merged_ke_id')->nullable()->after('is_aktif')
                  ->constrained('pasien')->onDelete('set null');
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
