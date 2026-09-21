<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;

class PasienController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(session('user_role'), ['dokter', 'resepsionis', 'pemilik'])) {
        return redirect()->route('login');
        }
        if(!session('user_id')) return redirect()->route('login');

        $search = $request->get('search');
        $sort   = $request->get('sort', 'terbaru');

        $query = Pasien::with('jadwal');

        if($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_pasien', 'like', '%'.$search.'%')
                  ->orWhere('no_hp', 'like', '%'.$search.'%');
            });
        }

        $query->orderBy('created_at', $sort === 'terlama' ? 'asc' : 'desc');
        if($sort === 'nama') $query->reorder()->orderBy('nama_pasien', 'asc');

        $pasien = $query->paginate(10)->withQueryString();

        return view('resepsionis.pasien.index', compact('pasien', 'search'));
    }

    public function create()
    {
        if(!session('user_id')) return redirect()->route('login');
        return view('resepsionis.pasien.create');
    }

    public function store(Request $request)
    {
        if(!session('user_id')) return redirect()->route('login');

        $request->validate([
            'nama_pasien'   => 'required|string|max:255',
            'no_hp'         => 'required|unique:pasien,no_hp',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat'        => 'nullable|string',
        ], [
            'nama_pasien.required' => 'Nama pasien wajib diisi',
            'no_hp.required'       => 'Nomor HP wajib diisi',
            'no_hp.unique'         => 'Nomor HP sudah terdaftar',
        ]);

        Pasien::create($request->all());

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil ditambahkan!');
    }

    public function show($id)
    {
        if(!session('user_id')) return redirect()->route('login');
        $pasien = Pasien::with('jadwal', 'mergedKe')->findOrFail($id);
        return view('resepsionis.pasien.show', compact('pasien'));
    }

    public function edit($id)
    {
        if(!session('user_id')) return redirect()->route('login');
        $pasien = Pasien::findOrFail($id);

        if(!$pasien->is_aktif) {
            return redirect()->route('pasien.show', $id)
                ->with('error', 'Data pasien ini sudah digabung ke pasien lain dan tidak bisa diedit lagi.');
        }

        return view('resepsionis.pasien.edit', compact('pasien'));
    }

    public function update(Request $request, $id)
    {
        if(!session('user_id')) return redirect()->route('login');
        $pasien = Pasien::findOrFail($id);

        $request->validate([
            'nama_pasien'   => 'required|string|max:255',
            'no_hp'         => 'required|unique:pasien,no_hp,'.$id,
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat'        => 'nullable|string',
        ], [
            'nama_pasien.required' => 'Nama pasien wajib diisi',
            'no_hp.required'       => 'Nomor HP wajib diisi',
            'no_hp.unique'         => 'Nomor HP sudah terdaftar',
        ]);

        $pasien->update($request->all());

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diupdate!');
    }

    public function destroy($id)
    {
        if(!session('user_id')) return redirect()->route('login');
        Pasien::findOrFail($id)->delete();
        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil dihapus!');
    }

    // Form untuk menggabungkan data pasien duplikat (mis. karena typo no HP saat registrasi)
    public function mergeForm($id)
    {
        if(!session('user_id')) return redirect()->route('login');

        $pasien = Pasien::findOrFail($id);

        if(!$pasien->is_aktif) {
            return redirect()->route('pasien.show', $id)
                ->with('error', 'Data pasien ini sudah nonaktif (hasil merge sebelumnya), tidak bisa dijadikan tujuan penggabungan lagi.');
        }

        // Kandidat duplikat: pasien lain yang masih aktif dengan nama mirip,
        // biar gampang dicari resepsionis
        $kandidat = Pasien::where('id', '!=', $id)
            ->where('is_aktif', true)
            ->where('nama_pasien', 'like', '%'.$pasien->nama_pasien.'%')
            ->get();

        return view('resepsionis.pasien.merge', compact('pasien', 'kandidat'));
    }

    // Eksekusi penggabungan: seluruh jadwal & rekam medis dari data duplikat
    // dipindahkan ke data pasien tujuan ($id). Data duplikat TIDAK dihapus
    // (rekam medis wajib disimpan sesuai UU Rekam Medis), hanya dinonaktifkan
    // supaya tidak bisa dipakai lagi untuk input data baru.
    public function merge(Request $request, $id)
    {
        if(!session('user_id')) return redirect()->route('login');

        $request->validate([
            'duplikat_id' => 'required|exists:pasien,id|different:id',
        ]);

        $tujuan   = Pasien::findOrFail($id);
        $duplikat = Pasien::findOrFail($request->duplikat_id);

        if ($tujuan->id === $duplikat->id) {
            return back()->with('error', 'Tidak bisa menggabungkan data pasien dengan dirinya sendiri.');
        }

        if (!$tujuan->is_aktif || !$duplikat->is_aktif) {
            return back()->with('error', 'Salah satu data sudah nonaktif (pernah digabung sebelumnya). Pilih data yang masih aktif.');
        }

        \DB::transaction(function () use ($tujuan, $duplikat) {
            $duplikat->jadwal()->update(['pasien_id' => $tujuan->id]);
            \App\Models\RekamMedis::where('pasien_id', $duplikat->id)
                ->update(['pasien_id' => $tujuan->id]);

            // Nonaktifkan, jangan dihapus — riwayat rekam medis lama
            // (jika ada yang tercatat sebelum digabung) tetap tersimpan.
            $duplikat->update([
                'is_aktif'     => false,
                'merged_ke_id' => $tujuan->id,
            ]);
        });

        return redirect()->route('pasien.show', $tujuan->id)
            ->with('success', "Data pasien '{$duplikat->nama_pasien}' berhasil digabung ke akun ini. Data lama dinonaktifkan, bukan dihapus.");
    }
}
