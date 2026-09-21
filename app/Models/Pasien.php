<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
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

    // Ikuti rantai merge sampai ke data pasien yang aktif
    public function resolveAktif()
    {
        $pasien = $this;
        while (!$pasien->is_aktif && $pasien->merged_ke_id) {
            $pasien = $pasien->mergedKe;
        }
        return $pasien;
    }
}
