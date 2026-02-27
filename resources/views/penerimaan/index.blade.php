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

<form method="GET" action="{{ route('penerimaan.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-bottom: 16px;">
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
    <a href="{{ route('penerimaan.index') }}" class="btn btn-secondary">Reset</a>
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
            <th>No Dokumen</th>
            <th>Nama Barang</th>
            <th>Jumlah Masuk</th>
            <th>Unit Kerja</th>
            <th>Tanggal</th>
            <th>Sumber Dana</th>
            <th>Dibuat oleh</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($penerimaanDetails as $detail)
        <tr>
            <td>{{ ($penerimaanDetails->currentPage() - 1) * $penerimaanDetails->perPage() + $loop->iteration }}</td>
            <td><strong>{{ $detail->penerimaan->no_dokumen }}</strong></td>
            <td><small>{{ $detail->barang->nama_barang }}</small></td>
            <td style="text-align: center;">{{ $detail->jumlah_masuk }}</td>
            <td>
                <small>{{ $detail->penerimaan->unitKerja->nama_unit }}</small>
            </td>
            <td>{{ $detail->penerimaan->tgl_dokumen->format('d/m/Y') }}</td>
            <td>{{ $detail->penerimaan->sumber_dana }}</td>
            <td>{{ $detail->penerimaan->creator->name }}</td>
            <td>
                @if($detail->penerimaan->status === 'pending')
                    <span class="badge badge-pending">PENDING</span>
                @elseif($detail->penerimaan->status === 'approved')
                    <span class="badge badge-approved">DISETUJUI</span>
                @else
                    <span class="badge badge-rejected">DITOLAK</span>
                @endif
            </td>
            <td>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <a href="{{ route('penerimaan.show', $detail->penerimaan) }}" class="btn btn-primary btn-sm">Lihat</a>
                    
                    @can('update', $detail->penerimaan)
                    <a href="{{ route('penerimaan.edit', $detail->penerimaan) }}" class="btn btn-warning btn-sm">Edit</a>
                    @endcan

                    @can('delete', $detail->penerimaan)
                    <form action="{{ route('penerimaan.destroy', $detail->penerimaan) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('Hapus penerimaan ini?')">
                            Hapus
                        </button>
                    </form>
                    @endcan

                    @can('approve', $detail->penerimaan)
                    @if($detail->penerimaan->status === 'pending')
                    <form action="{{ route('penerimaan.approve', $detail->penerimaan) }}" method="POST" style="display: inline;">
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
            <td colspan="10" style="text-align: center; padding: 30px;">
                <em>Tidak ada data penerimaan</em>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
    <div>
        <small style="color: #666;">
            Menampilkan {{ $penerimaanDetails->count() }} dari {{ $penerimaanDetails->total() }} data
        </small>
    </div>
    {{ $penerimaanDetails->links() }}
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
