@extends('layouts.app')

@section('title', 'Detail Pasien - Eclair Beauty Clinic')

@section('extra_style')
.profile-banner {
    display: flex;
    align-items: center;
    gap: 18px;
    padding-bottom: 24px;
    margin-bottom: 24px;
    border-bottom: 1px solid #F0E8E2;
}
.profile-banner-avatar {
    width: 64px; height: 64px;
    border-radius: 50%;
    background: #FBE4DD;
    color: #C17B7B;
    font-size: 22px;
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.profile-banner-name { font-size: 20px; font-weight: 700; color: #3A3A3A; }
.profile-banner-sub { font-size: 13px; color: #9B9B9B; margin-top: 4px; }

.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px 32px;
}
.detail-item-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #9B9B9B;
    font-weight: 600;
    margin-bottom: 6px;
}
.detail-item-value { font-size: 14.5px; color: #3A3A3A; }
.detail-item.full { grid-column: 1 / -1; }

.detail-actions {
    margin-top: 28px;
    padding-top: 20px;
    border-top: 1px solid #F0E8E2;
    display: flex;
    gap: 12px;
}
@endsection

@section('content')

<div class="page-header-row">
    <div>
        <h1 class="page-title">Detail Pasien</h1>
        <p class="page-subtitle">Informasi lengkap data pasien</p>
    </div>
    <a href="{{ route('pasien.index') }}" class="btn-secondary">Kembali</a>
</div>

<div class="card" style="max-width:760px;">
    <div class="profile-banner">
        <div class="profile-banner-avatar">
            {{ strtoupper(substr($pasien->nama_pasien, 0, 2)) }}
        </div>
        <div>
            <div class="profile-banner-name">{{ $pasien->nama_pasien }}</div>
            <div class="profile-banner-sub">{{ $pasien->no_hp }}</div>
        </div>
    </div>

    <div class="detail-grid">
        <div class="detail-item">
            <div class="detail-item-label">Jenis Kelamin</div>
            <div class="detail-item-value">
                {{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : ($pasien->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-item-label">Tanggal Lahir</div>
            <div class="detail-item-value">
                {{ $pasien->tanggal_lahir ? \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d M Y') : '-' }}
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-item-label">Nomor HP</div>
            <div class="detail-item-value">{{ $pasien->no_hp }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-item-label">Terdaftar Sejak</div>
            <div class="detail-item-value">{{ \Carbon\Carbon::parse($pasien->created_at)->format('d M Y') }}</div>
        </div>
        <div class="detail-item full">
            <div class="detail-item-label">Alamat</div>
            <div class="detail-item-value">{{ $pasien->alamat ?? '-' }}</div>
        </div>
    </div>

    <div class="detail-actions">
        <a href="{{ route('pasien.edit', $pasien->id) }}" class="btn-primary">Edit Data</a>
    </div>
</div>

@endsection
