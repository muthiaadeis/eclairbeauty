<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Jadwal;

class PemilikController extends Controller
{
    public function dashboard()
    {
        if (!session('user_id')) return redirect()->route('login');
        if (session('user_role') != 'pemilik') return redirect()->route('login');

        $totalPasien        = Pasien::count();
        $totalKunjungan     = Jadwal::whereIn('status_jadwal', ['hadir', 'selesai'])->count();
        $kunjunganBulanIni  = Jadwal::whereIn('status_jadwal', ['hadir', 'selesai'])
                                         ->whereMonth('tanggal_jadwal', date('m'))
                                         ->whereYear('tanggal_jadwal', date('Y'))
                                         ->count();
        $jadwalHariIni      = Jadwal::whereDate('tanggal_jadwal', date('Y-m-d'))->count();

        // Data 12 bulan terakhir untuk grafik
        $kunjunganPerBulan = [];
        $labelBulan        = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan            = date('m', strtotime("-$i months"));
            $tahun            = date('Y', strtotime("-$i months"));
            $labelBulan[]     = date('M Y', strtotime("-$i months"));
            $kunjunganPerBulan[] = Jadwal::whereIn('status_jadwal', ['hadir', 'selesai'])
                                          ->whereMonth('tanggal_jadwal', $bulan)
                                          ->whereYear('tanggal_jadwal', $tahun)
                                          ->count();
        }

        $jadwalTerbaru = Jadwal::with('pasien')
                               ->orderBy('tanggal_jadwal', 'desc')
                               ->limit(5)
                               ->get();

        $pasienTerbaru = Pasien::orderBy('created_at', 'desc')
                               ->limit(5)
                               ->get();

        return view('pemilik.dashboard', compact(
            'totalPasien',
            'totalKunjungan',
            'kunjunganBulanIni',
            'jadwalHariIni',
            'kunjunganPerBulan',
            'labelBulan',
            'jadwalTerbaru',
            'pasienTerbaru'
        ));
    }

    public function laporan(Request $request)
    {
        if (!session('user_id')) return redirect()->route('login');
        if (session('user_role') != 'pemilik') return redirect()->route('login');

        $dari    = $request->input('dari');
        $sampai  = $request->input('sampai');
        $status  = $request->input('status');

        $query = Jadwal::with('pasien')
                       ->orderBy('tanggal_jadwal', 'desc')
                       ->orderBy('jam_jadwal', 'desc');

        if ($dari) {
            $query->whereDate('tanggal_jadwal', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('tanggal_jadwal', '<=', $sampai);
        }
        if ($status) {
            $query->where('status_jadwal', $status);
        }

        $jadwal = $query->paginate(10)->withQueryString();

        return view('pemilik.laporan', compact('jadwal', 'dari', 'sampai', 'status'));
    }
}
