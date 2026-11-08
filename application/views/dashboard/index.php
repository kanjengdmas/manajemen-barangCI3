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
    <div class="d-flex align-items-center gap-2">
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

<!-- 1. Four Key Performance Indicator Stat Cards -->
<div class="row g-3 g-lg-4 mb-4">
    <!-- Stat 1: Total Produk -->
    <div class="col-12 col-sm-6 col-xl-3">
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
    <div class="col-12 col-sm-6 col-xl-3">
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
    <div class="col-12 col-sm-6 col-xl-3">
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
    <div class="col-12 col-sm-6 col-xl-3">
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
                <div class="mb-card-header flex-wrap gap-2">
                    <div>
                        <h3 class="mb-card-title">
                            <i class="bi bi-bar-chart-steps text-danger"></i> Arus Pergerakan Barang
                        </h3>
                        <p class="mb-card-subtitle">Volume perbandingan mutasi barang masuk vs barang keluar</p>
                    </div>

                    <!-- Dynamic Period Tabs -->
                    <div class="period-tabs">
                        <button type="button" class="period-tab active" data-period="7d">7 Hari</button>
                        <button type="button" class="period-tab" data-period="30d">30 Hari</button>
                        <button type="button" class="period-tab" data-period="6m">6 Bulan</button>
                        <button type="button" class="period-tab" data-period="12m">1 Tahun</button>
                    </div>
                </div>

                <!-- Summary Badges in Selected Period -->
                <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom border-subtle">
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle bg-success" style="width: 8px; height: 8px;"></span>
                        <span class="text-muted small">Total Masuk Periode Ini:</span>
                        <span id="period-total-in" class="tabular-nums fw-bold text-success">
                            <?= number_format($movement_data['total_in'] ?? 0); ?>
                        </span>
                        <span class="text-muted small">Unit</span>
                    </div>
                    <span class="text-muted opacity-25">|</span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle bg-danger" style="width: 8px; height: 8px;"></span>
                        <span class="text-muted small">Total Keluar Periode Ini:</span>
                        <span id="period-total-out" class="tabular-nums fw-bold text-danger">
                            <?= number_format($movement_data['total_out'] ?? 0); ?>
                        </span>
                        <span class="text-muted small">Unit</span>
                    </div>
                </div>

                <!-- Canvas Chart -->
                <div style="height: 300px; position: relative;">
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

                <!-- Donut Canvas -->
                <div style="height: 200px; position: relative;" class="my-2">
                    <canvas id="kategoriChartCanvas"></canvas>
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
                                <div class="d-flex align-items-center gap-2 text-truncate" style="max-width: 170px;">
                                    <span class="rounded-circle flex-shrink-0" style="width: 8px; height: 8px; background-color: <?= $dotColor; ?>;"></span>
                                    <span class="text-truncate" style="color: var(--mb-text-primary);"><?= html_escape($cat['nama_kategori']); ?></span>
                                </div>
                                <div class="tabular-nums font-monospace" style="font-size: 0.76rem;">
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

            <div class="table-responsive">
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
        </div>
    </div>

    <!-- FEFO Early Warning (4 cols) -->
    <div class="col-12 col-lg-4">
        <div class="mb-card h-100">
            <div class="mb-card-header">
                <div>
                    <h3 class="mb-card-title">
                        <i class="bi bi-clock-history text-warning"></i> Peringatan FEFO
                    </h3>
                    <p class="mb-card-subtitle">First Expired, First Out (Kadaluarsa H-30)</p>
                </div>
                <a href="<?= site_url('batch'); ?>" class="btn btn-sm btn-link text-muted text-decoration-none p-0" title="Lihat Batch">
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div id="expiring-batches-container">
                <?php if (!empty($expiring_batches)): ?>
                    <?php foreach ($expiring_batches as $b): ?>
                        <?php
                        $daysLeft = (int) ($b['days_left'] ?? 0);
                        $isExpired = $daysLeft < 0;
                        $badgeClass = $isExpired ? 'bg-danger text-white' : ($daysLeft <= 14 ? 'bg-warning text-dark' : 'bg-secondary text-white');
                        $badgeLabel = $isExpired ? 'Expired' : "H-{$daysLeft}";
                        ?>
                        <div class="p-2.5 mb-2 rounded border border-subtle d-flex align-items-center justify-content-between" style="background-color: var(--mb-card-raised);">
                            <div>
                                <div class="fw-bold small text-truncate" style="max-width: 170px;"><?= html_escape($b['nama_produk']); ?></div>
                                <div class="text-muted small tabular-nums">
                                    <?= html_escape($b['nomor_batch']); ?> · Rak: <?= html_escape($b['lokasi_rak'] ?? '-'); ?>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge <?= $badgeClass; ?> rounded-pill font-monospace" style="font-size: 0.72rem;"><?= $badgeLabel; ?></span>
                                <div class="text-muted small tabular-nums mt-0.5"><?= number_format($b['stok_saat_ini']); ?> unit</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-shield-check text-success fs-3 d-block mb-1"></i>
                        <span class="small">Semua batch lot dalam kondisi aman.</span>
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
<div class="mb-card mb-4">
    <div class="mb-card-header">
        <div>
            <h3 class="mb-card-title">
                <i class="bi bi-clock-history text-danger"></i> Aktivitas Mutasi Terkini
            </h3>
            <p class="mb-card-subtitle">Catatan 10 transaksi mutasi barang terakhir di gudang</p>
        </div>
        <a href="<?= site_url('stok'); ?>" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="font-size: 0.78rem;">
            <span>Lihat Riwayat Lengkap</span> <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div id="recent-activities-container">
        <?php if (!empty($recent_activities)): ?>
            <?php foreach ($recent_activities as $act): ?>
                <?php
                $isMasuk = $act['tipe'] === 'masuk';
                $iconClass = $isMasuk ? 'bi-arrow-down-left text-success' : 'bi-arrow-up-right text-danger';
                ?>
                <div class="d-flex align-items-center justify-content-between py-2.5 border-bottom border-subtle">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 36px; height: 36px; background-color: var(--mb-surface-hover);">
                            <i class="bi <?= $iconClass; ?> fs-6"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-truncate" style="max-width: 280px; color: var(--mb-text-primary);">
                                <?= html_escape($act['nama_produk'] ?? 'Produk'); ?>
                            </div>
                            <div class="text-muted small">
                                <?php if ($isMasuk): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Masuk</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Keluar</span>
                                <?php endif; ?>
                                <span class="ms-1 tabular-nums font-monospace fw-semibold"><?= number_format($act['jumlah']); ?> unit</span>
                                <?php if (!empty($act['nomor_batch'])): ?>
                                    <span class="ms-1 text-muted">· Lot: <?= html_escape($act['nomor_batch']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($act['catatan'])): ?>
                                    <span class="ms-1 text-muted d-none d-md-inline">&mdash; <?= html_escape($act['catatan']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="text-end text-muted small">
                        <div class="tabular-nums"><?= date('d M Y, H:i', strtotime($act['tanggal'])); ?></div>
                        <div class="text-truncate" style="max-width: 120px;"><?= html_escape($act['user_name'] ?? 'Petugas'); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-4 text-muted">
                <i class="bi bi-clock-history me-1"></i> Belum ada aktivitas mutasi stok tercatat.
            </div>
        <?php endif; ?>
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
