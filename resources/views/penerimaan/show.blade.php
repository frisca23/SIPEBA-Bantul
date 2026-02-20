@extends('layouts.app')

@section('title', 'Detail Penerimaan: ' . $penerimaan->no_dokumen)

@section('content')
<div class="page-header">
    <h2>Penerimaan: {{ $penerimaan->no_dokumen }}</h2>
    <p class="breadcrumbs">Home > Penerimaan > {{ $penerimaan->no_dokumen }}</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
    <div style="background: #f8f9fa; padding: 20px; border-radius: 5px;">
        <h4 style="color: #003399; margin-bottom: 15px;">Informasi Penerimaan</h4>
        <p><strong>No Dokumen:</strong> {{ $penerimaan->no_dokumen }}</p>
        <p><strong>Unit Kerja:</strong> {{ $penerimaan->unitKerja->nama_unit }}</p>
        <p><strong>Tanggal Dokumen:</strong> {{ $penerimaan->tgl_dokumen->format('d/m/Y') }}</p>
        <p><strong>Sumber Dana:</strong> {{ $penerimaan->sumber_dana }}</p>
        <p><strong>Tahun Anggaran:</strong> {{ $penerimaan->tahun_anggaran }}</p>
        <p><strong>Dibuat oleh:</strong> {{ $penerimaan->creator->name }}</p>
        @if($penerimaan->verifier)
        <p><strong>Disetujui oleh:</strong> {{ $penerimaan->verifier->name }}</p>
        <p><strong>Waktu Persetujuan:</strong> {{ $penerimaan->verified_at->format('d/m/Y H:i') }}</p>
        @endif
    </div>

    <div style="background: #e3f2fd; padding: 20px; border-radius: 5px;">
        <h4 style="color: #1976D2; margin-bottom: 15px;">Status</h4>
        <p style="font-size: 24px; font-weight: bold; margin-bottom: 10px;">
            @if($penerimaan->status === 'pending')
                <span class="badge badge-pending">PENDING</span>
            @elseif($penerimaan->status === 'approved')
                <span class="badge badge-approved">DISETUJUI</span>
            @else
                <span class="badge badge-rejected">DITOLAK</span>
            @endif
        </p>
        <small style="color: #666;">
            @if($penerimaan->status === 'pending')
                Menunggu persetujuan dari Kepala Bagian
            @elseif($penerimaan->status === 'approved')
                Stok dan harga barang sudah diperbarui
            @endif
        </small>
    </div>
</div>

<hr style="margin: 30px 0;">

<h3 style="color: #003399; margin-bottom: 15px;">Detail Penerimaan</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Satuan</th>
            <th style="text-align: center;">Jumlah</th>
            <th style="text-align: right;">Harga Satuan</th>
            <th style="text-align: right;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($penerimaan->detail as $detail)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $detail->barang->nama_barang }}</td>
            <td>{{ $detail->barang->satuan }}</td>
            <td style="text-align: center;">{{ $detail->jumlah_masuk }}</td>
            <td style="text-align: right;">Rp {{ number_format($detail->harga_satuan, 2, ',', '.') }}</td>
            <td style="text-align: right; font-weight: bold;">Rp {{ number_format($detail->total_harga, 2, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr style="background-color: #f8f9fa; font-weight: bold;">
            <td colspan="5">TOTAL</td>
            <td style="text-align: right;">Rp {{ number_format($penerimaan->detail->sum('total_harga'), 2, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

@if($penerimaan->keterangan)
<div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-top: 20px;">
    <h4 style="margin-bottom: 10px;">Keterangan</h4>
    <p>{{ $penerimaan->keterangan }}</p>
</div>
@endif

<div style="margin-top: 30px;">
    @can('update', $penerimaan)
    <a href="{{ route('penerimaan.edit', $penerimaan) }}" class="btn btn-warning">Edit</a>
    @endcan

    @can('approve', $penerimaan)
    <form action="{{ route('penerimaan.approve', $penerimaan) }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="btn btn-success" 
                onclick="return confirm('Setujui penerimaan ini? Stok akan diperbarui.')">
            Setujui Penerimaan
        </button>
    </form>
    @endcan

    @can('delete', $penerimaan)
    <form action="{{ route('penerimaan.destroy', $penerimaan) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus penerimaan ini?')">
            Hapus
        </button>
    </form>
    @endcan

    <a href="{{ route('penerimaan.index') }}" class="btn btn-primary">Kembali</a>
</div>
@endsection
