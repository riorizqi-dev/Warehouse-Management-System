# ERD dan Alur Web TheVoid (Versi Final)

## ERD Ringkas

Entitas utama:
- `users`
- `customers`
- `kategori_barangs`
- `data_barangs`
- `orders`
- `order_items`
- `stock_reservations`
- `pengirimans`
- `stock_movements`
- `notifications`

Relasi utama:
- `users (1) - (1) customers` untuk role customer.
- `customers (1) - (n) orders`.
- `orders (1) - (n) order_items`.
- `data_barangs (1) - (n) order_items`.
- `orders (1) - (n) stock_reservations`.
- `orders (1) - (1) pengirimans`.
- `users (1) - (n) notifications`.

## Alur Web

1. User login.
2. Redirect dashboard berdasarkan role:
   - customer -> portal customer
   - sales -> order queue
   - gudang -> order reserved/picking
   - kurir -> order siap kirim
   - admin/superadmin -> dashboard utama
3. Customer checkout membuat order + reservasi stok.
4. Sales reserve/reject order.
5. Gudang proses picking dan packing.
6. Kurir proses shipment dan delivery.
7. Customer memantau status dan notifikasi.

## Alur Saat Stok Tidak Cukup

- Sistem set status `waiting_restock`.
- Pesanan tidak lanjut ke packing.
- Setelah restock, sales dapat reserve ulang.

## Kontrol Role

- Superadmin: full control.
- Admin: operasional penuh tanpa kontrol role sistem.
- Staff Gudang: stok + picking/packing.
- Staff Penjualan: customer + reserve/reject.
- Kurir: ship/deliver.
- Customer: katalog + order + tracking.

## Catatan Implementasi

- Route dilindungi middleware `role`.
- Permission matrix tersedia di `config/role_permissions.php`.
- Label status order terpusat di helper `order_status_label()`.
- Semua perubahan stok penting dicatat di `stock_movements`.
