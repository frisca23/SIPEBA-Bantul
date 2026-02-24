@extends('layouts.app')

@section('title', 'Buat Pengurangan')

@section('content')
<div class="page-header">
    <h2>Buat Pengurangan Barang</h2>
    <p class="breadcrumbs">Home > Pengurangan > Buat Baru</p>
</div>

<form method="POST" action="{{ route('pengurangan.store') }}" style="max-width: 800px;">
    @csrf

    <h3 style="color: #003399; margin-top: 20px; margin-bottom: 15px;">Header Pengurangan</h3>

    <div class="form-group">
        <label for="tgl_keluar">Tanggal Keluar:</label>
        <input type="date" name="tgl_keluar" id="tgl_keluar" value="{{ old('tgl_keluar', date('Y-m-d')) }}" required>
    </div>

    <div class="form-group">
        <label for="keperluan">Keperluan:</label>
        <textarea name="keperluan" id="keperluan" rows="3" required>{{ old('keperluan') }}</textarea>
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
                        <span class="stok-tersedia">-</span>
                    </td>
                    <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: center;">
                        <input type="number" name="detail[{{ $index }}][jumlah_kurang]" value="{{ $detail['jumlah_kurang'] }}" min="1" class="jumlah-input" required style="width: 80px;">
                    </td>
                </tr>
                @endforeach
            @else
            <tr class="detail-row">
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                    <select name="detail[0][barang_id]" class="barang-select" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barang as $b)
                        <option value="{{ $b->id }}" data-stok="{{ $b->stok_saat_ini }}">
                            {{ $b->nama_barang }} ({{ $b->satuan }})
                        </option>
                        @endforeach
                    </select>
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: center;">
                    <span class="stok-tersedia">-</span>
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: center;">
                    <input type="number" name="detail[0][jumlah_kurang]" min="1" class="jumlah-input" required style="width: 80px;">
                </td>
            </tr>
            @endif
        </tbody>
    </table>

    <div style="margin-top: 15px;">
        <button type="button" class="btn btn-primary btn-sm" id="add-row">+ Tambah Baris</button>
    </div>

    <hr style="margin: 25px 0;">

    <div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <strong>⚠️ Penting:</strong> Saat pengurangan disetujui, sistem akan mengecek stok. Jika stok tidak cukup, pengurangan akan ditolak.
    </div>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn btn-success">Simpan Pengurangan</button>
        <a href="{{ route('pengurangan.index') }}" class="btn btn-primary">Batal</a>
    </div>
</form>

<script type="application/json" id="barangData">
@json($barang->mapWithKeys(fn($b) => [$b->id => ['name' => $b->nama_barang, 'stok' => $b->stok_saat_ini, 'satuan' => $b->satuan]])->toArray())
</script>
<script>
let rowCount = document.querySelectorAll('.detail-row').length;
const barangData = JSON.parse(document.getElementById('barangData').textContent);

const barangOptions = `
    <option value="">-- Pilih Barang --</option>
    {!! $barang->map(fn($b) => '<option value="' . $b->id . '" data-stok="' . $b->stok_saat_ini . '">' . $b->nama_barang . ' (' . $b->satuan . ')</option>')->implode('') !!}
`;

function updateStok(selectElement) {
    const barangId = selectElement.value;
    const stokCell = selectElement.closest('tr').querySelector('.stok-tersedia');
    if (barangData[barangId]) {
        stokCell.textContent = barangData[barangId].stok + ' ' + barangData[barangId].satuan;
    } else {
        stokCell.textContent = '-';
    }
}

document.getElementById('add-row').addEventListener('click', function() {
    const tbody = document.getElementById('detail-body');
    const newRow = document.createElement('tr');
    newRow.className = 'detail-row';
    newRow.innerHTML = `
        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
            <select name="detail[${rowCount}][barang_id]" class="barang-select" required>
                ${barangOptions}
            </select>
        </td>
        <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: center;">
            <span class="stok-tersedia">-</span>
        </td>
        <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: center;">
            <input type="number" name="detail[${rowCount}][jumlah_kurang]" min="1" class="jumlah-input" required style="width: 80px;">
        </td>
    `;
    tbody.appendChild(newRow);
    rowCount++;
    
    newRow.querySelector('.barang-select').addEventListener('change', function() {
        updateStok(this);
    });
});

document.querySelectorAll('.barang-select').forEach(select => {
    select.addEventListener('change', function() {
        updateStok(this);
    });
    if (select.value) {
        updateStok(select);
    }
});
</script>
@endsection
