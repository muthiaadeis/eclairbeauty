@extends('layouts.app')

@section('title', 'Rekam Medis - Eclair Beauty Clinic')

@section('extra_style')
.search-row {
    display: flex;
    gap: 10px;
    margin-bottom: 24px;
    align-items: center;
}
.search-input {
    flex: 1;
    padding: 10px 16px;
    border: 1px solid #F0E8E2;
    border-radius: 10px;
    font-size: 14px;
    background: #FAF6F2;
    outline: none;
    color: #3A3A3A;
    transition: border 0.2s;
}
.search-input:focus {
    border-color: #C17B7B;
    background: #fff;
}
.btn-search {
    padding: 10px 20px;
    background: #C17B7B;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: background 0.2s;
}
.btn-search:hover { background: #B06B6B; }

.btn-reset {
    padding: 10px 16px;
    background: #FAF6F2;
    color: #6B6B6B;
    border: 1px solid #E8DDD5;
    border-radius: 10px;
    text-decoration: none;
    font-size: 14px;
}

.pasien-table thead th {
    font-size: 11px;
    color: #9B9B9B;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 20px;
    border-bottom: 1px solid #F0E8E2;
    font-weight: 600;
}
.pasien-table tbody td {
    padding: 16px 20px;
    font-size: 14px;
    color: #3A3A3A;
    border-bottom: 1px solid #F8F2EE;
    vertical-align: middle;
}
.pasien-table tbody tr:last-child td { border-bottom: none; }
.pasien-table tbody tr:hover { background: #FDFAF8; }

.pasien-name {
    font-weight: 600;
    font-size: 14px;
    color: #3A3A3A;
}
.pasien-id {
    font-size: 12px;
    color: #9B9B9B;
    margin-top: 2px;
}

.btn-lihat {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: #FBE4DD;
    color: #C17B7B;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
}
.btn-lihat:hover {
    background: #C17B7B;
    color: white;
}

.table-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-top: 1px solid #F0E8E2;
    font-size: 12px;
    color: #9B9B9B;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.pagination-btns { display: flex; gap: 8px; }
.pag-btn {
    width: 30px; height: 30px;
    border-radius: 8px;
    border: 1px solid #F0E8E2;
    background: white;
    color: #6B6B6B;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 13px;
    text-decoration: none;
    transition: all 0.2s;
}
.pag-btn:hover { background: #FBF1EC; border-color: #C17B7B; color: #C17B7B; }

.gender-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 13px;
    color: #6B6B6B;
}
@endsection

@section('content')

<div class="breadcrumb" style="display:flex; align-items:center; gap:6px; font-size:11px; color:#9B9B9B; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">
    <a href="{{ route('dokter.dashboard') }}" style="color:#9B9B9B; text-decoration:none;">Manajemen</a>
    <span style="color:#D0C8C0;">›</span>
    <span style="color:#C17B7B; font-weight:600;">Rekam Medis</span>
</div>

<div class="page-header-row">
    <div>
        <h1 class="page-title">Data Pasien</h1>
        <p class="page-subtitle">Cari pasien untuk melihat riwayat rekam medis</p>
    </div>
</div>

{{-- Search --}}
<form method="GET" action="{{ route('rekam_medis.index') }}" class="search-row">
    <input
        type="text"
        name="search"
        class="search-input"
        placeholder="Cari nama atau nomor HP pasien..."
        value="{{ $search ?? '' }}"
    >
    <button type="submit" class="btn-search">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="display:inline; vertical-align:middle; margin-right:4px;">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        Cari
    </button>
    @if(!empty($search))
        <a href="{{ route('rekam_medis.index') }}" class="btn-reset">Reset</a>
    @endif
</form>

{{-- Table --}}
<div class="card" style="padding:0; overflow:hidden;">
    <table class="pasien-table" style="width:100%; border-collapse:collapse;">
        <thead>
            <tr>
                <th style="width:60px;">No</th>
                <th>Nama Pasien</th>
                <th>No HP</th>
                <th>Jenis Kelamin</th>
                <th style="text-align:right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pasien as $index => $p)
            <tr>
                <td style="color:#9B9B9B; font-size:13px;">
                    {{ $pasien->firstItem() + $index }}
                </td>
                <td>
                    <div class="pasien-name">{{ $p->nama_pasien }}</div>
                    <div class="pasien-id">#EC{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</div>
                </td>
                <td>
                    @if($p->no_hp && $p->no_hp !== 'belum diisi')
                        {{ $p->no_hp }}
                    @else
                        <span style="color:#C5B8B0;">—</span>
                    @endif
                </td>
                <td>
                    @if($p->jenis_kelamin == 'P')
                        <span class="gender-badge">♀ Perempuan</span>
                    @elseif($p->jenis_kelamin == 'L')
                        <span class="gender-badge">♂ Laki-laki</span>
                    @else
                        <span style="color:#C5B8B0;">—</span>
                    @endif
                </td>
                <td style="text-align:right;">
                    <a href="{{ route('rekam_medis.show', $p->id) }}" class="btn-lihat">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        Lihat Rekam Medis
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; padding:60px; color:#9B9B9B; font-size:14px;">
                    <div style="font-size:32px; margin-bottom:12px;">🔍</div>
                    @if(!empty($search))
                        Tidak ada pasien dengan nama atau nomor HP "<strong>{{ $search }}</strong>"
                    @else
                        Belum ada data pasien
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="table-footer">
        <span>
            Menampilkan {{ $pasien->firstItem() ?? 0 }}–{{ $pasien->lastItem() ?? 0 }} dari {{ $pasien->total() }} pasien
        </span>
        <div class="pagination-btns">
            @if($pasien->onFirstPage())
                <span class="pag-btn" style="opacity:0.4; cursor:default;">‹</span>
            @else
                <a href="{{ $pasien->previousPageUrl() }}" class="pag-btn">‹</a>
            @endif
            @if($pasien->hasMorePages())
                <a href="{{ $pasien->nextPageUrl() }}" class="pag-btn">›</a>
            @else
                <span class="pag-btn" style="opacity:0.4; cursor:default;">›</span>
            @endif
        </div>
    </div>
</div>

@endsection
