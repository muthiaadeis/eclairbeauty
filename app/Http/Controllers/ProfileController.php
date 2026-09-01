<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    // Tampilkan halaman profil
    public function index()
    {
        if (!session('user_id')) {
            return redirect()->route('login');
        }

        $user = User::findOrFail(session('user_id'));

        return view('profil.index', compact('user'));
    }

    // Update data profil
    public function update(Request $request)
    {
        if (!session('user_id')) {
            return redirect()->route('login');
        }

        $user = User::findOrFail(session('user_id'));

        $request->validate([
            'nama'          => 'required|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'password_baru' => 'nullable|min:6|confirmed',
        ], [
            'nama.required'           => 'Nama wajib diisi',
            'password_baru.min'       => 'Password baru minimal 6 karakter',
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok',
        ]);

        $user->nama = $request->nama;
        $user->nomor_telepon = $request->nomor_telepon;

        if ($request->filled('password_baru')) {
            $request->validate([
                'password_lama' => 'required',
            ], [
                'password_lama.required' => 'Masukkan password lama untuk mengganti password',
            ]);

            if (!Hash::check($request->password_lama, $user->password)) {
                return back()->with('error', 'Password lama salah!');
            }

            $user->password = $request->password_baru;
        }

        $user->save();

        session(['user_nama' => $user->nama]);

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
