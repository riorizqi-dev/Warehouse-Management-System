const fs = require('fs');
const path = require('path');
const outDir = path.join(__dirname, '..', 'screenshots');

function write(name, svg) {
  fs.writeFileSync(path.join(outDir, name), svg.trim());
  console.log('OK:', name);
}

write('dashboard.svg', `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 720" width="1280" height="720">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#0f1219"/><stop offset="1" stop-color="#1a1f2e"/></linearGradient>
    <linearGradient id="o" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#f97316"/><stop offset="1" stop-color="#ea580c"/></linearGradient>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#22c55e"/><stop offset="1" stop-color="#16a34a"/></linearGradient>
    <linearGradient id="b" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#3b82f6"/><stop offset="1" stop-color="#2563eb"/></linearGradient>
    <linearGradient id="p" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#a855f7"/><stop offset="1" stop-color="#7c3aed"/></linearGradient>
    <filter id="sh"><feDropShadow dx="0" dy="2" stdDeviation="8" flood-color="#000" flood-opacity="0.4"/></filter>
  </defs>
  <rect width="1280" height="720" fill="url(#bg)"/>
  <rect x="0" y="0" width="280" height="720" fill="#11151e" filter="url(#sh)"/>
  <line x1="280" y1="0" x2="280" y2="720" stroke="#2a3348" stroke-width="1"/>
  <rect x="20" y="20" width="44" height="44" rx="10" fill="rgba(249,115,22,0.15)" stroke="#f97316" stroke-width="1.5"/>
  <polygon points="32,28 48,38 32,48 16,38" fill="none" stroke="#f97316" stroke-width="2"/>
  <line x1="32" y1="28" x2="32" y2="48" stroke="#f97316" stroke-width="2"/>
  <line x1="16" y1="38" x2="48" y2="38" stroke="#f97316" stroke-width="1.5"/>
  <text x="76" y="36" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="15" font-weight="bold">RakElektronik</text>
  <text x="76" y="52" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="11">Sistem Gudang Elektronik</text>
  <text x="24" y="100" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">OVERVIEW</text>
  <rect x="14" y="112" width="252" height="38" rx="8" fill="rgba(249,115,22,0.12)" stroke="rgba(249,115,22,0.4)"/>
  <rect x="14" y="112" width="3" height="38" rx="1.5" fill="#f97316"/>
  <text x="48" y="138" fill="#fff" font-family="Segoe UI,sans-serif" font-size="14" font-weight="600">Dashboard</text>
  <text x="24" y="180" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">MASTER DATA</text>
  <rect x="14" y="192" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="218" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Data Barang</text>
  <rect x="14" y="234" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="260" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Kategori</text>
  <rect x="14" y="276" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="302" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Data Customer</text>
  <text x="24" y="340" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">SALES & ORDER</text>
  <rect x="14" y="352" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="378" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Pesanan</text>
  <text x="24" y="420" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">WAREHOUSE OPERATIONS</text>
  <rect x="14" y="432" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="458" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Barang Masuk</text>
  <rect x="14" y="474" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="500" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Pengeluaran Barang</text>
  <rect x="14" y="516" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="542" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Stok</text>
  <text x="24" y="580" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">REPORTS</text>
  <rect x="14" y="592" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="618" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Laporan</text>
  <text x="24" y="656" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">ADMINISTRATION</text>
  <rect x="14" y="668" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="694" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Users / Admin</text>
  <rect x="296" y="0" width="984" height="60" fill="#141a26"/>
  <line x1="296" y1="60" x2="1280" y2="60" stroke="#2a3348" stroke-width="1"/>
  <rect x="310" y="15" width="240" height="32" rx="8" fill="#1c2434" stroke="#2a3348" stroke-width="1"/>
  <text x="328" y="37" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="13">Cari sesuatu...</text>
  <rect x="1120" y="14" width="34" height="34" rx="17" fill="#2a3348"/>
  <circle cx="1137" cy="25" r="7" fill="#5a6a7e"/><circle cx="1137" cy="40" r="10" fill="#5a6a7e"/>
  <text x="1164" y="32" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13" font-weight="600">Super Admin</text>
  <text x="1164" y="46" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="10">superadmin</text>
  <text x="310" y="98" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="22" font-weight="bold">Dashboard</text>
  <text x="310" y="118" fill="#7d8aa0" font-family="Segoe UI,sans-serif" font-size="13">Overview sistem gudang elektronik</text>
  <rect x="310" y="140" width="220" height="100" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#sh)"/>
  <text x="330" y="170" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Total Barang</text>
  <text x="330" y="200" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="28" font-weight="bold">1,248</text>
  <rect x="330" y="212" width="60" height="20" rx="10" fill="rgba(34,197,94,0.15)"/>
  <text x="338" y="226" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="10" font-weight="600">+12.5%</text>
  <rect x="548" y="140" width="220" height="100" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#sh)"/>
  <text x="568" y="170" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Total Stok</text>
  <text x="568" y="200" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="28" font-weight="bold">48,592</text>
  <rect x="568" y="212" width="60" height="20" rx="10" fill="rgba(59,130,246,0.15)"/>
  <text x="576" y="226" fill="#3b82f6" font-family="Segoe UI,sans-serif" font-size="10" font-weight="600">+8.3%</text>
  <rect x="786" y="140" width="220" height="100" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#sh)"/>
  <text x="806" y="170" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Pesanan Aktif</text>
  <text x="806" y="200" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="28" font-weight="bold">24</text>
  <rect x="806" y="212" width="60" height="20" rx="10" fill="rgba(249,115,22,0.15)"/>
  <text x="814" y="226" fill="#f97316" font-family="Segoe UI,sans-serif" font-size="10" font-weight="600">+5.1%</text>
  <rect x="1024" y="140" width="220" height="100" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#sh)"/>
  <text x="1044" y="170" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Stok Menipis</text>
  <text x="1044" y="200" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="28" font-weight="bold">8</text>
  <rect x="1044" y="212" width="60" height="20" rx="10" fill="rgba(239,68,68,0.15)"/>
  <text x="1052" y="226" fill="#ef4444" font-family="Segoe UI,sans-serif" font-size="10" font-weight="600">+2.0%</text>
  <rect x="310" y="260" width="520" height="280" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#sh)"/>
  <text x="330" y="290" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="15" font-weight="600">Grafik Stok Bulanan</text>
  <rect x="340" y="380" width="36" height="120" rx="4" fill="url(#o)" opacity="0.8"/>
  <rect x="395" y="350" width="36" height="150" rx="4" fill="url(#b)" opacity="0.8"/>
  <rect x="450" y="370" width="36" height="130" rx="4" fill="url(#g)" opacity="0.8"/>
  <rect x="505" y="390" width="36" height="110" rx="4" fill="url(#p)" opacity="0.8"/>
  <rect x="560" y="360" width="36" height="140" rx="4" fill="url(#o)" opacity="0.8"/>
  <rect x="615" y="340" width="36" height="160" rx="4" fill="url(#b)" opacity="0.8"/>
  <rect x="670" y="375" width="36" height="125" rx="4" fill="url(#g)" opacity="0.8"/>
  <rect x="725" y="355" width="36" height="145" rx="4" fill="url(#p)" opacity="0.8"/>
  <text x="348" y="515" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" text-anchor="middle">Jan</text>
  <text x="403" y="515" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" text-anchor="middle">Feb</text>
  <text x="458" y="515" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" text-anchor="middle">Mar</text>
  <text x="513" y="515" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" text-anchor="middle">Apr</text>
  <text x="568" y="515" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" text-anchor="middle">Mei</text>
  <text x="623" y="515" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" text-anchor="middle">Jun</text>
  <text x="678" y="515" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" text-anchor="middle">Jul</text>
  <text x="733" y="515" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" text-anchor="middle">Agu</text>
  <rect x="848" y="260" width="396" height="130" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#sh)"/>
  <text x="868" y="290" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="15" font-weight="600">Aktivitas Terbaru</text>
  <line x1="884" y1="310" x2="884" y2="375" stroke="#2a3348" stroke-width="2"/>
  <circle cx="884" cy="315" r="4" fill="#22c55e"/>
  <text x="900" y="320" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="12">Stok masuk: 50x Samsung TV 32"</text>
  <circle cx="884" cy="340" r="4" fill="#3b82f6"/>
  <text x="900" y="345" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="12">Pesanan baru #INV-2024-0891</text>
  <circle cx="884" cy="365" r="4" fill="#f97316"/>
  <text x="900" y="370" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="12">Stok menipis: LG Soundbar</text>
  <rect x="848" y="405" width="396" height="135" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#sh)"/>
  <text x="868" y="435" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="15" font-weight="600">Status Stok</text>
  <rect x="868" y="450" width="356" height="6" rx="3" fill="#2a3348"/>
  <rect x="868" y="450" width="280" height="6" rx="3" fill="#22c55e"/>
  <text x="868" y="470" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="11">Tersedia</text>
  <text x="1190" y="470" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11" font-weight="600">78%</text>
  <rect x="868" y="480" width="356" height="6" rx="3" fill="#2a3348"/>
  <rect x="868" y="480" width="65" height="6" rx="3" fill="#f97316"/>
  <text x="868" y="500" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="11">Dipesan</text>
  <text x="1190" y="500" fill="#f97316" font-family="Segoe UI,sans-serif" font-size="11" font-weight="600">18%</text>
  <rect x="868" y="510" width="356" height="6" rx="3" fill="#2a3348"/>
  <rect x="868" y="510" width="15" height="6" rx="3" fill="#ef4444"/>
  <text x="868" y="530" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="11">Stok Habis</text>
  <text x="1190" y="530" fill="#ef4444" font-family="Segoe UI,sans-serif" font-size="11" font-weight="600">4%</text>
  <text x="296" y="700" fill="#3a4a5e" font-family="Segoe UI,sans-serif" font-size="11">RakElektronik v1.0.0 &mdash; Warehouse Management System</text>
</svg>`);

write('login.svg', `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 720" width="1280" height="720">
  <defs>
    <linearGradient id="lbg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#0f1219"/><stop offset="1" stop-color="#1a1f2e"/></linearGradient>
    <filter id="lsh"><feDropShadow dx="0" dy="4" stdDeviation="24" flood-color="#000" flood-opacity="0.5"/></filter>
  </defs>
  <rect width="1280" height="720" fill="url(#lbg)"/>
  <rect x="0" y="0" width="640" height="720" fill="#11151e"/>
  <rect x="100" y="260" width="44" height="44" rx="10" fill="rgba(249,115,22,0.15)" stroke="#f97316" stroke-width="1.5"/>
  <polygon points="112,268 128,278 112,288 96,278" fill="none" stroke="#f97316" stroke-width="2"/>
  <line x1="112" y1="268" x2="112" y2="288" stroke="#f97316" stroke-width="2"/>
  <line x1="96" y1="278" x2="128" y2="278" stroke="#f97316" stroke-width="1.5"/>
  <text x="160" y="280" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="22" font-weight="bold">RakElektronik</text>
  <text x="100" y="320" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="14">Sistem Manajemen Gudang Elektronik</text>
  <text x="100" y="360" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="13">Kelola stok, pesanan, dan pengiriman barang</text>
  <text x="100" y="380" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="13">elektronik dalam satu platform terintegrasi.</text>
  <rect x="100" y="420" width="16" height="16" rx="4" fill="#22c55e"/>
  <text x="126" y="434" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="13">Multi-role access control</text>
  <rect x="100" y="450" width="16" height="16" rx="4" fill="#3b82f6"/>
  <text x="126" y="464" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="13">Real-time inventory tracking</text>
  <rect x="100" y="480" width="16" height="16" rx="4" fill="#f97316"/>
  <text x="126" y="494" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="13">Order management workflow</text>
  <rect x="100" y="510" width="16" height="16" rx="4" fill="#a855f7"/>
  <text x="126" y="524" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="13">Customer self-service portal</text>
  <rect x="740" y="110" width="460" height="500" rx="20" fill="#1c2434" filter="url(#lsh)" stroke="#2a3348" stroke-width="1"/>
  <text x="970" y="180" text-anchor="middle" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="26" font-weight="bold">Selamat Datang</text>
  <text x="970" y="210" text-anchor="middle" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="14">Masuk ke sistem manajemen gudang</text>
  <text x="780" y="260" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="13" font-weight="600">Username / Email</text>
  <rect x="780" y="272" width="380" height="44" rx="8" fill="#141a26" stroke="#2a3348" stroke-width="1.5"/>
  <text x="800" y="300" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="13">Masukkan username atau email...</text>
  <text x="780" y="348" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="13" font-weight="600">Kata Sandi</text>
  <rect x="780" y="360" width="380" height="44" rx="8" fill="#141a26" stroke="#2a3348" stroke-width="1.5"/>
  <circle cx="1110" cy="383" r="4" fill="#5a6a7e"/>
  <circle cx="1122" cy="383" r="4" fill="#5a6a7e"/>
  <circle cx="1098" cy="383" r="4" fill="#5a6a7e"/>
  <circle cx="1086" cy="383" r="4" fill="#5a6a7e"/>
  <rect x="780" y="426" width="16" height="16" rx="4" fill="#f97316"/>
  <text x="804" y="440" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Ingat saya</text>
  <text x="1074" y="440" fill="#f97316" font-family="Segoe UI,sans-serif" font-size="12">Lupa password?</text>
  <rect x="780" y="466" width="380" height="46" rx="8" fill="#f97316"/>
  <text x="970" y="494" text-anchor="middle" fill="#fff" font-family="Segoe UI,sans-serif" font-size="15" font-weight="bold">Masuk</text>
  <text x="970" y="555" text-anchor="middle" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="13">Belum punya akun? <tspan fill="#f97316">Hubungi Admin</tspan></text>
  <text x="970" y="590" text-anchor="middle" fill="#3a4a5e" font-family="Segoe UI,sans-serif" font-size="11">RakElektronik &copy; 2026</text>
</svg>`);

write('products.svg', `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 720" width="1280" height="720">
  <defs><linearGradient id="pbg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#0f1219"/><stop offset="1" stop-color="#1a1f2e"/></linearGradient><filter id="ps"><feDropShadow dx="0" dy="2" stdDeviation="8" flood-color="#000" flood-opacity="0.4"/></filter></defs>
  <rect width="1280" height="720" fill="url(#pbg)"/><rect x="0" y="0" width="280" height="720" fill="#11151e"/>
  <rect x="20" y="20" width="44" height="44" rx="10" fill="rgba(249,115,22,0.15)" stroke="#f97316" stroke-width="1.5"/>
  <polygon points="32,28 48,38 32,48 16,38" fill="none" stroke="#f97316" stroke-width="2"/>
  <line x1="32" y1="28" x2="32" y2="48" stroke="#f97316" stroke-width="2"/>
  <line x1="16" y1="38" x2="48" y2="38" stroke="#f97316" stroke-width="1.5"/>
  <text x="76" y="36" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="15" font-weight="bold">RakElektronik</text>
  <text x="76" y="52" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="11">Sistem Gudang Elektronik</text>
  <text x="24" y="100" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">OVERVIEW</text>
  <rect x="14" y="112" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="138" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="14">Dashboard</text>
  <text x="24" y="180" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">MASTER DATA</text>
  <rect x="14" y="192" width="252" height="38" rx="8" fill="rgba(249,115,22,0.12)" stroke="rgba(249,115,22,0.4)"/>
  <rect x="14" y="192" width="3" height="38" rx="1.5" fill="#f97316"/>
  <text x="48" y="218" fill="#fff" font-family="Segoe UI,sans-serif" font-size="14" font-weight="600">Data Barang</text>
  <rect x="14" y="234" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="260" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Kategori</text>
  <rect x="14" y="276" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="302" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Data Customer</text>
  <text x="24" y="340" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">SALES & ORDER</text>
  <rect x="14" y="352" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="378" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Pesanan</text>
  <text x="24" y="420" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">WAREHOUSE OPERATIONS</text>
  <rect x="14" y="432" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="458" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Barang Masuk</text>
  <rect x="14" y="474" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="500" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Pengeluaran Barang</text>
  <rect x="14" y="516" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="542" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Stok</text>
  <text x="24" y="580" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">REPORTS</text>
  <rect x="14" y="592" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="618" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Laporan</text>
  <text x="24" y="656" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">ADMINISTRATION</text>
  <rect x="14" y="668" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="694" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Users / Admin</text>
  <rect x="296" y="0" width="984" height="60" fill="#141a26"/>
  <line x1="296" y1="60" x2="1280" y2="60" stroke="#2a3348" stroke-width="1"/>
  <rect x="310" y="15" width="240" height="32" rx="8" fill="#1c2434" stroke="#2a3348" stroke-width="1"/>
  <text x="328" y="37" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="13">Cari sesuatu...</text>
  <rect x="1120" y="14" width="34" height="34" rx="17" fill="#2a3348"/>
  <circle cx="1137" cy="25" r="7" fill="#5a6a7e"/><circle cx="1137" cy="40" r="10" fill="#5a6a7e"/>
  <text x="1164" y="32" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13" font-weight="600">Super Admin</text><text x="1164" y="46" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="10">superadmin</text>
  <text x="310" y="98" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="22" font-weight="bold">Data Barang</text>
  <text x="310" y="118" fill="#7d8aa0" font-family="Segoe UI,sans-serif" font-size="13">Manajemen produk elektronik</text>
  <rect x="1060" y="76" width="140" height="36" rx="8" fill="#f97316"/>
  <text x="1130" y="100" text-anchor="middle" fill="#fff" font-family="Segoe UI,sans-serif" font-size="13" font-weight="bold">+ Tambah</text>
  <rect x="310" y="140" width="930" height="50" rx="10" fill="#1c2434" stroke="#2a3348" stroke-width="1"/>
  <rect x="330" y="152" width="200" height="28" rx="6" fill="#141a26" stroke="#2a3348" stroke-width="1"/>
  <text x="340" y="171" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="12">Cari produk...</text>
  <rect x="550" y="152" width="120" height="28" rx="6" fill="#141a26" stroke="#2a3348" stroke-width="1"/>
  <text x="570" y="171" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="12">Kategori</text>
  <rect x="690" y="152" width="120" height="28" rx="6" fill="#141a26" stroke="#2a3348" stroke-width="1"/>
  <text x="710" y="171" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="12">Urutkan</text>
  <rect x="310" y="210" width="930" height="340" rx="10" fill="#1c2434" stroke="#2a3348" stroke-width="1"/>
  <rect x="310" y="210" width="930" height="40" rx="10" fill="#141a26"/>
  <text x="330" y="236" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Gambar</text>
  <text x="410" y="236" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Nama Barang</text>
  <text x="630" y="236" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Kategori</text>
  <text x="790" y="236" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Harga</text>
  <text x="910" y="236" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Stok</text>
  <text x="1020" y="236" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Aksi</text>
  <line x1="310" y1="250" x2="1240" y2="250" stroke="#2a3348" stroke-width="1"/>
  <rect x="330" y="264" width="40" height="40" rx="6" fill="rgba(59,130,246,0.15)"/>
  <rect x="335" y="269" width="30" height="30" rx="4" fill="#3b82f6" opacity="0.3"/>
  <text x="410" y="290" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">Samsung Galaxy S24</text>
  <text x="630" y="290" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Elektronik</text>
  <text x="790" y="290" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">Rp12.000.000</text>
  <rect x="900" y="278" width="50" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="925" y="293" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">50</text>
  <line x1="310" y1="314" x2="1240" y2="314" stroke="#1c2434" stroke-width="1"/>
  <rect x="330" y="328" width="40" height="40" rx="6" fill="rgba(249,115,22,0.15)"/>
  <rect x="335" y="333" width="30" height="30" rx="4" fill="#f97316" opacity="0.3"/>
  <text x="410" y="354" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">LG 43" 4K Smart TV</text>
  <text x="630" y="354" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Elektronik</text>
  <text x="790" y="354" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">Rp5.500.000</text>
  <rect x="900" y="342" width="50" height="22" rx="11" fill="rgba(249,115,22,0.15)"/>
  <text x="925" y="357" text-anchor="middle" fill="#f97316" font-family="Segoe UI,sans-serif" font-size="11">12</text>
  <line x1="310" y1="378" x2="1240" y2="378" stroke="#1c2434" stroke-width="1"/>
  <rect x="330" y="392" width="40" height="40" rx="6" fill="rgba(168,85,247,0.15)"/>
  <rect x="335" y="397" width="30" height="30" rx="4" fill="#a855f7" opacity="0.3"/>
  <text x="410" y="418" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">Sony WH-1000XM5</text>
  <text x="630" y="418" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Aksesoris</text>
  <text x="790" y="418" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">Rp3.200.000</text>
  <rect x="900" y="406" width="50" height="22" rx="11" fill="rgba(239,68,68,0.15)"/>
  <text x="925" y="421" text-anchor="middle" fill="#ef4444" font-family="Segoe UI,sans-serif" font-size="11">3</text>
  <line x1="310" y1="442" x2="1240" y2="442" stroke="#1c2434" stroke-width="1"/>
  <rect x="330" y="456" width="40" height="40" rx="6" fill="rgba(34,197,94,0.15)"/>
  <rect x="335" y="461" width="30" height="30" rx="4" fill="#22c55e" opacity="0.3"/>
  <text x="410" y="482" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">MacBook Air M3</text>
  <text x="630" y="482" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Laptop</text>
  <text x="790" y="482" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">Rp18.000.000</text>
  <rect x="900" y="470" width="50" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="925" y="485" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">25</text>
</svg>`);

write('inventory.svg', `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 720" width="1280" height="720">
  <defs><linearGradient id="ibg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#0f1219"/><stop offset="1" stop-color="#1a1f2e"/></linearGradient><filter id="is"><feDropShadow dx="0" dy="2" stdDeviation="8" flood-color="#000" flood-opacity="0.4"/></filter></defs>
  <rect width="1280" height="720" fill="url(#ibg)"/><rect x="0" y="0" width="280" height="720" fill="#11151e"/>
  <rect x="20" y="20" width="44" height="44" rx="10" fill="rgba(249,115,22,0.15)" stroke="#f97316" stroke-width="1.5"/>
  <polygon points="32,28 48,38 32,48 16,38" fill="none" stroke="#f97316" stroke-width="2"/>
  <line x1="32" y1="28" x2="32" y2="48" stroke="#f97316" stroke-width="2"/>
  <line x1="16" y1="38" x2="48" y2="38" stroke="#f97316" stroke-width="1.5"/>
  <text x="76" y="36" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="15" font-weight="bold">RakElektronik</text><text x="76" y="52" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="11">Sistem Gudang Elektronik</text>
  <text x="24" y="100" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">OVERVIEW</text>
  <rect x="14" y="112" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="138" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="14">Dashboard</text>
  <text x="24" y="180" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">MASTER DATA</text>
  <rect x="14" y="192" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="218" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Data Barang</text>
  <rect x="14" y="234" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="260" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Kategori</text>
  <rect x="14" y="276" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="302" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Data Customer</text>
  <text x="24" y="340" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">SALES & ORDER</text>
  <rect x="14" y="352" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="378" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Pesanan</text>
  <text x="24" y="420" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">WAREHOUSE OPERATIONS</text>
  <rect x="14" y="432" width="252" height="38" rx="8" fill="rgba(249,115,22,0.12)" stroke="rgba(249,115,22,0.4)"/>
  <rect x="14" y="432" width="3" height="38" rx="1.5" fill="#f97316"/>
  <text x="48" y="458" fill="#fff" font-family="Segoe UI,sans-serif" font-size="14" font-weight="600">Barang Masuk</text>
  <rect x="14" y="474" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="500" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Pengeluaran Barang</text>
  <rect x="14" y="516" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="542" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Stok</text>
  <text x="24" y="580" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">REPORTS</text>
  <rect x="14" y="592" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="618" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Laporan</text>
  <text x="24" y="656" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">ADMINISTRATION</text>
  <rect x="14" y="668" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="694" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Users / Admin</text>
  <rect x="296" y="0" width="984" height="60" fill="#141a26"/>
  <line x1="296" y1="60" x2="1280" y2="60" stroke="#2a3348" stroke-width="1"/>
  <rect x="1120" y="14" width="34" height="34" rx="17" fill="#2a3348"/>
  <circle cx="1137" cy="25" r="7" fill="#5a6a7e"/><circle cx="1137" cy="40" r="10" fill="#5a6a7e"/>
  <text x="1164" y="32" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13" font-weight="600">Super Admin</text><text x="1164" y="46" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="10">superadmin</text>
  <text x="310" y="98" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="22" font-weight="bold">Barang Masuk</text>
  <text x="310" y="118" fill="#7d8aa0" font-family="Segoe UI,sans-serif" font-size="13">Riwayat penerimaan stok barang</text>
  <rect x="310" y="140" width="220" height="90" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#is)"/>
  <text x="330" y="170" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Hari Ini</text>
  <text x="330" y="200" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="24" font-weight="bold">24</text>
  <rect x="548" y="140" width="220" height="90" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#is)"/>
  <text x="568" y="170" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Minggu Ini</text>
  <text x="568" y="200" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="24" font-weight="bold">156</text>
  <rect x="786" y="140" width="220" height="90" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#is)"/>
  <text x="806" y="170" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Bulan Ini</text>
  <text x="806" y="200" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="24" font-weight="bold">589</text>
  <rect x="1024" y="140" width="220" height="90" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#is)"/>
  <text x="1044" y="170" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Supplier</text>
  <text x="1044" y="200" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="24" font-weight="bold">12</text>
  <rect x="310" y="250" width="930" height="300" rx="10" fill="#1c2434" stroke="#2a3348" stroke-width="1"/>
  <rect x="310" y="250" width="930" height="40" rx="10" fill="#141a26"/>
  <text x="330" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Tanggal</text>
  <text x="460" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Produk</text>
  <text x="660" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Supplier</text>
  <text x="830" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Jumlah</text>
  <text x="980" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">PIC</text>
  <text x="1120" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Status</text>
  <line x1="310" y1="290" x2="1240" y2="290" stroke="#2a3348" stroke-width="1"/>
  <text x="330" y="316" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="13">14 Jun 2026</text>
  <text x="460" y="316" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">Samsung Galaxy S24</text>
  <text x="660" y="316" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">PT Samsung</text>
  <text x="840" y="316" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">50</text>
  <text x="980" y="316" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Staff Gudang</text>
  <rect x="1110" y="304" width="60" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="1140" y="319" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">Selesai</text>
  <line x1="310" y1="330" x2="1240" y2="330" stroke="#1c2434" stroke-width="1"/>
  <text x="330" y="356" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="13">13 Jun 2026</text>
  <text x="460" y="356" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">LG 43" 4K TV</text>
  <text x="660" y="356" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">PT LG</text>
  <text x="840" y="356" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">30</text>
  <text x="980" y="356" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Staff Gudang</text>
  <rect x="1110" y="344" width="60" height="22" rx="11" fill="rgba(59,130,246,0.15)"/>
  <text x="1140" y="359" text-anchor="middle" fill="#3b82f6" font-family="Segoe UI,sans-serif" font-size="11">Proses</text>
  <line x1="310" y1="370" x2="1240" y2="370" stroke="#1c2434" stroke-width="1"/>
  <text x="330" y="396" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="13">12 Jun 2026</text>
  <text x="460" y="396" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">MacBook Air M3</text>
  <text x="660" y="396" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">PT Apple</text>
  <text x="840" y="396" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">15</text>
  <text x="980" y="396" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Staff Gudang</text>
  <rect x="1110" y="384" width="60" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="1140" y="399" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">Selesai</text>
</svg>`);

write('stock-in.svg', `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 720" width="1280" height="720">
  <defs><linearGradient id="sbg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#0f1219"/><stop offset="1" stop-color="#1a1f2e"/></linearGradient><filter id="ss"><feDropShadow dx="0" dy="2" stdDeviation="8" flood-color="#000" flood-opacity="0.4"/></filter></defs>
  <rect width="1280" height="720" fill="url(#sbg)"/><rect x="0" y="0" width="280" height="720" fill="#11151e"/>
  <rect x="20" y="20" width="44" height="44" rx="10" fill="rgba(249,115,22,0.15)" stroke="#f97316" stroke-width="1.5"/>
  <polygon points="32,28 48,38 32,48 16,38" fill="none" stroke="#f97316" stroke-width="2"/>
  <line x1="32" y1="28" x2="32" y2="48" stroke="#f97316" stroke-width="2"/>
  <line x1="16" y1="38" x2="48" y2="38" stroke="#f97316" stroke-width="1.5"/>
  <text x="76" y="36" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="15" font-weight="bold">RakElektronik</text><text x="76" y="52" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="11">Sistem Gudang Elektronik</text>
  <text x="24" y="100" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">OVERVIEW</text>
  <rect x="14" y="112" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="138" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="14">Dashboard</text>
  <text x="24" y="180" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">MASTER DATA</text>
  <rect x="14" y="192" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="218" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Data Barang</text>
  <rect x="14" y="234" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="260" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Kategori</text>
  <rect x="14" y="276" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="302" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Data Customer</text>
  <text x="24" y="340" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">SALES & ORDER</text>
  <rect x="14" y="352" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="378" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Pesanan</text>
  <text x="24" y="420" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">WAREHOUSE OPERATIONS</text>
  <rect x="14" y="432" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="458" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Barang Masuk</text>
  <rect x="14" y="474" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="500" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Pengeluaran Barang</text>
  <rect x="14" y="516" width="252" height="38" rx="8" fill="rgba(249,115,22,0.12)" stroke="rgba(249,115,22,0.4)"/>
  <rect x="14" y="516" width="3" height="38" rx="1.5" fill="#f97316"/>
  <text x="48" y="542" fill="#fff" font-family="Segoe UI,sans-serif" font-size="14" font-weight="600">Stok</text>
  <text x="24" y="580" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">REPORTS</text>
  <rect x="14" y="592" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="618" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Laporan</text>
  <text x="24" y="656" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">ADMINISTRATION</text>
  <rect x="14" y="668" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="694" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Users / Admin</text>
  <rect x="296" y="0" width="984" height="60" fill="#141a26"/>
  <line x1="296" y1="60" x2="1280" y2="60" stroke="#2a3348" stroke-width="1"/>
  <rect x="1120" y="14" width="34" height="34" rx="17" fill="#2a3348"/>
  <circle cx="1137" cy="25" r="7" fill="#5a6a7e"/><circle cx="1137" cy="40" r="10" fill="#5a6a7e"/>
  <text x="1164" y="32" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13" font-weight="600">Super Admin</text><text x="1164" y="46" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="10">superadmin</text>
  <text x="310" y="98" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="22" font-weight="bold">Stok Barang</text>
  <text x="310" y="118" fill="#7d8aa0" font-family="Segoe UI,sans-serif" font-size="13">Monitoring stok barang elektronik</text>
  <rect x="310" y="140" width="220" height="90" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#ss)"/>
  <text x="330" y="170" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Total Stok</text>
  <text x="330" y="200" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="24" font-weight="bold">2,847</text>
  <rect x="548" y="140" width="220" height="90" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#ss)"/>
  <text x="568" y="170" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Tersedia</text>
  <text x="568" y="200" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="24" font-weight="bold">2,645</text>
  <rect x="786" y="140" width="220" height="90" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#ss)"/>
  <text x="806" y="170" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Dipesan</text>
  <text x="806" y="200" fill="#f97316" font-family="Segoe UI,sans-serif" font-size="24" font-weight="bold">202</text>
  <rect x="1024" y="140" width="220" height="90" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#ss)"/>
  <text x="1044" y="170" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Minimum Stok</text>
  <text x="1044" y="200" fill="#ef4444" font-family="Segoe UI,sans-serif" font-size="24" font-weight="bold">8</text>
  <rect x="310" y="250" width="930" height="300" rx="10" fill="#1c2434" stroke="#2a3348" stroke-width="1"/>
  <rect x="310" y="250" width="930" height="40" rx="10" fill="#141a26"/>
  <text x="330" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Produk</text>
  <text x="550" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Kategori</text>
  <text x="710" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Total</text>
  <text x="830" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Tersedia</text>
  <text x="970" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Min. Stok</text>
  <text x="1120" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Status</text>
  <line x1="310" y1="290" x2="1240" y2="290" stroke="#2a3348" stroke-width="1"/>
  <text x="330" y="316" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">Samsung Galaxy S24</text>
  <text x="550" y="316" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Elektronik</text>
  <text x="720" y="316" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">50</text>
  <text x="840" y="316" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">40</text>
  <text x="980" y="316" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">10</text>
  <rect x="1110" y="304" width="60" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="1140" y="319" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">Aman</text>
  <line x1="310" y1="330" x2="1240" y2="330" stroke="#1c2434" stroke-width="1"/>
  <text x="330" y="356" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">LG 43" 4K TV</text>
  <text x="550" y="356" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Elektronik</text>
  <text x="720" y="356" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">30</text>
  <text x="840" y="356" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">28</text>
  <text x="980" y="356" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">5</text>
  <rect x="1110" y="344" width="60" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="1140" y="359" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">Aman</text>
  <line x1="310" y1="370" x2="1240" y2="370" stroke="#1c2434" stroke-width="1"/>
  <text x="330" y="396" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">Sony WH-1000XM5</text>
  <text x="550" y="396" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Aksesoris</text>
  <text x="720" y="396" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">8</text>
  <text x="840" y="396" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">2</text>
  <text x="980" y="396" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">5</text>
  <rect x="1110" y="384" width="60" height="22" rx="11" fill="rgba(249,115,22,0.15)"/>
  <text x="1140" y="399" text-anchor="middle" fill="#f97316" font-family="Segoe UI,sans-serif" font-size="11">Kritis</text>
  <line x1="310" y1="410" x2="1240" y2="410" stroke="#1c2434" stroke-width="1"/>
  <text x="330" y="436" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">MacBook Air M3</text>
  <text x="550" y="436" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Laptop</text>
  <text x="720" y="436" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">25</text>
  <text x="840" y="436" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">25</text>
  <text x="980" y="436" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">5</text>
  <rect x="1110" y="424" width="60" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="1140" y="439" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">Aman</text>
</svg>`);

write('stock-out.svg', `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 720" width="1280" height="720">
  <defs><linearGradient id="obg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#0f1219"/><stop offset="1" stop-color="#1a1f2e"/></linearGradient><filter id="os"><feDropShadow dx="0" dy="2" stdDeviation="8" flood-color="#000" flood-opacity="0.4"/></filter></defs>
  <rect width="1280" height="720" fill="url(#obg)"/><rect x="0" y="0" width="280" height="720" fill="#11151e"/>
  <rect x="20" y="20" width="44" height="44" rx="10" fill="rgba(249,115,22,0.15)" stroke="#f97316" stroke-width="1.5"/>
  <polygon points="32,28 48,38 32,48 16,38" fill="none" stroke="#f97316" stroke-width="2"/>
  <line x1="32" y1="28" x2="32" y2="48" stroke="#f97316" stroke-width="2"/>
  <line x1="16" y1="38" x2="48" y2="38" stroke="#f97316" stroke-width="1.5"/>
  <text x="76" y="36" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="15" font-weight="bold">RakElektronik</text><text x="76" y="52" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="11">Sistem Gudang Elektronik</text>
  <text x="24" y="100" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">OVERVIEW</text>
  <rect x="14" y="112" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="138" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="14">Dashboard</text>
  <text x="24" y="180" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">MASTER DATA</text>
  <rect x="14" y="192" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="218" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Data Barang</text>
  <rect x="14" y="234" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="260" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Kategori</text>
  <rect x="14" y="276" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="302" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Data Customer</text>
  <text x="24" y="340" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">SALES & ORDER</text>
  <rect x="14" y="352" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="378" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Pesanan</text>
  <text x="24" y="420" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">WAREHOUSE OPERATIONS</text>
  <rect x="14" y="432" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="458" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Barang Masuk</text>
  <rect x="14" y="474" width="252" height="38" rx="8" fill="rgba(249,115,22,0.12)" stroke="rgba(249,115,22,0.4)"/>
  <rect x="14" y="474" width="3" height="38" rx="1.5" fill="#f97316"/>
  <text x="48" y="500" fill="#fff" font-family="Segoe UI,sans-serif" font-size="14" font-weight="600">Pengeluaran Barang</text>
  <rect x="14" y="516" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="542" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Stok</text>
  <text x="24" y="580" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">REPORTS</text>
  <rect x="14" y="592" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="618" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Laporan</text>
  <text x="24" y="656" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">ADMINISTRATION</text>
  <rect x="14" y="668" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="694" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Users / Admin</text>
  <rect x="296" y="0" width="984" height="60" fill="#141a26"/>
  <line x1="296" y1="60" x2="1280" y2="60" stroke="#2a3348" stroke-width="1"/>
  <rect x="1120" y="14" width="34" height="34" rx="17" fill="#2a3348"/>
  <circle cx="1137" cy="25" r="7" fill="#5a6a7e"/><circle cx="1137" cy="40" r="10" fill="#5a6a7e"/>
  <text x="1164" y="32" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13" font-weight="600">Super Admin</text><text x="1164" y="46" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="10">superadmin</text>
  <text x="310" y="98" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="22" font-weight="bold">Pengeluaran Barang</text>
  <text x="310" y="118" fill="#7d8aa0" font-family="Segoe UI,sans-serif" font-size="13">Riwayat pengeluaran stok barang</text>
  <rect x="310" y="140" width="220" height="90" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#os)"/>
  <text x="330" y="170" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Hari Ini</text>
  <text x="330" y="200" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="24" font-weight="bold">18</text>
  <rect x="548" y="140" width="220" height="90" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#os)"/>
  <text x="568" y="170" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Minggu Ini</text>
  <text x="568" y="200" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="24" font-weight="bold">98</text>
  <rect x="786" y="140" width="220" height="90" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#os)"/>
  <text x="806" y="170" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Bulan Ini</text>
  <text x="806" y="200" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="24" font-weight="bold">412</text>
  <rect x="1024" y="140" width="220" height="90" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#os)"/>
  <text x="1044" y="170" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Total Nilai</text>
  <text x="1044" y="200" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="24" font-weight="bold">Rp142jt</text>
  <rect x="310" y="250" width="930" height="300" rx="10" fill="#1c2434" stroke="#2a3348" stroke-width="1"/>
  <rect x="310" y="250" width="930" height="40" rx="10" fill="#141a26"/>
  <text x="330" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Tanggal</text>
  <text x="460" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Produk</text>
  <text x="660" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Tujuan</text>
  <text x="830" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Jumlah</text>
  <text x="980" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">PIC</text>
  <text x="1120" y="276" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Status</text>
  <line x1="310" y1="290" x2="1240" y2="290" stroke="#2a3348" stroke-width="1"/>
  <text x="330" y="316" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="13">14 Jun 2026</text>
  <text x="460" y="316" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">Samsung Galaxy S24</text>
  <text x="660" y="316" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">PT Maju Jaya</text>
  <text x="840" y="316" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">5</text>
  <text x="980" y="316" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Staff Sales</text>
  <rect x="1110" y="304" width="60" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="1140" y="319" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">Terkirim</text>
  <line x1="310" y1="330" x2="1240" y2="330" stroke="#1c2434" stroke-width="1"/>
  <text x="330" y="356" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="13">13 Jun 2026</text>
  <text x="460" y="356" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">MacBook Air M3</text>
  <text x="660" y="356" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">CV Sukses</text>
  <text x="840" y="356" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">3</text>
  <text x="980" y="356" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Staff Sales</text>
  <rect x="1110" y="344" width="60" height="22" rx="11" fill="rgba(249,115,22,0.15)"/>
  <text x="1140" y="359" text-anchor="middle" fill="#f97316" font-family="Segoe UI,sans-serif" font-size="11">Proses</text>
  <line x1="310" y1="370" x2="1240" y2="370" stroke="#1c2434" stroke-width="1"/>
  <text x="330" y="396" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="13">12 Jun 2026</text>
  <text x="460" y="396" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">LG 43" 4K TV</text>
  <text x="660" y="396" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Toko Elektronik</text>
  <text x="840" y="396" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">10</text>
  <text x="980" y="396" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Staff Sales</text>
  <rect x="1110" y="384" width="60" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="1140" y="399" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">Terkirim</text>
</svg>`);

write('users.svg', `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 720" width="1280" height="720">
  <defs><linearGradient id="ubg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#0f1219"/><stop offset="1" stop-color="#1a1f2e"/></linearGradient><filter id="us"><feDropShadow dx="0" dy="2" stdDeviation="8" flood-color="#000" flood-opacity="0.4"/></filter></defs>
  <rect width="1280" height="720" fill="url(#ubg)"/><rect x="0" y="0" width="280" height="720" fill="#11151e"/>
  <rect x="20" y="20" width="44" height="44" rx="10" fill="rgba(249,115,22,0.15)" stroke="#f97316" stroke-width="1.5"/>
  <polygon points="32,28 48,38 32,48 16,38" fill="none" stroke="#f97316" stroke-width="2"/>
  <line x1="32" y1="28" x2="32" y2="48" stroke="#f97316" stroke-width="2"/>
  <line x1="16" y1="38" x2="48" y2="38" stroke="#f97316" stroke-width="1.5"/>
  <text x="76" y="36" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="15" font-weight="bold">RakElektronik</text><text x="76" y="52" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="11">Sistem Gudang Elektronik</text>
  <text x="24" y="100" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">OVERVIEW</text>
  <rect x="14" y="112" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="138" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="14">Dashboard</text>
  <text x="24" y="180" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">MASTER DATA</text>
  <rect x="14" y="192" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="218" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Data Barang</text>
  <rect x="14" y="234" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="260" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Kategori</text>
  <rect x="14" y="276" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="302" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Data Customer</text>
  <text x="24" y="340" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">SALES & ORDER</text>
  <rect x="14" y="352" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="378" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Pesanan</text>
  <text x="24" y="420" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">WAREHOUSE OPERATIONS</text>
  <rect x="14" y="432" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="458" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Barang Masuk</text>
  <rect x="14" y="474" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="500" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Pengeluaran Barang</text>
  <rect x="14" y="516" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="542" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Stok</text>
  <text x="24" y="580" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">REPORTS</text>
  <rect x="14" y="592" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="618" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Laporan</text>
  <text x="24" y="656" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">ADMINISTRATION</text>
  <rect x="14" y="668" width="252" height="38" rx="8" fill="rgba(249,115,22,0.12)" stroke="rgba(249,115,22,0.4)"/>
  <rect x="14" y="668" width="3" height="38" rx="1.5" fill="#f97316"/>
  <text x="48" y="694" fill="#fff" font-family="Segoe UI,sans-serif" font-size="14" font-weight="600">Users / Admin</text>
  <rect x="296" y="0" width="984" height="60" fill="#141a26"/>
  <line x1="296" y1="60" x2="1280" y2="60" stroke="#2a3348" stroke-width="1"/>
  <rect x="1120" y="14" width="34" height="34" rx="17" fill="#2a3348"/>
  <circle cx="1137" cy="25" r="7" fill="#5a6a7e"/><circle cx="1137" cy="40" r="10" fill="#5a6a7e"/>
  <text x="1164" y="32" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13" font-weight="600">Super Admin</text><text x="1164" y="46" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="10">superadmin</text>
  <text x="310" y="98" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="22" font-weight="bold">Manajemen Pengguna</text>
  <text x="310" y="118" fill="#7d8aa0" font-family="Segoe UI,sans-serif" font-size="13">Kelola user dan hak akses sistem</text>
  <rect x="1050" y="76" width="140" height="36" rx="8" fill="#f97316"/>
  <text x="1120" y="100" text-anchor="middle" fill="#fff" font-family="Segoe UI,sans-serif" font-size="13" font-weight="bold">+ User Baru</text>
  <rect x="310" y="140" width="930" height="400" rx="10" fill="#1c2434" stroke="#2a3348" stroke-width="1"/>
  <rect x="310" y="140" width="930" height="40" rx="10" fill="#141a26"/>
  <text x="330" y="166" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">User</text>
  <text x="500" y="166" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Username</text>
  <text x="660" y="166" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Role</text>
  <text x="830" y="166" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Email</text>
  <text x="1050" y="166" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Status</text>
  <line x1="310" y1="180" x2="1240" y2="180" stroke="#2a3348" stroke-width="1"/>
  <circle cx="350" cy="212" r="16" fill="rgba(249,115,22,0.2)" stroke="#f97316" stroke-width="1.5"/>
  <text x="350" y="218" text-anchor="middle" fill="#f97316" font-family="Segoe UI,sans-serif" font-size="13" font-weight="bold">SA</text>
  <text x="500" y="218" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">superadmin</text>
  <rect x="650" y="206" width="90" height="22" rx="11" fill="rgba(249,115,22,0.15)"/>
  <text x="695" y="221" text-anchor="middle" fill="#f97316" font-family="Segoe UI,sans-serif" font-size="11">Super Admin</text>
  <text x="830" y="218" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">admin@warehouse.com</text>
  <rect x="1040" y="206" width="60" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="1070" y="221" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">Aktif</text>
  <line x1="310" y1="236" x2="1240" y2="236" stroke="#1c2434" stroke-width="1"/>
  <circle cx="350" cy="268" r="16" fill="rgba(59,130,246,0.2)" stroke="#3b82f6" stroke-width="1.5"/>
  <text x="350" y="274" text-anchor="middle" fill="#3b82f6" font-family="Segoe UI,sans-serif" font-size="13" font-weight="bold">AD</text>
  <text x="500" y="274" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">admin</text>
  <rect x="650" y="262" width="70" height="22" rx="11" fill="rgba(59,130,246,0.15)"/>
  <text x="685" y="277" text-anchor="middle" fill="#3b82f6" font-family="Segoe UI,sans-serif" font-size="11">Admin</text>
  <text x="830" y="274" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">admin@warehouse.com</text>
  <rect x="1040" y="262" width="60" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="1070" y="277" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">Aktif</text>
  <line x1="310" y1="292" x2="1240" y2="292" stroke="#1c2434" stroke-width="1"/>
  <circle cx="350" cy="324" r="16" fill="rgba(34,197,94,0.2)" stroke="#22c55e" stroke-width="1.5"/>
  <text x="350" y="330" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="13" font-weight="bold">WG</text>
  <text x="500" y="330" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">staffgudang</text>
  <rect x="650" y="318" width="100" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="700" y="333" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">Staff Gudang</text>
  <text x="830" y="330" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">gudang@warehouse.com</text>
  <rect x="1040" y="318" width="60" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="1070" y="333" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">Aktif</text>
  <line x1="310" y1="348" x2="1240" y2="348" stroke="#1c2434" stroke-width="1"/>
  <circle cx="350" cy="380" r="16" fill="rgba(168,85,247,0.2)" stroke="#a855f7" stroke-width="1.5"/>
  <text x="350" y="386" text-anchor="middle" fill="#a855f7" font-family="Segoe UI,sans-serif" font-size="13" font-weight="bold">SP</text>
  <text x="500" y="386" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">staffsales</text>
  <rect x="650" y="374" width="100" height="22" rx="11" fill="rgba(168,85,247,0.15)"/>
  <text x="700" y="389" text-anchor="middle" fill="#a855f7" font-family="Segoe UI,sans-serif" font-size="11">Staff Sales</text>
  <text x="830" y="386" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">sales@warehouse.com</text>
  <rect x="1040" y="374" width="60" height="22" rx="11" fill="rgba(239,68,68,0.15)"/>
  <text x="1070" y="389" text-anchor="middle" fill="#ef4444" font-family="Segoe UI,sans-serif" font-size="11">Nonaktif</text>
  <line x1="310" y1="404" x2="1240" y2="404" stroke="#1c2434" stroke-width="1"/>
  <circle cx="350" cy="436" r="16" fill="rgba(34,197,94,0.2)" stroke="#22c55e" stroke-width="1.5"/>
  <text x="350" y="442" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="13" font-weight="bold">KR</text>
  <text x="500" y="442" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">kurir</text>
  <rect x="650" y="430" width="70" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="685" y="445" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">Kurir</text>
  <text x="830" y="442" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">kurir@warehouse.com</text>
  <rect x="1040" y="430" width="60" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="1070" y="445" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">Aktif</text>
</svg>`);

write('reports.svg', `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 720" width="1280" height="720">
  <defs><linearGradient id="rbg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#0f1219"/><stop offset="1" stop-color="#1a1f2e"/></linearGradient><filter id="rs"><feDropShadow dx="0" dy="2" stdDeviation="8" flood-color="#000" flood-opacity="0.4"/></filter></defs>
  <rect width="1280" height="720" fill="url(#rbg)"/><rect x="0" y="0" width="280" height="720" fill="#11151e"/>
  <rect x="20" y="20" width="44" height="44" rx="10" fill="rgba(249,115,22,0.15)" stroke="#f97316" stroke-width="1.5"/>
  <polygon points="32,28 48,38 32,48 16,38" fill="none" stroke="#f97316" stroke-width="2"/>
  <line x1="32" y1="28" x2="32" y2="48" stroke="#f97316" stroke-width="2"/>
  <line x1="16" y1="38" x2="48" y2="38" stroke="#f97316" stroke-width="1.5"/>
  <text x="76" y="36" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="15" font-weight="bold">RakElektronik</text><text x="76" y="52" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="11">Sistem Gudang Elektronik</text>
  <text x="24" y="100" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">OVERVIEW</text>
  <rect x="14" y="112" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="138" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="14">Dashboard</text>
  <text x="24" y="180" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">MASTER DATA</text>
  <rect x="14" y="192" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="218" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Data Barang</text>
  <rect x="14" y="234" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="260" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Kategori</text>
  <rect x="14" y="276" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="302" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Data Customer</text>
  <text x="24" y="340" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">SALES & ORDER</text>
  <rect x="14" y="352" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="378" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Pesanan</text>
  <text x="24" y="420" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">WAREHOUSE OPERATIONS</text>
  <rect x="14" y="432" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="458" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Barang Masuk</text>
  <rect x="14" y="474" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="500" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Pengeluaran Barang</text>
  <rect x="14" y="516" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="542" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Stok</text>
  <text x="24" y="580" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">REPORTS</text>
  <rect x="14" y="592" width="252" height="38" rx="8" fill="rgba(249,115,22,0.12)" stroke="rgba(249,115,22,0.4)"/>
  <rect x="14" y="592" width="3" height="38" rx="1.5" fill="#f97316"/>
  <text x="48" y="618" fill="#fff" font-family="Segoe UI,sans-serif" font-size="14" font-weight="600">Laporan</text>
  <text x="24" y="656" fill="#5a6a7e" font-family="Segoe UI,sans-serif" font-size="10" letter-spacing="2">ADMINISTRATION</text>
  <rect x="14" y="668" width="252" height="38" rx="8" fill="transparent"/><text x="48" y="694" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Users / Admin</text>
  <rect x="296" y="0" width="984" height="60" fill="#141a26"/>
  <line x1="296" y1="60" x2="1280" y2="60" stroke="#2a3348" stroke-width="1"/>
  <rect x="1120" y="14" width="34" height="34" rx="17" fill="#2a3348"/>
  <circle cx="1137" cy="25" r="7" fill="#5a6a7e"/><circle cx="1137" cy="40" r="10" fill="#5a6a7e"/>
  <text x="1164" y="32" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13" font-weight="600">Super Admin</text><text x="1164" y="46" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="10">superadmin</text>
  <text x="310" y="98" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="22" font-weight="bold">Laporan</text>
  <text x="310" y="118" fill="#7d8aa0" font-family="Segoe UI,sans-serif" font-size="13">Laporan dan analisis stok barang</text>
  <rect x="1050" y="76" width="90" height="36" rx="8" fill="#22c55e"/>
  <text x="1095" y="100" text-anchor="middle" fill="#fff" font-family="Segoe UI,sans-serif" font-size="13" font-weight="bold">PDF</text>
  <rect x="1150" y="76" width="90" height="36" rx="8" fill="#3b82f6"/>
  <text x="1195" y="100" text-anchor="middle" fill="#fff" font-family="Segoe UI,sans-serif" font-size="13" font-weight="bold">Excel</text>
  <rect x="310" y="140" width="930" height="50" rx="10" fill="#1c2434" stroke="#2a3348" stroke-width="1"/>
  <rect x="330" y="152" width="140" height="28" rx="6" fill="#141a26" stroke="#2a3348" stroke-width="1"/>
  <text x="350" y="171" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="12">Jenis Laporan</text>
  <rect x="490" y="152" width="140" height="28" rx="6" fill="#141a26" stroke="#2a3348" stroke-width="1"/>
  <text x="510" y="171" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="12">Periode</text>
  <rect x="650" y="152" width="140" height="28" rx="6" fill="#141a26" stroke="#2a3348" stroke-width="1"/>
  <text x="670" y="171" fill="#c7d0df" font-family="Segoe UI,sans-serif" font-size="12">Kategori</text>
  <rect x="810" y="152" width="100" height="28" rx="6" fill="#f97316"/>
  <text x="860" y="171" text-anchor="middle" fill="#fff" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Terapkan</text>
  <rect x="310" y="210" width="220" height="90" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#rs)"/>
  <text x="330" y="240" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Total Stok</text>
  <text x="330" y="270" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="24" font-weight="bold">2,847</text>
  <rect x="548" y="210" width="220" height="90" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#rs)"/>
  <text x="568" y="240" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Total Barang</text>
  <text x="568" y="270" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="24" font-weight="bold">1,248</text>
  <rect x="786" y="210" width="220" height="90" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#rs)"/>
  <text x="806" y="240" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Nilai Stok</text>
  <text x="806" y="270" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="24" font-weight="bold">Rp2.8M</text>
  <rect x="1024" y="210" width="220" height="90" rx="12" fill="#1c2434" stroke="#2a3348" stroke-width="1" filter="url(#rs)"/>
  <text x="1044" y="240" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12">Stok Minimal</text>
  <text x="1044" y="270" fill="#ef4444" font-family="Segoe UI,sans-serif" font-size="24" font-weight="bold">8</text>
  <rect x="310" y="320" width="930" height="230" rx="10" fill="#1c2434" stroke="#2a3348" stroke-width="1"/>
  <rect x="310" y="320" width="930" height="40" rx="10" fill="#141a26"/>
  <text x="330" y="346" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Produk</text>
  <text x="520" y="346" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Kategori</text>
  <text x="680" y="346" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Stok</text>
  <text x="810" y="346" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Nilai</text>
  <text x="980" y="346" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Status</text>
  <text x="1120" y="346" fill="#9ca8b8" font-family="Segoe UI,sans-serif" font-size="12" font-weight="bold">Aksi</text>
  <line x1="310" y1="360" x2="1240" y2="360" stroke="#2a3348" stroke-width="1"/>
  <text x="330" y="386" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">Samsung Galaxy S24</text>
  <text x="520" y="386" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Elektronik</text>
  <text x="690" y="386" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">50</text>
  <text x="810" y="386" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">Rp12jt</text>
  <rect x="970" y="374" width="60" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="1000" y="389" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">Aman</text>
  <rect x="1110" y="374" width="60" height="22" rx="6" fill="rgba(249,115,22,0.15)"/>
  <text x="1140" y="389" text-anchor="middle" fill="#f97316" font-family="Segoe UI,sans-serif" font-size="11">Cetak</text>
  <line x1="310" y1="400" x2="1240" y2="400" stroke="#1c2434" stroke-width="1"/>
  <text x="330" y="426" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">LG 43" 4K TV</text>
  <text x="520" y="426" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Elektronik</text>
  <text x="690" y="426" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">30</text>
  <text x="810" y="426" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">Rp5.5jt</text>
  <rect x="970" y="414" width="60" height="22" rx="11" fill="rgba(34,197,94,0.15)"/>
  <text x="1000" y="429" text-anchor="middle" fill="#22c55e" font-family="Segoe UI,sans-serif" font-size="11">Aman</text>
  <rect x="1110" y="414" width="60" height="22" rx="6" fill="rgba(249,115,22,0.15)"/>
  <text x="1140" y="429" text-anchor="middle" fill="#f97316" font-family="Segoe UI,sans-serif" font-size="11">Cetak</text>
  <line x1="310" y1="440" x2="1240" y2="440" stroke="#1c2434" stroke-width="1"/>
  <text x="330" y="466" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">Sony WH-1000XM5</text>
  <text x="520" y="466" fill="#b0c0d4" font-family="Segoe UI,sans-serif" font-size="13">Aksesoris</text>
  <text x="690" y="466" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">8</text>
  <text x="810" y="466" fill="#e8edf5" font-family="Segoe UI,sans-serif" font-size="13">Rp3.2jt</text>
  <rect x="970" y="454" width="60" height="22" rx="11" fill="rgba(249,115,22,0.15)"/>
  <text x="1000" y="469" text-anchor="middle" fill="#f97316" font-family="Segoe UI,sans-serif" font-size="11">Kritis</text>
  <rect x="1110" y="454" width="60" height="22" rx="6" fill="rgba(249,115,22,0.15)"/>
  <text x="1140" y="469" text-anchor="middle" fill="#f97316" font-family="Segoe UI,sans-serif" font-size="11">Cetak</text>
</svg>`);

console.log('All 8 screenshots generated successfully!');