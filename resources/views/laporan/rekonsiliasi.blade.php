@extends('layouts.app')

@section('title', 'Rekonsiliasi Persediaan')

@section('content')
<div class="page-header">
    <h2>Rekonsiliasi Persediaan</h2>
    <p class="breadcrumbs">Home > Laporan > Rekonsiliasi Persediaan</p>
</div>

<div style="background: #f3e5f5; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
    <p>
        <strong>Unit Kerja:</strong> {{ $unitKerja->nama_unit }}<br>
        <strong>Periode:</strong> {{ $tglAwal->format('d/m/Y') }} - {{ $tglAkhir->format('d/m/Y') }}<br>
        <strong>Total Nilai Aset:</strong> Rp {{ number_format($totalNilaiAset, 2, ',', '.') }}
    </p>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Jenis Barang</th>
            <th style="text-align: right;">Total Nilai Aset</th>
            <th style="text-align: center;">Persentase (%)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->nama_jenis }}</td>
            <td style="text-align: right; font-weight: bold;">
                Rp {{ number_format($item->total_nilai_aset, 2, ',', '.') }}
            </td>
            <td style="text-align: center;">
                @if($totalNilaiAset > 0)
                    {{ number_format(($item->total_nilai_aset / $totalNilaiAset) * 100, 2) }}%
                @else
                    0%
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" style="text-align: center; padding: 30px;">
                <em>Tidak ada data barang untuk unit kerja ini</em>
            </td>
        </tr>
        @endforelse
        @if($data->count() > 0)
        <tr style="background-color: #f8f9fa; font-weight: bold;">
            <td colspan="2">TOTAL</td>
            <td style="text-align: right;">
                Rp {{ number_format($totalNilaiAset, 2, ',', '.') }}
            </td>
            <td style="text-align: center;">100%</td>
        </tr>
        @endif
    </tbody>
</table>

<div style="background: #e0e0e0; padding: 15px; border-radius: 5px; margin-top: 20px;">
    <h4 style="margin-bottom: 10px;">Penjelasan</h4>
    <p>
        Laporan ini menunjukkan perbandingan nilai aset persediaan barang yang dimiliki oleh {{ $unitKerja->nama_unit }}
        berdasarkan pengelompokan jenis barang. Total Nilai Aset dihitung dengan formula:<br>
        <strong>Total Nilai Aset per Jenis = SUM(Stok × Harga Terakhir) untuk semua barang dalam jenis tersebut</strong>
    </p>
</div>

<div style="margin-top: 20px;">
    <a href="{{ route('laporan.index') }}" class="btn btn-primary">Kembali ke Laporan</a>
    <button onclick="window.print()" class="btn btn-success" style="margin-left: 10px;">Print</button>
</div>
@endsection
