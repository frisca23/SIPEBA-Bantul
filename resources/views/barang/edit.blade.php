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
        <input type="text" name="nama_barang" id="nama_barang" value="{{ $barang->nama_barang }}" 
               list="barang-suggestions" required autocomplete="off">
        <datalist id="barang-suggestions">
            <!-- Akan terisi dengan JavaScript -->
        </datalist>
        <small style="color: #666; display: block; margin-top: 5px;">
            <i class="fas fa-info-circle"></i> Ketik untuk mencari barang dari daftar master
        </small>
    </div>

    <div class="form-group">
        <label for="satuan">Satuan:</label>
        <input type="text" name="satuan" id="satuan" value="{{ $barang->satuan }}" 
               list="satuan-suggestions" required>
        <datalist id="satuan-suggestions">
            <option value="BUAH">
            <option value="PACK">
            <option value="Dus">
            <option value="RIM">
            <option value="ROL">
            <option value="BUKU">
            <option value="Paket">
            <option value="Box">
            <option value="Botol">
            <option value="dsb">
        </datalist>
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

<script>
// Data master barang (sama seperti di create) - dikelompokkan berdasarkan jenis
const masterBarang = [
    // BAHAN KIMIA
    { kode: '11701010205', nama: 'pembersih kaca', satuan: 'BUAH', jenis: 'Bahan Kimia' },
    { kode: '11701010205', nama: 'pembersih keramik', satuan: 'BUAH', jenis: 'Bahan Kimia' },
    { kode: '11701010205', nama: 'pembersih lantai', satuan: 'BUAH', jenis: 'Bahan Kimia' },
    { kode: '11701010205', nama: 'pembersih toilet', satuan: 'BUAH', jenis: 'Bahan Kimia' },
    { kode: '11701010205', nama: 'pengharum kendaraan', satuan: 'BUAH', jenis: 'Bahan Kimia' },
    { kode: '11701010205', nama: 'pengharum ruangan', satuan: 'BUAH', jenis: 'Bahan Kimia' },
    { kode: '11701010205', nama: 'pengharum ruangan refil', satuan: 'BUAH', jenis: 'Bahan Kimia' },
    { kode: '11701010205', nama: 'pengharum ruangan gantung', satuan: 'BUAH', jenis: 'Bahan Kimia' },
    { kode: '11701010205', nama: 'sabun cuci piring', satuan: 'BUAH', jenis: 'Bahan Kimia' },
    { kode: '11701010205', nama: 'sabun mandi', satuan: 'BUAH', jenis: 'Bahan Kimia' },
    { kode: '11701010401', nama: 'BBM', satuan: 'Paket', jenis: 'Bahan Bakar dan Pelumas' },
    { kode: '11703010101', nama: 'ballpoint standar', satuan: 'Dus', jenis: 'Alat Tulis Kantor' },
    { kode: '11703010101', nama: 'Ballpoint boxi', satuan: 'BUAH', jenis: 'Alat Tulis Kantor' },
    { kode: '11703010103', nama: 'binder klip 105 kecil', satuan: 'Dus', jenis: 'Alat Tulis Kantor' },
    { kode: '11703010103', nama: 'binder klip 111', satuan: 'Dus', jenis: 'Alat Tulis Kantor' },
    { kode: '11703010103', nama: 'binder klip 260', satuan: 'Dus', jenis: 'Alat Tulis Kantor' },
    { kode: '11703010116', nama: 'flashdisk', satuan: 'BUAH', jenis: 'Alat Tulis Kantor' },
    { kode: '11703010112', nama: 'stapler', satuan: 'BUAH', jenis: 'Alat Tulis Kantor' },
    { kode: '11703010113', nama: 'isi stapler', satuan: 'Dus', jenis: 'Alat Tulis Kantor' },
    { kode: '11703010113', nama: 'Hechneicess/Isi Stapler', satuan: 'BUAH', jenis: 'Alat Tulis Kantor' },
    { kode: '11703010103', nama: 'paper clip', satuan: 'PACK', jenis: 'Alat Tulis Kantor' },
    { kode: '11703010106', nama: 'snelheckter', satuan: 'BUAH', jenis: 'Alat Tulis Kantor' },
    { kode: '11703020101', nama: 'hvs f4', satuan: 'RIM', jenis: 'Kertas dan Cover' },
    { kode: '11703020101', nama: 'kertas HVS f4 70 gr', satuan: 'RIM', jenis: 'Kertas dan Cover' },
    { kode: '11703020101', nama: 'hvs f4 70 gr', satuan: 'ONS', jenis: 'Kertas dan Cover' },
    { kode: '11703020101', nama: 'Kertas HVS F4 70 gr', satuan: 'RIM', jenis: 'Kertas dan Cover' },
    { kode: '11703020103', nama: 'Kertas Cover', satuan: 'PACK', jenis: 'Kertas dan Cover' },
    { kode: '11703020106', nama: 'stiknot besar', satuan: 'BUAH', jenis: 'Kertas dan Cover' },
    { kode: '11703020106', nama: 'stik not besar', satuan: 'BUAH', jenis: 'Kertas dan Cover' },
    { kode: '11703020106', nama: 'stiknot kecil', satuan: 'BUAH', jenis: 'Kertas dan Cover' },
    { kode: '11703020106', nama: 'stopmap 6000', satuan: 'BUAH', jenis: 'Kertas dan Cover' },
    { kode: '11703020106', nama: 'stopmap batik', satuan: 'BUAH', jenis: 'Kertas dan Cover' },
    { kode: '11703020106', nama: 'stopmap snelhekter', satuan: 'PACK', jenis: 'Kertas dan Cover' },
    { kode: '11703020106', nama: 'stopmap', satuan: 'PACK', jenis: 'Kertas dan Cover' },
    { kode: '11703030107', nama: 'Cetak Buku Perjanjian Kinerja', satuan: 'BUKU', jenis: 'Bahan Cetak' },
    { kode: '11703040101', nama: 'meterai', satuan: 'BUAH', jenis: 'Benda Pos' },
    { kode: '11703040101', nama: 'materai', satuan: 'BUAH', jenis: 'Benda Pos' },
    { kode: '11703060112', nama: 'key board', satuan: 'BUAH', jenis: 'Bahan Komputer' },
    { kode: '11703060112', nama: 'keyboard wireless', satuan: 'BUAH', jenis: 'Bahan Komputer' },
    { kode: '11703060110', nama: 'mouse wireless', satuan: 'BUAH', jenis: 'Bahan Komputer' },
    { kode: '11703060112', nama: 'refil toner', satuan: 'BUAH', jenis: 'Bahan Komputer' },
    { kode: '11703060112', nama: 'refil toner cartride', satuan: 'BUAH', jenis: 'Bahan Komputer' },
    { kode: '11703060104', nama: 'toner cartridge', satuan: 'BUAH', jenis: 'Bahan Komputer' },
    { kode: '11703070101', nama: 'sapu lantai', satuan: 'BUAH', jenis: 'Perabot Kantor' },
    { kode: '11703070115', nama: 'tissu kering', satuan: 'BUAH', jenis: 'Perabot Kantor' },
    { kode: '11703070115', nama: 'tisu kering', satuan: 'BUAH', jenis: 'Perabot Kantor' },
    { kode: '11703070115', nama: 'tisu wajah', satuan: 'BUAH', jenis: 'Perabot Kantor' },
    { kode: '11703070115', nama: 'tissu basah', satuan: 'BUAH', jenis: 'Perabot Kantor' },
    { kode: '11703070115', nama: 'tisu basah', satuan: 'BUAH', jenis: 'Perabot Kantor' },
    { kode: '11703070115', nama: 'tissu gulung', satuan: 'ROL', jenis: 'Perabot Kantor' },
    { kode: '11703070115', nama: 'tisu gulung', satuan: 'BUAH', jenis: 'Perabot Kantor' },
    { kode: '11703070102', nama: 'refill kain pel', satuan: 'BUAH', jenis: 'Perabot Kantor' },
    { kode: '11703070115', nama: 'tempat tissu', satuan: 'BUAH', jenis: 'Perabot Kantor' },
    { kode: '11703070115', nama: 'tempat tisu', satuan: 'BUAH', jenis: 'Perabot Kantor' },
    { kode: '11703070115', nama: 'tempat tissu meja', satuan: 'BUAH', jenis: 'Perabot Kantor' },
    { kode: '11703070115', nama: 'kanebo', satuan: 'BUAH', jenis: 'Perabot Kantor' },
    { kode: '11703080110', nama: 'alkaline a2', satuan: 'PACK', jenis: 'Alat Listrik' },
    { kode: '11703080110', nama: 'alkaline a 3', satuan: 'PACK', jenis: 'Alat Listrik' },
    { kode: '11703130101', nama: 'handuk wastafel', satuan: 'BUAH', jenis: 'Alat/Bahan untuk Kegiatan Kantor Lainnya' }
];

// Fungsi untuk update datalist berdasarkan jenis yang dipilih
function updateBarangList(selectedJenisNama) {
    const datalist = document.getElementById('barang-suggestions');
    datalist.innerHTML = ''; // Clear existing options
    
    if (!selectedJenisNama) {
        return;
    }
    
    // Filter barang berdasarkan jenis
    const filteredBarang = masterBarang.filter(item => 
        item.jenis.toLowerCase() === selectedJenisNama.toLowerCase()
    );
    
    // Populate datalist dengan barang yang sudah difilter
    filteredBarang.forEach(item => {
        const option = document.createElement('option');
        option.value = item.nama;
        datalist.appendChild(option);
    });
}

// Event listener untuk dropdown jenis barang
const jenisSelect = document.getElementById('jenis_id');
const namaBarangInput = document.getElementById('nama_barang');
const satuanInput = document.getElementById('satuan');

jenisSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const selectedJenisNama = selectedOption.text;
    
    // Update list barang sesuai jenis yang dipilih
    updateBarangList(selectedJenisNama);
});

// Auto-fill satuan ketika memilih dari daftar
namaBarangInput.addEventListener('input', function() {
    const selectedNama = this.value;
    const barang = masterBarang.find(item => item.nama.toLowerCase() === selectedNama.toLowerCase());
    
    if (barang) {
        satuanInput.value = barang.satuan;
        satuanInput.style.backgroundColor = '#e8f5e9';
        setTimeout(() => {
            satuanInput.style.backgroundColor = '';
        }, 1000);
    }
});

// Initialize - load barang list based on current jenis
if (jenisSelect.value) {
    const selectedOption = jenisSelect.options[jenisSelect.selectedIndex];
    updateBarangList(selectedOption.text);
}

</script>
@endsection
