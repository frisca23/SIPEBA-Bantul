<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SIPEBA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ============================================================
           RESET & BASE
        ============================================================ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 240px;
            --topbar-h: 56px;
            --primary:   #003399;
            --primary-dk:#002266;
            --primary-lt:#dce8ff;
            --accent:    #4f7ef8;
            --surface:   #f0f4fb;
            --card-bg:   #ffffff;
            --text-main: #1a2b4b;
            --text-muted:#6b7280;
            --border:    #e5e9f2;
            --sidebar-bg:#0a1a3e;
            --sidebar-text: rgba(255,255,255,0.75);
            --sidebar-hover-bg: rgba(255,255,255,0.09);
            --sidebar-active-bg: rgba(79,126,248,0.25);
            --sidebar-active-text: #7eaaff;
            --radius-lg: 12px;
            --radius-md: 8px;
            --shadow-sm: 0 1px 4px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.09);
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: var(--surface);
            color: var(--text-main);
            min-height: 100vh;
            font-size: 14px;
            line-height: 1.5;
        }

        a { text-decoration: none; }

        /* ============================================================
           SIDEBAR
        ============================================================ */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 300;
            overflow: hidden;
            transition: width 0.3s ease;
        }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 22px 22px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            flex-shrink: 0;
        }
        .sidebar-brand-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #4f7ef8, #003399);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-brand-icon svg { width: 22px; height: 22px; color: #fff; }
        .sidebar-brand-text strong {
            display: block;
            font-size: 15px;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.3px;
        }
        .sidebar-brand-text span {
            font-size: 10.5px;
            color: var(--sidebar-text);
            font-weight: 500;
        }

        /* User block */
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px 18px;
            margin: 14px 14px 6px;
            background: rgba(255,255,255,0.06);
            border-radius: var(--radius-md);
            border: 1px solid rgba(255,255,255,0.07);
        }
        .sidebar-user-avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #4f7ef8, #7c3aed);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .sidebar-user-info strong {
            display: block;
            font-size: 12.5px; font-weight: 700;
            color: #fff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            max-width: 162px;
        }
        .sidebar-user-info span {
            font-size: 10.5px; color: var(--sidebar-text);
        }

        /* Nav */
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 8px 0 16px; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

        .sidebar-section-label {
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.3);
            padding: 12px 22px 6px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 20px;
            margin: 2px 10px;
            border-radius: 9px;
            color: var(--sidebar-text);
            font-size: 13.5px;
            font-weight: 500;
            transition: background 0.18s, color 0.18s;
        }
        .sidebar-nav a svg { width: 18px; height: 18px; flex-shrink: 0; opacity: 0.75; transition: opacity 0.18s; }
        .sidebar-nav a:hover { background: var(--sidebar-hover-bg); color: #fff; }
        .sidebar-nav a:hover svg { opacity: 1; }
        .sidebar-nav a.active { background: var(--sidebar-active-bg); color: var(--sidebar-active-text); font-weight: 600; }
        .sidebar-nav a.active svg { opacity: 1; color: var(--sidebar-active-text); }

        /* Logout */
        .sidebar-footer {
            padding: 12px 14px 20px;
            border-top: 1px solid rgba(255,255,255,0.07);
            flex-shrink: 0;
        }
        .sidebar-footer form button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 16px;
            background: rgba(239,68,68,0.12);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 9px;
            color: #fca5a5;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.18s;
            font-family: inherit;
        }
        .sidebar-footer form button:hover { background: rgba(239,68,68,0.22); }
        .sidebar-footer form button svg { width: 17px; height: 17px; }

        /* ============================================================
           MAIN WRAPPER
        ============================================================ */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        /* ============================================================
           TOP BAR
        ============================================================ */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 200;
            height: var(--topbar-h);
            background: rgba(240,244,251,0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }
        .topbar-left { display: flex; align-items: center; gap: 10px; }
        .topbar-menu-btn {
            display: none;
            width: 36px; height: 36px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 8px;
            align-items: center; justify-content: center;
            cursor: pointer;
        }
        .topbar-menu-btn svg { width: 18px; height: 18px; color: var(--text-main); }
        .topbar-breadcrumb { font-size: 14px; color: var(--text-muted); }
        .topbar-breadcrumb strong { color: var(--text-main); font-weight: 700; }

        .topbar-right { display: flex; align-items: center; gap: 10px; }
        .topbar-date {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ============================================================
           CONTENT AREA
        ============================================================ */
        .content-area {
            flex: 1;
            padding: 28px 30px;
        }

        /* ============================================================
           ALERTS
        ============================================================ */
        .alert {
            padding: 13px 16px;
            margin-bottom: 18px;
            border-radius: var(--radius-md);
            border-left: 4px solid;
            font-size: 13.5px;
        }
        .alert-success { background:#d1fae5; border-color:#10b981; color:#065f46; }
        .alert-danger  { background:#fee2e2; border-color:#ef4444; color:#991b1b; }
        .alert-warning { background:#fef3c7; border-color:#f59e0b; color:#92400e; }

        /* ============================================================
           TABLES (global)
        ============================================================ */
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 11px 14px; text-align: left; border-bottom: 1px solid var(--border); }
        th { background: #f8faff; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing:.4px; color: var(--text-muted); }
        tr:hover td { background: #fafbff; }

        /* ============================================================
           BUTTONS (global)
        ============================================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dk); }
        .btn-success { background: #10b981; color: #fff; }
        .btn-success:hover { background: #059669; }
        .btn-danger  { background: #ef4444; color: #fff; }
        .btn-danger:hover  { background: #dc2626; }
        .btn-warning { background: #f59e0b; color: #fff; }
        .btn-warning:hover { background: #d97706; }
        .btn-sm { padding: 5px 11px; font-size: 12px; }
        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--border);
            color: var(--text-muted);
        }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); background: #eff6ff; }

        /* ============================================================
           FORM ELEMENTS (global)
        ============================================================ */
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 13px; color: var(--text-main); }
        input, select, textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            font-size: 13.5px;
            color: var(--text-main);
            background: var(--card-bg);
            transition: border-color .2s, box-shadow .2s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(79,126,248,0.12);
        }
        textarea { resize: vertical; min-height: 90px; }

        /* ============================================================
           PAGINATION (global)
        ============================================================ */
        .pagination {
            display: flex; list-style: none; gap: 5px; margin: 18px 0; flex-wrap: wrap;
        }
        .pagination a, .pagination span {
            padding: 7px 12px;
            border: 1.5px solid var(--border);
            border-radius: 7px;
            font-size: 13px;
            color: var(--primary);
            transition: all .2s;
        }
        .pagination a:hover { background: #eff6ff; }
        .pagination .active span { background: var(--primary); color: #fff; border-color: var(--primary); }

        /* ============================================================
           BADGES (global)
        ============================================================ */
        .badge, .db-badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px; font-weight: 700;
            letter-spacing: .3px;
        }
        .badge-pending,  .db-badge-pending  { background:#fef3c7; color:#92400e; }
        .badge-approved, .db-badge-approved { background:#d1fae5; color:#065f46; }
        .badge-rejected, .db-badge-rejected { background:#fee2e2; color:#991b1b; }

        /* ============================================================
           PAGE HEADER (used by sub-pages)
        ============================================================ */
        .page-header {
            margin-bottom: 22px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--primary-lt);
        }
        .page-header h2 { font-size: 20px; font-weight: 800; color: var(--text-main); margin-bottom: 4px; }
        .page-header .breadcrumbs { font-size: 12px; color: var(--text-muted); }

        /* ============================================================
           CARD (used by sub-pages)
        ============================================================ */
        .card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            padding: 22px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            margin-bottom: 20px;
        }

        /* ============================================================
           MOBILE OVERLAY & RESPONSIVE
        ============================================================ */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 290;
        }

        @media (max-width: 900px) {
            .sidebar { width: 260px; transform: translateX(-260px); transition: transform 0.3s ease; }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .main-wrapper { margin-left: 0; }
            .topbar-menu-btn { display: flex; }
            .topbar { padding: 0 18px; }
            .content-area { padding: 20px 16px; }
        }

        /* ============================================================
           FOOTER
        ============================================================ */
        footer {
            text-align: center;
            padding: 16px 20px;
            color: var(--text-muted);
            font-size: 11.5px;
            border-top: 1px solid var(--border);
            background: var(--card-bg);
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ===== SIDEBAR ===== --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<aside class="sidebar" id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
            </svg>
        </div>
        <div class="sidebar-brand-text">
            <strong>SIPEBA</strong>
            <span>Persediaan Barang</span>
        </div>
    </div>

    @auth
    {{-- User Block --}}
    <div class="sidebar-user">
        <div class="sidebar-user-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="sidebar-user-info">
            <strong>{{ auth()->user()->name }}</strong>
            <span>{{ auth()->user()->unitKerja?->nama_unit ?? 'Super Admin' }}</span>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        <div class="sidebar-section-label">Menu Utama</div>

        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            {{-- House/Home icon --}}
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <div class="sidebar-section-label">Master Data</div>

        <a href="{{ route('barang.index') }}" class="{{ request()->routeIs('barang.*') ? 'active' : '' }}">
            {{-- Cube / 3D Box icon --}}
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
            </svg>
            Master Barang
        </a>

        <a href="{{ route('jenis-barang.index') }}" class="{{ request()->routeIs('jenis-barang.*') ? 'active' : '' }}">
            {{-- Tag / categorize icon --}}
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Jenis Barang
        </a>

        <div class="sidebar-section-label">Transaksi</div>

        <a href="{{ route('penerimaan.index') }}" class="{{ request()->routeIs('penerimaan.*') ? 'active' : '' }}">
            {{-- Arrow Down Tray (download/incoming) icon --}}
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Penerimaan
        </a>

        <a href="{{ route('pengurangan.index') }}" class="{{ request()->routeIs('pengurangan.*') ? 'active' : '' }}">
            {{-- Minus circle / outgoing box icon --}}
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2L19 8m-9 4h4"/>
            </svg>
            Pengurangan
        </a>

        <div class="sidebar-section-label">Laporan</div>

        <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            {{-- Document chart bar (reports) icon --}}
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Laporan
        </a>

        @can('is-super-admin')
        <a href="{{ route('rekap-setda.index') }}" class="{{ request()->routeIs('rekap-setda.*') ? 'active' : '' }}">
            {{-- Building / institution icon --}}
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
            </svg>
            Rekap SETDA
        </a>
        @endcan

        @if(auth()->user()->role === 'super_admin')
        <div class="sidebar-section-label">Administrasi</div>
        <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
            {{-- Users / people icon --}}
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Kelola User
        </a>
        @endif
    </nav>

    {{-- Logout --}}
    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
    @endauth

</aside>

{{-- ===== MAIN WRAPPER ===== --}}
<div class="main-wrapper">

    {{-- Top Bar --}}
    <div class="topbar">
        <div class="topbar-left">
            <button class="topbar-menu-btn" id="menuToggle" aria-label="Toggle sidebar">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="topbar-breadcrumb">
                SIPEBA &rsaquo; <strong>@yield('title', 'Dashboard')</strong>
            </span>
        </div>
        <div class="topbar-right">
            <span class="topbar-date">{{ now()->locale('id')->isoFormat('D MMM YYYY') }}</span>
        </div>
    </div>

    {{-- Flash Messages --}}
    <div style="padding: 0 30px;">
        @if ($errors->any())
        <div class="alert alert-danger" style="margin-top: 18px;">
            <strong>Terjadi Kesalahan:</strong>
            <ul style="margin-top: 6px; padding-left: 16px;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if (session('success'))
        <div class="alert alert-success" style="margin-top: 18px;">
            {{ session('success') }}
        </div>
        @endif
    </div>

    {{-- Page Content --}}
    <main class="content-area">
        @yield('content')
    </main>

    <footer>
        &copy; {{ date('Y') }} SIPEBA &mdash; Sekretariat Daerah Kabupaten Bantul. All rights reserved.
    </footer>
</div>

{{-- Sidebar toggle script --}}
<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggle  = document.getElementById('menuToggle');

    toggle?.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    });
    overlay?.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    });
</script>

@stack('scripts')
</body>
</html>
