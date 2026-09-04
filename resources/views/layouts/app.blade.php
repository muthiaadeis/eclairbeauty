<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Eclair Beauty Clinic')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', -apple-system, sans-serif;
            background: #FDF8F4;
            color: #3A3A3A;
        }

        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 250px;
            background: #FFFFFF;
            border-right: 1px solid #F0E8E2;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 50;
            transition: transform 0.25s ease;
        }

        /* Sidebar tertutup: geser keluar layar, main-content balik rapat ke kiri */
        .app-layout.sidebar-closed .sidebar {
            transform: translateX(-100%);
        }

        .sidebar-top-row {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 12px 14px 0;
        }

        .sidebar-toggle-btn {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            border: 1px solid #F0E8E2;
            background: #FDFAF8;
            color: #6B6B6B;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: all 0.2s;
        }
        .sidebar-toggle-btn:hover {
            background: #FBF1EC;
            color: #C17B7B;
            border-color: #C17B7B;
        }

        /* Tombol buka sidebar (floating), cuma keliatan pas sidebar ketutup */
        .sidebar-open-fab {
            display: none;
            position: fixed;
            top: 16px;
            left: 16px;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 1px solid #E8DDD5;
            background: #FFFFFF;
            color: #6B6B6B;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 45;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.2s;
        }
        .sidebar-open-fab:hover {
            background: #FBF1EC;
            color: #C17B7B;
            border-color: #C17B7B;
        }
        .app-layout.sidebar-closed .sidebar-open-fab {
            display: flex;
        }

        .sidebar-logo {
            padding: 8px 20px 20px;
            text-align: center;
            border-bottom: 1px solid #F5EEE8;
        }

        .sidebar-logo-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FDF0E8, #FAE3DC);
            display: block;
            object-fit: cover;
            margin: 0 auto 8px;
        }

        .sidebar-logo-text {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #3A3A3A;
        }

        .sidebar-menu {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 11px 14px;
            border-radius: 10px;
            color: #6B6B6B;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 4px;
            transition: all 0.2s;
        }

        .sidebar-menu a:hover {
            background: #FBF1EC;
        }

        .sidebar-menu a.active {
            background: #C17B7B;
            color: white;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 16px 14px;
            border-top: 1px solid #F5EEE8;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-footer-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #FBE4DD;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #C17B7B;
            font-size: 14px;
            flex-shrink: 0;
        }

        .sidebar-footer-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-footer-name {
            font-size: 13px;
            font-weight: 600;
            color: #3A3A3A;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-footer-role {
            font-size: 11px;
            color: #9B9B9B;
            text-transform: uppercase;
        }

        .sidebar-logout {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px 0;
            font-size: 13px;
            color: #C17B7B;
            text-decoration: none;
        }

        /* ===== SIDEBAR BACKDROP (buat mode overlay di layar sempit) ===== */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.35);
            z-index: 40;
        }
        .app-layout.sidebar-open-mobile .sidebar-backdrop {
            display: block;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            flex: 1;
            min-width: 0;
            margin-left: 250px;
            transition: margin-left 0.25s ease;
        }

        .app-layout.sidebar-closed .main-content {
            margin-left: 0;
        }

        .page-content {
            padding: 32px;
            width: 100%;
        }

        /* Kasih ruang atas biar gak ketiban tombol floating pas sidebar ketutup */
        .app-layout.sidebar-closed .page-content {
            padding-top: 76px;
        }

        .page-breadcrumb {
            font-size: 12px;
            color: #C17B7B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .page-title {
            font-size: 26px;
            font-weight: 700;
            color: #3A3A3A;
            margin-bottom: 4px;
        }

        .page-subtitle {
            font-size: 14px;
            color: #9B9B9B;
            margin-bottom: 24px;
        }

        .page-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }

        /* ===== REUSABLE COMPONENTS ===== */
        .btn-primary {
            padding: 11px 22px;
            background: #C17B7B;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }
        .btn-primary:hover { background: #B86B6B; }

        .btn-secondary {
            padding: 11px 22px;
            background: #FFFFFF;
            color: #6B6B6B;
            border: 1px solid #E8DDD5;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            max-width: 100%;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #FBF1EC;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat-label {
            font-size: 11px;
            color: #9B9B9B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #3A3A3A;
        }

        /* Tabel bisa di-scroll horizontal di layar sempit, gak bikin layout pecah */
        .table-scroll {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        table { width: 100%; border-collapse: collapse; min-width: 560px; }
        thead th {
            text-align: left;
            font-size: 11px;
            color: #9B9B9B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-bottom: 1px solid #F0E8E2;
        }
        tbody td {
            padding: 14px 16px;
            font-size: 14px;
            color: #3A3A3A;
            border-bottom: 1px solid #F8F2EE;
        }
        tbody tr:hover { background: #FDFAF8; }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
            line-height: 1.2;
            text-transform: uppercase;
        }
        .badge-menunggu { background: #FDF0DC; color: #B8860B; }
        .badge-hadir { background: #DCEEF5; color: #2C7A9B; }
        .badge-selesai { background: #DCF0E0; color: #2E8B4F; }
        .badge-batal { background: #FBE0E0; color: #C0392B; }
        .badge-aktif { background: #DCF0E0; color: #2E8B4F; }
        .badge-tidak-aktif { background: #F0F0F0; color: #888; }

        .avatar-initial {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #FBE4DD;
            color: #C17B7B;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
        }

        .alert-success {
            background: #DCF0E0;
            color: #2E8B4F;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-error {
            background: #FBE0E0;
            color: #C0392B;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* ===== RESPONSIVE ===== */

        /* Layar medium (tablet / window di-split) — sidebar default ketutup, jadi overlay */
        @media (max-width: 1024px) {
            .app-layout:not(.sidebar-open-mobile) .sidebar {
                transform: translateX(-100%);
            }
            .app-layout:not(.sidebar-open-mobile) .main-content {
                margin-left: 0;
            }
            .app-layout:not(.sidebar-open-mobile) .page-content {
                padding-top: 76px;
            }
            .app-layout:not(.sidebar-open-mobile) .sidebar-open-fab {
                display: flex;
            }
            .app-layout.sidebar-open-mobile .sidebar {
                transform: translateX(0);
                box-shadow: 4px 0 24px rgba(0,0,0,0.15);
            }
            .app-layout.sidebar-open-mobile .main-content {
                margin-left: 0;
            }
            .app-layout.sidebar-open-mobile .sidebar-open-fab {
                display: none;
            }

            .stat-grid { grid-template-columns: repeat(2, 1fr); }
        }

        /* Layar kecil / HP */
        @media (max-width: 640px) {
            .page-content { padding: 16px; }
            .app-layout:not(.sidebar-open-mobile) .page-content {
                padding-top: 68px;
            }
            .stat-grid { grid-template-columns: 1fr; }
            .page-title { font-size: 21px; }
            .page-header-row { flex-direction: column; align-items: stretch; }
            .card { padding: 16px; }
            .sidebar { width: 230px; }
            .sidebar-open-fab { top: 12px; left: 12px; }
        }

        @yield('extra_style')
    </style>
</head>
<body>
    <div class="app-layout" id="appLayout">

        <!-- BACKDROP (dipakai pas sidebar dibuka sebagai overlay di layar sempit) -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- TOMBOL BUKA SIDEBAR (floating, cuma muncul pas sidebar ketutup) -->
        <button class="sidebar-open-fab" id="sidebarOpenBtn" aria-label="Buka sidebar">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-top-row">
                <button class="sidebar-toggle-btn" id="sidebarCloseBtn" aria-label="Tutup sidebar">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
            </div>

            <div class="sidebar-logo">
                <img src="{{ asset('images/logo-eclair.png') }}" alt="Eclair Beauty Clinic" class="sidebar-logo-icon">
                <div class="sidebar-logo-text">ECLAIR BEAUTY CLINIC</div>
            </div>

            <nav class="sidebar-menu">
                @php $role = session('user_role'); @endphp

                @if($role == 'resepsionis')
                    <a href="{{ route('resepsionis.dashboard') }}" class="{{ request()->routeIs('resepsionis.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('pasien.index') }}" class="{{ request()->routeIs('pasien.*') ? 'active' : '' }}">Data Pasien</a>
                    <a href="{{ route('jadwal.index') }}" class="{{ request()->routeIs('jadwal.*') ? 'active' : '' }}">Jadwal</a>
                    <a href="{{ route('profil.index') }}" class="{{ request()->routeIs('profil.*') ? 'active' : '' }}">Profil</a>
                @elseif($role == 'dokter')
                    <a href="{{ route('dokter.dashboard') }}" class="{{ request()->routeIs('dokter.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('dokter.antrean') }}" class="{{ request()->routeIs('dokter.antrean') ? 'active' : '' }}">Antrean Pasien</a>
                    <a href="{{ route('rekam_medis.index') }}" class="{{ request()->routeIs('rekam_medis.*') ? 'active' : '' }}">Rekam Medis</a>
                    <a href="{{ route('profil.index') }}" class="{{ request()->routeIs('profil.*') ? 'active' : '' }}">Profil</a>
                @elseif($role == 'pemilik')
                    <a href="{{ route('pemilik.dashboard') }}" class="{{ request()->routeIs('pemilik.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('pemilik.laporan') }}" class="{{ request()->routeIs('pemilik.laporan') ? 'active' : '' }}">Laporan</a>
                    <a href="{{ route('profil.index') }}" class="{{ request()->routeIs('profil.*') ? 'active' : '' }}">Profil</a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-footer-avatar">
                    {{ strtoupper(substr(session('user_nama', 'U'), 0, 2)) }}
                </div>
                <div class="sidebar-footer-info">
                    <div class="sidebar-footer-name">{{ session('user_nama') }}</div>
                    <div class="sidebar-footer-role">{{ session('user_role') }}</div>
                </div>
            </div>
            <a href="{{ route('logout') }}" class="sidebar-logout">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Keluar
            </a>
            <div style="height:14px"></div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <main class="page-content">
                @yield('content')
            </main>
        </div>

    </div>

    <script>
        (function() {
            const appLayout = document.getElementById('appLayout');
            const openBtn = document.getElementById('sidebarOpenBtn');
            const closeBtn = document.getElementById('sidebarCloseBtn');
            const backdrop = document.getElementById('sidebarBackdrop');
            const MOBILE_BREAKPOINT = 1024;

            function isMobileWidth() {
                return window.innerWidth <= MOBILE_BREAKPOINT;
            }

            function applyStoredState() {
                if (isMobileWidth()) {
                    // Di layar sempit, sidebar defaultnya ketutup (overlay pas dibuka)
                    appLayout.classList.remove('sidebar-open-mobile');
                    appLayout.classList.remove('sidebar-closed');
                } else {
                    const closed = localStorage.getItem('eclair_sidebar_closed') === '1';
                    appLayout.classList.toggle('sidebar-closed', closed);
                    appLayout.classList.remove('sidebar-open-mobile');
                }
            }

            function openSidebar() {
                if (isMobileWidth()) {
                    appLayout.classList.add('sidebar-open-mobile');
                } else {
                    appLayout.classList.remove('sidebar-closed');
                    localStorage.setItem('eclair_sidebar_closed', '0');
                }
            }

            function closeSidebar() {
                if (isMobileWidth()) {
                    appLayout.classList.remove('sidebar-open-mobile');
                } else {
                    appLayout.classList.add('sidebar-closed');
                    localStorage.setItem('eclair_sidebar_closed', '1');
                }
            }

            openBtn.addEventListener('click', openSidebar);
            closeBtn.addEventListener('click', closeSidebar);
            backdrop.addEventListener('click', closeSidebar);

            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(applyStoredState, 150);
            });

            applyStoredState();
        })();
    </script>

    @yield('scripts')
</body>
</html>
