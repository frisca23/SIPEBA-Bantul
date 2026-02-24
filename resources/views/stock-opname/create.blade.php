@extends('layouts.app')

@section('title', 'Buat Stock Opname')

@section('content')
<div class="page-header">
    <h2>Buat Stock Opname</h2>
    <p class="breadcrumbs">Home > Stock Opname > Buat</p>
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

<form method="POST" action="{{ route('stock-opname.store') }}" style="max-width: 700px;">
    @csrf

    <div class="form-group">
        <label for="tgl_opname">Tanggal Opname:</label>
        <input type="date" id="tgl_opname" name="tgl_opname" value="{{ old('tgl_opname') }}" required>
    </div>

    <div class="form-group">
        <label for="barang_id">Barang:</label>
        <select id="barang_id" name="barang_id" required>
            <option value="">-- Pilih Barang --</option>
            @foreach($barang as $item)
                <option value="{{ $item->id }}" data-stok="{{ $item->stok_saat_ini }}" @selected(old('barang_id') == $item->id)>
                    {{ $item->nama_barang }} ({{ $item->satuan }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Stok di Aplikasi:</label>
        <div id="stok-aplikasi" style="padding: 10px 12px; border: 1.5px solid var(--border); border-radius: 8px; background: var(--card-bg);">
            -
        </div>
        <small style="color: #6b7280;">Nilai ini diambil dari stok terakhir pada sistem saat dibuat.</small>
    </div>

    <div class="form-group">
        <label for="stok_fisik_gudang">Stok Fisik Gudang:</label>
        <input type="number" id="stok_fisik_gudang" name="stok_fisik_gudang" min="0" value="{{ old('stok_fisik_gudang') }}" required>
    </div>

    <div class="form-group">
        <label for="keterangan">Keterangan (Opsional):</label>
        <textarea id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
    </div>

    <div style="display: flex; gap: 10px;">
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('stock-opname.index') }}" class="btn btn-primary">Batal</a>
    </div>
</form>

<script>
    const barangSelect = document.getElementById('barang_id');
    const stokLabel = document.getElementById('stok-aplikasi');

    const updateStok = () => {
        const selected = barangSelect.options[barangSelect.selectedIndex];
        const stok = selected ? selected.getAttribute('data-stok') : null;
        stokLabel.textContent = stok !== null && stok !== '' ? stok : '-';
    };

    barangSelect.addEventListener('change', updateStok);
    updateStok();
</script>
@endsection
