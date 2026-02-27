@extends('layouts.app')

@section('title', 'Data Pengurangan')

@section('content')
<div class="page-header">
    <h2>Data Pengurangan Barang</h2>
    <p class="breadcrumbs">Home > Pengurangan</p>
</div>

@can('create', App\Models\Pengurangan::class)
<div style="margin-bottom: 15px;">
    <a href="{{ route('pengurangan.create') }}" class="btn btn-success">+ Buat Pengurangan Baru</a>
</div>
@endcan

<form method="GET" action="{{ route('pengurangan.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-bottom: 16px;">
    <label for="barang-filter" style="font-weight: 600; color: #1a2b4b;">Barang:</label>
    <select id="barang-filter" name="barang_id" style="max-width: 260px;">
        <option value="all" @selected(($selectedBarang ?? 'all') === 'all')>Semua Barang</option>
        @foreach($barangOptions as $barang)
            <option value="{{ $barang->id }}" @selected(($selectedBarang ?? 'all') == $barang->id)>
                {{ $barang->nama_barang }}
            </option>
        @endforeach
    </select>

    <label for="tahun-filter" style="font-weight: 600; color: #1a2b4b;">Tahun:</label>
    <select id="tahun-filter" name="tahun" style="max-width: 160px;">
        <option value="all" @selected(($selectedTahun ?? 'all') === 'all')>Semua Tahun</option>
        @foreach($tahunOptions as $tahun)
            <option value="{{ $tahun }}" @selected(($selectedTahun ?? 'all') == $tahun)>
                {{ $tahun }}
            </option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-primary">Terapkan</button>
    <a href="{{ route('pengurangan.index') }}" class="btn btn-secondary">Reset</a>
</form>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 16px;">
    <div style="background: #e3f2fd; padding: 12px 14px; border-radius: 6px;">
        <div style="font-size: 12px; color: #1a2b4b;">Total Masuk</div>
        <div style="font-size: 18px; font-weight: 700; color: #0d47a1;">{{ number_format($totalMasuk) }}</div>
    </div>
    <div style="background: #fff3cd; padding: 12px 14px; border-radius: 6px;">
        <div style="font-size: 12px; color: #1a2b4b;">Total Keluar</div>
        <div style="font-size: 18px; font-weight: 700; color: #8a6d3b;">{{ number_format($totalKeluar) }}</div>
    </div>
    <div style="background: #e8f5e9; padding: 12px 14px; border-radius: 6px;">
        <div style="font-size: 12px; color: #1a2b4b;">Saldo</div>
        <div style="font-size: 18px; font-weight: 700; color: #1b5e20;">{{ number_format($saldo) }}</div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No Bukti</th>
            <th>Nama Barang</th>
            <th>Jumlah Keluar</th>
            <th>Unit Kerja</th>
            <th>Tanggal Keluar</th>
            <th>Keperluan</th>
            <th>Dibuat oleh</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($penguranganDetails as $detail)
        <tr>
            <td>{{ ($penguranganDetails->currentPage() - 1) * $penguranganDetails->perPage() + $loop->iteration }}</td>
            <td><strong>{{ $detail->pengurangan->no_bukti }}</strong></td>
            <td><small>{{ $detail->barang->nama_barang }}</small></td>
            <td style="text-align: center;">{{ $detail->jumlah_kurang }}</td>
            <td>
                <small>{{ $detail->pengurangan->unitKerja->nama_unit }}</small>
            </td>
            <td>{{ $detail->pengurangan->tgl_keluar->format('d/m/Y') }}</td>
            <td>{{ Str::limit($detail->pengurangan->keperluan, 30) }}</td>
            <td>{{ $detail->pengurangan->creator->name }}</td>
            <td>
                @if($detail->pengurangan->status === 'pending')
                    <span class="badge badge-pending">PENDING</span>
                @elseif($detail->pengurangan->status === 'approved')
                    <span class="badge badge-approved">DISETUJUI</span>
                @else
                    <span class="badge badge-rejected">DITOLAK</span>
                @endif
            </td>
            <td>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <a href="{{ route('pengurangan.show', $detail->pengurangan) }}" class="btn btn-primary btn-sm">Lihat</a>
                    
                    @can('update', $detail->pengurangan)
                    <a href="{{ route('pengurangan.edit', $detail->pengurangan) }}" class="btn btn-warning btn-sm">Edit</a>
                    @endcan

                    @can('delete', $detail->pengurangan)
                    <form action="{{ route('pengurangan.destroy', $detail->pengurangan) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('Hapus pengurangan ini?')">
                            Hapus
                        </button>
                    </form>
                    @endcan

                    @can('approve', $detail->pengurangan)
                    @if($detail->pengurangan->status === 'pending')
                    <form action="{{ route('pengurangan.approve', $detail->pengurangan) }}" method="POST" style="display: inline;">
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
            <td colspan="10" style="text-align: center; padding: 30px;">
                <em>Tidak ada data pengurangan</em>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
    <div>
        <small style="color: #666;">
            Menampilkan {{ $penguranganDetails->count() }} dari {{ $penguranganDetails->total() }} data
        </small>
    </div>
    {{ $penguranganDetails->links() }}
</div>

<hr style="margin: 30px 0;">

<div style="background: #f0f0f0; padding: 15px; border-radius: 5px;">
    <h4 style="margin-bottom: 10px;">Penjelasan Kolom:</h4>
    <ul style="margin-left: 20px;">
        <li><strong>Unit Kerja:</strong> Menunjukkan unit mana yang membuat pengurangan. Semua unit bisa melihat.</li>
        <li><strong>Status:</strong> 
            <span class="badge badge-pending">PENDING</span> = Menunggu persetujuan,
            <span class="badge badge-approved">DISETUJUI</span> = Sudah disetujui dan stok diperbarui
        </li>
        <li><strong>Aksi Edit:</strong> Hanya bisa diedit jika status masih PENDING dan milik unit Anda sendiri</li>
        <li><strong>Aksi Setujui:</strong> Hanya Kepala Bagian dari unit yang sama yang dapat menyetujui</li>
    </ul>
</div>
@endsection
