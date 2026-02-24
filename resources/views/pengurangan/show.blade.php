@extends('layouts.app')

@section('title', 'Detail Pengurangan')

@section('content')
<div class="page-header">
    <h2>Detail Pengurangan</h2>
    <p class="breadcrumbs">Home > Pengurangan > Detail</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
    <div style="background: #f8f9fa; padding: 20px; border-radius: 5px;">
        <h4 style="color: #003399; margin-bottom: 15px;">Informasi Pengurangan</h4>
        <p><strong>Unit Kerja:</strong> {{ $pengurangan->unitKerja->nama_unit }}</p>
        <p><strong>Tanggal Keluar:</strong> {{ $pengurangan->tgl_keluar->format('d/m/Y') }}</p>
        <p><strong>Dibuat oleh:</strong> {{ $pengurangan->creator->name }}</p>
        @if($pengurangan->verifier)
        <p><strong>Disetujui oleh:</strong> {{ $pengurangan->verifier->name }}</p>
        <p><strong>Waktu Persetujuan:</strong> {{ $pengurangan->verified_at->format('d/m/Y H:i') }}</p>
        @endif
    </div>

    <div style="background: #ffebee; padding: 20px; border-radius: 5px;">
        <h4 style="color: #D32F2F; margin-bottom: 15px;">Status</h4>
        <p style="font-size: 24px; font-weight: bold; margin-bottom: 10px;">
            @if($pengurangan->status === 'pending')
            <span class="badge badge-pending">PENDING</span>
            @elseif($pengurangan->status === 'approved')
            <span class="badge badge-approved">DISETUJUI</span>
            @else
            <span class="badge badge-rejected">DITOLAK</span>
            @endif
        </p>
        <small style="color: #666;">
            @if($pengurangan->status === 'pending')
            Menunggu persetujuan dari Kepala Bagian
            @elseif($pengurangan->status === 'approved')
            Stok barang sudah dikurangi
            @endif
        </small>
    </div>
</div>

<hr style="margin: 30px 0;">

<h3 style="color: #003399; margin-bottom: 15px;">Detail Barang yang Dikurangi</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Satuan</th>
            <th style="text-align: center;">Jumlah Dikurangi</th>
            <th style="text-align: right;">Harga Satuan</th>
            <th style="text-align: right;">Total Nilai</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pengurangan->detail as $detail)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $detail->barang->nama_barang }}</td>
            <td>{{ $detail->barang->satuan }}</td>
            <td style="text-align: center;">{{ $detail->jumlah_kurang }}</td>
            <td style="text-align: right;">Rp {{ number_format($detail->barang->harga_terakhir, 2, ',', '.') }}</td>
            <td style="text-align: right; font-weight: bold;">Rp {{ number_format($detail->jumlah_kurang * $detail->barang->harga_terakhir, 2, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr style="background-color: #f8f9fa; font-weight: bold;">
            <td colspan="5">TOTAL NILAI PENGURANGAN</td>
            <td style="text-align: right;">
                Rp {{ number_format($pengurangan->detail->sum(fn($d) => $d->jumlah_kurang * $d->barang->harga_terakhir), 2, ',', '.') }}
            </td>
        </tr>
    </tbody>
</table>

<div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-top: 20px;">
    <h4 style="margin-bottom: 10px;">Keperluan</h4>
    <p>{{ $pengurangan->keperluan }}</p>
</div>

<div style="margin-top: 30px;">
    @can('update', $pengurangan)
    <a href="{{ route('pengurangan.edit', $pengurangan) }}" class="btn btn-warning">Edit</a>
    @endcan

    @can('approve', $pengurangan)
    <form action="{{ route('pengurangan.approve', $pengurangan) }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="btn btn-success"
            onclick="return confirm('Setujui pengurangan ini? Stok akan dikurangi.')">
            Setujui Pengurangan
        </button>
    </form>
    @endcan

    @can('delete', $pengurangan)
    <form action="{{ route('pengurangan.destroy', $pengurangan) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus pengurangan ini?')">
            Hapus
        </button>
    </form>
    @endcan

    <a href="{{ route('pengurangan.index') }}" class="btn btn-primary">Kembali</a>
</div>
@endsection