<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    protected $table = 'rekam_medis';

    public $timestamps = false;

    protected $fillable = [
        'pasien_id',
        'user_id',
        'tanggal_tindakan',
        'keluhan',
        'hasil_konsultasi',
        'catatan_tindakan',
        'tanggal_kontrol',
    ];

    // Relasi ke pasien
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    // Relasi ke user (dokter)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke dokumentasi
    public function dokumentasi()
    {
        return $this->hasMany(DokumentasiTindakan::class, 'rekam_medis_id');
    }
}
