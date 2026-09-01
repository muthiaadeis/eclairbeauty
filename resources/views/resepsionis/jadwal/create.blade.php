@extends('layouts.app')

@section('title', 'Tambah Jadwal - Eclair Beauty Clinic')

@section('extra_style')
.jadwal-card { max-width: 720px; }
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

.jam-section-label {
    font-size: 12px;
    font-weight: 700;
    color: #9B9B9B;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 20px 0 10px;
}
.jam-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}
.jam-chip {
    padding: 10px 6px;
    border: 1px solid #E8DDD5;
    border-radius: 10px;
    text-align: center;
    cursor: pointer;
    font-size: 13.5px;
    color: #3A3A3A;
    transition: all 0.15s;
}
.jam-chip:hover { border-color: #C17B7B; background: #FBF1EC; }
.jam-chip input[type="radio"] { display: none; }
.jam-chip.selected { border-color: #C17B7B; background: #C17B7B; color: #fff; font-weight: 600; }

.form-actions { margin-top: 28px; display: flex; gap: 12px; }
@endsection

@section('content')

<div class="page-header-row">
    <div>
        <h1 class="page-title">Tambah Jadwal Booking</h1>
        <p class="page-subtitle">Buat jadwal kunjungan baru untuk pasien walk-in</p>
    </div>
</div>

@if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
@endif

<div class="card jadwal-card">
    <form method="POST" action="{{ route('jadwal.store') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">Pilih Pasien *</label>
            <select name="pasien_id" class="form-control" required>
                <option value="">-- Pilih Pasien --</option>
                @foreach($pasien as $p)
                    <option value="{{ $p->id }}" {{ old('pasien_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_pasien }} - {{ $p->no_hp }}
                    </option>
                @endforeach
            </select>
            @error('pasien_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Pilih Dokter *</label>
            <select name="dokter_id" class="form-control" required>
                <option value="">-- Pilih Dokter --</option>
                @foreach($dokter as $d)
                    <option value="{{ $d->id }}" {{ old('dokter_id') == $d->id ? 'selected' : '' }}>
                        {{ $d->nama }}
                    </option>
                @endforeach
            </select>
            @error('dokter_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Tanggal Jadwal *</label>
                <input type="date" name="tanggal_jadwal" class="form-control"
                    value="{{ old('tanggal_jadwal', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                @error('tanggal_jadwal') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Keterangan</label>
                <input type="text" name="keterangan" class="form-control"
                    placeholder="Contoh: Konsultasi awal, Facial, dll" value="{{ old('keterangan') }}">
            </div>
        </div>

        <div class="jam-section-label">Pilih Jam Jadwal *</div>
        <div class="jam-grid">
            @php
                $jamList = ['09:00','09:30','10:00','10:30','11:00','11:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00'];
            @endphp
            @foreach($jamList as $jam)
                <label class="jam-chip {{ old('jam_jadwal') == $jam ? 'selected' : '' }}" onclick="pilihJam(this)">
                    <input type="radio" name="jam_jadwal" value="{{ $jam }}" {{ old('jam_jadwal') == $jam ? 'checked' : '' }} required>
                    {{ $jam }}
                </label>
            @endforeach
        </div>
        @error('jam_jadwal') <div class="form-error">{{ $message }}</div> @enderror

        <div class="form-actions">
            <button type="submit" class="btn-primary">Simpan Jadwal</button>
            <a href="{{ route('jadwal.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
function pilihJam(el) {
    document.querySelectorAll('.jam-chip').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
}
</script>

@endsection
