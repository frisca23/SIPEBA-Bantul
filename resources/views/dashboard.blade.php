@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    /* ============================================================
       DASHBOARD PAGE STYLES
    ============================================================ */
    .db-page-title {
        margin-bottom: 26px;
    }
    .db-page-title h2 {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-main);
        margin: 0 0 4px;
    }
    .db-page-title p {
        font-size: 13px;
        color: var(--text-muted);
        margin: 0;
    }

    /* ---- STAT CARDS ---- */
    .db-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }
    @media (max-width: 1200px) { .db-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 540px)  { .db-stats { grid-template-columns: 1fr; } }

    .db-stat {
        background: var(--card-bg);
        border-radius: var(--radius-lg);
        padding: 22px 20px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        gap: 14px;
        text-decoration: none;
        color: inherit;
        position: relative;
        overflow: hidden;
        transition: transform .22s, box-shadow .22s;
    }
    .db-stat::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
    }
    .db-stat.blue::before  { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
    .db-stat.purple::before{ background: linear-gradient(90deg, #7c3aed, #a78bfa); }
    .db-stat.green::before { background: linear-gradient(90deg, #10b981, #34d399); }
    .db-stat.teal::before  { background: linear-gradient(90deg, #0891b2, #22d3ee); }
    .db-stat:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }

    .db-stat-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }
    .db-stat-label {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: var(--text-muted);
        margin-bottom: 8px;
    }
    .db-stat-val {
        font-size: 30px;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -0.5px;
    }
    .db-stat-val.blue   { color: #2563eb; }
    .db-stat-val.purple { color: #6d28d9; }
    .db-stat-val.green  { color: #059669; }
    .db-stat-val.teal   { color: #0891b2; }
    .db-stat-val.teal-sm { font-size: 19px; color: #0891b2; }

    .db-stat-icon {
        width: 50px; height: 50px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .db-stat-icon svg { width: 24px; height: 24px; color: #fff; }
    .bg-blue   { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .bg-purple { background: linear-gradient(135deg, #7c3aed, #5b21b6); }
    .bg-green  { background: linear-gradient(135deg, #10b981, #059669); }
    .bg-teal   { background: linear-gradient(135deg, #06b6d4, #0284c7); }

    .db-stat-footer {
        font-size: 11.5px;
        font-weight: 500;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 5px;
        border-top: 1px solid var(--border);
        padding-top: 12px;
    }
    .db-stat-footer svg { width: 13px; height: 13px; }

    /* ---- CHARTS ROW ---- */
    .db-charts-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 18px;
        margin-bottom: 24px;
    }
    @media (max-width: 920px) { .db-charts-row { grid-template-columns: 1fr; } }

    .db-panel {
        background: var(--card-bg);
        border-radius: var(--radius-lg);
        padding: 22px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
    }
    .db-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .db-panel-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .db-panel-title-icon {
        width: 36px; height: 36px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
    }
    .db-panel-title-icon svg { width: 18px; height: 18px; color: #fff; }
    .db-panel-title h3 {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
        margin: 0;
    }

    /* ---- TABLES ROW ---- */
    .db-tables-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-bottom: 24px;
    }
    @media (max-width: 920px) { .db-tables-row { grid-template-columns: 1fr; } }

    .db-table-inner { width: 100%; border-collapse: collapse; font-size: 13px; }
    .db-table-inner th {
        text-align: left;
        padding: 9px 13px;
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: var(--text-muted);
        border-bottom: 1.5px solid var(--border);
        background: none;
    }
    .db-table-inner td {
        padding: 10px 13px;
        color: var(--text-main);
        border-bottom: 1px solid #f3f4f6;
        border: none;
    }
    .db-table-inner tbody tr {
        border-bottom: 1px solid #f3f4f6;
    }
    .db-table-inner tbody tr:last-child { border-bottom: none; }
    .db-table-inner tbody tr:hover td { background: #fafbff; }

    .db-see-all {
        font-size: 11.5px;
        font-weight: 600;
        color: #4f7ef8;
        text-decoration: none;
        padding: 5px 12px;
        background: #eff6ff;
        border-radius: 6px;
        transition: background .18s;
    }
    .db-see-all:hover { background: #dbeafe; }

    /* ---- QUICK MENU ---- */
    .db-quick-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 14px;
    }
    .db-qbtn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding: 20px 14px;
        background: var(--card-bg);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-md);
        text-decoration: none;
        color: var(--text-main);
        font-size: 12.5px;
        font-weight: 600;
        text-align: center;
        transition: all .2s;
        cursor: pointer;
    }
    .db-qbtn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.09);
        border-color: #c7d9ff;
        color: var(--primary);
        background: #f5f8ff;
    }
    .db-qbtn-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
    }
    .db-qbtn-icon svg { width: 22px; height: 22px; color: #fff; }

    /* ---- INFO NOTE ---- */
    .db-note {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: linear-gradient(135deg, #eff6ff, #f0fdf4);
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: 14px 18px;
        font-size: 13px;
        color: #374151;
        margin-top: 20px;
    }
    .db-note svg { width: 18px; height: 18px; color: #3b82f6; flex-shrink: 0; margin-top: 1px; }
</style>
@endpush

@section('content')

{{-- Page title --}}
<div class="db-page-title">
    <h2>Dashboard</h2>
    <p>Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>. Berikut ringkasan data hari ini.</p>
</div>

{{-- ===== STAT CARDS ===== --}}
<div class="db-stats">

    <a href="{{ route('barang.index') }}" class="db-stat blue">
        <div class="db-stat-top">
            <div>
                <div class="db-stat-label">Total Barang</div>
                <div class="db-stat-val blue">{{ $totalBarang }}</div>
            </div>
            <div class="db-stat-icon bg-blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
            </div>
        </div>
        <div class="db-stat-footer">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            Lihat semua barang
        </div>
    </a>

    <a href="{{ route('penerimaan.index') }}" class="db-stat purple">
        <div class="db-stat-top">
            <div>
                <div class="db-stat-label">Penerimaan Bulan Ini</div>
                <div class="db-stat-val purple">{{ $totalPenerimaanBulanIni }}</div>
            </div>
            <div class="db-stat-icon bg-purple">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            </div>
        </div>
        <div class="db-stat-footer">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            Transaksi masuk
        </div>
    </a>

    <a href="{{ route('pengurangan.index') }}" class="db-stat green">
        <div class="db-stat-top">
            <div>
                <div class="db-stat-label">Pengurangan Bulan Ini</div>
                <div class="db-stat-val green">{{ $totalPenguranganBulanIni }}</div>
            </div>
            <div class="db-stat-icon bg-green">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </div>
        </div>
        <div class="db-stat-footer">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            Transaksi keluar
        </div>
    </a>

    <div class="db-stat teal">
        <div class="db-stat-top">
            <div>
                <div class="db-stat-label">Estimasi Total Aset</div>
                <div class="db-stat-val teal-sm">Rp {{ number_format($estimasiAset, 0, ',', '.') }}</div>
            </div>
            <div class="db-stat-icon bg-teal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div class="db-stat-footer">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            Nilai stok keseluruhan
        </div>
    </div>

</div>

{{-- ===== CHARTS ===== --}}
<div class="db-charts-row">

    <div class="db-panel">
        <div class="db-panel-header">
            <div class="db-panel-title">
                <div class="db-panel-title-icon bg-blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                </div>
                <h3>Mutasi Barang 6 Bulan Terakhir</h3>
            </div>
        </div>
        <div style="height: 270px; position: relative;">
            <canvas id="mutasiChart"></canvas>
        </div>
    </div>

    <div class="db-panel">
        <div class="db-panel-header">
            <div class="db-panel-title">
                <div class="db-panel-title-icon bg-purple">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                </div>
                <h3>Stok per Jenis Barang</h3>
            </div>
        </div>
        <div style="height: 270px; position: relative;">
            <canvas id="jenisChart"></canvas>
        </div>
    </div>

</div>

{{-- ===== RECENT TRANSACTIONS ===== --}}
<div class="db-tables-row">

    {{-- Penerimaan Terbaru --}}
    <div class="db-panel">
        <div class="db-panel-header">
            <div class="db-panel-title">
                <div class="db-panel-title-icon bg-purple">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                </div>
                <h3>Penerimaan Terbaru</h3>
            </div>
            <a href="{{ route('penerimaan.index') }}" class="db-see-all">Lihat Semua →</a>
        </div>
        <div style="overflow-x: auto;">
            <table class="db-table-inner">
                <thead>
                    <tr>
                        <th>No. Dokumen</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestPenerimaan as $item)
                    <tr>
                        <td style="font-weight: 600; font-size: 12.5px; color: #374151;">{{ $item->no_dokumen }}</td>
                        <td style="font-size: 12.5px; color: var(--text-muted);">{{ $item->tgl_dokumen->format('d M Y') }}</td>
                        <td>
                            <span class="db-badge {{ $item->status === 'approved' ? 'db-badge-approved' : ($item->status === 'rejected' ? 'db-badge-rejected' : 'db-badge-pending') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center; color:#9ca3af; padding:22px; font-size:13px;">Belum ada data penerimaan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pengurangan Terbaru --}}
    <div class="db-panel">
        <div class="db-panel-header">
            <div class="db-panel-title">
                <div class="db-panel-title-icon bg-green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </div>
                <h3>Pengurangan Terbaru</h3>
            </div>
            <a href="{{ route('pengurangan.index') }}" class="db-see-all">Lihat Semua →</a>
        </div>
        <div style="overflow-x: auto;">
            <table class="db-table-inner">
                <thead>
                    <tr>
                        <th>No. Dokumen</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestPengurangan as $item)
                    <tr>
                        <td style="font-weight: 600; font-size: 12.5px; color: #374151;">{{ $item->no_dokumen }}</td>
                        <td style="font-size: 12.5px; color: var(--text-muted);">{{ $item->tgl_keluar->format('d M Y') }}</td>
                        <td>
                            <span class="db-badge {{ $item->status === 'approved' ? 'db-badge-approved' : ($item->status === 'rejected' ? 'db-badge-rejected' : 'db-badge-pending') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center; color:#9ca3af; padding:22px; font-size:13px;">Belum ada data pengurangan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ===== QUICK MENU ===== --}}
<div class="db-panel">
    <div class="db-panel-header" style="margin-bottom: 16px;">
        <div class="db-panel-title">
            <div class="db-panel-title-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h3>Menu Cepat</h3>
        </div>
    </div>
    <div class="db-quick-grid">

        <a href="{{ route('barang.index') }}" class="db-qbtn">
            <div class="db-qbtn-icon bg-blue"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg></div>
            Lihat Barang
        </a>

        @can('create', App\Models\Barang::class)
        <a href="{{ route('barang.create') }}" class="db-qbtn">
            <div class="db-qbtn-icon bg-blue"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div>
            Tambah Barang
        </a>
        @endcan

        @can('create', App\Models\JenisBarang::class)
        <a href="{{ route('jenis-barang.create') }}" class="db-qbtn">
            <div class="db-qbtn-icon bg-blue"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg></div>
            Tambah Jenis Barang
        </a>
        @endcan

        <a href="{{ route('penerimaan.index') }}" class="db-qbtn">
            <div class="db-qbtn-icon bg-purple"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg></div>
            Lihat Penerimaan
        </a>

        @can('create', App\Models\Penerimaan::class)
        <a href="{{ route('penerimaan.create') }}" class="db-qbtn">
            <div class="db-qbtn-icon bg-purple"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div>
            Buat Penerimaan
        </a>
        @endcan

        <a href="{{ route('pengurangan.index') }}" class="db-qbtn">
            <div class="db-qbtn-icon bg-green"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg></div>
            Lihat Pengurangan
        </a>

        @can('create', App\Models\Pengurangan::class)
        <a href="{{ route('pengurangan.create') }}" class="db-qbtn">
            <div class="db-qbtn-icon bg-green"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div>
            Buat Pengurangan
        </a>
        @endcan

        <a href="{{ route('laporan.index') }}" class="db-qbtn">
            <div class="db-qbtn-icon bg-teal"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
            Laporan
        </a>

        @can('is-super-admin')
        <a href="{{ route('rekap-setda.index') }}" class="db-qbtn">
            <div class="db-qbtn-icon" style="background: linear-gradient(135deg,#f59e0b,#d97706);"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
            Rekap SETDA
        </a>
        @endcan

    </div>
</div>

{{-- ===== INFO NOTE ===== --}}
<div class="db-note">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span>Gunakan menu di samping atau menu cepat di atas untuk mengelola barang, penerimaan, dan pengurangan. Hanya <strong>Kepala Bagian</strong> yang dapat menyetujui transaksi.</span>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mutasiData = @json($chartMutasi);
    const jenisData  = @json($chartJenis);

    Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
    Chart.defaults.font.size   = 12;
    Chart.defaults.color       = '#6b7280';

    /* ---- Mutasi Line Chart ---- */
    const mCtx = document.getElementById('mutasiChart').getContext('2d');
    const gIn  = mCtx.createLinearGradient(0, 0, 0, 270);
    gIn.addColorStop(0, 'rgba(99,102,241,.25)');
    gIn.addColorStop(1, 'rgba(99,102,241,.02)');
    const gOut = mCtx.createLinearGradient(0, 0, 0, 270);
    gOut.addColorStop(0, 'rgba(16,185,129,.25)');
    gOut.addColorStop(1, 'rgba(16,185,129,.02)');

    new Chart(mCtx, {
        type: 'line',
        data: {
            labels: mutasiData.labels,
            datasets: [
                {
                    label: 'Penerimaan',
                    data: mutasiData.penerimaan,
                    borderColor: '#6366f1', backgroundColor: gIn,
                    borderWidth: 2.5, tension: 0.4, fill: true,
                    pointRadius: 5, pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff', pointBorderWidth: 2, pointHoverRadius: 7,
                },
                {
                    label: 'Pengurangan',
                    data: mutasiData.pengurangan,
                    borderColor: '#10b981', backgroundColor: gOut,
                    borderWidth: 2.5, tension: 0.4, fill: true,
                    pointRadius: 5, pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff', pointBorderWidth: 2, pointHoverRadius: 7,
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, padding: 14 } },
                tooltip: {
                    backgroundColor: '#1a2b4b', padding: 12, cornerRadius: 8,
                    callbacks: { label: c => ` ${c.dataset.label}: ${c.parsed.y} transaksi` }
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { stepSize: 1 } }
            }
        }
    });

    /* ---- Jenis Doughnut Chart ---- */
    const jCtx = document.getElementById('jenisChart').getContext('2d');
    new Chart(jCtx, {
        type: 'doughnut',
        data: {
            labels: jenisData.labels,
            datasets: [{
                data: jenisData.data,
                backgroundColor: [
                    'rgba(99,102,241,.85)', 'rgba(16,185,129,.85)',
                    'rgba(6,182,212,.85)',  'rgba(245,158,11,.85)',
                    'rgba(239,68,68,.85)',  'rgba(168,85,247,.85)',
                ],
                borderColor: '#fff', borderWidth: 3, hoverOffset: 8,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '65%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 11 } } },
                tooltip: {
                    backgroundColor: '#1a2b4b', padding: 12, cornerRadius: 8,
                    callbacks: { label: c => ` ${c.label}: ${c.parsed} item` }
                }
            }
        }
    });
});
</script>
@endpush
