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
    ];

    // Relasi ke jadwal
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'pasien_id');
    }
}
