@extends('layouts.app')

@section('title', 'Edit Pasien - Eclair Beauty Clinic')

@section('extra_style')
.form-group { margin-bottom: 20px; }
.form-label {
    display: block;
    margin-bottom: 6px;
    font-size: 13px;
    color: #6B6B6B;
    font-weight: 600;
}
.form-control {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #E8DDD5;
    border-radius: 10px;
    font-size: 14px;
    color: #3A3A3A;
    outline: none;
}
.form-control:focus { border-color: #C17B7B; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-error { color: #C0392B; font-size: 12px; margin-top: 4px; }
.form-actions { margin-top: 8px; display: flex; gap: 12px; }
@endsection

@section('content')

<div class="page-header-row">
    <div>
        <h1 class="page-title">Edit Data Pasien</h1>
        <p class="page-subtitle">Perbarui informasi {{ $pasien->nama_pasien }}</p>
    </div>
    <a href="{{ route('pasien.show', $pasien->id) }}" class="btn-secondary">Kembali</a>
</div>

<div class="card" style="max-width:720px;">
    <form method="POST" action="{{ route('pasien.update', $pasien->id) }}">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" name="nama_pasien" class="form-control" value="{{ old('nama_pasien', $pasien->nama_pasien) }}">
                @error('nama_pasien') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Nomor HP *</label>
                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $pasien->no_hp) }}">
                @error('no_hp') <div class="form-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $pasien->tanggal_lahir) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ $pasien->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ $pasien->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" rows="3" class="form-control">{{ old('alamat', $pasien->alamat) }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Update Data</button>
            <a href="{{ route('pasien.show', $pasien->id) }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
