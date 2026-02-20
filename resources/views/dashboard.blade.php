@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h2>Dashboard</h2>
    <p class="breadcrumbs">Beranda</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
    <div style="background: #e3f2fd; padding: 20px; border-radius: 5px; border-left: 4px solid #003399;">
        <h3 style="color: #003399; margin-bottom: 10px;">Unit Kerja Anda</h3>
        <p style="font-size: 16px; color: #555;">
            {{ auth()->user()->unitKerja?->nama_unit ?? 'Super Admin' }}
        </p>
    </div>

    <div style="background: #f3e5f5; padding: 20px; border-radius: 5px; border-left: 4px solid #7c4dff;">
        <h3 style="color: #7c4dff; margin-bottom: 10px;">Role Anda</h3>
        <p style="font-size: 16px; color: #555;">
            @if(auth()->user()->role === 'kepala_bagian')
                Kepala Bagian
            @elseif(auth()->user()->role === 'pengurus_barang')
                Pengurus Barang
            @else
                Super Admin
            @endif
        </p>
    </div>

    <div style="background: #e8f5e9; padding: 20px; border-radius: 5px; border-left: 4px solid #28a745;">
        <h3 style="color: #28a745; margin-bottom: 10px;">Total Barang</h3>
        <p style="font-size: 24px; font-weight: bold; color: #28a745;">
            @if(auth()->user()->unitKerja)
                {{ auth()->user()->unitKerja->barang()->count() }}
            @else
                -
            @endif
        </p>
    </div>
</div>

<hr style="margin: 30px 0;">

<h3>Menu Cepat</h3>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
    <a href="{{ route('barang.index') }}" class="btn btn-primary" style="text-align: center; padding: 20px;">
        <strong>Lihat Barang</strong>
    </a>
    <a href="{{ route('barang.create') }}" class="btn btn-success" style="text-align: center; padding: 20px;" @cannot('create', App\Models\Barang::class) style="opacity: 0.5; cursor: not-allowed;" @endcannot>
        <strong>Tambah Barang</strong>
    </a>
    <a href="{{ route('penerimaan.index') }}" class="btn btn-primary" style="text-align: center; padding: 20px;">
        <strong>Lihat Penerimaan</strong>
    </a>
    <a href="{{ route('penerimaan.create') }}" class="btn btn-success" style="text-align: center; padding: 20px;" @cannot('create', App\Models\Penerimaan::class) style="opacity: 0.5; cursor: not-allowed;" @endcannot>
        <strong>Buat Penerimaan</strong>
    </a>
    <a href="{{ route('pengurangan.index') }}" class="btn btn-primary" style="text-align: center; padding: 20px;">
        <strong>Lihat Pengurangan</strong>
    </a>
    <a href="{{ route('pengurangan.create') }}" class="btn btn-success" style="text-align: center; padding: 20px;" @cannot('create', App\Models\Pengurangan::class) style="opacity: 0.5; cursor: not-allowed;" @endcannot>
        <strong>Buat Pengurangan</strong>
    </a>
    <a href="{{ route('laporan.index') }}" class="btn btn-primary" style="text-align: center; padding: 20px;">
        <strong>Laporan</strong>
    </a>
    @can('is-super-admin')
    <a href="{{ route('rekap-setda.index') }}" class="btn btn-primary" style="text-align: center; padding: 20px;">
        <strong>Rekap SETDA</strong>
    </a>
    @endcan
</div>

<hr style="margin: 30px 0;">

<div style="background: #fff8e1; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
    <strong>Note:</strong> Gunakan menu di atas untuk mengelola barang, penerimaan, pengurangan, dan melihat laporan. 
    Hanya Kepala Bagian yang dapat menyetujui transaksi.
</div>
@endsection
