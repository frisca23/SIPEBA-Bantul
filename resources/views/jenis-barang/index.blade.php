@extends('layouts.app')

@section('title', 'Master Jenis Barang')

@section('content')
<div class="page-header">
    <h2>Master Jenis Barang</h2>
    <p class="breadcrumbs">Home > Jenis Barang</p>
</div>

@can('create', App\Models\JenisBarang::class)
<div style="margin-bottom: 20px;">
    <a href="{{ route('jenis-barang.create') }}" class="btn btn-success">+ Tambah Jenis Barang</a>
</div>
@endcan

@if($jenisBarang->count() > 0)
<table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <thead>
        <tr style="background-color: #f8f9fa;">
            <th style="padding: 15px; text-align: left; border-bottom: 2px solid #ddd; font-weight: 600;">NO</th>
            <th style="padding: 15px; text-align: left; border-bottom: 2px solid #ddd; font-weight: 600;">KODE JENIS</th>
            <th style="padding: 15px; text-align: left; border-bottom: 2px solid #ddd; font-weight: 600;">NAMA JENIS BARANG</th>
            <th style="padding: 15px; text-align: center; border-bottom: 2px solid #ddd; font-weight: 600;">JUMLAH BARANG</th>
            <th style="padding: 15px; text-align: center; border-bottom: 2px solid #ddd; font-weight: 600;">AKSI</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jenisBarang as $index => $jenis)
        <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 15px;">{{ ($jenisBarang->currentPage() - 1) * $jenisBarang->perPage() + $index + 1 }}</td>
            <td style="padding: 15px;">{{ $jenis->kode_jenis }}</td>
            <td style="padding: 15px;">{{ $jenis->nama_jenis }}</td>
            <td style="padding: 15px; text-align: center;">{{ $jenis->barang()->count() }}</td>
            <td style="padding: 15px; text-align: center;">
                <a href="{{ route('jenis-barang.show', $jenis) }}" class="btn btn-primary btn-sm">Lihat</a>
                @can('update', $jenis)
                    <a href="{{ route('jenis-barang.edit', $jenis) }}" class="btn btn-warning btn-sm">Edit</a>
                @endcan
                @can('delete', $jenis)
                    <form action="{{ route('jenis-barang.destroy', $jenis) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $jenisBarang->links() }}
@else
<div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 5px;">
    <p>Belum ada data jenis barang.</p>
    @if(auth()->user()->role === 'super_admin')
        <a href="{{ route('jenis-barang.create') }}" class="btn btn-success">Tambah Jenis Barang Pertama</a>
    @endif
</div>
@endif
@endsection
