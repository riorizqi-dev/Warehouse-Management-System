<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBarang extends Model
{
    use HasFactory;

    protected $table = 'data_barangs';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'merek',
        'tipe_model',
        'garansi_bulan',
        'lokasi_rak',
        'stok',
        'stok_minimum',
        'harga',
        'harga_beli',
        'harga_jual',
        'satuan',
        'foto_barang', // jangan lupa tambahin kolom foto_barang
        'kategori_id',
        'user_id',
    ];

    public function getFotoUrlAttribute()
    {
        $placeholder = 'data:image/svg+xml;base64,'.base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300"><rect fill="#e0e0e0" width="400" height="300"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#999" font-family="Arial" font-size="20">No Image</text></svg>');

        if (! $this->foto_barang || empty($this->foto_barang)) {
            return $placeholder;
        }

        $url = asset('storage/'.$this->foto_barang);

        return $url;
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'data_barang_id');
    }

    public function barangKeluarItems()
    {
        return $this->hasMany(BarangKeluarItem::class, 'data_barang_id');
    }

    public function stockReservations()
    {
        return $this->hasMany(StockReservation::class, 'data_barang_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'data_barang_id');
    }
}
