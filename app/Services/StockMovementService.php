<?php

namespace App\Services;

use App\Models\StockMovement;
use Illuminate\Support\Facades\Schema;

class StockMovementService
{
    public function record(array $payload): void
    {
        if (! Schema::hasTable('stock_movements')) {
            return;
        }

        StockMovement::create([
            'data_barang_id' => (int) $payload['data_barang_id'],
            'order_id' => $payload['order_id'] ?? null,
            'movement_type' => (string) $payload['movement_type'],
            'qty_change' => (int) $payload['qty_change'],
            'qty_before' => max(0, (int) ($payload['qty_before'] ?? 0)),
            'qty_after' => max(0, (int) ($payload['qty_after'] ?? 0)),
            'reference_type' => $payload['reference_type'] ?? null,
            'reference_id' => $payload['reference_id'] ?? null,
            'notes' => $payload['notes'] ?? null,
            'created_by' => $payload['created_by'] ?? null,
        ]);
    }
}
