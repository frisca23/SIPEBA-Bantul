@extends('layouts.app')

@section('title', 'Buat Penerimaan')

@section('content')
<div class="page-header">
    <h2>Buat Penerimaan Barang Baru</h2>
    <p class="breadcrumbs">Home > Penerimaan > Buat Baru</p>
</div>

<form method="POST" action="{{ route('penerimaan.store') }}" style="max-width: 800px;">
    @csrf

    <h3 style="color: #003399; margin-top: 20px; margin-bottom: 15px;">Header Penerimaan</h3>

    <div class="form-group">
        <label for="no_dokumen">No Dokumen:</label>
        <input type="text" name="no_dokumen" id="no_dokumen" value="{{ old('no_dokumen') }}" required>
    </div>

    <div class="form-group">
        <label for="tgl_dokumen">Tanggal Dokumen:</label>
        <input type="date" name="tgl_dokumen" id="tgl_dokumen" value="{{ old('tgl_dokumen', date('Y-m-d')) }}" required>
    </div>

    <div class="form-group">
        <label for="sumber_dana">Sumber Dana:</label>
        <input type="text" name="sumber_dana" id="sumber_dana" value="{{ old('sumber_dana') }}" placeholder="APBD, DAK, dsb" required>
    </div>

    <div class="form-group">
        <label for="tahun_anggaran">Tahun Anggaran:</label>
        <input type="number" name="tahun_anggaran" id="tahun_anggaran" value="{{ old('tahun_anggaran', date('Y')) }}" min="2000" max="2099" required>
    </div>

    <div class="form-group">
        <label for="keterangan">Keterangan:</label>
        <textarea name="keterangan" id="keterangan" rows="3">{{ old('keterangan') }}</textarea>
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
            @if(old('detail'))
                @foreach(old('detail') as $index => $detail)
                <tr class="detail-row">
                    <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                        <select name="detail[{{ $index }}][barang_id]" class="barang-select" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barang as $b)
                            <option value="{{ $b->id }}" @selected($detail['barang_id'] == $b->id)>
                                {{ $b->nama_barang }} ({{ $b->satuan }})
                            </option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: center;">
                        <input type="number" name="detail[{{ $index }}][jumlah_masuk]" value="{{ $detail['jumlah_masuk'] }}" min="1" class="jumlah-input" required style="width: 80px;">
                    </td>
                    <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right;">
                        <input type="number" name="detail[{{ $index }}][harga_satuan]" value="{{ $detail['harga_satuan'] }}" min="0" step="0.01" class="harga-input" required style="width: 100px;">
                    </td>
                    <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right;">
                        <span class="total-item">0</span>
                    </td>
                </tr>
                @endforeach
            @else
            <tr class="detail-row">
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                    <select name="detail[0][barang_id]" class="barang-select" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barang as $b)
                        <option value="{{ $b->id }}">
                            {{ $b->nama_barang }} ({{ $b->satuan }})
                        </option>
                        @endforeach
                    </select>
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: center;">
                    <input type="number" name="detail[0][jumlah_masuk]" min="1" class="jumlah-input" required style="width: 80px;">
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right;">
                    <input type="number" name="detail[0][harga_satuan]" min="0" step="0.01" class="harga-input" required style="width: 100px;">
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right;">
                    <span class="total-item">0</span>
                </td>
            </tr>
            @endif
        </tbody>
    </table>

    <div style="margin-top: 15px;">
        <button type="button" class="btn btn-primary btn-sm" id="add-row">+ Tambah Baris</button>
    </div>

    <hr style="margin: 25px 0;">

    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <p><strong>Total Nilai Penerimaan:</strong></p>
        <p style="font-size: 20px; font-weight: bold; color: #003399;" id="grand-total">Rp 0</p>
    </div>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn btn-success">Simpan Penerimaan</button>
        <a href="{{ route('penerimaan.index') }}" class="btn btn-primary">Batal</a>
    </div>
</form>

<script>
let rowCount = document.querySelectorAll('.detail-row').length;

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

document.getElementById('add-row').addEventListener('click', function() {
    const tbody = document.getElementById('detail-body');
    const newRow = document.createElement('tr');
    newRow.className = 'detail-row';
    newRow.innerHTML = `
        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
            <select name="detail[${rowCount}][barang_id]" class="barang-select" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($barang as $b)
                <option value="{{ $b->id }}">{{ $b->nama_barang }} ({{ $b->satuan }})</option>
                @endforeach
            </select>
        </td>
        <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: center;">
            <input type="number" name="detail[${rowCount}][jumlah_masuk]" min="1" class="jumlah-input" required style="width: 80px;">
        </td>
        <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right;">
            <input type="number" name="detail[${rowCount}][harga_satuan]" min="0" step="0.01" class="harga-input" required style="width: 100px;">
        </td>
        <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right;">
            <span class="total-item">0</span>
        </td>
    `;
    tbody.appendChild(newRow);
    rowCount++;
    
    newRow.querySelector('.jumlah-input').addEventListener('input', updateTotal);
    newRow.querySelector('.harga-input').addEventListener('input', updateTotal);
});

document.querySelectorAll('.jumlah-input, .harga-input').forEach(input => {
    input.addEventListener('input', updateTotal);
});

updateTotal();
</script>
@endsection
