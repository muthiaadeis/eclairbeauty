<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Pasien;
use Laravel\Sanctum\PersonalAccessToken;

class AuthPasien
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if(!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = trim(str_replace('Bearer ', '', $token));

        // 1. Cek apakah ini Sanctum Token resmi
        $accessToken = PersonalAccessToken::findToken($token);
        if ($accessToken && $accessToken->tokenable instanceof Pasien) {
            $pasien = $accessToken->tokenable;
        } else {
            // 2. Fallback: cek google_uid jika masih ada client lama saat transisi
            $pasien = Pasien::cariByGoogleUid($token);
        }

        if(!$pasien) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Kalau token ini terhubung ke data pasien yang sudah digabung,
        // arahkan ke data pasien yang aktif.
        $pasien = $pasien->resolveAktif();

        // Data nonaktif tanpa tujuan merge: tolak, jangan diperlakukan sebagai aktif
        if (!$pasien->is_aktif) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda telah dinonaktifkan. Silakan hubungi Eclair Beauty Clinic.',
            ], 403);
        }

        $request->merge(['pasien' => $pasien]);
        return $next($request);
    }
}
