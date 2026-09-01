@extends('layouts.app')

@section('title', 'Dashboard Antrean')

@section('extra_style')
/* ── Stats Cards ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}
.dash-stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.dash-stat-icon {
    width: 48px; height: 48px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.dash-stat-icon.orange { background: #FFF0E6; color: #E8834A; }
.dash-stat-icon.rose   { background: #FBE9E9; color: #C17B7B; }
.dash-stat-icon.green  { background: #E6F9F1; color: #2EAA72; }
.dash-stat-label {
    font-size: 0.68rem; font-weight: 600;
    letter-spacing: 0.08em; text-transform: uppercase;
    color: #9CA3AF; margin-bottom: 0.2rem;
}
.dash-stat-value {
    font-size: 2rem; font-weight: 700;
    color: #1F2937; line-height: 1;
}

/* ── Section Card ── */
.section-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    overflow: hidden;
}
.section-header {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #F3F4F6;
}
.section-title { font-size: 1rem; font-weight: 600; color: #1F2937; margin: 0; }

.dash-search { position: relative; }
.dash-search input {
    padding: 0.5rem 0.75rem 0.5rem 2.2rem;
    border: 1px solid #E5E7EB; border-radius: 10px;
    font-size: 0.85rem; color: #374151;
    background: #F9FAFB; outline: none; width: 200px;
    transition: border-color 0.2s;
}
.dash-search input:focus { border-color: #C17B7B; background: #fff; }
.dash-search svg {
    position: absolute; left: 0.6rem; top: 50%;
    transform: translateY(-50%); color: #9CA3AF;
    width: 14px; height: 14px; pointer-events: none;
}

/* ── Queue Table ── */
.queue-table { width: 100%; border-collapse: collapse; }
.queue-table thead tr { border-bottom: 1px solid #F3F4F6; }
.queue-table th {
    padding: 0.75rem 1.5rem; text-align: left;
    font-size: 0.68rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: 0.07em; color: #9CA3AF;
}
.queue-table td {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #F9FAFB;
    vertical-align: middle; font-size: 0.88rem; color: #374151;
}
.queue-table tbody tr:last-child td { border-bottom: none; }
.queue-table tbody tr:hover { background: #FDFAF9; }

/* Patient cell */
.patient-cell { display: flex; align-items: center; gap: 0.75rem; }
.q-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.78rem; font-weight: 700; color: #fff; flex-shrink: 0;
}
.av-0 { background: #C17B7B; } .av-1 { background: #6B9EC7; }
.av-2 { background: #6BBF8A; } .av-3 { background: #9B85C4; }
.av-4 { background: #E8A066; }
.patient-name { font-size: 0.9rem; font-weight: 600; color: #1F2937; }
.patient-id   { font-size: 0.72rem; color: #9CA3AF; }

/* Badges */
.badge-layanan {
    display: inline-block; padding: 0.3rem 0.75rem;
    border-radius: 20px; font-size: 0.75rem; font-weight: 500;
    background: #FBE9E9; color: #C17B7B;
}
.badge-q {
    display: inline-block; padding: 0.3rem 0.75rem;
    border-radius: 20px; font-size: 0.72rem; font-weight: 600;
    letter-spacing: 0.04em; text-transform: uppercase;
}
.badge-q.menunggu { background: #F3F4F6; color: #6B7280; }
.badge-q.hadir    { background: #EBF5FF; color: #3B82F6; }
.badge-q.selesai  { background: #E6F9F1; color: #2EAA72; }

/* Action button */
.btn-aksi {
    background: none; border: none; cursor: pointer;
    padding: 0.3rem; border-radius: 8px;
    color: #C17B7B; transition: background 0.15s;
    display: inline-flex; align-items: center;
}
.btn-aksi:hover { background: #FBE9E9; }
.btn-aksi svg { width: 18px; height: 18px; }

/* Empty state */
.empty-state {
    text-align: center; padding: 3rem 1rem; color: #9CA3AF;
}
.empty-state-icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
.empty-state p { font-size: 0.9rem; }

/* Show more */
.btn-show-more {
    display: block; width: 100%; padding: 1rem;
    text-align: center; font-size: 0.85rem; font-weight: 600;
    color: #C17B7B; background: none; border: none;
    border-top: 1px solid #F3F4F6; cursor: pointer;
    transition: background 0.15s; text-decoration: none;
}
.btn-show-more:hover { background: #FDFAF9; }

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: 1fr; }
}
.today-badge {
    background: #FBE9E9;
    color: #C17B7B;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.55rem 1.1rem;
    border-radius: 12px;
    white-space: nowrap;
    flex-shrink: 0;
}
@endsection

@section('content')

{{-- Header --}}
<div class="page-header-row" style="align-items:flex-start;">
    <div>
        <div class="page-breadcrumb">MANAJEMEN</div>
        <h1 class="page-title">Dashboard Antrean</h1>
        <p class="page-subtitle" style="margin-bottom:0;">Pantau alur konsultasi pasien secara real-time</p>
    </div>
    <div class="today-badge">
        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="dash-stat-card">
        <div class="dash-stat-icon orange">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <div class="dash-stat-label">Total Antrean</div>
            <div class="dash-stat-value">{{ str_pad($totalAntrean, 2, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>

    <div class="dash-stat-card">
        <div class="dash-stat-icon rose">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div class="dash-stat-label">Pasien Menunggu</div>
            <div class="dash-stat-value">{{ str_pad($pasienMenunggu, 2, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>

    <div class="dash-stat-card">
        <div class="dash-stat-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div class="dash-stat-label">Selesai Tindakan</div>
            <div class="dash-stat-value">{{ str_pad($selesaiTindakan, 2, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>
</div>

{{-- Queue Table --}}
<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">Antrean Konsultasi Hari Ini</h2>
        <div class="dash-search">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari pasien...">
        </div>
    </div>

    @if($antrean->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">📋</div>
            <p>Belum ada jadwal untuk hari ini.</p>
        </div>
    @else
        <table class="queue-table" id="queueTable">
            <thead>
                <tr>
                    <th>Nama Pasien</th>
                    <th>Jam Kedatangan</th>
                    <th>Layanan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($antrean as $i => $jadwal)
                    @php
                        $nama     = $jadwal->pasien->nama_pasien ?? '-';
                        $initials = collect(explode(' ', $nama))->take(2)->map(fn($w) => strtoupper($w[0]))->join('');
                        $avClass  = 'av-' . ($i % 5);
                        $status   = $jadwal->status_jadwal;
                        $statusLabel = match($status) {
                            'hadir'   => 'Sedang Konsultasi',
                            'selesai' => 'Selesai',
                            default   => 'Menunggu',
                        };
                    @endphp
                    <tr>
                        <td>
                            <div class="patient-cell">
                                <div class="q-avatar {{ $avClass }}">{{ $initials }}</div>
                                <div>
                                    <div class="patient-name">{{ $nama }}</div>
                                    <div class="patient-id">ID: #{{ $jadwal->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($jadwal->jam_jadwal)->format('H:i') }} WIB</td>
                        <td><span class="badge-layanan">Konsultasi</span></td>
                        <td><span class="badge-q {{ $status === 'hadir' ? 'hadir' : $status }}">{{ $statusLabel }}</span></td>
                        <td>
                            @if($status === 'selesai')
                                {{-- Lihat rekam medis --}}
                                <a href="{{ route('rekam_medis.show', $jadwal->pasien_id) }}" class="btn-aksi" title="Lihat Rekam Medis">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            @elseif($status === 'menunggu')
                                {{-- Check-in --}}
                                <form method="POST" action="{{ route('jadwal.checkin', $jadwal->id) }}" style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn-aksi" title="Check-in Pasien">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                {{-- Hadir: selesaikan --}}
                                <form method="POST" action="{{ route('jadwal.selesai', $jadwal->id) }}" style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn-aksi" title="Selesaikan">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('jadwal.index') }}" class="btn-show-more">Tampilkan Lebih Banyak</a>
    @endif
</div>

<script>
    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#queueTable tbody tr').forEach(row => {
            const name = row.querySelector('.patient-name');
            if (name) row.style.display = name.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
</script>

@endsection
