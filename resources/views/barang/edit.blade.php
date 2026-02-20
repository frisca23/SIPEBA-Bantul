@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<div class="page-header">
    <h2>Edit Barang</h2>
    <p class="breadcrumbs">Home > Barang > Edit > {{ $barang->nama_barang }}</p>
</div>

<form method="POST" action="{{ route('barang.update', $barang) }}" style="max-width: 600px;">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="jenis_id">Jenis Barang:</label>
        <select name="jenis_id" id="jenis_id" required>
            @foreach($jenisBarang as $jenis)
            <option value="{{ $jenis->id }}" @selected($barang->jenis_id === $jenis->id)>
                {{ $jenis->nama_jenis }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="kode_barang">Kode Barang:</label>
        <input type="text" name="kode_barang" id="kode_barang" value="{{ $barang->kode_barang }}" disabled>
        <small style="color: #999;">Kode barang tidak dapat diubah</small>
    </div>

    <div class="form-group">
        <label for="nama_barang">Nama Barang:</label>
        <input type="text" name="nama_barang" id="nama_barang" value="{{ $barang->nama_barang }}" required>
    </div>

    <div class="form-group">
        <label for="satuan">Satuan:</label>
        <input type="text" name="satuan" id="satuan" value="{{ $barang->satuan }}" required>
    </div>

    <div style="background: #f0f0f0; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
        <p><strong>Stok Saat Ini:</strong> {{ $barang->stok_saat_ini }}</p>
        <p><strong>Harga Terakhir:</strong> Rp {{ number_format($barang->harga_terakhir, 2, ',', '.') }}</p>
        <small style="color: #666;">Stok dan harga diperbarui melalui transaksi penerimaan/pengurangan</small>
    </div>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="{{ route('barang.show', $barang) }}" class="btn btn-primary">Batal</a>
    </div>
</form>
@endsection
