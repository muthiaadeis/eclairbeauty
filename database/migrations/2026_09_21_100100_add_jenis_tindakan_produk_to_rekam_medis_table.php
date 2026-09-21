<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->string('jenis_tindakan')->nullable()->after('tanggal_tindakan');
            $table->text('produk_digunakan')->nullable()->after('catatan_tindakan');
        });
    }

    public function down(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->dropColumn(['jenis_tindakan', 'produk_digunakan']);
        });
    }
};
