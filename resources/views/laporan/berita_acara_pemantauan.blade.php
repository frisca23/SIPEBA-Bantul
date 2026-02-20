@extends('layouts.app')

@section('title', 'Berita Acara Pemantauan')

@section('content')
<div class="page-header">
    <h2>Berita Acara Pemantauan</h2>
    <p class="breadcrumbs">Home > Laporan > Berita Acara Pemantauan</p>
</div>

<div style="background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
    <p>
        <strong>Unit Kerja:</strong> {{ $unitKerja->nama_unit }}<br>
        <strong>Periode:</strong> {{ $tglAwal->format('d/m/Y') }} - {{ $tglAkhir->format('d/m/Y') }}
    </p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-bottom: 30px;">
    <div style="background: #e3f2fd; padding: 20px; border-radius: 5px; border-left: 4px solid #2196F3;">
        <h4 style="color: #1976D2; margin-bottom: 10px;">Saldo Awal</h4>
        <p style="font-size: 24px; font-weight: bold; color: #1976D2;">
            Rp {{ number_format($saldoAwal, 2, ',', '.') }}
        </p>
        <small style="color: #666;">Total nilai aset sebelum periode</small>
    </div>

    <div style="background: #e8f5e9; padding: 20px; border-radius: 5px; border-left: 4px solid #4CAF50;">
        <h4 style="color: #388E3C; margin-bottom: 10px;">Total Penerimaan</h4>
        <p style="font-size: 24px; font-weight: bold; color: #388E3C;">
            Rp {{ number_format($totalPenerimaan, 2, ',', '.') }}
        </p>
        <small style="color: #666;">Nilai penerimaan selama periode</small>
    </div>

    <div style="background: #ffebee; padding: 20px; border-radius: 5px; border-left: 4px solid #F44336;">
        <h4 style="color: #D32F2F; margin-bottom: 10px;">Total Pengurangan</h4>
        <p style="font-size: 24px; font-weight: bold; color: #D32F2F;">
            Rp {{ number_format($totalPengurangan, 2, ',', '.') }}
        </p>
        <small style="color: #666;">Nilai pengurangan selama periode</small>
    </div>

    <div style="background: #fff3e0; padding: 20px; border-radius: 5px; border-left: 4px solid #FF9800;">
        <h4 style="color: #E65100; margin-bottom: 10px;">Saldo Akhir</h4>
        <p style="font-size: 24px; font-weight: bold; color: #E65100;">
            Rp {{ number_format($saldoAkhir, 2, ',', '.') }}
        </p>
        <small style="color: #666;">Saldo Awal + Penerimaan - Pengurangan</small>
    </div>
</div>

<hr>

<div style="background: #f0f0f0; padding: 20px; border-radius: 5px; margin-top: 20px;">
    <h4 style="margin-bottom: 10px;">Catatan Penting:</h4>
    <ul style="margin-left: 20px;">
        <li>Laporan ini menampilkan agregat nilai aset untuk unit kerja {{ $unitKerja->nama_unit }}</li>
        <li>Saldo Awal adalah total nilai aset sebelum tanggal {{ $tglAwal->format('d/m/Y') }}</li>
        <li>Total Penerimaan hanya menghitung transaksi yang sudah disetujui</li>
        <li>Total Pengurangan hanya menghitung transaksi yang sudah disetujui</li>
        <li>Perhitungan: <strong>Saldo Akhir = Saldo Awal + Total Penerimaan - Total Pengurangan</strong></li>
    </ul>
</div>

<div style="margin-top: 20px;">
    <a href="{{ route('laporan.index') }}" class="btn btn-primary">Kembali ke Laporan</a>
    <button onclick="window.print()" class="btn btn-success" style="margin-left: 10px;">Print</button>
</div>
@endsection
