@extends('layouts.app')

@section('title', 'Edit Stock Opname')

@section('content')
<div class="page-header">
    <h2>Edit Stock Opname</h2>
    <p class="breadcrumbs">Home > Stock Opname > Edit</p>
</div>

@if($errors->any())
<div class="alert alert-danger" style="margin-bottom: 18px;">
    <strong>Terjadi Kesalahan:</strong>
    <ul style="margin-top: 6px; padding-left: 16px;">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('stock-opname.update', $stockOpname) }}" style="max-width: 700px;">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Barang:</label>
        <div style="padding: 10px 12px; border: 1.5px solid var(--border); border-radius: 8px; background: var(--card-bg);">
            {{ $stockOpname->barang->nama_barang }} ({{ $stockOpname->barang->satuan }})
        </div>
    </div>

    <div class="form-group">
        <label>Stok di Aplikasi:</label>
        <div style="padding: 10px 12px; border: 1.5px solid var(--border); border-radius: 8px; background: var(--card-bg);">
            {{ $stockOpname->stok_di_aplikasi }}
        </div>
    </div>

    <div class="form-group">
        <label for="tgl_opname">Tanggal Opname:</label>
        <input type="date" id="tgl_opname" name="tgl_opname" value="{{ old('tgl_opname', $stockOpname->tgl_opname->format('Y-m-d')) }}" required>
    </div>

    <div class="form-group">
        <label for="stok_fisik_gudang">Stok Fisik Gudang:</label>
        <input type="number" id="stok_fisik_gudang" name="stok_fisik_gudang" min="0" value="{{ old('stok_fisik_gudang', $stockOpname->stok_fisik_gudang) }}" required>
    </div>

    <div class="form-group">
        <label for="keterangan">Keterangan (Opsional):</label>
        <textarea id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $stockOpname->keterangan) }}</textarea>
    </div>

    <div style="display: flex; gap: 10px;">
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('stock-opname.show', $stockOpname) }}" class="btn btn-primary">Batal</a>
    </div>
</form>
@endsection
