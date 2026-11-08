<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary); letter-spacing: -0.01em;">
            Master Data Produk & Barang
        </h2>
        <p class="text-muted small mb-0">
            Daftar lengkap katalog barang inventaris, ambang batas minimum, dan kode QR unik.
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?= site_url('produk/create'); ?>" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold text-white shadow-sm" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
            <i class="bi bi-plus-lg"></i> Tambah Produk
        </a>
    </div>
</div>

<div class="table-card mb-4">
    <!-- Modern Table Toolbar -->
    <div class="table-toolbar">
        <div class="table-search-input">
            <i class="bi bi-search"></i>
            <input type="text" id="productSearchInput" class="form-control" placeholder="Cari nama barang, SKU, kategori...">
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge-soft badge-soft-secondary" id="productCountBadge">
                <i class="bi bi-box-seam"></i> Total <?= count($products); ?> Produk
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-modern" id="productTable">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th class="text-center" style="width: 80px;">QR Barcode</th>
                    <th>Nama Produk</th>
                    <th>SKU Barang</th>
                    <th>Kategori</th>
                    <th>Pemasok</th>
                    <th class="text-end" style="width: 140px;">Stok Fisik</th>
                    <th class="text-end" style="width: 100px;">Batas Min.</th>
                    <th class="text-center" style="width: 110px;">Status</th>
                    <th class="text-center" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                <?php if (empty($products)): ?>
                    <tr id="emptyRow">
                        <td colspan="10">
                            <div class="table-empty-state">
                                <div class="table-empty-icon">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <h6 class="fw-bold mb-1" style="color: var(--mb-text-primary);">Belum Ada Produk</h6>
                                <p class="text-muted small mb-3">Katalog inventaris masih kosong. Daftarkan produk baru untuk memulai.</p>
                                <a href="<?= site_url('produk/create'); ?>" class="btn btn-sm btn-danger px-3" style="background-color: var(--mb-brand);">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Produk Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($products as $p): ?>
                        <?php
                            $stok = (int)$p['stok'];
                            $minStok = (int)$p['stok_minimal'];
                            $pct = $minStok > 0 ? min(100, round(($stok / ($minStok * 2)) * 100)) : 100;
                            if ($stok <= 0) {
                                $meterClass = 'danger';
                            } elseif ($stok <= $minStok) {
                                $meterClass = 'warning';
                            } else {
                                $meterClass = 'safe';
                            }
                        ?>
                        <tr class="product-row" data-search="<?= strtolower(html_escape($p['nama_produk'] . ' ' . $p['sku'] . ' ' . ($p['nama_kategori'] ?? '') . ' ' . ($p['nama_pemasok'] ?? ''))); ?>">
                            <td class="text-center text-muted fw-semibold tabular-nums row-index"><?= $no++; ?></td>
                            <td class="text-center">
                                <?php if (!empty($p['qr_code']) && file_exists(FCPATH . $p['qr_code'])): ?>
                                    <div class="table-qr-thumb" title="QR Code: <?= html_escape($p['sku']); ?>">
                                        <img src="<?= base_url($p['qr_code']); ?>" alt="QR">
                                    </div>
                                <?php else: ?>
                                    <span class="badge-soft badge-soft-secondary" style="font-size: 0.68rem;">No QR</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-semibold" style="color: var(--mb-text-primary); font-size: 0.88rem;">
                                    <?= html_escape($p['nama_produk']); ?>
                                </div>
                                <?php if (!empty($p['deskripsi'])): ?>
                                    <div class="text-muted small text-truncate" style="max-width: 260px; font-size: 0.74rem;">
                                        <?= html_escape($p['deskripsi']); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="sku-badge">
                                    <i class="bi bi-upc-scan text-muted" style="font-size: 0.7rem;"></i>
                                    <?= html_escape($p['sku']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-soft badge-soft-secondary">
                                    <?= html_escape($p['nama_kategori'] ?? '-'); ?>
                                </span>
                            </td>
                            <td>
                                <span class="text-muted" style="font-size: 0.82rem;">
                                    <?= html_escape($p['nama_pemasok'] ?? '-'); ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="stock-level-cell">
                                    <span class="fw-bold tabular-nums font-monospace" style="color: var(--mb-text-primary);">
                                        <?= number_format($p['stok']); ?> <span class="text-muted fw-normal" style="font-size: 0.72rem;">unit</span>
                                    </span>
                                    <div class="stock-meter-bar" title="Kapasitas vs Min: <?= $pct; ?>%">
                                        <div class="stock-meter-fill <?= $meterClass; ?>" style="width: <?= max(6, $pct); ?>%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end text-muted tabular-nums font-monospace" style="font-size: 0.82rem;">
                                <?= number_format($p['stok_minimal']); ?>
                            </td>
                            <td class="text-center">
                                <?php if ($p['stok'] <= 0): ?>
                                    <span class="badge-soft badge-soft-danger">
                                        <span class="badge-dot bg-danger"></span> Habis
                                    </span>
                                <?php elseif ($p['stok'] <= $p['stok_minimal']): ?>
                                    <span class="badge-soft badge-soft-warning">
                                        <span class="badge-dot bg-warning"></span> Menipis
                                    </span>
                                <?php else: ?>
                                    <span class="badge-soft badge-soft-success">
                                        <span class="badge-dot bg-success"></span> Aman
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <a href="<?= site_url('produk/edit/' . $p['id']); ?>" class="table-action-btn btn-edit" title="Edit Data Produk">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= site_url('produk/delete/' . $p['id']); ?>" class="table-action-btn btn-delete" title="Hapus Produk" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('productSearchInput');
    const tableBody = document.getElementById('productTableBody');
    const countBadge = document.getElementById('productCountBadge');
    const rows = document.querySelectorAll('.product-row');

    if (searchInput && rows.length > 0) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let visibleCount = 0;

            rows.forEach(row => {
                const searchData = row.getAttribute('data-search') || '';
                if (searchData.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update visible count in badge
            if (countBadge) {
                countBadge.innerHTML = `<i class="bi bi-box-seam"></i> ${visibleCount} Ditemukan`;
            }

            // Show empty search notice if 0 matches
            let noMatchRow = document.getElementById('noMatchRow');
            if (visibleCount === 0) {
                if (!noMatchRow) {
                    noMatchRow = document.createElement('tr');
                    noMatchRow.id = 'noMatchRow';
                    noMatchRow.innerHTML = `
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-3 d-block mb-2 text-secondary"></i>
                            Tidak ada produk yang cocok dengan pencarian "<strong>${query}</strong>"
                        </td>
                    `;
                    tableBody.appendChild(noMatchRow);
                } else {
                    noMatchRow.style.display = '';
                    noMatchRow.querySelector('strong').textContent = query;
                }
            } else if (noMatchRow) {
                noMatchRow.style.display = 'none';
            }
        });
    }
});
</script>
