@extends('layouts.app')

@section('title', 'Tambah Jenis Barang')

@section('content')
<div class="page-header">
    <h2>Tambah Jenis Barang Baru</h2>
    <p class="breadcrumbs">Home > Jenis Barang > Tambah</p>
</div>

<form method="POST" action="{{ route('jenis-barang.store') }}" style="max-width: 600px;">
    @csrf

    <div class="form-group">
        <label for="kode_jenis">Kode Jenis Barang (6 digit):</label>
        <input type="text" name="kode_jenis" id="kode_jenis" value="{{ old('kode_jenis') }}" maxlength="6" placeholder="Contoh: 117011, 117031, 117032" required>
        <small style="color: #6c757d; display: block; margin-top: 5px;">
            <i class="fas fa-info-circle"></i> Masukkan 6 digit kode sesuai klasifikasi barang (117011=Bahan Kimia, 117031=ATK, 117032=Kertas, dll)
        </small>
        @error('kode_jenis')
            <span style="color: #dc3545; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="nama_jenis">Nama Jenis Barang:</label>
        <input type="text" name="nama_jenis" id="nama_jenis" value="{{ old('nama_jenis') }}" required autofocus>
        @error('nama_jenis')
            <span style="color: #dc3545; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn btn-success">Simpan Jenis Barang</button>
        <a href="{{ route('jenis-barang.index') }}" class="btn btn-primary">Batal</a>
    </div>
</form>
@endsection
