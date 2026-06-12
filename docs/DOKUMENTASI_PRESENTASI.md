# Dokumentasi Presentasi Project Web TheVoid

## 1. Ringkasan Sistem

`TheVoid` adalah sistem gudang barang elektronik berbasis Laravel dengan role management dan alur order end-to-end:

Customer melihat barang -> checkout -> sistem reservasi stok -> tim sales verifikasi -> tim gudang picking/packing -> kurir kirim -> customer memantau status sampai selesai.

## 2. Role dan Hak Akses

Role aktif dalam sistem:
- `superadmin`: akses penuh, termasuk manajemen user, role, toggle fitur.
- `admin`: kontrol operasional lintas modul (barang, order, gudang, pengiriman, laporan).
- `staff_gudang`: proses stok, barang masuk/keluar, picking, packing.
- `staff_penjualan`: kelola customer, input order, reserve/reject pesanan.
- `kurir`: update pengiriman (`shipped`, `delivered`).
- `customer`: katalog, keranjang, checkout, tracking order.

Permission matrix disimpan di `config/role_permissions.php`.

## 3. Struktur Data Utama

Tabel inti operasional:
- `users`
- `customers`
- `kategori_barangs`
- `data_barangs`
- `orders`
- `order_items`
- `stock_reservations`
- `barang_keluars`
- `barang_keluar_items`
- `pengirimans`
- `inventory_logs`
- `stock_movements` (baru)
- `notifications` (baru)

Relasi penting:
- `customers.user_id -> users.id`
- `orders.customer_id -> customers.id`
- `order_items.order_id -> orders.id`
- `order_items.data_barang_id -> data_barangs.id`
- `stock_reservations` mengikat order item terhadap stok barang
- `pengirimans.order_id -> orders.id`
- `notifications.user_id -> users.id`

## 4. Alur Status Pesanan

Status order yang dipakai sistem:
- `pending`
- `waiting_restock`
- `reserved`
- `picking`
- `packed`
- `shipped`
- `completed`
- `rejected`

Aturan proses:
1. Order dibuat (`pending`).
2. Sistem cek stok tersedia (stok fisik dikurangi reservasi aktif).
3. Jika cukup -> `reserved`; jika tidak -> `waiting_restock`.
4. Gudang `picking` lalu `packed`.
5. Kurir ubah ke `shipped`.
6. Setelah diterima, status `completed`.

## 5. Manajemen Stok Profesional

Konsep stok yang diimplementasi:
- **Stok fisik**: `data_barangs.stok`
- **Stok reserved**: `stock_reservations` status `active`
- **Stok tersedia**: stok fisik - reservasi aktif
- **Stok minimum**: `data_barangs.stok_minimum`
- **Lokasi rak**: `data_barangs.lokasi_rak`
- **Mutasi stok**:
  - audit umum: `inventory_logs`
  - mutasi kuantitatif: `stock_movements` (baru)

Anti double-order:
- transaksi DB (`DB::transaction`)
- lock row stok saat checkout/packing (`lockForUpdate`)
- reservasi stok per item order
- release reservasi saat reject

## 6. Notifikasi Sistem

Notifikasi tersimpan di tabel `notifications` dan dikirim otomatis saat:
- order dibuat
- status berubah (`reserved`, `waiting_restock`, `picking`, `packed`, `shipped`, `completed`, `rejected`)

Customer melihat notifikasi di dashboard portal.

## 7. Struktur Menu (Siap Demo)

- **Master Data**: Data Barang, Kategori, Customer
- **Transaksi**: Pesanan
- **Operasional Gudang**: Barang Masuk, Barang Keluar, Stok, Picking/Packing
- **Pengiriman**: Dispatch, Resi, Status Kirim
- **Laporan**: stok, mutasi, order
- **Pengaturan**: profil dan konfigurasi sistem

## 8. UI/UX yang Sudah Dirapikan

- Label status order diseragamkan melalui helper `order_status_label()`.
- Sidebar ditampilkan berdasarkan role/menu permission.
- Portal customer mobile-friendly dengan bottom navigation.
- Dashboard customer menampilkan KPI order + notifikasi sistem.

## 9. Checklist Deploy/Run

1. Jalankan migrasi baru:
   - `php artisan migrate`
2. Seed akun role:
   - `php artisan db:seed --class=UserSeeder`
3. Login sesuai `ACCOUNTS.txt`.

## 10. Nilai Jual Saat Presentasi

- Alur bisnis nyata: dari order sampai pengiriman selesai.
- Kontrol akses tegas per role operasional.
- Stok aman (reserved + lock transaksi + mutasi tercatat).
- Customer portal dan backoffice terintegrasi dalam satu sistem.
