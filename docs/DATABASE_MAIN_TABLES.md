# Database Utama (Implementasi TheVoid)

Dokumen ini memetakan struktur yang diminta ke implementasi aktual project.

## Tabel dan Mapping

1. `users`
- Akun semua role.
- Kolom utama: `name`, `username`, `email`, `password`, `role`.

2. `roles`
- Pada implementasi saat ini, role disimpan langsung di `users.role`.
- Permission matrix dikelola pada `config/role_permissions.php`.

3. `customers`
- Profil pelanggan.
- Relasi: `customers.user_id -> users.id`.

4. `categories`
- Implementasi: `kategori_barangs`.

5. `products`
- Implementasi: `data_barangs`.

6. `stocks`
- Implementasi stok fisik: `data_barangs.stok`.
- Stok minimum: `data_barangs.stok_minimum`.
- Lokasi rak: `data_barangs.lokasi_rak`.

7. `stock_movements`
- Implementasi: `stock_movements` (baru).
- Menyimpan mutasi kuantitatif (before/after/change).

8. `orders`
- Implementasi: `orders`.
- Menyimpan header pesanan dan status proses.

9. `order_items`
- Implementasi: `order_items`.
- Detail item per order.

10. `shipments`
- Implementasi: `pengirimans`.

11. `notifications`
- Implementasi: `notifications` (baru).
- Notifikasi status order per user.

## Relasi Singkat

- `users 1..1 customers`
- `customers 1..n orders`
- `orders 1..n order_items`
- `data_barangs 1..n order_items`
- `orders 1..n stock_reservations`
- `orders 1..1 pengirimans`
- `users 1..n notifications`
- `data_barangs 1..n stock_movements`

## Catatan Reserved Stock

Reserved stock disimpan terpisah di `stock_reservations`, sehingga:
- stok fisik tetap ada di `data_barangs.stok`
- stok tersedia dihitung sebagai: `stok fisik - reservasi aktif`
