<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Pasien;
use App\Models\Jadwal;
use App\Models\RekamMedis;
use App\Models\User;
use App\Models\NotifikasiLog;

class ApiController extends Controller
{
    private function akunNonaktif()
    {
        return response()->json([
            'success' => false,
            'message' => 'Akun Anda telah dinonaktifkan. Silakan hubungi Eclair Beauty Clinic.',
        ], 403);
    }

    /**
     * Verifikasi ID Token Google ke server Google OAuth2 / Firebase.
     * Mengembalikan array ['google_uid', 'email', 'nama'] jika valid, atau null jika gagal.
     */
    private function verifikasiGoogleToken(?string $idToken): ?array
    {
        if (empty($idToken)) {
            return null;
        }

        try {
            // 1. Verifikasi resmi melalui Google OAuth2 tokeninfo endpoint
            $response = Http::timeout(7)->get('https://oauth2.googleapis.com/tokeninfo', [
                'id_token' => $idToken,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $uid = $data['sub'] ?? $data['user_id'] ?? null;
                if ($uid) {
                    return [
                        'google_uid' => $uid,
                        'email'      => $data['email'] ?? null,
                        'nama'       => $data['name'] ?? null,
                    ];
                }
            }

            // 2. Fallback jika berupa Firebase ID Token JWT
            $parts = explode('.', $idToken);
            if (count($parts) === 3) {
                $payloadJson = base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1]));
                $payload = json_decode($payloadJson, true);

                if (is_array($payload) && !empty($payload['sub'])) {
                    $exp = $payload['exp'] ?? 0;
                    if ($exp > time()) {
                        return [
                            'google_uid' => $payload['sub'] ?? $payload['user_id'],
                            'email'      => $payload['email'] ?? null,
                            'nama'       => $payload['name'] ?? null,
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal verifikasi Google token: ' . $e->getMessage());
        }

        return null;
    }

    // Register / login pasien via Google.
    // Menerima id_token dari Google Sign-In di Android untuk diverifikasi langsung ke server Google.
    public function registerPasien(Request $request)
    {
        $idToken = $request->input('id_token') 
                ?? $request->input('idToken') 
                ?? $request->input('google_token');

        if ($idToken) {
            $googleData = $this->verifikasiGoogleToken($idToken);
            if (!$googleData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token Google tidak valid atau sudah kedaluwarsa.',
                ], 401);
            }

            $googleUid   = $googleData['google_uid'];
            $emailGoogle = $googleData['email'] ?? $request->input('email_google');
            $namaPasien  = $googleData['nama'] ?? $request->input('nama_pasien', 'Pasien');
        } else {
            // Fallback jika aplikasi masih mengirimkan google_uid langsung saat transisi
            $request->validate([
                'google_uid'   => 'required',
                'nama_pasien'  => 'required',
                'email_google' => 'required|email',
            ]);

            $googleUid   = $request->google_uid;
            $emailGoogle = $request->email_google;
            $namaPasien  = $request->nama_pasien;
        }

        $pasien = Pasien::cariByGoogleUid($googleUid);

        if ($pasien) {
            // Ikuti merge ke data aktif; kalau tetap nonaktif, tolak
            $pasien = $pasien->resolveAktif();

            if (!$pasien->is_aktif) {
                return $this->akunNonaktif();
            }

            $update = [];
            if (empty($pasien->email_google) && $emailGoogle) {
                $update['email_google'] = $emailGoogle;
            }
            if (empty($pasien->nama_pasien) && $namaPasien) {
                $update['nama_pasien'] = $namaPasien;
            }
            if (!empty($update)) {
                $pasien->update($update);
            }
        } else {
            $pasien = Pasien::create([
                'google_uid'   => $googleUid,
                'nama_pasien'  => $namaPasien,
                'email_google' => $emailGoogle,
                'no_hp'        => null,
            ]);
        }

        // Buat API Token Sanctum yang aman (bukan raw UID)
        $token = $pasien->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'pasien'  => $this->formatPasien($pasien),
            'token'   => $token,
        ]);
    }

    // Login pasien
    public function loginPasien(Request $request)
    {
        $idToken = $request->input('id_token') 
                ?? $request->input('idToken') 
                ?? $request->input('google_token');

        if ($idToken) {
            $googleData = $this->verifikasiGoogleToken($idToken);
            if (!$googleData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token Google tidak valid atau sudah kedaluwarsa.',
                ], 401);
            }

            $googleUid   = $googleData['google_uid'];
            $emailGoogle = $googleData['email'] ?? $request->input('email_google');
            $namaPasien  = $googleData['nama'] ?? $request->input('nama_pasien', 'Pasien');
        } else {
            $request->validate([
                'google_uid' => 'required',
            ]);

            $googleUid   = $request->google_uid;
            $emailGoogle = $request->input('email_google');
            $namaPasien  = $request->input('nama_pasien');
        }

        $pasien = Pasien::cariByGoogleUid($googleUid);

        // Jika login dengan Google tapi belum ada datanya di DB, otomatis buatkan akun
        if (!$pasien) {
            if ($idToken) {
                $pasien = Pasien::create([
                    'google_uid'   => $googleUid,
                    'nama_pasien'  => $namaPasien ?: 'Pasien',
                    'email_google' => $emailGoogle,
                    'no_hp'        => null,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pasien tidak ditemukan'
                ], 404);
            }
        }

        $pasien = $pasien->resolveAktif();

        if (!$pasien->is_aktif) {
            return $this->akunNonaktif();
        }

        // Buat API Token Sanctum yang aman
        $token = $pasien->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'pasien'  => $this->formatPasien($pasien),
            'token'   => $token,
        ]);
    }

    // Logout pasien (menghapus token akses)
    public function logoutPasien(Request $request)
    {
        $pasien = $request->pasien;
        if ($pasien) {
            $pasien->tokens()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    // Profil pasien (dipakai aplikasi untuk cek apakah data diri sudah lengkap)
    public function profil(Request $request)
    {
        return response()->json([
            'success' => true,
            'pasien'  => $this->formatPasien($request->pasien),
        ]);
    }

    // Lengkapi data (no HP + tanggal lahir), dipanggil sekali saat booking pertama.
    // No HP adalah identitas utama pasien: kalau nomornya sudah terdaftar di data lain,
    // akun Google ini ditautkan ke data tersebut dan data baru dinonaktifkan (bukan dihapus).
    public function lengkapiData(Request $request)
    {
        $request->validate([
            'no_hp'         => ['required', 'regex:/^08[0-9]{8,11}$/'],
            'tanggal_lahir' => 'required|date|before:today',
        ], [
            'no_hp.required'         => 'Nomor HP wajib diisi',
            'no_hp.regex'            => 'Format nomor HP tidak valid (contoh: 081234567890)',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.before'   => 'Tanggal lahir tidak valid',
        ]);

        $pasien = $request->pasien;

        // Cari data pasien lain yang punya nomor HP sama
        $existing = Pasien::where('no_hp', $request->no_hp)
                          ->where('id', '!=', $pasien->id)
                          ->first();

        if ($existing) {
            $existing = $existing->resolveAktif();

            // Nomor ini milik data pasien yang sudah dinonaktifkan klinik: jangan ditautkan
            if (!$existing->is_aktif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor HP ini terdaftar pada data pasien yang sudah dinonaktifkan. Silakan hubungi Eclair Beauty Clinic.',
                ], 422);
            }

            // No HP cocok — sekarang verifikasi tanggal lahir juga harus sama
            // supaya typo nomor HP tidak menyebabkan salah merge ke orang lain.
            if ($existing->tanggal_lahir && $existing->tanggal_lahir !== $request->tanggal_lahir) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor HP ini sudah terdaftar pada data pasien lain. '
                               . 'Pastikan nomor HP dan tanggal lahir Anda benar, atau hubungi Eclair Beauty Clinic.',
                ], 422);
            }
        }

        if ($existing && $existing->id !== $pasien->id) {
            // No HP DAN tanggal lahir cocok -> orang yang sama, tautkan akun Google ini ke data lama
            DB::transaction(function () use ($pasien, $existing, $request) {
                $googleUid = $pasien->google_uid;
                $email     = $pasien->email_google;
                $fcmToken  = $pasien->fcm_token;

                // Pindahkan jejak apa pun dari data baru ke data lama
                $pasien->jadwal()->update(['pasien_id' => $existing->id]);
                RekamMedis::where('pasien_id', $pasien->id)->update(['pasien_id' => $existing->id]);
                NotifikasiLog::where('pasien_id', $pasien->id)->update(['pasien_id' => $existing->id]);

                // Data baru dinonaktifkan, tidak dihapus
                $pasien->update([
                    'google_uid'   => null,
                    'fcm_token'    => null,
                    'is_aktif'     => false,
                    'merged_ke_id' => $existing->id,
                ]);

                $existing->update([
                    'google_uid'    => $googleUid,
                    'email_google'  => $email,
                    'fcm_token'     => $fcmToken ?: $existing->fcm_token,
                    'tanggal_lahir' => $existing->tanggal_lahir ?: $request->tanggal_lahir,
                ]);
            });

            $pasien = $existing->fresh();
        } else {
            $pasien->update([
                'no_hp'         => $request->no_hp,
                'tanggal_lahir' => $request->tanggal_lahir,
            ]);
            $pasien = $pasien->fresh();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dilengkapi',
            'pasien'  => $this->formatPasien($pasien),
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

        // Jika tanggal adalah hari ini, masukkan jam yang sudah lewat ke jam_penuh (abu-abu)
        if ($tanggal === date('Y-m-d')) {
            $nowJam = date('H:i');
            foreach ($jamTersedia as $jam) {
                if ($jam <= $nowJam && !in_array($jam, $jamTerpakai)) {
                    $jamTerpakai[] = $jam;
                }
            }
        }

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

        // No HP & tanggal lahir wajib sudah dilengkapi sebelum booking
        if (!$pasien->no_hp || !$pasien->tanggal_lahir) {
            return response()->json([
                'success' => false,
                'message' => 'Lengkapi data diri (nomor HP dan tanggal lahir) terlebih dahulu.'
            ], 422);
        }

        // Cek apakah tanggal/jam sudah lewat
        $today = date('Y-m-d');
        $nowJam = date('H:i');
        $jamReq = substr($request->jam_jadwal, 0, 5);
        if ($request->tanggal_jadwal < $today || ($request->tanggal_jadwal === $today && $jamReq <= $nowJam)) {
            return response()->json([
                'success' => false,
                'message' => 'Jam tersebut sudah lewat! Silakan pilih jam lain.'
            ], 400);
        }

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

        if ($cek) {
            return response()->json([
                'success' => false,
                'message' => 'Jam tersebut sudah terisi!'
            ], 400);
        }

        $jadwal = Jadwal::create([
            'pasien_id' => $pasien->id,
            'dokter_id' => $dokter?->id,
            'tanggal_jadwal' => $request->tanggal_jadwal,
            'jam_jadwal' => $request->jam_jadwal,
            'status_jadwal' => 'menunggu',
            'keterangan' => $request->filled('keterangan') ? $request->keterangan : 'Booking via aplikasi',
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
                        ->orderBy('jam_jadwal', 'desc')
                        ->get();

        $today = now()->format('Y-m-d');

        $jadwal->transform(function ($j) use ($today, $pasien) {
            $waktuJadwal = \Carbon\Carbon::parse($j->tanggal_jadwal . ' ' . $j->jam_jadwal);

            // Cek apakah ada rekam medis untuk pasien ini pada tanggal tindakan tsb
            $adaRekamMedis = \App\Models\RekamMedis::where('pasien_id', $pasien->id)
                ->whereDate('tanggal_tindakan', $j->tanggal_jadwal)
                ->exists();

            if ($adaRekamMedis) {
                $j->status_jadwal = 'selesai';
            } elseif ($j->status_jadwal === 'hadir') {
                $j->status_jadwal = 'checkin';
            } elseif ($j->status_jadwal === 'menunggu') {
                if ($waktuJadwal->isPast()) {
                    $j->status_jadwal = 'terlewat';
                } elseif ($j->tanggal_jadwal === $today) {
                    $j->status_jadwal = 'hari_ini';
                } else {
                    $j->status_jadwal = 'terjadwal';
                }
            }
            return $j;
        });

        return response()->json([
            'success' => true,
            'jadwal' => $jadwal,
        ]);
    }

    // Pasien membatalkan jadwal mandiri
    public function batalJadwal($id, Request $request)
    {
        $pasien = $request->pasien;

        $jadwal = Jadwal::where('id', $id)
                        ->where('pasien_id', $pasien->id)
                        ->first();

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan.',
            ], 404);
        }

        if ($jadwal->status_jadwal === 'batal') {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal ini sudah dibatalkan sebelumnya.',
            ], 400);
        }

        if ($jadwal->status_jadwal === 'selesai') {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal yang sudah selesai tidak dapat dibatalkan.',
            ], 400);
        }

        if ($jadwal->status_jadwal === 'hadir') {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan check-in di klinik. Silakan konfirmasi ke resepsionis jika berhalangan.',
            ], 400);
        }

        $waktuJadwal = \Carbon\Carbon::parse($jadwal->tanggal_jadwal . ' ' . $jadwal->jam_jadwal);
        if ($waktuJadwal->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal yang sudah lewat waktunya tidak dapat dibatalkan.',
            ], 400);
        }

        $jadwal->update(['status_jadwal' => 'batal']);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal Anda berhasil dibatalkan. Jam tersebut telah dibuka kembali.',
        ]);
    }

    // Riwayat tindakan pasien (tanpa keluhan & hasil konsultasi, sesuai batasan masalah)
    public function riwayat(Request $request)
    {
        $pasien = $request->pasien;

        $riwayat = RekamMedis::with('dokumentasi', 'user')
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
                                     'dokter' => $rm->user?->nama,
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

    private function formatPasien($pasien)
    {
        return [
            'id'            => $pasien->id,
            'nama'          => $pasien->nama_pasien,
            'email'         => $pasien->email_google,
            'nomor_hp'      => $pasien->no_hp,
            'tanggal_lahir' => $pasien->tanggal_lahir,
            'alamat'        => $pasien->alamat,
            'data_lengkap'  => !empty($pasien->no_hp) && !empty($pasien->tanggal_lahir),
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
