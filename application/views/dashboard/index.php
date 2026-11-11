<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Page Header / Context -->
<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary); letter-spacing: -0.01em;">
            Dashboard Gudang & Inventaris
        </h2>
        <p class="text-muted small mb-0">
            Ikhtisar status persediaan, arus barang real-time, dan peringatan dini batch operasional.
        </p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="<?= site_url('stok/masuk'); ?>" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold">
            <i class="bi bi-arrow-down-left"></i> Stok Masuk
        </a>
        <a href="<?= site_url('stok/keluar'); ?>" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold">
            <i class="bi bi-arrow-up-right"></i> Stok Keluar
        </a>
        <a href="<?= site_url('scan'); ?>" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold text-white shadow-sm" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
            <i class="bi bi-qr-code-scan"></i> Scan QR
        </a>
    </div>
</div>

<!-- 1. Four Key Performance Indicator Stat Cards (2x2 Grid on Mobile, 4 Cols on Desktop) -->
<div class="row g-2 g-md-3 g-lg-4 mb-4">
    <!-- Stat 1: Total Produk -->
    <div class="col-6 col-xl-3">
        <div class="mb-card stat-card">
            <div>
                <div class="stat-card-top">
                    <div>
                        <div class="stat-label">Total Produk</div>
                        <div class="stat-value" id="stat-total-produk" data-stock-value="<?= number_format($stats['total_produk']); ?>">
                            <?= number_format($stats['total_produk']); ?>
                        </div>
                    </div>
                    <div class="stat-icon primary">
                        <i class="bi bi-box"></i>
                    </div>
                </div>
            </div>
            <div class="stat-footer">
                <span>Terdaftar di gudang</span>
                <span class="stat-badge neutral tabular-nums"><?= $stats['total_kategori']; ?> Kategori</span>
            </div>
        </div>
    </div>

    <!-- Stat 2: Total Stok Unit -->
    <div class="col-6 col-xl-3">
        <div class="mb-card stat-card">
            <div>
                <div class="stat-card-top">
                    <div>
                        <div class="stat-label">Total Stok Fisik</div>
                        <div class="stat-value" id="stat-total-stok" data-stock-value="<?= number_format($stats['total_stok']); ?>">
                            <?= number_format($stats['total_stok']); ?>
                        </div>
                    </div>
                    <div class="stat-icon success">
                        <i class="bi bi-layers"></i>
                    </div>
                </div>
            </div>
            <div class="stat-footer">
                <span>Unit siap sedia</span>
                <span class="stat-badge safe tabular-nums"><?= $stats['total_batch']; ?> Batch Lot</span>
            </div>
        </div>
    </div>

    <!-- Stat 3: Stok Menipis -->
    <div class="col-6 col-xl-3">
        <div class="mb-card stat-card">
            <div>
                <div class="stat-card-top">
                    <div>
                        <div class="stat-label">Stok Menipis</div>
                        <div class="stat-value <?= $stats['produk_stok_menipis'] > 0 ? 'text-warning' : ''; ?>" id="stat-stok-menipis" data-stock-value="<?= number_format($stats['produk_stok_menipis']); ?>">
                            <?= number_format($stats['produk_stok_menipis']); ?>
                        </div>
                    </div>
                    <div class="stat-icon warning">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            <div class="stat-footer">
                <span>Batas minimum</span>
                <span class="stat-badge <?= $stats['produk_stok_menipis'] > 0 ? 'alert' : 'safe'; ?>">
                    <?= $stats['produk_stok_menipis'] > 0 ? 'Perlu Restock' : 'Aman'; ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Stat 4: Stok Habis -->
    <div class="col-6 col-xl-3">
        <div class="mb-card stat-card">
            <div>
                <div class="stat-card-top">
                    <div>
                        <div class="stat-label">Stok Habis (0)</div>
                        <div class="stat-value <?= $stats['produk_stok_habis'] > 0 ? 'text-danger' : ''; ?>" id="stat-stok-habis" data-stock-value="<?= number_format($stats['produk_stok_habis']); ?>">
                            <?= number_format($stats['produk_stok_habis']); ?>
                        </div>
                    </div>
                    <div class="stat-icon danger">
                        <i class="bi bi-x-octagon"></i>
                    </div>
                </div>
            </div>
            <div class="stat-footer">
                <span>Kekosongan unit</span>
                <span class="stat-badge <?= $stats['produk_stok_habis'] > 0 ? 'alert' : 'safe'; ?>">
                    <?= $stats['produk_stok_habis'] > 0 ? 'Kritis' : 'Nol Masalah'; ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- 2. Charts Row: Stock Movement & Category Breakdown -->
<div class="row g-3 g-lg-4 mb-4">
    <!-- Main Movement Chart (8 cols) -->
    <div class="col-12 col-xl-8">
        <div class="mb-card h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="mb-card-header flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2.5">
                    <div>
                        <h3 class="mb-card-title">
                            <i class="bi bi-bar-chart-steps text-danger"></i> Arus Pergerakan Barang
                        </h3>
                        <p class="mb-card-subtitle">Volume perbandingan mutasi barang masuk vs barang keluar</p>
                    </div>

                    <!-- Dynamic Period Tabs -->
                    <div class="period-tabs w-100 w-sm-auto justify-content-between justify-content-sm-start">
                        <button type="button" class="period-tab flex-fill flex-sm-grow-0 text-center active" data-period="7d">7 Hari</button>
                        <button type="button" class="period-tab flex-fill flex-sm-grow-0 text-center" data-period="30d">30 Hari</button>
                        <button type="button" class="period-tab flex-fill flex-sm-grow-0 text-center" data-period="6m">6 Bulan</button>
                        <button type="button" class="period-tab flex-fill flex-sm-grow-0 text-center" data-period="12m">1 Tahun</button>
                    </div>
                </div>

                <!-- Summary Badges in Selected Period -->
                <div class="d-flex flex-wrap align-items-center gap-2 gap-sm-3 mb-3 pb-3 border-bottom border-subtle">
                    <div class="d-flex align-items-center gap-1.5 gap-sm-2">
                        <span class="rounded-circle bg-success" style="width: 8px; height: 8px;"></span>
                        <span class="text-muted small">Total Masuk Periode Ini:</span>
                        <span id="period-total-in" class="tabular-nums fw-bold text-success">
                            <?= number_format($movement_data['total_in'] ?? 0); ?>
                        </span>
                        <span class="text-muted small">Unit</span>
                    </div>
                    <span class="text-muted opacity-25 d-none d-sm-inline">|</span>
                    <div class="d-flex align-items-center gap-1.5 gap-sm-2">
                        <span class="rounded-circle bg-danger" style="width: 8px; height: 8px;"></span>
                        <span class="text-muted small">Total Keluar Periode Ini:</span>
                        <span id="period-total-out" class="tabular-nums fw-bold text-danger">
                            <?= number_format($movement_data['total_out'] ?? 0); ?>
                        </span>
                        <span class="text-muted small">Unit</span>
                    </div>
                </div>

                <!-- Canvas Chart -->
                <div style="height: 280px; position: relative; max-width: 100%;">
                    <canvas id="movementChartCanvas"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Distribution Doughnut Chart (4 cols) -->
    <div class="col-12 col-xl-4">
        <div class="mb-card h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="mb-card-header">
                    <div>
                        <h3 class="mb-card-title">
                            <i class="bi bi-pie-chart text-primary"></i> Stok Kategori
                        </h3>
                        <p class="mb-card-subtitle">Distribusi unit barang per kategori</p>
                    </div>
                    <a href="<?= site_url('kategori'); ?>" class="btn btn-sm btn-link text-muted text-decoration-none p-0" title="Kelola Kategori">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <!-- Donut Canvas Centered & Constrained -->
                <div class="d-flex justify-content-center align-items-center my-2" style="height: 190px; position: relative; max-width: 100%; overflow: hidden;">
                    <canvas id="kategoriChartCanvas" style="max-height: 190px; max-width: 220px;"></canvas>
                </div>

                <!-- Custom Clean Legend List -->
                <div class="mt-3 pt-2 border-top border-subtle" style="max-height: 140px; overflow-y: auto;">
                    <?php
                    $colors = ['#dc2626', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4', '#ec4899', '#64748b'];
                    $totalCategoryUnits = array_sum(array_column($category_distribution, 'total_stok'));
                    ?>
                    <?php if (!empty($category_distribution)): ?>
                        <?php foreach ($category_distribution as $idx => $cat): ?>
                            <?php
                            $stok = (int) ($cat['total_stok'] ?? 0);
                            $pct = $totalCategoryUnits > 0 ? round(($stok / $totalCategoryUnits) * 100, 1) : 0;
                            $dotColor = $colors[$idx % count($colors)];
                            ?>
                            <div class="d-flex align-items-center justify-content-between py-1.5 text-muted small">
                                <div class="d-flex align-items-center gap-2 text-truncate" style="min-width: 0; max-width: 60%;">
                                    <span class="rounded-circle flex-shrink-0" style="width: 8px; height: 8px; background-color: <?= $dotColor; ?>;"></span>
                                    <span class="text-truncate" style="color: var(--mb-text-primary);"><?= html_escape($cat['nama_kategori']); ?></span>
                                </div>
                                <div class="tabular-nums font-monospace flex-shrink-0 text-end" style="font-size: 0.76rem;">
                                    <strong><?= number_format($stok); ?></strong> unit (<?= $pct; ?>%)
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-3 text-muted small">Belum ada data kategori.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. Operational Grid: Low Stock Table, FEFO Early Warning, & Quick Actions -->
<div class="row g-3 g-lg-4 mb-4">
    <!-- Low Stock Table (5 cols) -->
    <div class="col-12 col-lg-5">
        <div class="mb-card h-100">
            <div class="mb-card-header">
                <div>
                    <h3 class="mb-card-title">
                        <i class="bi bi-shield-exclamation text-warning"></i> Prioritas Restock
                    </h3>
                    <p class="mb-card-subtitle">Produk dengan sisa stok di bawah batas minimal</p>
                </div>
                <a href="<?= site_url('produk'); ?>" class="btn btn-sm btn-link text-danger text-decoration-none p-0 fw-semibold" style="font-size: 0.8rem;">
                    Lihat Semua <i class="bi bi-chevron-right"></i>
                </a>
            </div>

            <!-- Desktop Table (Visible >= 768px) -->
            <div class="table-responsive d-none d-md-block">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 36px;">No</th>
                            <th>Produk & SKU</th>
                            <th class="text-end" style="width: 90px;">Stok</th>
                            <th class="text-end" style="width: 75px;">Min</th>
                            <th class="text-center" style="width: 95px;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="low-stock-tbody">
                        <?php if (!empty($low_stock_products)): ?>
                            <?php foreach ($low_stock_products as $idx => $p): ?>
                                <?php
                                $stok = (int) $p['stok'];
                                $minStok = (int) $p['stok_minimal'];
                                $isHabis = $stok <= 0;
                                ?>
                                <tr>
                                    <td class="text-muted text-center tabular-nums fw-semibold"><?= $idx + 1; ?></td>
                                    <td>
                                        <div class="fw-semibold text-truncate" style="max-width: 170px; color: var(--mb-text-primary); font-size: 0.85rem;"><?= html_escape($p['nama_produk']); ?></div>
                                        <span class="sku-badge mt-0.5" style="font-size: 0.68rem; padding: 1px 5px;">
                                            <?= html_escape($p['sku'] ?? '-'); ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold tabular-nums font-monospace <?= $isHabis ? 'text-danger' : 'text-warning'; ?>">
                                            <?= number_format($p['stok']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end text-muted tabular-nums font-monospace" style="font-size: 0.8rem;"><?= number_format($p['stok_minimal']); ?></td>
                                    <td class="text-center">
                                        <?php if ($isHabis): ?>
                                            <span class="badge-soft badge-soft-danger" style="font-size: 0.68rem; padding: 2px 7px;">
                                                <span class="badge-dot bg-danger"></span> Habis
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-soft badge-soft-warning" style="font-size: 0.68rem; padding: 2px 7px;">
                                                <span class="badge-dot bg-warning"></span> Menipis
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-check-circle text-success me-1"></i> Semua stok produk dalam batas aman.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card Feed (Visible < 768px) -->
            <div class="d-md-none p-2 p-sm-3 d-flex flex-column gap-2" id="low-stock-mobile-cards">
                <?php if (!empty($low_stock_products)): ?>
                    <?php foreach ($low_stock_products as $p): ?>
                        <?php
                        $stok = (int) $p['stok'];
                        $minStok = (int) $p['stok_minimal'];
                        $isHabis = $stok <= 0;
                        ?>
                        <div class="mobile-data-card p-3 mb-0">
                            <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                <div style="min-width: 0;">
                                    <div class="fw-bold text-truncate" style="color: var(--mb-text-primary); font-size: 0.88rem;">
                                        <?= html_escape($p['nama_produk']); ?>
                                    </div>
                                    <span class="sku-badge mt-1" style="font-size: 0.68rem; padding: 1px 6px;">
                                        <i class="bi bi-upc-scan me-0.5"></i> <?= html_escape($p['sku'] ?? '-'); ?>
                                    </span>
                                </div>
                                <span class="badge-soft <?= $isHabis ? 'badge-soft-danger' : 'badge-soft-warning'; ?> flex-shrink-0">
                                    <span class="badge-dot <?= $isHabis ? 'bg-danger' : 'bg-warning'; ?>"></span> <?= $isHabis ? 'Habis' : 'Menipis'; ?>
                                </span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-subtle">
                                <div class="small text-muted">
                                    Sisa: <strong class="tabular-nums font-monospace <?= $isHabis ? 'text-danger' : 'text-warning'; ?>"><?= number_format($p['stok']); ?></strong>
                                    <span class="opacity-50">/ min <?= number_format($p['stok_minimal']); ?></span>
                                </div>
                                <a href="<?= site_url('stok/masuk?produk_id=' . $p['id']); ?>" class="btn btn-sm btn-outline-success py-1 px-2.5 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.74rem;">
                                    <i class="bi bi-arrow-down-left"></i> Restock
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-check-circle text-success me-1"></i> Semua stok produk dalam batas aman.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- FIFO Priority Recommendation (4 cols) -->
    <div class="col-12 col-lg-4">
        <div class="mb-card h-100">
            <div class="mb-card-header">
                <div>
                    <h3 class="mb-card-title">
                        <i class="bi bi-clock-history text-danger"></i> Prioritas FIFO
                    </h3>
                    <p class="mb-card-subtitle">First In, First Out (Antrean Keluar Tertua)</p>
                </div>
                <a href="<?= site_url('batch'); ?>" class="btn btn-sm btn-link text-muted text-decoration-none p-0" title="Kelola Lot Batch">
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div id="expiring-batches-container">
                <?php
                $fifoItems = !empty($fifo_batches) ? $fifo_batches : (!empty($expiring_batches) ? $expiring_batches : []);
                ?>
                <?php if (!empty($fifoItems)): ?>
                    <?php foreach ($fifoItems as $b): ?>
                        <?php
                        $days = (int) ($b['days_in_warehouse'] ?? 0);
                        ?>
                        <div class="p-2.5 mb-2 rounded border border-subtle d-flex align-items-center justify-content-between" style="background-color: var(--mb-card-raised);">
                            <div>
                                <div class="fw-bold small text-truncate" style="max-width: 170px;" title="<?= html_escape($b['nama_produk']); ?>"><?= html_escape($b['nama_produk']); ?></div>
                                <div class="text-muted small tabular-nums font-monospace">
                                    <?= html_escape($b['nomor_batch']); ?> · Rak: <?= html_escape($b['lokasi_rak'] ?? '-'); ?>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge-soft badge-soft-danger font-monospace" style="font-size: 0.7rem; padding: 2px 7px;">
                                    <?= $days > 0 ? "{$days} hr di rak" : 'Hari ini'; ?>
                                </span>
                                <div class="text-muted small tabular-nums mt-0.5"><?= number_format($b['stok_saat_ini']); ?> unit</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-shield-check text-success fs-3 d-block mb-1"></i>
                        <span class="small">Belum ada antrean batch aktif.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions (3 cols) -->
    <div class="col-12 col-lg-3">
        <div class="mb-card h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="mb-card-header">
                    <div>
                        <h3 class="mb-card-title">
                            <i class="bi bi-lightning-charge text-danger"></i> Aksi Cepat
                        </h3>
                        <p class="mb-card-subtitle">Pintasan operasional 1-klik</p>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2.5">
                    <a href="<?= site_url('produk/create'); ?>" class="quick-action-btn">
                        <div class="quick-action-icon text-danger" style="background-color: var(--mb-brand-subtle);">
                            <i class="bi bi-plus-lg"></i>
                        </div>
                        <div>
                            <div>Tambah Produk</div>
                            <div class="text-muted" style="font-size: 0.7rem; font-weight: normal;">Registrasi SKU barang baru</div>
                        </div>
                    </a>

                    <a href="<?= site_url('stok/masuk'); ?>" class="quick-action-btn">
                        <div class="quick-action-icon text-success" style="background-color: var(--mb-success-subtle);">
                            <i class="bi bi-arrow-down-left"></i>
                        </div>
                        <div>
                            <div>Input Stok Masuk</div>
                            <div class="text-muted" style="font-size: 0.7rem; font-weight: normal;">Penerimaan PO / Supplier</div>
                        </div>
                    </a>

                    <a href="<?= site_url('stok/keluar'); ?>" class="quick-action-btn">
                        <div class="quick-action-icon text-danger" style="background-color: var(--mb-danger-subtle);">
                            <i class="bi bi-arrow-up-right"></i>
                        </div>
                        <div>
                            <div>Input Stok Keluar</div>
                            <div class="text-muted" style="font-size: 0.7rem; font-weight: normal;">Pengeluaran rekomendasi FIFO</div>
                        </div>
                    </a>

                    <a href="<?= site_url('kategori/create'); ?>" class="quick-action-btn">
                        <div class="quick-action-icon text-primary" style="background-color: var(--mb-info-subtle);">
                            <i class="bi bi-tag"></i>
                        </div>
                        <div>
                            <div>Tambah Kategori</div>
                            <div class="text-muted" style="font-size: 0.7rem; font-weight: normal;">Klasifikasi barang baru</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 4. Recent Stock Activity Log -->
<div class="table-card mb-4">
    <div class="table-toolbar flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2.5">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h3 class="mb-card-title mb-0" style="font-size: 0.95rem;">
                    <i class="bi bi-clock-history text-danger me-1.5"></i> Aktivitas Mutasi Terkini
                </h3>
                <span class="badge-soft badge-soft-secondary d-none d-sm-inline-flex align-items-center gap-1" style="font-size: 0.68rem; padding: 2px 7px;" title="Tabel ini dilengkapi fitur scroll vertikal">
                    <i class="bi bi-arrows-vertical text-muted"></i> Scrollable
                </span>
            </div>
            <p class="text-muted small mb-0" style="font-size: 0.75rem;">
                Catatan 15 transaksi pergerakan barang terakhir di gudang (scroll untuk melihat lebih banyak)
            </p>
        </div>

        <div class="d-flex align-items-center justify-content-between justify-content-sm-end gap-2 w-100 w-sm-auto flex-wrap">
            <!-- Segmented Filter Pills -->
            <div class="dashboard-filter-pills flex-grow-1 flex-sm-grow-0" id="activity-type-filter">
                <button type="button" class="dashboard-filter-btn active" data-filter="all">
                    <span>Semua</span>
                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-1.5 py-0.5" style="font-size: 0.65rem;" id="filter-count-all"><?= count($recent_activities ?? []); ?></span>
                </button>
                <button type="button" class="dashboard-filter-btn" data-filter="masuk">
                    <span class="badge-dot bg-success"></span>
                    <span>Masuk</span>
                </button>
                <button type="button" class="dashboard-filter-btn" data-filter="keluar">
                    <span class="badge-dot bg-danger"></span>
                    <span>Keluar</span>
                </button>
            </div>

            <a href="<?= site_url('stok'); ?>" class="table-toolbar-action flex-shrink-0" title="Lihat riwayat mutasi stok lengkap">
                <span>Riwayat Lengkap</span>
                <i class="bi bi-arrow-right action-arrow"></i>
            </a>
        </div>
    </div>

    <!-- Desktop Table View (>= 768px) -->
    <div class="table-responsive table-scroll-container d-none d-md-block">
        <table class="table-modern" id="recent-activities-table">
            <thead>
                <tr>
                    <th style="width: 140px;">Waktu</th>
                    <th class="text-center" style="width: 110px;">Tipe</th>
                    <th>Produk &amp; SKU</th>
                    <th style="width: 160px;">Batch / Lot</th>
                    <th class="text-end" style="width: 120px;">Kuantitas</th>
                    <th style="width: 180px;">Petugas &amp; Catatan</th>
                </tr>
            </thead>
            <tbody id="recent-activities-tbody">
                <?php if (!empty($recent_activities)): ?>
                    <?php foreach ($recent_activities as $act): ?>
                        <?php
                        $isMasuk = $act['tipe'] === 'masuk';
                        $timeObj = strtotime($act['tanggal']);
                        $timeDiff = time() - $timeObj;
                        if ($timeDiff < 60) {
                            $relativeTime = 'Baru saja';
                        } elseif ($timeDiff < 3600) {
                            $relativeTime = floor($timeDiff / 60) . ' mnt lalu';
                        } elseif ($timeDiff < 86400) {
                            $relativeTime = floor($timeDiff / 3600) . ' jam lalu';
                        } else {
                            $relativeTime = date('d M Y', $timeObj);
                        }

                        $userName = !empty($act['user_name']) ? $act['user_name'] : 'Petugas';
                        $words = explode(' ', trim($userName));
                        $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                        ?>
                        <tr class="activity-row" data-type="<?= $act['tipe']; ?>">
                            <td>
                                <div class="fw-semibold tabular-nums" style="font-size: 0.82rem; color: var(--mb-text-primary);">
                                    <?= date('d M Y', $timeObj); ?>
                                </div>
                                <div class="text-muted tabular-nums d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                    <i class="bi bi-clock"></i> <?= date('H:i', $timeObj); ?>
                                    <span class="text-muted opacity-75">· <?= $relativeTime; ?></span>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php if ($isMasuk): ?>
                                    <span class="badge-soft badge-soft-success">
                                        <span class="badge-dot bg-success"></span>
                                        <i class="bi bi-arrow-down-left"></i> Masuk
                                    </span>
                                <?php else: ?>
                                    <span class="badge-soft badge-soft-danger">
                                        <span class="badge-dot bg-danger"></span>
                                        <i class="bi bi-arrow-up-right"></i> Keluar
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-semibold text-truncate" style="max-width: 260px; color: var(--mb-text-primary); font-size: 0.85rem;" title="<?= html_escape($act['nama_produk'] ?? 'Produk'); ?>">
                                    <?= html_escape($act['nama_produk'] ?? 'Produk'); ?>
                                </div>
                                <div class="mt-0.5">
                                    <span class="sku-badge" style="font-size: 0.68rem; padding: 1px 6px;">
                                        <i class="bi bi-upc-scan text-muted" style="font-size: 0.65rem;"></i>
                                        <?= html_escape($act['sku'] ?? '-'); ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($act['nomor_batch'])): ?>
                                    <div class="sku-badge font-monospace" style="font-size: 0.72rem; padding: 2px 7px;">
                                        <i class="bi bi-box-seam text-muted" style="font-size: 0.68rem;"></i>
                                        <?= html_escape($act['nomor_batch']); ?>
                                    </div>
                                    <?php if (!empty($act['lokasi_rak'])): ?>
                                        <div class="text-muted mt-0.5 d-flex align-items-center gap-1" style="font-size: 0.68rem;">
                                            <i class="bi bi-geo-alt"></i> Rak: <?= html_escape($act['lokasi_rak']); ?>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted" style="font-size: 0.75rem;">Non-Batch</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <span class="fw-bold tabular-nums font-monospace <?= $isMasuk ? 'text-success' : 'text-danger'; ?>" style="font-size: 0.92rem;">
                                    <?= $isMasuk ? '+' : '-'; ?><?= number_format($act['jumlah']); ?>
                                </span>
                                <span class="text-muted small" style="font-size: 0.72rem;">unit</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1.5 mb-0.5">
                                    <div class="mini-avatar-initials" title="<?= html_escape($userName); ?>">
                                        <?= html_escape($initials); ?>
                                    </div>
                                    <span class="fw-semibold text-truncate small" style="max-width: 130px; font-size: 0.8rem; color: var(--mb-text-primary);">
                                        <?= html_escape($userName); ?>
                                    </span>
                                </div>
                                <div class="text-muted text-truncate" style="max-width: 160px; font-size: 0.72rem;" title="<?= html_escape($act['catatan'] ?? ''); ?>">
                                    <?= !empty($act['catatan']) ? html_escape($act['catatan']) : '<span class="opacity-50">Tanpa catatan</span>'; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="empty-activity-row">
                        <td colspan="6">
                            <div class="table-empty-state py-4">
                                <div class="table-empty-icon" style="width: 40px; height: 40px; font-size: 1.1rem;">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <h6 class="fw-bold mb-1" style="color: var(--mb-text-primary); font-size: 0.88rem;">Belum Ada Mutasi</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.75rem;">Belum ada aktivitas transaksi mutasi barang tercatat.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                <tr id="filter-empty-row" class="d-none">
                    <td colspan="6">
                        <div class="table-empty-state py-4">
                            <div class="table-empty-icon" style="width: 40px; height: 40px; font-size: 1.1rem;">
                                <i class="bi bi-funnel"></i>
                            </div>
                            <h6 class="fw-bold mb-1" style="color: var(--mb-text-primary); font-size: 0.88rem;">Tidak Ditemukan</h6>
                            <p class="text-muted small mb-0" style="font-size: 0.75rem;">Tidak ada transaksi mutasi sesuai filter ini pada 15 aktivitas terakhir.</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Feed Activity Cards (< 768px) -->
    <div class="d-md-none p-2 p-sm-3 d-flex flex-column gap-2" id="recent-activities-mobile-cards">
        <?php if (!empty($recent_activities)): ?>
            <?php foreach ($recent_activities as $act): ?>
                <?php
                $isMasuk = $act['tipe'] === 'masuk';
                $timeObj = strtotime($act['tanggal']);
                $timeDiff = time() - $timeObj;
                if ($timeDiff < 60) {
                    $relativeTime = 'Baru saja';
                } elseif ($timeDiff < 3600) {
                    $relativeTime = floor($timeDiff / 60) . ' mnt lalu';
                } elseif ($timeDiff < 86400) {
                    $relativeTime = floor($timeDiff / 3600) . ' jam lalu';
                } else {
                    $relativeTime = date('d M Y', $timeObj);
                }

                $userName = !empty($act['user_name']) ? $act['user_name'] : 'Petugas';
                $iconClass = $isMasuk ? 'in' : 'out';
                $icon = $isMasuk ? 'bi-arrow-down-left' : 'bi-arrow-up-right';
                $sign = $isMasuk ? '+' : '-';
                $qtyClass = $isMasuk ? 'in' : 'out';
                ?>
                <div class="mobile-mutation-card activity-card-item" data-type="<?= $act['tipe']; ?>">
                    <div class="mobile-mutation-icon <?= $iconClass; ?>">
                        <i class="bi <?= $icon; ?>"></i>
                    </div>
                    <div class="mobile-mutation-details">
                        <div class="mobile-mutation-prod"><?= html_escape($act['nama_produk'] ?? 'Produk'); ?></div>
                        <div class="mobile-mutation-meta">
                            <span class="badge-soft badge-soft-secondary font-monospace" style="font-size: 0.68rem;"><?= html_escape($act['sku'] ?? '-'); ?></span>
                            <?php if (!empty($act['nomor_batch'])): ?>
                                <span class="badge-soft badge-soft-warning font-monospace" style="font-size: 0.68rem;">Lot: <?= html_escape($act['nomor_batch']); ?></span>
                            <?php endif; ?>
                            <span>· <?= html_escape($userName); ?></span>
                        </div>
                        <?php if (!empty($act['catatan'])): ?>
                            <div class="text-muted small mt-1 text-truncate" style="font-size: 0.72rem;">
                                <i class="bi bi-chat-left-text me-1"></i><?= html_escape($act['catatan']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mobile-mutation-qty">
                        <div class="mobile-qty-pill <?= $qtyClass; ?>"><?= $sign . number_format($act['jumlah']); ?></div>
                        <div class="mobile-mutation-time tabular-nums text-muted mt-0.5" style="font-size: 0.68rem;">
                            <?= date('H:i', $timeObj); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="table-empty-state py-4 text-center">
                <div class="table-empty-icon mb-2">
                    <i class="bi bi-clock-history fs-2 text-muted"></i>
                </div>
                <h6 class="fw-bold mb-1" style="color: var(--mb-text-primary);">Belum Ada Mutasi</h6>
                <p class="text-muted small mb-0">Belum ada aktivitas transaksi mutasi barang tercatat.</p>
            </div>
        <?php endif; ?>
        <div id="filter-empty-mobile" class="d-none table-empty-state py-4 text-center">
            <div class="table-empty-icon mb-2">
                <i class="bi bi-funnel fs-2 text-muted"></i>
            </div>
            <h6 class="fw-bold mb-1" style="color: var(--mb-text-primary);">Tidak Ditemukan</h6>
            <p class="text-muted small mb-0">Tidak ada transaksi mutasi sesuai filter ini pada 15 aktivitas terakhir.</p>
        </div>
    </div>
</div>

<!-- Inline Configuration for Dashboard JS -->
<script>
    window.__INITIAL_MOVEMENT_DATA__ = <?= json_encode($movement_data); ?>;
    window.__INITIAL_CATEGORY_DATA__ = <?= json_encode($category_distribution); ?>;
    window.__AUTO_REFRESH_INTERVAL__ = <?= (int) ($settings['refresh_interval'] ?? 5); ?>;
    window.__AUTO_REFRESH_ENABLED__ = <?= ($settings['auto_refresh'] ?? '1') == '1' ? 'true' : 'false'; ?>;
</script>
<script src="<?= base_url('assets/js/dashboard.js'); ?>"></script>
