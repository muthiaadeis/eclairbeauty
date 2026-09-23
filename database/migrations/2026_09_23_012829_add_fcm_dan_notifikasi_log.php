<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->string('fcm_token')->nullable()->after('google_uid');
        });

        Schema::create('notifikasi_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasien')->onDelete('cascade');
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwal')->onDelete('set null');
            $table->string('judul');
            $table->text('pesan');
            $table->string('tipe'); // H-3 / H-2 / H-1
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::table('pasien', fn (Blueprint $t) => $t->dropColumn('fcm_token'));
        Schema::dropIfExists('notifikasi_log');
    }
};
