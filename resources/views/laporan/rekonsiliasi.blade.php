@extends('layouts.app')

@section('title', 'Rekonsiliasi Persediaan')

@section('content')
<style>
    .rekon-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .rekon-table th, .rekon-table td { border: 1px solid #999; padding: 6px 8px; }
    .rekon-table th { background: #d4af37; font-weight: bold; text-align: center; }
    .rekon-table .asset-label { background: #87ceeb; font-weight: bold; }
    .rekon-table .asset-label td { text-align: center; color: white; background: #4682b4; }
    .rekon-table .label { background: #f5f5f5; font-weight: bold; }
    .rekon-table .num { text-align: right; }
    .rekon-table .subheader { background: #fffacd; font-weight: bold; }
    .rekon-table tfoot tr { background: #f8f9fa; font-weight: bold; }
    .rekon-table tfoot td { border-top: 2px solid #333; }
</style>

<div class="page-header">
    <h2>Rekonsiliasi Persediaan</h2>
    <p class="breadcrumbs">Home > Laporan > Rekonsiliasi Persediaan</p>
</div>

<div style="background: #f3e5f5; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
    <p>
        <strong>Nama OPD/Unit Kerja:</strong> {{ $unitKerja->nama_unit }}<br>
        <strong>Bulan:</strong> {{ $tglAwal->clone()->isoFormat('MMMM YYYY') }}<br>
        <strong>Periode:</strong> {{ $tglAwal->format('d/m/Y') }} - {{ $tglAkhir->format('d/m/Y') }}
    </p>
</div>

<table class="rekon-table">
    <thead>
        <tr>
            <th rowspan="2" style="width: 40px;">NO</th>
            <th rowspan="2" style="width: 240px;">URAIAN</th>
            <th colspan="2">SALDO AWAL</th>
            <th colspan="8">PENAMBAHAN ASET</th>
            <th colspan="4">PENGURANGAN ASET</th>
            <th colspan="2">SALDO AKHIR</th>
            <th rowspan="2">KETERANGAN</th>
        </tr>
        <tr>
            <th>Unit</th>
            <th>Rp</th>
            <th>Unit<br>Belanja<br>Modal</th>
            <th>Rp</th>
            <th>Unit<br>Belanja<br>Barang/Jasa</th>
            <th>Rp</th>
            <th>Unit<br>Dropping</th>
            <th>Rp</th>
            <th>Unit<br>Hibah</th>
            <th>Rp</th>
            <th>Unit<br>Penghapusan</th>
            <th>Rp</th>
            <th>Unit<br>Mutasi<br>Keluar</th>
            <th>Rp</th>
            <th>Unit</th>
            <th>Rp</th>
        </tr>
    </thead>
    <tbody>
        @foreach($report['asset_groups'] as $assetKey => $assetGroup)
            @if(!empty($assetGroup['groups']))
                <tr class="asset-label">
                    <td colspan="19">{{ $assetGroup['label'] }}</td>
                </tr>
                @php $groupCounter = 0; @endphp
                @foreach($assetGroup['groups'] as $group)
                    @php $groupCounter++; @endphp
                    <tr class="label">
                        <td colspan="19">{{ $group['label'] }}</td>
                    </tr>
                    @foreach($group['rows'] as $idx => $row)
                        <tr>
                            <td class="num">
                                @if($row['is_detail'])
                                    {{ $groupCounter }}.{{ $loop->iteration }}
                                @else
                                    {{ $groupCounter }}
                                @endif
                            </td>
                            <td style="{{ $row['is_detail'] ? 'padding-left: 30px;' : 'font-weight: bold;' }}">
                                {{ $row['uraian'] }}
                            </td>
                            <td class="num">{{ $row['saldo_awal_unit'] }}</td>
                            <td class="num">{{ number_format($row['saldo_awal_val'], 0, ',', '.') }}</td>
                            <td class="num">{{ $row['penambahan']['belanja_modal']['unit'] }}</td>
                            <td class="num">{{ number_format($row['penambahan']['belanja_modal']['val'], 0, ',', '.') }}</td>
                            <td class="num">{{ $row['penambahan']['belanja_barang']['unit'] }}</td>
                            <td class="num">{{ number_format($row['penambahan']['belanja_barang']['val'], 0, ',', '.') }}</td>
                            <td class="num">{{ $row['penambahan']['dropping']['unit'] }}</td>
                            <td class="num">{{ number_format($row['penambahan']['dropping']['val'], 0, ',', '.') }}</td>
                            <td class="num">{{ $row['penambahan']['hibah']['unit'] }}</td>
                            <td class="num">{{ number_format($row['penambahan']['hibah']['val'], 0, ',', '.') }}</td>
                            <td class="num">{{ $row['pengurangan']['penghapusan']['unit'] }}</td>
                            <td class="num">{{ number_format($row['pengurangan']['penghapusan']['val'], 0, ',', '.') }}</td>
                            <td class="num">{{ $row['pengurangan']['mutasi_keluar']['unit'] }}</td>
                            <td class="num">{{ number_format($row['pengurangan']['mutasi_keluar']['val'], 0, ',', '.') }}</td>
                            <td class="num">{{ $row['saldo_akhir_unit'] }}</td>
                            <td class="num">{{ number_format($row['saldo_akhir_val'], 0, ',', '.') }}</td>
                            <td>{{ $row['keterangan'] }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endif
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">{{ $report['total']['uraian'] }}</td>
            <td class="num">{{ $report['total']['saldo_awal_unit'] }}</td>
            <td class="num">{{ number_format($report['total']['saldo_awal_val'], 0, ',', '.') }}</td>
            <td class="num">{{ $report['total']['penambahan']['belanja_modal']['unit'] }}</td>
            <td class="num">{{ number_format($report['total']['penambahan']['belanja_modal']['val'], 0, ',', '.') }}</td>
            <td class="num">{{ $report['total']['penambahan']['belanja_barang']['unit'] }}</td>
            <td class="num">{{ number_format($report['total']['penambahan']['belanja_barang']['val'], 0, ',', '.') }}</td>
            <td class="num">{{ $report['total']['penambahan']['dropping']['unit'] }}</td>
            <td class="num">{{ number_format($report['total']['penambahan']['dropping']['val'], 0, ',', '.') }}</td>
            <td class="num">{{ $report['total']['penambahan']['hibah']['unit'] }}</td>
            <td class="num">{{ number_format($report['total']['penambahan']['hibah']['val'], 0, ',', '.') }}</td>
            <td class="num">{{ $report['total']['pengurangan']['penghapusan']['unit'] }}</td>
            <td class="num">{{ number_format($report['total']['pengurangan']['penghapusan']['val'], 0, ',', '.') }}</td>
            <td class="num">{{ $report['total']['pengurangan']['mutasi_keluar']['unit'] }}</td>
            <td class="num">{{ number_format($report['total']['pengurangan']['mutasi_keluar']['val'], 0, ',', '.') }}</td>
            <td class="num">{{ $report['total']['saldo_akhir_unit'] }}</td>
            <td class="num">{{ number_format($report['total']['saldo_akhir_val'], 0, ',', '.') }}</td>
            <td>{{ $report['total']['keterangan'] }}</td>
        </tr>
    </tfoot>
</table>

<div style="margin-top: 20px;">
    <a href="{{ route('laporan.index') }}" class="btn btn-primary">Kembali</a>
    <button onclick="window.print()" class="btn btn-success" style="margin-left: 10px;">Print</button>
</div>
@endsection
