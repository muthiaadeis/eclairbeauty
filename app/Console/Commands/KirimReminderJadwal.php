<?php

namespace App\Console\Commands;

use App\Models\Jadwal;
use App\Models\NotifikasiLog;
use App\Services\FcmService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class KirimReminderJadwal extends Command
{
    protected $signature = 'jadwal:kirim-reminder';
    protected $description = 'Kirim notifikasi pengingat H-3, H-2, H-1 ke pasien yang punya jadwal';

    public function handle(FcmService $fcm)
    {
        $rules = [3 => 'H-3', 2 => 'H-2', 1 => 'H-1'];

        foreach ($rules as $hMinus => $label) {
            $tanggalTarget = Carbon::today()->addDays($hMinus)->toDateString();

            $jadwal = Jadwal::with('pasien')
                ->whereDate('tanggal_jadwal', $tanggalTarget)
                ->where('status_jadwal', '!=', 'batal')
                ->get();

            foreach ($jadwal as $j) {
                $pasien = $j->pasien;
                if (!$pasien || !$pasien->fcm_token) continue;

                $sudahDikirim = NotifikasiLog::where('jadwal_id', $j->id)->where('tipe', $label)->exists();
                if ($sudahDikirim) continue;

                $judul = "$label Jadwal Tindakan";
                $jam   = substr($j->jam_jadwal, 0, 5);
                $pesan = "Jangan lupa jadwal Anda tanggal {$j->tanggal_jadwal} pukul {$jam} WIB di Eclair Beauty Clinic.";

                $terkirim = $fcm->sendToToken($pasien->fcm_token, $judul, $pesan, [
                    'jadwal_id' => (string) $j->id,
                    'tipe' => $label,
                ]);

                if ($terkirim) {
                    NotifikasiLog::create([
                        'pasien_id' => $pasien->id,
                        'jadwal_id' => $j->id,
                        'judul' => $judul,
                        'pesan' => $pesan,
                        'tipe' => $label,
                    ]);
                    $this->info("Notifikasi $label terkirim ke pasien #{$pasien->id}");
                }
            }
        }
    }
}
