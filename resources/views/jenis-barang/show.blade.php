@extends('layouts.app')

@section('title', 'Detail Jenis Barang')

@section('content')
<div class="page-header">
    <h2>{{ $jenisBarang->nama_jenis }}</h2>
    <p class="breadcrumbs">Home > Jenis Barang > {{ $jenisBarang->nama_jenis }}</p>
</div>

<div style="background: white; padding: 20px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px;">
    <h3 style="color: #003399; margin-bottom: 15px;">Informasi Jenis Barang</h3>
    
    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 15px; margin-bottom: 20px;">
        <strong>Kode Jenis:</strong>
        <span>{{ $jenisBarang->kode_jenis }}</span>
        
        <strong>Nama Jenis:</strong>
        <span>{{ $jenisBarang->nama_jenis }}</span>
        
        <strong>Jumlah Barang:</strong>
        <span>{{ $jenisBarang->barang()->count() }}</span>
        
        <strong>Dibuat:</strong>
        <span>{{ $jenisBarang->created_at->format('d-m-Y H:i') }}</span>
        
        <strong>Diperbarui:</strong>
        <span>{{ $jenisBarang->updated_at->format('d-m-Y H:i') }}</span>
    </div>

    @can('update', $jenisBarang)
    <div style="margin-top: 20px;">
        <a href="{{ route('jenis-barang.edit', $jenisBarang) }}" class="btn btn-warning">Edit</a>
    </div>
    @endcan
    
    @can('delete', $jenisBarang)
    <div style="margin-top: 10px;">
        <form action="{{ route('jenis-barang.destroy', $jenisBarang) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
        </form>
    </div>
    @endcan
</div>

@if($jenisBarang->barang()->count() > 0)
<div style="background: white; padding: 20px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <h3 style="color: #003399; margin-bottom: 15px;">Daftar Barang dengan Jenis Ini</h3>
    
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">NO</th>
                <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">UNIT KERJA</th>
                <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">KODE BARANG</th>
                <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">NAMA BARANG</th>
                <th style="padding: 10px; text-align: center; border-bottom: 2px solid #ddd;">SATUAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jenisBarang->barang as $index => $barang)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;">{{ $index + 1 }}</td>
                <td style="padding: 10px;">{{ $barang->unitKerja->nama_unit_kerja }}</td>
                <td style="padding: 10px;">{{ $barang->kode_barang }}</td>
                <td style="padding: 10px;">{{ $barang->nama_barang }}</td>
                <td style="padding: 10px; text-align: center;">{{ $barang->satuan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div style="margin-top: 20px;">
    <a href="{{ route('jenis-barang.index') }}" class="btn btn-primary">Kembali</a>
</div>
@endsection
