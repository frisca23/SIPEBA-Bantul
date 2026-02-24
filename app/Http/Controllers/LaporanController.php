<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penerimaan;
use App\Models\Pengurangan;
use App\Models\StockOpname;
use App\Models\UnitKerja;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    use AuthorizesRequests;

    /**
     * Show laporan form with date filters
     */
    public function index(): View
    {
        return view('laporan.index');
    }

    /**
     * Buku Penerimaan Report
     */
    public function bukuPenerimaan(Request $request): View
    {
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        $tglAwal = Carbon::parse($validated['tgl_awal']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);

        // Query detail penerimaan yang approved
        $data = DB::table('penerimaan_detail')
            ->join('penerimaan', 'penerimaan_detail.penerimaan_id', '=', 'penerimaan.id')
            ->join('barang', 'penerimaan_detail.barang_id', '=', 'barang.id')
            ->where('penerimaan.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('penerimaan.status', 'approved')
            ->whereBetween('penerimaan.tgl_dokumen', [$tglAwal, $tglAkhir])
            ->select([
                'penerimaan.no_dokumen',
                'penerimaan.tgl_dokumen',
                'barang.nama_barang',
                'penerimaan_detail.jumlah_masuk',
                'penerimaan_detail.harga_satuan',
                'penerimaan_detail.total_harga',
            ])
            ->orderBy('penerimaan.tgl_dokumen')
            ->get();

        $unitKerja = UnitKerja::find($validated['unit_kerja_id']);
        $totalNilai = $data->sum('total_harga');

        return view('laporan.buku_penerimaan', compact('data', 'unitKerja', 'tglAwal', 'tglAkhir', 'totalNilai'));
    }

    /**
     * Buku Pengurangan Report
     */
    public function bukuPengurangan(Request $request): View
    {
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        $tglAwal = Carbon::parse($validated['tgl_awal']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);

        // Query detail pengurangan yang approved
        $data = DB::table('pengurangan_detail')
            ->join('pengurangan', 'pengurangan_detail.pengurangan_id', '=', 'pengurangan.id')
            ->join('barang', 'pengurangan_detail.barang_id', '=', 'barang.id')
            ->where('pengurangan.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('pengurangan.status', 'approved')
            ->whereBetween('pengurangan.tgl_keluar', [$tglAwal, $tglAkhir])
            ->select([
                'pengurangan.no_bukti',
                'pengurangan.tgl_keluar',
                'pengurangan.keperluan',
                'barang.nama_barang',
                'pengurangan_detail.jumlah_kurang',
            ])
            ->orderBy('pengurangan.tgl_keluar')
            ->get();

        $unitKerja = UnitKerja::find($validated['unit_kerja_id']);

        return view('laporan.buku_pengurangan', compact('data', 'unitKerja', 'tglAwal', 'tglAkhir'));
    }

    /**
     * Hasil Fisik Stock Opname Report
     */
    public function hasilFisikStockOpname(Request $request): View
    {
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        $tglAwal = Carbon::parse($validated['tgl_awal']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);

        // Query stock opname yang approved
        $data = DB::table('stock_opname')
            ->join('barang', 'stock_opname.barang_id', '=', 'barang.id')
            ->where('stock_opname.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('stock_opname.status', 'approved')
            ->whereBetween('stock_opname.tgl_opname', [$tglAwal, $tglAkhir])
            ->select([
                'barang.nama_barang',
                'stock_opname.stok_di_aplikasi',
                'stock_opname.stok_fisik_gudang',
                'stock_opname.selisih',
                'barang.harga_terakhir',
                DB::raw('stock_opname.selisih * barang.harga_terakhir as total_nilai_selisih'),
            ])
            ->orderBy('barang.nama_barang')
            ->get();

        $unitKerja = UnitKerja::find($validated['unit_kerja_id']);
        $totalNilaiSelisih = $data->sum('total_nilai_selisih');

        return view('laporan.hasil_fisik_stock_opname', compact('data', 'unitKerja', 'tglAwal', 'tglAkhir', 'totalNilaiSelisih'));
    }

    /**
     * Berita Acara Pemantauan Report
     * Laporan GABUNGAN (4 angka agregat) tanpa rincian barang
     */
    public function beritaAcaraPemantauan(Request $request): View
    {
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        $tglAwal = Carbon::parse($validated['tgl_awal']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);

        // Saldo Awal: Total nilai aset sebelum tgl_awal
        $saldoAwal = DB::table('barang')
            ->where('unit_kerja_id', $validated['unit_kerja_id'])
            ->selectRaw('SUM(stok_saat_ini * harga_terakhir) as total')
            ->value('total') ?? 0;

        // Total Penerimaan: Total nilai penerimaan yang approved selama periode
        $totalPenerimaan = DB::table('penerimaan_detail')
            ->join('penerimaan', 'penerimaan_detail.penerimaan_id', '=', 'penerimaan.id')
            ->where('penerimaan.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('penerimaan.status', 'approved')
            ->whereBetween('penerimaan.tgl_dokumen', [$tglAwal, $tglAkhir])
            ->sum('penerimaan_detail.total_harga') ?? 0;

        // Total Pengurangan: Total nilai pengurangan yang approved selama periode
        // Untuk pengurangan, kita harus hitung nilai berdasarkan harga_satuan saat itu
        $totalPengurangan = DB::table('pengurangan_detail')
            ->join('pengurangan', 'pengurangan_detail.pengurangan_id', '=', 'pengurangan.id')
            ->join('barang', 'pengurangan_detail.barang_id', '=', 'barang.id')
            ->where('pengurangan.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('pengurangan.status', 'approved')
            ->whereBetween('pengurangan.tgl_keluar', [$tglAwal, $tglAkhir])
            ->selectRaw('SUM(pengurangan_detail.jumlah_kurang * barang.harga_terakhir) as total')
            ->value('total') ?? 0;

        // Saldo Akhir: Saldo Awal + Total Penerimaan - Total Pengurangan
        $saldoAkhir = $saldoAwal + $totalPenerimaan - $totalPengurangan;

        $unitKerja = UnitKerja::find($validated['unit_kerja_id']);

        return view('laporan.berita_acara_pemantauan', compact(
            'unitKerja',
            'tglAwal',
            'tglAkhir',
            'saldoAwal',
            'totalPenerimaan',
            'totalPengurangan',
            'saldoAkhir'
        ));
    }

    /**
     * Rekonsiliasi Persediaan Report
     * Per jenis barang
     */
    public function rekonsiliasi(Request $request): View
    {
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        $tglAwal = Carbon::parse($validated['tgl_awal']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);

        // Group total nilai aset per jenis barang
        $data = DB::table('barang')
            ->join('jenis_barang', 'barang.jenis_id', '=', 'jenis_barang.id')
            ->where('barang.unit_kerja_id', $validated['unit_kerja_id'])
            ->groupBy('jenis_barang.id', 'jenis_barang.nama_jenis')
            ->selectRaw('
                jenis_barang.id,
                jenis_barang.nama_jenis,
                SUM(barang.stok_saat_ini * barang.harga_terakhir) as total_nilai_aset
            ')
            ->orderBy('jenis_barang.nama_jenis')
            ->get();

        $unitKerja = UnitKerja::find($validated['unit_kerja_id']);
        $totalNilaiAset = $data->sum('total_nilai_aset');

        return view('laporan.rekonsiliasi', compact('data', 'unitKerja', 'tglAwal', 'tglAkhir', 'totalNilaiAset'));
    }

    /**
     * Export Buku Penerimaan to Excel
     */
    public function exportBukuPenerimaan(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        $tglAwal = Carbon::parse($validated['tgl_awal']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);

        // Query detail penerimaan yang approved dengan informasi barang lengkap
        $data = DB::table('penerimaan_detail')
            ->join('penerimaan', 'penerimaan_detail.penerimaan_id', '=', 'penerimaan.id')
            ->join('barang', 'penerimaan_detail.barang_id', '=', 'barang.id')
            ->join('jenis_barang', 'barang.jenis_id', '=', 'jenis_barang.id')
            ->where('penerimaan.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('penerimaan.status', 'approved')
            ->whereBetween('penerimaan.tgl_dokumen', [$tglAwal, $tglAkhir])
            ->select([
                'jenis_barang.nama_jenis',
                'barang.kode_barang',
                'barang.nama_barang',
                'barang.satuan',
                'penerimaan.no_dokumen',
                'penerimaan.tgl_dokumen',
                'penerimaan_detail.jumlah_masuk',
                'penerimaan_detail.harga_satuan',
                'penerimaan_detail.total_harga',
            ])
            ->orderBy('jenis_barang.nama_jenis')
            ->orderBy('penerimaan.tgl_dokumen')
            ->get();

        $unitKerja = UnitKerja::find($validated['unit_kerja_id']);
        $totalNilai = $data->sum('total_harga');
        $totalJumlah = $data->sum('jumlah_masuk');

        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(6);
        $sheet->getColumnDimension('E')->setWidth(6);
        $sheet->getColumnDimension('F')->setWidth(6);
        $sheet->getColumnDimension('G')->setWidth(6);
        $sheet->getColumnDimension('H')->setWidth(6);
        $sheet->getColumnDimension('I')->setWidth(6);
        $sheet->getColumnDimension('J')->setWidth(3);
        $sheet->getColumnDimension('K')->setWidth(3);
        $sheet->getColumnDimension('L')->setWidth(15);
        $sheet->getColumnDimension('M')->setWidth(15);
        $sheet->getColumnDimension('N')->setWidth(12);
        $sheet->getColumnDimension('O')->setWidth(10);
        $sheet->getColumnDimension('P')->setWidth(15);
        $sheet->getColumnDimension('Q')->setWidth(15);
        $sheet->getColumnDimension('R')->setWidth(30);
        $sheet->getColumnDimension('S')->setWidth(15);
        $sheet->getColumnDimension('T')->setWidth(30);

        $currentRow = 1;

        // Header - Lampiran Peraturan
        $sheet->mergeCells("P{$currentRow}:T{$currentRow}");
        $sheet->setCellValue("P{$currentRow}", 'LAMPIRAN IV');
        $currentRow++;

        $sheet->mergeCells("P{$currentRow}:T{$currentRow}");
        $sheet->setCellValue("P{$currentRow}", 'PERATURAN BUPATI BANTUL');
        $currentRow++;

        $sheet->mergeCells("P{$currentRow}:T{$currentRow}");
        $sheet->setCellValue("P{$currentRow}", 'NOMOR Tahun 2018');
        $currentRow++;

        $sheet->mergeCells("P{$currentRow}:T{$currentRow}");
        $sheet->setCellValue("P{$currentRow}", 'TENTANG');
        $currentRow++;

        $sheet->mergeCells("P{$currentRow}:T{$currentRow}");
        $sheet->setCellValue("P{$currentRow}", 'PEDOMAN PENGELOLAANBARANG PERSEDIAAN DI LINGKUNGANPEMERINTAH KABUPATEN BANTUL');
        $currentRow++;

        $currentRow++; // Empty row

        // Title
        $currentRow++;
        $sheet->mergeCells("A{$currentRow}:T{$currentRow}");
        $sheet->setCellValue("A{$currentRow}", 'BUKU PENERIMAAN BARANG PERSEDIAAN');
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $currentRow++;

        // Unit Kerja Info
        $sheet->setCellValue("A{$currentRow}", 'OPD/UNIT KERJA :');
        $sheet->mergeCells("C{$currentRow}:F{$currentRow}");
        $sheet->setCellValue("C{$currentRow}", $unitKerja->nama_unit);
        $currentRow++;

        // Periode Info
        $sheet->setCellValue("A{$currentRow}", 'Periode Bulan :');
        $sheet->mergeCells("C{$currentRow}:F{$currentRow}");
        $sheet->setCellValue("C{$currentRow}", $tglAwal->format('Y-m-d') . ' - ' . $tglAkhir->format('Y-m-d'));
        $currentRow++;

        // Kabupaten
        $sheet->setCellValue("A{$currentRow}", 'KABUPATEN :');
        $currentRow++;

        $currentRow++; // Empty rows
        $currentRow++;
        $currentRow++;

        // Table header row 1
        $headerRow = $currentRow;
        $sheet->setCellValue("A{$currentRow}", 'NO');
        $sheet->setCellValue("B{$currentRow}", 'JENIS/BARANG YANG DIBELI');
        $sheet->setCellValue("D{$currentRow}", 'KODE BARANG');
        $sheet->setCellValue("K{$currentRow}", 'DARI');
        $sheet->setCellValue("L{$currentRow}", 'DOKUMEN FAKTUR');
        $sheet->setCellValue("N{$currentRow}", 'BANYAKNYA');
        $sheet->setCellValue("O{$currentRow}", 'SATUAN');
        $sheet->setCellValue("P{$currentRow}", 'HARGA SATUAN (Rp)');
        $sheet->setCellValue("Q{$currentRow}", 'JUMLAH HARGA (Rp)');
        $sheet->setCellValue("R{$currentRow}", 'BUKTI PENERIMAAN');
        $sheet->setCellValue("T{$currentRow}", 'KETERANGAN');

        // Merge cells for "KODE BARANG"
        $sheet->mergeCells("D{$currentRow}:J{$currentRow}");

        // Merge cells for "DOKUMEN FAKTUR"
        $sheet->mergeCells("L{$currentRow}:M{$currentRow}");

        // Merge cells for "BUKTI PENERIMAAN"
        $sheet->mergeCells("R{$currentRow}:S{$currentRow}");

        $currentRow++;

        // Table header row 2 (sub-headers)
        $sheet->setCellValue("L{$currentRow}", 'NOMOR');
        $sheet->setCellValue("M{$currentRow}", 'TANGGAL');
        $sheet->setCellValue("R{$currentRow}", 'NOMOR');
        $sheet->setCellValue("S{$currentRow}", 'TANGGAL');

        // Set number row
        $currentRow++;
        $letters = range('A', 'T');
        foreach ($letters as $index => $letter) {
            $sheet->setCellValue("{$letter}{$currentRow}", (string)($index + 1));
        }

        // Style header
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle("A{$headerRow}:T{$currentRow}")->applyFromArray($headerStyle);

        $currentRow++;

        $groupedData = $data->groupBy('nama_jenis');
        $rowNum = 1;

        foreach ($groupedData as $jenisBarang => $items) {
            // Jenis Barang Header
            $sheet->setCellValue("B{$currentRow}", $rowNum . ' ' . $jenisBarang);
            $kodeBarangParts = explode('.', $items->first()->kode_barang);
            for ($i = 0; $i < 7; $i++) {
                $col = chr(68 + $i); // D, E, F, G, H, I, J
                $sheet->setCellValue("{$col}{$currentRow}", isset($kodeBarangParts[$i]) ? $kodeBarangParts[$i] : '');
            }
            $sheet->getStyle("B{$currentRow}")->getFont()->setBold(true);
            $currentRow++;
            $rowNum++;

            // Items in this jenis
            $itemNum = 1;
            foreach ($items as $item) {
                $sheet->setCellValue("A{$currentRow}", $itemNum);
                $sheet->setCellValue("B{$currentRow}", '- ' . $item->nama_barang);

                // Kode barang
                $kodeBarangParts = explode('.', $item->kode_barang);
                for ($i = 0; $i < 7; $i++) {
                    $col = chr(68 + $i); // D, E, F, G, H, I, J
                    $sheet->setCellValue("{$col}{$currentRow}", isset($kodeBarangParts[$i]) ? " " . $kodeBarangParts[$i] : '');
                }

                $sheet->setCellValue("K{$currentRow}", ''); // DARI
                $sheet->setCellValue("L{$currentRow}", $item->no_dokumen);
                $sheet->setCellValue("M{$currentRow}", Carbon::parse($item->tgl_dokumen)->format('Y-m-d'));
                $sheet->setCellValue("N{$currentRow}", $item->jumlah_masuk);
                $sheet->setCellValue("O{$currentRow}", $item->satuan);
                $sheet->setCellValue("P{$currentRow}", $item->harga_satuan);
                $sheet->setCellValue("Q{$currentRow}", $item->total_harga);
                
                // Add reference note
                $sheet->setCellValue("R{$currentRow}", 'bk 362'); // Placeholder from layout/DB?
                $sheet->setCellValue("S{$currentRow}", Carbon::parse($item->tgl_dokumen)->format('Y-m-d'));
                $sheet->setCellValue("T{$currentRow}", 'Belanja Barang dan Jasa (APBD)');

                // Format numbers
                $sheet->getStyle("P{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("Q{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');

                $currentRow++;
                $itemNum++;
            }
        }

        // Total row
        $sheet->setCellValue("A{$currentRow}", 'JUMLAH');
        $sheet->setCellValue("N{$currentRow}", $totalJumlah);
        $sheet->setCellValue("P{$currentRow}", '');
        $sheet->setCellValue("Q{$currentRow}", $totalNilai);
        $sheet->getStyle("A{$currentRow}:T{$currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("Q{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');

        // Create writer and return response
        $writer = new Xlsx($spreadsheet);

        $fileName = 'Buku_Penerimaan_' . $unitKerja->nama_unit . '_' . $tglAwal->format('Y-m-d') . '_' . $tglAkhir->format('Y-m-d') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
    /**
     * Export Buku Pengurangan to Excel
     */
    public function exportBukuPengurangan(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        $tglAwal = Carbon::parse($validated['tgl_awal']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);

        // Query detail pengurangan yang approved dengan informasi barang lengkap
        $data = DB::table('pengurangan_detail')
            ->join('pengurangan', 'pengurangan_detail.pengurangan_id', '=', 'pengurangan.id')
            ->join('barang', 'pengurangan_detail.barang_id', '=', 'barang.id')
            ->join('jenis_barang', 'barang.jenis_id', '=', 'jenis_barang.id')
            ->where('pengurangan.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('pengurangan.status', 'approved')
            ->whereBetween('pengurangan.tgl_keluar', [$tglAwal, $tglAkhir])
            ->select([
                'jenis_barang.nama_jenis',
                'barang.kode_barang',
                'barang.nama_barang',
                'barang.satuan',
                'barang.harga_terakhir',
                'pengurangan.no_bukti',
                'pengurangan.tgl_keluar',
                'pengurangan.keperluan',
                'pengurangan_detail.jumlah_kurang',
                DB::raw('pengurangan_detail.jumlah_kurang * barang.harga_terakhir as total_harga')
            ])
            ->orderBy('jenis_barang.nama_jenis')
            ->orderBy('pengurangan.tgl_keluar')
            ->get();

        $unitKerja = UnitKerja::find($validated['unit_kerja_id']);
        $totalNilai = $data->sum('total_harga');
        $totalJumlah = $data->sum('jumlah_kurang');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(6);
        $sheet->getColumnDimension('E')->setWidth(6);
        $sheet->getColumnDimension('F')->setWidth(6);
        $sheet->getColumnDimension('G')->setWidth(6);
        $sheet->getColumnDimension('H')->setWidth(6);
        $sheet->getColumnDimension('I')->setWidth(6);
        $sheet->getColumnDimension('J')->setWidth(6);
        $sheet->getColumnDimension('K')->setWidth(12);
        $sheet->getColumnDimension('L')->setWidth(12);
        $sheet->getColumnDimension('M')->setWidth(12);
        $sheet->getColumnDimension('N')->setWidth(15);
        $sheet->getColumnDimension('O')->setWidth(15);
        $sheet->getColumnDimension('P')->setWidth(15);
        $sheet->getColumnDimension('Q')->setWidth(20);

        $currentRow = 1;

        // Header - Lampiran Peraturan
        $sheet->mergeCells("L{$currentRow}:Q{$currentRow}");
        $sheet->setCellValue("L{$currentRow}", 'LAMPIRAN X');
        $currentRow++;

        $sheet->mergeCells("L{$currentRow}:Q{$currentRow}");
        $sheet->setCellValue("L{$currentRow}", 'PERATURAN BUPATI BANTUL');
        $currentRow++;

        $sheet->mergeCells("L{$currentRow}:Q{$currentRow}");
        $sheet->setCellValue("L{$currentRow}", 'NOMOR P/0011120 2019');
        $currentRow++;

        $sheet->mergeCells("L{$currentRow}:Q{$currentRow}");
        $sheet->setCellValue("L{$currentRow}", 'TENTANG PEDOMAN PENGELOLAAN');
        $currentRow++;

        $sheet->mergeCells("L{$currentRow}:Q{$currentRow}");
        $sheet->setCellValue("L{$currentRow}", 'BARANG PERSEDIAAN LINGKUNGAN');
        $currentRow++;
        
        $sheet->mergeCells("L{$currentRow}:Q{$currentRow}");
        $sheet->setCellValue("L{$currentRow}", 'PEMERINTAH KABUPATEN BANTUL');
        $currentRow++;

        $currentRow++; // Empty row

        // Title
        $currentRow++;
        $sheet->mergeCells("A{$currentRow}:Q{$currentRow}");
        $sheet->setCellValue("A{$currentRow}", 'BUKU PENGELUARAN BARANG PERSEDIAAN');
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $currentRow++;

        $currentRow++; // Empty row

        // Unit Kerja Info
        $sheet->setCellValue("A{$currentRow}", 'OPD/Unit Kerja');
        $sheet->setCellValue("B{$currentRow}", ':');
        $sheet->setCellValue("C{$currentRow}", $unitKerja->nama_unit);
        $currentRow++;

        // Periode Info
        $sheet->setCellValue("A{$currentRow}", 'Periode Bulan');
        $sheet->setCellValue("B{$currentRow}", ':');
        $sheet->setCellValue("C{$currentRow}", $tglAwal->format('Y-m-d') . ' - ' . $tglAkhir->format('Y-m-d'));
        $currentRow++;

        // Kabupaten
        $sheet->setCellValue("A{$currentRow}", 'Kabupaten');
        $sheet->setCellValue("B{$currentRow}", ':');
        $sheet->setCellValue("C{$currentRow}", 'Bantul');
        $currentRow++;

        $currentRow++; // Empty row

        // Table header row 1
        $headerRow = $currentRow;
        $sheet->setCellValue("A{$currentRow}", 'No');
        $sheet->setCellValue("B{$currentRow}", 'TANGGAL PENGELUARAN BARANG');
        $sheet->setCellValue("C{$currentRow}", 'NAMA BARANG');
        $sheet->setCellValue("D{$currentRow}", 'KODE BARANG');
        $sheet->setCellValue("K{$currentRow}", 'NOMOR');
        $sheet->setCellValue("L{$currentRow}", 'BANYAKNYA');
        $sheet->setCellValue("M{$currentRow}", 'SATUAN');
        $sheet->setCellValue("N{$currentRow}", 'HARGA SATUAN (RP)');
        $sheet->setCellValue("O{$currentRow}", 'JUMLAH HARGA (RP)');
        $sheet->setCellValue("P{$currentRow}", 'TANGGAL PENYERAHAN');
        $sheet->setCellValue("Q{$currentRow}", 'KETERANGAN');

        // Merge cells for "KODE BARANG"
        $sheet->mergeCells("D{$currentRow}:J{$currentRow}");

        $currentRow++;

        // Set number row
        $letters = range('A', 'Q');
        foreach ($letters as $index => $letter) {
            $sheet->setCellValue("{$letter}{$currentRow}", (string)($index + 1));
        }

        // Style header
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle("A{$headerRow}:Q{$currentRow}")->applyFromArray($headerStyle);
        $sheet->getStyle("A{$headerRow}:Q{$currentRow}")->getAlignment()->setWrapText(true);

        $currentRow++;

        $groupedData = $data->groupBy('nama_jenis');

        foreach ($groupedData as $jenisBarang => $items) {
            // Jenis Barang Header
            $sheet->setCellValue("A{$currentRow}", '+');
            $sheet->setCellValue("C{$currentRow}", $jenisBarang);
            $kodeBarangParts = explode('.', $items->first()->kode_barang);
            for ($i = 0; $i < 7; $i++) {
                $col = chr(68 + $i); // D, E, F, G, H, I, J
                $sheet->setCellValue("{$col}{$currentRow}", isset($kodeBarangParts[$i]) ? $kodeBarangParts[$i] : '');
            }
            $sheet->getStyle("A{$currentRow}:C{$currentRow}")->getFont()->setBold(true);
            $currentRow++;

            // Items in this jenis
            $itemNum = 1;
            foreach ($items as $item) {
                $sheet->setCellValue("A{$currentRow}", $itemNum);
                $sheet->setCellValue("B{$currentRow}", Carbon::parse($item->tgl_keluar)->format('Y-m-d'));
                $sheet->setCellValue("C{$currentRow}", '- ' . $item->nama_barang);

                // Kode barang
                $kodeBarangParts = explode('.', $item->kode_barang);
                for ($i = 0; $i < 7; $i++) {
                    $col = chr(68 + $i); // D, E, F, G, H, I, J
                    $sheet->setCellValue("{$col}{$currentRow}", isset($kodeBarangParts[$i]) ? " " . $kodeBarangParts[$i] : '');
                }

                $sheet->setCellValue("K{$currentRow}", ''); // NOMOR R empty in template?
                $sheet->setCellValue("L{$currentRow}", $item->jumlah_kurang);
                $sheet->setCellValue("M{$currentRow}", $item->satuan);
                $sheet->setCellValue("N{$currentRow}", $item->harga_terakhir);
                $sheet->setCellValue("O{$currentRow}", $item->total_harga);
                $sheet->setCellValue("P{$currentRow}", Carbon::parse($item->tgl_keluar)->format('Y-m-d'));
                $sheet->setCellValue("Q{$currentRow}", 'Belanja Barang dan Jasa (APBD)');

                // Format numbers
                $sheet->getStyle("N{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("O{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');

                $currentRow++;
                $itemNum++;
            }
        }

        // Total row
        $sheet->setCellValue("A{$currentRow}", '');
        $sheet->setCellValue("B{$currentRow}", 'JUMLAH');
        $sheet->setCellValue("L{$currentRow}", $totalJumlah);
        $sheet->setCellValue("N{$currentRow}", '');
        $sheet->setCellValue("O{$currentRow}", $totalNilai);
        $sheet->getStyle("A{$currentRow}:Q{$currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("O{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Buku_Pengeluaran_' . $unitKerja->nama_unit . '_' . $tglAwal->format('Y-m-d') . '_' . $tglAkhir->format('Y-m-d') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
    /**
     * Export Hasil Fisik Stock Opname / Rekap Mutasi to Excel
     */
    public function exportHasilFisikStockOpname(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        $tglAwal = Carbon::parse($validated['tgl_awal']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);
        $unitKerja = UnitKerja::find($validated['unit_kerja_id']);

        // 1. Get all barang for this unit_kerja
        $barangs = DB::table('barang')
            ->join('jenis_barang', 'barang.jenis_id', '=', 'jenis_barang.id')
            ->where('barang.unit_kerja_id', $validated['unit_kerja_id'])
            ->select(
                'barang.id',
                'barang.kode_barang',
                'barang.stok_saat_ini',
                'barang.harga_terakhir',
                'jenis_barang.id as jenis_id',
                'jenis_barang.nama_jenis'
            )
            ->get();

        // 2. Get penambahan per barang
        $penerimaan = DB::table('penerimaan_detail')
            ->join('penerimaan', 'penerimaan_detail.penerimaan_id', '=', 'penerimaan.id')
            ->where('penerimaan.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('penerimaan.status', 'approved')
            ->whereBetween('penerimaan.tgl_dokumen', [$tglAwal, $tglAkhir])
            ->selectRaw('barang_id, SUM(total_harga) as total_penambahan')
            ->groupBy('barang_id')
            ->pluck('total_penambahan', 'barang_id');

        // 3. Get pengurangan per barang (nilai = qty * harga_terakhir)
        $pengurangan = DB::table('pengurangan_detail')
            ->join('pengurangan', 'pengurangan_detail.pengurangan_id', '=', 'pengurangan.id')
            ->join('barang', 'pengurangan_detail.barang_id', '=', 'barang.id')
            ->where('pengurangan.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('pengurangan.status', 'approved')
            ->whereBetween('pengurangan.tgl_keluar', [$tglAwal, $tglAkhir])
            ->selectRaw('pengurangan_detail.barang_id, SUM(pengurangan_detail.jumlah_kurang * barang.harga_terakhir) as total_pengurangan')
            ->groupBy('pengurangan_detail.barang_id')
            ->pluck('total_pengurangan', 'barang_id');

        $rekapPerJenis = [];

        foreach ($barangs as $b) {
            $tambah = $penerimaan[$b->id] ?? 0;
            $kurang = $pengurangan[$b->id] ?? 0;
            $akhir = $b->stok_saat_ini * $b->harga_terakhir;
            $awal = $akhir - $tambah + $kurang;

            if (!isset($rekapPerJenis[$b->jenis_id])) {
                $parts = explode('.', $b->kode_barang);
                $kode6 = array_slice($parts, 0, 6);
                
                $rekapPerJenis[$b->jenis_id] = [
                    'nama_jenis' => $b->nama_jenis,
                    'kode_parts' => $kode6,
                    'awal' => 0,
                    'tambah' => 0,
                    'kurang' => 0,
                    'akhir' => 0,
                ];
            }

            $rekapPerJenis[$b->jenis_id]['awal'] += $awal;
            $rekapPerJenis[$b->jenis_id]['tambah'] += $tambah;
            $rekapPerJenis[$b->jenis_id]['kurang'] += $kurang;
            $rekapPerJenis[$b->jenis_id]['akhir'] += $akhir;
        }

        // Sort by nama_jenis
        usort($rekapPerJenis, function($a, $b) {
            return strcmp($a['nama_jenis'], $b['nama_jenis']);
        });

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Allow wrapping text for headers
        $sheet->getStyle("A1:N10")->getAlignment()->setWrapText(true);

        // Columns: A(NO), B(NAMA BARANG), C-H(KODE BARANG 6 parts), I(SALDO AWAL), J(PENAMBAHAN), K(PENGURANGAN), L(SALDO AKHIR), M(KETERANGAN)
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(5);
        $sheet->getColumnDimension('D')->setWidth(5);
        $sheet->getColumnDimension('E')->setWidth(5);
        $sheet->getColumnDimension('F')->setWidth(5);
        $sheet->getColumnDimension('G')->setWidth(5);
        $sheet->getColumnDimension('H')->setWidth(5);
        $sheet->getColumnDimension('I')->setWidth(18);
        $sheet->getColumnDimension('J')->setWidth(18);
        $sheet->getColumnDimension('K')->setWidth(18);
        $sheet->getColumnDimension('L')->setWidth(18);
        $sheet->getColumnDimension('M')->setWidth(20);

        $currentRow = 1;

        $sheet->mergeCells("I{$currentRow}:M{$currentRow}");
        $sheet->setCellValue("I{$currentRow}", "Lampiran Berita Acara\nPemeriksaan Hasil Stock Opname");
        $sheet->getStyle("I{$currentRow}")->getAlignment()->setWrapText(true);
        $currentRow += 2;

        $sheet->mergeCells("A{$currentRow}:M{$currentRow}");
        $sheet->setCellValue("A{$currentRow}", 'DAFTAR HASIL PERHITUNGAN FISIK ATAS BARANG PERSEDIAAN/STOCK OPNAME');
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $currentRow++;

        $sheet->mergeCells("A{$currentRow}:M{$currentRow}");
        $sheet->setCellValue("A{$currentRow}", 'DI LINGKUNGAN PEMERINTAH KABUPATEN BANTUL');
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $currentRow++;
        
        $currentRow++; // Empty

        $sheet->setCellValue("A{$currentRow}", strtoupper($unitKerja->nama_unit) . " KAB. BANTUL");
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
        $currentRow++;
        $sheet->setCellValue("A{$currentRow}", "PER TANGGAL " . Carbon::parse($tglAkhir)->format('d F Y'));
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
        $currentRow++;

        $headerRow = $currentRow; // A to M
        $sheet->setCellValue("A{$currentRow}", 'NO');
        $sheet->setCellValue("B{$currentRow}", 'NAMA BARANG');
        $sheet->setCellValue("C{$currentRow}", 'KODE BARANG');
        $sheet->setCellValue("I{$currentRow}", "SALDO AWAL\n" . $tglAwal->format('Y'));
        $sheet->setCellValue("J{$currentRow}", "PENAMBAHAN\n" . $tglAwal->format('Y'));
        $sheet->setCellValue("K{$currentRow}", "PENGURANGAN\n" . $tglAwal->format('Y'));
        $sheet->setCellValue("L{$currentRow}", "SALDO AKHIR\n" . $tglAkhir->format('Y'));
        $sheet->setCellValue("M{$currentRow}", 'KETERANGAN');

        $sheet->mergeCells("C{$currentRow}:H{$currentRow}");
        $currentRow++;

        $letters = range('A', 'M');
        foreach ($letters as $index => $letter) {
            // For C-H they are merged under "3" or we can just label C to H as 3
            if ($letter >= 'C' && $letter <= 'H') {
                $sheet->setCellValue("{$letter}{$currentRow}", '3');
                if ($letter == 'H') {
                    $sheet->mergeCells("C{$currentRow}:H{$currentRow}");
                }
            } elseif ($letter > 'H') {
                $num = 4 + (ord($letter) - ord('I'));
                $sheet->setCellValue("{$letter}{$currentRow}", (string)$num);
            } else {
                $sheet->setCellValue("{$letter}{$currentRow}", (string)($index + 1));
            }
        }

        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle("A{$headerRow}:M{$currentRow}")->applyFromArray($headerStyle);
        $currentRow++;

        $totalAwal = 0;
        $totalTambah = 0;
        $totalKurang = 0;
        $totalAkhir = 0;

        foreach ($rekapPerJenis as $idx => $rekap) {
            $sheet->setCellValue("A{$currentRow}", $idx + 1);
            $sheet->setCellValue("B{$currentRow}", $rekap['nama_jenis']);
            
            for ($i = 0; $i < 6; $i++) {
                $col = chr(67 + $i); // C, D, E, F, G, H
                $sheet->setCellValue("{$col}{$currentRow}", isset($rekap['kode_parts'][$i]) ? $rekap['kode_parts'][$i] : '');
            }

            // Fill values only if they are not 0, or show 0
            if ($rekap['awal'] > 0) $sheet->setCellValue("I{$currentRow}", $rekap['awal']);
            else $sheet->setCellValue("I{$currentRow}", '-');

            if ($rekap['tambah'] > 0) $sheet->setCellValue("J{$currentRow}", $rekap['tambah']);
            else $sheet->setCellValue("J{$currentRow}", '-');

            if ($rekap['kurang'] > 0) $sheet->setCellValue("K{$currentRow}", $rekap['kurang']);
            else $sheet->setCellValue("K{$currentRow}", '-');

            if ($rekap['akhir'] > 0) $sheet->setCellValue("L{$currentRow}", $rekap['akhir']);
            else $sheet->setCellValue("L{$currentRow}", '-');

            $sheet->setCellValue("M{$currentRow}", '-');

            $sheet->getStyle("I{$currentRow}:L{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');

            $totalAwal += $rekap['awal'];
            $totalTambah += $rekap['tambah'];
            $totalKurang += $rekap['kurang'];
            $totalAkhir += $rekap['akhir'];

            $currentRow++;
        }

        // Row JUMLAH
        $sheet->mergeCells("A{$currentRow}:H{$currentRow}");
        $sheet->setCellValue("A{$currentRow}", 'JUMLAH');
        $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$currentRow}:M{$currentRow}")->getFont()->setBold(true);
        
        $sheet->setCellValue("I{$currentRow}", $totalAwal > 0 ? $totalAwal : '-');
        $sheet->setCellValue("J{$currentRow}", $totalTambah > 0 ? $totalTambah : '-');
        $sheet->setCellValue("K{$currentRow}", $totalKurang > 0 ? $totalKurang : '-');
        $sheet->setCellValue("L{$currentRow}", $totalAkhir > 0 ? $totalAkhir : '-');
        
        $sheet->getStyle("I{$currentRow}:L{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Hasil_Fisik_Stock_Opname_' . $unitKerja->nama_unit . '_' . $tglAwal->format('Y-m-d') . '_' . $tglAkhir->format('Y-m-d') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
