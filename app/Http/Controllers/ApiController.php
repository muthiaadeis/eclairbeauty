<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Jadwal;
use App\Models\RekamMedis;

class ApiController extends Controller
{
    // Register atau login pasien via Google
    public function registerPasien(Request $request)
    {
        $request->validate([
            'google_uid' => 'required',
            'nama_pasien' => 'required',
            'email_google' => 'required|email',
        ]);

        // Cek apakah sudah terdaftar
        $pasien = Pasien::where('google_uid', $request->google_uid)->first();

        if(!$pasien) {
            // Buat pasien baru
            $pasien = Pasien::create([
                'google_uid' => $request->google_uid,
                'nama_pasien' => $request->nama_pasien,
                'email_google' => $request->email_google,
                'no_hp' => $request->no_hp ?? 'google_'.$request->google_uid,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'pasien' => $pasien,
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

        // Semua slot jam yang ada
        $jamTersedia = [
            '09:00', '09:30', '10:00', '10:30',
            '11:00', '11:30', '13:00', '13:30',
            '14:00', '14:30', '15:00', '15:30',
            '16:00', '16:30', '17:00'
        ];

        // Jam yang sudah terpakai (buat ditandai abu-abu, BUKAN dibuang dari daftar)
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
            'jam_tersedia' => $jamTersedia, // kirim SEMUA slot, jangan difilter lagi
            'jam_penuh' => $jamTerpakai,
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
}
