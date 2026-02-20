@extends('layouts.app')

@section('title', 'Rekap SETDA')

@section('content')
<div class="page-header">
    <h2>Dashboard Rekap SETDA</h2>
    <p class="breadcrumbs">Home > Rekap SETDA</p>
</div>

<div style="background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107; margin-bottom: 20px;">
    <strong>⚠️ Akses Terbatas:</strong> Halaman ini hanya dapat diakses oleh Super Admin
</div>

<!-- Summary Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 30px;">
    <div style="background: #e3f2fd; padding: 15px; border-radius: 5px; border-left: 4px solid #2196F3;">
        <h4 style="color: #1976D2; margin-bottom: 10px; font-size: 12px;">GRAND TOTAL NILAI ASET</h4>
        <p style="font-size: 20px; font-weight: bold; color: #1976D2;">
            Rp {{ number_format($grandTotalNilaiAset, 2, ',', '.') }}
        </p>
    </div>

    <div style="background: #e8f5e9; padding: 15px; border-radius: 5px; border-left: 4px solid #4CAF50;">
        <h4 style="color: #388E3C; margin-bottom: 10px; font-size: 12px;">TOTAL BARANG</h4>
        <p style="font-size: 20px; font-weight: bold; color: #388E3C;">
            {{ $grandTotalBarang }}
        </p>
    </div>

    <div style="background: #f3e5f5; padding: 15px; border-radius: 5px; border-left: 4px solid #9C27B0;">
        <h4 style="color: #6A1B9A; margin-bottom: 10px; font-size: 12px;">TOTAL STOK</h4>
        <p style="font-size: 20px; font-weight: bold; color: #6A1B9A;">
            {{ $grandTotalStok }}
        </p>
    </div>

    <div style="background: #f1f8e9; padding: 15px; border-radius: 5px; border-left: 4px solid #689F38;">
        <h4 style="color: #33691E; margin-bottom: 10px; font-size: 12px;">TRANSAKSI DISETUJUI</h4>
        <p style="font-size: 20px; font-weight: bold; color: #33691E;">
            {{ $totalPenerimaan + $totalPengurangan }}
        </p>
    </div>
</div>

<hr style="margin: 30px 0;">

<!-- Transaction Status -->
<div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
    <h3 style="margin-bottom: 15px;">Status Transaksi</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
        <div style="background: white; padding: 15px; border-radius: 5px; border: 1px solid #ddd;">
            <h4 style="color: #28a745;">Penerimaan Disetujui</h4>
            <p style="font-size: 24px; font-weight: bold; color: #28a745;">{{ $totalPenerimaan }}</p>
        </div>
        <div style="background: white; padding: 15px; border-radius: 5px; border: 1px solid #ddd;">
            <h4 style="color: #dc3545;">Pengurangan Disetujui</h4>
            <p style="font-size: 24px; font-weight: bold; color: #dc3545;">{{ $totalPengurangan }}</p>
        </div>
        <div style="background: white; padding: 15px; border-radius: 5px; border: 1px solid #ddd;">
            <h4 style="color: #ffc107;">Penerimaan Pending</h4>
            <p style="font-size: 24px; font-weight: bold; color: #ffc107;">{{ $totalPendingPenerimaan }}</p>
        </div>
        <div style="background: white; padding: 15px; border-radius: 5px; border: 1px solid #ddd;">
            <h4 style="color: #ffc107;">Pengurangan Pending</h4>
            <p style="font-size: 24px; font-weight: bold; color: #ffc107;">{{ $totalPendingPengurangan }}</p>
        </div>
    </div>
</div>

<!-- Units Table -->
<h3 style="margin-top: 30px; margin-bottom: 15px;">Nilai Aset Per Unit Kerja</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Unit Kerja</th>
            <th>Total Barang</th>
            <th>Total Stok</th>
            <th>Total Nilai Aset</th>
            <th>Persentase</th>
        </tr>
    </thead>
    <tbody>
        @forelse($units as $unit)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $unit['nama_unit'] }}</strong></td>
            <td style="text-align: center;">{{ $unit['total_barang'] }}</td>
            <td style="text-align: center;">{{ $unit['total_stok'] }}</td>
            <td style="text-align: right; font-weight: bold;">
                Rp {{ number_format($unit['total_nilai_aset'], 2, ',', '.') }}
            </td>
            <td style="text-align: center;">
                @if($grandTotalNilaiAset > 0)
                    {{ number_format(($unit['total_nilai_aset'] / $grandTotalNilaiAset) * 100, 2) }}%
                @else
                    0%
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 30px;">
                <em>Tidak ada data unit kerja</em>
            </td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr style="background-color: #f8f9fa; font-weight: bold;">
            <td colspan="2">TOTAL SEMUA UNIT</td>
            <td style="text-align: center;">{{ $grandTotalBarang }}</td>
            <td style="text-align: center;">{{ $grandTotalStok }}</td>
            <td style="text-align: right;">
                Rp {{ number_format($grandTotalNilaiAset, 2, ',', '.') }}
            </td>
            <td style="text-align: center;">100%</td>
        </tr>
    </tfoot>
</table>

<hr style="margin: 30px 0;">

<div style="background: #e3f2fd; padding: 15px; border-radius: 5px;">
    <h4 style="margin-bottom: 10px; color: #003399;">Penjelasan Dashboard SETDA:</h4>
    <ul style="margin-left: 20px;">
        <li><strong>Grand Total Nilai Aset:</strong> Total nilai aset yang dimiliki oleh semua 8 unit kerja di Sekretariat Daerah</li>
        <li><strong>Total Barang:</strong> Jumlah item barang yang terdaftar di semua unit</li>
        <li><strong>Total Stok:</strong> Jumlah unit stok barang dari semua barang di semua unit</li>
        <li><strong>Transaksi Disetujui:</strong> Jumlah total transaksi penerimaan dan pengurangan yang sudah disetujui</li>
        <li><strong>Tabel Unit Kerja:</strong> Menunjukkan kontribusi setiap unit terhadap total nilai aset (dengan persentase)</li>
    </ul>
</div>
@endsection
