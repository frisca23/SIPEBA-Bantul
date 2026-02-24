@extends('layouts.app')

@section('title', 'Buku Pengurangan')

@section('content')
<div class="page-header">
    <h2>Buku Pengurangan Barang</h2>
    <p class="breadcrumbs">Home > Laporan > Buku Pengurangan</p>
</div>

<div style="background: #ffebee; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
    <p>
        <strong>Unit Kerja:</strong> {{ $unitKerja->nama_unit }}<br>
        <strong>Periode:</strong> {{ $tglAwal->format('d/m/Y') }} - {{ $tglAkhir->format('d/m/Y') }}
    </p>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No Bukti</th>
            <th>Tanggal Keluar</th>
            <th>Nama Peminta (Keperluan)</th>
            <th>Nama Barang</th>
            <th style="text-align: center;">Jumlah Keluar</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $item->no_bukti }}</strong></td>
            <td>{{ date('d/m/Y', strtotime($item->tgl_keluar)) }}</td>
            <td>{{ $item->keperluan }}</td>
            <td>{{ $item->nama_barang }}</td>
            <td style="text-align: center;">{{ $item->jumlah_kurang }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 30px;">
                <em>Tidak ada data pengurangan untuk periode ini</em>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="background: #f0f0f0; padding: 15px; border-radius: 5px; margin-top: 20px;">
    <p><strong>Total Baris Data:</strong> {{ $data->count() }}</p>
</div>

<div style="margin-top: 20px;">
    <a href="{{ route('laporan.index') }}" class="btn btn-primary">Kembali ke Laporan</a>
    <button onclick="window.print()" class="btn btn-secondary" style="margin-left: 10px;">Print</button>
    <form action="{{ route('laporan.buku-pengurangan.export') }}" method="POST" style="display: inline;">
        @csrf
        <input type="hidden" name="tgl_awal" value="{{ $tglAwal->format('Y-m-d') }}">
        <input type="hidden" name="tgl_akhir" value="{{ $tglAkhir->format('Y-m-d') }}">
        <input type="hidden" name="unit_kerja_id" value="{{ $unitKerja->id }}">
        <button type="submit" class="btn btn-success" style="margin-left: 10px;">Download Excel</button>
    </form>
</div>
@endsection
