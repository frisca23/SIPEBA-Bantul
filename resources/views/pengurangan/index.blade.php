@extends('layouts.app')

@section('title', 'Buku Pengurangan Barang')

@section('content')
<div class="page-header">
    <h2>Buku Pengurangan Barang</h2>
    <p class="breadcrumbs">Home > Pengurangan</p>
</div>

@can('create', App\Models\Pengurangan::class)
<div style="margin-bottom: 15px;">
    <a href="{{ route('pengurangan.create') }}" class="btn btn-success">+ Buat Pengurangan Baru</a>
</div>
@endcan

<<<<<<< Updated upstream
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No Bukti</th>
            <th>Unit Kerja</th>
            <th>Tanggal Keluar</th>
            <th>Keperluan</th>
            <th>Dibuat oleh</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pengurangan as $item)
        <tr>
            <td>{{ ($pengurangan->currentPage() - 1) * $pengurangan->perPage() + $loop->iteration }}</td>
            <td><strong>{{ $item->no_bukti }}</strong></td>
            <td>
                <small>{{ $item->unitKerja->nama_unit }}</small>
            </td>
            <td>{{ $item->tgl_keluar->format('d/m/Y') }}</td>
            <td>{{ Str::limit($item->keperluan, 30) }}</td>
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
                    <a href="{{ route('pengurangan.show', $item) }}" class="btn btn-primary btn-sm">Lihat</a>
                    
                    @can('update', $item)
                    <a href="{{ route('pengurangan.edit', $item) }}" class="btn btn-warning btn-sm">Edit</a>
                    @endcan

                    @can('delete', $item)
                    <form action="{{ route('pengurangan.destroy', $item) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('Hapus pengurangan ini?')">
                            Hapus
                        </button>
                    </form>
                    @endcan

                    @can('approve', $item)
                    @if($item->status === 'pending')
                    <form action="{{ route('pengurangan.approve', $item) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" 
                                onclick="return confirm('Setujui pengurangan ini? Stok akan diperbarui.')">
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
                <em>Tidak ada data pengurangan</em>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
=======
<div style="overflow-x: auto;">
    <table style="width: 100%;">
        <thead>
            <tr>
                <th>No</th>
                <th>No Bukti</th>
                <th>Unit Kerja</th>
                <th>Tanggal Pengeluaran Barang</th>
                <th>Dibuat oleh</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengurangan as $item)
            <tr>
                <td>{{ ($pengurangan->currentPage() - 1) * $pengurangan->perPage() + $loop->iteration }}</td>
                <td><strong>{{ $item->no_bukti }}</strong></td>
                <td>
                    <small>{{ $item->unitKerja->nama_unit ?? '-' }}</small>
                </td>
                <td>{{ $item->tgl_keluar->format('d/m/Y') }}</td>
                <td>{{ $item->creator->name }}</td>
                <td style="text-align: center;">
                    @if($item->status === 'pending')
                        <span class="badge badge-pending">PENDING</span>
                    @elseif($item->status === 'approved')
                        <span class="badge badge-approved">DISETUJUI</span>
                    @else
                        <span class="badge badge-rejected">DITOLAK</span>
                    @endif
                </td>
                <td>
                    <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                        <a href="{{ route('pengurangan.show', $item) }}" class="btn btn-primary btn-sm">Lihat</a>
                        
                        @can('update', $item)
                        <a href="{{ route('pengurangan.edit', $item) }}" class="btn btn-warning btn-sm">Edit</a>
                        @endcan

                        @can('delete', $item)
                        <form action="{{ route('pengurangan.destroy', $item) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Hapus pengurangan ini?')">
                                Hapus
                            </button>
                        </form>
                        @endcan

                        @can('approve', $item)
                        @if($item->status === 'pending')
                        <form action="{{ route('pengurangan.approve', $item) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" 
                                    onclick="return confirm('Setujui pengurangan ini?')">
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
                <td colspan="7" style="text-align: center; padding: 30px;">
                    <em>Tidak ada data pengurangan barang</em>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
>>>>>>> Stashed changes

<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
    <div>
        <small style="color: #666;">
            Menampilkan {{ $pengurangan->count() }} dari {{ $pengurangan->total() }} data
        </small>
    </div>
    {{ $pengurangan->links() }}
</div>

<hr style="margin: 30px 0;">

<div style="background: #e3f2fd; padding: 15px; border-radius: 5px;">
    <h4 style="margin-bottom: 10px; color: #003399;">Penjelasan Status Pengurangan:</h4>
    <ul style="margin-left: 20px;">
<<<<<<< Updated upstream
        <li><strong>Unit Kerja:</strong> Menunjukkan unit mana yang membuat pengurangan. Semua unit bisa melihat.</li>
        <li><strong>Status:</strong> 
            <span class="badge badge-pending">PENDING</span> = Menunggu persetujuan,
            <span class="badge badge-approved">DISETUJUI</span> = Sudah disetujui dan stok diperbarui
        </li>
        <li><strong>Aksi Edit:</strong> Hanya bisa diedit jika status masih PENDING dan milik unit Anda sendiri</li>
        <li><strong>Aksi Setujui:</strong> Hanya Kepala Bagian dari unit yang sama yang dapat menyetujui</li>
=======
        <li><strong>PENDING:</strong> Pengurangan baru yang menunggu persetujuan dari Kepala Bagian</li>
        <li><strong>DISETUJUI:</strong> Pengurangan sudah disetujui dan stok barang sudah berkurang</li>
        <li><strong>DITOLAK:</strong> Pengurangan ditolak dan tidak dapat diproses</li>
    </ul>
</div>

<hr style="margin: 30px 0;">

<div style="background: #f0f0f0; padding: 15px; border-radius: 5px;">
    <h4 style="margin-bottom: 10px;">Keterangan:</h4>
    <ul style="margin-left: 20px;">
        <li>Buku ini menampilkan semua barang yang sudah <strong>DISETUJUI</strong> pengurangannya</li>
        <li><strong>Tanggal Pengeluaran:</strong> Tanggal barang keluar dari inventaris</li>
        <li><strong>Tanggal Penyerahan:</strong> Tanggal barang diserahkan/digunakan</li>
        <li><strong>Nomor:</strong> ID referensi barang dalam sistem</li>
        <li><strong>Jumlah Harga:</strong> Banyaknya × Harga Satuan</li>
>>>>>>> Stashed changes
    </ul>
</div>
@endsection
