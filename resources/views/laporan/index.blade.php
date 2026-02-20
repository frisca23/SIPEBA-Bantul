@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="page-header">
    <h2>Pusat Laporan</h2>
    <p class="breadcrumbs">Home > Laporan</p>
</div>

<div style="background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
    <h3>Pilih Jenis Laporan</h3>
    <p>Untuk mengakses laporan, Anda perlu memilih unit kerja dan rentang tanggal terlebih dahulu.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
    <!-- Buku Penerimaan -->
    <div style="border: 1px solid #ddd; padding: 20px; border-radius: 5px; background: #fafafa;">
        <h4 style="color: #003399; margin-bottom: 15px;">Buku Penerimaan</h4>
        <p style="color: #666; margin-bottom: 15px;">
            Laporan detail semua penerimaan barang yang sudah disetujui dalam periode tertentu.
        </p>
        <form action="{{ route('laporan.buku-penerimaan') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="tgl_awal_penerimaan">Tanggal Awal:</label>
                <input type="date" id="tgl_awal_penerimaan" name="tgl_awal" required>
            </div>
            <div class="form-group">
                <label for="tgl_akhir_penerimaan">Tanggal Akhir:</label>
                <input type="date" id="tgl_akhir_penerimaan" name="tgl_akhir" required>
            </div>
            <div class="form-group">
                <label for="unit_penerimaan">Unit Kerja:</label>
                <select name="unit_kerja_id" id="unit_penerimaan" required>
                    <option value="">-- Pilih Unit Kerja --</option>
                    @foreach(\App\Models\UnitKerja::all() as $unit)
                    <option value="{{ $unit->id }}" @selected(auth()->user()->unit_kerja_id === $unit->id)>
                        {{ $unit->nama_unit }}
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Lihat Laporan
            </button>
        </form>
    </div>

    <!-- Buku Pengurangan -->
    <div style="border: 1px solid #ddd; padding: 20px; border-radius: 5px; background: #fafafa;">
        <h4 style="color: #003399; margin-bottom: 15px;">Buku Pengurangan</h4>
        <p style="color: #666; margin-bottom: 15px;">
            Laporan detail semua pengurangan barang yang sudah disetujui dalam periode tertentu.
        </p>
        <form action="{{ route('laporan.buku-pengurangan') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="tgl_awal_pengurangan">Tanggal Awal:</label>
                <input type="date" id="tgl_awal_pengurangan" name="tgl_awal" required>
            </div>
            <div class="form-group">
                <label for="tgl_akhir_pengurangan">Tanggal Akhir:</label>
                <input type="date" id="tgl_akhir_pengurangan" name="tgl_akhir" required>
            </div>
            <div class="form-group">
                <label for="unit_pengurangan">Unit Kerja:</label>
                <select name="unit_kerja_id" id="unit_pengurangan" required>
                    <option value="">-- Pilih Unit Kerja --</option>
                    @foreach(\App\Models\UnitKerja::all() as $unit)
                    <option value="{{ $unit->id }}" @selected(auth()->user()->unit_kerja_id === $unit->id)>
                        {{ $unit->nama_unit }}
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Lihat Laporan
            </button>
        </form>
    </div>

    <!-- Hasil Fisik Stock Opname -->
    <div style="border: 1px solid #ddd; padding: 20px; border-radius: 5px; background: #fafafa;">
        <h4 style="color: #003399; margin-bottom: 15px;">Hasil Fisik Stock Opname</h4>
        <p style="color: #666; margin-bottom: 15px;">
            Laporan hasil stock opname dengan perbandingan stok sistem vs fisik gudang.
        </p>
        <form action="{{ route('laporan.hasil-fisik-stock-opname') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="tgl_awal_opname">Tanggal Awal:</label>
                <input type="date" id="tgl_awal_opname" name="tgl_awal" required>
            </div>
            <div class="form-group">
                <label for="tgl_akhir_opname">Tanggal Akhir:</label>
                <input type="date" id="tgl_akhir_opname" name="tgl_akhir" required>
            </div>
            <div class="form-group">
                <label for="unit_opname">Unit Kerja:</label>
                <select name="unit_kerja_id" id="unit_opname" required>
                    <option value="">-- Pilih Unit Kerja --</option>
                    @foreach(\App\Models\UnitKerja::all() as $unit)
                    <option value="{{ $unit->id }}" @selected(auth()->user()->unit_kerja_id === $unit->id)>
                        {{ $unit->nama_unit }}
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Lihat Laporan
            </button>
        </form>
    </div>

    <!-- Berita Acara Pemantauan -->
    <div style="border: 1px solid #ddd; padding: 20px; border-radius: 5px; background: #fafafa;">
        <h4 style="color: #003399; margin-bottom: 15px;">Berita Acara Pemantauan</h4>
        <p style="color: #666; margin-bottom: 15px;">
            Ringkasan 4 angka agregat: Saldo Awal, Total Penerimaan, Total Pengurangan, Saldo Akhir.
        </p>
        <form action="{{ route('laporan.berita-acara-pemantauan') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="tgl_awal_berita">Tanggal Awal:</label>
                <input type="date" id="tgl_awal_berita" name="tgl_awal" required>
            </div>
            <div class="form-group">
                <label for="tgl_akhir_berita">Tanggal Akhir:</label>
                <input type="date" id="tgl_akhir_berita" name="tgl_akhir" required>
            </div>
            <div class="form-group">
                <label for="unit_berita">Unit Kerja:</label>
                <select name="unit_kerja_id" id="unit_berita" required>
                    <option value="">-- Pilih Unit Kerja --</option>
                    @foreach(\App\Models\UnitKerja::all() as $unit)
                    <option value="{{ $unit->id }}" @selected(auth()->user()->unit_kerja_id === $unit->id)>
                        {{ $unit->nama_unit }}
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Lihat Laporan
            </button>
        </form>
    </div>

    <!-- Rekonsiliasi Persediaan -->
    <div style="border: 1px solid #ddd; padding: 20px; border-radius: 5px; background: #fafafa;">
        <h4 style="color: #003399; margin-bottom: 15px;">Rekonsiliasi Persediaan</h4>
        <p style="color: #666; margin-bottom: 15px;">
            Laporan nilai aset per jenis barang dengan perbandingan saldo awal dan akhir.
        </p>
        <form action="{{ route('laporan.rekonsiliasi') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="tgl_awal_rekon">Tanggal Awal:</label>
                <input type="date" id="tgl_awal_rekon" name="tgl_awal" required>
            </div>
            <div class="form-group">
                <label for="tgl_akhir_rekon">Tanggal Akhir:</label>
                <input type="date" id="tgl_akhir_rekon" name="tgl_akhir" required>
            </div>
            <div class="form-group">
                <label for="unit_rekon">Unit Kerja:</label>
                <select name="unit_kerja_id" id="unit_rekon" required>
                    <option value="">-- Pilih Unit Kerja --</option>
                    @foreach(\App\Models\UnitKerja::all() as $unit)
                    <option value="{{ $unit->id }}" @selected(auth()->user()->unit_kerja_id === $unit->id)>
                        {{ $unit->nama_unit }}
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Lihat Laporan
            </button>
        </form>
    </div>
</div>
@endsection
