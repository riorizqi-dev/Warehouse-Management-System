<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_item_id',
        'data_barang_id',
        'qty_reserved',
        'status',
        'reserved_by',
        'reserved_at',
        'fulfilled_at',
        'released_at',
        'notes',
    ];

    protected $casts = [
        'reserved_at' => 'datetime',
        'fulfilled_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function dataBarang()
    {
        return $this->belongsTo(DataBarang::class, 'data_barang_id');
    }

    public function reservedBy()
    {
        return $this->belongsTo(User::class, 'reserved_by');
    }
}
