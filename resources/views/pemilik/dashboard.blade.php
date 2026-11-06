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

.dash-two-col { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-top: 24px; }

.chart-card {
    background: white; border-radius: 16px;
    padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    min-width: 0;
}
.chart-header {
    display: flex; justify-content: space-between; align-items: flex-start;
    flex-wrap: wrap; gap: 8px;
    margin-bottom: 20px;
}
.chart-title { font-size: 15px; font-weight: 700; color: #3A3A3A; }
.chart-subtitle { font-size: 12px; color: #9B9B9B; margin-top: 2px; }

.table-card {
    background: white; border-radius: 16px;
    padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    margin-top: 24px;
    min-width: 0;
}
.table-card-header {
    display: flex; justify-content: space-between; align-items: center;
    flex-wrap: wrap; gap: 8px;
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
    white-space: nowrap;
}

/* ── Custom Chart Selector Dropdown ── */
.custom-chart-select-wrap {
    position: relative;
    display: inline-block;
}
.custom-chart-select-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 16px;
    border-radius: 20px;
    border: 1px solid #F0E8E2;
    background: #FAF6F2;
    color: #4B5563;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.2s;
    outline: none;
}
.custom-chart-select-btn:hover {
    border-color: #C17B7B;
    background: #FFF;
}
.custom-chart-select-btn.open {
    border-color: #C17B7B;
    box-shadow: 0 0 0 3px rgba(193,123,123,0.12);
    background: #FFF;
}
.custom-chart-select-btn svg {
    width: 13px;
    height: 13px;
    color: #9CA3AF;
    transition: transform 0.2s;
}
.custom-chart-select-btn.open svg {
    transform: rotate(180deg);
    color: #C17B7B;
}

.custom-chart-menu {
    display: none;
    position: absolute;
    top: calc(100% + 6px);
    right: 0;
    min-width: 195px;
    background: #FFF;
    border: 1px solid #F0E8E2;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    padding: 6px;
    z-index: 50;
    overflow: hidden;
}
.custom-chart-menu.show {
    display: block;
    animation: fadeInDown 0.15s ease-out;
}
@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}

.custom-chart-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 9px 12px;
    border: none;
    background: none;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 500;
    color: #4B5563;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.15s;
    text-align: left;
}
.custom-chart-item:hover {
    background: #FAF6F2;
    color: #C17B7B;
}
.custom-chart-item.selected {
    background: #FAF6F2;
    color: #C17B7B;
    font-weight: 600;
}
.custom-chart-item .item-check {
    display: none;
    color: #C17B7B;
}
.custom-chart-item.selected .item-check {
    display: block;
}

/* Tabel bisa di-scroll horizontal di layar sempit */
.table-card-scroll { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
.table-card-scroll table { min-width: 460px; }

/* ===== RESPONSIVE ===== */
@media (max-width: 1200px) {
    .stat-grid-4 { grid-template-columns: repeat(2, 1fr); }
    .dash-two-col { grid-template-columns: 1fr; }
}
@media (max-width: 860px) {
    .two-tables { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
    .stat-grid-4 { grid-template-columns: 1fr; }
    .page-header-row { flex-direction: column; align-items: stretch; }
    .stat-card { padding: 16px; }
    .chart-card, .table-card { padding: 16px; }
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

    {{-- Chart Gabungan --}}
    <div class="chart-card" style="margin-top: 24px;">
        <div class="chart-header">
            <div>
                <div class="chart-title">Statistik Klinik</div>
                <div class="chart-subtitle" id="chartSubtitle">Statistik kunjungan operasional harian</div>
            </div>
            <div>
                <div class="custom-chart-select-wrap">
                    <button type="button" class="custom-chart-select-btn" id="btnChartDropdown" onclick="toggleChartDropdown()">
                        <span id="labelCurrentChart">Tren Janji Temu</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="custom-chart-menu" id="menuChartDropdown">
                        <button type="button" class="custom-chart-item selected" data-value="kunjungan" onclick="switchChartMode('kunjungan', 'Tren Janji Temu')">
                            <span>Tren Janji Temu</span>
                            <svg class="item-check" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        </button>
                        <button type="button" class="custom-chart-item" data-value="treatment" onclick="switchChartMode('treatment', 'Treatment Terbanyak')">
                            <span>Treatment Terbanyak</span>
                            <svg class="item-check" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Container Tren Kunjungan --}}
        <div id="containerKunjungan">
            <canvas id="grafikKunjungan" height="100"></canvas>
        </div>

        {{-- Container Treatment --}}
        <div id="containerTreatment" style="display: none; width: 100%;">
            @if($treatmentTerbanyak->isEmpty())
                <div style="padding: 56px 20px; text-align: center;">
                    <div style="width: 58px; height: 58px; border-radius: 50%; background: #FAF6F2; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px;">
                        <svg width="26" height="26" fill="none" stroke="#C17B7B" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <div style="font-size: 14.5px; font-weight: 600; color: #3A3A3A; margin-bottom: 6px;">Belum Ada Data Treatment</div>
                    <div style="font-size: 12.5px; color: #9CA3AF; max-width: 440px; margin: 0 auto; line-height: 1.5;">
                        Grafik treatment terbanyak akan otomatis muncul begitu dokter mencatat rekam medis tindakan perawatan pasien di sistem.
                    </div>
                </div>
            @else
                <div style="position: relative; height: 320px; width: 100%; display: flex; align-items: center; justify-content: center;">
                    <canvas id="grafikTreatment"></canvas>
                </div>
            @endif
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
        <div class="table-card-scroll">
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
    </div>

    {{-- Pasien Terbaru --}}
    <div class="table-card" style="margin-top:0;">
        <div class="table-card-header">
            <div class="table-card-title">Pasien Terbaru</div>
            <a href="{{ route('pemilik.laporan') }}" class="link-lihat">Lihat Semua Laporan →</a>
        </div>
        <div class="table-card-scroll">
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

<script>
    let chartTreatmentInstance = null;

    function initTreatmentChart() {
        const canvas = document.getElementById('grafikTreatment');
        if (!canvas || chartTreatmentInstance) return;

        const labelTreatment = @json($treatmentTerbanyak->pluck('jenis_tindakan'));
        const dataTreatment  = @json($treatmentTerbanyak->pluck('total'));

        if (!dataTreatment.length) return;

        chartTreatmentInstance = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: labelTreatment,
                datasets: [{
                    data: dataTreatment,
                    backgroundColor: [
                        '#C17B7B', // Rose
                        '#2C7A9B', // Blue
                        '#B8860B', // Amber
                        '#5B8B6C', // Green
                        '#9B7293'  // Plum
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: { size: 11, family: "'Inter', sans-serif" },
                            color: '#6B6B6B'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#3A3A3A',
                        padding: 10,
                        cornerRadius: 8,
                    }
                },
                cutout: '65%'
            }
        });
    }

    function toggleChartDropdown() {
        const btn = document.getElementById('btnChartDropdown');
        const menu = document.getElementById('menuChartDropdown');
        const isOpen = menu.classList.contains('show');
        if (isOpen) {
            menu.classList.remove('show');
            btn.classList.remove('open');
        } else {
            menu.classList.add('show');
            btn.classList.add('open');
        }
    }

    function switchChartMode(mode, label) {
        document.getElementById('labelCurrentChart').innerText = label;
        toggleChartDropdown();

        document.querySelectorAll('#menuChartDropdown .custom-chart-item').forEach(item => {
            item.classList.remove('selected');
        });
        const activeItem = document.querySelector(`#menuChartDropdown .custom-chart-item[data-value="${mode}"]`);
        if (activeItem) activeItem.classList.add('selected');

        const containerKunjungan = document.getElementById('containerKunjungan');
        const containerTreatment = document.getElementById('containerTreatment');
        const chartSubtitle = document.getElementById('chartSubtitle');

        if (mode === 'kunjungan') {
            containerKunjungan.style.display = 'block';
            containerTreatment.style.display = 'none';
            chartSubtitle.textContent = 'Statistik kunjungan operasional harian';
        } else {
            containerKunjungan.style.display = 'none';
            containerTreatment.style.display = 'block';
            chartSubtitle.textContent = 'Tindakan paling sering dilakukan';
            initTreatmentChart();
            if (chartTreatmentInstance) {
                chartTreatmentInstance.resize();
            }
        }
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-chart-select-wrap')) {
            const btn = document.getElementById('btnChartDropdown');
            const menu = document.getElementById('menuChartDropdown');
            if (menu && btn) {
                menu.classList.remove('show');
                btn.classList.remove('open');
            }
        }
    });
</script>
@endsection
