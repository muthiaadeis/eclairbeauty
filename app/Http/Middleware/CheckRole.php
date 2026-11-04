<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $role = session('user_role');

        // Belum login
        if (!session('user_id') || !$role) {
            return redirect()->route('login');
        }

        // Sudah login tapi role tidak boleh masuk -> balik ke dashboard role-nya
        if (!in_array($role, $roles)) {
            $dashboard = [
                'dokter'      => 'dokter.dashboard',
                'resepsionis' => 'resepsionis.dashboard',
                'pemilik'     => 'pemilik.dashboard',
            ];

            if (isset($dashboard[$role])) {
                return redirect()->route($dashboard[$role])
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
