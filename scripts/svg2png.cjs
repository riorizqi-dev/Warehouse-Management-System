const fs = require('fs');
const path = require('path');
const zlib = require('zlib');

function createPNG(width, height, pixels) {
  function crc32(buf) {
    let c = 0xffffffff;
    const tbl = new Int32Array(256);
    for (let n = 0; n < 256; n++) {
      let x = n;
      for (let k = 0; k < 8; k++) x = x & 1 ? 0xedb88320 ^ (x >>> 1) : x >>> 1;
      tbl[n] = x;
    }
    for (let i = 0; i < buf.length; i++) c = tbl[(c ^ buf[i]) & 0xff] ^ (c >>> 8);
    return (c ^ 0xffffffff) >>> 0;
  }

  function chunk(type, data) {
    const len = Buffer.alloc(4);
    len.writeUInt32BE(data.length);
    const typeData = Buffer.concat([Buffer.from(type), data]);
    const crc = Buffer.alloc(4);
    crc.writeUInt32BE(crc32(typeData));
    return Buffer.concat([len, typeData, crc]);
  }

  const ihdr = Buffer.alloc(13);
  ihdr.writeUInt32BE(width, 0);
  ihdr.writeUInt32BE(height, 4);
  ihdr[8] = 8; ihdr[9] = 6; ihdr[10] = 0; ihdr[11] = 0; ihdr[12] = 0;

  const raw = [];
  for (let y = 0; y < height; y++) {
    raw.push(0);
    for (let x = 0; x < width; x++) {
      const idx = (y * width + x) * 4;
      raw.push(pixels[idx], pixels[idx+1], pixels[idx+2], pixels[idx+3]);
    }
  }
  const rawData = Buffer.from(raw);
  const compressed = zlib.deflateSync(rawData);

  const sig = Buffer.from([137, 80, 78, 71, 13, 10, 26, 10]);
  return Buffer.concat([sig, chunk('IHDR', ihdr), chunk('IDAT', compressed), chunk('IEND', Buffer.alloc(0))]);
}

// Color palette (dark theme)
const BG = [30, 33, 40, 255];
const SIDEBAR = [22, 25, 32, 255];
const CARD = [38, 42, 52, 255];
const TEXT = [255, 255, 255, 255];
const MUTED = [140, 150, 160, 255];
const ACCENT = [99, 102, 241, 255];
const GREEN = [34, 197, 94, 255];
const ORANGE = [249, 115, 22, 255];
const RED = [239, 68, 68, 255];
const BLUE = [59, 130, 246, 255];
const YELLOW = [234, 179, 8, 255];
const HEADER_BG = [17, 18, 23, 255];
const BORDER = [55, 60, 72, 255];

function drawRect(pixels, w, x1, y1, w2, h, color) {
  for (let y = Math.max(0, y1); y < Math.min(y1 + h, 700); y++) {
    for (let x = Math.max(0, x1); x < Math.min(x1 + w2, w); x++) {
      const idx = (y * w + x) * 4;
      pixels[idx] = color[0]; pixels[idx+1] = color[1];
      pixels[idx+2] = color[2]; pixels[idx+3] = color[3];
    }
  }
}

function drawHLine(pixels, w, x1, x2, y, color) {
  drawRect(pixels, w, x1, y, x2 - x1, 1, color);
}

function drawText(pixels, w, x, y, text, color, size = 2) {
  // Simple block text
  for (let i = 0; i < text.length && x + i * size * 5 < w; i++) {
    drawRect(pixels, w, x + i * size * 5, y, size * 4, size * 6, color);
  }
}

function drawCircle(pixels, w, cx, cy, r, color) {
  for (let y = cy - r; y <= cy + r; y++) {
    for (let x = cx - r; x <= cx + r; x++) {
      if (x >= 0 && x < w && y >= 0 && y < 700) {
        if ((x-cx)*(x-cx) + (y-cy)*(y-cy) <= r*r) {
          const idx = (y * w + x) * 4;
          pixels[idx] = color[0]; pixels[idx+1] = color[1];
          pixels[idx+2] = color[2]; pixels[idx+3] = color[3];
        }
      }
    }
  }
}

function drawChart(pixels, w, x, y, width, height, color) {
  const barWidth = Math.floor(width / 7);
  for (let i = 0; i < 7; i++) {
    const h = Math.floor(height * (0.3 + Math.random() * 0.7));
    drawRect(pixels, w, x + i * barWidth + 4, y + height - h, barWidth - 8, h, color);
  }
}

function makeDashboard() {
  const w = 1200, h = 700;
  const px = new Uint8Array(w * h * 4);
  drawRect(px, w, 0, 0, w, h, BG);
  // Sidebar
  drawRect(px, w, 0, 0, 250, h, SIDEBAR);
  drawRect(px, w, 250, 0, 1, h, BORDER);
  // Sidebar menu items
  for (let i = 0; i < 8; i++) {
    if (i === 1) drawRect(px, w, 0, 60 + i * 50, 250, 45, [35, 38, 48, 255]);
    drawText(px, w, 45, 72 + i * 50, 'Menu ' + i, i === 1 ? ACCENT : MUTED);
  }
  // Header
  drawRect(px, w, 250, 0, w - 250, 55, HEADER_BG);
  drawHLine(px, w, 250, w, 55, BORDER);
  drawText(px, w, 270, 18, 'Dashboard', TEXT, 3);
  // Stat cards
  const cards = [
    { label: 'Total Items', value: '1,234', color: BLUE },
    { label: 'Low Stock', value: '12', color: ORANGE },
    { label: 'Orders', value: '89', color: GREEN },
    { label: 'Customers', value: '456', color: ACCENT },
  ];
  cards.forEach((c, i) => {
    const cx = 280 + i * 220;
    drawRect(px, w, cx, 75, 200, 90, CARD);
    drawRect(px, w, cx, 75, 200, 3, c.color);
    drawText(px, w, cx + 15, 95, c.label, MUTED);
    drawText(px, w, cx + 15, 120, c.value, TEXT, 4);
  });
  // Chart area
  drawRect(px, w, 280, 190, 880, 240, CARD);
  drawText(px, w, 300, 205, 'Monthly Overview', TEXT);
  drawChart(px, w, 300, 230, 840, 180, ACCENT);
  // Table area
  drawRect(px, w, 280, 450, 880, 230, CARD);
  drawText(px, w, 300, 465, 'Recent Orders', TEXT);
  for (let i = 0; i < 5; i++) {
    drawHLine(px, w, 280, 1160, 495 + i * 35, BORDER);
    drawText(px, w, 300, 502 + i * 35, 'Order #' + (1000 + i), MUTED);
    drawCircle(px, w, 750, 512 + i * 35, 6, i < 3 ? GREEN : ORANGE);
    drawText(px, w, 800, 502 + i * 35, i < 3 ? 'Completed' : 'Pending', MUTED);
  }
  return createPNG(w, h, px);
}

function makeLogin() {
  const w = 1200, h = 700;
  const px = new Uint8Array(w * h * 4);
  drawRect(px, w, 0, 0, w, h, BG);
  // Center card
  drawRect(px, w, 400, 120, 400, 460, CARD);
  drawRect(px, w, 400, 120, 400, 3, ACCENT);
  drawText(px, w, 530, 160, 'Sign In', TEXT, 4);
  drawText(px, w, 470, 210, 'Welcome back', MUTED);
  // Username field
  drawRect(px, w, 430, 260, 340, 45, [45, 49, 60, 255]);
  drawText(px, w, 445, 272, 'Username', MUTED);
  // Password field
  drawRect(px, w, 430, 320, 340, 45, [45, 49, 60, 255]);
  drawText(px, w, 445, 332, 'Password', MUTED);
  // Button
  drawRect(px, w, 430, 390, 340, 50, ACCENT);
  drawText(px, w, 555, 402, 'Login', TEXT, 3);
  // Footer
  drawText(px, w, 470, 470, 'Warehouse Management System', MUTED);
  return createPNG(w, h, px);
}

function makeProducts() {
  const w = 1200, h = 700;
  const px = new Uint8Array(w * h * 4);
  drawRect(px, w, 0, 0, w, h, BG);
  drawRect(px, w, 0, 0, 250, h, SIDEBAR);
  drawHLine(px, w, 250, w, 55, BORDER);
  drawRect(px, w, 250, 0, w - 250, 55, HEADER_BG);
  for (let i = 0; i < 8; i++) drawText(px, w, 45, 72 + i * 50, 'Menu ' + i, i === 2 ? ACCENT : MUTED);
  drawText(px, w, 270, 18, 'Data Barang', TEXT, 3);
  // Table
  drawRect(px, w, 280, 75, 880, 40, ACCENT);
  ['No', 'Nama Barang', 'Kategori', 'Stok', 'Harga', 'Aksi'].forEach((h2, i) => {
    drawText(px, w, 295 + i * 145, 85, h2, TEXT);
  });
  const items = ['Laptop ASUS', 'Mouse Logitech', 'Keyboard Mech', 'Monitor LG', 'Headset Sony', 'USB Hub'];
  items.forEach((item, i) => {
    const y = 130 + i * 55;
    drawRect(px, w, 280, y, 880, 50, i % 2 === 0 ? CARD : BG);
    drawText(px, w, 295, y + 18, String(i + 1), MUTED);
    drawText(px, w, 440, y + 18, item, TEXT);
    drawText(px, w, 585, y + 18, 'Electronics', MUTED);
    drawText(px, w, 730, y + 18, String(Math.floor(Math.random() * 100)), GREEN);
    drawText(px, w, 875, y + 18, 'Rp ' + (Math.floor(Math.random() * 9) + 1) + '.000.000', MUTED);
    drawRect(px, w, 1020, y + 10, 30, 25, GREEN);
    drawRect(px, w, 1060, y + 10, 30, 25, RED);
  });
  return createPNG(w, h, px);
}

function makeInventory() {
  const w = 1200, h = 700;
  const px = new Uint8Array(w * h * 4);
  drawRect(px, w, 0, 0, w, h, BG);
  drawRect(px, w, 0, 0, 250, h, SIDEBAR);
  drawRect(px, w, 250, 0, w - 250, 55, HEADER_BG);
  for (let i = 0; i < 8; i++) drawText(px, w, 45, 72 + i * 50, 'Menu ' + i, i === 3 ? ACCENT : MUTED);
  drawText(px, w, 270, 18, 'Inventory', TEXT, 3);
  // Stat cards
  const cards2 = [
    { l: 'Total Items', v: '1,234', c: BLUE },
    { l: 'Total Stock', v: '45,678', c: GREEN },
    { l: 'Reserved', v: '234', c: ORANGE },
    { l: 'Low Stock', v: '12', c: RED },
  ];
  cards2.forEach((c, i) => {
    const cx = 280 + i * 220;
    drawRect(px, w, cx, 75, 200, 80, CARD);
    drawText(px, w, cx + 15, 90, c.l, MUTED);
    drawText(px, w, cx + 15, 115, c.v, c.c, 3);
  });
  // Stock level bars
  drawRect(px, w, 280, 180, 880, 300, CARD);
  drawText(px, w, 300, 195, 'Stock Levels by Category', TEXT);
  ['Electronics', 'Accessories', 'Peripherals', 'Networking', 'Storage'].forEach((cat, i) => {
    const y = 225 + i * 45;
    drawText(px, w, 300, y + 5, cat, MUTED);
    const barW = 200 + Math.floor(Math.random() * 400);
    drawRect(px, w, 480, y, barW, 25, i === 4 ? RED : i === 3 ? ORANGE : GREEN);
  });
  // Activity log
  drawRect(px, w, 280, 500, 880, 180, CARD);
  drawText(px, w, 300, 515, 'Recent Activity', TEXT);
  ['Stock in: Laptop x10', 'Stock out: Mouse x5', 'Reserved: Monitor x2'].forEach((a, i) => {
    drawText(px, w, 300, 545 + i * 35, a, MUTED);
  });
  return createPNG(w, h, px);
}

function makeStockIn() {
  const w = 1200, h = 700;
  const px = new Uint8Array(w * h * 4);
  drawRect(px, w, 0, 0, w, h, BG);
  drawRect(px, w, 0, 0, 250, h, SIDEBAR);
  drawRect(px, w, 250, 0, w - 250, 55, HEADER_BG);
  for (let i = 0; i < 8; i++) drawText(px, w, 45, 72 + i * 50, 'Menu ' + i, i === 4 ? ACCENT : MUTED);
  drawText(px, w, 270, 18, 'Stock In', TEXT, 3);
  // Form
  drawRect(px, w, 280, 75, 880, 600, CARD);
  drawText(px, w, 300, 95, 'Tambah Stock Masuk', TEXT, 3);
  // Form fields
  const fields = ['Tanggal', 'Supplier', 'Barang', 'Jumlah', 'Keterangan'];
  fields.forEach((f, i) => {
    const y = 140 + i * 65;
    drawText(px, w, 300, y, f + ' *', MUTED);
    drawRect(px, w, 300, y + 15, 400, 40, [45, 49, 60, 255]);
  });
  // Items table
  drawRect(px, w, 750, 140, 380, 200, [45, 49, 60, 255]);
  drawText(px, w, 760, 155, 'Items', TEXT);
  for (let i = 0; i < 3; i++) {
    drawText(px, w, 760, 185 + i * 35, 'Item ' + (i+1) + ' x' + (i+2), MUTED);
  }
  // Button
  drawRect(px, w, 300, 580, 200, 45, GREEN);
  drawText(px, w, 340, 592, 'Simpan', TEXT, 3);
  return createPNG(w, h, px);
}

function makeStockOut() {
  const w = 1200, h = 700;
  const px = new Uint8Array(w * h * 4);
  drawRect(px, w, 0, 0, w, h, BG);
  drawRect(px, w, 0, 0, 250, h, SIDEBAR);
  drawRect(px, w, 250, 0, w - 250, 55, HEADER_BG);
  for (let i = 0; i < 8; i++) drawText(px, w, 45, 72 + i * 50, 'Menu ' + i, i === 5 ? ACCENT : MUTED);
  drawText(px, w, 270, 18, 'Stock Out', TEXT, 3);
  // Form
  drawRect(px, w, 280, 75, 880, 600, CARD);
  drawText(px, w, 300, 95, 'Tambah Stock Keluar', TEXT, 3);
  const fields2 = ['Tanggal', 'Tujuan', 'Barang', 'Jumlah', 'Keterangan'];
  fields2.forEach((f, i) => {
    const y = 140 + i * 65;
    drawText(px, w, 300, y, f + ' *', MUTED);
    drawRect(px, w, 300, y + 15, 400, 40, [45, 49, 60, 255]);
  });
  drawRect(px, w, 750, 140, 380, 200, [45, 49, 60, 255]);
  drawText(px, w, 760, 155, 'Items', TEXT);
  for (let i = 0; i < 3; i++) {
    drawText(px, w, 760, 185 + i * 35, 'Item ' + (i+1) + ' x' + (i+2), MUTED);
  }
  drawRect(px, w, 300, 580, 200, 45, ORANGE);
  drawText(px, w, 340, 592, 'Simpan', TEXT, 3);
  return createPNG(w, h, px);
}

function makeUsers() {
  const w = 1200, h = 700;
  const px = new Uint8Array(w * h * 4);
  drawRect(px, w, 0, 0, w, h, BG);
  drawRect(px, w, 0, 0, 250, h, SIDEBAR);
  drawRect(px, w, 250, 0, w - 250, 55, HEADER_BG);
  for (let i = 0; i < 8; i++) drawText(px, w, 45, 72 + i * 50, 'Menu ' + i, i === 6 ? ACCENT : MUTED);
  drawText(px, w, 270, 18, 'User Management', TEXT, 3);
  // Table
  drawRect(px, w, 280, 75, 880, 40, ACCENT);
  ['No', 'Nama', 'Email', 'Role', 'Status', 'Aksi'].forEach((h2, i) => {
    drawText(px, w, 295 + i * 145, 85, h2, TEXT);
  });
  const users = [
    ['Super Admin', 'admin@wh.com', 'superadmin'],
    ['Budi Santoso', 'budi@wh.com', 'staff_gudang'],
    ['Sari Dewi', 'sari@wh.com', 'staff_penjualan'],
    ['Andi Kurir', 'andi@wh.com', 'kurir'],
    ['Toko Maju', 'maju@mail.com', 'customer'],
  ];
  users.forEach((u, i) => {
    const y = 130 + i * 55;
    drawRect(px, w, 280, y, 880, 50, i % 2 === 0 ? CARD : BG);
    drawText(px, w, 295, y + 18, String(i + 1), MUTED);
    drawText(px, w, 440, y + 18, u[0], TEXT);
    drawText(px, w, 585, y + 18, u[1], MUTED);
    drawText(px, w, 730, y + 18, u[2], ACCENT);
    drawCircle(px, w, 870, y + 25, 6, GREEN);
    drawRect(px, w, 950, y + 10, 30, 25, GREEN);
    drawRect(px, w, 990, y + 10, 30, 25, RED);
  });
  return createPNG(w, h, px);
}

function makeReports() {
  const w = 1200, h = 700;
  const px = new Uint8Array(w * h * 4);
  drawRect(px, w, 0, 0, w, h, BG);
  drawRect(px, w, 0, 0, 250, h, SIDEBAR);
  drawRect(px, w, 250, 0, w - 250, 55, HEADER_BG);
  for (let i = 0; i < 8; i++) drawText(px, w, 45, 72 + i * 50, 'Menu ' + i, i === 7 ? ACCENT : MUTED);
  drawText(px, w, 270, 18, 'Laporan', TEXT, 3);
  // Filter
  drawRect(px, w, 280, 75, 880, 50, CARD);
  drawText(px, w, 300, 90, 'Filter:', MUTED);
  drawRect(px, w, 360, 82, 150, 30, [45, 49, 60, 255]);
  drawRect(px, w, 520, 82, 150, 30, [45, 49, 60, 255]);
  drawRect(px, w, 680, 82, 100, 30, ACCENT);
  // Charts
  drawRect(px, w, 280, 140, 430, 250, CARD);
  drawText(px, w, 300, 155, 'Stock Masuk', TEXT);
  drawChart(px, w, 300, 180, 390, 190, GREEN);
  drawRect(px, w, 720, 140, 440, 250, CARD);
  drawText(px, w, 740, 155, 'Stock Keluar', TEXT);
  drawChart(px, w, 740, 180, 400, 190, RED);
  // Table
  drawRect(px, w, 280, 410, 880, 270, CARD);
  drawText(px, w, 300, 425, 'Ringkasan Laporan', TEXT);
  ['Bulan', 'Masuk', 'Keluar', 'Net'].forEach((h2, i) => {
    drawText(px, w, 300 + i * 200, 455, h2, ACCENT);
  });
  for (let i = 0; i < 5; i++) {
    const y = 480 + i * 35;
    drawHLine(px, w, 280, 1160, y, BORDER);
    drawText(px, w, 300, y + 8, ['Jan', 'Feb', 'Mar', 'Apr', 'Mei'][i], MUTED);
    drawText(px, w, 500, y + 8, String(Math.floor(Math.random() * 500) + 100), GREEN);
    drawText(px, w, 700, y + 8, String(Math.floor(Math.random() * 300) + 50), RED);
    drawText(px, w, 900, y + 8, String(Math.floor(Math.random() * 200) + 50), TEXT);
  }
  return createPNG(w, h, px);
}

const dir = path.join(__dirname, '..', 'screenshots');
const generators = [
  ['dashboard', makeDashboard],
  ['login', makeLogin],
  ['products', makeProducts],
  ['inventory', makeInventory],
  ['stock-in', makeStockIn],
  ['stock-out', makeStockOut],
  ['users', makeUsers],
  ['reports', makeReports],
];

console.log('Generating PNG screenshots...');
generators.forEach(([name, fn]) => {
  const png = fn();
  const out = path.join(dir, name + '.png');
  fs.writeFileSync(out, png);
  console.log(`  ${name}.png (${(png.length / 1024).toFixed(1)} KB)`);
});
console.log('Done!');