<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));
        $barangs = DataBarang::with('kategori')
            ->when($request->query('kategori_id'), function ($query) use ($request) {
                return $query->where('kategori_id', $request->query('kategori_id'));
            })
            ->when($keyword !== '', function ($query) use ($keyword) {
                return $query->where(function ($q) use ($keyword) {
                    $q->where('nama_barang', 'like', '%'.$keyword.'%')
                        ->orWhereHas('kategori', function ($kq) use ($keyword) {
                            $kq->where('nama_kategori', 'like', '%'.$keyword.'%');
                        });
                });
            })
            ->when($request->query('tanggal_mulai'), function ($query) use ($request) {
                return $query->whereDate('updated_at', '>=', $request->query('tanggal_mulai'));
            })
            ->when($request->query('tanggal_akhir'), function ($query) use ($request) {
                return $query->whereDate('updated_at', '<=', $request->query('tanggal_akhir'));
            })
            ->get();

        if ($request->filled('export')) {
            return $this->export($request, $barangs);
        }

        return view('laporan', compact('barangs'));
    }

    protected function export(Request $request, $barangs)
    {
        $type = (string) $request->query('export', 'laporan'); // stok|pergerakan|rusak|nilai
        $format = (string) $request->query('format', 'pdf'); // pdf|excel
        $today = now()->format('Ymd_His');

        $titleMap = [
            'stok' => 'Laporan Stok Barang',
            'pergerakan' => 'Laporan Pergerakan Barang',
            'rusak' => 'Laporan Inventaris Rusak/Hilang',
            'nilai' => 'Laporan Nilai Aset',
        ];
        $title = $titleMap[$type] ?? 'Laporan';

        switch ($type) {
            case 'nilai':
                $totalNilai = $barangs->sum(fn ($b) => $b->stok * $b->harga);
                $columns = ['No', 'Nama Barang', 'Kategori', 'Stok', 'Harga Satuan', 'Total Nilai', 'Persentase'];
                $rows = [];
                foreach ($barangs as $idx => $b) {
                    $nilai = $b->stok * $b->harga;
                    $rows[] = [
                        $idx + 1,
                        $b->nama_barang,
                        optional($b->kategori)->nama_kategori,
                        $b->stok,
                        $b->harga,
                        $nilai,
                        $totalNilai > 0 ? round(($nilai / $totalNilai) * 100, 2).'%' : '0%',
                    ];
                }
                break;
            case 'pergerakan':
                $columns = ['Tanggal', 'Barang', 'Tipe Mutasi', 'Perubahan', 'Stok Sebelum', 'Stok Sesudah', 'Catatan'];
                $movements = Schema::hasTable('stock_movements')
                    ? StockMovement::with('dataBarang')->latest()->limit(500)->get()
                    : collect();
                $rows = $movements->map(function ($m) {
                    return [
                        optional($m->created_at)->format('d/m/Y H:i'),
                        optional($m->dataBarang)->nama_barang ?? '-',
                        strtoupper((string) $m->movement_type),
                        $m->qty_change,
                        $m->qty_before,
                        $m->qty_after,
                        $m->notes ?? '-',
                    ];
                })->all();
                break;
            case 'rusak':
                $columns = ['Tanggal', 'Nama Barang', 'Kategori', 'Jumlah', 'Status', 'Keterangan'];
                $rows = []; // Belum ada data real; sediakan kosong
                break;
            case 'stok':
            default:
                $columns = ['No', 'Nama Barang', 'Kategori', 'Stok', 'Harga Satuan', 'Total Nilai', 'Tanggal Update'];
                $rows = [];
                foreach ($barangs as $idx => $b) {
                    $rows[] = [
                        $idx + 1,
                        $b->nama_barang,
                        optional($b->kategori)->nama_kategori,
                        $b->stok,
                        $b->harga,
                        $b->stok * $b->harga,
                        optional($b->updated_at)->format('d/m/Y H:i'),
                    ];
                }
                break;
        }

        if ($format === 'excel') {
            $filename = strtolower((string) $type)."_{$today}.csv";
            $handle = fopen('php://temp', 'r+');
            fputcsv($handle, $columns);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            rewind($handle);
            $csv = stream_get_contents($handle);
            fclose($handle);

            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);
        }

        $html = view('exports.laporan', [
            'title' => $title,
            'columns' => $columns,
            'rows' => $rows,
        ])->render();

        if (class_exists(\Dompdf\Dompdf::class)) {
            $dompdf = new \Dompdf\Dompdf([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $filename = strtolower((string) $type)."_{$today}.pdf";

            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);
        }

        $filename = strtolower((string) $type)."_{$today}.html";

        return response($html, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
