@extends('layouts.app')

@section('title', 'Rekonsiliasi Persediaan')

@section('content')
<style>
    @media print {
        .no-print { display: none; }
        .page-header { margin-bottom: 10px; }
    }
    .rekon-table { 
        width: 100%; 
        border-collapse: collapse; 
        font-size: 10px;
        margin-top: 15px;
    }
    .rekon-table th, .rekon-table td { 
        border: 1px solid #555; 
        padding: 5px 6px;
        vertical-align: middle;
    }
    .rekon-table th { 
        background: #FFC107; 
        font-weight: bold; 
        text-align: center;
        font-size: 9px;
        line-height: 1.3;
    }
    .rekon-table .num { text-align: right; }
    .rekon-table .category-header { 
        background: #e0e0e0; 
        font-weight: bold; 
        text-align: center;
        font-size: 11px;
    }
    .rekon-table .row-bold { 
        font-weight: bold;
        background: #f9f9f9;
    }
    .rekon-table .row-indent {
        padding-left: 20px;
    }
    .rekon-table tfoot tr { 
        background: #f0f0f0; 
        font-weight: bold;
        border-top: 2px solid #000;
    }
    .info-box {
        background: #e8eaf6;
        padding: 12px 15px;
        border-radius: 4px;
        margin-bottom: 15px;
        font-size: 13px;
    }
    .info-box p { margin: 3px 0; }
</style>

<div class="page-header no-print">
    <h2>Rekonsiliasi Penambahan dan Pengurangan Aset & Persediaan</h2>
    <p class="breadcrumbs">Home > Laporan > Rekonsiliasi</p>
</div>

<div class="info-box">
    <p><strong>DATA REKONSILIASI PENAMBAHAN DAN PENGURANGAN ASET & PERSEDIAAN</strong></p>
    <p><strong>BULAN {{ strtoupper($tglAwal->clone()->isoFormat('MMMM')) }} TAHUN {{ $tglAwal->year }}</strong></p>
    <p style="margin-top: 8px;">
        <strong>Nama OPD / Unit Kerja:</strong> {{ $unitKerja->nama_unit }}<br>
        <strong>Periode Laporan:</strong> {{ $tglAwal->format('d/m/Y') }} s/d {{ $tglAkhir->format('d/m/Y') }}
    </p>
</div>

<table class="rekon-table">
    <thead>
        <tr>
            <th rowspan="2" style="width: 35px;">NO.</th>
            <th rowspan="2" style="width: 200px;">URAIAN</th>
            <th colspan="2">SALDO AWAL</th>
            <th colspan="8">PENAMBAHAN ASET</th>
            <th colspan="4">PENGURANGAN ASET</th>
            <th colspan="2">SALDO AKHIR</th>
            <th rowspan="2" style="width: 80px;">KETERANGAN</th>
        </tr>
        <tr>
            <th style="width: 35px;">Unit</th>
            <th style="width: 70px;">Rp</th>
            <th style="width: 35px;">Unit<br>Belanja<br>Modal</th>
            <th style="width: 70px;">Rp</th>
            <th style="width: 35px;">Unit<br>Belanja<br>Barang/Jasa</th>
            <th style="width: 70px;">Rp</th>
            <th style="width: 35px;">Unit<br>Dropping</th>
            <th style="width: 70px;">Rp</th>
            <th style="width: 35px;">Unit<br>Hibah</th>
            <th style="width: 70px;">Rp</th>
            <th style="width: 35px;">Unit<br>Penghapusan</th>
            <th style="width: 70px;">Rp</th>
            <th style="width: 35px;">Unit<br>Mutasi<br>Keluar</th>
            <th style="width: 70px;">Rp</th>
            <th style="width: 35px;">Unit</th>
            <th style="width: 70px;">Rp</th>
        </tr>
    </thead>
    <tbody>
        @foreach($report['groups'] as $group)
            <tr class="category-header">
                <td colspan="19">{{ $group['label'] }}</td>
            </tr>
            @foreach($group['rows'] as $row)
                <tr class="{{ $row['is_bold'] ? 'row-bold' : '' }}">
                    <td class="num">
                        {{ $row['nomor'] ?? '' }}
                    </td>
                    <td class="{{ isset($row['indent']) && $row['indent'] > 0 ? 'row-indent' : '' }}">
                        {{ $row['uraian'] }}
                    </td>
                    <td class="num">{{ $row['saldo_awal_unit'] ?: '-' }}</td>
                    <td class="num">{{ $row['saldo_awal_val'] > 0 ? number_format($row['saldo_awal_val'], 2, ',', '.') : '-' }}</td>
                    <td class="num">{{ $row['penambahan']['belanja_modal']['unit'] ?: '-' }}</td>
                    <td class="num">{{ $row['penambahan']['belanja_modal']['val'] > 0 ? number_format($row['penambahan']['belanja_modal']['val'], 2, ',', '.') : '-' }}</td>
                    <td class="num">{{ $row['penambahan']['belanja_barang']['unit'] ?: '-' }}</td>
                    <td class="num">{{ $row['penambahan']['belanja_barang']['val'] > 0 ? number_format($row['penambahan']['belanja_barang']['val'], 2, ',', '.') : '-' }}</td>
                    <td class="num">{{ $row['penambahan']['dropping']['unit'] ?: '-' }}</td>
                    <td class="num">{{ $row['penambahan']['dropping']['val'] > 0 ? number_format($row['penambahan']['dropping']['val'], 2, ',', '.') : '-' }}</td>
                    <td class="num">{{ $row['penambahan']['hibah']['unit'] ?: '-' }}</td>
                    <td class="num">{{ $row['penambahan']['hibah']['val'] > 0 ? number_format($row['penambahan']['hibah']['val'], 2, ',', '.') : '-' }}</td>
                    <td class="num">{{ $row['pengurangan']['penghapusan']['unit'] ?: '-' }}</td>
                    <td class="num">{{ $row['pengurangan']['penghapusan']['val'] > 0 ? number_format($row['pengurangan']['penghapusan']['val'], 2, ',', '.') : '-' }}</td>
                    <td class="num">{{ $row['pengurangan']['mutasi_keluar']['unit'] ?: '-' }}</td>
                    <td class="num">{{ $row['pengurangan']['mutasi_keluar']['val'] > 0 ? number_format($row['pengurangan']['mutasi_keluar']['val'], 2, ',', '.') : '-' }}</td>
                    <td class="num">{{ $row['saldo_akhir_unit'] ?: '-' }}</td>
                    <td class="num">{{ $row['saldo_akhir_val'] > 0 ? number_format($row['saldo_akhir_val'], 2, ',', '.') : '-' }}</td>
                    <td>{{ $row['keterangan'] }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="text-align: center;">{{ $report['total']['uraian'] }}</td>
            <td class="num">{{ $report['total']['saldo_awal_unit'] ?: '-' }}</td>
            <td class="num">{{ $report['total']['saldo_awal_val'] > 0 ? number_format($report['total']['saldo_awal_val'], 2, ',', '.') : '-' }}</td>
            <td class="num">{{ $report['total']['penambahan']['belanja_modal']['unit'] ?: '-' }}</td>
            <td class="num">{{ $report['total']['penambahan']['belanja_modal']['val'] > 0 ? number_format($report['total']['penambahan']['belanja_modal']['val'], 2, ',', '.') : '-' }}</td>
            <td class="num">{{ $report['total']['penambahan']['belanja_barang']['unit'] ?: '-' }}</td>
            <td class="num">{{ $report['total']['penambahan']['belanja_barang']['val'] > 0 ? number_format($report['total']['penambahan']['belanja_barang']['val'], 2, ',', '.') : '-' }}</td>
            <td class="num">{{ $report['total']['penambahan']['dropping']['unit'] ?: '-' }}</td>
            <td class="num">{{ $report['total']['penambahan']['dropping']['val'] > 0 ? number_format($report['total']['penambahan']['dropping']['val'], 2, ',', '.') : '-' }}</td>
            <td class="num">{{ $report['total']['penambahan']['hibah']['unit'] ?: '-' }}</td>
            <td class="num">{{ $report['total']['penambahan']['hibah']['val'] > 0 ? number_format($report['total']['penambahan']['hibah']['val'], 2, ',', '.') : '-' }}</td>
            <td class="num">{{ $report['total']['pengurangan']['penghapusan']['unit'] ?: '-' }}</td>
            <td class="num">{{ $report['total']['pengurangan']['penghapusan']['val'] > 0 ? number_format($report['total']['pengurangan']['penghapusan']['val'], 2, ',', '.') : '-' }}</td>
            <td class="num">{{ $report['total']['pengurangan']['mutasi_keluar']['unit'] ?: '-' }}</td>
            <td class="num">{{ $report['total']['pengurangan']['mutasi_keluar']['val'] > 0 ? number_format($report['total']['pengurangan']['mutasi_keluar']['val'], 2, ',', '.') : '-' }}</td>
            <td class="num">{{ $report['total']['saldo_akhir_unit'] ?: '-' }}</td>
            <td class="num">{{ $report['total']['saldo_akhir_val'] > 0 ? number_format($report['total']['saldo_akhir_val'], 2, ',', '.') : '-' }}</td>
            <td>-</td>
        </tr>
    </tfoot>
</table>

<div style="margin-top: 20px; font-size: 11px;">
    <p><em>Catatan: Dokumen Elektronik dan/atau hasil cetaknya merupakan alat bukti hukum yang sah. Informasi Elektronik dan/atau hasil cetaknya secara elektronik menggunakan sertifikat elektronik yang diterbitkan BSRE.</em></p>
    <p><em>*) Isian ini telah ditandatangani secara elektronik.</em></p>
</div>

<div style="margin-top: 20px;" class="no-print">
    <a href="{{ route('laporan.index') }}" class="btn btn-primary">Kembali</a>
    <button onclick="window.print()" class="btn btn-success" style="margin-left: 10px;">Print</button>
</div>
@endsection
