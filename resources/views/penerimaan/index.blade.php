@extends('layouts.app')

@section('title', 'Data Penerimaan')

@section('content')
<div class="page-header">
    <h2>Data Penerimaan Barang</h2>
    <p class="breadcrumbs">Home > Penerimaan</p>
</div>

@can('create', App\Models\Penerimaan::class)
<div style="margin-bottom: 15px;">
    <a href="{{ route('penerimaan.create') }}" class="btn btn-success">+ Buat Penerimaan Baru</a>
</div>
@endcan

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No Dokumen</th>
            <th>Unit Kerja</th>
            <th>Tanggal</th>
            <th>Sumber Dana</th>
            <th>Dibuat oleh</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($penerimaan as $item)
        <tr>
            <td>{{ ($penerimaan->currentPage() - 1) * $penerimaan->perPage() + $loop->iteration }}</td>
            <td><strong>{{ $item->no_dokumen }}</strong></td>
            <td>
                <small>{{ $item->unitKerja->nama_unit }}</small>
            </td>
            <td>{{ $item->tgl_dokumen->format('d/m/Y') }}</td>
            <td>{{ $item->sumber_dana }}</td>
            <td>{{ $item->creator->name }}</td>
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
                    <a href="{{ route('penerimaan.show', $item) }}" class="btn btn-primary btn-sm">Lihat</a>
                    
                    @can('update', $item)
                    <a href="{{ route('penerimaan.edit', $item) }}" class="btn btn-warning btn-sm">Edit</a>
                    @endcan

                    @can('delete', $item)
                    <form action="{{ route('penerimaan.destroy', $item) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('Hapus penerimaan ini?')">
                            Hapus
                        </button>
                    </form>
                    @endcan

                    @can('approve', $item)
                    @if($item->status === 'pending')
                    <form action="{{ route('penerimaan.approve', $item) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" 
                                onclick="return confirm('Setujui penerimaan ini? Stok akan diperbarui.')">
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
            <td colspan="8" style="text-align: center; padding: 30px;">
                <em>Tidak ada data penerimaan</em>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
    <div>
        <small style="color: #666;">
            Menampilkan {{ $penerimaan->count() }} dari {{ $penerimaan->total() }} data
        </small>
    </div>
    {{ $penerimaan->links() }}
</div>

<hr style="margin: 30px 0;">

<div style="background: #e3f2fd; padding: 15px; border-radius: 5px;">
    <h4 style="margin-bottom: 10px; color: #003399;">Penjelasan Status Penerimaan:</h4>
    <ul style="margin-left: 20px;">
        <li><strong>PENDING:</strong> Penerimaan baru yang menunggu persetujuan dari Kepala Bagian</li>
        <li><strong>DISETUJUI:</strong> Penerimaan sudah disetujui dan stok barang sudah diperbarui</li>
        <li><strong>DITOLAK:</strong> Penerimaan ditolak dan tidak dapat diproses</li>
    </ul>
</div>
@endsection
