<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';

    protected $fillable = [
    'pasien_id',
    'user_id',
    'dokter_id',
    'tanggal_jadwal',
    'jam_jadwal',
    'status_jadwal',
    'keterangan',
    ];

    // Relasi ke dokter yang menangani jadwal ini
    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    // Relasi ke tabel pasien
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
