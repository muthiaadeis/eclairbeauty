@extends('layouts.app')

@section('title', 'Laporan Operasional - Eclair Beauty Clinic')

@section('extra_style')
.filter-card {
    background: white; border-radius: 16px;
    padding: 20px 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    margin-bottom: 20px;
}
.filter-row {
    display: flex; align-items: flex-end; gap: 16px; flex-wrap: wrap;
}
.filter-group { display: flex; flex-direction: column; gap: 6px; }
.filter-group label {
    font-size: 11px; color: #9B9B9B;
    text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;
}
.filter-input {
    padding: 9px 14px;
    border: 1px solid #F0E8E2;
    border-radius: 10px;
    font-size: 13px; color: #3A3A3A;
    background: #FAF6F2;
    outline: none;
    transition: border 0.2s;
    min-width: 140px;
}
.filter-input:focus { border-color: #C17B7B; background: white; }
.btn-cetak {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px;
    background: white; color: #C17B7B;
    border: 1.5px solid #C17B7B;
    border-radius: 10px; cursor: pointer;
    font-size: 13px; font-weight: 600;
    text-decoration: none;
    transition: all 0.2s; white-space: nowrap;
}
.btn-cetak:hover { background: #C17B7B; color: white; }

.laporan-grid { display: grid; grid-template-columns: 1fr 280px; gap: 20px; }

.laporan-table-card {
    background: white; border-radius: 16px;
    padding: 0; box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    overflow: hidden;
}
.laporan-table thead th {
    font-size: 11px; color: #9B9B9B; text-transform: uppercase;
    letter-spacing: 0.5px; padding: 12px 20px;
    border-bottom: 1px solid #F0E8E2; font-weight: 600;
}
.laporan-table tbody td {
    padding: 16px 20px; font-size: 13px; color: #3A3A3A;
    border-bottom: 1px solid #F8F2EE; vertical-align: middle;
}
.laporan-table tbody tr:last-child td { border-bottom: none; }
.laporan-table tbody tr:hover { background: #FDFAF8; }

.pasien-anon {
    display: flex; align-items: center; gap: 10px;
}
.anon-dot {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700; flex-shrink: 0;
}
.anon-dot.rose  { background: #FBE4DD; color: #C17B7B; }
.anon-dot.blue  { background: #DCEEF5; color: #2C7A9B; }
.anon-dot.green { background: #DCF0E0; color: #2E8B4F; }

.layanan-chip {
    display: inline-block;
    padding: 4px 12px;
    background: #FAF6F2;
    color: #6B6B6B;
    border-radius: 20px;
    font-size: 12px;
}
.layanan-kategori {
    font-size: 11px; color: #9B9B9B; margin-top: 2px;
    text-transform: uppercase; letter-spacing: 0.3px;
}

.table-footer-pagination {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px; border-top: 1px solid #F0E8E2;
    font-size: 12px; color: #9B9B9B;
}
.pag-btns { display: flex; gap: 6px; align-items: center; }
.pag-btn {
    min-width: 30px; height: 30px; padding: 0 8px;
    border-radius: 8px; border: 1px solid #F0E8E2;
    background: white; color: #6B6B6B;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 12px; text-decoration: none; transition: all 0.2s;
}
.pag-btn:hover { background: #FBF1EC; border-color: #C17B7B; color: #C17B7B; }
.pag-btn.active { background: #C17B7B; color: white; border-color: #C17B7B; }

/* Side panel */
.side-panel { display: flex; flex-direction: column; gap: 16px; }
.side-card {
    background: white; border-radius: 16px;
    padding: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
.side-card-title {
    font-size: 13px; font-weight: 700; color: #3A3A3A;
    margin-bottom: 14px;
    display: flex; align-items: center; gap: 6px;
}
.layanan-bar-row { margin-bottom: 12px; }
.layanan-bar-label {
    display: flex; justify-content: space-between;
    font-size: 12px; color: #3A3A3A; margin-bottom: 5px; font-weight: 500;
}
.layanan-bar-track {
    height: 6px; background: #F5EEE8; border-radius: 999px; overflow: hidden;
}
.layanan-bar-fill {
    height: 100%; border-radius: 999px;
    background: #C17B7B; transition: width 0.4s;
}
.layanan-bar-fill.blue  { background: #2C7A9B; }
.layanan-bar-fill.green { background: #2E8B4F; }
.layanan-count { font-size: 11px; color: #9B9B9B; }

.stat-quick {
    text-align: center; padding: 16px 0;
    border-top: 1px solid #F5EEE8; margin-top: 8px;
}
.stat-quick-val {
    font-size: 32px; font-weight: 800; color: #3A3A3A; line-height: 1;
}
.stat-quick-label { font-size: 11px; color: #9B9B9B; text-transform: uppercase; margin-top: 4px; }
@endsection

@section('content')

<div class="page-header-row">
    <div>
        <h1 class="page-title">Detail Laporan Operasional</h1>
        <p class="page-subtitle">Analisis aktivitas operasional klinik berdasarkan periode</p>
    </div>
    <button class="btn-cetak" onclick="window.print()">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/>
            <rect x="6" y="14" width="12" height="8"/>
        </svg>
        Cetak Laporan
    </button>
</div>

{{-- Filter --}}
<div class="filter-card">
    <form method="GET" action="{{ route('pemilik.laporan') }}" class="filter-row" id="filterForm">
        <div class="filter-group">
            <label>Tanggal Mulai</label>
            <input type="date" name="dari" class="filter-input" value="{{ $dari ?? '' }}" onchange="this.form.submit()">
        </div>
        <div class="filter-group">
            <label>Tanggal Selesai</label>
            <input type="date" name="sampai" class="filter-input" value="{{ $sampai ?? '' }}" onchange="this.form.submit()">
        </div>
        <div class="filter-group">
            <label>Status</label>
            <select name="status" class="filter-input" style="cursor:pointer;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ ($status??'')==='menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="hadir"    {{ ($status??'')==='hadir'    ? 'selected' : '' }}>Dalam Perawatan</option>
                <option value="selesai"  {{ ($status??'')==='selesai'  ? 'selected' : '' }}>Selesai</option>
                <option value="batal"    {{ ($status??'')==='batal'    ? 'selected' : '' }}>Batal</option>
            </select>
        </div>
    </form>
</div>

<div class="laporan-grid">

    {{-- Tabel Utama --}}
    <div>
        <div class="laporan-table-card">
            <table class="laporan-table" style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="width:100px;">ID Pasien</th>
                        <th>Waktu Kunjungan</th>
                        <th>Keterangan</th>
                        <th style="width:120px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php $colors = ['rose','blue','green']; @endphp
                    @forelse($jadwal as $index => $j)
                    <tr>
                        <td>
                            <div class="pasien-anon">
                                <div class="anon-dot {{ $colors[$index % 3] }}">
                                    P{{ $jadwal->firstItem() + $index }}
                                </div>
                                <div>
                                    <div style="font-size:12px; font-weight:600;">
                                        P-{{ date('Y') }}{{ str_pad($j->pasien_id, 2, '0', STR_PAD_LEFT) }}-****{{ str_pad($jadwal->firstItem()+$index, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-size:13px; font-weight:500;">
                                {{ \Carbon\Carbon::parse($j->tanggal_jadwal)->locale('id')->isoFormat('D MMM YYYY') }}
                            </div>
                            <div style="font-size:12px; color:#9B9B9B;">
                                {{ \Carbon\Carbon::parse($j->jam_jadwal)->format('H:i') }} WIB
                            </div>
                        </td>
                        <td>
                            @if($j->keterangan)
                                <span class="layanan-chip">{{ $j->keterangan }}</span>
                            @else
                                <span style="color:#C5B8B0; font-size:13px;">—</span>
                            @endif
                        </td>
                        <td>
                            @if($j->status_jadwal == 'selesai')
                                <span class="badge badge-selesai">Selesai</span>
                            @elseif($j->status_jadwal == 'hadir')
                                <span class="badge badge-hadir">Dalam Perawatan</span>
                            @elseif($j->status_jadwal == 'batal')
                                <span class="badge badge-batal">Batal</span>
                            @else
                                <span class="badge badge-menunggu">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:60px; color:#9B9B9B; font-size:14px;">
                            <div style="font-size:32px; margin-bottom:12px;">📋</div>
                            Tidak ada data untuk periode ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="table-footer-pagination">
                <span>Menampilkan {{ $jadwal->firstItem() ?? 0 }}–{{ $jadwal->lastItem() ?? 0 }} dari {{ $jadwal->total() }} laporan</span>
                <div class="pag-btns">
                    @if($jadwal->onFirstPage())
                        <span class="pag-btn" style="opacity:0.4;">‹</span>
                    @else
                        <a href="{{ $jadwal->previousPageUrl() }}" class="pag-btn">‹</a>
                    @endif

                    @for($i = max(1, $jadwal->currentPage()-1); $i <= min($jadwal->lastPage(), $jadwal->currentPage()+2); $i++)
                        <a href="{{ $jadwal->url($i) }}" class="pag-btn {{ $i == $jadwal->currentPage() ? 'active' : '' }}">{{ $i }}</a>
                    @endfor

                    @if($jadwal->hasMorePages())
                        <a href="{{ $jadwal->nextPageUrl() }}" class="pag-btn">›</a>
                    @else
                        <span class="pag-btn" style="opacity:0.4;">›</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Side Panel --}}
    <div class="side-panel">

        {{-- Status Summary --}}
        <div class="side-card">
            <div class="side-card-title">
                <svg width="14" height="14" fill="none" stroke="#C17B7B" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
                Statistik Status
            </div>
            @php
                $selesai  = $jadwal->getCollection()->where('status_jadwal','selesai')->count();
                $hadir    = $jadwal->getCollection()->where('status_jadwal','hadir')->count();
                $menunggu = $jadwal->getCollection()->where('status_jadwal','menunggu')->count();
                $batal    = $jadwal->getCollection()->where('status_jadwal','batal')->count();
                $total    = $jadwal->getCollection()->count() ?: 1;
            @endphp
            <div class="layanan-bar-row">
                <div class="layanan-bar-label">
                    <span>Selesai</span>
                    <span class="layanan-count">{{ $selesai }}</span>
                </div>
                <div class="layanan-bar-track">
                    <div class="layanan-bar-fill green" style="width:{{ round($selesai/$total*100) }}%"></div>
                </div>
            </div>
            <div class="layanan-bar-row">
                <div class="layanan-bar-label">
                    <span>Dalam Perawatan</span>
                    <span class="layanan-count">{{ $hadir }}</span>
                </div>
                <div class="layanan-bar-track">
                    <div class="layanan-bar-fill blue" style="width:{{ round($hadir/$total*100) }}%"></div>
                </div>
            </div>
            <div class="layanan-bar-row">
                <div class="layanan-bar-label">
                    <span>Menunggu</span>
                    <span class="layanan-count">{{ $menunggu }}</span>
                </div>
                <div class="layanan-bar-track">
                    <div class="layanan-bar-fill" style="width:{{ round($menunggu/$total*100) }}%"></div>
                </div>
            </div>
            @if($batal > 0)
            <div class="layanan-bar-row">
                <div class="layanan-bar-label">
                    <span>Batal</span>
                    <span class="layanan-count">{{ $batal }}</span>
                </div>
                <div class="layanan-bar-track">
                    <div class="layanan-bar-fill" style="width:{{ round($batal/$total*100) }}%; background:#C0392B;"></div>
                </div>
            </div>
            @endif
        </div>

        {{-- Total Quick Stat --}}
        <div class="side-card">
            <div class="side-card-title">
                <svg width="14" height="14" fill="none" stroke="#C17B7B" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Statistik Cepat (Ini)
            </div>
            <div class="stat-quick">
                <div class="stat-quick-val">{{ $jadwal->total() }}</div>
                <div class="stat-quick-label">Total Laporan</div>
            </div>
        </div>

    </div>
</div>

@endsection
