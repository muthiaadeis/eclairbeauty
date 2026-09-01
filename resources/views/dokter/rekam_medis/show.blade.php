@extends('layouts.app')

@section('title', 'Rekam Medis - {{ $pasien->nama_pasien }}')

@section('extra_style')
.pasien-info-card {
    background: white;
    border-radius: 16px;
    padding: 24px 28px;
    margin-bottom: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
}
.pasien-avatar-lg {
    width: 54px; height: 54px;
    border-radius: 50%;
    background: linear-gradient(135deg, #FBE4DD, #FAD5CB);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 20px; color: #C17B7B;
    flex-shrink: 0;
}
.pasien-meta h2 {
    font-size: 18px; font-weight: 700; color: #3A3A3A; margin-bottom: 6px;
}
.pasien-meta-row {
    display: flex; gap: 20px; flex-wrap: wrap;
}
.pasien-meta-item {
    display: flex; align-items: center; gap: 6px;
    font-size: 13px; color: #6B6B6B;
}
.pasien-actions {
    display: flex; gap: 10px; align-items: center; flex-shrink: 0;
}

.btn-back-soft {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 18px;
    background: #FAF6F2;
    color: #6B6B6B;
    border: 1px solid #E8DDD5;
    border-radius: 10px;
    text-decoration: none;
    font-size: 13px; font-weight: 500;
    transition: all 0.2s;
}
.btn-back-soft:hover { background: #F0E8E2; }

.btn-tambah-rm {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 18px;
    background: #C17B7B; color: white;
    border: none; border-radius: 10px;
    text-decoration: none; font-size: 13px; font-weight: 600;
    transition: background 0.2s;
}
.btn-tambah-rm:hover { background: #B06B6B; }

.section-title {
    font-size: 15px; font-weight: 700; color: #3A3A3A;
    margin-bottom: 16px;
    display: flex; align-items: center; gap: 8px;
}

.rekam-card {
    background: white;
    border-radius: 16px;
    padding: 24px 28px;
    margin-bottom: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
.rekam-card-header {
    display: flex; justify-content: space-between; align-items: center;
    padding-bottom: 16px;
    border-bottom: 1px solid #F5EEE8;
    margin-bottom: 20px;
}
.rekam-tanggal {
    display: flex; align-items: center; gap: 8px;
    font-size: 15px; font-weight: 700; color: #C17B7B;
}
.rekam-dokter {
    font-size: 12px; color: #9B9B9B;
    background: #FAF6F2;
    padding: 5px 12px;
    border-radius: 20px;
}

.rekam-fields {
    display: grid; grid-template-columns: 1fr 1fr; gap: 20px;
}
.rekam-field label {
    font-size: 11px; color: #9B9B9B;
    text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;
    display: block; margin-bottom: 6px;
}
.rekam-field p {
    font-size: 14px; color: #3A3A3A; line-height: 1.5;
}
.rekam-field.full { grid-column: span 2; }

.kontrol-chip {
    display: inline-flex; align-items: center; gap: 6px;
    margin-top: 16px;
    padding: 8px 16px;
    background: #FDF0DC; color: #B8860B;
    border-radius: 20px; font-size: 13px; font-weight: 600;
}

.foto-section { margin-top: 20px; }
.foto-section-label {
    font-size: 11px; color: #9B9B9B;
    text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;
    margin-bottom: 12px;
}
.foto-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.foto-item { text-align: center; }
.foto-item img {
    width: 100%; height: 220px; object-fit: cover;
    border-radius: 12px; border: 1px solid #F0E8E2;
}
.foto-placeholder {
    width: 100%; height: 220px;
    background: #FAF6F2;
    border-radius: 12px;
    border: 1px dashed #E0D5CD;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    color: #C5B8B0; font-size: 13px; gap: 8px;
}
.foto-caption {
    margin-top: 8px;
    font-size: 11px; color: #9B9B9B;
    font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
}

.empty-rm {
    text-align: center; padding: 60px;
    color: #9B9B9B; font-size: 14px;
}
@endsection

@section('content')

{{-- Breadcrumb --}}
<div style="display:flex; align-items:center; gap:6px; font-size:11px; color:#9B9B9B; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">
    <a href="{{ route('dokter.dashboard') }}" style="color:#9B9B9B; text-decoration:none;">Manajemen</a>
    <span style="color:#D0C8C0;">›</span>
    <a href="{{ route('rekam_medis.index') }}" style="color:#9B9B9B; text-decoration:none;">Rekam Medis</a>
    <span style="color:#D0C8C0;">›</span>
    <span style="color:#C17B7B; font-weight:600;">{{ $pasien->nama_pasien }}</span>
</div>

<div class="page-header-row" style="margin-bottom:20px;">
    <h1 class="page-title">Detail Rekam Medis</h1>
</div>

@if(session('success'))
    <div class="alert-success" style="margin-bottom:20px;">✓ {{ session('success') }}</div>
@endif

{{-- Kartu Info Pasien --}}
<div class="pasien-info-card">
    <div style="display:flex; align-items:center; gap:16px;">
        <div class="pasien-avatar-lg">
            {{ strtoupper(substr($pasien->nama_pasien, 0, 2)) }}
        </div>
        <div class="pasien-meta">
            <h2>{{ $pasien->nama_pasien }}</h2>
            <div class="pasien-meta-row">
                <span class="pasien-meta-item">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.68A2 2 0 012 .99h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                    </svg>
                    {{ $pasien->no_hp ?? '—' }}
                </span>
                <span class="pasien-meta-item">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    {{ $pasien->jenis_kelamin == 'P' ? 'Perempuan' : ($pasien->jenis_kelamin == 'L' ? 'Laki-laki' : '—') }}
                </span>
                @if($pasien->tanggal_lahir)
                <span class="pasien-meta-item">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d M Y') }}
                </span>
                @endif
                <span class="pasien-meta-item" style="color:#C17B7B;">
                    #EC{{ str_pad($pasien->id, 5, '0', STR_PAD_LEFT) }}
                </span>
            </div>
        </div>
    </div>
    <div class="pasien-actions">
        <a href="{{ route('rekam_medis.index') }}" class="btn-back-soft">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            Kembali
        </a>
        <a href="{{ route('rekam_medis.create', $pasien->id) }}" class="btn-tambah-rm">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Rekam Medis
        </a>
    </div>
</div>

{{-- Riwayat Tindakan --}}
<div class="section-title">
    <svg width="16" height="16" fill="none" stroke="#C17B7B" stroke-width="2" viewBox="0 0 24 24">
        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
    </svg>
    Riwayat Tindakan
    <span style="font-size:12px; color:#9B9B9B; font-weight:400;">({{ $rekamMedis->count() }} catatan)</span>
</div>

@forelse($rekamMedis as $rm)
<div class="rekam-card">
    <div class="rekam-card-header">
        <div class="rekam-tanggal">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            {{ \Carbon\Carbon::parse($rm->tanggal_tindakan)->locale('id')->isoFormat('D MMMM YYYY') }}
        </div>
        <span class="rekam-dokter">
            Dokter: {{ $rm->user->nama ?? session('user_nama') ?? '—' }}
        </span>
    </div>

    <div class="rekam-fields">
        <div class="rekam-field">
            <label>Keluhan</label>
            <p>{{ $rm->keluhan ?? '—' }}</p>
        </div>
        <div class="rekam-field">
            <label>Hasil Konsultasi</label>
            <p>{{ $rm->hasil_konsultasi ?? '—' }}</p>
        </div>
        <div class="rekam-field full">
            <label>Catatan Tindakan</label>
            <p>{{ $rm->catatan_tindakan ?? '—' }}</p>
        </div>
    </div>

    @if($rm->tanggal_kontrol)
        <div class="kontrol-chip">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            Jadwal Kontrol: {{ \Carbon\Carbon::parse($rm->tanggal_kontrol)->locale('id')->isoFormat('D MMMM YYYY') }}
        </div>
    @endif

    {{-- Dokumentasi Foto --}}
    @foreach($rm->dokumentasi as $dok)
    <div class="foto-section">
        <div class="foto-section-label">Dokumentasi Foto</div>
        <div class="foto-grid">
            <div class="foto-item">
                @if($dok->foto_before)
                    <img src="{{ asset('storage/'.$dok->foto_before) }}" alt="Before">
                @else
                    <div class="foto-placeholder">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                        </svg>
                        Tidak ada foto
                    </div>
                @endif
                <div class="foto-caption">Before</div>
            </div>
            <div class="foto-item">
                @if($dok->foto_after)
                    <img src="{{ asset('storage/'.$dok->foto_after) }}" alt="After">
                @else
                    <div class="foto-placeholder">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                        </svg>
                        Tidak ada foto
                    </div>
                @endif
                <div class="foto-caption">After</div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@empty
<div class="rekam-card empty-rm">
    <div style="font-size:36px; margin-bottom:12px;">📋</div>
    <p style="font-weight:600; margin-bottom:6px;">Belum ada rekam medis</p>
    <p style="font-size:13px; color:#C5B8B0;">Klik "Tambah Rekam Medis" untuk memulai</p>
</div>
@endforelse

@endsection
