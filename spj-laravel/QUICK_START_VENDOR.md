# Quick Start Guide - Manajemen Vendor Kegiatan

## 🚀 Cara Menggunakan

### 1. Akses Halaman Manajemen Vendor

Dari halaman **Edit Kegiatan**, klik tombol hijau:
```
📋 Kelola Vendor & Nomor Surat
```

### 2. Tambah Vendor ke Kegiatan

1. **Pilih Vendor** dari dropdown
2. Masukkan **nomor surat** (opsional):
   - Nomor Berita Acara
   - Nomor BAST (Berita Acara Serah Terima Barang/Pekerjaan)
   - Nomor Berita Pembayaran
3. Klik **Tambah Vendor**

### 3. Edit Nomor Surat

1. Pada tabel vendor, klik icon **pensil (✏️)** di kolom Aksi
2. Input field akan muncul untuk edit
3. Klik icon **centang (✓)** untuk simpan
4. Klik icon **X** untuk batal

### 4. Hapus Vendor

1. Klik icon **tempat sampah (🗑️)** di kolom Aksi
2. Konfirmasi penghapusan
3. Vendor akan dihapus (jika tidak digunakan di konsumsi)

---

## 💡 Contoh Kasus Penggunaan

### Case 1: Satu Vendor, Banyak Kegiatan

**PT Epson** digunakan di 3 kegiatan berbeda:

| Kegiatan | Nomor BA | Nomor BAST | Nomor BP |
|----------|----------|------------|----------|
| Workshop 2026 | BA/001/2026 | BAST/001/2026 | BP/001/2026 |
| Pelatihan IT | BA/015/2026 | BAST/015/2026 | BP/015/2026 |
| Seminar Nasional | BA/032/2026 | BAST/032/2026 | BP/032/2026 |

Setiap kegiatan punya nomor surat yang **berbeda** meskipun vendornya **sama**.

### Case 2: Satu Kegiatan, Banyak Vendor

**Workshop 2026** menggunakan 3 vendor berbeda:

| Vendor | Nomor BA | Nomor BAST | Nomor BP |
|--------|----------|------------|----------|
| PT Epson | BA/001/2026 | BAST/001/2026 | BP/001/2026 |
| PT Canon | BA/002/2026 | BAST/002/2026 | BP/002/2026 |
| PT HP Indonesia | BA/003/2026 | BAST/003/2026 | BP/003/2026 |

Setiap vendor di kegiatan yang sama memiliki nomor surat yang berbeda.

---

## 🔧 Untuk Developer

### Query Vendor dari Kegiatan

```php
$kegiatan = Kegiatan::find(1);

foreach ($kegiatan->vendors as $vendor) {
    echo "Vendor: {$vendor->nama_vendor}\n";
    echo "No. BA: {$vendor->pivot->nomor_berita_acara}\n";
    echo "No. BAST: {$vendor->pivot->nomor_bast}\n";
    echo "No. BP: {$vendor->pivot->nomor_berita_pembayaran}\n";
}
```

### Query Kegiatan dari Vendor

```php
$vendor = Vendor::find(1);

foreach ($vendor->kegiatans as $kegiatan) {
    echo "Kegiatan: {$kegiatan->nama_kegiatan}\n";
    echo "No. BA: {$kegiatan->pivot->nomor_berita_acara}\n";
}
```

### Tambah Vendor Secara Programmatic

```php
$kegiatan = Kegiatan::find(1);

$kegiatan->vendors()->attach(5, [
    'nomor_berita_acara' => 'BA/001/2026',
    'nomor_bast' => 'BAST/001/2026',
    'nomor_berita_pembayaran' => 'BP/001/2026'
]);
```

### Update Nomor Surat

```php
$kegiatan->vendors()->updateExistingPivot(5, [
    'nomor_berita_acara' => 'BA/999/2026'
]);
```

### Cek Vendor Sudah Ada?

```php
if ($kegiatan->vendors()->where('vendor_id', 5)->exists()) {
    echo "Vendor sudah ditambahkan!";
}
```

---

## ⚠️ Catatan Penting

1. **Satu vendor hanya bisa ditambahkan 1x** ke satu kegiatan (UNIQUE constraint)
2. **Vendor tidak bisa dihapus** jika sudah digunakan di data konsumsi
3. **Nomor surat boleh kosong** (nullable), bisa diisi kemudian
4. **Edit inline** tersedia di tabel untuk update cepat

---

## 📁 File yang Terlibat

### Migration
- `database/migrations/2026_02_06_000001_create_kegiatan_vendor_pivot_table.php`

### Models
- `app/Models/Kegiatan.php` → relasi `vendors()`
- `app/Models/Vendor.php` → relasi `kegiatans()`

### Controller
- `app/Http/Controllers/KegiatanVendorController.php`

### Views
- `resources/views/kegiatan/vendor/index.blade.php`
- `resources/views/kegiatan/edit.blade.php` (updated)

### Routes
- `routes/web.php` (added kegiatan vendor routes)
