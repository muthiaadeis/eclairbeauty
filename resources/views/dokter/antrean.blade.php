@extends('layouts.app')

@section('title', 'Antrean Pasien - Eclair Beauty Clinic')

@section('extra_style')
.breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: 11px; color: #9B9B9B;
    text-transform: uppercase; letter-spacing: 0.5px;
    margin-bottom: 6px;
}
.breadcrumb a { color: #9B9B9B; text-decoration: none; }
.breadcrumb a:hover { color: #C17B7B; }
.breadcrumb .bc-sep { color: #D0C8C0; }
.breadcrumb .bc-active { color: #C17B7B; font-weight: 600; }

.antrean-table thead th {
    font-size: 11px; color: #9B9B9B;
    text-transform: uppercase; letter-spacing: 0.5px;
    padding: 12px 20px;
    border-bottom: 1px solid #F0E8E2;
    font-weight: 600;
}
.antrean-table tbody td {
    padding: 18px 20px;
    font-size: 13.5px;
    color: #3A3A3A;
    border-bottom: 1px solid #F8F2EE;
    vertical-align: middle;
}
.antrean-table tbody tr:last-child td { border-bottom: none; }
.antrean-table tbody tr:hover { background: #FDFAF8; }

/* Nomor antrean badge */
.no-antrean {
    width: 46px; height: 46px;
    border-radius: 12px;
    background: #FBE4DD;
    color: #C17B7B;
    font-weight: 700;
    font-size: 13px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

.patient-name { font-weight: 600; font-size: 14px; color: #3A3A3A; }
.patient-treatment { font-size: 12px; color: #9B9B9B; margin-top: 3px; }
.patient-id-small { font-size: 12px; color: #9B9B9B; }

.jam-cell { font-size: 14px; font-weight: 500; color: #3A3A3A; }

.badge-menunggu-soft {
    background: #FDF0DC;
    color: #B8860B;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.btn-mulai-konsultasi {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px;
    background: #C17B7B;
    color: white;
    border: none; border-radius: 10px;
    font-size: 13px; font-weight: 600;
    cursor: pointer; text-decoration: none;
    transition: background 0.2s;
    white-space: nowrap;
}
.btn-mulai-konsultasi:hover { background: #B06B6B; }
.btn-mulai-konsultasi svg { width: 15px; height: 15px; }

.btn-lanjut {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px;
    background: #2C7A9B;
    color: white;
    border: none; border-radius: 10px;
    font-size: 13px; font-weight: 600;
    cursor: pointer; text-decoration: none;
    transition: background 0.2s;
}
.btn-lanjut:hover { background: #236080; }

.table-footer {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-top: 1px solid #F0E8E2;
    font-size: 12px; color: #9B9B9B;
    text-transform: uppercase; letter-spacing: 0.5px;
}
.pagination-btns { display: flex; gap: 8px; }
.pag-btn {
    width: 30px; height: 30px;
    border-radius: 8px; border: 1px solid #F0E8E2;
    background: white; color: #6B6B6B;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 13px;
    text-decoration: none;
    transition: all 0.2s;
}
.pag-btn:hover { background: #FBF1EC; border-color: #C17B7B; color: #C17B7B; }
@endsection

@section('content')

<div class="breadcrumb">
    <a href="{{ route('dokter.dashboard') }}">Manajemen</a>
    <span class="bc-sep">›</span>
    <span class="bc-active">Antrean Pasien</span>
</div>

<div class="page-header-row" style="margin-bottom:24px;">
    <div>
        <h1 class="page-title">Antrean Konsultasi Hari Ini</h1>
        <p class="page-subtitle">Menampilkan daftar pasien yang siap untuk konsultasi</p>
    </div>
    <div style="font-size:13px; color:#6B6B6B; display:flex; align-items:center; gap:8px; background:#FAF6F2; padding:10px 18px; border-radius:12px;">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMM YYYY') }}
    </div>
</div>

<div class="card" style="padding:0; overflow:hidden;">
    <table class="antrean-table" style="width:100%; border-collapse:collapse;">
        <thead>
            <tr>
                <th style="width:80px;">Nomor<br>Antrean</th>
                <th>ID Pasien</th>
                <th>Nama Pasien</th>
                <th>Jam Check-In</th>
                <th>Status</th>
                <th style="text-align:right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwal as $index => $j)
            <tr>
                <td>
                    <div class="no-antrean">
                        A-<br>{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}
                    </div>
                </td>
                <td>
                    <span class="patient-id-small">#EC{{ str_pad($j->pasien->id ?? 0, 5, '0', STR_PAD_LEFT) }}</span>
                </td>
                <td>
                    <div class="patient-name">{{ $j->pasien->nama_pasien ?? '-' }}</div>
                    <div class="patient-treatment">Treatment: {{ $j->keterangan ?? 'Konsultasi Awal' }}</div>
                </td>
                <td>
                    <span class="jam-cell">{{ \Carbon\Carbon::parse($j->jam_jadwal)->format('H:i') }} WIB</span>
                </td>
                <td>
                    @if($j->status_jadwal == 'selesai')
                        <span class="badge badge-selesai">Selesai</span>
                    @elseif($j->status_jadwal == 'hadir')
                        <span class="badge badge-hadir">Sedang Konsultasi</span>
                    @else
                        <span class="badge-menunggu-soft">Menunggu</span>
                    @endif
                </td>
                <td style="text-align:right;">
                    @if($j->status_jadwal == 'selesai')
                        <a href="{{ route('rekam_medis.show', $j->pasien_id) }}" class="btn-lanjut">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            Lihat Rekam Medis
                        </a>
                    @else
                        <a href="{{ route('rekam_medis.create', $j->pasien_id) }}" class="btn-mulai-konsultasi">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><polyline points="10 8 16 12 10 16"/>
                            </svg>
                            Mulai Konsultasi
                        </a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:60px; color:#9B9B9B; font-size:14px;">
                    <div style="font-size:32px; margin-bottom:12px;">📋</div>
                    Belum ada antrean hari ini
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="table-footer">
        <span>Menampilkan {{ $jadwal->count() }} dari {{ $jadwal->count() }} pasien antrean hari ini</span>
        <div class="pagination-btns">
            <a href="#" class="pag-btn">‹</a>
            <a href="#" class="pag-btn">›</a>
        </div>
    </div>
</div>

@endsection
