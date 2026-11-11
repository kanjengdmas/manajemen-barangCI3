<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Page Header -->
<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h2 class="h4 fw-bold mb-0" style="color: var(--mb-text-primary); letter-spacing: -0.01em;">
                <i class="bi bi-file-earmark-bar-graph text-danger me-2"></i>Pusat Ekspor & Laporan Inventaris
            </h2>
            <span class="badge-soft badge-soft-danger px-2.5 py-0.5 rounded-pill" style="font-size: 0.7rem; font-weight: 600;">
                Enterprise Reporting
            </span>
        </div>
        <p class="text-muted small mb-0">
            Generate, pratinjau data interaktif, dan ekspor laporan mutasi, master katalog produk, lot kardus, serta arsip data sistem.
        </p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="<?= site_url('stok'); ?>" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold flex-fill flex-sm-grow-0 justify-content-center" style="font-size: 0.78rem;">
            <i class="bi bi-clock-history"></i>
            <span>Log Mutasi</span>
        </a>
        <a href="<?= site_url('pengaturan'); ?>" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold flex-fill flex-sm-grow-0 justify-content-center" style="font-size: 0.78rem;">
            <i class="bi bi-building"></i>
            <span>Profil Kop Surat</span>
        </a>
    </div>
</div>

<!-- Top Telemetry Row -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="report-stat-card">
            <div class="report-stat-icon bg-primary-subtle text-primary">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <div>
                <div class="text-muted small" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em;">
                    Total Mutasi Tercatat
                </div>
                <div class="h4 fw-bold mb-0" style="color: var(--mb-text-primary); font-variant-numeric: tabular-nums;">
                    <?= number_format($stats['total_transactions'] ?? 0); ?>
                </div>
                <div class="text-muted" style="font-size: 0.72rem; margin-top: 2px;">
                    <span class="text-success fw-semibold"><i class="bi bi-arrow-down-left"></i> Masuk: <?= number_format($stats['total_masuk'] ?? 0); ?></span>
                    <span class="mx-1 text-secondary">•</span>
                    <span class="text-danger fw-semibold"><i class="bi bi-arrow-up-right"></i> Keluar: <?= number_format($stats['total_keluar'] ?? 0); ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="report-stat-card">
            <div class="report-stat-icon bg-success-subtle text-success">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <div class="text-muted small" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em;">
                    Katalog Master Produk
                </div>
                <div class="h4 fw-bold mb-0" style="color: var(--mb-text-primary); font-variant-numeric: tabular-nums;">
                    <?= number_format($stats['total_products'] ?? 0); ?> <span class="fs-6 fw-normal text-muted">Item</span>
                </div>
                <div class="text-muted" style="font-size: 0.72rem; margin-top: 2px;">
                    Akumulasi Fisik: <strong class="text-primary fw-semibold"><?= number_format($stats['total_stock'] ?? 0); ?> Unit</strong> di Gudang
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="report-stat-card">
            <div class="report-stat-icon bg-info-subtle text-info">
                <i class="bi bi-layers"></i>
            </div>
            <div>
                <div class="text-muted small" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em;">
                    Lot Batch &amp; Lokasi Rak
                </div>
                <div class="h4 fw-bold mb-0" style="color: var(--mb-text-primary); font-variant-numeric: tabular-nums;">
                    <?= number_format($stats['total_batches'] ?? 0); ?> <span class="fs-6 fw-normal text-muted">Batch</span>
                </div>
                <div class="text-muted" style="font-size: 0.72rem; margin-top: 2px;">
                    Pelacakan tanggal masuk (FIFO) dan penempatan rak fisik
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Quick Tab Selector (Smartphones & Tablets < 768px) -->
<div class="d-md-none mb-3">
    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.68rem; letter-spacing: 0.05em;">
        <i class="bi bi-segmented-nav me-1 text-danger"></i> Kategori Laporan &amp; Data:
    </label>
    <div class="input-group shadow-sm">
        <span class="input-group-text border-subtle text-danger" style="background-color: var(--mb-card-raised); border-color: var(--mb-border);">
            <i class="bi bi-file-earmark-bar-graph"></i>
        </span>
        <select class="form-select fw-semibold" id="mobile-report-tab-dropdown" style="background-color: var(--mb-card); border-color: var(--mb-border); color: var(--mb-text-primary); font-size: 0.85rem;">
            <option value="tab-mutasi" selected>🔄 Laporan Mutasi Stok</option>
            <option value="tab-produk">📦 Katalog Master Produk</option>
            <option value="tab-batch">🏷️ Lot Batch &amp; Lokasi Rak</option>
            <option value="tab-backup">💾 Cadangan Basis Data</option>
        </select>
    </div>
</div>

<!-- Report Navigation Tabs -->
<div class="report-tabs-container mb-4">
    <button type="button" class="report-tab-btn active" data-tab="tab-mutasi">
        <i class="bi bi-clock-history"></i> Laporan Mutasi Stok
    </button>
    <button type="button" class="report-tab-btn" data-tab="tab-produk">
        <i class="bi bi-boxes"></i> Katalog Master Produk
    </button>
    <button type="button" class="report-tab-btn" data-tab="tab-batch">
        <i class="bi bi-layers"></i> Lot Batch &amp; Lokasi Rak
    </button>
    <button type="button" class="report-tab-btn" data-tab="tab-backup">
        <i class="bi bi-database-down"></i> Cadangan Basis Data
    </button>
</div>

<!-- ========================================================================= -->
<!-- TAB 1: Laporan Mutasi Stok (Transactions)                                 -->
<!-- ========================================================================= -->
<div class="report-tab-content active" id="tab-mutasi">
    <div class="row g-4 mb-4">
        <!-- Filter Card -->
        <div class="col-12 col-xl-7">
            <div class="mb-card h-100 p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-secondary-subtle">
                    <div>
                        <h5 class="fw-bold mb-0" style="color: var(--mb-text-primary); font-size: 1rem;">
                            <i class="bi bi-funnel text-danger me-1.5"></i> Kriteria Filter Mutasi Stok
                        </h5>
                        <p class="text-muted small mb-0" style="font-size: 0.76rem;">
                            Tentukan parameter rentang tanggal, jenis mutasi, dan item spesifik yang ingin dilaporkan.
                        </p>
                    </div>
                    <button type="button" class="btn btn-sm btn-link text-decoration-none text-muted p-0" id="btn-reset-filter" title="Reset ke Default">
                        <i class="bi bi-arrow-counterclockwise"></i> <span style="font-size: 0.75rem;">Reset</span>
                    </button>
                </div>

                <!-- Quick Date Presets -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small mb-1.5 text-secondary" style="font-size: 0.75rem;">
                        Pilihan Cepat Rentang Waktu:
                    </label>
                    <div class="report-date-presets" id="date-range-presets">
                        <button type="button" class="report-preset-pill" data-range="today">Hari Ini</button>
                        <button type="button" class="report-preset-pill" data-range="7d">7 Hari Terakhir</button>
                        <button type="button" class="report-preset-pill active" data-range="month">Bulan Ini</button>
                        <button type="button" class="report-preset-pill" data-range="last_month">Bulan Lalu</button>
                        <button type="button" class="report-preset-pill" data-range="year">Tahun Ini</button>
                        <button type="button" class="report-preset-pill" data-range="all">Semua Waktu</button>
                    </div>
                </div>

                <form id="report-filter-form" onsubmit="return false;">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-semibold small text-secondary">Tanggal Mulai</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-body-tertiary border-end-0"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" name="start_date" id="rep-start-date" class="form-control" value="<?= date('Y-m-01'); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-semibold small text-secondary">Tanggal Selesai</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-body-tertiary border-end-0"><i class="bi bi-calendar-check"></i></span>
                                <input type="date" name="end_date" id="rep-end-date" class="form-control" value="<?= date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-semibold small text-secondary">Spesifik Produk</label>
                            <select name="produk_id" id="rep-product" class="form-select form-select-sm">
                                <option value="">-- Seluruh Katalog Produk --</option>
                                <?php foreach ($products as $p): ?>
                                    <option value="<?= $p['id']; ?>">
                                        <?= html_escape($p['nama_produk']); ?> (<?= html_escape($p['sku']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-semibold small text-secondary">Arah Pergerakan Mutasi</label>
                            <select name="tipe" id="rep-type" class="form-select form-select-sm">
                                <option value="">-- Semua Mutasi (Masuk &amp; Keluar) --</option>
                                <option value="masuk">Hanya Stok Masuk (IN)</option>
                                <option value="keluar">Hanya Stok Keluar (OUT)</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Format Cards Sisi Kanan -->
        <div class="col-12 col-xl-5">
            <div class="d-flex flex-column gap-3 h-100">
                <!-- Card Excel -->
                <div class="export-format-card format-excel">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="export-format-icon bg-success-subtle text-success">
                                <i class="bi bi-file-earmark-excel"></i>
                            </div>
                            <span class="badge-soft badge-soft-success px-2.5 py-0.5 rounded-pill" style="font-size: 0.68rem; font-weight: 600;">
                                Spreadsheet Native
                            </span>
                        </div>
                        <h4 class="export-format-title">Ekspor Excel (.xlsx / .xls)</h4>
                        <p class="export-format-desc">
                            Format tabel resmi terstruktur murni XML/HTML tanpa ketergantungan Composer. Kompatibel penuh dengan Microsoft Excel, WPS Office, dan Google Sheets.
                        </p>
                        <div class="export-format-meta">
                            <span class="report-live-badge"><i class="bi bi-table"></i> 9 Kolom Standar</span>
                            <span class="report-live-badge"><i class="bi bi-lightning-charge"></i> Siap Olah Data</span>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success d-inline-flex align-items-center justify-content-center gap-2 fw-semibold w-100 py-2" style="font-size: 0.82rem;" onclick="exportMutasi('excel')">
                        <i class="bi bi-download"></i>
                        <span>Unduh File Excel Mutasi</span>
                    </button>
                </div>

                <!-- Card PDF -->
                <div class="export-format-card format-pdf">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="export-format-icon bg-danger-subtle text-danger">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </div>
                            <span class="badge-soft badge-soft-danger px-2.5 py-0.5 rounded-pill" style="font-size: 0.68rem; font-weight: 600;">
                                Siap Cetak A4
                            </span>
                        </div>
                        <h4 class="export-format-title">Cetak Dokumen PDF Resmi</h4>
                        <p class="export-format-desc">
                            Dokumen resmi berformat A4 Lanskap lengkap dengan kop surat badan usaha dinamis dan blok legalitas tanda tangan 3 pihak (Pengirim, Petugas, Kepala Gudang).
                        </p>
                        <div class="export-format-meta">
                            <span class="report-live-badge"><i class="bi bi-printer"></i> A4 Lanskap</span>
                            <span class="report-live-badge"><i class="bi bi-pen"></i> Tanda Tangan 3 Pihak</span>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger d-inline-flex align-items-center justify-content-center gap-2 fw-semibold w-100 py-2" style="background-color: var(--mb-brand); border-color: var(--mb-brand); font-size: 0.82rem;" onclick="exportMutasi('pdf')">
                        <i class="bi bi-printer"></i>
                        <span>Buka &amp; Cetak PDF Resmi</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Preview Table Card -->
    <div class="table-card mb-4">
        <div class="table-toolbar flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="mb-card-title mb-0" style="font-size: 0.95rem;">
                            <i class="bi bi-eye text-primary me-1.5"></i> Pratinjau Sampel Data Terfilter
                        </h3>
                        <span class="badge-soft badge-soft-secondary" id="preview-badge-status" style="font-size: 0.68rem; padding: 2px 7px;">
                            Memuat data...
                        </span>
                    </div>
                    <p class="text-muted small mb-0" style="font-size: 0.75rem;">
                        Pratinjau langsung 10 baris pertama yang sesuai kriteria filter di atas sebelum proses ekspor dijalankan.
                    </p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 ms-auto">
                <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold" style="font-size: 0.76rem;" onclick="fetchPreviewData()">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span>Segarkan Preview</span>
                </button>
            </div>
        </div>

        <div class="table-responsive table-scroll-container" style="max-height: 340px;">
            <table class="table-modern" id="report-preview-table">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th style="width: 140px;">Waktu Transaksi</th>
                        <th class="text-center" style="width: 100px;">Arah</th>
                        <th>Nama Produk &amp; SKU</th>
                        <th style="width: 140px;">Nomor Lot / Batch</th>
                        <th class="text-end" style="width: 110px;">Jumlah</th>
                        <th style="width: 150px;">Petugas</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody id="report-preview-tbody">
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <div class="spinner-border spinner-border-sm text-secondary me-2" role="status"></div>
                            <span>Menghubungi server dan menyiapkan data pratinjau...</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- TAB 2: Katalog Master Produk & Valuasi                                    -->
<!-- ========================================================================= -->
<div class="report-tab-content" id="tab-produk">
    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="mb-card p-4 h-100">
                <h5 class="fw-bold mb-1" style="color: var(--mb-text-primary); font-size: 1rem;">
                    <i class="bi bi-box-seam text-success me-1.5"></i> Parameter Katalog Master Produk
                </h5>
                <p class="text-muted small mb-3" style="font-size: 0.76rem;">
                    Ekspor seluruh daftar inventaris barang aktif, nomor kode SKU, kategori, pemasok, posisi stok fisik, dan status batas minimum.
                </p>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-8">
                        <label class="form-label fw-semibold small text-secondary">Filter Berdasarkan Kategori</label>
                        <select id="prod-rep-category" class="form-select form-select-sm">
                            <option value="">-- Seluruh Kategori Barang (Semua) --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id']; ?>"><?= html_escape($cat['nama_kategori']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="p-3 rounded mb-4" style="background-color: var(--mb-card-raised); border: 1px solid var(--mb-border);">
                    <div class="fw-semibold small mb-2" style="color: var(--mb-text-primary);">
                        <i class="bi bi-info-circle text-primary me-1"></i> Informasi Kolom Ekspor Produk:
                    </div>
                    <ul class="text-muted small mb-0 ps-3" style="font-size: 0.76rem; line-height: 1.6;">
                        <li><strong>Nama Produk &amp; SKU:</strong> Identifikasi unik barang master.</li>
                        <li><strong>Kategori &amp; Pemasok:</strong> Klasifikasi jenis dan vendor asal penyedia.</li>
                        <li><strong>Stok Fisik &amp; Stok Minimal:</strong> Kuantitas unit aktual saat diekspor.</li>
                        <li><strong>Status Otomatis:</strong> Label kondisi stok (<em>Aman</em>, <em>Menipis</em>, atau <em>Habis/Kosong</em>).</li>
                    </ul>
                </div>

                <button type="button" class="btn btn-success d-inline-flex align-items-center gap-2 px-4 py-2 fw-semibold" style="font-size: 0.82rem;" onclick="exportProduk()">
                    <i class="bi bi-file-earmark-excel"></i>
                    <span>Unduh Katalog Master Produk (.xlsx)</span>
                </button>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="mb-card p-4 h-100">
                <h5 class="fw-bold mb-3" style="color: var(--mb-text-primary); font-size: 1rem;">
                    <i class="bi bi-check2-circle text-success me-1.5"></i> Standar Ekspor Produk
                </h5>
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="p-2 rounded bg-success-subtle text-success">
                        <i class="bi bi-file-earmark-spreadsheet fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-semibold small">Audit Stok Siap Pakai</div>
                        <div class="text-muted small" style="font-size: 0.75rem;">File spreadsheet langsung dapat dicetak atau digunakan tim gudang untuk *stock opname* berkala.</div>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="p-2 rounded bg-primary-subtle text-primary">
                        <i class="bi bi-sort-alpha-down fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-semibold small">Urutan Alfabet A-Z</div>
                        <div class="text-muted small" style="font-size: 0.75rem;">Data otomatis terurut rapi memudahkan pencarian fisik di rak gudang.</div>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3">
                    <div class="p-2 rounded bg-danger-subtle text-danger">
                        <i class="bi bi-exclamation-triangle fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-semibold small">Deteksi Stok Menipis Instan</div>
                        <div class="text-muted small" style="font-size: 0.75rem;">Kolom status memberikan indikasi jelas untuk barang yang perlu segera di-restock.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- TAB 3: Lot Batch & Lokasi Rak                                             -->
<!-- ========================================================================= -->
<div class="report-tab-content" id="tab-batch">
    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="mb-card p-4 h-100">
                <h5 class="fw-bold mb-1" style="color: var(--mb-text-primary); font-size: 1rem;">
                    <i class="bi bi-layers text-info me-1.5"></i> Laporan Lot Kardus &amp; Lokasi Rak
                </h5>
                <p class="text-muted small mb-3" style="font-size: 0.76rem;">
                    Ekspor pelacakan lot/batch kardus spesifik, kapasitas wadah, sisa unit, tanggal kedatangan (FIFO), dan kode lokasi rak penyimpanan.
                </p>

                <div class="p-3 rounded mb-4" style="background-color: var(--mb-card-raised); border: 1px solid var(--mb-border);">
                    <div class="fw-semibold small mb-2" style="color: var(--mb-text-primary);">
                        <i class="bi bi-info-circle text-info me-1"></i> Rincian Data Pelacakan Lot:
                    </div>
                    <ul class="text-muted small mb-0 ps-3" style="font-size: 0.76rem; line-height: 1.6;">
                        <li><strong>Nomor Batch / Lot:</strong> Nomor identifikasi kardus/lot produk.</li>
                        <li><strong>Kapasitas &amp; Sisa Unit:</strong> Akurasi kuantitas yang tersisa di dalam wadah kardus.</li>
                        <li><strong>Lokasi Rak Fisik:</strong> Penempatan rak penyimpanan fisik (misal: <em>RAK-A-01</em>).</li>
                        <li><strong>Antrean Kedatangan FIFO:</strong> Pemantauan tanggal masuk untuk rotasi pengeluaran barang First In, First Out.</li>
                    </ul>
                </div>

                <button type="button" class="btn btn-success d-inline-flex align-items-center gap-2 px-4 py-2 fw-semibold" style="font-size: 0.82rem;" onclick="exportBatch()">
                    <i class="bi bi-file-earmark-excel"></i>
                    <span>Unduh Laporan Batch &amp; Lokasi Rak (.xlsx)</span>
                </button>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="mb-card p-4 h-100">
                <h5 class="fw-bold mb-3" style="color: var(--mb-text-primary); font-size: 1rem;">
                    <i class="bi bi-shield-check text-info me-1.5"></i> Keunggulan Pelacakan Batch
                </h5>
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="p-2 rounded bg-info-subtle text-info">
                        <i class="bi bi-qr-code-scan fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-semibold small">Konektivitas QR Code Kardus</div>
                        <div class="text-muted small" style="font-size: 0.75rem;">Setiap nomor lot batch memiliki QR unik yang dapat dicetak menjadi stiker fisik melalui tabel master Batch.</div>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="p-2 rounded bg-danger-subtle text-danger">
                        <i class="bi bi-clock-history fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-semibold small">Prioritas Pengeluaran FIFO</div>
                        <div class="text-muted small" style="font-size: 0.75rem;">Mendukung analisa lot kardus yang masuk paling awal untuk dikeluarkan terlebih dahulu agar perputaran barang optimal.</div>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3">
                    <div class="p-2 rounded bg-secondary-subtle text-secondary">
                        <i class="bi bi-geo-alt fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-semibold small">Manajemen Ruang Rak</div>
                        <div class="text-muted small" style="font-size: 0.75rem;">Mengetahui secara persis lokasi baris rak untuk mempercepat proses picking operator.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- TAB 4: Cadangan Basis Data (System Archives)                              -->
<!-- ========================================================================= -->
<div class="report-tab-content" id="tab-backup">
    <div class="row g-4">
        <!-- Card Backup SQL -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="export-format-card format-database h-100">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="export-format-icon bg-primary-subtle text-primary">
                            <i class="bi bi-database-down"></i>
                        </div>
                        <span class="badge-soft badge-soft-primary px-2.5 py-0.5 rounded-pill" style="font-size: 0.68rem; font-weight: 600;">
                            MySQL / MariaDB
                        </span>
                    </div>
                    <h4 class="export-format-title">Cadangkan Database (.sql)</h4>
                    <p class="export-format-desc">
                        Ekspor seluruh struktur tabel (`CREATE TABLE`) dan data (`INSERT INTO`) dalam berkas SQL dump 1-klik, siap di-restore ke phpMyAdmin XAMPP.
                    </p>
                    <div class="export-format-meta">
                        <span class="report-live-badge"><i class="bi bi-file-code"></i> Format .sql murni</span>
                        <span class="report-live-badge"><i class="bi bi-shield-check"></i> Siap Restore</span>
                    </div>
                </div>
                <a href="<?= site_url('api/settings/backup-sql'); ?>" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2 fw-semibold w-100 py-2" style="font-size: 0.82rem;">
                    <i class="bi bi-download"></i>
                    <span>Unduh Cadangan SQL Script</span>
                </a>
            </div>
        </div>

        <!-- Card Snapshot JSON -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="export-format-card h-100" style="border-top: 3px solid #0284c7;">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="export-format-icon bg-info-subtle text-info">
                            <i class="bi bi-filetype-json"></i>
                        </div>
                        <span class="badge-soft badge-soft-info px-2.5 py-0.5 rounded-pill" style="font-size: 0.68rem; font-weight: 600;">
                            JSON Snapshot
                        </span>
                    </div>
                    <h4 class="export-format-title">Snapshot Data Aplikasi (.json)</h4>
                    <p class="export-format-desc">
                        Arsip objek terstruktur yang memuat seluruh entitas master produk, batch kardus, kategori, pemasok, dan catatan riwayat mutasi stok.
                    </p>
                    <div class="export-format-meta">
                        <span class="report-live-badge"><i class="bi bi-code-slash"></i> Format JSON</span>
                        <span class="report-live-badge"><i class="bi bi-archive"></i> Arsip Interoperabilitas</span>
                    </div>
                </div>
                <a href="<?= site_url('api/settings/backup'); ?>" class="btn btn-outline-info d-inline-flex align-items-center justify-content-center gap-2 fw-semibold w-100 py-2" style="font-size: 0.82rem;">
                    <i class="bi bi-download"></i>
                    <span>Unduh Snapshot JSON</span>
                </a>
            </div>
        </div>

        <!-- Card Pembersihan Cache -->
        <div class="col-12 col-md-12 col-xl-4">
            <div class="export-format-card h-100" style="border-top: 3px solid #eab308;">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="export-format-icon bg-warning-subtle text-warning">
                            <i class="bi bi-trash3"></i>
                        </div>
                        <span class="badge-soft badge-soft-warning px-2.5 py-0.5 rounded-pill" style="font-size: 0.68rem; font-weight: 600;">
                            Server Maintenance
                        </span>
                    </div>
                    <h4 class="export-format-title">Pembersihan Cache Server</h4>
                    <p class="export-format-desc">
                        Kosongkan seluruh cache query dan cache aplikasi di server untuk menyegarkan performa tanpa menghapus preferensi atau data inventaris.
                    </p>
                    <div class="export-format-meta">
                        <span class="report-live-badge"><i class="bi bi-speedometer2"></i> Percepat Respon</span>
                        <span class="report-live-badge"><i class="bi bi-safe"></i> Data Aman</span>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-warning d-inline-flex align-items-center justify-content-center gap-2 fw-semibold w-100 py-2 text-dark" style="font-size: 0.82rem;" onclick="clearServerCache()">
                    <i class="bi bi-stars"></i>
                    <span>Kosongkan Cache Server</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Logic -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    initReportTabs();
    initDatePresets();
    initFilterChangeListeners();
    fetchPreviewData();
});

// 1. Tab Switching (Mobile Dropdown + Horizontal Swipe Bar)
function initReportTabs() {
    const tabs = document.querySelectorAll('.report-tab-btn');
    const contents = document.querySelectorAll('.report-tab-content');
    const mobileDropdown = document.getElementById('mobile-report-tab-dropdown');

    function switchReportTab(target) {
        tabs.forEach(t => {
            const isMatch = t.dataset.tab === target;
            t.classList.toggle('active', isMatch);
            if (isMatch) {
                t.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            }
        });

        contents.forEach(c => {
            if (c.id === target) {
                c.classList.remove('d-none');
                c.classList.add('active');
            } else {
                c.classList.add('d-none');
                c.classList.remove('active');
            }
        });

        if (mobileDropdown && mobileDropdown.value !== target) {
            mobileDropdown.value = target;
        }

        if (window.history && window.history.replaceState) {
            window.history.replaceState(null, null, '#' + target);
        }
    }

    tabs.forEach(btn => {
        btn.addEventListener('click', function() {
            switchReportTab(this.dataset.tab);
        });
    });

    if (mobileDropdown) {
        mobileDropdown.addEventListener('change', function() {
            switchReportTab(this.value);
        });
    }

    const hash = window.location.hash.replace('#', '');
    if (hash && document.getElementById(hash)) {
        switchReportTab(hash);
    }
}

// 2. Quick Date Presets
function initDatePresets() {
    const presetPills = document.querySelectorAll('.report-preset-pill');
    const startInput = document.getElementById('rep-start-date');
    const endInput = document.getElementById('rep-end-date');

    presetPills.forEach(pill => {
        pill.addEventListener('click', function() {
            presetPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');

            const range = this.dataset.range;
            const now = new Date();

            let sDate = '';
            let eDate = formatDateYMD(now);

            if (range === 'today') {
                sDate = formatDateYMD(now);
                eDate = formatDateYMD(now);
            } else if (range === '7d') {
                const past7 = new Date();
                past7.setDate(now.getDate() - 6);
                sDate = formatDateYMD(past7);
            } else if (range === 'month') {
                const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
                sDate = formatDateYMD(firstDay);
            } else if (range === 'last_month') {
                const firstDayLastMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                const lastDayLastMonth = new Date(now.getFullYear(), now.getMonth(), 0);
                sDate = formatDateYMD(firstDayLastMonth);
                eDate = formatDateYMD(lastDayLastMonth);
            } else if (range === 'year') {
                const firstDayYear = new Date(now.getFullYear(), 0, 1);
                sDate = formatDateYMD(firstDayYear);
            } else if (range === 'all') {
                sDate = '';
                eDate = '';
            }

            startInput.value = sDate;
            endInput.value = eDate;
            fetchPreviewData();
        });
    });

    // Reset button
    const btnReset = document.getElementById('btn-reset-filter');
    if (btnReset) {
        btnReset.addEventListener('click', function() {
            document.getElementById('rep-product').value = '';
            document.getElementById('rep-type').value = '';
            const now = new Date();
            startInput.value = formatDateYMD(new Date(now.getFullYear(), now.getMonth(), 1));
            endInput.value = formatDateYMD(now);

            presetPills.forEach(p => p.classList.remove('active'));
            const defaultMonth = document.querySelector('.report-preset-pill[data-range="month"]');
            if (defaultMonth) defaultMonth.classList.add('active');

            fetchPreviewData();
        });
    }
}

function formatDateYMD(d) {
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// 3. Filter Change Listeners
function initFilterChangeListeners() {
    const inputs = ['rep-start-date', 'rep-end-date', 'rep-product', 'rep-type'];
    inputs.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('change', function() {
                // Remove preset active if dates edited manually
                if (id === 'rep-start-date' || id === 'rep-end-date') {
                    document.querySelectorAll('.report-preset-pill').forEach(p => p.classList.remove('active'));
                }
                fetchPreviewData();
            });
        }
    });
}

// 4. Live Preview Fetcher
let previewDebounceTimer = null;
function fetchPreviewData() {
    clearTimeout(previewDebounceTimer);
    previewDebounceTimer = setTimeout(() => {
        const startDate = document.getElementById('rep-start-date').value;
        const endDate = document.getElementById('rep-end-date').value;
        const prodId = document.getElementById('rep-product').value;
        const type = document.getElementById('rep-type').value;

        const tbody = document.getElementById('report-preview-tbody');
        const badge = document.getElementById('preview-badge-status');

        if (badge) badge.textContent = 'Memperbarui preview...';

        const params = new URLSearchParams();
        if (startDate) params.append('start_date', startDate);
        if (endDate) params.append('end_date', endDate);
        if (prodId) params.append('produk_id', prodId);
        if (type) params.append('tipe', type);
        params.append('limit', '10');

        fetch(window.BASE_URL + 'api/stok/history?' + params.toString())
            .then(res => res.json())
            .then(res => {
                if (!res.status || !res.data || res.data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-4 d-block mb-1 text-secondary opacity-50"></i>
                                Tidak ada data mutasi yang cocok dengan kriteria filter di atas.
                            </td>
                        </tr>
                    `;
                    if (badge) badge.textContent = '0 Data Ditemukan';
                    return;
                }

                if (badge) badge.textContent = `Menampilkan ${res.data.length} Sampel Teratas`;

                let html = '';
                res.data.forEach((item, index) => {
                    const isMasuk = item.tipe === 'masuk';
                    const badgeClass = isMasuk ? 'badge-soft-success' : 'badge-soft-danger';
                    const sign = isMasuk ? '+' : '-';
                    const textClass = isMasuk ? 'text-success' : 'text-danger';
                    const iconArrow = isMasuk ? 'bi-arrow-down-left' : 'bi-arrow-up-right';

                    html += `
                        <tr>
                            <td class="text-center text-muted small">${index + 1}</td>
                            <td>
                                <div class="small fw-semibold text-nowrap" style="color: var(--mb-text-primary);">
                                    ${item.tanggal_formatted || item.tanggal}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge-soft ${badgeClass} d-inline-flex align-items-center gap-1" style="font-size: 0.68rem; padding: 2px 7px;">
                                    <i class="bi ${iconArrow}"></i> ${item.tipe.toUpperCase()}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-truncate" style="max-width: 240px; color: var(--mb-text-primary);" title="${escapeHtml(item.nama_produk || '-')}">
                                    ${escapeHtml(item.nama_produk || '-')}
                                </div>
                                <span class="sku-badge" style="font-size: 0.65rem;">
                                    ${escapeHtml(item.sku || '-')}
                                </span>
                            </td>
                            <td>
                                <span class="badge-soft badge-soft-secondary" style="font-size: 0.7rem;">
                                    ${escapeHtml(item.nomor_batch || '-')}
                                </span>
                            </td>
                            <td class="text-end">
                                <span class="fw-bold ${textClass}" style="font-variant-numeric: tabular-nums;">
                                    ${sign}${Number(item.jumlah).toLocaleString()}
                                </span>
                            </td>
                            <td>
                                <span class="small text-muted">${escapeHtml(item.user_name || '-')}</span>
                            </td>
                            <td>
                                <span class="small text-muted text-truncate d-inline-block" style="max-width: 200px;" title="${escapeHtml(item.catatan || '-')}">
                                    ${escapeHtml(item.catatan || '-')}
                                </span>
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            })
            .catch(err => {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4 text-danger">
                            <i class="bi bi-exclamation-triangle fs-4 d-block mb-1"></i>
                            Gagal memuat pratinjau data. Pastikan koneksi server aktif.
                        </td>
                    </tr>
                `;
                if (badge) badge.textContent = 'Gagal Memuat';
            });
    }, 250);
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// 5. Export Actions
function exportMutasi(format) {
    const startDate = document.getElementById('rep-start-date').value;
    const endDate = document.getElementById('rep-end-date').value;
    const prodId = document.getElementById('rep-product').value;
    const type = document.getElementById('rep-type').value;

    let baseUrl = window.BASE_URL + 'api/stok/export/' + format + '?';
    const params = new URLSearchParams();
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    if (prodId) params.append('produk_id', prodId);
    if (type) params.append('tipe', type);

    window.open(baseUrl + params.toString(), '_blank');
}

function exportProduk() {
    const catId = document.getElementById('prod-rep-category').value;
    let baseUrl = window.BASE_URL + 'api/produk/export/excel?';
    const params = new URLSearchParams();
    if (catId) params.append('kategori_id', catId);
    window.open(baseUrl + params.toString(), '_blank');
}

function exportBatch() {
    window.open(window.BASE_URL + 'api/batch/export/excel', '_blank');
}

function clearServerCache() {
    Swal.fire({
        title: 'Kosongkan Cache Server?',
        text: 'Cache query dan cache aplikasi di server akan dihapus untuk menyegarkan data. Pengaturan dan basis data inventaris tetap aman.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#eab308',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Kosongkan Cache',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append(window.CSRF_TOKEN_NAME, window.CSRF_HASH);

            fetch(window.BASE_URL + 'api/settings/clear-cache', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if (res.status) {
                    Swal.fire({
                        title: 'Cache Dibersihkan!',
                        text: res.message || 'Cache aplikasi berhasil disegarkan.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Gagal!', res.message || 'Gagal membersihkan cache server.', 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error!', 'Terjadi kesalahan jaringan saat mengosongkan cache.', 'error');
            });
        }
    });
}
</script>
