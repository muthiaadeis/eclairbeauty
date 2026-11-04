<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\NotifikasiLog;

class PasienController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sort   = $request->get('sort', 'terbaru');
        $status = $request->get('status', 'semua');

        // Filter "nonaktif": tampilkan pasien yang dinonaktifkan / sudah digabung.
        // Selain itu, hanya pasien yang masih aktif.
        $query = Pasien::with('jadwal')->where('is_aktif', $status === 'nonaktif' ? false : true);

        if($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_pasien', 'like', '%'.$search.'%')
                  ->orWhere('no_hp', 'like', '%'.$search.'%');
            });
        }

        // "aktif" = punya jadwal, "tidak_aktif" = belum punya jadwal (bukan is_aktif!)
        if ($status === 'aktif') {
            $query->has('jadwal');
        } elseif ($status === 'tidak_aktif') {
            $query->doesntHave('jadwal');
        } elseif ($status !== 'nonaktif') {
            // Default: taruh pasien yang belum punya jadwal di bawah
            $query->orderByRaw('(SELECT COUNT(*) FROM jadwal WHERE jadwal.pasien_id = pasien.id) DESC');
        }

        if($sort === 'nama') {
            $query->orderBy('nama_pasien', 'asc');
        } else {
            $query->orderBy('created_at', $sort === 'terlama' ? 'asc' : 'desc');
        }

        $pasien = $query->paginate(10)->withQueryString();

        return view('resepsionis.pasien.index', compact('pasien', 'search'));
    }

    public function create()
    {
        return view('resepsionis.pasien.create');
    }

    public function store(Request $request)
    {
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

        Pasien::create($request->only([
            'nama_pasien', 'no_hp', 'tanggal_lahir', 'jenis_kelamin', 'alamat',
        ]));

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil ditambahkan!');
    }

    public function show($id)
    {
        $pasien = Pasien::with('jadwal', 'mergedKe')->findOrFail($id);
        return view('resepsionis.pasien.show', compact('pasien'));
    }

    public function edit($id)
    {
        $pasien = Pasien::findOrFail($id);

        if(!$pasien->is_aktif) {
            return redirect()->route('pasien.show', $id)
                ->with('error', 'Data pasien ini sudah digabung ke pasien lain dan tidak bisa diedit lagi.');
        }

        return view('resepsionis.pasien.edit', compact('pasien'));
    }

    public function update(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);

        if(!$pasien->is_aktif) {
            return redirect()->route('pasien.show', $id)
                ->with('error', 'Data pasien ini sudah nonaktif dan tidak bisa diubah.');
        }

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

        // Hanya kolom data diri — is_aktif, google_uid, dst. tidak boleh ikut ter-update dari form
        $pasien->update($request->only([
            'nama_pasien', 'no_hp', 'tanggal_lahir', 'jenis_kelamin', 'alamat',
        ]));

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diupdate!');
    }

    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);
        $pasien->update(['is_aktif' => false]);

        return redirect()->route('pasien.index')
                        ->with('success', 'Data pasien berhasil dinonaktifkan!');
    }

    // Form untuk menggabungkan data pasien duplikat (mis. karena typo no HP saat registrasi)
    public function mergeForm($id)
    {
        $pasien = Pasien::findOrFail($id);

        if(!$pasien->is_aktif) {
            return redirect()->route('pasien.show', $id)
                ->with('error', 'Data pasien ini sudah nonaktif (hasil merge sebelumnya), tidak bisa dijadikan tujuan penggabungan lagi.');
        }

        // Kandidat duplikat: pasien lain yang masih aktif dengan nama mirip
        $kandidat = Pasien::where('id', '!=', $id)
            ->where('is_aktif', true)
            ->where('nama_pasien', 'like', '%'.$pasien->nama_pasien.'%')
            ->get();

        return view('resepsionis.pasien.merge', compact('pasien', 'kandidat'));
    }

    // Eksekusi penggabungan: seluruh jadwal & rekam medis dari data duplikat
    // dipindahkan ke data pasien tujuan ($id). Data duplikat TIDAK dihapus
    // (rekam medis wajib disimpan sesuai UU Rekam Medis), hanya dinonaktifkan.
    public function merge(Request $request, $id)
    {
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
            \App\Models\NotifikasiLog::where('pasien_id', $duplikat->id)
                ->update(['pasien_id' => $tujuan->id]);

            // Pindahkan akun Google dari duplikat ke tujuan (kalau tujuan belum punya),
            // supaya pasien yang login via app tetap bisa masuk ke data yang benar.
            $updateTujuan = [];
            if (empty($tujuan->google_uid) && !empty($duplikat->google_uid)) {
                $updateTujuan['google_uid']   = $duplikat->google_uid;
                $updateTujuan['email_google'] = $duplikat->email_google;
            }
            if (empty($tujuan->fcm_token) && !empty($duplikat->fcm_token)) {
                $updateTujuan['fcm_token'] = $duplikat->fcm_token;
            }
            if (!empty($updateTujuan)) {
                $tujuan->update($updateTujuan);
            }

            $duplikat->update([
                'is_aktif'     => false,
                'merged_ke_id' => $tujuan->id,
                'google_uid'   => null,
                'fcm_token'    => null,
            ]);
        });

        return redirect()->route('pasien.show', $tujuan->id)
            ->with('success', "Data pasien '{$duplikat->nama_pasien}' berhasil digabung ke akun ini. Data lama dinonaktifkan, bukan dihapus.");
    }
}
