# Sistem Manajemen Barang (Inventory Management System)

> **Platform Web Pengelolaan Inventaris Gudang, Produk, Lot/Batch, dan Mutasi Stok Real-Time**  
> **Arsitektur:** CodeIgniter 3 (MVC Native, Server-Side Rendering) — Tanpa Dependensi Composer / Vendor  
> **Repository:** [https://github.com/kanjengdmas/manajemen-barangCI3](https://github.com/kanjengdmas/manajemen-barangCI3)

---

## 📌 Tentang Proyek

**Manajemen Barang** adalah aplikasi inventaris pergudangan berbasis web yang dirancang untuk mengotomasi dan mempermudah pengelolaan produk, pelacakan lot/batch kardus (FIFO/FEFO), transaksi arus keluar-masuk barang, pencetakan surat jalan / bukti mutasi, serta pemantauan stok menipis secara real-time.

Sistem dibangun murni menggunakan **CodeIgniter 3.1.x** yang ringan, cepat, dan siap dijalankan langsung di lingkungan **XAMPP (Apache & MySQL)** tanpa memerlukan instalasi Node.js, Vite, maupun Composer.

---

## 🚀 Fitur Unggulan

1. **Dashboard Real-Time & Analitik**:
   - 4 Kartu metrik: Total Produk, Total Stok (dengan fitur sembunyikan nominal), Stok Menipis, dan Stok Habis.
   - Grafik distribusi stok per kategori (Chart.js) dan grafik mingguan/bulanan arus barang masuk vs keluar.
   - Tabel peringatan stok kritis dan log aktivitas 10 mutasi terkini.
   - Fitur *Auto-Refresh* otomatis latar belakang setiap 5 menit tanpa kedip layar (*no flicker*).

2. **Master Data & Produk**:
   - CRUD Produk lengkap dengan kode SKU unik, kategori, pemasok, stok awal, dan ambang batas minimum.
   - Pop-up modal generator kode QR produk siap pratinjau dan unduh dalam format PNG.
   - Pencarian instan dan penyaringan data berbasis DataTables.

3. **Manajemen Lot / Batch Kardus (FIFO & FEFO)**:
   - Pelacakan detail tiap lot barang: kapasitas, jumlah awal, stok saat ini, tanggal masuk, tanggal kedaluwarsa, dan nomor rak.
   - Rekomendasi otomatis pengeluaran barang **FIFO (First-In, First-Out)** dan **FEFO (First-Expired, First-Out)** pada formulir barang keluar.
   - Deteksi dini peringatan batch mendekati masa kedaluwarsa.

4. **Mutasi Arus Stok (Masuk & Keluar)**:
   - Transaksi stok masuk dari supplier dengan pencatatan nomor dokumen/penerimaan.
   - Transaksi stok keluar dengan proteksi anti-minus dan validasi rekomendasi FIFO.
   - Seluruh mutasi dijalankan dalam transaksi atomik database (`trans_start` / `trans_complete`).
   - Riwayat mutasi lengkap yang dapat difilter berdasarkan tanggal, jenis transaksi, dan produk.

5. **Cetak Surat Jalan & Bukti Serah Terima Transaksi**:
   - Tombol cetak langsung pada setiap baris riwayat mutasi.
   - Format standar nota A5 / thermal siap cetak, memuat identitas perusahaan, rincian barang, dan blok tanda tangan tiga pihak (Diserahkan, Diterima, & Kepala Gudang).

6. **Scanner QR Code Fisik**:
   - Pemindaian langsung menggunakan kamera laptop atau smartphone (*html5-qrcode*).
   - Deteksi otomatis QR Produk dan QR Batch untuk membuka formulir transaksi masuk/keluar secara instan.

7. **Profil Perusahaan & Kop Surat Dinamis**:
   - Pengaturan nama PT/instansi, alamat gudang, kontak, penanggung jawab (PIC), dan catatan kaki dokumen.
   - Fitur *Live Preview* kop surat interaktif di halaman pengaturan.

8. **Pencadangan Database 1-Klik (.sql & .json)**:
   - Ekspor seluruh struktur dan data database langsung ke file `.sql` siap restore di phpMyAdmin.
   - Opsi backup snapshot berbasis data JSON.

9. **Keamanan & Personalisasi Antarmuka**:
   - Sesi terproteksi menggunakan driver database (`ci_sessions`) dan password terenkripsi `password_hash()`.
   - Proteksi CSRF, sanitasi XSS (`xss_clean`), dan Route Guard `MY_Controller`.
   - Dukungan tema Mode Terang (*Light*), Mode Gelap (*Dark*), serta pemilihan format tanggal (`DD/MM/YYYY`, `YYYY-MM-DD`, `DD MMMM YYYY`).

---

## 🛠️ Tech Stack

* **Backend:** PHP (v7.4 – v8.2) dengan framework **CodeIgniter 3.1.x**
* **Database:** MySQL / MariaDB (Driver `mysqli`)
* **Frontend:** PHP Views, Bootstrap 5.3, Bootstrap Icons, jQuery, DataTables, Chart.js
* **QR Scanner:** html5-qrcode
* **Arsitektur:** Native CI3 (Tanpa Composer & Tanpa Folder Vendor)

---

## 📂 Struktur Direktori

```
manajemen-barang/
├── application/
│   ├── config/            # Konfigurasi config, database, routes, autoload, migration
│   ├── controllers/       # Controller Auth, Dashboard, Produk, Stok, Batch, api/*
│   ├── core/              # MY_Controller (Route guard), MY_Model
│   ├── helpers/           # Helper fungsi tanggal dan utilitas
│   ├── libraries/         # Library standalone
│   ├── migrations/        # File migrasi skema database CI3
│   ├── models/            # Model data entitas
│   └── views/             # Template layout, partials, dan halaman aplikasi
├── assets/                # Aset statis CSS tema dan JavaScript
├── database.sql           # Skema database & data awal (impor phpMyAdmin)
├── uploads/               # Direktori media unggahan avatar dan QR code
├── system/                # Inti framework CodeIgniter 3
├── index.php              # Bootstrap entrypoint aplikasi
├── PRD.md                 # Product Requirements Document versi CI3
└── README.md              # Dokumentasi proyek
```

---

## 💻 Panduan Instalasi (XAMPP)

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di komputer lokal:

### 1. Letakkan Folder Proyek
Salin atau clone folder repositori ini ke dalam direktori webroot XAMPP Anda:
```bash
# Path XAMPP Windows:
C:\xampp\htdocs\manajemen-barang
```

### 2. Setup Basis Data di phpMyAdmin
1. Buka browser dan akses **`http://localhost/phpmyadmin`**.
2. Buat database baru bernama **`manajemen_barang`** (atau nama yang diinginkan).
3. Klik menu **Import** pada tab atas.
4. Pilih file **`database.sql`** yang berada di root folder proyek ini.
5. Klik **Import / Kirim**. Skema dan seluruh data awal (user, kategori, produk sampel, batch, pengaturan) akan otomatis terpasang.

*(Opsi Alternatif CLI: Jika ingin menjalankan via terminal CLI CI3, Anda cukup mengetikkan `php index.php migrate latest` lalu `php index.php migrate seed`).*

### 3. Konfigurasi Database (Jika Diperlukan)
Buka file **`application/config/database.php`** dan sesuaikan kredensial MySQL XAMPP Anda (default):
```php
$db['default'] = array(
    'hostname' => 'localhost', // atau '127.0.0.1'
    'username' => 'root',
    'password' => '',          // default XAMPP kosong
    'database' => 'manajemen_barang',
    'dbdriver' => 'mysqli',
    // ...
);
```

### 4. Buka Aplikasi di Browser
Pastikan modul **Apache** dan **MySQL** pada XAMPP Control Panel telah berjalan (*Start*), lalu buka URL berikut:
```
http://localhost/manajemen-barang/
```

---

## 🔑 Akun Login Bawaan (Default Credentials)

Gunakan kredensial berikut untuk masuk pertama kali ke sistem:

| Peran (Role) | Email | Password |
|---|---|---|
| **Administrator** | `admin@admin.com` | `password` |
| **Petugas Gudang** | `petugas@admin.com` | `password` |

> ⚠️ *Catatan Keamanan: Segera ubah kata sandi bawaan melalui menu **Profil Saya** (`/profil`) setelah berhasil masuk pada lingkungan produksi.*

---

## 📊 Skema Database (10 Tabel Utama)

1. **`users`** — Menyimpan kredensial autentikasi pengguna (role: `admin` / `petugas`).
2. **`kategori`** — Klasifikasi jenis dan kelompok barang inventaris.
3. **`pemasok`** — Data vendor dan penyedia produk barang.
4. **`produk`** — Master data barang inventaris, SKU unik, dan batas minimum stok.
5. **`batch`** — Pencatatan kardus/lot spesifik per produk, tanggal kedaluwarsa, lokasi rak, dan QR code.
6. **`stok_transaksi`** — Log mutasi arus keluar-masuk stok barang beserta operator pencatat.
7. **`sisa_stok`** — Snapshot agregasi stok produk untuk integritas data instan.
8. **`pengaturan`** — Preferensi aplikasi dan profil kop surat resmi dalam format JSON.
9. **`ci_sessions`** — Manajemen sesi pengguna berbasis tabel database native CI3.
10. **`migrations`** — Pencatat versi status migrasi skema database internal.

---

## 📄 Lisensi & Kontribusi

Proyek ini dibangun di atas framework open-source **CodeIgniter 3** di bawah lisensi [MIT License](license.txt).  
Kontribusi perbaikan bug dan penambahan fitur dipersilakan melalui *Pull Request* atau pembuatan *Issue* pada repositori ini.
