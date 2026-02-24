@extends('layouts.app')

@section('title', 'Master Barang')

@section('content')
<div class="page-header">
    <h2>Master Data Barang</h2>
    <p class="breadcrumbs">Home > Barang</p>
</div>

@if(session('success'))
<div class="alert alert-success" style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px 20px; border-radius: 4px; margin-bottom: 20px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger" style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px 20px; border-radius: 4px; margin-bottom: 20px;">
    <i class="fas fa-exclamation-triangle"></i>
    @foreach($errors->all() as $error)
        {{ $error }}
    @endforeach
</div>
@endif

@can('create', App\Models\Barang::class)
<div style="margin-bottom: 15px;">
    <a href="{{ route('barang.create') }}" class="btn btn-success">+ Tambah Barang Baru</a>
</div>
@endcan

@if(auth()->user()->role === 'super_admin')
<div style="display: flex; gap: 12px; align-items: center; margin-bottom: 18px;">
    <label for="unit-kerja-filter" style="font-weight: 600; color: #1a2b4b;">Filter Unit Kerja:</label>
    <select id="unit-kerja-filter" style="max-width: 320px;">
        <option value="all" @selected(($selectedUnit ?? 'all') === 'all')>Semua Unit</option>
        @foreach($unitKerja as $unit)
            <option value="{{ $unit->id }}" @selected(($selectedUnit ?? 'all') == $unit->id)>
                {{ $unit->nama_unit }}
            </option>
        @endforeach
    </select>
</div>
@endif

<div id="barang-table">
    @include('barang.partials.table', ['barang' => $barang])
</div>

@if(auth()->user()->role === 'super_admin')
<script>
    const unitFilter = document.getElementById('unit-kerja-filter');
    const tableContainer = document.getElementById('barang-table');

    const fetchTable = (url) => {
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                tableContainer.innerHTML = html;
            })
            .catch(() => {
                tableContainer.innerHTML = '<p style="color:#991b1b;">Gagal memuat data. Silakan refresh halaman.</p>';
            });
    };

    unitFilter.addEventListener('change', () => {
        const params = new URLSearchParams(window.location.search);
        params.set('unit_kerja_id', unitFilter.value);
        params.delete('page');
        const url = `${window.location.pathname}?${params.toString()}`;
        fetchTable(url);
    });

    tableContainer.addEventListener('click', (event) => {
        const link = event.target.closest('a');
        if (!link || !link.closest('.pagination')) {
            return;
        }
        event.preventDefault();
        const url = new URL(link.href);
        if (unitFilter && !url.searchParams.has('unit_kerja_id')) {
            url.searchParams.set('unit_kerja_id', unitFilter.value);
        }
        fetchTable(url.toString());
    });
</script>
@endif
@endsection
