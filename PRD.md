# PRD — Sistem Manajemen Barang (Inventory Management System) — Versi CodeIgniter 3

> **Dokumen**: Product Requirements Document (PRD)  
> **Versi**: 2.6.0 (CI3 Edition)  
> **Terakhir Diperbarui**: November 2026  
> **Status**: Migrasi stack — Laravel + React → CodeIgniter 3  
> **Repository**: [kanjengdmas/manajemen-barangCI3](https://github.com/kanjengdmas/manajemen-barangCI3)  

---

## 1. Ringkasan Eksekutif

**Manajemen Barang** adalah platform inventaris gudang berbasis web yang dirancang untuk mengotomasi dan menyederhanakan pengelolaan produk, stok, lot/batch kardus, serta alur transaksi keluar-masuk barang secara real-time. Sistem dibangun dengan **CodeIgniter 3 (arsitektur MVC, server-side rendering)** dan dilengkapi pemindai kamera QR Code, ekspor laporan multi-format (Excel & PDF), antarmuka dwibahasa (Indonesia & Inggris), manajemen profil pengguna, pencadangan database 1-klik (.sql), kop surat perusahaan dinamis, serta dashboard analitik terpadu dengan tren pergerakan stok bulanan dan kemampuan pembaruan data otomatis (*auto-refresh*).

### Problem Statement
Pengelolaan inventaris berbasis manual atau spreadsheet rentan terhadap selisih stok (*stock discrepancy*), keterlambatan pembaruan data antar-shift gudang, risiko kehilangan dokumen fisik, serta lambatnya proses audit. Sistem ini mengintegrasikan seluruh titik operasional ke dalam satu basis data terpusat yang aman, cepat, dan terukur.

### Target Pengguna & Peran (Role)
- **Administrator Gudang (Single Admin)** — Memiliki akses penuh terhadap seluruh fitur: master data produk, kategori, pemasok, batch, mutasi stok, laporan eksekutif, pencadangan basis data, profil perusahaan/kop surat, serta pengaturan sistem dan preferensi pengguna. (Sistem saat ini difokuskan pada model **Single Administrator** untuk kesederhanaan operasional dan integritas kontrol penuh).

---

## 2. Ringkasan Perubahan Stack (Laravel + React → CI3)

Seluruh fungsionalitas pada PRD sebelumnya dipertahankan. Perubahan utama ada pada cara pembangunannya:

| Aspek | Sebelumnya | Sekarang (CI3) |
|---|---|---|
| Arsitektur | SPA React + REST API Laravel | MVC server-side rendering + endpoint AJAX JSON untuk bagian dinamis |
| Autentikasi | Laravel Sanctum (Bearer token di `localStorage`) | Session CI3 (cookie, driver database) + `password_hash()` |
| Route guard | `PrivateRoute` (React) | `MY_Controller` / hook `Auth` yang redirect ke `/login` |
| ORM | Eloquent | Query Builder + Model CI3 (`MY_Model`) |
| Migrasi DB | `php artisan migrate` | CI3 Migration class (`php index.php migrate latest`) |
| Cache preferensi | Laravel Cache | Tabel `pengaturan` di database (+ CI Cache driver file untuk cache query) |
| Frontend | React 19 + Vite + Tailwind | PHP Views + Bootstrap 5 + JavaScript vanilla/jQuery |
| Tema gelap | `DarkModeContext` | `data-bs-theme` + `theme.js` |
| i18n | `LanguageContext` | `i18n.js` (kamus ID/EN) + atribut `data-i18n` |
| Export Excel | `maatwebsite/excel` | Library CI3 Native (`application/libraries/Export_excel.php`) |
| Export PDF | `barryvdh/laravel-dompdf` | Library CI3 Native (`application/libraries/Export_pdf.php`) |
| QR Code | `endroid/qr-code` v5 | Library Standalone CI3 (`application/libraries/Qr_service.php`) |
| Dependensi | Composer | Standalone Native CI3 (Tanpa Composer & Vendor) |
| Build frontend | `npm run build` (Vite) | Tidak perlu build; aset statis di folder `assets/` |

---

## 3. Tech Stack

### Backend

| Komponen | Teknologi | Deskripsi |
|---|---|---|
| Framework | CodeIgniter 3.1.x (PHP 7.4 – 8.1) | MVC framework, routing, form validation, session |
| Autentikasi | CI Session (driver `database`) + `password_hash()` / `password_verify()` | Login berbasis sesi cookie dengan proteksi CSRF |
| Database | MySQL / MariaDB (XAMPP) | Relational database, schema dikelola lewat Migration class |
| Akses Data | Query Builder + Model (`MY_Model`) | Query terparameter, relasi via JOIN, transaksi via `trans_start()` |
| Keamanan | CSRF protection, `html_escape()`, Form Validation, `xss_clean` pada input | Pencegahan CSRF, XSS, dan SQL injection |
| Cache & Storage | CI Cache (driver file), tabel `pengaturan`, folder `uploads/` | Preferensi sistem/pengguna di DB; media foto & QR di folder `uploads/` |
| QR Code Engine | Library Standalone CI3 (`Qr_service`) | Generator gambar QR Code produk & batch |
| Export Excel | Library Spreadsheet/CSV Native CI3 (`Export_excel`) | Generator spreadsheet laporan stok |
| Export PDF | Library PDF Standalone CI3 (`Export_pdf`) | Generator dokumen PDF resmi berformat cetak |
| Email | CI Email library (SMTP) + Controller CLI + cron | Pengiriman Daily Digest |
| Dependency Manager | Native CI3 (Tanpa Composer & Vendor) | Library dikelola langsung di folder `application/libraries/` |

### Frontend

| Komponen | Teknologi | Deskripsi |
|---|---|---|
| Rendering | PHP Views (server-side) + AJAX `fetch()` | Halaman dirender server; data dinamis (chart, polling, scan) lewat endpoint JSON |
| Layout | Template view (`layouts/main.php` + partial header/sidebar/footer) | Layout tunggal dipakai seluruh halaman internal |
| Routing | `application/config/routes.php` | URL bersih lewat `.htaccess` (mod_rewrite) |
| Styling | Bootstrap 5.3 + `assets/css/theme.css` | Responsif, dengan tema *Crimson & Clean Slate* |
| Theme System | `data-bs-theme` + `theme.js` | Toggle Mode Terang (Light), Gelap (Dark), & Sistem |
| Multi-Language (i18n) | `assets/js/i18n.js` + `data-i18n` | Bahasa Indonesia 🇮🇩 dan English (US) 🇺🇸 tanpa reload halaman |
| UI Icons | Bootstrap Icons | Ikonografi vektor yang konsisten dan ringan |
| Animasi & Transisi | AOS + CSS transitions | Animasi micro-interaction halus |
| Tabel Data | DataTables (jQuery) | Pencarian, sorting, paginasi, dan kerapatan baris |
| Data Visualization | Chart.js (vanilla) | Diagram lingkaran stok kategori & grafik garis arus barang |
| QR Scanner | html5-qrcode | Pemindai QR Code langsung melalui kamera perangkat |
| Dialog & Notifikasi | SweetAlert2 | Modal peringatan, konfirmasi aksi, dan toast respon |
| HTTP Client | Fetch API (native) | Permintaan AJAX dengan token CSRF |

---

## 4. Arsitektur Sistem

```
+-----------------------------------------------------------------------------------+
|                         FRONTEND (PHP Views + JS di Browser)                      |
|  Dashboard | Produk | Batch | Scanner QR | Stok Mutasi | Profil | Pengaturan | Lap |
+-----------------------------------------------------------------------------------+
                                       |
                 HTTP Request (halaman) + AJAX JSON, Session Cookie + CSRF Token
                                       |
+-----------------------------------------------------------------------------------+
|                          BACKEND (CodeIgniter 3 — MVC)                            |
|  Controllers: Auth | Dashboard | Produk | Stok | Batch | Kategori | Pemasok        |
|               Profil | Pengaturan | Scanner | Laporan | api/* (JSON)              |
|  Libraries  : Qr_service | Export_excel | Export_pdf | Preference                  |
|  Models     : User_model | Produk_model | Batch_model | Stok_model | ...          |
+-----------------------------------------------------------------------------------+
                                       |
                        Query Builder / Database Driver (mysqli)
                                       |
+-----------------------------------------------------------------------------------+
|                        DATABASE & PERSISTENCE STORAGE                             |
|  users | produk | batch | kategori | pemasok | stok_transaksi | sisa_stok          |
|  pengaturan | ci_sessions | migrations        + folder uploads/ (QR, avatar)       |
+-----------------------------------------------------------------------------------+
```

### 4.1 Struktur Folder CI3

```
Manajemen-Barang/
├── application/
│   ├── config/            # config.php, database.php, routes.php, autoload.php, migration.php
│   ├── controllers/
│   │   ├── Auth.php  Dashboard.php  Produk.php  Kategori.php  Pemasok.php
│   │   ├── Batch.php  Stok.php  Scanner.php  Laporan.php  Profil.php  Pengaturan.php
│   │   ├── Migrate.php            # migrasi & seeding (khusus CLI)
│   │   ├── Cli_digest.php         # Daily Digest (dipanggil cron)
│   │   └── api/                   # endpoint JSON (Dashboard.php, Batch.php, Produk.php, ...)
│   ├── core/              # MY_Controller.php (guard login), MY_Model.php
│   ├── helpers/           # preference_helper.php (format tanggal, dsb.)
│   ├── libraries/         # Qr_service.php, Export_excel.php, Export_pdf.php
│   ├── migrations/        # file migrasi skema
│   ├── models/            # *_model.php
│   └── views/             # layouts/, partials/, auth/, dashboard/, produk/, batch/, stok/, ...
├── assets/                # css/, js/ (app.js, theme.js, i18n.js, preferences.js, scanner.js)
├── uploads/               # qr/, avatar/ (writable)
├── system/                # core CodeIgniter 3
├── database.sql           # skema database & seed data awal (impor phpMyAdmin)
├── .htaccess
└── index.php
```

---

## 5. Model Data & Skema Database

| Entitas | Kolom Kunci | Keterangan & Relasi |
|---|---|---|
| **users** | `id`, `name`, `email`, `role`, `password`, `avatar`, `total_login`, `created_at` | Pengguna sistem; role: `admin` / `petugas`. Password berupa hash `password_hash()`. Relasi ke transaksi stok. |
| **produk** | `id`, `nama_produk`, `sku`, `qr_code`, `stok`, `stok_minimal`, `kategori_id`, `pemasok_id` | Master barang inventaris; berelasi Many-to-One dengan `kategori` dan `pemasok`. |
| **kategori** | `id`, `nama_kategori`, `created_at` | Klasifikasi barang; berelasi One-to-Many dengan `produk`. |
| **pemasok** | `id`, `nama_pemasok`, `kontak`, `alamat` | Vendor penyedia produk; berelasi One-to-Many dengan `produk`. |
| **batch** | `id`, `produk_id`, `qr_code`, `kapasitas`, `jumlah_awal`, `stok_saat_ini`, `tanggal_masuk`, `tanggal_kadaluarsa`, `lokasi_rak` | Lot/kardus spesifik per produk untuk pelacakan tanggal dan penempatan rak. |
| **stok_transaksi** | `id`, `produk_id`, `batch_id`, `user_id`, `tipe`, `jumlah`, `catatan`, `scan_mode`, `tanggal` | Buku kas mutasi stok (masuk/keluar); mencatat riwayat operator dan mode scan. |
| **sisa_stok** | `id`, `produk_id`, `total_stok`, `updated_at` | Snapshot agregat stok produk untuk integritas data instan. |
| **pengaturan** | `id`, `user_id` (nullable), `kunci`, `nilai`, `updated_at` | Pengganti Laravel Cache: menyimpan preferensi pengguna (`user_id` terisi), pengaturan sistem, dan profil perusahaan/kop surat (`user_id` NULL). Nilai disimpan sebagai JSON. |
| **ci_sessions** | `id`, `ip_address`, `timestamp`, `data` | Penyimpanan sesi CI3 (driver database). |
| **migrations** | `version` | Pencatat versi migrasi CI3. |

---

## 6. Fitur & Fungsionalitas Utama

### 6.1 Autentikasi & Akun Pengguna
- **Login & Sesi Terproteksi**: Masuk menggunakan email dan password; sesi disimpan di database lewat CI Session (cookie `HttpOnly`), bukan token di `localStorage`. Password diverifikasi dengan `password_verify()`.
- **Route Guard (`MY_Controller`)**: Semua controller internal mewarisi `MY_Controller` yang memblokir akses tanpa sesi login dan mengarahkan pengguna ke `/login`. Untuk request AJAX, guard mengembalikan respon JSON `401`.
- **Header Profile Widget**: Menampilkan foto avatar, nama akun, badge role, tombol cepat notifikasi, dan dropdown menu (partial `partials/header.php`).
- **Halaman Profil Saya (`/profil`)**:
  - *Tab Informasi Akun*: Nama lengkap, email, peran, tanggal bergabung (terformat otomatis sesuai preferensi akun), total login, dan upload foto avatar pengguna ke `uploads/avatar/` (`POST /api/profile`).
  - *Tab Ubah Password*: Validasi password saat ini, pembuatan kata sandi baru (min. 8 karakter), dan konfirmasi password (`POST /api/profile/password`) lewat Form Validation CI3.
  - *Tab Preferensi Pengguna (Points 2 - 6)*:
    1. **Mode Konfirmasi Transaksi Stok (Point 2)**:
       - *Mode Aman (Direkomendasikan)*: Memunculkan pop-up modal SweetAlert konfirmasi sebelum mutasi stok masuk atau keluar dieksekusi ke database untuk meminimalisasi kesalahan input operator.
       - *Mode Cepat (Instan)*: Melewati pop-up konfirmasi dan langsung mengirim transaksi mutasi seketika, meningkatkan efisiensi kerja operator gudang berpengalaman. Terintegrasi pada `views/stok/masuk.php` dan `views/stok/keluar.php`.
    2. **Format Tampilan Tanggal Akun (Point 3)**:
       - Opsi format: `DD/MM/YYYY` (Standar Indonesia), `YYYY-MM-DD` (Standar ISO), dan `DD MMMM YYYY` (Format Panjang).
       - Dilengkapi kotak *Live Preview* contoh tanggal dan langsung merefleksikan tanggal bergabung akun secara dinamis di kartu profil.
    3. **Email Rekap Ringkasan Harian (Daily Digest) (Point 4)**:
       - Toggle pengaktifan rangkuman aktivitas mutasi stok masuk dan keluar yang dikirimkan ke email terdaftar di akhir jam kerja gudang.
       - Dijalankan oleh controller CLI `Cli_digest` melalui cron, misalnya: `0 17 * * * php /path/ke/index.php cli_digest run`, menggunakan CI Email library (SMTP).
    4. **Efek Suara Notifikasi Desktop (Point 5)**:
       - Audio chime feedback dua nada harmonis (659.25Hz & 987.77Hz) berbasis Web Audio API tanpa dependensi file eksternal.
       - Berbunyi saat peringatan sistem dipicu atau panel notifikasi lonceng di header dibuka.
       - Dilengkapi tombol interaktif "Uji Suara" untuk pengujian langsung oleh pengguna.
    5. **Penyimpanan Nyata & Auto-Save Realtime (Point 6)**:
       - Menerapkan prinsip *Auto-Save on Change (Tersimpan Otomatis Real-time)* tanpa tombol simpan manual.
       - Setiap perubahan toggle, pilihan mode transaksi, atau format tanggal langsung disinkronkan ke `localStorage` (`userPreferences`) dan ke server lewat `POST /api/profile/preferences` (tabel `pengaturan`).
       - Dilengkapi indikator badge status *Auto-Save* hijau (`🟢 Tersimpan otomatis` / `Perubahan tersimpan`) dan memancarkan event `user-preferences-changed` secara real-time ke seluruh skrip aktif di halaman.

### 6.2 Dashboard Real-Time & Pembaruan Otomatis
Pusat kendali visual kondisi operasional gudang secara langsung dari database (`Dashboard::index` + endpoint `api/dashboard/*`):
- **4 Kartu Statistik Utama (Simetris & 100% Real-Time Database)**:
  1. *Total Produk* — Jumlah seluruh item produk terdaftar.
  2. *Total Stok* — Akumulasi kuantitas unit (mendukung masking `••••••` saat fitur privasi aktif).
  3. *Stok Menipis* — Jumlah produk yang berada di bawah ambang batas minimum (*warning alert*).
  4. *Stok Habis* — Jumlah produk dengan sisa stok 0 unit (*danger alert*).
- **Fitur Refresh Otomatis Dashboard (5 Menit)**:
  - Polling data background otomatis setiap 5 menit (300.000 ms) via `setInterval` + `fetch()` saat diaktifkan di menu Pengaturan.
  - Berjalan secara *silent* tanpa memunculkan skeleton loader yang mengganggu pandangan pengguna.
  - **Indikator Visual di Header**:
    - 🟢 *Auto-Refresh Aktif (5m)* dengan animasi kedip (*pulsing ping*) dan jam pembaruan terakhir.
    - ⚪ *Auto-Refresh Nonaktif* saat dinonaktifkan.
    - Tombol manual refresh (ikon `bi-arrow-clockwise`) untuk pembaruan paksa secara instan.
- **Stok Kategori Chart**: Visualisasi distribusi kuantitas stok per kategori menggunakan Chart.js.
- **Tabel Stok Menipis**: Menampilkan 5 produk dengan stok terendah lengkap dengan ikon kontekstual per jenis barang dan status badge (*Habis* / *Menipis*).
- **Grafik Pergerakan Stok (Movement Chart)**: Grafik mingguan pembanding volume barang masuk vs barang keluar.
- **Log Aktivitas Terbaru**: Catatan 10 aktivitas mutasi stok terakhir dengan informasi waktu relatif dan operator.
- **Widget Aksi Cepat**: Akses 1-klik untuk tambah produk, input stok masuk, input stok keluar, dan tambah kategori.

### 6.3 Pengaturan Sistem & Preferensi (`/pengaturan`)
Menu kustomisasi lengkap yang menerapkan prinsip **Auto-Save on Change** tanpa tombol simpan manual, dilengkapi indikator status visual halus (*Auto-save badge*):
- **Tampilan & Tema**: Pilihan tema *Terang (Light)*, *Gelap (Dark)*, dan *Sistem* dengan mockup preview visual.
- **Bahasa & Lokalisasi**: Penggantian instan antara Bahasa Indonesia 🇮🇩 dan English (US) 🇺🇸 tanpa reload halaman (lewat `i18n.js`).
- **Pengaturan Lainnya**:
  - *Refresh otomatis dashboard*: Toggle pengaktifan interval sinkronisasi data 5 menit.
  - *Tampilkan jumlah stok*: Toggle penyembunyian/penampilan kuantitas angka stok untuk privasi presentasi.
- **Preferensi Notifikasi**: Toggle peringatan stok kritis, notifikasi mutasi transaksi, dan suara efek scanner.
- **Keamanan Sistem**: Pemilihan batas waktu sesi (*session timeout* 30m, 60m, 120m, 480m) yang divalidasi di `MY_Controller` berdasarkan `last_activity` sesi, dan Two-Factor Authentication (2FA, berbasis TOTP, misalnya library `robthree/twofactorauth`).
- **Tentang Sistem**: Informasi versi build (v2.4.0), database driver, framework backend (CodeIgniter 3), dan status aplikasi.

### 6.4 Manajemen Produk
- **CRUD Produk** (`Produk::index/create/store/edit/update/delete`): Nama, SKU unik, deskripsi, pemilihan kategori dan pemasok, stok awal, serta stok minimal, dengan validasi Form Validation CI3.
- **Pop-up Modal QR Code**: Klik "Generate" atau "Lihat QR" langsung menampilkan dialog modal visual gambar QR tanpa wajib unduh terlebih dahulu.
- **Auto-Generate & Unduh QR**: File PNG QR Code dibuat otomatis oleh library `Qr_service` jika belum tersedia (disimpan di `uploads/qr/`) dan dapat diunduh langsung dalam format PNG.
- **Filter & Pencarian Instan**: Pencarian berbasis nama dan SKU secara lokal lewat DataTables tanpa jeda jaringan.

### 6.5 Manajemen Batch & Kardus
- Pelacakan barang spesifik per lot/kardus dengan atribut: Produk, Kapasitas, Jumlah Awal, Stok Saat Ini, Lokasi Rak, Tanggal Masuk, dan Tanggal Kadaluarsa.
- Generate QR Code unik per batch.
- Pencarian dan filter produk batch, serta riwayat mutasi per batch.

### 6.6 Mutasi Stok (Masuk & Keluar)
- **Stok Masuk**: Penambahan stok barang ke batch tertentu dengan catatan penerimaan dari pemasok.
- **Stok Keluar**: Pengurangan stok barang dari batch tertentu dengan validasi proteksi anti-minus.
- Setiap mutasi dijalankan dalam satu transaksi database (`trans_start()` / `trans_complete()`) yang memperbarui `batch.stok_saat_ini`, `produk.stok`, `sisa_stok`, dan mencatat baris di `stok_transaksi` secara atomik.
- **Riwayat Transaksi**: Log pencatatan lengkap dengan filter rentang tanggal, jenis transaksi, dan produk (server-side processing agar ringan pada data besar).

### 6.7 Scanner QR Code Fisik
- Pemindaian kode QR langsung melalui kamera laptop/smartphone dengan modul `html5-qrcode` (`views/scanner/index.php` + `assets/js/scanner.js`).
- Pengenalan otomatis QR Batch atau QR Produk lewat `GET /api/batch/scan/{qrCode}` untuk langsung membuka formulir transaksi masuk/keluar.

### 6.8 Ekspor & Pelaporan Data
- Laporan inventaris berkala berdasarkan filter tanggal, jenis transaksi, dan produk.
- Ekspor tabel ke file Microsoft Excel (`.xlsx`) via `phpoffice/phpspreadsheet` (library `Export_excel`).
- Ekspor laporan berformat cetak dokumen resmi PDF (`.pdf`) via `dompdf/dompdf` (library `Export_pdf`).

### 6.9 Pengaturan Lanjutan & Personalisasi Gudang (v2.4.0)
1. **Interval Waktu Auto-Refresh Dinamis**: Pilihan jeda polling otomatis (1, 3, 5, 10 menit) dengan indikator badge waktu real-time di header dashboard.
2. **Konfigurasi Scanner QR & Kamera**:
   - Efek suara konfirmasi beep (880 Hz) menggunakan Web Audio API tanpa dependensi file eksternal.
   - Pilihan kamera default (kamera belakang / *environment* vs kamera depan / *user*).
   - Opsi pembukaan otomatis pop-up transaksi mutasi saat QR berhasil dipindai.
3. **Ambang Batas Peringatan Kadaluarsa Batch**: Pemantauan usia kedaluwarsa produk batch (7, 14, 30, 60 hari) disertai lencana visual status (*Expired*, *Warning H-X*, *Aman*) pada tabel batch.
4. **Standardisasi Kode & Prefix Gudang**: Prefix otomatis untuk SKU Produk (contoh: `PRD-`) dan Lot Batch (contoh: `LOT-`) pada pembuatan formulir baru dan pembuatan QR code.
5. **Default Baris per Halaman (Paginasi)**: Konfigurasi jumlah baris per halaman (10, 25, 50, 100 baris) yang langsung sinkron ke semua tabel DataTables (Produk, Batch, Mutasi Stok, Kategori, Pemasok) lewat opsi `pageLength`.
6. **Kerapatan Baris & Urutan Default Data Tabel**:
   - **Kerapatan Baris Tabel (Row Density)**: Pilihan *Normal / Nyaman (Standar)* vs *Ringkas / Padat (Lebih Banyak Data)* lewat class CSS pada tabel.
   - **Urutan Default Data Tabel (Default Sort Order)**: Pilihan urutan data produk (*Terbaru Ditambahkan*, *Stok Menipis Dahulu / Prioritas Restock*, *Stok Terbanyak Dahulu*, dan *Nama Produk A-Z*) lewat opsi `order` DataTables.
7. **Penyimpanan Otomatis Real-Time (Auto-Save)**: Pengaturan disimpan seketika ke `localStorage` dan tabel `pengaturan` saat nilai diubah, dengan indikator animasi tersimpan tanpa tombol manual.

### 6.10 Fitur Manajemen Gudang & Pengaturan Tingkat Lanjut (v2.5.2 — Point 1-5)
1. **Pengaturan Cetak Label QR Kardus & Rak Fisik (Point 1)**:
   - Pilihan ukuran label standar pencetakan: *Kecil (30x20mm)*, *Sedang (50x30mm)*, dan *Standar Rak (70x40mm)*.
   - Opsi toggle penyertaan informasi produk (Nama Barang, SKU, dan Kategori) pada lembar label fisik.
   - Tombol "Cetak Label" pada modal pop-up QR Code Produk (`views/produk/index.php`) yang membuka window cetak browser dengan dimensi presisi dan styling ramah printer thermal/kertas stiker.
2. **Manajemen Data & Cadangan Gudang (Database Snapshot & Cache Server) (Point 2)**:
   - Tab navigasi khusus "Manajemen Data & Cadangan" pada halaman Pengaturan (`/pengaturan`).
   - **Ekspor Cadangan Data (`GET /api/settings/backup`)**: Menghasilkan file JSON snapshot berisi seluruh entitas database (`kategori`, `pemasok`, `produk`, `batch`, dan riwayat transaksi stok) dengan nama `backup-gudang-YYYY-MM-DD-His.json`.
   - **Pembersihan Cache Sistem (`POST /api/settings/clear-cache`)**: Mengosongkan cache query dan cache aplikasi (`$this->cache->clean()`) untuk menyegarkan performa server. Data di tabel `pengaturan` tidak ikut terhapus.
3. **Default Ambang Stok Menipis Produk Baru (Point 3)**:
   - Konfigurasi ambang stok minimal default (5, 10, 20, 50 unit) di menu Pengaturan.
   - Terintegrasi otomatis ke formulir registrasi produk baru (`views/produk/create.php`) sehingga operator tidak perlu menginput stok minimal berulang.
4. **Format Tampilan Tanggal & Waktu Transaksi (Point 4)**:
   - Konfigurasi format tampilan tanggal transaksi global: `DD/MM/YYYY` (Standar Indonesia), `YYYY-MM-DD` (ISO), dan `DD MMMM YYYY` (Format Panjang).
   - Terintegrasi lewat helper PHP `format_date_by_preference()` (sisi server, untuk PDF/cetak) dan fungsi JS `formatDateByPreference()` (sisi klien) pada tabel riwayat transaksi stok (`views/stok/index.php`), laporan (`views/laporan/index.php`), serta profil pengguna.
5. **Kebijakan Pengeluaran Barang (FIFO / First-In First-Out Recommendation) (Point 5)**:
   - Toggle kebijakan pengeluaran stok FIFO di tab Gudang & Mutasi Pengaturan.
   - Terintegrasi pada formulir mutasi barang keluar (`views/stok/keluar.php`):
     - Batch terurut otomatis mendahulukan tanggal kadaluarsa terawal (FEFO) dan tanggal masuk tertua (FIFO), dengan query `ORDER BY tanggal_kadaluarsa ASC, tanggal_masuk ASC` pada batch yang `stok_saat_ini > 0`.
     - Batch tertua otomatis terpilih (*auto-select*) saat produk ditentukan.
     - Penanda visual opsi `⭐ [Rekomendasi FIFO]` pada dropdown batch.
     - Banner status hijau jika memilih batch yang sesuai kebijakan FIFO, atau banner peringatan amber jika memilih batch berbeda.
     - Peringatan kontekstual pada dialog konfirmasi mutasi stok jika terjadi deviasi dari rekomendasi FIFO.

### 6.11 Fitur Operasional Gudang Nyata & Skalabilitas (v2.6.0)
1. **Cetak Surat Jalan & Bukti Serah Terima Transaksi (Receipt / Delivery Slip)**:
   - Tombol "Cetak Bukti Transaksi" (ikon printer) pada setiap baris riwayat mutasi stok di tabel transaksi (`views/stok/index.php`), membuka halaman cetak `Stok::bukti/(:num)`.
   - Format cetak ramah nota standar A5 dan thermal printing:
     - Header dokumen memuat otomatis profil dan kop surat resmi perusahaan (Nama PT, tagline, alamat kantor/gudang, nomor telepon/WA).
     - Rincian transaksi lengkap: Nomor referensi transaksi, tanggal & jam cetak, nama produk, kode SKU, ID Batch/Lot, kuantitas mutasi (Masuk/Keluar), jenis transaksi, dan catatan operasional.
     - Blok legalitas tanda tangan tiga pihak: **Diserahkan Oleh** (Pengirim/Supplier), **Diterima Oleh** (Penerima Divisi), dan **Kepala Gudang / PIC** (nama PIC dan jabatan disinkronkan dinamis dari pengaturan identitas perusahaan).
2. **Cadangkan & Unduh Database 1-Klik (.sql)**:
   - **Unduh Cadangan SQL Script (`GET /api/settings/backup-sql`)**:
     - Memakai `Database Utility` CI3 (`$this->dbutil->backup()`, format `txt`, `add_drop` & `add_insert` aktif) lalu `force_download()` ke berkas `.sql`.
     - Mengekspor seluruh struktur tabel (`CREATE TABLE`) dan data (`INSERT INTO`); siap di-restore ke MySQL/MariaDB (XAMPP / phpMyAdmin).
   - **Ekspor Snapshot JSON (`GET /api/settings/backup`)**: Tetap disediakan untuk kebutuhan arsip data aplikasi berbasis JSON.
   - *Catatan*: Unduh berkas biner `.sqlite` dihapus karena stack CI3 ini menggunakan MySQL/MariaDB. Fitur tersebut hanya relevan bila driver database diganti ke SQLite.
3. **Grafik Tren Pergerakan Stok Bulanan Dashboard**:
   - Pilihan rentang periode dinamis pada grafik arus barang masuk & keluar:
     - `7 Hari Terakhir` (Harian)
     - `30 Hari Terakhir` (Harian)
     - `6 Bulan Terakhir (Bulanan)`
     - `1 Tahun Terakhir (12 Bulan)`
   - Endpoint (`/api/dashboard/movement-chart?days=6m` & `days=12m`) mengagregasi volume transaksi masuk dan keluar per bulan (`GROUP BY YEAR(tanggal), MONTH(tanggal)`) dengan label bulan & tahun (misal: *May 2026*, *Jun 2026*, dst.).
   - Dua kartu ringkasan di atas grafik beralih menghitung total akumulasi masuk & keluar selama periode yang dipilih (*Total Masuk Periode Ini* & *Total Keluar Periode Ini*).
4. **Profil Perusahaan & Kop Surat Dokumen Dinamis**:
   - Tab navigasi khusus **"Profil & Kop Surat"** pada menu Pengaturan (`/pengaturan`).
   - Formulir terstruktur untuk identitas institusi:
     - Nama Perusahaan / Instansi
     - Sub-judul / Tagline Kop Surat
     - Alamat Lengkap Kantor / Gudang
     - Nomor Telepon & WhatsApp
     - Email Resmi Instansi
     - Nama PIC / Kepala Pergudangan (Penanggung Jawab)
     - Jabatan Resmi PIC
     - Catatan Kaki Dokumen Cetak (Footer Note)
   - **Pratinjau Langsung (Live Preview)**: Visualisasi kop surat dan blok tanda tangan interaktif yang terupdate real-time saat form diedit (JavaScript).
   - **Integrasi Terpusat**:
     - Data tersimpan via `/api/settings/company` (tabel `pengaturan`) dan `localStorage` (`companyProfile`).
     - Sinkronisasi instan melalui custom event `company-profile-updated` sehingga perubahan langsung tercermin pada cetak Bukti Transaksi di menu Stok dan ekspor PDF resmi laporan stok (`/api/stok/export/pdf`).

---

## 7. Daftar Endpoint

Aplikasi CI3 ini memakai dua jenis endpoint: **halaman** (dirender server, form `POST` dengan CSRF) dan **endpoint AJAX JSON** berawalan `/api/` yang dipakai untuk chart, polling, scanner, dan auto-save. Semua endpoint selain login dilindungi sesi (`MY_Controller`). Request `POST` wajib menyertakan token CSRF (`csrf_token_name` / `csrf_hash`); `csrf_regenerate` disarankan `FALSE` agar AJAX paralel tidak gagal.

### 7.1 Publik
| Method | Endpoint | Controller::method | Fungsi |
|---|---|---|---|
| `GET` | `/login` | `Auth::index` | Menampilkan formulir login |
| `POST` | `/login` | `Auth::attempt` | Autentikasi user & pembuatan sesi |

### 7.2 Terproteksi (Sesi Login)

#### Profil & Preferensi Pengguna
| Method | Endpoint | Controller::method | Fungsi |
|---|---|---|---|
| `POST` | `/logout` | `Auth::logout` | Menghancurkan sesi aktif |
| `GET` | `/api/user` | `api/Profile::me` | Data akun yang sedang login |
| `GET` | `/api/profile` | `api/Profile::index` | Detail profil lengkap beserta tanggal bergabung ISO |
| `POST` | `/api/profile` | `api/Profile::update` | Memperbarui nama, email, dan avatar |
| `POST` | `/api/profile/password` | `api/Profile::password` | Memvalidasi dan mengubah password akun |
| `GET` | `/api/profile/preferences` | `api/Profile::preferences` | Mengambil preferensi akun (konfirmasi stok, format tanggal, daily digest, suara) |
| `POST` | `/api/profile/preferences` | `api/Profile::save_preferences` | Menyimpan preferensi akun ke tabel `pengaturan` |
| `GET, POST` | `/users`, `/users/create`, `/users/edit/(:num)`, `/users/delete/(:num)` | `Users::*` | Manajemen pengguna oleh admin |

#### Pengaturan Sistem & Cadangan
| Method | Endpoint | Controller::method | Fungsi |
|---|---|---|---|
| `GET` | `/api/settings` | `api/Settings::index` | Mengambil preferensi sistem (tema, bahasa, auto_refresh, format tanggal, dsb.) |
| `POST` | `/api/settings` | `api/Settings::save` | Menyimpan preferensi sistem ke tabel `pengaturan` |
| `GET` | `/api/settings/backup` | `api/Settings::backup` | Mengunduh JSON snapshot cadangan database gudang |
| `GET` | `/api/settings/backup-sql` | `api/Settings::backup_sql` | Mengunduh cadangan SQL dump (.sql) 1-klik |
| `GET` | `/api/settings/company` | `api/Settings::company` | Mengambil profil perusahaan & kop surat |
| `POST` | `/api/settings/company` | `api/Settings::save_company` | Menyimpan identitas perusahaan & kop surat |
| `POST` | `/api/settings/clear-cache` | `api/Settings::clear_cache` | Mengosongkan cache aplikasi |

#### Dashboard
| Method | Endpoint | Controller::method | Fungsi |
|---|---|---|---|
| `GET` | `/api/dashboard/stats` | `api/Dashboard::stats` | Agregat kartu statistik dan tren data |
| `GET` | `/api/dashboard/stok-chart` | `api/Dashboard::stok_chart` | Distribusi kuantitas stok per kategori |
| `GET` | `/api/dashboard/low-stock` | `api/Dashboard::low_stock` | Daftar produk stok menipis / kritis |
| `GET` | `/api/dashboard/movement-chart` | `api/Dashboard::movement_chart` | Pergerakan stok (harian: `days=7`, `30`; bulanan: `days=6m`, `12m`) |
| `GET` | `/api/dashboard/recent-activities` | `api/Dashboard::recent_activities` | 10 riwayat transaksi mutasi terakhir |

#### Produk, Kategori, & Pemasok
| Method | Endpoint | Controller::method | Fungsi |
|---|---|---|---|
| `GET, POST` | `/produk`, `/produk/create`, `/produk/store`, `/produk/edit/(:num)`, `/produk/update/(:num)`, `/produk/delete/(:num)` | `Produk::*` | CRUD data produk |
| `GET` | `/api/produk/(:num)/history` | `api/Produk::history` | Riwayat mutasi stok per produk |
| `GET, POST` | `/kategori`, `/kategori/create`, `/kategori/edit/(:num)`, `/kategori/delete/(:num)` | `Kategori::*` | CRUD kategori barang |
| `GET, POST` | `/pemasok`, `/pemasok/create`, `/pemasok/edit/(:num)`, `/pemasok/delete/(:num)` | `Pemasok::*` | CRUD pemasok / vendor |

#### Batch & Kardus
| Method | Endpoint | Controller::method | Fungsi |
|---|---|---|---|
| `GET, POST` | `/batch`, `/batch/create`, `/batch/edit/(:num)`, `/batch/delete/(:num)` | `Batch::*` | CRUD lot/batch barang |
| `GET` | `/api/batch/(:num)/history` | `api/Batch::history` | Riwayat mutasi pada batch tertentu |
| `GET` | `/api/batch/scan/(:any)` | `api/Batch::scan` | Pencarian dan identifikasi batch via QR Code |
| `GET` | `/api/batch/produk/(:num)/batches` | `api/Batch::by_produk` | Daftar batch milik satu produk (terurut FIFO/FEFO) |

#### Layanan QR Code
| Method | Endpoint | Controller::method | Fungsi |
|---|---|---|---|
| `POST` | `/api/produk/(:num)/generate-qr` | `api/Produk::generate_qr` | Generate file QR Code produk |
| `GET` | `/api/produk/(:num)/download-qr` | `api/Produk::download_qr` | Mengunduh PNG QR produk |
| `POST` | `/api/produk/(:num)/delete-qr` | `api/Produk::delete_qr` | Menghapus file QR produk *(memakai POST agar tervalidasi CSRF)* |
| `POST` | `/api/batch/(:num)/generate-qr` | `api/Batch::generate_qr` | Generate file QR Code batch |
| `GET` | `/api/batch/(:num)/download-qr` | `api/Batch::download_qr` | Mengunduh PNG QR batch |

#### Transaksi Stok & Ekspor
| Method | Endpoint | Controller::method | Fungsi |
|---|---|---|---|
| `POST` | `/stok/masuk` | `Stok::masuk_store` | Catat transaksi barang masuk |
| `POST` | `/stok/keluar` | `Stok::keluar_store` | Catat transaksi barang keluar |
| `GET` | `/api/stok/history` | `api/Stok::history` | Riwayat log mutasi stok (server-side processing) |
| `GET` | `/api/stok/summary` | `api/Stok::summary` | Ringkasan akumulasi mutasi |
| `GET` | `/api/stok/export/excel` | `api/Stok::export_excel` | Unduh Excel laporan stok |
| `GET` | `/api/stok/export/pdf` | `api/Stok::export_pdf` | Unduh PDF resmi laporan stok ber-kop perusahaan & tanda tangan PIC |
| `GET` | `/api/batch/export/excel` | `api/Batch::export_excel` | Unduh Excel laporan batch |

---

## 8. Struktur Halaman & Routing

Route didefinisikan di `application/config/routes.php` (URL huruf kecil, contoh `$route['produk/edit/(:num)'] = 'produk/edit/$1';`). Alias bahasa Inggris (`/profile`, `/settings`) diarahkan ke controller yang sama.

| Route URL | Controller::method | View | Deskripsi Fungsional |
|---|---|---|---|
| `/login` | `Auth::index` | `auth/login.php` | Formulir masuk akun |
| `/` & `/dashboard` | `Dashboard::index` | `dashboard/index.php` | Dashboard analitik utama gudang |
| `/profil` & `/profile` | `Profil::index` | `profil/index.php` | Kelola akun, avatar, dan kata sandi |
| `/pengaturan` & `/settings` | `Pengaturan::index` | `pengaturan/index.php` | Pengaturan tema, bahasa, auto-refresh, notifikasi, cadangan, kop surat |
| `/produk` | `Produk::index` | `produk/index.php` | Tabel master produk, pencarian, dan pop-up QR |
| `/produk/create` | `Produk::create` | `produk/create.php` | Formulir registrasi produk baru |
| `/produk/edit/(:num)` | `Produk::edit` | `produk/edit.php` | Formulir pembaruan data produk |
| `/kategori` | `Kategori::index` | `kategori/index.php` | Daftar kategori barang |
| `/kategori/create` | `Kategori::create` | `kategori/create.php` | Tambah kategori baru |
| `/kategori/edit/(:num)` | `Kategori::edit` | `kategori/edit.php` | Edit kategori |
| `/pemasok` | `Pemasok::index` | `pemasok/index.php` | Daftar pemasok barang |
| `/pemasok/create` | `Pemasok::create` | `pemasok/create.php` | Tambah pemasok baru |
| `/pemasok/edit/(:num)` | `Pemasok::edit` | `pemasok/edit.php` | Edit pemasok |
| `/batch` | `Batch::index` | `batch/index.php` | Tabel batch/lot kardus & lokasi rak |
| `/batch/create` | `Batch::create` | `batch/create.php` | Registrasi batch baru |
| `/batch/edit/(:num)` | `Batch::edit` | `batch/edit.php` | Edit data batch |
| `/batch/detail/(:num)` | `Batch::detail` | `batch/detail.php` | Rincian kapasitas & sisa unit batch |
| `/batch/history/(:num)` | `Batch::history` | `batch/history.php` | Riwayat alur mutasi pada batch |
| `/stok` | `Stok::index` | `stok/index.php` | Riwayat keluar-masuk stok barang |
| `/stok/masuk` | `Stok::masuk` | `stok/masuk.php` | Formulir pencatatan barang masuk |
| `/stok/keluar` | `Stok::keluar` | `stok/keluar.php` | Formulir pencatatan barang keluar |
| `/stok/bukti/(:num)` | `Stok::bukti` | `stok/bukti.php` | Cetak surat jalan / bukti serah terima |
| `/scan` | `Scanner::index` | `scanner/index.php` | Antarmuka kamera pemindai QR Code |
| `/laporan` | `Laporan::index` | `laporan/index.php` | Pusat filter dan unduh ekspor laporan |

---

## 9. Desain, Estetika, & UI/UX

- **Identitas Visual**: Skema warna modern bernuansa *Crimson & Clean Slate* (merah `#dc2626` / `#b91c1c` dengan kontras putih bersih dan slate), diterapkan lewat variabel CSS Bootstrap di `assets/css/theme.css`.
- **Full Dark Mode**: Tema gelap terintegrasi penuh ke seluruh kartu, formulir, modal, dan tabel lewat `data-bs-theme="dark"`.
- **Reaktivitas Tanpa Refresh**: Pergantian bahasa (ID/EN) dan perubahan preferensi visual diterapkan ke DOM secara instan lewat JavaScript, tanpa reload browser.
- **Silent Background Syncing**: Pembaruan otomatis data dashboard tidak menyebabkan layar berkedip (*no full page flicker*).
- **Aksesibilitas & Feedback**: Menggunakan SweetAlert2 untuk konfirmasi destruktif (hapus data/logout) dan toast notifikasi sukses.

---

## 10. Persyaratan Non-Fungsional

1. **Keamanan**:
   - Seluruh controller operasional mewarisi `MY_Controller` (wajib sesi login); endpoint AJAX mengembalikan `401` jika sesi habis.
   - `csrf_protection` aktif di `config.php`; output view memakai `html_escape()`.
   - Kata sandi di-hash dengan `password_hash()` (Bcrypt / Argon2); cookie sesi `HttpOnly`.
   - Validasi ketat dengan Form Validation CI3 dan Query Builder terparameter untuk mencegah injeksi, serta penolakan transaksi stok bernilai minus.
   - Folder `application/` dan `system/` tidak boleh dapat diakses langsung dari web (gunakan `.htaccess` atau pindahkan di luar webroot).
2. **Kinerja & Responsivitas**:
   - Aset statis dikompres lewat `mod_deflate` (gzip) dan di-cache browser; tanpa langkah build.
   - Paginasi (DataTables / server-side processing) dan *debounce* pencarian untuk kelancaran di perangkat berspesifikasi rendah.
3. **Kompatibilitas Lintas Perangkat**:
   - Layout responsif optimal mulai dari layar ponsel (360px), tablet (768px), hingga monitor desktop (1920px+).
4. **Kompatibilitas Runtime**:
   - PHP 7.4 – 8.1 (CI 3.1.13 belum sepenuhnya bebas *deprecation notice* pada PHP 8.2+), MySQL 5.7+/MariaDB 10.4+, Apache dengan `mod_rewrite`.

---

## 11. Cara Instalasi & Menjalankan Aplikasi

```bash
# 1. Letakkan folder project di webroot XAMPP (htdocs)
cd C:/xampp/htdocs/manajemen-barang

# 2. Setup Database di phpMyAdmin (XAMPP)
#    - Buka http://localhost/phpmyadmin
#    - Buat database baru bernama `manajemen_barang`
#    - Import file `database.sql` yang ada di root project
#    - (Opsi alternatif CLI CI3: jalankan `php index.php migrate latest` lalu `php index.php migrate seed`)

# 3. Konfigurasi aplikasi
#    - Pastikan konfigurasi di application/config/database.php sudah sesuai (default: hostname 127.0.0.1/localhost, db: manajemen_barang, user: root, pass: '')
#    - Atur base_url dan encryption_key di application/config/config.php (pastikan 'composer_autoload' = FALSE)

# 4. Beri izin tulis pada folder media/cache (jika di Linux/macOS)
chmod -R 775 uploads application/cache application/logs

# 5. Jalankan aplikasi
#    - Melalui Apache XAMPP: akses http://localhost/manajemen-barang/ di browser
#    - Atau via server bawaan PHP: php -S localhost:8000
```

**Cron (opsional, untuk Daily Digest):**

```
0 17 * * * php /path/ke/Manajemen-Barang/index.php cli_digest run
```

**Akun Bawaan (Default):**
- Email: `admin@admin.com` atau `admin@gmail.com`
- Password: `password` *(wajib diganti setelah login pertama pada lingkungan produksi)*

---

*Dokumen ini diperbarui untuk mencerminkan konversi stack Manajemen Barang v2.6.0 dari Laravel + React ke CodeIgniter 3 — November 2026.*
