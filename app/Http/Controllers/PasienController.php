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
        $pasien = Pasien::with('jadwal')->findOrFail($id);
        return view('resepsionis.pasien.show', compact('pasien'));
    }

    public function edit($id)
    {
        if(!session('user_id')) return redirect()->route('login');
        $pasien = Pasien::findOrFail($id);
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
}
