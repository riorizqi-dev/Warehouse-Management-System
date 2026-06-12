<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluarItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_keluar_id',
        'data_barang_id',
        'qty_keluar',
        'stok_sebelum',
        'stok_sesudah',
        'harga_snapshot',
        'subtotal',
    ];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class);
    }

    public function dataBarang()
    {
        return $this->belongsTo(DataBarang::class, 'data_barang_id');
    }
}
