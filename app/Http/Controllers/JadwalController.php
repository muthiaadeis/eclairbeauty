<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Pasien;
use App\Models\User;

class JadwalController extends Controller
{
    // Tampilkan daftar jadwal
    public function index(Request $request)
    {
        if(!session('user_id')) return redirect()->route('login');

        $tanggal = $request->get('tanggal', date('Y-m-d'));

        $jadwal = Jadwal::with('pasien', 'user', 'dokter')
                        ->whereDate('tanggal_jadwal', $tanggal)
                        ->orderBy('jam_jadwal', 'asc')
                        ->get();

        return view('resepsionis.jadwal.index', compact('jadwal', 'tanggal'));
    }

    // Tampilkan form tambah jadwal
    public function create()
    {
        if(!session('user_id')) return redirect()->route('login');
        $pasien = Pasien::orderBy('nama_pasien')->get();
        $dokter = User::where('role', 'dokter')
                    ->where('status_aktif', true)
                    ->orderBy('nama')
                    ->get();
        return view('resepsionis.jadwal.create', compact('pasien', 'dokter'));
    }

    // Simpan jadwal baru
    public function store(Request $request)
    {
        if(!session('user_id')) return redirect()->route('login');

        $request->validate([
            'pasien_id' => 'required|exists:pasien,id',
            'dokter_id' => 'required|exists:users,id',
            'tanggal_jadwal' => 'required|date',
            'jam_jadwal' => 'required',
        ], [
            'pasien_id.required' => 'Pasien wajib dipilih',
            'dokter_id.required' => 'Dokter wajib dipilih',
            'tanggal_jadwal.required' => 'Tanggal jadwal wajib diisi',
            'jam_jadwal.required' => 'Jam jadwal wajib diisi',
        ]);

        // Cek bentrok jam untuk dokter yang sama
        $cek = Jadwal::where('tanggal_jadwal', $request->tanggal_jadwal)
                    ->where('jam_jadwal', $request->jam_jadwal)
                    ->where('dokter_id', $request->dokter_id)
                    ->where('status_jadwal', '!=', 'batal')
                    ->first();

        if($cek) {
            return back()->with('error', 'Jam tersebut sudah terisi untuk dokter ini, pilih jam lain!');
        }

        Jadwal::create([
            'pasien_id' => $request->pasien_id,
            'user_id' => session('user_id'),
            'dokter_id' => $request->dokter_id,
            'tanggal_jadwal' => $request->tanggal_jadwal,
            'jam_jadwal' => $request->jam_jadwal,
            'status_jadwal' => 'menunggu',
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('jadwal.index')
                        ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    // Check-in pasien
    public function checkin($id)
    {
        if(!session('user_id')) return redirect()->route('login');

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update(['status_jadwal' => 'hadir']);

        return redirect()->route('jadwal.index')
                        ->with('success', 'Pasien berhasil di check-in!');
    }

    // Selesaikan jadwal
    public function selesai($id)
    {
        if(!session('user_id')) return redirect()->route('login');

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update(['status_jadwal' => 'selesai']);

        return redirect()->route('jadwal.index')
                        ->with('success', 'Jadwal berhasil diselesaikan!');
    }

    // Batalkan jadwal
    public function batal($id)
    {
        if(!session('user_id')) return redirect()->route('login');

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update(['status_jadwal' => 'batal']);

        return redirect()->route('jadwal.index')
                        ->with('success', 'Jadwal berhasil dibatalkan!');
    }
}
