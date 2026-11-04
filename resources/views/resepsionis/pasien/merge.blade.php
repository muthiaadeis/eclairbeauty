@extends('layouts.app')

@section('title', 'Gabungkan Data Pasien - Eclair Beauty Clinic')

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
    background: #fff;
}
.form-control:focus { border-color: #C17B7B; }
.form-error { color: #C0392B; font-size: 12px; margin-top: 4px; }
.form-actions { margin-top: 8px; display: flex; gap: 12px; }

.merge-target {
    background: #FBF6F2;
    border: 1px solid #F0E8E2;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 20px;
    font-size: 14px;
    color: #3A3A3A;
}
.merge-target small { display: block; color: #9B9B9B; margin-top: 4px; font-size: 12px; }
.merge-warning {
    background: #FEF9C3;
    color: #854D0E;
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-size: 13.5px;
    line-height: 1.6;
}
@endsection

@section('content')

@php
    // Kandidat = nama mirip (dari controller). Sisanya = semua pasien aktif lain,
    // untuk kasus nama di dua data ditulis berbeda.
    $kandidatIds = $kandidat->pluck('id')->all();
    $lainnya = \App\Models\Pasien::where('is_aktif', true)
        ->where('id', '!=', $pasien->id)
        ->whereNotIn('id', $kandidatIds)
        ->orderBy('nama_pasien')
        ->get();
@endphp

<div class="page-header-row">
    <div>
        <h1 class="page-title">Gabungkan Data Pasien</h1>
        <p class="page-subtitle">Pindahkan jadwal & rekam medis dari data duplikat ke satu data pasien</p>
    </div>
    <a href="{{ route('pasien.show', $pasien->id) }}" class="btn-secondary">Kembali</a>
</div>

<div class="card" style="max-width:720px;">

    <div class="merge-target">
        <strong>Data yang dipertahankan:</strong> {{ $pasien->nama_pasien }}
        <small>No HP: {{ $pasien->no_hp ?? '-' }} · Terdaftar {{ \Carbon\Carbon::parse($pasien->created_at)->format('d M Y') }}</small>
    </div>

    <div class="merge-warning">
        Data duplikat yang kamu pilih akan <strong>dinonaktifkan</strong> (tidak dihapus), dan seluruh jadwal serta
        rekam medisnya dipindahkan ke <strong>{{ $pasien->nama_pasien }}</strong>. Penggabungan ini
        <strong>tidak bisa dibatalkan</strong> dari aplikasi, jadi pastikan keduanya memang pasien yang sama.
    </div>

    <form method="POST" action="{{ route('pasien.merge', $pasien->id) }}"
          onsubmit="return confirm('Gabungkan data duplikat ini ke {{ addslashes($pasien->nama_pasien) }}? Tindakan ini tidak bisa dibatalkan.');">
        @csrf

        <div class="form-group">
            <label class="form-label">Pilih data duplikat yang akan digabung *</label>
            <select name="duplikat_id" class="form-control" required>
                <option value="">-- Pilih data duplikat --</option>

                @if($kandidat->count())
                    <optgroup label="Nama mirip">
                        @foreach($kandidat as $k)
                            <option value="{{ $k->id }}" {{ old('duplikat_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_pasien }} — {{ $k->no_hp ?? 'tanpa no HP' }} ({{ $k->jadwal()->count() }} jadwal)
                            </option>
                        @endforeach
                    </optgroup>
                @endif

                @if($lainnya->count())
                    <optgroup label="Pasien aktif lainnya">
                        @foreach($lainnya as $k)
                            <option value="{{ $k->id }}" {{ old('duplikat_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_pasien }} — {{ $k->no_hp ?? 'tanpa no HP' }} ({{ $k->jadwal()->count() }} jadwal)
                            </option>
                        @endforeach
                    </optgroup>
                @endif
            </select>
            @error('duplikat_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Gabungkan Data</button>
            <a href="{{ route('pasien.show', $pasien->id) }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
