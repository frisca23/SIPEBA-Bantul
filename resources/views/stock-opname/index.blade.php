@extends('layouts.app')

@section('title', 'Stock Opname')

@section('content')
<div class="page-header">
    <h2>Data Stock Opname</h2>
    <p class="breadcrumbs">Home > Stock Opname</p>
</div>

@can('create', App\Models\StockOpname::class)
<div style="margin-bottom: 15px;">
    <a href="{{ route('stock-opname.create') }}" class="btn btn-success">+ Buat Stock Opname</a>
</div>
@endcan

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Barang</th>
            <th>Unit Kerja</th>
            <th>Stok Sistem</th>
            <th>Stok Fisik</th>
            <th>Selisih</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($stockOpname as $item)
        <tr>
            <td>{{ ($stockOpname->currentPage() - 1) * $stockOpname->perPage() + $loop->iteration }}</td>
            <td>{{ $item->tgl_opname->format('d/m/Y') }}</td>
            <td>{{ $item->barang->nama_barang }}</td>
            <td><small>{{ $item->unitKerja->nama_unit }}</small></td>
            <td style="text-align: center; font-weight: bold;">{{ $item->stok_di_aplikasi }}</td>
            <td style="text-align: center;">{{ $item->stok_fisik_gudang }}</td>
            <td style="text-align: center; font-weight: bold;">
                {{ $item->selisih > 0 ? '+' : '' }}{{ $item->selisih }}
            </td>
            <td>
                @if($item->status === 'pending')
                    <span class="badge badge-pending">PENDING</span>
                @elseif($item->status === 'approved')
                    <span class="badge badge-approved">DISETUJUI</span>
                @else
                    <span class="badge badge-rejected">DITOLAK</span>
                @endif
            </td>
            <td>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <a href="{{ route('stock-opname.show', $item) }}" class="btn btn-primary btn-sm">Lihat</a>

                    @can('update', $item)
                    <a href="{{ route('stock-opname.edit', $item) }}" class="btn btn-warning btn-sm">Edit</a>
                    @endcan

                    @can('delete', $item)
                    <form action="{{ route('stock-opname.destroy', $item) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Hapus data stock opname ini?')">
                            Hapus
                        </button>
                    </form>
                    @endcan

                    @can('approve', $item)
                    @if($item->status === 'pending')
                    <form action="{{ route('stock-opname.approve', $item) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm"
                                onclick="return confirm('Setujui stock opname ini? Stok akan diperbarui.')">
                            Setujui
                        </button>
                    </form>
                    @endif
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" style="text-align: center; padding: 30px;">
                <em>Tidak ada data stock opname</em>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
    <div>
        <small style="color: #666;">
            Menampilkan {{ $stockOpname->count() }} dari {{ $stockOpname->total() }} data
        </small>
    </div>
    {{ $stockOpname->links() }}
</div>
@endsection
