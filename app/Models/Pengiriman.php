<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengirimans';

    protected $fillable = [
        'order_id',
        'barang_keluar_id',
        'metode_kirim',
        'nama_ekspedisi',
        'no_resi',
        'tanggal_kirim',
        'tanggal_terima',
        'status_pengiriman',
        'biaya_kirim',
        'alamat_kirim_snapshot',
        'penerima',
        'catatan',
    ];

    protected $casts = [
        'tanggal_kirim' => 'datetime',
        'tanggal_terima' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class);
    }
}
