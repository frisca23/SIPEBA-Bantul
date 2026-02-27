@extends('layouts.app')

@section('title', 'Edit Penerimaan')

@section('content')
<div class="page-header">
    <h2>Edit Penerimaan</h2>
    <p class="breadcrumbs">Home > Penerimaan > Edit > {{ $penerimaan->no_dokumen }}</p>
</div>

<form method="POST" action="{{ route('penerimaan.update', $penerimaan) }}" style="max-width: 800px;">
    @csrf
    @method('PUT')

    <h3 style="color: #003399; margin-top: 20px; margin-bottom: 15px;">Header Penerimaan</h3>

    <div class="form-group">
        <label for="no_dokumen">No Dokumen:</label>
        <input type="text" name="no_dokumen" id="no_dokumen" value="{{ $penerimaan->no_dokumen }}" disabled>
        <small style="color: #999;">Nomor dokumen tidak dapat diubah</small>
    </div>

    <div class="form-group">
        <label for="tgl_dokumen">Tanggal Dokumen:</label>
        <input type="date" name="tgl_dokumen" id="tgl_dokumen" value="{{ $penerimaan->tgl_dokumen->format('Y-m-d') }}" required>
    </div>

    <div class="form-group">
        <label for="sumber_dana">Sumber Dana:</label>
        <input type="text" name="sumber_dana" id="sumber_dana" value="{{ $penerimaan->sumber_dana }}" required>
    </div>

    <div class="form-group">
        <label for="tahun_anggaran">Tahun Anggaran:</label>
        <input type="number" name="tahun_anggaran" id="tahun_anggaran" value="{{ $penerimaan->tahun_anggaran }}" required>
    </div>

    <div class="form-group">
        <label for="keterangan">Keterangan:</label>
        <textarea name="keterangan" id="keterangan" rows="3">{{ $penerimaan->keterangan }}</textarea>
    </div>

    <hr style="margin: 25px 0;">

    <h3 style="color: #003399; margin-bottom: 15px;">Detail Barang</h3>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Barang</th>
                <th style="padding: 10px; text-align: center; border-bottom: 2px solid #ddd; width: 100px;">Jumlah</th>
                <th style="padding: 10px; text-align: right; border-bottom: 2px solid #ddd; width: 120px;">Harga Satuan</th>
                <th style="padding: 10px; text-align: right; border-bottom: 2px solid #ddd; width: 120px;">Total</th>
            </tr>
        </thead>
        <tbody id="detail-body">
            @foreach($penerimaan->detail as $index => $detail)
            <tr class="detail-row">
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                    <select name="detail[{{ $index }}][barang_id]" class="barang-select" required>
                        @foreach($barang as $b)
                        <option value="{{ $b->id }}" @selected($detail->barang_id == $b->id)>
                            {{ $b->nama_barang }} ({{ $b->satuan }})
                        </option>
                        @endforeach
                    </select>
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: center;">
                    <input type="number" name="detail[{{ $index }}][jumlah_masuk]" value="{{ $detail->jumlah_masuk }}" min="1" class="jumlah-input" required style="width: 80px;">
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right;">
                    <input type="number" name="detail[{{ $index }}][harga_satuan]" value="{{ $detail->harga_satuan }}" min="0" step="0.01" class="harga-input" required style="width: 100px;">
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right;">
                    <span class="total-item">0</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr style="margin: 25px 0;">

    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <p><strong>Total Nilai Penerimaan:</strong></p>
        <p style="font-size: 20px; font-weight: bold; color: #003399;" id="grand-total">Rp 0</p>
    </div>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="{{ route('penerimaan.show', $penerimaan) }}" class="btn btn-primary">Batal</a>
    </div>
</form>

<script>
function updateTotal() {
    let grand = 0;
    document.querySelectorAll('.detail-row').forEach(row => {
        const jumlah = parseFloat(row.querySelector('.jumlah-input').value) || 0;
        const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
        const total = jumlah * harga;
        row.querySelector('.total-item').textContent = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(total);
        grand += total;
    });
    document.getElementById('grand-total').textContent = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(grand);
}

function checkDuplicateBarang(row) {
    const selectedBarangId = row.querySelector('.barang-select').value;
    const selectedBarangText = row.querySelector('.barang-select').options[row.querySelector('.barang-select').selectedIndex].text;
    
    if (!selectedBarangId) return; // Skip jika belum memilih
    
    let duplicateCount = 0;
    document.querySelectorAll('.detail-row').forEach(otherRow => {
        if (otherRow !== row && otherRow.querySelector('.barang-select').value === selectedBarangId) {
            duplicateCount++;
        }
    });
    
    if (duplicateCount > 0) {
        alert('Barang "' + selectedBarangText + '" sudah ada dalam daftar. Setiap barang hanya boleh ditambahkan sekali!');
        row.querySelector('.barang-select').value = '';
    }
}

document.querySelectorAll('.barang-select').forEach(select => {
    select.addEventListener('change', function() {
        checkDuplicateBarang(select.closest('.detail-row'));
        updateTotal();
    });
});

document.querySelectorAll('.jumlah-input, .harga-input').forEach(input => {
    input.addEventListener('input', updateTotal);
});

updateTotal();
</script>
@endsection
