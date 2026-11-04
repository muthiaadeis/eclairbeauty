<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';

    // Slot jam praktik (sama dengan yang ditampilkan di aplikasi mobile)
    public const SLOT_JAM = [
        '09:00', '09:30', '10:00', '10:30',
        '11:00', '11:30', '13:00', '13:30',
        '14:00', '14:30', '15:00', '15:30',
        '16:00', '16:30', '17:00',
    ];

    protected $fillable = [
        'pasien_id',
        'user_id',
        'dokter_id',
        'tanggal_jadwal',
        'jam_jadwal',
        'status_jadwal',
        'keterangan',
    ];

    // Slot pertama yang masih kosong untuk dokter pada tanggal tertentu (null kalau penuh semua)
    public static function slotKosong(string $tanggal, $dokterId): ?string
    {
        $terpakai = static::whereDate('tanggal_jadwal', $tanggal)
            ->where('dokter_id', $dokterId)
            ->where('status_jadwal', '!=', 'batal')
            ->pluck('jam_jadwal')
            ->map(fn ($jam) => substr($jam, 0, 5))
            ->all();

        foreach (self::SLOT_JAM as $jam) {
            if (!in_array($jam, $terpakai)) {
                return $jam;
            }
        }

        return null;
    }

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
