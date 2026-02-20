@extends('layouts.app')

@section('title', 'Edit Pengurangan')

@section('content')
<div class="page-header">
    <h2>Edit Pengurangan</h2>
    <p class="breadcrumbs">Home > Pengurangan > Edit > {{ $pengurangan->no_bukti }}</p>
</div>

<form method="POST" action="{{ route('pengurangan.update', $pengurangan) }}" style="max-width: 800px;">
    @csrf
    @method('PUT')

    <h3 style="color: #003399; margin-top: 20px; margin-bottom: 15px;">Header Pengurangan</h3>

    <div class="form-group">
        <label for="no_bukti">No Bukti:</label>
        <input type="text" name="no_bukti" id="no_bukti" value="{{ $pengurangan->no_bukti }}" disabled>
        <small style="color: #999;">Nomor bukti tidak dapat diubah</small>
    </div>

    <div class="form-group">
        <label for="tgl_keluar">Tanggal Keluar:</label>
        <input type="date" name="tgl_keluar" id="tgl_keluar" value="{{ $pengurangan->tgl_keluar->format('Y-m-d') }}" required>
    </div>

    <div class="form-group">
        <label for="keperluan">Keperluan:</label>
        <textarea name="keperluan" id="keperluan" rows="3" required>{{ $pengurangan->keperluan }}</textarea>
    </div>

    <hr style="margin: 25px 0;">

    <h3 style="color: #003399; margin-bottom: 15px;">Detail Barang</h3>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Barang</th>
                <th style="padding: 10px; text-align: center; border-bottom: 2px solid #ddd; width: 100px;">Stok Tersedia</th>
                <th style="padding: 10px; text-align: center; border-bottom: 2px solid #ddd; width: 100px;">Jumlah Keluar</th>
            </tr>
        </thead>
        <tbody id="detail-body">
            @foreach($pengurangan->detail as $index => $detail)
            <tr class="detail-row">
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                    <select name="detail[{{ $index }}][barang_id]" class="barang-select" required>
                        @foreach($barang as $b)
                        <option value="{{ $b->id }}" data-stok="{{ $b->stok_saat_ini }}" @selected($detail->barang_id == $b->id)>
                            {{ $b->nama_barang }} ({{ $b->satuan }})
                        </option>
                        @endforeach
                    </select>
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: center;">
                    <span class="stok-tersedia">{{ $detail->barang->stok_saat_ini }}</span>
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: center;">
                    <input type="number" name="detail[{{ $index }}][jumlah_kurang]" value="{{ $detail->jumlah_kurang }}" min="1" class="jumlah-input" required style="width: 80px;">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr style="margin: 25px 0;">

    <div style="margin-top: 20px;">
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="{{ route('pengurangan.show', $pengurangan) }}" class="btn btn-primary">Batal</a>
    </div>
</form>

<script type="application/json" id="barangData">
@json($barang->mapWithKeys(fn($b) => [$b->id => ['stok' => $b->stok_saat_ini]])->toArray())
</script>
<script>
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
</script>
@endsection
