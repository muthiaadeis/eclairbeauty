<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        // Cari user berdasarkan username
        $user = User::where('username', $request->username)
                    ->where('status_aktif', true)
                    ->first();

        // Cek password
        if ($user && Hash::check($request->password, $user->password)) {
            // Login berhasil, simpan data user ke session
            session([
                'user_id' => $user->id,
                'user_nama' => $user->nama,
                'user_role' => $user->role,
            ]);

            // Arahkan ke dashboard sesuai role
            if ($user->role == 'dokter') {
                return redirect()->route('dokter.dashboard');
            } elseif ($user->role == 'resepsionis') {
                return redirect()->route('resepsionis.dashboard');
            } elseif ($user->role == 'pemilik') {
                return redirect()->route('pemilik.dashboard');
            }
        }

        // Login gagal
        return back()->with('error', 'Username atau password salah!');
    }

    // Logout
    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}
