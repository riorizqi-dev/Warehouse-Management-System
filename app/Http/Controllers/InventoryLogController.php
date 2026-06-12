<?php

namespace App\Http\Controllers;

use App\Models\InventoryLog;
use Illuminate\Http\Request;

class InventoryLogController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        abort_unless($user && $user->canManageWarehouse(), 403);

        $logType = strtolower((string) $request->query('type', 'all'));
        $keyword = trim((string) $request->query('q', ''));
        if (! in_array($logType, ['masuk', 'keluar', 'all'], true)) {
            $logType = 'all';
        }

        $query = InventoryLog::with(['user', 'dataBarang'])->latest();

        if ($logType === 'masuk') {
            $query->whereIn('action', ['create', 'stock_in', 'order_reserved']);
        } elseif ($logType === 'keluar') {
            $query->whereIn('action', ['delete', 'stock_out', 'order_reservation_released']);
        }

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('action', 'like', '%'.$keyword.'%')
                    ->orWhereHas('user', function ($uq) use ($keyword) {
                        $uq->where('name', 'like', '%'.$keyword.'%');
                    })
                    ->orWhereHas('dataBarang', function ($bq) use ($keyword) {
                        $bq->where('nama_barang', 'like', '%'.$keyword.'%');
                    });
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        $pageTitle = match ($logType) {
            'masuk' => 'Riwayat Barang Masuk',
            'keluar' => 'Riwayat Barang Keluar',
            default => 'Riwayat Perubahan Data Barang',
        };

        $pageDescription = match ($logType) {
            'masuk' => 'Menampilkan aktivitas penambahan barang dan penambahan stok.',
            'keluar' => 'Menampilkan aktivitas pengurangan/hapus barang dan pengurangan stok.',
            default => 'Menampilkan seluruh aktivitas perubahan data barang.',
        };

        return view('admin.inventory-logs', compact('logs', 'logType', 'pageTitle', 'pageDescription'));
    }
}
