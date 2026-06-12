# Blueprint Sistem Warehouse Elektronik

## Struktur Modul
- `Overview`: Dashboard.
- `Master Data`: Data Barang, Kategori, Data Customer.
- `Sales & Order`: Pesanan (order entry, reservasi stok, proses picking/packing).
- `Warehouse Operations`: Barang Masuk, Pengeluaran Barang, Monitoring Stok.
- `Shipping`: Pengiriman (order packed/shipped/completed).
- `Reports`: Laporan stok dan pergerakan.
- `Administration`: User/Admin, FAQ Chatbot, Pengaturan.

## Alur Operasional
1. Admin input pesanan customer (`status: pending`).
2. Sistem cek stok tersedia berdasarkan stok fisik dikurangi reservasi aktif order lain.
3. Jika cukup, sistem membuat reservasi stok (`status: reserved`).
4. Jika tidak cukup, sistem menandai `waiting_restock`.
5. Gudang mulai `picking` untuk order reserved.
6. Saat `packed`, sistem mengurangi stok fisik, membuat dokumen barang keluar, dan fulfill reservasi.
7. Admin input data kirim wajib (metode, ekspedisi, resi) lalu `status: shipped`.
8. Saat barang diterima, admin klik `terkirim` lalu order `completed`.

## Role & Permission
- `Superadmin`:
  - Akses penuh seluruh modul.
  - Kelola user/admin.
  - Pengaturan dan konfigurasi fitur.
- `Admin`:
  - Kelola master data operasional.
  - Input dan proses pesanan sampai pengiriman.
  - Akses laporan operasional.
- Role tambahan yang direkomendasikan fase berikutnya:
  - `Warehouse Admin`: fokus picking, packing, stock opname.
  - `Sales Admin`: fokus customer dan order entry.
  - `Viewer/Manager`: hanya baca dashboard dan laporan.

## Standar Status
- Order: `pending`, `waiting_restock`, `reserved`, `picking`, `packed`, `shipped`, `completed`, `rejected`.
- Reservasi stok: `active`, `fulfilled`, `released`.
- Pengiriman: `siap_pickup`, `dikirim`, `terkirim`.

## Rekomendasi UI/UX
- Gunakan bahasa konsisten Indonesia di seluruh label.
- Dashboard wajib punya blok:
  - KPI stok (total, menipis, habis, nilai inventaris).
  - KPI antrian order (pending, reserved, picking, packed/shipped).
  - Aktivitas terbaru dan stok kritis.
- Di halaman pesanan gunakan tombol aksi berbasis status agar user tidak salah langkah:
  - `Reserve`, `Start Picking`, `Pack`, `Kirim`, `Terkirim`.

## Pengembangan Tahap Berikutnya
1. Portal customer untuk cek status dan tracking.
2. Integrasi API ekspedisi (cek ongkir + update status otomatis).
3. Laporan lanjutan:
   - Lead time order ke kirim.
   - Fill-rate.
   - Aging stok dan dead-stock.
