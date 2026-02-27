@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<div class="page-header">
    <h2>Tambah Barang Baru</h2>
    <p class="breadcrumbs">Home > Barang > Tambah</p>
</div>

<form method="POST" action="{{ route('barang.store') }}" style="max-width: 600px;">
    @csrf

    <div class="form-group">
        <label for="jenis_id">Jenis Barang:</label>
        <select name="jenis_id" id="jenis_id" required>
            <option value="">-- Pilih Jenis --</option>
            @foreach($jenisBarang as $jenis)
            <option value="{{ $jenis->id }}" @selected(old('jenis_id') == $jenis->id)>
                {{ $jenis->nama_jenis }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="nama_barang">Nama Barang:</label>
        <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang') }}" 
               list="barang-suggestions" required autocomplete="off"
               placeholder="Ketik atau pilih nama barang...">
        <datalist id="barang-suggestions"></datalist>
        <small style="color: #666; display: block; margin-top: 5px;">
            <i class="fas fa-info-circle"></i> Ketik untuk mencari barang atau pilih dari daftar
        </small>
    </div>

    <div class="form-group">
        <label for="kode_barang">Kode Barang:</label>
        <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang') }}" required>
        <small style="color: #999;">Akan terisi otomatis saat memilih nama barang dari daftar</small>
    </div>

    <div class="form-group">
        <label for="satuan">Satuan:</label>
        <input type="text" name="satuan" id="satuan" value="{{ old('satuan') }}" 
               list="satuan-suggestions" placeholder="Pcs, Box, Botol, dsb" required>
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
        <p><strong>Unit Kerja:</strong> {{ $unitKerja->nama_unit }}</p>
        <small style="color: #666;">Barang akan dibuat untuk unit kerja Anda sendiri</small>
    </div>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('barang.index') }}" class="btn btn-primary">Batal</a>
    </div>
</form>

<script>
// Data master barang dari file CSV - dikelompokkan berdasarkan jenis
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
    
    // BAHAN BAKAR DAN PELUMAS
    { kode: '11701010401', nama: 'BBM', satuan: 'Paket', jenis: 'Bahan Bakar dan Pelumas' },
    
    // ALAT TULIS KANTOR
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
    
    // KERTAS DAN COVER
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
    
    // BAHAN CETAK
    { kode: '11703030107', nama: 'Cetak Buku Perjanjian Kinerja', satuan: 'BUKU', jenis: 'Bahan Cetak' },
    
    // BENDA POS
    { kode: '11703040101', nama: 'meterai', satuan: 'BUAH', jenis: 'Benda Pos' },
    { kode: '11703040101', nama: 'materai', satuan: 'BUAH', jenis: 'Benda Pos' },
    
    // BAHAN KOMPUTER
    { kode: '11703060112', nama: 'key board', satuan: 'BUAH', jenis: 'Bahan Komputer' },
    { kode: '11703060112', nama: 'keyboard wireless', satuan: 'BUAH', jenis: 'Bahan Komputer' },
    { kode: '11703060110', nama: 'mouse wireless', satuan: 'BUAH', jenis: 'Bahan Komputer' },
    { kode: '11703060112', nama: 'refil toner', satuan: 'BUAH', jenis: 'Bahan Komputer' },
    { kode: '11703060112', nama: 'refil toner cartride', satuan: 'BUAH', jenis: 'Bahan Komputer' },
    { kode: '11703060104', nama: 'toner cartridge', satuan: 'BUAH', jenis: 'Bahan Komputer' },
    
    // PERABOT KANTOR
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
    
    // ALAT LISTRIK
    { kode: '11703080110', nama: 'alkaline a2', satuan: 'PACK', jenis: 'Alat Listrik' },
    { kode: '11703080110', nama: 'alkaline a 3', satuan: 'PACK', jenis: 'Alat Listrik' },
    
    // ALAT/BAHAN UNTUK KEGIATAN KANTOR LAINNYA
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
        option.setAttribute('data-kode', item.kode);
        option.setAttribute('data-satuan', item.satuan);
        datalist.appendChild(option);
    });
}

// Event listener untuk dropdown jenis barang
const jenisSelect = document.getElementById('jenis_id');
const namaBarangInput = document.getElementById('nama_barang');
const kodeBarangInput = document.getElementById('kode_barang');
const satuanInput = document.getElementById('satuan');

jenisSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const selectedJenisNama = selectedOption.text;
    
    // Clear input fields ketika ganti jenis
    namaBarangInput.value = '';
    kodeBarangInput.value = '';
    satuanInput.value = '';
    
    // Update list barang sesuai jenis yang dipilih
    updateBarangList(selectedJenisNama);
    
    // Enable/disable nama barang input
    if (selectedJenisNama) {
        namaBarangInput.disabled = false;
        namaBarangInput.placeholder = 'Pilih dari daftar atau ketik nama barang...';
    } else {
        namaBarangInput.disabled = true;
        namaBarangInput.placeholder = 'Pilih jenis barang terlebih dahulu';
    }
});

// Event listener untuk auto-fill kode barang dan satuan
namaBarangInput.addEventListener('input', function() {
    const selectedNama = this.value;
    const barang = masterBarang.find(item => item.nama.toLowerCase() === selectedNama.toLowerCase());
    
    if (barang) {
        kodeBarangInput.value = barang.kode;
        satuanInput.value = barang.satuan;
        
        // Highlight untuk menunjukkan data terisi otomatis
        kodeBarangInput.style.backgroundColor = '#e8f5e9';
        satuanInput.style.backgroundColor = '#e8f5e9';
        setTimeout(() => {
            kodeBarangInput.style.backgroundColor = '';
            satuanInput.style.backgroundColor = '';
        }, 1000);
    }
});

// Initialize - disable nama barang input jika belum pilih jenis
if (!jenisSelect.value) {
    namaBarangInput.disabled = true;
    namaBarangInput.placeholder = 'Pilih jenis barang terlebih dahulu';
}

// Search/filter functionality
namaBarangInput.addEventListener('focus', function() {
    if (!this.disabled) {
        this.select();
    }
});
</script>

<style>
#nama_barang {
    position: relative;
}

#nama_barang:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.form-group small {
    font-size: 12px;
}
</style>
@endsection
