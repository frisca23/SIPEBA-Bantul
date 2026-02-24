@extends('layouts.app')

@section('title', 'Hasil Fisik Stock Opname')

@section('content')
<style>
    .selisih-positive { text-align: center; font-weight: bold; color: #388E3C; }
    .selisih-negative { text-align: center; font-weight: bold; color: #D32F2F; }
    .selisih-neutral { text-align: center; font-weight: bold; color: #666666; }
</style>
<div class="page-header">
    <h2>Hasil Fisik Stock Opname</h2>
    <p class="breadcrumbs">Home > Laporan > Hasil Fisik Stock Opname</p>
</div>

<div style="background: #e8f5e9; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
    <p>
        <strong>Unit Kerja:</strong> {{ $unitKerja->nama_unit }}<br>
        <strong>Periode Opname:</strong> {{ $tglAwal->format('d/m/Y') }} - {{ $tglAkhir->format('d/m/Y') }}<br>
        <strong>Total Nilai Selisih:</strong> Rp {{ number_format($totalNilaiSelisih, 2, ',', '.') }}
    </p>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th style="text-align: center;">Stok Sistem</th>
            <th style="text-align: center;">Stok Fisik Gudang</th>
            <th style="text-align: center;">Selisih</th>
            <th style="text-align: right;">Harga Satuan</th>
            <th style="text-align: right;">Total Nilai Selisih</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->nama_barang }}</td>
            <td style="text-align: center;">{{ $item->stok_di_aplikasi }}</td>
            <td style="text-align: center;">{{ $item->stok_fisik_gudang }}</td>
            <td class="selisih-{{ $item->selisih > 0 ? 'positive' : ($item->selisih < 0 ? 'negative' : 'neutral') }}">
                {{ $item->selisih > 0 ? '+' : '' }}{{ $item->selisih }}
            </td>
            <td style="text-align: right;">Rp {{ number_format($item->harga_terakhir, 2, ',', '.') }}</td>
            <td style="text-align: right; font-weight: bold;">
                Rp {{ number_format($item->total_nilai_selisih, 2, ',', '.') }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align: center; padding: 30px;">
                <em>Tidak ada data stock opname untuk periode ini</em>
            </td>
        </tr>
        @endforelse
        @if($data->count() > 0)
        <tr style="background-color: #f8f9fa; font-weight: bold;">
            <td colspan="6">TOTAL NILAI SELISIH</td>
            <td style="text-align: right;">Rp {{ number_format($totalNilaiSelisih, 2, ',', '.') }}</td>
        </tr>
        @endif
    </tbody>
</table>

<div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin-top: 20px;">
    <strong>Catatan:</strong>
    <ul style="margin-left: 20px; margin-top: 10px;">
        <li>Selisih positif berarti stok fisik lebih banyak dari sistem (ada surplus)</li>
        <li>Selisih negatif berarti stok fisik kurang dari sistem (ada defisit)</li>
        <li>Total Nilai Selisih = Selisih × Harga Satuan</li>
    </ul>
</div>

<div style="margin-top: 20px;">
    <a href="{{ route('laporan.index') }}" class="btn btn-primary">Kembali ke Laporan</a>
    <button onclick="window.print()" class="btn btn-secondary" style="margin-left: 10px;">Print</button>
    <form action="{{ route('laporan.hasil-fisik-stock-opname.export') }}" method="POST" style="display: inline;">
        @csrf
        <input type="hidden" name="tgl_awal" value="{{ $tglAwal->format('Y-m-d') }}">
        <input type="hidden" name="tgl_akhir" value="{{ $tglAkhir->format('Y-m-d') }}">
        <input type="hidden" name="unit_kerja_id" value="{{ $unitKerja->id }}">
        <button type="submit" class="btn btn-success" style="margin-left: 10px;">Download Excel</button>
    </form>
</div>
@endsection
