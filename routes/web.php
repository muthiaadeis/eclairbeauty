<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\ProfileController;
use App\Models\Jadwal;

// Login
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Profil
Route::get('/profil', [ProfileController::class, 'index'])->name('profil.index');
Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update');

// Dashboard dokter — sekarang kirim data antrean
Route::get('/dokter/dashboard', function () {
    if(session('user_role') != 'dokter') return redirect()->route('login');

    $antrean = Jadwal::with('pasien')
        ->where('dokter_id', session('user_id'))
        ->whereDate('tanggal_jadwal', today())
        ->whereIn('status_jadwal', ['menunggu', 'hadir', 'selesai'])
        ->orderBy('jam_jadwal', 'asc')
        ->get();

    return view('dokter.dashboard', compact('antrean'));
})->name('dokter.dashboard');

// Antrean pasien untuk dokter (halaman penuh)
Route::get('/dokter/antrean', function () {
    if(session('user_role') != 'dokter') return redirect()->route('login');

    $jadwal = Jadwal::with('pasien')
        ->where('dokter_id', session('user_id'))
        ->whereDate('tanggal_jadwal', today())
        ->whereIn('status_jadwal', ['menunggu', 'hadir', 'selesai'])
        ->orderBy('jam_jadwal', 'asc')
        ->get();

    return view('dokter.antrean', compact('jadwal'));
})->name('dokter.antrean');

// Dashboard resepsionis
Route::get('/resepsionis/dashboard', function () {
    if(session('user_role') != 'resepsionis') return redirect()->route('login');

    $antrean = Jadwal::with('pasien')
        ->whereDate('tanggal_jadwal', today())
        ->whereIn('status_jadwal', ['menunggu', 'hadir', 'selesai'])
        ->orderBy('jam_jadwal', 'asc')
        ->get();

    $totalAntrean    = $antrean->count();
    $pasienMenunggu  = $antrean->whereIn('status_jadwal', ['menunggu', 'hadir'])->count();
    $selesaiTindakan = $antrean->where('status_jadwal', 'selesai')->count();

    return view('resepsionis.dashboard', compact('antrean', 'totalAntrean', 'pasienMenunggu', 'selesaiTindakan'));
})->name('resepsionis.dashboard');

// Dashboard pemilik
Route::get('/pemilik/dashboard', [PemilikController::class, 'dashboard'])->name('pemilik.dashboard');
Route::get('/pemilik/laporan', [PemilikController::class, 'laporan'])->name('pemilik.laporan');

// Pasien
Route::get('/pasien', [PasienController::class, 'index'])->name('pasien.index');
Route::get('/pasien/create', [PasienController::class, 'create'])->name('pasien.create');
Route::post('/pasien', [PasienController::class, 'store'])->name('pasien.store');
Route::get('/pasien/{id}', [PasienController::class, 'show'])->name('pasien.show');
Route::get('/pasien/{id}/edit', [PasienController::class, 'edit'])->name('pasien.edit');
Route::put('/pasien/{id}', [PasienController::class, 'update'])->name('pasien.update');
Route::delete('/pasien/{id}', [PasienController::class, 'destroy'])->name('pasien.destroy');

// Jadwal
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
Route::post('/jadwal/{id}/checkin', [JadwalController::class, 'checkin'])->name('jadwal.checkin');
Route::post('/jadwal/{id}/selesai', [JadwalController::class, 'selesai'])->name('jadwal.selesai');
Route::post('/jadwal/{id}/batal', [JadwalController::class, 'batal'])->name('jadwal.batal');

// Rekam medis
Route::get('/rekam-medis', [RekamMedisController::class, 'index'])->name('rekam_medis.index');
Route::get('/rekam-medis/{pasien_id}', [RekamMedisController::class, 'show'])->name('rekam_medis.show');
Route::get('/rekam-medis/{pasien_id}/create', [RekamMedisController::class, 'create'])->name('rekam_medis.create');
Route::post('/rekam-medis/{pasien_id}', [RekamMedisController::class, 'store'])->name('rekam_medis.store');
