@extends('layouts.app')

@section('title', 'Dashboard Pemilik - Eclair Beauty Clinic')

@section('extra_style')
.stat-grid-4 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}
.stat-card {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 22px 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    display: flex;
    align-items: center;
    gap: 16px;
}
.stat-icon {
    width: 50px; height: 50px;
    border-radius: 14px;
    background: #FBF1EC;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.stat-icon.blue  { background: #EAF4FB; }
.stat-icon.green { background: #EAF6EE; }
.stat-icon.amber { background: #FDF5E0; }
.stat-label {
    font-size: 11px; color: #9B9B9B;
    text-transform: uppercase; letter-spacing: 0.5px;
    margin-bottom: 4px;
}
.stat-value {
    font-size: 26px; font-weight: 700; color: #3A3A3A;
}

.two-col { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-top: 24px; }

.chart-card {
    background: white; border-radius: 16px;
    padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
.chart-header {
    display: flex; justify-content: space-between; align-items: flex-start;
    margin-bottom: 20px;
}
.chart-title { font-size: 15px; font-weight: 700; color: #3A3A3A; }
.chart-subtitle { font-size: 12px; color: #9B9B9B; margin-top: 2px; }

.table-card {
    background: white; border-radius: 16px;
    padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    margin-top: 24px;
}
.table-card-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 16px;
}
.table-card-title { font-size: 15px; font-weight: 700; color: #3A3A3A; }
.link-lihat {
    font-size: 12px; color: #C17B7B; text-decoration: none; font-weight: 600;
}
.link-lihat:hover { text-decoration: underline; }

.pemilik-table thead th {
    font-size: 11px; color: #9B9B9B; text-transform: uppercase;
    letter-spacing: 0.5px; padding: 10px 16px;
    border-bottom: 1px solid #F0E8E2; font-weight: 600;
}
.pemilik-table tbody td {
    padding: 13px 16px; font-size: 13.5px; color: #3A3A3A;
    border-bottom: 1px solid #F8F2EE; vertical-align: middle;
}
.pemilik-table tbody tr:last-child td { border-bottom: none; }
.pemilik-table tbody tr:hover { background: #FDFAF8; }

.pasien-anon {
    display: flex; align-items: center; gap: 10px;
}
.anon-avatar {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: #FBE4DD;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700; color: #C17B7B;
    flex-shrink: 0;
}
.anon-id { font-size: 12px; color: #9B9B9B; }

.two-tables { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 24px; }

.periode-chip {
    background: #FAF6F2;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px; color: #6B6B6B;
    display: flex; align-items: center; gap: 6px;
}
@endsection

@section('content')

{{-- Header --}}
<div class="page-header-row">
    <div>
        <h1 class="page-title">Dashboard Statistik Pemilik</h1>
        <p class="page-subtitle">Pemantauan performa operasional klinik secara menyeluruh</p>
    </div>
    <div class="periode-chip">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM YYYY') }}
    </div>
</div>

{{-- Stat Cards --}}
<div class="stat-grid-4">
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="22" height="22" fill="none" stroke="#C17B7B" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
            </svg>
        </div>
        <div>
            <div class="stat-label">Total Pasien Terdaftar</div>
            <div class="stat-value">{{ number_format($totalPasien) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="22" height="22" fill="none" stroke="#2C7A9B" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
        </div>
        <div>
            <div class="stat-label">Janji Temu Hari Ini</div>
            <div class="stat-value">{{ number_format($jadwalHariIni) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="22" height="22" fill="none" stroke="#B8860B" stroke-width="2" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
        </div>
        <div>
            <div class="stat-label">Janji Temu Bulan Ini</div>
            <div class="stat-value">{{ number_format($kunjunganBulanIni) }}</div>
        </div>
    </div>
</div>

{{-- Chart + Recent --}}
<div style="display:grid; grid-template-columns:2fr 1fr; gap:20px;">

    {{-- Tren Chart --}}
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">Tren Janji Temu</div>
                <div class="chart-subtitle">Statistik kunjungan operasional harian</div>
            </div>
            <span class="periode-chip">7 Hari Terakhir ↑</span>
        </div>
        <canvas id="grafikKunjungan" height="120"></canvas>
    </div>

    {{-- Layanan Terpopuler placeholder --}}
    <div class="chart-card" style="display:flex; flex-direction:column;">
        <div>
            <div class="chart-title">Total Kunjungan</div>
        </div>
        <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px;">
            <div style="font-size:48px; font-weight:800; color:#C17B7B; line-height:1;">
                {{ number_format($totalKunjungan) }}
            </div>
            <div style="font-size:12px; color:#9B9B9B; text-transform:uppercase; letter-spacing:0.5px;">Total Kunjungan</div>
            <a href="{{ route('pemilik.laporan') }}" style="margin-top:12px; display:inline-flex; align-items:center; gap:6px; padding:10px 20px; background:#C17B7B; color:white; border-radius:10px; text-decoration:none; font-size:13px; font-weight:600;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                Lihat Laporan
            </a>
        </div>
    </div>
</div>

{{-- Two tables --}}
<div class="two-tables">

    {{-- Jadwal Terbaru --}}
    <div class="table-card" style="margin-top:0;">
        <div class="table-card-header">
            <div class="table-card-title">Daftar Janji Temu Terbaru</div>
            <span style="font-size:12px; color:#9B9B9B;">Riwayat operasional 24 jam terakhir</span>
        </div>
        <table class="pemilik-table" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th>Pasien</th>
                    <th>Tanggal</th>
                    <th>Status Operasional</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwalTerbaru as $index => $j)
                <tr>
                    <td>
                        <div class="pasien-anon">
                            <div class="anon-avatar">P{{ $index+1 }}</div>
                            <div>
                                <div style="font-weight:600; font-size:13px;">Pasien {{ $index+1 }}</div>
                                <div class="anon-id">#EC{{ str_pad($j->pasien_id, 5, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:12px; color:#6B6B6B;">
                        {{ \Carbon\Carbon::parse($j->tanggal_jadwal)->locale('id')->isoFormat('D MMM YYYY') }}<br>
                        <span style="color:#9B9B9B;">{{ \Carbon\Carbon::parse($j->jam_jadwal)->format('H:i') }} WIB</span>
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
                    <td colspan="3" style="text-align:center; padding:40px; color:#9B9B9B; font-size:13px;">
                        <div style="font-size:28px; margin-bottom:8px;">📅</div>
                        Belum ada jadwal terbaru
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pasien Terbaru --}}
    <div class="table-card" style="margin-top:0;">
        <div class="table-card-header">
            <div class="table-card-title">Pasien Terbaru</div>
            <a href="{{ route('pemilik.laporan') }}" class="link-lihat">Lihat Semua Laporan →</a>
        </div>
        <table class="pemilik-table" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>No HP</th>
                    <th>Terdaftar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasienTerbaru as $index => $p)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div class="anon-avatar">P{{ $index+1 }}</div>
                            <span style="font-size:12px; color:#9B9B9B;">#EC{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </td>
                    <td style="font-size:13px; color:#6B6B6B;">
                        {{ $p->no_hp ?? '—' }}
                    </td>
                    <td style="font-size:12px; color:#9B9B9B;">
                        {{ \Carbon\Carbon::parse($p->created_at)->locale('id')->isoFormat('D MMM YYYY') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align:center; padding:40px; color:#9B9B9B; font-size:13px;">
                        Belum ada data pasien
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labelBulan = @json($labelBulan);
    const kunjunganPerBulan = @json($kunjunganPerBulan);

    const ctx = document.getElementById('grafikKunjungan').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labelBulan,
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: kunjunganPerBulan,
                borderColor: '#C17B7B',
                backgroundColor: 'rgba(193, 123, 123, 0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#C17B7B',
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#3A3A3A',
                    padding: 10,
                    cornerRadius: 8,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#F5EEE8' },
                    ticks: { color: '#9B9B9B', font: { size: 11 }, stepSize: 1 }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#9B9B9B', font: { size: 11 } }
                }
            }
        }
    });
</script>
@endsection
