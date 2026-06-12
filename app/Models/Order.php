<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_WAITING_RESTOCK = 'waiting_restock';

    public const STATUS_RESERVED = 'reserved';

    public const STATUS_PICKING = 'picking';

    public const STATUS_PACKED = 'packed';

    public const STATUS_SHIPPED = 'shipped';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_REJECTED = 'rejected';

    public const PAYMENT_UNPAID = 'unpaid';

    public const PAYMENT_AWAITING_VERIFICATION = 'awaiting_verification';

    public const PAYMENT_VERIFIED = 'verified';

    public const PAYMENT_REJECTED = 'rejected';

    protected $fillable = [
        'nomor_pesanan',
        'customer_id',
        'tanggal_pesanan',
        'status_pesanan',
        'order_source',
        'total_item',
        'total_qty',
        'total_nilai',
        'catatan',
        'payment_status',
        'payment_proof_path',
        'payment_note',
        'payment_uploaded_at',
        'payment_verified_by',
        'payment_verified_at',
        'payment_reject_note',
        'approved_by',
        'approved_at',
        'created_by',
    ];

    protected $casts = [
        'tanggal_pesanan' => 'datetime',
        'approved_at' => 'datetime',
        'payment_uploaded_at' => 'datetime',
        'payment_verified_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function barangKeluar()
    {
        return $this->hasOne(BarangKeluar::class);
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class);
    }

    public function stockReservations()
    {
        return $this->hasMany(StockReservation::class);
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_WAITING_RESTOCK,
            self::STATUS_RESERVED,
            self::STATUS_PICKING,
            self::STATUS_PACKED,
            self::STATUS_SHIPPED,
            self::STATUS_COMPLETED,
            self::STATUS_REJECTED,
        ];
    }
}
