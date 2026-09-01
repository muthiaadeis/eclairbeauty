@extends('layouts.app')

@section('title', 'Jadwal Konsultasi')

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
.btn-tambah-jadwal {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 0.65rem 1.25rem;
    background: #C17B7B; color: #fff;
    border: none; border-radius: 12px;
    font-size: 0.85rem; font-weight: 600;
    cursor: pointer; text-decoration: none;
    white-space: nowrap; flex-shrink: 0;
    transition: background 0.2s;
}
.btn-tambah-jadwal:hover { background: #B06B6B; }
.btn-tambah-jadwal svg { width: 16px; height: 16px; }

/* ── Search + filter bar ── */
.search-filter-bar {
    display: flex; align-items: center;
    justify-content: space-between; gap: 1rem;
    margin-bottom: 1.25rem; flex-wrap: wrap;
}
.search-wrap {
    position: relative; flex: 1; max-width: 320px;
}
.search-wrap svg {
    position: absolute; left: 0.75rem; top: 50%;
    transform: translateY(-50%);
    width: 15px; height: 15px; color: #9CA3AF;
    pointer-events: none;
}
.search-wrap input {
    width: 100%; padding: 0.55rem 0.75rem 0.55rem 2.2rem;
    border: 1px solid #E5E7EB; border-radius: 10px;
    font-size: 0.85rem; color: #374151;
    background: #fff; outline: none;
    transition: border-color 0.2s;
}
.search-wrap input:focus { border-color: #C17B7B; }

.bar-right { display: flex; align-items: center; gap: 10px; }

.btn-filter {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 0.5rem 1rem;
    border: 1px solid #E5E7EB; border-radius: 10px;
    background: #fff; font-size: 0.83rem;
    color: #6B7280; cursor: pointer;
    transition: border-color 0.2s;
}
.btn-filter:hover { border-color: #C17B7B; color: #C17B7B; }
.btn-filter svg { width: 14px; height: 14px; }

.date-chip {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 0.5rem 0.9rem;
    background: #FBE9E9; border-radius: 10px;
    font-size: 0.82rem; font-weight: 500; color: #C17B7B;
    white-space: nowrap;
}
.date-chip svg { width: 14px; height: 14px; }
/* clickable date chip for date picker */
.date-chip-label { cursor: pointer; }
.date-chip-input {
    position: absolute; opacity: 0; width: 0; height: 0;
}

/* ── Alerts ── */
.alert-ok {
    background: #E6F9F1; color: #1A7A4A;
    padding: 0.85rem 1.1rem; border-radius: 12px;
    margin-bottom: 1rem; font-size: 0.875rem;
    display: flex; align-items: center; gap: 8px;
}
.alert-err {
    background: #FBE9E9; color: #B91C1C;
    padding: 0.85rem 1.1rem; border-radius: 12px;
    margin-bottom: 1rem; font-size: 0.875rem;
    display: flex; align-items: center; gap: 8px;
}

/* ── Table card ── */
.jadwal-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    overflow: hidden;
}

/* ── Table ── */
.jadwal-table { width: 100%; border-collapse: collapse; }
.jadwal-table th {
    padding: 0.75rem 1.25rem; text-align: left;
    font-size: 0.68rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: 0.07em;
    color: #9CA3AF; border-bottom: 1px solid #F3F4F6;
}
.jadwal-table td {
    padding: 0.95rem 1.25rem;
    border-bottom: 1px solid #F9FAFB;
    font-size: 0.875rem; color: #374151;
    vertical-align: middle;
}
.jadwal-table tbody tr:last-child td { border-bottom: none; }
.jadwal-table tbody tr:hover { background: #FDFAF9; }

/* ID cell */
.id-cell { color: #C17B7B; font-weight: 600; font-size: 0.82rem; }

/* Pasien cell */
.pasien-cell { display: flex; align-items: center; gap: 10px; }
.j-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.78rem; font-weight: 700; color: #fff; flex-shrink: 0;
}
.av-0 { background: #C17B7B; } .av-1 { background: #6B9EC7; }
.av-2 { background: #6BBF8A; } .av-3 { background: #9B85C4; }
.av-4 { background: #E8A066; }
.j-nama   { font-size: 0.88rem; font-weight: 600; color: #1F2937; }
.j-dokter { font-size: 0.72rem; color: #9CA3AF; }

/* Jam cell */
.jam-cell { font-weight: 500; color: #1F2937; }

/* Layanan cell */
.layanan-cell { color: #6B7280; }

/* Status badges */
.jbadge {
    display: inline-block; padding: 0.28rem 0.7rem;
    border-radius: 20px; font-size: 0.7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.05em;
}
.jbadge.menunggu   { background: #FEF3C7; color: #92400E; }
.jbadge.hadir      { background: #DBEAFE; color: #1E40AF; }
.jbadge.selesai    { background: #D1FAE5; color: #065F46; }
.jbadge.batal      { background: #FEE2E2; color: #991B1B; }

/* Action icons */
.aksi-cell { display: flex; align-items: center; gap: 4px; }
.btn-ico {
    width: 30px; height: 30px; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    border: none; cursor: pointer; text-decoration: none;
    background: none; color: #9CA3AF;
    transition: background 0.15s, color 0.15s;
}
.btn-ico:hover        { background: #F3F4F6; color: #374151; }
.btn-ico.rose:hover   { background: #FBE9E9; color: #C17B7B; }
.btn-ico svg { width: 16px; height: 16px; }

/* Dropdown menu */
.aksi-wrap { position: relative; }
.dropdown-menu {
    display: none; position: absolute;
    right: 0; top: 36px; z-index: 50;
    background: #fff; border: 1px solid #E5E7EB;
    border-radius: 10px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    min-width: 160px; overflow: hidden;
}
.dropdown-menu.open { display: block; }
.dropdown-item {
    display: flex; align-items: center; gap: 8px;
    padding: 0.65rem 1rem; font-size: 0.82rem;
    color: #374151; cursor: pointer; border: none;
    background: none; width: 100%; text-align: left;
    transition: background 0.1s;
}
.dropdown-item:hover { background: #F9FAFB; }
.dropdown-item.danger { color: #DC2626; }
.dropdown-item.danger:hover { background: #FEF2F2; }
.dropdown-item svg { width: 14px; height: 14px; }

/* Empty */
.empty-jadwal {
    text-align: center; padding: 3.5rem 1rem; color: #9CA3AF;
}
.empty-jadwal-icon { font-size: 2.5rem; margin-bottom: 0.75rem; }

/* ── Footer ── */
.table-footer {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 1rem 1.25rem;
    border-top: 1px solid #F3F4F6;
    font-size: 0.78rem; color: #9CA3AF;
    flex-wrap: wrap; gap: 0.5rem;
}
.pagi { display: flex; align-items: center; gap: 4px; }
.pagi a, .pagi span {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; border-radius: 8px;
    font-size: 0.82rem; font-weight: 500; text-decoration: none;
    border: 1px solid #E5E7EB; color: #374151;
    transition: all 0.15s;
}
.pagi a:hover         { background: #FBE9E9; border-color: #C17B7B; color: #C17B7B; }
.pagi span.active     { background: #C17B7B; border-color: #C17B7B; color: #fff; }
.pagi span.disabled   { color: #D1D5DB; cursor: default; }
@endsection

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <a href="{{ route('resepsionis.dashboard') }}">Manajemen</a>
    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
    <span>Jadwal Konsultasi</span>
</div>

{{-- Page header --}}
<div class="ph-row">
    <div>
        <h1 class="page-title">Jadwal Konsultasi</h1>
        <p class="page-subtitle">Kelola antrean dan jadwal konsultasi harian pasien secara terpadu.</p>
    </div>
    <a href="{{ route('jadwal.create') }}" class="btn-tambah-jadwal">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Reservasi
    </a>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert-ok">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert-err">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        {{ session('error') }}
    </div>
@endif

{{-- Search + Filter bar --}}
<form method="GET" action="{{ route('jadwal.index') }}" id="jadwalForm">
<div class="search-filter-bar">
    <div class="search-wrap">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
        </svg>
        <input type="text" name="search" placeholder="Cari ID atau nama pasien..."
               value="{{ request('search') }}" id="searchJadwal">
    </div>

    <div class="bar-right">
        <button type="button" class="btn-filter">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            Filter
        </button>

        {{-- Date picker chip --}}
        <label class="date-chip date-chip-label" for="tanggalPicker">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMM YYYY') }}
            <input type="date" name="tanggal" id="tanggalPicker" class="date-chip-input"
                   value="{{ $tanggal }}" onchange="this.form.submit()">
        </label>
    </div>
</div>
</form>

{{-- Table --}}
<div class="jadwal-card">
    @php
        $filtered = collect($jadwal);
        $search   = request('search');
        if($search) {
            $filtered = $filtered->filter(fn($j) =>
                str_contains(strtolower($j->pasien->nama_pasien ?? ''), strtolower($search)) ||
                str_contains('p-' . str_pad($j->id, 5, '0', STR_PAD_LEFT), strtolower($search))
            );
        }
        $avColors = ['av-0','av-1','av-2','av-3','av-4'];
    @endphp

    @if($filtered->isEmpty())
        <div class="empty-jadwal">
            <div class="empty-jadwal-icon">📅</div>
            <p>Tidak ada jadwal{{ $search ? ' yang cocok.' : ' untuk tanggal ini.' }}</p>
        </div>
    @else
        <table class="jadwal-table">
            <thead>
                <tr>
                    <th>ID Pasien</th>
                    <th>Nama Pasien</th>
                    <th>Jam Reservasi</th>
                    <th>Layanan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($filtered as $i => $j)
                    @php
                        $nama     = $j->pasien->nama_pasien ?? '-';
                        $initials = collect(explode(' ', $nama))->take(2)->map(fn($w) => strtoupper($w[0]))->join('');
                        $avClass  = $avColors[$i % 5];
                        $jamMulai = \Carbon\Carbon::parse($j->jam_jadwal)->format('H:i');
                        $jamSelesai = \Carbon\Carbon::parse($j->jam_jadwal)->addMinutes(30)->format('H:i');
                        $layanan  = $j->keterangan ?: 'Konsultasi Awal';
                        $status   = $j->status_jadwal;
                        $statusLabel = match($status) {
                            'hadir'   => 'Hadir',
                            'selesai' => 'Selesai',
                            'batal'   => 'Dibatalkan',
                            default   => 'Menunggu',
                        };
                    @endphp
                    <tr>
                        <td class="id-cell">P-{{ str_pad($j->pasien_id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="pasien-cell">
                                <div class="j-avatar {{ $avClass }}">{{ $initials }}</div>
                                <div>
                                    <div class="j-nama">{{ $nama }}</div>
                                    <div class="j-dokter">{{ $j->dokter->nama ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="jam-cell">{{ $jamMulai }} - {{ $jamSelesai }}</td>
                        <td class="layanan-cell">{{ $layanan }}</td>
                        <td><span class="jbadge {{ $status === 'hadir' ? 'hadir' : ($status === 'batal' ? 'batal' : $status) }}">{{ $statusLabel }}</span></td>
                        <td>
                            <div class="aksi-cell">
                                {{-- Reschedule icon --}}
                                <button type="button" class="btn-ico rose" title="Reschedule">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </button>

                                {{-- 3-dot dropdown --}}
                                <div class="aksi-wrap">
                                    <button type="button" class="btn-ico" onclick="toggleDropdown(this)" title="Lainnya">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="5" cy="12" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="19" cy="12" r="2"/>
                                        </svg>
                                    </button>
                                    <div class="dropdown-menu">
                                        @if($status === 'menunggu')
                                            <form method="POST" action="{{ route('jadwal.checkin', $j->id) }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Check-in Pasien
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('jadwal.batal', $j->id) }}" onsubmit="return confirm('Yakin batalkan jadwal ini?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    Batalkan
                                                </button>
                                            </form>
                                        @elseif($status === 'hadir')
                                            <form method="POST" action="{{ route('jadwal.selesai', $j->id) }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                    Selesaikan
                                                </button>
                                            </form>
                                        @else
                                            <span class="dropdown-item" style="color:#9CA3AF; cursor:default;">Tidak ada aksi</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Footer --}}
        <div class="table-footer">
            <span>Menampilkan {{ $filtered->count() }} dari {{ $jadwal->count() }} jadwal hari ini</span>
            <div class="pagi">
                <span class="disabled">‹</span>
                <span class="active">1</span>
                <span class="disabled">›</span>
            </div>
        </div>
    @endif
</div>

<script>
// Live search client-side
document.getElementById('searchJadwal').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.jadwal-table tbody tr').forEach(row => {
        const id   = row.querySelector('.id-cell')?.textContent.toLowerCase() ?? '';
        const nama = row.querySelector('.j-nama')?.textContent.toLowerCase() ?? '';
        row.style.display = (id.includes(q) || nama.includes(q)) ? '' : 'none';
    });
});

// Dropdown toggle
function toggleDropdown(btn) {
    const menu = btn.nextElementSibling;
    const isOpen = menu.classList.contains('open');
    // tutup semua dropdown dulu
    document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
    if (!isOpen) menu.classList.add('open');
}
// Klik luar = tutup dropdown
document.addEventListener('click', function(e) {
    if (!e.target.closest('.aksi-wrap')) {
        document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
    }
});
</script>

@endsection
