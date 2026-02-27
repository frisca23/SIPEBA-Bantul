<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Unit Kerja</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Satuan</th>
            <th>Stok Saat Ini</th>
            <th>Harga Terakhir</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($barang as $item)
        <tr>
            <td>{{ ($barang->currentPage() - 1) * $barang->perPage() + $loop->iteration }}</td>
            <td><small>{{ $item->unitKerja->nama_unit }}</small></td>
            <td><strong>{{ $item->kode_barang }}</strong></td>
            <td>{{ $item->nama_barang }}</td>
            <td>{{ $item->satuan }}</td>
            <td style="text-align: center; font-weight: bold;">
                {{ $item->stok_saat_ini }}
            </td>
            <td style="text-align: right;">
                Rp {{ number_format($item->harga_terakhir, 2, ',', '.') }}
            </td>
            <td>
                <a href="{{ route('barang.show', $item) }}" class="btn btn-primary btn-sm">Lihat</a>
                
                @can('update', $item)
                <a href="{{ route('barang.edit', $item) }}" class="btn btn-warning btn-sm">Edit</a>
                @endcan

                @can('delete', $item)
                <form action="{{ route('barang.destroy', $item) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" 
                            onclick="return confirm('Yakin ingin menghapus barang {{ $item->nama_barang }}?\n\nCATATAN: Barang yang sudah digunakan dalam transaksi tidak dapat dihapus.')">
                        Hapus
                    </button>
                </form>
                @endcan
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align: center; padding: 30px;">
                <em>Tidak ada data barang</em>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
    <div>
        <small style="color: #666;">
            Menampilkan {{ $barang->count() }} dari {{ $barang->total() }} data
        </small>
    </div>
    {{ $barang->links() }}
</div>
