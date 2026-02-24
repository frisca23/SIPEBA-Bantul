@extends('layouts.app')

@section('title', 'Detail Barang: ' . $barang->nama_barang)

@section('content')
<div class="page-header">
    <h2>{{ $barang->nama_barang }}</h2>
    <p class="breadcrumbs">Home > Barang > {{ $barang->kode_barang }}</p>
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

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
    <div style="background: #f8f9fa; padding: 20px; border-radius: 5px;">
        <h4 style="color: #003399; margin-bottom: 15px;">Informasi Barang</h4>
        <p><strong>Kode:</strong> {{ $barang->kode_barang }}</p>
        <p><strong>Nama:</strong> {{ $barang->nama_barang }}</p>
        <p><strong>Jenis:</strong> {{ $barang->jenisBarang->nama_jenis }}</p>
        <p><strong>Satuan:</strong> {{ $barang->satuan }}</p>
        <p><strong>Unit Kerja:</strong> {{ $barang->unitKerja->nama_unit }}</p>
        <p><strong>Dibuat:</strong> {{ $barang->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div style="background: #e3f2fd; padding: 20px; border-radius: 5px;">
        <h4 style="color: #1976D2; margin-bottom: 15px;">Status Stok</h4>
        <p style="font-size: 24px; font-weight: bold; color: #1976D2; margin-bottom: 10px;">
            {{ $barang->stok_saat_ini }}
        </p>
        <small style="color: #666;">Stok Saat Ini</small>
        <hr style="margin: 15px 0;">
        <p><strong>Harga Terakhir:</strong></p>
        <p style="font-size: 18px; font-weight: bold; color: #388E3C;">
            Rp {{ number_format($barang->harga_terakhir, 2, ',', '.') }}
        </p>
    </div>
</div>

@can('update', $barang)
<div style="margin-bottom: 20px;">
    <a href="{{ route('barang.edit', $barang) }}" class="btn btn-warning">Edit Barang</a>
    @can('delete', $barang)
    <form action="{{ route('barang.destroy', $barang) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus barang {{ $barang->nama_barang }}?\n\nCATATAN: Barang yang sudah digunakan dalam transaksi tidak dapat dihapus.')">
            Hapus
        </button>
    </form>
    @endcan
</div>
@endcan

<div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin-top: 20px;">
    <strong>Catatan:</strong> Stok dan harga barang tidak dapat diedit langsung. Keduanya akan diperbarui otomatis saat transaksi penerimaan atau pengurangan disetujui.
</div>

<a href="{{ route('barang.index') }}" class="btn btn-primary" style="margin-top: 20px;">Kembali ke Daftar</a>
@endsection
