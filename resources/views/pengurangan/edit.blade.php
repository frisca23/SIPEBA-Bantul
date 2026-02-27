@extends('layouts.app')

@section('title', 'Edit Pengurangan')

@section('content')
<div class="page-header">
    <h2>Edit Pengurangan</h2>
    <p class="breadcrumbs">Home > Pengurangan > Edit > {{ $pengurangan->no_bukti }}</p>
</div>

<form method="POST" action="{{ route('pengurangan.update', $pengurangan) }}" style="max-width: 800px; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.05);">
    @csrf
    @method('PUT')

    <h3 style="color: #003399; margin-top: 10px; margin-bottom: 20px; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px;">Header Pengurangan</h3>

    <div class="form-group" style="margin-bottom: 15px;">
        <label for="no_bukti" style="font-weight: bold;">No Bukti:</label>
        <input type="text" name="no_bukti" id="no_bukti" value="{{ $pengurangan->no_bukti }}" disabled style="width: 100%; padding: 8px; background: #f8f9fa; border: 1px solid #ccc; border-radius: 4px;">
        <small style="color: #999;">Nomor bukti tidak dapat diubah</small>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
        <div class="form-group">
            <label for="tgl_keluar" style="font-weight: bold;">Tanggal Pengeluaran Barang:</label>
            <input type="date" name="tgl_keluar" id="tgl_keluar" value="{{ $pengurangan->tgl_keluar->format('Y-m-d') }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <div class="form-group">
            <label for="tgl_serah" style="font-weight: bold;">Tanggal Penyerahan Barang:</label>
            <input type="date" name="tgl_serah" id="tgl_serah" value="{{ $pengurangan->tgl_serah ? $pengurangan->tgl_serah->format('Y-m-d') : '' }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
    </div>

    <div class="form-group" style="margin-bottom: 25px;">
        <label for="keperluan" style="font-weight: bold;">Keterangan / Keperluan:</label>
        <textarea name="keperluan" id="keperluan" rows="3" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">{{ $pengurangan->keperluan }}</textarea>
    </div>

    <h3 style="color: #003399; margin-bottom: 15px; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px;">Detail Barang</h3>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Barang</th>
                <th style="padding: 12px; text-align: center; border-bottom: 2px solid #ddd; width: 120px;">Stok Tersedia</th>
                <th style="padding: 12px; text-align: center; border-bottom: 2px solid #ddd; width: 120px;">Jumlah Keluar</th>
            </tr>
        </thead>
        <tbody id="detail-body">
            @foreach($pengurangan->detail as $index => $detail)
            <tr class="detail-row">
                <td style="padding: 12px; border-bottom: 1px solid #eee;">
                    <select name="detail[{{ $index }}][barang_id]" class="barang-select" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        @foreach($barang as $b)
                        <option value="{{ $b->id }}" data-stok="{{ $b->stok_saat_ini }}" @selected($detail->barang_id == $b->id)>
                            {{ $b->nama_barang }} ({{ $b->satuan }})
                        </option>
                        @endforeach
                    </select>
                </td>
                <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: center;">
                    <span class="stok-tersedia" style="font-weight: bold; color: #666;">{{ $detail->barang->stok_saat_ini }}</span>
                </td>
                <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: center;">
                    <input type="number" name="detail[{{ $index }}][jumlah_kurang]" value="{{ $detail->jumlah_kurang }}" min="1" class="jumlah-input" required style="width: 100px; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; display: flex; gap: 10px;">
        <button type="submit" class="btn btn-success" style="padding: 10px 20px;">Simpan Perubahan</button>
        <a href="{{ route('pengurangan.show', $pengurangan) }}" class="btn" style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Batal</a>
    </div>
</form>

<script type="application/json" id="barangData">
@json($barang->mapWithKeys(fn($b) => [$b->id => ['stok' => $b->stok_saat_ini]])->toArray())
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const barangData = JSON.parse(document.getElementById('barangData').textContent);

    function updateStok(selectElement) {
        const barangId = selectElement.value;
        const stokCell = selectElement.closest('tr').querySelector('.stok-tersedia');
        if (barangData[barangId]) {
            stokCell.textContent = barangData[barangId].stok;
        }
    }

    document.querySelectorAll('.barang-select').forEach(select => {
        select.addEventListener('change', function() {
            updateStok(this);
        });
    });
});
</script>
@endsection
