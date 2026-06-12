# Sistem Gudang Elektronik - Role, Alur, dan Stok

## Role Utama
- `superadmin`: akses penuh, kelola user + permission + fitur.
- `admin`: kelola operasional harian lintas modul.
- `staff_gudang`: picking, packing, barang masuk/keluar, monitoring stok.
- `staff_penjualan`: kelola customer, input order, reserve/reject order.
- `kurir`: proses pengiriman, update shipped/delivered.
- `customer`: lihat katalog, checkout, pantau status pesanan.

## Alur Bisnis Utama
1. Customer registrasi dan login.
2. Customer melihat katalog lalu membuat pesanan.
3. Sistem cek stok tersedia vs stok reserved.
4. Staff penjualan verifikasi lalu reserve order.
5. Staff gudang picking dan packing.
6. Kurir kirim barang dan update resi/status.
7. Customer memantau status sampai selesai.

## Alur Jika Stok Tidak Cukup
- Status order menjadi `waiting_restock`.
- Order tetap tercatat, belum dipacking.
- Setelah restock, staff penjualan dapat reserve ulang.

## Manajemen Stok
- `stok fisik`: kolom `data_barangs.stok`.
- `stok reserved`: tabel `stock_reservations` dengan status `active`.
- `stok tersedia`: `stok fisik - total reserved aktif`.
- `stok minimum`: kolom `stok_minimum` per barang.
- Mutasi stok tercatat di `inventory_logs`.

## Status Pesanan Standar
- `pending`
- `waiting_restock`
- `reserved`
- `picking`
- `packed`
- `shipped`
- `completed`
- `rejected`

## Prinsip Anti Double Order
- Validasi checkout menggunakan transaksi DB.
- Perhitungan stok selalu kurangi reservasi aktif.
- Reservasi disimpan per item order (`stock_reservations`).
- Reservasi dilepas otomatis saat order ditolak.

## Struktur Menu Fungsional
- Master Data: Barang, Kategori, Customer.
- Transaksi: Pesanan.
- Operasional Gudang: Barang Masuk/Keluar, Stok, Picking/Packing.
- Pengiriman: Dispatch, Resi, Status Kirim.
- Laporan: Stok, Pergerakan, Pesanan, Pengiriman.
- Pengaturan: profil akun + konfigurasi fitur.
