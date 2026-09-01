@extends('layouts.app')

@section('title', 'Data Pasien')

@section('extra_style')
/* ── Breadcrumb ── */
.breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: 0.72rem; color: #9CA3AF;
    text-transform: uppercase; letter-spacing: 0.05em;
    margin-bottom: 6px;
}
.breadcrumb a { color: #9CA3AF; text-decoration: none; }
.breadcrumb a:hover { color: #C17B7B; }
.breadcrumb span { color: #C17B7B; font-weight: 600; }

/* ── Page header row ── */
.ph-row {
    display: flex; align-items: flex-start;
    justify-content: space-between; gap: 1rem;
    margin-bottom: 1.5rem;
}
.ph-left .page-title  { margin-bottom: 4px; }
.ph-left .page-subtitle { margin-bottom: 0; }

.btn-tambah-pasien {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 0.65rem 1.25rem;
    background: #C17B7B; color: #fff;
    border: none; border-radius: 12px;
    font-size: 0.85rem; font-weight: 600;
    cursor: pointer; text-decoration: none;
    white-space: nowrap; flex-shrink: 0;
    transition: background 0.2s;
}
.btn-tambah-pasien:hover { background: #B06B6B; }
.btn-tambah-pasien svg  { width: 16px; height: 16px; }

/* ── Filter bar ── */
.filter-bar {
    display: flex; align-items: center;
    justify-content: space-between; gap: 1rem;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
}
.filter-tabs { display: flex; gap: 8px; }
.filter-tab {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 0.5rem 1rem;
    border-radius: 20px; font-size: 0.82rem; font-weight: 500;
    cursor: pointer; text-decoration: none; border: none;
    background: none; transition: all 0.15s;
    color: #6B7280;
}
.filter-tab:hover  { background: #F3F4F6; }
.filter-tab.active {
    background: #C17B7B; color: #fff; font-weight: 600;
}
.filter-tab svg { width: 14px; height: 14px; }

.sort-box {
    display: flex; align-items: center; gap: 8px;
    font-size: 0.82rem; color: #6B7280;
}
.sort-box select {
    padding: 0.45rem 0.75rem;
    border: 1px solid #E5E7EB; border-radius: 8px;
    font-size: 0.82rem; color: #374151;
    background: #fff; outline: none; cursor: pointer;
}
.sort-box select:focus { border-color: #C17B7B; }

/* ── Table card ── */
.pasien-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    overflow: hidden;
}

/* ── Alert ── */
.alert-ok {
    background: #E6F9F1; color: #1A7A4A;
    padding: 0.85rem 1.1rem; border-radius: 12px;
    margin-bottom: 1rem; font-size: 0.875rem;
    display: flex; align-items: center; gap: 8px;
}

/* ── Table ── */
.pasien-table { width: 100%; border-collapse: collapse; }
.pasien-table th {
    padding: 0.75rem 1.25rem;
    text-align: left; font-size: 0.68rem;
    font-weight: 600; text-transform: uppercase;
    letter-spacing: 0.07em; color: #9CA3AF;
    border-bottom: 1px solid #F3F4F6;
}
.pasien-table td {
    padding: 0.9rem 1.25rem;
    border-bottom: 1px solid #F9FAFB;
    font-size: 0.875rem; color: #374151;
    vertical-align: middle;
}
.pasien-table tbody tr:last-child td { border-bottom: none; }
.pasien-table tbody tr:hover { background: #FDFAF9; }

/* ID cell */
.id-cell { color: #C17B7B; font-weight: 600; font-size: 0.82rem; }

/* Patient name cell */
.pname-cell { display: flex; align-items: center; gap: 10px; }
.p-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.78rem; font-weight: 700; color: #fff;
    flex-shrink: 0;
}
.av-rose   { background: #C17B7B; }
.av-blue   { background: #6B9EC7; }
.av-green  { background: #6BBF8A; }
.av-purple { background: #9B85C4; }
.av-orange { background: #E8A066; }

.pname     { font-size: 0.88rem; font-weight: 600; color: #1F2937; }
.pgender   { font-size: 0.72rem; color: #9CA3AF; }

/* Date cell */
.date-cell { color: #6B7280; font-size: 0.82rem; }

/* Status badge */
.badge-aktif      { display:inline-block; padding:0.25rem 0.65rem; border-radius:20px; font-size:0.72rem; font-weight:600; background:#E6F9F1; color:#1A7A4A; }
.badge-tdk-aktif  { display:inline-block; padding:0.25rem 0.65rem; border-radius:20px; font-size:0.72rem; font-weight:600; background:#F3F4F6; color:#6B7280; }

/* Action buttons */
.aksi-cell { display: flex; align-items: center; gap: 6px; }
.btn-icon {
    width: 32px; height: 32px; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    border: none; cursor: pointer; text-decoration: none;
    transition: background 0.15s;
}
.btn-icon svg { width: 16px; height: 16px; }
.btn-icon.view  { background: #F3F4F6; color: #6B7280; }
.btn-icon.edit  { background: #FBE9E9; color: #C17B7B; }
.btn-icon.view:hover { background: #E5E7EB; }
.btn-icon.edit:hover { background: #F5D5D5; }

/* ── Footer ── */
.table-footer {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 1rem 1.25rem;
    border-top: 1px solid #F3F4F6;
    font-size: 0.78rem; color: #9CA3AF;
    flex-wrap: wrap; gap: 0.5rem;
}
.pagi {
    display: flex; align-items: center; gap: 4px;
}
.pagi a, .pagi span {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; border-radius: 8px;
    font-size: 0.82rem; font-weight: 500; text-decoration: none;
    border: 1px solid #E5E7EB; color: #374151;
    transition: all 0.15s;
}
.pagi a:hover            { background: #FBE9E9; border-color: #C17B7B; color: #C17B7B; }
.pagi span.active        { background: #C17B7B; border-color: #C17B7B; color: #fff; }
.pagi span.disabled      { color: #D1D5DB; cursor: default; }

/* Empty */
.empty-pasien {
    text-align: center; padding: 3.5rem 1rem; color: #9CA3AF;
}
.empty-pasien-icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
@endsection

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <a href="{{ route('resepsionis.dashboard') }}">Manajemen</a>
    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
    <span>Data Pasien</span>
</div>

{{-- Page header --}}
<div class="ph-row">
    <div class="ph-left">
        <h1 class="page-title">Manajemen Data Pasien</h1>
        <p class="page-subtitle">Daftar lengkap pasien klinik. Kelola informasi personal dan riwayat kunjungan dengan aman.</p>
    </div>
    <a href="{{ route('pasien.create') }}" class="btn-tambah-pasien">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        Tambah Pasien Baru
    </a>
</div>

{{-- Alert --}}
@if(session('success'))
    <div class="alert-ok">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

{{-- Filter bar --}}
<form method="GET" action="{{ route('pasien.index') }}" id="filterForm">
<div class="filter-bar">
    <div class="filter-tabs">
        <button type="submit" name="filter" value="semua"
            class="filter-tab {{ (!request('filter') || request('filter') == 'semua') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            Semua Pasien
        </button>
        <button type="submit" name="filter" value="terbaru"
            class="filter-tab {{ request('filter') == 'terbaru' ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Terakhir Berkunjung
        </button>
    </div>

    <div class="sort-box">
        Urutkan:
        <select name="sort" onchange="document.getElementById('filterForm').submit()">
            <option value="terbaru" {{ request('sort','terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
            <option value="terlama" {{ request('sort') == 'terlama'  ? 'selected' : '' }}>Terlama</option>
            <option value="nama"    {{ request('sort') == 'nama'     ? 'selected' : '' }}>Nama A–Z</option>
        </select>
        <input type="hidden" name="search" value="{{ $search ?? '' }}">
    </div>
</div>
</form>

{{-- Table --}}
<div class="pasien-card">
    @if($pasien->isEmpty())
        <div class="empty-pasien">
            <div class="empty-pasien-icon">👥</div>
            <p>Belum ada data pasien{{ ($search ?? '') ? ' yang cocok dengan pencarian.' : '.' }}</p>
        </div>
    @else
        <table class="pasien-table">
            <thead>
                <tr>
                    <th>ID Pasien</th>
                    <th>Nama Lengkap</th>
                    <th>No. Telepon</th>
                    <th>Kunjungan Terakhir</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $avColors = ['av-rose','av-blue','av-green','av-purple','av-orange'];
                    $idx = 0;
                @endphp
                @foreach($pasien as $p)
                    @php
                        $initials = collect(explode(' ', $p->nama_pasien))->take(2)->map(fn($w) => strtoupper($w[0]))->join('');
                        $avClass  = $avColors[$idx % 5];
                        $idx++;

                        $genderLabel = match($p->jenis_kelamin) {
                            'L' => 'Laki-laki',
                            'P' => 'Perempuan',
                            default => '-'
                        };

                        // Kunjungan terakhir: ambil dari jadwal terakhir yang selesai
                        $kunjunganTerakhir = $p->jadwal()
                            ->where('status_jadwal', 'selesai')
                            ->orderByDesc('tanggal_jadwal')
                            ->value('tanggal_jadwal');

                        $isAktif = $p->jadwal()->exists();
                    @endphp
                    <tr>
                        <td class="id-cell">EP-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="pname-cell">
                                <div class="p-avatar {{ $avClass }}">{{ $initials }}</div>
                                <div>
                                    <div class="pname">{{ $p->nama_pasien }}</div>
                                    <div class="pgender">
                                        {{ $genderLabel }}
                                        @if($p->tanggal_lahir)
                                            , {{ \Carbon\Carbon::parse($p->tanggal_lahir)->age }} thn
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $p->no_hp ?? '-' }}</td>
                        <td class="date-cell">
                            {{ $kunjunganTerakhir ? \Carbon\Carbon::parse($kunjunganTerakhir)->locale('id')->isoFormat('D MMM YYYY') : '-' }}
                        </td>
                        <td>
                            @if($isAktif)
                                <span class="badge-aktif">Aktif</span>
                            @else
                                <span class="badge-tdk-aktif">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="aksi-cell">
                                <a href="{{ route('pasien.show', $p->id) }}" class="btn-icon view" title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('pasien.edit', $p->id) }}" class="btn-icon edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Footer: count + pagination --}}
        <div class="table-footer">
            <span>
                Menampilkan {{ $pasien->firstItem() }}–{{ $pasien->lastItem() }} dari {{ $pasien->total() }} pasien
            </span>
            <div class="pagi">
                {{-- Prev --}}
                @if($pasien->onFirstPage())
                    <span class="disabled">‹</span>
                @else
                    <a href="{{ $pasien->previousPageUrl() }}">‹</a>
                @endif

                {{-- Pages --}}
                @php
                    $current  = $pasien->currentPage();
                    $last     = $pasien->lastPage();
                    $start    = max(1, $current - 1);
                    $end      = min($last, $current + 1);
                @endphp

                @if($start > 1)<a href="{{ $pasien->url(1) }}">1</a>@endif
                @if($start > 2)<span class="disabled">…</span>@endif

                @for($page = $start; $page <= $end; $page++)
                    @if($page == $current)
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $pasien->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor

                @if($end < $last - 1)<span class="disabled">…</span>@endif
                @if($end < $last)<a href="{{ $pasien->url($last) }}">{{ $last }}</a>@endif

                {{-- Next --}}
                @if($pasien->hasMorePages())
                    <a href="{{ $pasien->nextPageUrl() }}">›</a>
                @else
                    <span class="disabled">›</span>
                @endif
            </div>
        </div>
    @endif
</div>

@endsection
