<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

// API untuk mobile
Route::post('/pasien/register', [ApiController::class, 'registerPasien']);
Route::post('/pasien/login', [ApiController::class, 'loginPasien']);

Route::middleware('auth.pasien')->group(function () {
    Route::get('/pasien/profil', [ApiController::class, 'profil']);
    Route::get('/jadwal/tersedia', [ApiController::class, 'jadwalTersedia']);
    Route::post('/jadwal/booking', [ApiController::class, 'booking']);
    Route::get('/jadwal/saya', [ApiController::class, 'jadwalSaya']);
    Route::get('/riwayat', [ApiController::class, 'riwayat']);
});
