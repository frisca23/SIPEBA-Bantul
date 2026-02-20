@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<div class="page-header">
    <h2>Tambah Barang Baru</h2>
    <p class="breadcrumbs">Home > Barang > Tambah</p>
</div>

<form method="POST" action="{{ route('barang.store') }}" style="max-width: 600px;">
    @csrf

    <div class="form-group">
        <label for="jenis_id">Jenis Barang:</label>
        <select name="jenis_id" id="jenis_id" required>
            <option value="">-- Pilih Jenis --</option>
            @foreach($jenisBarang as $jenis)
            <option value="{{ $jenis->id }}" @selected(old('jenis_id') == $jenis->id)>
                {{ $jenis->nama_jenis }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="kode_barang">Kode Barang:</label>
        <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang') }}" required>
        <small style="color: #999;">Contoh: ATK-001, KIMIA-001</small>
    </div>

    <div class="form-group">
        <label for="nama_barang">Nama Barang:</label>
        <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang') }}" required>
    </div>

    <div class="form-group">
        <label for="satuan">Satuan:</label>
        <input type="text" name="satuan" id="satuan" value="{{ old('satuan') }}" placeholder="Pcs, Box, Botol, dsb" required>
    </div>

    <div style="background: #f0f0f0; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
        <p><strong>Unit Kerja:</strong> {{ $unitKerja->nama_unit }}</p>
        <small style="color: #666;">Barang akan dibuat untuk unit kerja Anda sendiri</small>
    </div>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('barang.index') }}" class="btn btn-primary">Batal</a>
    </div>
</form>
@endsection
