@extends('layouts.app')

@section('title', 'Detail Pengurangan: ' . $pengurangan->no_bukti)

@section('content')
<div class="page-header">
    <h2>Pengurangan: {{ $pengurangan->no_bukti }}</h2>
    <p class="breadcrumbs">Home > Pengurangan > {{ $pengurangan->no_bukti }}</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
<<<<<<< Updated upstream
    <div style="background: #f8f9fa; padding: 20px; border-radius: 5px;">
        <h4 style="color: #003399; margin-bottom: 15px;">Informasi Pengurangan</h4>
        <p><strong>No Bukti:</strong> {{ $pengurangan->no_bukti }}</p>
        <p><strong>Unit Kerja:</strong> {{ $pengurangan->unitKerja->nama_unit }}</p>
        <p><strong>Tanggal Keluar:</strong> {{ $pengurangan->tgl_keluar->format('d/m/Y') }}</p>
        <p><strong>Dibuat oleh:</strong> {{ $pengurangan->creator->name }}</p>
        @if($pengurangan->verifier)
        <p><strong>Disetujui oleh:</strong> {{ $pengurangan->verifier->name }}</p>
        <p><strong>Waktu Persetujuan:</strong> {{ $pengurangan->verified_at->format('d/m/Y H:i') }}</p>
        @endif
=======
    <div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #eee;">
        <h4 style="color: #003399; margin-bottom: 20px; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px;">Informasi Pengurangan</h4>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; color: #666; width: 150px;"><strong>No Bukti:</strong></td>
                <td style="padding: 8px 0;">{{ $pengurangan->no_bukti }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #666;"><strong>Unit Kerja:</strong></td>
                <td style="padding: 8px 0;">{{ $pengurangan->unitKerja->nama_unit ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #666;"><strong>Tanggal Keluar:</strong></td>
                <td style="padding: 8px 0;">{{ $pengurangan->tgl_keluar->format('d/m/Y') }}</td>
            </tr>
            @if($pengurangan->tgl_serah)
            <tr>
                <td style="padding: 8px 0; color: #666;"><strong>Tanggal Penyerahan Barang:</strong></td>
                <td style="padding: 8px 0;">{{ $pengurangan->tgl_serah->format('d/m/Y') }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 8px 0; color: #666;"><strong>Dibuat oleh:</strong></td>
                <td style="padding: 8px 0;">{{ $pengurangan->creator->name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #666;"><strong>Waktu Pembuatan:</strong></td>
                <td style="padding: 8px 0;">{{ $pengurangan->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @if($pengurangan->verifier)
            <tr>
                <td style="padding: 8px 0; color: #666;"><strong>Disetujui oleh:</strong></td>
                <td style="padding: 8px 0;">{{ $pengurangan->verifier->name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #666;"><strong>Waktu Persetujuan:</strong></td>
                <td style="padding: 8px 0;">{{ $pengurangan->verified_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endif
        </table>
>>>>>>> Stashed changes
    </div>

    <div style="background: #e3f2fd; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #bbdefb;">
        <h4 style="color: #01579b; margin-bottom: 20px;">Status</h4>
        <p style="margin-bottom: 15px;">
            @if($pengurangan->status === 'pending')
<<<<<<< Updated upstream
                <span class="badge badge-pending">PENDING</span>
            @elseif($pengurangan->status === 'approved')
                <span class="badge badge-approved">DISETUJUI</span>
            @else
                <span class="badge badge-rejected">DITOLAK</span>
=======
            <span class="badge badge-pending" style="font-size: 14px; padding: 6px 12px;">PENDING</span>
            @elseif($pengurangan->status === 'approved')
            <span class="badge badge-approved" style="font-size: 14px; padding: 6px 12px;">DISETUJUI</span>
            @else
            <span class="badge badge-rejected" style="font-size: 14px; padding: 6px 12px;">DITOLAK</span>
>>>>>>> Stashed changes
            @endif
        </p>
        <p style="color: #546e7a; font-size: 14px; line-height: 1.5;">
            @if($pengurangan->status === 'pending')
<<<<<<< Updated upstream
                Menunggu persetujuan dari Kepala Bagian
            @elseif($pengurangan->status === 'approved')
                Stok barang sudah dikurangi
=======
            Pengurangan baru yang menunggu persetujuan dari Kepala Bagian.
            @elseif($pengurangan->status === 'approved')
            Pengurangan sudah disetujui dan stok barang sudah berkurang.
            @else
            Pengurangan ditolak dan tidak diproses.
>>>>>>> Stashed changes
            @endif
        </p>
    </div>
</div>

<div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #eee;">
    <h4 style="color: #003399; margin-bottom: 20px; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px;">Detail Pengurangan Barang</h4>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8f9fa;">
                <th style="padding: 12px; border-bottom: 2px solid #dee2e6; text-align: left; width: 50px;">NO</th>
                <th style="padding: 12px; border-bottom: 2px solid #dee2e6; text-align: left;">NAMA BARANG</th>
                <th style="padding: 12px; border-bottom: 2px solid #dee2e6; text-align: left; width: 100px;">SATUAN</th>
                <th style="padding: 12px; border-bottom: 2px solid #dee2e6; text-align: center; width: 100px;">JUMLAH</th>
                <th style="padding: 12px; border-bottom: 2px solid #dee2e6; text-align: right; width: 150px;">HARGA SATUAN</th>
                <th style="padding: 12px; border-bottom: 2px solid #dee2e6; text-align: right; width: 150px;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($pengurangan->detail as $detail)
            @php
                $subTotal = $detail->jumlah_kurang * ($detail->barang->harga_terakhir ?? 0);
                $grandTotal += $subTotal;
            @endphp
            <tr>
                <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $loop->iteration }}</td>
                <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $detail->barang->nama_barang }}</td>
                <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $detail->barang->satuan }}</td>
                <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: center;">{{ $detail->jumlah_kurang }}</td>
                <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: right;">Rp {{ number_format($detail->barang->harga_terakhir, 0, ',', '.') }}</td>
                <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: right; font-weight: bold;">Rp {{ number_format($subTotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr style="background: #f8f9fa; font-weight: bold;">
                <td colspan="5" style="padding: 12px; text-align: left; letter-spacing: 1px;">TOTAL</td>
                <td style="padding: 12px; text-align: right;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 30px; background: #fafafa; padding: 20px; border-radius: 6px; border-left: 4px solid #003399;">
        <h5 style="margin-bottom: 10px; color: #333;">Keterangan</h5>
        <p style="margin: 0; color: #666;">{{ $pengurangan->keperluan ?? '-' }}</p>
    </div>

    <div style="margin-top: 30px; display: flex; gap: 10px;">
        @can('update', $pengurangan)
        @if($pengurangan->status === 'pending')
        <a href="{{ route('pengurangan.edit', $pengurangan) }}" class="btn btn-warning">Edit</a>
        @endif
        @endcan

        @can('delete', $pengurangan)
        @if($pengurangan->status === 'pending')
        <form action="{{ route('pengurangan.destroy', $pengurangan) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus pengurangan ini?')">Hapus</button>
        </form>
        @endif
        @endcan

        @can('approve', $pengurangan)
        <form action="{{ route('pengurangan.approve', $pengurangan) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-success" onclick="return confirm('Setujui pengurangan ini?')">Setujui</button>
        </form>
        @endcan

<<<<<<< Updated upstream
    @can('approve', $pengurangan)
    <form action="{{ route('pengurangan.approve', $pengurangan) }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="btn btn-success" 
                onclick="return confirm('Setujui pengurangan ini? Stok akan dikurangi.')">
            Setujui Pengurangan
        </button>
    </form>
    @endcan

    @can('delete', $pengurangan)
    <form action="{{ route('pengurangan.destroy', $pengurangan) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus pengurangan ini?')">
            Hapus
        </button>
    </form>
    @endcan

    <a href="{{ route('pengurangan.index') }}" class="btn btn-primary">Kembali</a>
=======
        <a href="{{ route('pengurangan.index') }}" class="btn btn-primary" style="background: #1a237e;">Kembali</a>
    </div>
>>>>>>> Stashed changes
</div>
@endsection
