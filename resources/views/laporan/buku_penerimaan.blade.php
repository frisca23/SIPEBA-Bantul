@extends('layouts.app')

@section('title', 'Buku Penerimaan')

@section('content')
<div class="page-header">
    <h2>Buku Penerimaan Barang</h2>
    <p class="breadcrumbs">Home > Laporan > Buku Penerimaan</p>
</div>

<div style="background: #e3f2fd; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
    <p>
        <strong>Unit Kerja:</strong> {{ $unitKerja->nama_unit }}<br>
        <strong>Periode:</strong> {{ $tglAwal->format('d/m/Y') }} - {{ $tglAkhir->format('d/m/Y') }}<br>
        <strong>Total Nilai:</strong> Rp {{ number_format($totalNilai, 2, ',', '.') }}
    </p>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No Dokumen</th>
            <th>Tanggal</th>
            <th>Nama Barang</th>
            <th style="text-align: center;">Jumlah</th>
            <th style="text-align: right;">Harga Satuan</th>
            <th style="text-align: right;">Total Harga</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $item->no_dokumen }}</strong></td>
            <td>{{ date('d/m/Y', strtotime($item->tgl_dokumen)) }}</td>
            <td>{{ $item->nama_barang }}</td>
            <td style="text-align: center;">{{ $item->jumlah_masuk }}</td>
            <td style="text-align: right;">Rp {{ number_format($item->harga_satuan, 2, ',', '.') }}</td>
            <td style="text-align: right; font-weight: bold;">Rp {{ number_format($item->total_harga, 2, ',', '.') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align: center; padding: 30px;">
                <em>Tidak ada data penerimaan untuk periode ini</em>
            </td>
        </tr>
        @endforelse
        @if($data->count() > 0)
        <tr style="background-color: #f8f9fa; font-weight: bold;">
            <td colspan="6">TOTAL</td>
            <td style="text-align: right;">Rp {{ number_format($totalNilai, 2, ',', '.') }}</td>
        </tr>
        @endif
    </tbody>
</table>

<div style="margin-top: 20px;">
    <a href="{{ route('laporan.index') }}" class="btn btn-primary">Kembali ke Laporan</a>
    <form action="{{ route('laporan.buku-penerimaan.export') }}" method="POST" style="display: inline;">
        @csrf
        <input type="hidden" name="tgl_awal" value="{{ $tglAwal->format('Y-m-d') }}">
        <input type="hidden" name="tgl_akhir" value="{{ $tglAkhir->format('Y-m-d') }}">
        <input type="hidden" name="unit_kerja_id" value="{{ $unitKerja->id }}">
        <button type="submit" class="btn btn-success" style="margin-left: 10px;">Download Excel</button>
    </form>
</div>
@endsection