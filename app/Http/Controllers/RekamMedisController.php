<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RekamMedis;
use App\Models\DokumentasiTindakan;
use App\Models\Pasien;
use App\Models\Jadwal;

class RekamMedisController extends Controller
{
    // Cek apakah pasien ini sudah di-check-in resepsionis hari ini,
    // untuk jadwal dengan dokter yang sedang login
    private function sudahCheckin($pasien_id)
    {
        return Jadwal::where('pasien_id', $pasien_id)
                    ->where('dokter_id', session('user_id'))
                    ->whereDate('tanggal_jadwal', today())
                    ->where('status_jadwal', 'hadir')
                    ->exists();
    }

    // Tampilkan daftar pasien untuk dokter
    public function index(Request $request)
    {
        if (!in_array(session('user_role'), ['dokter', 'resepsionis', 'pemilik'])) {
        return redirect()->route('login');
        }
        if(!session('user_id')) return redirect()->route('login');
        if(session('user_role') != 'dokter') return redirect()->route('login');

        $search = $request->get('search');

        if($search) {
            $pasien = Pasien::where('nama_pasien', 'like', '%'.$search.'%')
                           ->orWhere('no_hp', 'like', '%'.$search.'%')
                           ->orderBy('nama_pasien')
                           ->paginate(10);
        } else {
            $pasien = Pasien::orderBy('nama_pasien')->paginate(10);
        }

        return view('dokter.rekam_medis.index', compact('pasien', 'search'));
    }

    // Tampilkan riwayat rekam medis pasien
    public function show($pasien_id)
    {
        if(!session('user_id')) return redirect()->route('login');

        $pasien = Pasien::findOrFail($pasien_id);
        $rekamMedis = RekamMedis::with('dokumentasi', 'user')
                                ->where('pasien_id', $pasien_id)
                                ->orderBy('tanggal_tindakan', 'desc')
                                ->get();

        // Dipakai di view buat nampilin/nyembunyiin tombol "Tambah Rekam Medis"
        $sudahCheckin = $this->sudahCheckin($pasien_id);

        return view('dokter.rekam_medis.show', compact('pasien', 'rekamMedis', 'sudahCheckin'));
    }

    // Tampilkan form tambah rekam medis
    public function create($pasien_id)
    {
        if(!session('user_id')) return redirect()->route('login');
        if(session('user_role') != 'dokter') return redirect()->route('login');

        $pasien = Pasien::findOrFail($pasien_id);

        // Blokir kalau data pasien ini sudah digabung/dinonaktifkan
        if(!$pasien->is_aktif) {
            return redirect()->route('rekam_medis.show', $pasien_id)
                            ->with('error', 'Data pasien ini sudah digabung ke data pasien lain, rekam medis tidak bisa ditambahkan di sini lagi.');
        }

        // Blokir kalau pasien belum di-check-in resepsionis hari ini
        if(!$this->sudahCheckin($pasien_id)) {
            return redirect()->route('rekam_medis.show', $pasien_id)
                            ->with('error', 'Pasien ini belum check-in oleh resepsionis hari ini, jadi rekam medis belum bisa diisi.');
        }

        return view('dokter.rekam_medis.create', compact('pasien'));
    }

    // Simpan rekam medis baru
    public function store(Request $request, $pasien_id)
    {
        if(!session('user_id')) return redirect()->route('login');
        if(session('user_role') != 'dokter') return redirect()->route('login');

        $pasien = Pasien::findOrFail($pasien_id);

        if(!$pasien->is_aktif) {
            return redirect()->route('rekam_medis.show', $pasien_id)
                            ->with('error', 'Data pasien ini sudah digabung ke data pasien lain, rekam medis tidak bisa ditambahkan di sini lagi.');
        }

        if(!$this->sudahCheckin($pasien_id)) {
            return redirect()->route('rekam_medis.show', $pasien_id)
                            ->with('error', 'Pasien ini belum check-in oleh resepsionis hari ini, jadi rekam medis belum bisa diisi.');
        }

        $request->validate([
            'tanggal_tindakan' => 'required|date',
            'jenis_perawatan' => 'nullable|string',
            'keluhan' => 'nullable|string',
            'hasil_konsultasi' => 'nullable|string',
            'catatan_tindakan' => 'nullable|string',
            'produk_digunakan' => 'nullable|string',
            'tanggal_kontrol' => 'nullable|date',
            'foto_before' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_after' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan rekam medis
        $rekamMedis = RekamMedis::create([
            'pasien_id' => $pasien_id,
            'user_id' => session('user_id'),
            'tanggal_tindakan' => $request->tanggal_tindakan,
            'jenis_tindakan' => $request->jenis_perawatan,
            'keluhan' => $request->keluhan,
            'hasil_konsultasi' => $request->hasil_konsultasi,
            'catatan_tindakan' => $request->catatan_tindakan,
            'produk_digunakan' => $request->produk_digunakan,
            'tanggal_kontrol' => $request->tanggal_kontrol,
            'created_at' => now(),
        ]);

        $fotoBefore = null;
        $fotoAfter = null;

        if($request->hasFile('foto_before')) {
            $fotoBefore = $request->file('foto_before')
                                  ->store('foto_tindakan', 'public');
        }

        if($request->hasFile('foto_after')) {
            $fotoAfter = $request->file('foto_after')
                                 ->store('foto_tindakan', 'public');
        }

        if($fotoBefore || $fotoAfter) {
            DokumentasiTindakan::create([
                'rekam_medis_id' => $rekamMedis->id,
                'foto_before' => $fotoBefore,
                'foto_after' => $fotoAfter,
                'keterangan' => $request->keterangan_foto,
                'created_at' => now(),
            ]);
        }

        return redirect()->route('rekam_medis.show', $pasien_id)
                        ->with('success', 'Rekam medis berhasil disimpan!');
    }
}
