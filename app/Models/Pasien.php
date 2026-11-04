<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Pasien extends Model
{
    use HasApiTokens;

    protected $table = 'pasien';

    protected $fillable = [
        'nama_pasien',
        'email_google',
        'google_uid',
        'no_hp',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
        'is_aktif',
        'merged_ke_id',
        'fcm_token'
    ];

    // Relasi ke jadwal
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'pasien_id');
    }

    // Kalau data ini nonaktif (hasil merge), ini pasien tujuannya
    public function mergedKe()
    {
        return $this->belongsTo(Pasien::class, 'merged_ke_id');
    }

    // Cari pasien dari akun Google. Kalau ada lebih dari satu baris
    // dengan google_uid yang sama, utamakan yang masih aktif.
    public static function cariByGoogleUid(?string $uid): ?self
    {
        if (!$uid) return null;

        return static::where('google_uid', $uid)
                     ->orderByDesc('is_aktif')
                     ->first();
    }

    // Ikuti rantai merge sampai ke data pasien yang aktif.
    // CATATAN: kalau data nonaktif tanpa tujuan merge (dinonaktifkan manual),
    // hasilnya tetap nonaktif — pemanggil WAJIB cek is_aktif sesudahnya.
    public function resolveAktif()
    {
        $pasien = $this;
        while (!$pasien->is_aktif && $pasien->merged_ke_id) {
            $pasien = $pasien->mergedKe;
        }
        return $pasien;
    }
}
