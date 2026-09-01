<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumentasiTindakan extends Model
{
    protected $table = 'dokumentasi_tindakan';

    public $timestamps = false;

    protected $fillable = [
        'rekam_medis_id',
        'foto_before',
        'foto_after',
        'keterangan',
    ];

    // Relasi ke rekam medis
    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'rekam_medis_id');
    }
}
