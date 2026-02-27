@extends('layouts.app')

@section('title', 'Detail Stock Opname')

@section('content')
<div class="page-header">
    <h2>Detail Stock Opname</h2>
    <p class="breadcrumbs">Home > Stock Opname > Detail</p>
</div>

@if(session('success'))
<div class="alert alert-success" style="margin-bottom: 18px;">
    {{ session('success') }}
</div>
@endif

<div style="background: #f8f9fa; padding: 18px; border-radius: 8px; margin-bottom: 20px;">
    <h4 style="margin-bottom: 10px; color: #003399;">Informasi Stock Opname</h4>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 6px 0; width: 180px;"><strong>Tanggal Opname</strong></td>
            <td>{{ $stockOpname->tgl_opname->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 0;"><strong>Barang</strong></td>
            <td>{{ $stockOpname->barang->nama_barang }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 0;"><strong>Unit Kerja</strong></td>
            <td>{{ $stockOpname->unitKerja->nama_unit }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 0;"><strong>Stok di Aplikasi</strong></td>
            <td>{{ $stockOpname->stok_di_aplikasi }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 0;"><strong>Stok Fisik Gudang</strong></td>
            <td>{{ $stockOpname->stok_fisik_gudang }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 0;"><strong>Selisih</strong></td>
            <td>{{ $stockOpname->selisih > 0 ? '+' : '' }}{{ $stockOpname->selisih }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 0;"><strong>Status</strong></td>
            <td>
                @if($stockOpname->status === 'pending')
                    <span class="badge badge-pending">PENDING</span>
                @elseif($stockOpname->status === 'approved')
                    <span class="badge badge-approved">DISETUJUI</span>
                @else
                    <span class="badge badge-rejected">DITOLAK</span>
                @endif
            </td>
        </tr>
        <tr>
            <td style="padding: 6px 0;"><strong>Dibuat Oleh</strong></td>
            <td>{{ $stockOpname->creator->name }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 0;"><strong>Disetujui Oleh</strong></td>
            <td>{{ $stockOpname->verifier?->name ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 0;"><strong>Keterangan</strong></td>
            <td>{{ $stockOpname->keterangan ?? '-' }}</td>
        </tr>
    </table>
</div>

<div style="display: flex; gap: 10px; flex-wrap: wrap;">
    <a href="{{ route('stock-opname.index') }}" class="btn btn-primary">Kembali</a>

    @can('update', $stockOpname)
    <a href="{{ route('stock-opname.edit', $stockOpname) }}" class="btn btn-warning">Edit</a>
    @endcan

    @can('delete', $stockOpname)
    <form action="{{ route('stock-opname.destroy', $stockOpname) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus stock opname ini?')">Hapus</button>
    </form>
    @endcan

    @can('approve', $stockOpname)
    @if($stockOpname->status === 'pending')
    <form action="{{ route('stock-opname.approve', $stockOpname) }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="btn btn-success" onclick="return confirm('Setujui stock opname ini? Stok akan diperbarui.')">Setujui</button>
    </form>
    @endif
    @endcan
</div>
@endsection
