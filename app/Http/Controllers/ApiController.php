<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Jadwal;
use App\Models\RekamMedis;
use App\Models\User;
use App\Models\NotifikasiLog;

class ApiController extends Controller
{
    // Register atau login pasien via Google
    public function registerPasien(Request $request)
    {
        $request->validate([
            'google_uid'   => 'required',
            'nama_pasien'  => 'required',
            'email_google' => 'required|email',
            // no_hp wajib & harus format nomor Indonesia (08xxxxxxxxxx, 10-13 digit)
            // supaya no_hp bisa dipakai sebagai identitas asli pasien, bukan cuma placeholder
            'no_hp'        => ['required', 'regex:/^08[0-9]{8,11}$/'],
        ], [
            'no_hp.required' => 'Nomor HP wajib diisi',
            'no_hp.regex'    => 'Format nomor HP tidak valid (contoh: 081234567890)',
        ]);

        // Identitas asli pasien adalah no_hp, bukan google_uid,
        // supaya login pakai akun Google lain tidak dianggap pasien baru.
        $pasien = Pasien::where('no_hp', $request->no_hp)->first();

        if ($pasien) {
            // Kalau data ini sudah pernah digabung ke pasien lain, ikuti ke data aktifnya
            $pasien = $pasien->resolveAktif();

            // Nomor HP sudah terdaftar -> tautkan akun Google yang baru login
            // ke data pasien yang sama (bukan bikin data baru).
            $pasien->update([
                'google_uid'   => $request->google_uid,
                'email_google' => $request->email_google,
            ]);
        } else {
            // Nomor HP belum pernah dipakai -> pasien baru
            $pasien = Pasien::create([
                'google_uid'   => $request->google_uid,
                'nama_pasien'  => $request->nama_pasien,
                'email_google' => $request->email_google,
                'no_hp'        => $request->no_hp,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'pasien' => $this->formatPasien($pasien),
            'token' => $request->google_uid,
        ]);
    }

    // Login pasien
    public function loginPasien(Request $request)
    {
        $pasien = Pasien::where('google_uid', $request->google_uid)->first();

        if(!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan'
            ], 404);
        }

        // Kalau akun Google ini terhubung ke data yang sudah digabung
        // (nonaktif), arahkan diam-diam ke data pasien yang aktif.
        $pasien = $pasien->resolveAktif();

        return response()->json([
            'success' => true,
            'pasien' => $pasien,
            'token' => $request->google_uid,
        ]);
    }

    // Profil pasien
    public function profil(Request $request)
    {
        return response()->json([
            'success' => true,
            'pasien' => $request->pasien,
        ]);
    }

    // Jadwal yang tersedia
    public function jadwalTersedia(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));

        $jamTersedia = [
            '09:00', '09:30', '10:00', '10:30',
            '11:00', '11:30', '13:00', '13:30',
            '14:00', '14:30', '15:00', '15:30',
            '16:00', '16:30', '17:00'
        ];

        $jamTerpakai = Jadwal::where('tanggal_jadwal', $tanggal)
                             ->where('status_jadwal', '!=', 'batal')
                             ->pluck('jam_jadwal')
                             ->map(function ($jam) {
                                 return substr($jam, 0, 5);
                             })
                             ->unique()
                             ->values()
                             ->toArray();

        return response()->json([
            'success' => true,
            'tanggal' => $tanggal,
            'jam_tersedia' => $jamTersedia,
            'jam_penuh' => $jamTerpakai,
        ]);
    }

    // Booking jadwal baru
    public function booking(Request $request)
    {
        $pasien = $request->pasien;

        $request->validate([
            'tanggal_jadwal' => 'required|date',
            'jam_jadwal' => 'required',
        ]);

        // Auto-assign ke dokter yang ada (saat ini baru 1 dokter aktif)
        $dokter = User::where('role', 'dokter')
                      ->where('status_aktif', true)
                      ->first();

        // Cek apakah jam masih tersedia untuk dokter ini
        $cek = Jadwal::where('tanggal_jadwal', $request->tanggal_jadwal)
                     ->where('jam_jadwal', $request->jam_jadwal)
                     ->where('dokter_id', $dokter?->id)
                     ->where('status_jadwal', '!=', 'batal')
                     ->first();

        if($cek) {
            return response()->json([
                'success' => false,
                'message' => 'Jam tersebut sudah terisi!'
            ], 400);
        }

        // Lengkapi tanggal lahir dari form booking kalau belum terisi
        // (no_hp sudah pasti benar sejak registrasi, jadi tidak perlu disentuh lagi)
        if ($request->filled('tanggal_lahir') && !$pasien->tanggal_lahir) {
            $pasien->update(['tanggal_lahir' => $request->tanggal_lahir]);
        }

        $jadwal = Jadwal::create([
            'pasien_id' => $pasien->id,
            'dokter_id' => $dokter?->id,
            'tanggal_jadwal' => $request->tanggal_jadwal,
            'jam_jadwal' => $request->jam_jadwal,
            'status_jadwal' => 'menunggu',
            'keterangan' => $request->keterangan ?? 'Booking via aplikasi',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil!',
            'jadwal' => $jadwal,
        ]);
    }

    // Jadwal pasien
    public function jadwalSaya(Request $request)
    {
        $pasien = $request->pasien;

        $jadwal = Jadwal::where('pasien_id', $pasien->id)
                        ->orderBy('tanggal_jadwal', 'desc')
                        ->get();

        return response()->json([
            'success' => true,
            'jadwal' => $jadwal,
        ]);
    }

    // Riwayat tindakan pasien
    public function riwayat(Request $request)
    {
        $pasien = $request->pasien;

        $riwayat = RekamMedis::with('dokumentasi')
                             ->where('pasien_id', $pasien->id)
                             ->orderBy('tanggal_tindakan', 'desc')
                             ->get()
                             ->map(function($rm) {
                                 return [
                                     'id' => $rm->id,
                                     'tanggal_tindakan' => $rm->tanggal_tindakan,
                                     'jenis_tindakan' => $rm->jenis_tindakan,
                                     'catatan_tindakan' => $rm->catatan_tindakan,
                                     'tanggal_kontrol' => $rm->tanggal_kontrol,
                                     'foto_before' => $rm->dokumentasi->first()?->foto_before
                                         ? asset('storage/'.$rm->dokumentasi->first()->foto_before)
                                         : null,
                                     'foto_after' => $rm->dokumentasi->first()?->foto_after
                                         ? asset('storage/'.$rm->dokumentasi->first()->foto_after)
                                         : null,
                                 ];
                             });

        return response()->json([
            'success' => true,
            'riwayat' => $riwayat,
        ]);
    }

    // Tambahin method private ini di dalam class ApiController
    private function formatPasien($pasien)
    {
        return [
            'id'            => $pasien->id,
            'nama'          => $pasien->nama_pasien,
            'email'         => $pasien->email_google,
            'nomor_hp'      => $pasien->no_hp,
            'tanggal_lahir' => $pasien->tanggal_lahir,
            'alamat'        => $pasien->alamat,
        ];
    }

    public function simpanFcmToken(Request $request)
    {
        $request->validate(['fcm_token' => 'required|string']);
        $request->pasien->update(['fcm_token' => $request->fcm_token]);

        return response()->json(['success' => true, 'message' => 'Token tersimpan']);
    }

    public function riwayatNotifikasi(Request $request)
    {
        $notifikasi = NotifikasiLog::where('pasien_id', $request->pasien->id)
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();

        return response()->json(['success' => true, 'notifikasi' => $notifikasi]);
    }
}
