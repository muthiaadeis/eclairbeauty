<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotifikasiLog extends Model
{
    protected $table = 'notifikasi_log';
    public $timestamps = false;
    protected $fillable = ['pasien_id', 'jadwal_id', 'judul', 'pesan', 'tipe'];
}
