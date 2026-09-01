<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Pasien;

class AuthPasien
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if(!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = str_replace('Bearer ', '', $token);
        $pasien = Pasien::where('google_uid', $token)->first();

        if(!$pasien) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->merge(['pasien' => $pasien]);
        return $next($request);
    }
}
