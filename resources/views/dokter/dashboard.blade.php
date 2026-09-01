@extends('layouts.app')

@section('title', 'Dashboard - Eclair Beauty Clinic')

@section('extra_style')
.stat-icon-orange { background: #FDF0DC; }
.stat-icon-red    { background: #FDE8E8; }
.stat-icon-green  { background: #E2F5E8; }

.stat-icon-orange svg { color: #D4950A; }
.stat-icon-red    svg { color: #C0392B; }
.stat-icon-green  svg { color: #2E8B4F; }

.section-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}
.section-title {
    font-size: 16px;
    font-weight: 700;
    color: #3A3A3A;
}

.search-wrap {
    position: relative;
}
.search-wrap svg {
    position: absolute; left: 12px; top: 50%;
    transform: translateY(-50%);
    width: 15px; height: 15px; color: #9B9B9B;
    pointer-events: none;
}
.search-input {
    padding: 9px 14px 9px 36px;
    border: 1px solid #F0E8E2;
    border-radius: 10px;
    font-size: 13px;
    color: #3A3A3A;
    background: #FAF6F2;
    outline: none;
    width: 220px;
}
.search-input:focus { border-color: #C17B7B; background: #fff; }

/* Table override for dashboard */
.dashboard-table thead th {
    font-size: 11px; color: #9B9B9B;
    text-transform: uppercase; letter-spacing: 0.5px;
    padding: 12px 16px;
    border-bottom: 1px solid #F0E8E2;
    background: transparent;
    font-weight: 600;
}
.dashboard-table tbody td {
    padding: 14px 16px;
    font-size: 13.5px;
    color: #3A3A3A;
    border-bottom: 1px solid #F8F2EE;
    vertical-align: middle;
}
.dashboard-table tbody tr:last-child td { border-bottom: none; }
.dashboard-table tbody tr:hover { background: #FDFAF8; }

.patient-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: #FBE4DD;
    color: #C17B7B;
    font-weight: 700;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    flex-shrink: 0;
}
.patient-cell { display: flex; align-items: center; }
.patient-id { font-size: 11px; color: #9B9B9B; margin-top: 2px; }

.badge-konsultasi-awal  { background: #FDF0DC; color: #B8860B; }
.badge-sedang           { background: #DCEEF5; color: #2C7A9B; }

.action-btn {
    width: 32px; height: 32px;
    border-radius: 8px;
    border: none; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center;
    text-decoration: none;
    transition: background 0.2s;
}
.action-btn-edit  { background: #FBF1EC; color: #C17B7B; }
.action-btn-edit:hover { background: #F5E2D8; }
.action-btn-view  { background: #EDF5FB; color: #2C7A9B; }
.action-btn-view:hover { background: #D8EBF5; }

.show-more {
    text-align: center;
    padding: 14px;
    font-size: 13px;
    color: #C17B7B;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: block;
    border-top: 1px solid #F8F2EE;
}
.show-more:hover { background: #FDFAF8; }
@endsection

@section('content')

{{-- Page Header --}}
<div class="page-header-row">
    <div>
        <h1 class="page-title">Dashboard Antrean</h1>
        <p class="page-subtitle">Pantau alur konsultasi pasien secara real-time</p>
    </div>
    <div class="topbar-date" style="background:#FAF6F2; padding:10px 18px; border-radius:12px; font-size:13px; color:#6B6B6B; display:flex; align-items:center; gap:8px;">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMM YYYY') }}
    </div>
</div>

{{-- Stat Cards --}}
@php
    $antrean = \App\Models\Jadwal::with('pasien')
        ->whereDate('tanggal_jadwal', today())
        ->whereIn('status_jadwal', ['menunggu', 'hadir', 'selesai'])
        ->orderBy('jam_jadwal', 'asc')
        ->get();
    $totalAntrean    = $antrean->count();
    $pasienMenunggu  = $antrean->whereIn('status_jadwal', ['menunggu', 'hadir'])->count();
    $selesaiTindakan = $antrean->where('status_jadwal', 'selesai')->count();
@endphp

<div class="stat-grid" style="margin-bottom:28px;">
    <div class="stat-card">
        <div class="stat-icon stat-icon-orange">
            <svg width="22" height="22" fill="none" stroke="#D4950A" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div>
            <div class="stat-label">Total Antrean</div>
            <div class="stat-value">{{ str_pad($totalAntrean, 2, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-red">
            <svg width="22" height="22" fill="none" stroke="#C0392B" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>
        <div>
            <div class="stat-label">Pasien Menunggu</div>
            <div class="stat-value">{{ str_pad($pasienMenunggu, 2, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-green">
            <svg width="22" height="22" fill="none" stroke="#2E8B4F" stroke-width="2" viewBox="0 0 24 24">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
        </div>
        <div>
            <div class="stat-label">Selesai Tindakan</div>
            <div class="stat-value">{{ str_pad($selesaiTindakan, 2, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>
</div>

{{-- Antrean Table --}}
<div class="card" style="padding:0; overflow:hidden;">
    <div class="section-header-row" style="padding:20px 24px 0;">
        <div class="section-title">Antrean Konsultasi Hari Ini</div>
        <div class="search-wrap">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" class="search-input" placeholder="Cari pasien..." id="searchInput">
        </div>
    </div>

    <div style="padding:16px 0 0;">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Nama Pasien</th>
                    <th>Jam Kedatangan</th>
                    <th>Layanan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($antrean->take(5) as $j)
                <tr class="patient-row">
                    <td>
                        <div class="patient-cell">
                            <div class="patient-avatar">
                                {{ strtoupper(substr($j->pasien->nama_pasien ?? 'P', 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;">{{ $j->pasien->nama_pasien ?? '-' }}</div>
                                <div class="patient-id">ID #EC{{ str_pad($j->pasien->id ?? 0, 5, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($j->jam_jadwal)->format('H:i') }} WIB</td>
                    <td>
                        <span class="badge badge-konsultasi-awal">
                            {{ $j->keterangan ?? 'Konsultasi Awal' }}
                        </span>
                    </td>
                    <td>
                        @if($j->status_jadwal == 'selesai')
                            <span class="badge badge-selesai">Selesai</span>
                        @elseif($j->status_jadwal == 'hadir')
                            <span class="badge badge-sedang">Sedang Konsultasi</span>
                        @else
                            <span class="badge badge-menunggu">Menunggu</span>
                        @endif
                    </td>
                    <td>
                        @if($j->status_jadwal == 'selesai')
                            <a href="{{ route('rekam_medis.show', $j->pasien_id) }}" class="action-btn action-btn-view" title="Lihat rekam medis">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('rekam_medis.create', $j->pasien_id) }}" class="action-btn action-btn-edit" title="Input rekam medis">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:48px; color:#9B9B9B; font-size:14px;">
                        Belum ada antrean hari ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($antrean->count() > 5)
    <a href="{{ route('jadwal.index') }}" class="show-more">
        Tampilkan Lebih Banyak
    </a>
    @endif
</div>

<script>
document.getElementById('searchInput').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#tableBody .patient-row').forEach(row => {
        const name = row.querySelector('.patient-cell div div')?.textContent?.toLowerCase() ?? '';
        row.style.display = name.includes(q) ? '' : 'none';
    });
});
</script>
@endsection
