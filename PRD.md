# PRD — Sistem Manajemen Barang (Inventory Management System) — Versi CodeIgniter 3

> **Dokumen**: Product Requirements Document (PRD)  
> **Versi**: 2.12.0 (CI3 Enterprise SaaS UI/UX Edition — Unified Multi-Palette 6-Theme Architecture & Pure FIFO Stock Alignment)  
> **Terakhir Diperbarui**: November 2026  
> **Status**: Migrasi stack (Laravel + React → CI3) & Redesain UI/UX Enterprise SaaS Modern  
> **Repository**: [kanjengdmas/manajemen-barangCI3](https://github.com/kanjengdmas/manajemen-barangCI3)  

---

## 1. Ringkasan Eksekutif

**Manajemen Barang** adalah platform inventaris gudang berbasis web yang dirancang untuk mengotomasi dan menyederhanakan pengelolaan produk, stok, lot/batch kardus, serta alur transaksi keluar-masuk barang secara real-time. Sistem dibangun dengan **CodeIgniter 3 (arsitektur MVC, server-side rendering)** dan mengadopsi standar antarmuka **Enterprise SaaS Modern (Linear / Stripe / Vercel style)** dengan Quiet Chrome aesthetic, skema *Crimson & Clean Slate*, pemindai kamera QR Code fisik, ekspor laporan multi-format (Excel & PDF), antarmuka dwibahasa (Indonesia & Inggris), **Arsitektur Mobile-First Komprehensif (Docked Mobile Bottom Navigation, Native Mobile Data Cards, 2x2 Stat KPI Grid, Touch-First Ergonomics)**, **Sistem Palet 6 Tema Terpadu Desktop & Mobile (Light, Dark, Sepia Warm Sand, Midnight Navy, OLED Pure Black, Forest Emerald)** via dropdown palet header bebas kedipan (*Zero FOIT*), Pusat Manajemen Profil & Kredensial Operator Ramping (`/profil`), kebijakan rotasi stok murni **FIFO (First-In, First-Out)**, pencadangan database 1-klik (.sql), kop surat perusahaan dinamis, serta dashboard analitik terpadu dengan tren pergerakan stok bulanan, adaptasi warna Chart.js multi-tema instan, tabel aktivitas mutasi interaktif berkemampuan scroll & filter instan, ticker jam detik real-time (`HH:MM:SS`), dan auto-refresh background tanpa kedip.

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
| Theme System | `data-bs-theme` + `theme.js` | Palet 6 Tema Visual (Light, Dark, Sepia, Navy, OLED, Forest) + Zero-FOIT Script & Dynamic Chart.js Adaptation |
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
| **batch** | `id`, `produk_id`, `qr_code`, `kapasitas`, `jumlah_awal`, `stok_saat_ini`, `tanggal_masuk`, `lokasi_rak` | Lot/kardus barang untuk rotasi murni FIFO (First In, First Out) dan penempatan rak fisik gudang telekomunikasi. Kolom `tanggal_kadaluarsa` dinonaktifkan/null demi kesesuaian domain material hardware Telkom Akses. |
| **stok_transaksi** | `id`, `produk_id`, `batch_id`, `user_id`, `tipe`, `jumlah`, `catatan`, `scan_mode`, `tanggal` | Buku kas mutasi stok (masuk/keluar); mencatat riwayat operator dan mode scan. |
| **sisa_stok** | `id`, `produk_id`, `total_stok`, `updated_at` | Snapshot agregat stok produk untuk integritas data instan. |
| **pengaturan** | `id`, `user_id` (nullable), `kunci`, `nilai`, `updated_at` | Pengganti Laravel Cache: menyimpan preferensi pengguna (`user_id` terisi), pengaturan sistem, dan profil perusahaan/kop surat (`user_id` NULL). Nilai disimpan sebagai JSON. |
| **ci_sessions** | `id`, `ip_address`, `timestamp`, `data` | Penyimpanan sesi CI3 (driver database). |
| **migrations** | `version` | Pencatat versi migrasi CI3. |

---

## 6. Fitur & Fungsionalitas Utama

### 6.1 Autentikasi & Akun Pengguna
- **Halaman Login Ambient Split-Screen (`/login`)**:
  - Mengusung tata letak *split-screen modern enterprise*:
    - **Sisi Kiri (Hero Brand Showcase)**: Panel gelap elegan dengan background ambient radial glow, badge version pill, display headline, tiga kartu telemetri sistem (*99.98% Akurasi Stok*, *Sinkronisasi Database Real-Time*, dan *ISO/IEC 27001 Security Standard*), serta security trust banner.
    - **Sisi Kanan (Authentication Card)**: Form login ramping dan bersih dengan logo mark bercahaya halus, input floating dengan ikon Bootstrap, toggle visibilitas password mata (`bi-eye`/`bi-eye-slash`), proteksi CSRF otomatis, dan **1-Click Interactive Demo Fill** (tombol cepat untuk mengisi otomatis kredensial akun demo `admin@admin.com` & `password` demi kemudahan pengujian dan demonstrasi instan).
- **Login & Sesi Terproteksi**: Masuk menggunakan email dan password; sesi disimpan di database lewat CI Session (cookie `HttpOnly`), bukan token di `localStorage`. Password diverifikasi dengan `password_verify()`.
- **Route Guard (`MY_Controller`)**: Semua controller internal mewarisi `MY_Controller` yang memblokir akses tanpa sesi login dan mengarahkan pengguna ke `/login`. Untuk request AJAX, guard mengembalikan respon JSON `401`.
- **Tab-Scoped Session Security Guard (`sessionStorage` Protection)**:
  - Mengisolasi otorisasi sesi per tab browser individual. Saat tab peramban ditutup dan tab baru dibuka untuk mengakses URL aplikasi, sistem tidak langsung menampilkan dashboard, melainkan memblokir render konten dan segera mengarahkan pengguna ke halaman login (`/login`).
  - Menggunakan script proteksi dini di `<head>` master layout (`layouts/main.php`): mengevaluasi token `sessionStorage.getItem('mb_tab_auth')`. Jika token tidak ditemukan pada konteks tab yang baru dibuka, peramban mengeksekusi `window.location.replace('/logout')`.
  - Pada halaman login (`auth/login.php`), token tab lama otomatis dibersihkan saat render dan token otentikasi baru diinjeksikan seketika saat form autentikasi berhasil disubmit (`sessionStorage.setItem('mb_tab_auth', '1')`).
  - Diperkuat dengan konfigurasi `$config['sess_expiration'] = 0;` pada CodeIgniter 3 agar cookie sesi otomatis kedaluwarsa saat seluruh peramban ditutup.
- **Sidebar Navigasi Modern Enterprise SaaS (`views/partials/sidebar.php`)**:
  - Quiet Chrome aesthetic ala Linear/Stripe dengan layout terbagi menjadi 4 seksi logis: *Pusat Kontrol*, *Operasional Gudang*, *Laporan & Dokumen*, dan *Konfigurasi*.
  - Kotak logo brand dengan aura crimson halus dan status online.
  - Fitur pencarian menu cepat (*Quick Jump Command Search*) dengan shortcut keyboard `Ctrl + K` / `⌘K` dan keycap kustom.
  - Indikator status koneksi server live dengan efek denyut hijau (*pulsing beacon dot* `🟢 Server Online`).
  - Active indicator bar merah crimson (3px) pada menu aktif dengan efek transisi mulus.
  - Kartu profil ringkas di kaki sidebar (*footer user pill*) menampilkan avatar inisial, role pengguna, status online, serta tombol logout cepat.
- **Header Profile Widget**: Menampilkan foto avatar, nama akun, badge role, jam realtime presisi tinggi dengan penghitung detik dinamis (`HH:MM:SS`), dan dropdown menu (partial `partials/header.php`).
- **Pusat Profil & Pengaturan Akun Operator Terpadu (`/profil`)**:
  - **Operator Hero Card (Kartu Identitas Terpadu)**:
    - *Interactive Circular Avatar Uploader*: Frame foto lingkaran modern dengan hover overlay ("Ubah"), preview instan via client-side `FileReader`, opsi "Hapus Foto" yang otomatis mengembalikan ke avatar inisial, dan batas unggah file (JPG/PNG/WebP maks. 2MB).
    - *Identitas & Metadata Operator*: Nama lengkap, email resmi, badge peran (`SUPERADMIN` / `OPERATOR`), serta ringkasan tanggal login terakhir.
    - *Operator Telemetry Stat Pills*: Menampilkan akumulasi aktivitas mutasi yang ditangani operator, frekuensi login (`total_login`), serta bulan & tahun bergabung.
  - **Arsitektur 3-Segmented Tabs Ramping (Quiet Chrome)**:
    1. **Tab 1: Informasi Akun Operator (`tab-info`)**:
       - Form pembaruan nama lengkap dan alamat email dengan proteksi validasi unik.
       - Kolom peran/hak akses dan tanggal pendaftaran dalam mode *read-only* dengan badge indikator keamanan.
       - Banner notifikasi foto baru yang siap disimpan sebelum submit.
       - Eksekusi pembaruan asinkronus (AJAX) via `POST /api/profile/update` dengan SweetAlert2 feedback.
    2. **Tab 2: Keamanan & Kata Sandi (`tab-security`)**:
       - Kolom verifikasi kata sandi saat ini (`current_password`).
       - Kolom kata sandi baru (`new_password`) dilengkapi toggle visibilitas mata (`bi-eye` / `bi-eye-slash`).
       - **Real-Time Password Strength Meter**: Evaluator kekuatan kata sandi interaktif dengan 4 baris segmen warna (*Lemah, Cukup, Baik, Sangat Kuat*) dan checklist persyaratan dinamis (Min. 8 Karakter, Huruf Besar & Kecil, Angka 0-9, Karakter Spesial/Simbol).
       - **Live Password Match Feedback**: Indikator instan kecocokan antara kolom kata sandi baru dan konfirmasi kata sandi.
       - Submit pembaruan kata sandi via `POST /api/profile/password`.
    3. **Tab 3: Sesi & Audit Log (`tab-session`)**:
       - Telemetri perangkat klien: Alamat IP riil, status peran aktif, protokol keamanan valid, dan string lengkap *User Agent* peramban.
       - Tombol pintas keluar akun (*Logout*) aman dengan token CSRF.
       *(Catatan: Tab Preferensi & Personalisasi lama dihilangkan untuk mengeliminasi redundansi, karena tema dan konfigurasi global telah terpusat di header dan pengaturan sistem).*

### 6.2 Dashboard Real-Time & Pembaruan Otomatis
Pusat kendali visual kondisi operasional gudang secara langsung dari database (`Dashboard::index` + endpoint `api/dashboard/*`):
- **4 Kartu Statistik Utama (Simetris & 100% Real-Time Database)**:
  1. *Total Produk* — Jumlah seluruh item produk terdaftar.
  2. *Total Stok* — Akumulasi kuantitas unit seluruh barang gudang secara transparan dan jelas tanpa masking.
  3. *Stok Menipis* — Jumlah produk yang berada di bawah ambang batas minimum (*warning alert*).
  4. *Stok Habis* — Jumlah produk dengan sisa stok 0 unit (*danger alert*).
- **Fitur Refresh Otomatis Dashboard & Detik Realtime di Header**:
  - Polling data background otomatis setiap 5 menit (300.000 ms) via `setInterval` + `fetch()` saat diaktifkan di menu Pengaturan.
  - Ticker detik real-time (`HH:MM:SS`) presisi tinggi berjalan setiap 1000ms pada header badge (`LIVE (5m) · Pembaruan: HH:MM:SS`) dan topbar live clock tanpa pergeseran layout (*zero cumulative layout shift* via `.tabular-nums`).
  - Berjalan secara *silent* tanpa memunculkan skeleton loader yang mengganggu pandangan pengguna.
  - **Indikator Visual di Header**:
    - 🟢 *Auto-Refresh Aktif (5m)* dengan animasi kedip (*pulsing ping*) dan jam pembaruan detik realtime.
    - ⚪ *Auto-Refresh Nonaktif* saat dinonaktifkan.
    - Tombol manual refresh (ikon `bi-arrow-clockwise`) untuk pembaruan paksa secara instan.
- **Stok Kategori Chart**: Visualisasi distribusi kuantitas stok per kategori menggunakan Chart.js.
- **Tabel Stok Menipis**: Menampilkan 5 produk dengan stok terendah lengkap dengan ikon kontekstual per jenis barang dan status badge (*Habis* / *Menipis*).
- **Grafik Pergerakan Stok (Movement Chart)**: Grafik pembanding volume barang masuk vs keluar dengan rentang dinamis (7 Hari, 30 Hari, 6 Bulan, 12 Bulan) dan 2 kartu ringkasan total akumulasi periode.
- **Prioritas FIFO Gudang (Warehouse Aging Alert)**: Widget analitik cerdas yang memantau lot kardus yang harus dikeluarkan terlebih dahulu berdasarkan antrean kedatangan terlama (`batch.tanggal_masuk ASC`), menggantikan mekanisme FEFO kadaluarsa generik. Menghitung lama waktu menginap barang di rak fisik (`DATEDIFF(CURDATE(), batch.tanggal_masuk) AS days_in_warehouse`) dan menyajikan label badge dinamis (*X hr di rak*, *Hari ini*) untuk memastikan perputaran inventaris perangkat keras telekomunikasi tetap sehat.
- **Aktivitas Mutasi Terkini (Modern Enterprise Activity Table)**:
  - Transformasi widget mutasi menjadi tabel enterprise terstruktur rapi yang memuat 15 transaksi pergerakan barang terakhir di gudang.
  - **Fitur Scroll Vertikal & Pinned Header (`.table-scroll-container`)**:
    - Kontainer tabel dibatasi ketinggian maksimalnya (`max-height: 400px; overflow-y: auto`) dengan scrollbar ramping bernuansa modern.
    - Header tabel (`thead th`) dikunci melayang (*sticky pinned header*) menggunakan `position: sticky; top: 0; z-index: 5` dengan border-shadow halus sehingga judul kolom tetap terbaca jelas saat melakukan penelusuran data.
  - **Segmented Filter Pills (`.dashboard-filter-pills`)**:
    - Kontainer filter tab instan berbentuk kapsul di kanan toolbar (**Semua (15)**, **Masuk**, **Keluar**) dengan status dot berwarna.
    - Memfilter transaksi di sisi klien secara instan tanpa jeda jaringan atau reload halaman.
  - **Pill Action Button (`.table-toolbar-action`)**:
    - Tombol "Riwayat Lengkap" berlekuk kapsul (`border-radius: 9999px; height: 30px`) yang serasi secara proporsi dengan segmented filter di sebelahnya.
    - Dilengkapi micro-interaction pergeseran ikon panah (`translateX(3px)`) dan aksen crimson saat kursor diarahkan ke tombol.
  - **Kelurusan Kolom Tabular Presisi**:
    - *Waktu*: Tanggal formal, jam transaksi, dan waktu relatif (*"Baru saja"*, *"X mnt lalu"*).
    - *Tipe*: Soft badge arah pergerakan (`IN` hijau / `OUT` merah) dengan panah transaksi.
    - *Produk & SKU*: Nama barang bold truncate dengan chip SKU monospace.
    - *Batch / Lot & Rak*: Chip nomor lot kardus dan tag lokasi rak fisik.
    - *Kuantitas*: Angka bertanda (`+200` hijau / `-50` merah) dengan perataan kanan dan font monospace tabular.
    - *Petugas & Catatan*: Avatar inisial operator lingkaran (`.mini-avatar-initials`), nama petugas, dan catatan keterangan.
  - **Sinkronisasi Polling Background 5 Menit**:
    - Skrip `assets/js/dashboard.js` secara berkala mengambil 15 item mutasi terbaru dan me-render baris tabel yang identik serta mempertahankan status filter aktif.
- **Widget Aksi Cepat**: Akses 1-klik untuk tambah produk, input stok masuk, input stok keluar, dan tambah kategori.

### 6.3 Pengaturan Sistem & Preferensi Modular 5-Tab (`/pengaturan`)
Pusat kendali pengaturan sistem yang direstrukturisasi menjadi arsitektur **5-Tab Enterprise Control Center** berbasis prinsip **Auto-Save on Change** tanpa tombol simpan manual, dilengkapi sakelar bergaya iOS (`.ios-switch`) dan indikator status visual halus (*Auto-save badge*):
1. **Tab 1: Tampilan & Sistem**:
   - *Palet Tema Terpadu*: Pemilihan tema terpusat secara global pada dropdown palet di bilah navigasi header (menyediakan 6 preset visual: Light, Dark, Sepia, Navy, OLED, Forest) dengan preview swatch warna dan indikator aktif, mengeliminasi duplikasi elemen pemilih tema pada tab pengaturan.
   - *Bahasa & Lokalisasi*: Penggantian instan antara Bahasa Indonesia 🇮🇩 dan English (US) 🇺🇸 tanpa reload halaman lewat kamus `assets/js/i18n.js`.
   - *Tampilkan Jumlah Angka Stok*: Toggle penyembunyian angka kuantitas stok fisik dengan masking `••••••` untuk privasi saat presentasi publik.
   - *Interval Auto-Refresh Dashboard*: Konfigurasi jeda polling otomatis (1, 3, 5, 10 menit) dengan toggle on/off.
2. **Tab 2: Gudang & Mutasi**:
   - *Kebijakan Pengeluaran Barang FIFO*: Toggle rekomendasi First-In First-Out yang terintegrasi pada dropdown form mutasi barang keluar untuk memastikan rotasi barang tertua di rak gudang.
   - *Default Ambang Stok Menipis*: Konfigurasi default (5, 10, 20, 50 unit) yang otomatis terisi pada formulir registrasi produk baru.
   - *Standardisasi Prefix Kode*: Format prefix SKU Produk (misal: `PRD-`) dan Lot Batch (misal: `LOT-`).
   - *Pengaturan Ukuran Cetak Label QR*: Pilihan ukuran stiker fisik (*Kecil 30x20mm*, *Sedang 50x30mm*, *Standar Rak 70x40mm*) dan toggle informasi produk pada label.
   - *Format Tampilan Tanggal Transaksi*: Pilihan format tanggal global (`DD/MM/YYYY`, `YYYY-MM-DD`, `DD MMMM YYYY`).
3. **Tab 3: Pemberitahuan & Efek**:
   - *Efek Suara Scanner QR*: Audio beep (880 Hz) berbasis Web Audio API tanpa file eksternal.
   - *Peringatan Stok Kritis & Usia Simpan*: Peringatan dini email/desktop untuk produk di bawah ambang batas minimal dan batch dengan usia simpan lama di rak gudang (7, 14, 30, 60 hari).
   - *Daily Digest Email*: Toggle pengiriman rekap harian aktivitas mutasi ke email administrator di akhir shift kerja.
4. **Tab 4: Profil Perusahaan & Kop Surat**:
   - Formulir identitas resmi instansi: Nama Perusahaan, Sub-judul/Tagline, Alamat Lengkap Kantor/Gudang, Nomor Telepon/WhatsApp, Email Resmi, Nama Penanggung Jawab / PIC Gudang, Jabatan Resmi PIC, dan Catatan Kaki (Footer Note).
   - **Reactive Live Kop Surat Preview (Sisi Kanan)**: Visualisasi mockup kertas nota kop surat dan blok legalitas tanda tangan tiga pihak (*Diserahkan*, *Diterima*, *Kepala Gudang*) yang terupdate secara real-time saat pengguna mengetik formulir.
   - Perubahan langsung tersimpan ke server dan `localStorage` serta disinkronkan ke dokumen cetak Surat Jalan / Bukti Transaksi dan ekspor PDF resmi.
5. **Tab 5: Keamanan & Manajemen Data**:
   - *Batas Waktu Sesi (Session Timeout)*: Pilihan durasi sesi (30m, 60m, 120m, 480m) yang divalidasi server-side di `MY_Controller`.
   - *Two-Factor Authentication (2FA)*: Toggle opsi keamanan ganda berbasis TOTP.
   - *Cadangkan Database 1-Klik (.sql)*: Tombol unduh langsung SQL dump script lengkap dengan struktur tabel dan data untuk restore instan ke MySQL / MariaDB phpMyAdmin.
   - *Ekspor Snapshot JSON*: Unduh backup data lengkap dalam arsip JSON.
   - *Pembersihan Cache Server*: Tombol 1-klik untuk mengosongkan cache aplikasi dan query server tanpa menghapus tabel pengaturan.
   - **Live Server Telemetry Card**: Kartu ringkasan spesifikasi server yang memantau versi PHP runtime, driver database MySQL/MariaDB, sisa ruang penyimpanan harddisk (*Disk Free Space Meter*), dan status operasional sistem secara real-time.

### 6.4 Manajemen Produk
- **CRUD Produk** (`Produk::index/create/store/edit/update/delete`): Nama, SKU unik, deskripsi, pemilihan kategori dan pemasok, stok awal, serta stok minimal, dengan validasi Form Validation CI3.
- **Pop-up Modal QR Code**: Klik "Generate" atau "Lihat QR" langsung menampilkan dialog modal visual gambar QR tanpa wajib unduh terlebih dahulu.
- **Auto-Generate & Unduh QR**: File PNG QR Code dibuat otomatis oleh library `Qr_service` jika belum tersedia (disimpan di `uploads/qr/`) dan dapat diunduh langsung dalam format PNG.
- **Filter & Pencarian Instan**: Pencarian berbasis nama dan SKU secara lokal lewat DataTables tanpa jeda jaringan.

### 6.5 Manajemen Batch & Kardus (Domain Gudang Telkom Akses & Pure FIFO)
- **Karakteristik Domain Hardware & Material Telekomunikasi**: Seluruh material pergudangan PT Telkom Akses (seperti ONT/Modem, STB, kabel Drop Core Fiber Optik, ODP, Roset, Splitter, Joint Closure, hingga alat kerja Splicer) merupakan perangkat keras (*durable equipment*) dan material pasif jaringan yang tidak memiliki masa kedaluwarsa layaknya produk makanan/farmasi.
- **Eliminasi Penuh Atribut Kadaluarsa (Opsi 1)**: Mengeliminasi input `tanggal_kadaluarsa` dari antarmuka formulir registrasi, tabel data, filter, dan proses validasi. Di tingkat backend, field ini di-set `NULL` tanpa memecahkan kompatibilitas skema database eksisting.
- **Formulir Registrasi Ergonomis 3-Kolom (`views/batch/create.php`)**: Ditata ulang menjadi 3 kolom terstruktur: **Jumlah Lot (Unit)**, **Tanggal Masuk (FIFO)**, dan **Lokasi Rak / Pallet**.
- **Status Batch Sederhana & Fungsional**: Menggantikan kalkulasi FEFO kadaluarsa menjadi 3 status operasional riil:
  - `tersedia`: Sisa stok di atas ambang 20% kapasitas awal lot.
  - `hampir_habis`: Sisa stok kritis berada pada atau di bawah 20% kapasitas awal lot.
  - `habis`: Sisa stok habis (0 unit).
- **Alokasi Stok Murni FIFO (First-In, First-Out)**: Seluruh pemrosesan kuantitas barang mendahulukan kardus/lot dengan tanggal kedatangan tertua (`ORDER BY batch.tanggal_masuk ASC, batch.id ASC`).
- **Pelacakan Rak Fisik & Identifikasi Cepat**: Kode lokasi rak fisik penyimpanan (misal: *RAK-A-01*, *Pallet FO*) disajikan transparan di kartu desktop dan mobile view.
- **Generate QR Code Unik per Batch**: Pembuatan stiker QR otomatis untuk kardus wadah, pemindaian cepat via kamera fisik, dan riwayat mutasi per batch.

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

### 6.12 Standarisasi UI/UX Enterprise SaaS & Design System Modern (v2.7.0)
1. **Redesain Modern Sidebar Enterprise SaaS (Quiet Chrome)**:
   - Mengadopsi prinsip navigasi terstruktur Linear / Stripe / Tailwind UI / Vercel design system.
   - Terbagi dalam 4 seksi navigasi semantik: *Pusat Kontrol*, *Operasional Gudang*, *Laporan & Dokumen*, dan *Konfigurasi*.
   - Fitur Quick Jump Command Search (`Ctrl + K` / `⌘K`) dengan input pencarian menu instan.
   - Live Database Status Beacon: Indikator denyut hijau (*pulsing dot* `🟢 Server Online`) di header sidebar yang memantau kesiapan koneksi database.
   - Active state indicator bar merah crimson (3px) dengan fluid transition.
   - User profile pill di kaki sidebar lengkap dengan avatar inisial, role pengguna, status online, serta tombol logout cepat.
2. **Redesain Ambient Split-Screen Login Page**:
   - Layout dua sisi responsif: panel showcase brand gelap dengan ambient radial aura di kiri dan form autentikasi bersih di kanan.
   - Tiga kartu telemetri trust metrics: *99.98% Akurasi Stok*, *Sinkronisasi Database Real-Time*, dan *Standar Keamanan ISO/IEC 27001*.
   - **1-Click Interactive Demo Fill**: Tombol cepat untuk mengisi otomatis kredensial akun demo (`admin@admin.com` & `password`) demi kemudahan pengujian dan demonstrasi instan tanpa pengetikan manual.
   - Input password dengan toggle visibilitas mata (`bi-eye`/`bi-eye-slash`) dan CSRF token terintegrasi.
3. **Standarisasi Seluruh Tabel Data Sistem (Modern Data Tables)**:
   - Menerapkan arsitektur komponen seragam di semua modul data master dan transaksi (`/produk`, `/batch`, `/stok`, `/kategori`, `/pemasok`, `/laporan`):
     - `.table-card`: Elevated card container ber-border hairline `var(--mb-border)` dan subtle elevation.
     - `.table-toolbar`: Header terintegrasi dengan judul semantik, counter pill reaktif, pencarian cepat di sisi klien dengan tombol clear (`bi-x-circle`), dropdown filter, dan tombol aksi utama.
     - `.table-modern`: Tabel borderless dengan subtle alternating row hover dan perataan vertikal presisi (`align-middle`).
     - `.sku-badge`: Monospace SKU chip dengan tombol copy instan dan hairline border.
     - Visual Stock Meters: Progress bar persentase sisa stok dengan pewarnaan semantik dinamis (hijau aman, amber menipis, merah kritis/habis).
     - Soft Semantic Badges (`.badge-soft-success`, `.badge-soft-danger`, `.badge-soft-warning`, `.badge-soft-info`, `.badge-soft-secondary`).
     - QR Code Interactive Thumbnail: Hover zoom preview dengan modal trigger 1-klik dan opsi unduh PNG / cetak label stiker fisik.
4. **Re-arsitektur Pengaturan Sistem Modular 5-Tab**:
   - Tab 1: Tampilan & Sistem (Visual Theme Cards interaktif Light/Dark/System, Instant Language ID/EN, Masking Stok).
   - Tab 2: Gudang & Mutasi (FIFO Recommendation, Ambang Stok Minimal, Prefix SKU/LOT, Ukuran Label Stiker QR).
   - Tab 3: Pemberitahuan & Efek (Web Audio API scanner beep chime, peringatan stok menipis, daily digest).
   - Tab 4: Profil Perusahaan & Kop Surat (Form identitas instansi dengan **Reactive Live Kop Surat Preview** di sisi kanan).
   - Tab 5: Keamanan & Manajemen Data (Session timeout, 2FA, 1-klik unduh backup SQL dump `.sql`, snapshot JSON, clear cache, serta **Live Server Telemetry Card**).
   - Seluruh formulir menggunakan switch bergaya iOS (`.ios-switch`) dan auto-save real-time badge.
5. **Aktivitas Mutasi Terkini dengan Fitur Scroll Vertikal, Sticky Pinned Header, dan Segmented Filter**:
   - Menampilkan 15 transaksi mutasi terbaru dalam kontainer vertikal terproteksi (`max-height: 400px; overflow-y: auto`).
   - Sticky header (`thead th { position: sticky; top: 0; z-index: 5; }`) yang terkunci melayang di atas saat scroll data.
   - Segmented filter pills (**Semua**, **Masuk**, **Keluar**) dengan counter angka dinamis untuk penyaringan instan sisi klien.
   - Pill action button "Riwayat Lengkap" (`.table-toolbar-action`) dengan animated arrow hover interaction (`translateX(3px)`).
   - Avatar inisial operator lingkaran (`.mini-avatar-initials`) untuk audit trail visual yang bersih.
6. **Arsitektur Mobile-First Komprehensif & Native Card Ergonomics (v2.11.0)**:
   - **Docked Mobile Bottom Navigation Bar (`partials/bottom_nav.php`)**:
     - Bilah navigasi tetap (*fixed bottom dock*) pada viewport mobile dan tablet (`< 992px`) dengan `position: fixed; bottom: 0; left: 0; right: 0; z-index: 1030`.
     - 5 tombol aksi berbasis jangkauan jempol (*thumb-zone navigation*): **Ringkasan** (`/dashboard`), **Produk** (`/produk`), **Scan QR** (Center Floating Button dengan elevated brand crimson gradient dan micro-scale `:active` feedback), **Mutasi** (`/stok`), dan **Menu Lain** (`#bottom-nav-menu-btn` yang memicu drawer off-canvas).
     - Dukungan penuh iOS Safe Area melalui `padding-bottom: env(safe-area-inset-bottom, 0px)`.
     - Kompensasi padding dinamis pada container utama (`.app-main { padding-bottom: calc(74px + env(safe-area-inset-bottom, 0px)) !important; }`) pada `< 992px` untuk mencegah tumpang-tindih (*content clipping*) antara tombol aksi bawah dan navigasi dock.
   - **Eliminasi Geser Horizontal Tabel via Mobile Data Cards (`.mobile-data-card`)**:
     - Menghilangkan friksi kelelahan geser horizontal (*horizontal pan fatigue*) pada tabel 5–10 kolom master data di layar ponsel (`< 768px`).
     - Menggunakan pola *Dual-View Responsive*: Tabel enterprise dienkapsulasi dengan class `d-none d-md-block`, sedangkan feed kartu data mobile dirender dengan `d-md-none`.
     - **Kartu Produk (`/produk`)**: Dilengkapi SKU copy chip, badge kategori, thumbnail preview QR code, barometer stok visual dengan progress bar, dan tombol aksi Edit & Hapus ukuran penuh.
     - **Kartu Kategori (`/kategori`)**: Menampilkan nama klasifikasi, badge jumlah produk terkait, deskripsi ringkas, dan tombol aksi cepat.
     - **Kartu Pemasok (`/pemasok`)**: Dilengkapi tombol interaktif 1-klik panggilan langsung telepon vendor (`<a href="tel:..." class="btn btn-sm btn-outline-primary"><i class="bi bi-telephone-fill"></i> Hubungi</a>`), alamat gudang penyuplai, dan tombol edit.
     - **Kartu Batch Kardus (`/batch`)**: Menampilkan lencana status FEFO dinamis (*Tersedia*, *Kritis FEFO*, *Kadaluarsa*), countdown pill kedaluwarsa (`H-X hari` / `EXPIRED`), barometer kapasitas lot sisa, dan tombol 1-klik unduh QR.
     - **Kartu Feed Aktivitas Mutasi (`/stok`)**: Merombak tampilan log mutasi menjadi activity card feed bergaya aplikasi perbankan modern dengan ikon arah transaksi (hijau untuk masuk, merah untuk keluar), nomor referensi lot/SKU, inisial operator, dan stempel waktu tabular.
   - **Grid Metrik KPI 2x2 Kompak di Dashboard**:
     - Mengubah susunan 4 kartu statistik KPI (`col-12` bertumpuk 4 baris yang memboroskan tinggi layar) menjadi grid kompak 2x2 (`col-6 col-xl-3`).
     - Seluruh angka metrik, label, dan persentase tetap terbaca jelas tanpa memerlukan scroll berlebih.
   - **Pencarian Real-Time Dual-View Tersinkronisasi**:
     - Event listener input pencarian di-upgrade untuk memfilter baris tabel desktop (`<tr>`) dan node kartu data mobile (`.mobile-data-card`) secara bersamaan.
     - Status hasil kosong (*empty state banner*) tersinkronisasi otomatis untuk kedua breakpoint.
   - **Touch Target & Stacked Action Footers**:
     - Seluruh form registrasi/edit (`/produk/create`, `/kategori/create`, `/pemasok/create`, `/batch/create`, `/scan`, dll.) mengadopsi container footer tombol responsif bertumpuk vertikal pada mobile (`d-flex flex-column flex-sm-row justify-content-end gap-2`) dengan target sentuh minimal 44px.
   - **Header Bar Ultra-Kompak**:
     - Pada layar ponsel (`< 576px`), label teks panjang disembunyikan secara cerdas sehingga indikator live beroperasi sebagai badge mikro (`● 14:05:22`) tanpa menyebabkan penumpukan baris atau pergeseran layout.
     - Seluruh tombol tindakan cepat (`.btn-icon-quiet`) dioptimalkan dengan target sentuh minimal 34px–40px untuk mencegah salah sentuh (*fat finger protection*).
   - **Sidebar Off-Canvas dengan Backdrop Blur & Auto-Close**:
     - Sidebar meluncur mulus dengan transisi cubic-bezier (`width: 280px; max-width: 85vw; z-index: 1045`).
     - Latar belakang meredup dengan efek *backdrop-filter blur(4px)* dan mengunci scroll body (`overflow: hidden`) saat menu terbuka.
     - Auto-close saat navigasi tautan diklik pada layar mobile serta dukungan penutupan dengan tombol `Escape` dan tombol menu bar bawah.
   - **Segmented Tabs dengan Momentum Touch-Scrolling**:
     - Seluruh tab navigasi domain (`.settings-tabs-container`, `.report-tabs-container`, `.dashboard-filter-pills`) dilengkapi `-webkit-overflow-scrolling: touch; scrollbar-width: none` untuk gesekan jari horizontal yang mulus tanpa scrollbar visual yang mengganggu.
   - **Dashboard Mobile Ergonomics & Viewport Overflow Elimination**:
     - **Pemberantasan Blowout Layar Horizontal**: Mengeliminasi scroll horizontal yang tidak diinginkan dengan menerapkan `overflow-x: hidden; max-width: 100vw;` pada `html, body` serta `min-width: 0; max-width: 100%; overflow-x: hidden;` pada kontainer `.app-wrapper`, `.app-main`, dan `.app-content`.
     - **Centering & Bounding Chart Donut Kategori**: Mengatasi canvas Chart.js yang terdorong ke kanan luar layar ponsel pada widget "Stok Kategori" dengan menyematkan kontainer terpusat berbatas tegas (`max-height: 190px; max-width: 220px; margin: 0 auto; overflow: hidden`) dan pelindung teks legend anti-terpotong (`min-width: 0; max-width: 60%`).
     - **Kontrol Periode Segmented Arus Pergerakan**: Mengonversi pill period filter (`7 Hari`, `30 Hari`, `6 Bulan`, `1 Tahun`) menjadi segmented control responsif penuh (`w-100 w-sm-auto`) dengan badge ringkasan mutasi masuk/keluar yang membungkus rapi (*flex-wrap*).
     - **Dual-View Prioritas Restock**: Tabel 5 kolom desktop dibungkus `d-none d-md-block` dan digantikan oleh feed kartu sentuh `d-md-none` (`#low-stock-mobile-cards`) lengkap dengan indikator stok sisa vs batas minimal dan tombol pintasan 1-klik "Restock" (`/stok/masuk?produk_id=...`).
     - **Dual-View Aktivitas Mutasi Terkini**: Tabel mutasi 6 kolom desktop dipasangkan dengan feed kartu transaksi perbankan seluler (`#recent-activities-mobile-cards`), tersinkronisasi penuh dengan tombol filter segmented (**Semua**, **Masuk**, **Keluar**) dan background AJAX polling secara simultan.
   - **Mobile-Ready Scanner QR**:
     - Viewport kamera pemindai QR (`#qr-reader`) menyesuaikan 100% lebar ponsel gudang dengan tombol konfirmasi stok masuk/keluar tersusun vertikal penuh (*thumb-friendly stacked buttons*).

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
| `GET` | `/api/dashboard/recent-activities` | `api/Dashboard::recent_activities` | 15 riwayat transaksi mutasi terakhir (scrollable & filterable) |

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
| `/laporan` | `Laporan::index` | `laporan/index.php` | Pusat ekspor terpadu, filter multi-domain, dan pratinjau data interaktif |
| `/api/stok/export/excel` | `api/Stok::export_excel` | - | Unduh spreadsheet Excel mutasi transaksi stok terfilter |
| `/api/stok/export/pdf` | `api/Stok::export_pdf` | `stok/export_pdf.php` | Unduh/cetak dokumen PDF resmi mutasi ber-kop surat |
| `/api/produk/export/excel` | `api/Produk::export_excel` | - | Unduh spreadsheet Excel katalog master produk & status stok |
| `/api/batch/export/excel` | `api/Batch::export_excel` | - | Unduh spreadsheet Excel master lot batch dan lokasi rak |

---

## 9. Desain, Estetika, & UI/UX (Enterprise Design System)

Aplikasi mengimplementasikan standar desain **Enterprise SaaS Modern** (mengadopsi filosofi *Linear, Stripe, Tailwind UI, dan Vercel design system*) dengan pendekatan **Quiet Chrome** — antarmuka tenang dan fungsional yang meminimalisasi distraksi visual serta menonjolkan kejelasan data inventaris operasional:

### 9.1 Sistem Token & Palet Warna (`assets/css/theme.css`)
- **Brand Crimson**: Aksen utama bernuansa merah tegas (`#dc2626` / `#b91c1c` / `#ef4444`) yang memberikan identitas kuat pada elemen interaktif, logo aura, dan indikator navigasi aktif.
- **6 Preset Palet Tema Visual Komprehensif**:
  1. **Mode Terang (Clean Slate)**: Latar `#f8fafc` dengan kartu `#ffffff`, border slate lembut, dan aksen crimson korporat gudang siang hari.
  2. **Mode Gelap (Deep Slate)**: Latar `#090d16` dengan kartu graphite `#111827`, border hairline subtle, dan kenyamanan mata di pencahayaan rendah.
  3. **Kertas Hangat (Sepia / Warm Sand)**: Latar `#f6f1e8` bernuansa kertas buku lembut dengan kartu `#fffdf9` dan aksen amber hangat (`#d97706`), dirancang khusus untuk meminimalkan kelelahan mata operator gudang (*low eye-strain*).
  4. **Midnight Navy (Deep Blue Enterprise)**: Latar `#0b1329` dengan kartu `#131f3d`, border maritim modern, dan aksen langit biru (`#38bdf8`) berstandar enterprise.
  5. **OLED Pure Black (High-Contrast)**: Latar hitam murni 100% (`#000000`) dengan kartu `#0c0c0e`, memberikan kontras teks tertinggi dan efisiensi daya baterai maksimal pada smartphone AMOLED/OLED operator gudang.
  6. **Forest Emerald (Sage Green)**: Latar hijau pinus gelap `#071712` dengan kartu `#0d251d` dan aksen mint emerald (`#34d399`) yang sejuk dan menenangkan mata.
- **Soft Semantic Badges**:
  - `.badge-soft-success`: Hijau tenang untuk mutasi Masuk, stok aman, dan rekomendasi FIFO.
  - `.badge-soft-danger`: Merah tenang untuk mutasi Keluar dan stok kritis/habis.
  - `.badge-soft-warning`: Amber untuk stok menipis dan deviasi peringatan.
  - `.badge-soft-info`: Biru lembut untuk informasi SKU, QR, dan batch kardus.
  - `.badge-soft-secondary`: Abu-abu lembut untuk metadata tanggal dan ID.

### 9.2 Tipografi & Perataan Tabular
- **Font Stack**: Menggunakan kombinasi sans-serif sistem modern (Inter, Segoe UI, system-ui) dengan pembagian hierarki ukuran yang disiplin (*sentence-case typography*).
- **Tabular Figures Monospace**: Seluruh angka kuantitas, nilai satuan, tanggal, dan jam menggunakan `font-variant-numeric: tabular-nums` agar sejajar sempurna secara vertikal dari baris ke baris.
- **Kode & SKU Monospace Chip (`.sku-badge`)**: Monospace badge rapi dengan tombol salin instan (*1-click copy to clipboard*) dan feedback toast.

### 9.3 Pustaka Komponen UI Kustom
1. **Container Tabel Terintegrasi (`.table-card` & `.table-toolbar`)**: Menghilangkan kartu generik dan border kaku; menyatukan judul semantik, counter badge dinamis, pencarian lokal dengan tombol reset cepat (`bi-x-circle`), filter, dan tombol aksi dalam satu kesatuan baris yang rapi.
2. **Tabel Modern Borderless (`.table-modern`)**: Baris data dengan perataan vertikal tengah (`align-middle`), hover state halus, dan margin yang pas untuk kepadatan optimal.
3. **Scroll Container dengan Sticky Header (`.table-scroll-container`)**:
   - Membatasi tinggi tabel (`max-height: 400px; overflow-y: auto`) untuk menjaga proporsi vertikal dashboard.
   - Pinned sticky header (`thead th { position: sticky; top: 0; z-index: 5; }`) dengan border-shadow halus sehingga judul kolom tetap melayang terkunci di atas saat data di-scroll.
4. **Segmented Filter Pills (`.dashboard-filter-pills`)**: Kontainer tab filter berbentuk kapsul (`border-radius: 9999px`) dengan status dot dan counter badge untuk penyaringan instan sisi klien tanpa jeda jaringan.
5. **Toolbar Action Pill (`.table-toolbar-action`)**: Tombol aksi berbentuk kapsul dengan tinggi dan warna selaras dengan segmented filter, dilengkapi micro-interaction panah yang bergeser halus (`translateX(3px)`) dan menyala crimson saat di-hover.
6. **Sakelar Bergaya iOS (`.ios-switch`)**: Toggle switch halus pengganti checkbox standar pada menu pengaturan sistem dan preferensi profil.
7. **Barometer Stok Visual (Visual Stock Meters)**: Progress bar indikator stok dengan pewarnaan dinamis untuk memudahkan supervisor gudang mengidentifikasi kondisi stok secara sekilas.
8. **Avatar Inisial Operator (`.mini-avatar-initials`)**: Lingkaran inisial nama petugas 22x22px dengan font bold dan border tipis untuk audit trail visual yang ramah dan konsisten.
9. **Pusat Ekspor & Laporan Terpadu (`.report-tabs-container`, `.report-stat-card`, `.export-format-card`)**:
   - **4 Tab Domain Ekspor Semantik**: Mutasi Transaksi Stok, Master Katalog Produk, Lot Batch & Lokasi Rak Kardus, dan Cadangan Basis Data.
   - **Filter Cepat Rentang Waktu (Quick Date Presets)**: Tombol kapsul preset dinamis (*Hari Ini, 7 Hari Terakhir, Bulan Ini, Bulan Lalu, Tahun Ini, Semua Waktu*) dengan kalkulasi instan sisi klien.
   - **Pratinjau Data Langsung (Live Preview Table)**: Menampilkan 10 baris sampel data terfilter secara reaktif via asynchronous REST API sebelum file diunduh.
   - **Kartu Aksi Format Khusus**: Pemisahan visual tegas untuk format Excel (.xlsx murni XML/HTML), PDF dokumen dinas resmi lanskap A4 dengan kop surat institusi dan legalitas 3 tanda tangan, serta SQL Dump (.sql) 1-klik.
10. **Bilah Navigasi Bawah Docked Mobile (`.mobile-bottom-nav`)**:
   - Navigasi tetap di bawah layar untuk smartphone dan tablet dengan efek backdrop-blur (`backdrop-filter: blur(16px)`), elevasi bayangan halus, dan kompensasi iOS Safe Area (`env(safe-area-inset-bottom)`).
   - Menghadirkan tombol tengah mengambang (Floating Action Button) bergradien crimson untuk pemindaian instan QR Code kamera.
11. **Kartu Data Mobile Adaptif (`.mobile-data-card`)**:
   - Komponen representasi data non-tabel untuk perangkat seluler yang mengintegrasikan status chip, thumbnail QR, meter stok, informasi pemasok/lot, dan tombol aksi tanpa geser horizontal.
12. **Grid Statistik 2x2 Kompak**:
   - Pengaturan kartu metrik KPI menggunakan `col-6 col-xl-3` pada perangkat mobile untuk menghemat ruang vertikal dan memberikan visibilitas metrik seketika.
13. **Dropdown Palet Tema Terpadu (`.theme-palette-menu`, `.theme-dropdown-option`, `.theme-swatch`)**:
   - Komponen pemilih tema terpusat pada header dan form login dengan preview swatch lingkaran warna, label deskriptif, dan indikator centang aktif (`.theme-check-icon`). Dibatasi `max-width: calc(100vw - 24px)` untuk mencegah overflow di layar mobile.
14. **Garis Aksen Kartu Berkelengkungan Halus (`.export-format-card::before`)**:
   - Strip aksen format file (Excel hijau, PDF merah, Database ungu) berbasis pseudo-element absolut dengan `overflow: hidden` pada container sehingga mengikuti kurva rounded corner tanpa artefak sudut tajam.

### 9.4 Reaktivitas, Multi-Palet Tema, & Aksesibilitas
- **Arsitektur Multi-Palet 6 Tema Bebas Kedip (Zero-FOIT)**: Injeksi early inline synchronous script di `<head>` pada `layouts/main.php` dan `auth/login.php` yang memverifikasi `localStorage` terhadap whitelist valid `['light', 'dark', 'sepia', 'navy', 'oled', 'forest']` sebelum DOM dirender, mencegah efek kedipan putih (flash of incorrect theme).
- **Adaptasi Dinamis Chart.js Multi-Tema**: Pengiriman custom event `theme-changed` yang direspons oleh `assets/js/dashboard.js` untuk mengkalibrasi warna sumbu label, garis grid, tooltip background, dan border donat kategori secara instan tanpa reload halaman atau merusak dataset.
- **Kontras WCAG AA**: Rasio kontras teks terhadap latar belakang diuji memenuhi standar minimal 4.5:1 untuk keterbacaan optimal di lingkungan pencahayaan gudang pada seluruh 6 varian tema.
- **Reaktivitas Tanpa Refresh**: Pergantian bahasa (ID/EN) dan preferensi tema diterapkan seketika ke DOM lewat JavaScript tanpa reload browser.
- **Silent Background Syncing**: Auto-refresh 5 menit berjalan di latar belakang tanpa skeleton loader yang mengganggu pandangan pengguna.
- **Micro-Interactions & Audio Feedback**:
  - SweetAlert2 untuk modal konfirmasi destruktif dan notifikasi toast sukses.
  - Web Audio API dual-tone chime (880 Hz / 659.25Hz & 987.77Hz) tanpa dependensi aset file suara eksternal.
  - Pulsing beacon dot hijau untuk indikator live database connection di sidebar.
  - Hover zoom thumbnail QR Code dengan dialog pratinjau instan.

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

*Dokumen ini diperbarui untuk mencerminkan rilis Manajemen Barang v2.12.0 (CI3 Enterprise SaaS UI/UX Edition — Unified Multi-Palette 6-Theme Architecture & Pure FIFO Stock Alignment) — November 2026.*
