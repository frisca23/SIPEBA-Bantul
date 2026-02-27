@extends('layouts.app')

@section('title', 'Buat Pengurangan')

@section('content')
<div class="page-header">
    <h2>Buat Pengurangan Barang</h2>
    <p class="breadcrumbs">Home > Pengurangan > Buat Baru</p>
</div>

<form method="POST" action="{{ route('pengurangan.store') }}" style="max-width: 800px; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.05);">
    @csrf

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <!-- Left Column -->
        <div>
            <div class="form-group">
                <label for="no_bukti" style="font-weight: bold;">No Bukti / Referensi:</label>
                <input type="text" name="no_bukti" id="no_bukti" value="{{ old('no_bukti') }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" placeholder="Masukkan Nomor Bukti">
            </div>

<<<<<<< Updated upstream
    <div class="form-group">
        <label for="no_bukti">No Bukti:</label>
        <input type="text" name="no_bukti" id="no_bukti" value="{{ old('no_bukti') }}" required>
    </div>

    <div class="form-group">
        <label for="tgl_keluar">Tanggal Keluar:</label>
        <input type="date" name="tgl_keluar" id="tgl_keluar" value="{{ old('tgl_keluar', date('Y-m-d')) }}" required>
=======
            <div class="form-group" style="margin-top: 15px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="tgl_keluar" style="font-weight: bold;">Tanggal Pengeluaran Barang:</label>
                        <input type="date" name="tgl_keluar" id="tgl_keluar" value="{{ old('tgl_keluar', date('Y-m-d')) }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <div class="form-group">
                        <label for="tgl_serah" style="font-weight: bold;">Tanggal Penyerahan Barang:</label>
                        <input type="date" name="tgl_serah" id="tgl_serah" value="{{ old('tgl_serah', date('Y-m-d')) }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label for="jenis_barang" style="font-weight: bold;">Jenis Barang:</label>
                <select id="jenis_barang" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                    <option value="">-- Pilih Jenis Barang --</option>
                    @foreach($jenisBarang as $jb)
                    <option value="{{ $jb->id }}" data-kode="{{ $jb->kode_jenis }}">{{ $jb->nama_jenis }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label for="kode_jenis" style="font-weight: bold;">Kode Jenis Barang:</label>
                <input type="text" id="kode_jenis" readonly style="width: 100%; padding: 8px; background: #f8f9fa; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label for="barang_id" style="font-weight: bold;">Nama Barang:</label>
                <select name="detail[0][barang_id]" id="barang_id" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" disabled>
                    <option value="">-- Pilih Barang --</option>
                    @foreach($barang as $b)
                    <option value="{{ $b->id }}" data-jenis="{{ $b->jenis_id }}" data-kode="{{ $b->kode_barang }}" data-satuan="{{ $b->satuan }}" data-harga="{{ $b->harga_terakhir }}" data-stok="{{ $b->stok_saat_ini }}" style="display: none;">
                        {{ $b->nama_barang }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group" style="margin-top: 15px;">
                <label for="kode_barang" style="font-weight: bold;">Kode Barang:</label>
                <input type="text" id="kode_barang" readonly style="width: 100%; padding: 8px; background: #f8f9fa; border: 1px solid #ccc; border-radius: 4px;">
            </div>
        </div>

        <!-- Right Column -->
        <div>
            <div class="form-group">
                <label style="font-weight: bold;">Stok Tersedia:</label>
                <div id="stok_tersedia" style="padding: 8px; background: #e9ecef; border: 1px solid #ccc; border-radius: 4px; font-weight: bold; color: #495057;">-</div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                <div class="form-group">
                    <label for="jumlah_kurang" style="font-weight: bold;">Banyaknya:</label>
                    <input type="number" name="detail[0][jumlah_kurang]" id="jumlah_kurang" min="1" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" readonly>
                </div>
                
                <div class="form-group">
                    <label for="satuan" style="font-weight: bold;">Satuan:</label>
                    <input type="text" id="satuan" readonly style="width: 100%; padding: 8px; background: #f8f9fa; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label for="harga_satuan" style="font-weight: bold;">Harga Satuan (Rp):</label>
                <input type="number" id="harga_satuan" readonly style="width: 100%; padding: 8px; background: #f8f9fa; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label for="jumlah_harga" style="font-weight: bold;">Jumlah Harga (Rp):</label>
                <input type="text" id="jumlah_harga" readonly style="width: 100%; padding: 8px; background: #e8f5e9; border: 1px solid #a5d6a7; border-radius: 4px; color: #2e7d32; font-weight: bold;">
            </div>
        </div>
>>>>>>> Stashed changes
    </div>

    <div class="form-group" style="margin-top: 20px;">
        <label for="keperluan" style="font-weight: bold;">Keterangan / Keperluan:</label>
        <textarea name="keperluan" id="keperluan" rows="3" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">{{ old('keperluan') }}</textarea>
    </div>

    <hr style="margin: 25px 0; border: 0; border-top: 1px solid #eee;">

    <div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #ffc107;">
        <strong>⚠️ Penting:</strong> Saat pengurangan disetujui, sistem akan mengecek stok. Jika stok tidak cukup, pengurangan akan ditolak.
    </div>

    <div style="margin-top: 20px; display: flex; gap: 10px;">
        <button type="submit" class="btn btn-success" style="padding: 10px 20px;">Simpan Pengurangan</button>
        <a href="{{ route('pengurangan.index') }}" class="btn" style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Batal</a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jenisBarangSelect = document.getElementById('jenis_barang');
    const kodeJenisInput = document.getElementById('kode_jenis');
    
    const barangSelect = document.getElementById('barang_id');
    const kodeBarangInput = document.getElementById('kode_barang');
    const satuanInput = document.getElementById('satuan');
    const hargaSatuanInput = document.getElementById('harga_satuan');
    const stokTersediaDiv = document.getElementById('stok_tersedia');
    
    const jumlahKurangInput = document.getElementById('jumlah_kurang');
    const jumlahHargaInput = document.getElementById('jumlah_harga');

    // Utility untuk format rupiah
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // Kalkulasi jumlah harga
    function calculateTotal() {
        const qty = parseFloat(jumlahKurangInput.value) || 0;
        const harga = parseFloat(hargaSatuanInput.value) || 0;
        const total = qty * harga;
        jumlahHargaInput.value = formatRupiah(total);
    }

    // Event saat jenis barang berubah
    jenisBarangSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            // Update kode jenis
            kodeJenisInput.value = selectedOption.getAttribute('data-kode');
            
            // Filter barang options
            barangSelect.disabled = false;
            barangSelect.value = "";
            let found = false;
            
            Array.from(barangSelect.options).forEach(opt => {
                if (opt.value === "") return; // Skip placeholder
                
                if (opt.getAttribute('data-jenis') === this.value) {
                    opt.style.display = '';
                    found = true;
                } else {
                    opt.style.display = 'none';
                }
            });

            // Reset field barang
            kodeBarangInput.value = '';
            satuanInput.value = '';
            hargaSatuanInput.value = '';
            stokTersediaDiv.textContent = '-';
            jumlahKurangInput.value = '';
            jumlahKurangInput.readOnly = true;
            jumlahHargaInput.value = '';
            
        } else {
            // Reset semuanya
            kodeJenisInput.value = '';
            barangSelect.disabled = true;
            barangSelect.value = '';
            kodeBarangInput.value = '';
            satuanInput.value = '';
            hargaSatuanInput.value = '';
            stokTersediaDiv.textContent = '-';
            jumlahKurangInput.value = '';
            jumlahKurangInput.readOnly = true;
            jumlahHargaInput.value = '';
            
            // Sembunyikan semua option barang
            Array.from(barangSelect.options).forEach(opt => {
                if (opt.value !== "") opt.style.display = 'none';
            });
        }
    });

    // Event saat barang berubah
    barangSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            kodeBarangInput.value = selectedOption.getAttribute('data-kode');
            satuanInput.value = selectedOption.getAttribute('data-satuan');
            
            const harga = selectedOption.getAttribute('data-harga');
            hargaSatuanInput.value = harga;
            
            const stok = selectedOption.getAttribute('data-stok');
            stokTersediaDiv.textContent = stok + ' ' + selectedOption.getAttribute('data-satuan');
            
            jumlahKurangInput.readOnly = false;
            jumlahKurangInput.max = stok; // Optional: restrict max input to available stock
            
            calculateTotal();
        } else {
            kodeBarangInput.value = '';
            satuanInput.value = '';
            hargaSatuanInput.value = '';
            stokTersediaDiv.textContent = '-';
            jumlahKurangInput.value = '';
            jumlahKurangInput.readOnly = true;
            jumlahHargaInput.value = '';
        }
    });

    // Event saat input quantity berubah
    jumlahKurangInput.addEventListener('input', calculateTotal);
});
</script>
@endsection
