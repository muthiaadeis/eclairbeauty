@extends('layouts.app')

@section('title', 'Gabung Data Pasien Duplikat - Eclair Beauty Clinic')

@section('content')

<div class="page-header-row">
    <div>
        <h1 class="page-title">Gabung Data Pasien Duplikat</h1>
        <p class="page-subtitle">
            Semua jadwal &amp; rekam medis dari data duplikat akan dipindahkan ke
            <strong>{{ $pasien->nama_pasien }} ({{ $pasien->no_hp }})</strong>,
            lalu data duplikat tersebut dihapus. Tindakan ini tidak bisa dibatalkan.
        </p>
    </div>
    <a href="{{ route('pasien.show', $pasien->id) }}" class="btn-secondary">Kembali</a>
</div>

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="card" style="max-width:640px;">
    <form action="{{ route('pasien.merge', $pasien->id) }}" method="POST">
        @csrf

        <label class="detail-item-label" for="duplikat_id">Pilih data pasien duplikat</label>

        @if($kandidat->isEmpty())
            <p class="detail-item-value" style="margin-top:8px;">
                Tidak ditemukan data pasien lain dengan nama yang mirip.
                Cari manual lewat menu <a href="{{ route('pasien.index') }}">Data Pasien</a>
                lalu buka halaman ini lagi dari pasien yang benar.
            </p>
        @else
            <select name="duplikat_id" id="duplikat_id" required
                    style="width:100%; padding:10px; margin-top:8px; border-radius:8px; border:1px solid #E0D5CE;">
                <option value="">-- Pilih pasien duplikat --</option>
                @foreach($kandidat as $k)
                    <option value="{{ $k->id }}">
                        {{ $k->nama_pasien }} — {{ $k->no_hp }} (terdaftar {{ \Carbon\Carbon::parse($k->created_at)->format('d M Y') }})
                    </option>
                @endforeach
            </select>

            <div class="detail-actions">
                <button type="submit" class="btn-primary"
                        onclick="return confirm('Yakin gabungkan data ini? Data duplikat akan dihapus permanen.')">
                    Gabungkan
                </button>
            </div>
        @endif
    </form>
</div>

@endsection
