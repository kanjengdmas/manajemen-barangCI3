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
        <button type="button" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold text-white shadow-sm" style="background-color: var(--mb-brand); border-color: var(--mb-brand);" data-bs-toggle="modal" data-bs-target="#modalTambahProduk">
            <i class="bi bi-plus-lg"></i> Tambah Produk
        </button>
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

    <!-- 1. Desktop High-Density Table View (Hidden on Phones) -->
    <div class="table-responsive d-none d-md-block">
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
                                    <a href="<?= site_url('api/produk/' . $p['id'] . '/download-qr'); ?>" target="_blank" class="table-action-btn btn-qr" title="Unduh Label Barcode QR">
                                        <i class="bi bi-qr-code"></i>
                                    </a>
                                    <button type="button" class="table-action-btn btn-edit btn-edit-product" title="Edit Data Produk"
                                            data-id="<?= $p['id']; ?>"
                                            data-nama="<?= html_escape($p['nama_produk']); ?>"
                                            data-sku="<?= html_escape($p['sku']); ?>"
                                            data-kategori="<?= $p['kategori_id']; ?>"
                                            data-pemasok="<?= $p['pemasok_id'] ?? ''; ?>"
                                            data-stok-minimal="<?= $p['stok_minimal']; ?>"
                                            data-deskripsi="<?= html_escape($p['deskripsi'] ?? ''); ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <a href="<?= site_url('produk/delete/' . $p['id']); ?>" class="table-action-btn btn-delete btn-delete-confirm" title="Hapus Produk" data-confirm-name="<?= html_escape($p['nama_produk']); ?>" data-confirm-type="produk">
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

    <!-- 2. Mobile Touch-Optimized Card Feed (Visible on Phones < 768px) -->
    <div class="mobile-card-list d-md-none p-3" id="productMobileCards">
        <?php if (empty($products)): ?>
            <div class="table-empty-state py-4 text-center">
                <div class="table-empty-icon mb-2">
                    <i class="bi bi-inbox fs-2 text-muted"></i>
                </div>
                <h6 class="fw-bold mb-1" style="color: var(--mb-text-primary);">Belum Ada Produk</h6>
                <p class="text-muted small mb-3">Katalog masih kosong. Tambahkan produk pertama Anda.</p>
                <button type="button" class="btn btn-sm btn-danger px-3" style="background-color: var(--mb-brand);" data-bs-toggle="modal" data-bs-target="#modalTambahProduk">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Produk
                </button>
            </div>
        <?php else: ?>
            <?php foreach ($products as $p): ?>
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
                <div class="mobile-data-card product-card-item" data-search="<?= strtolower(html_escape($p['nama_produk'] . ' ' . $p['sku'] . ' ' . ($p['nama_kategori'] ?? '') . ' ' . ($p['nama_pemasok'] ?? ''))); ?>">
                    <div class="mobile-card-header">
                        <?php if ($stok <= 0): ?>
                            <span class="badge-soft badge-soft-danger"><span class="badge-dot bg-danger"></span> Habis</span>
                        <?php elseif ($stok <= $minStok): ?>
                            <span class="badge-soft badge-soft-warning"><span class="badge-dot bg-warning"></span> Menipis</span>
                        <?php else: ?>
                            <span class="badge-soft badge-soft-success"><span class="badge-dot bg-success"></span> Aman</span>
                        <?php endif; ?>
                        <span class="mobile-card-sku-pill">
                            <i class="bi bi-upc-scan me-1"></i><?= html_escape($p['sku']); ?>
                        </span>
                    </div>

                    <div class="mobile-card-body">
                        <?php if (!empty($p['qr_code']) && file_exists(FCPATH . $p['qr_code'])): ?>
                            <div class="mobile-card-thumb">
                                <img src="<?= base_url($p['qr_code']); ?>" alt="QR">
                            </div>
                        <?php else: ?>
                            <div class="mobile-card-thumb text-muted">
                                <i class="bi bi-box-seam mobile-card-thumb-icon"></i>
                            </div>
                        <?php endif; ?>
                        <div class="mobile-card-content">
                            <div class="mobile-card-title"><?= html_escape($p['nama_produk']); ?></div>
                            <div class="mobile-card-subtitle">
                                <span class="badge-soft badge-soft-secondary me-1"><?= html_escape($p['nama_kategori'] ?? '-'); ?></span>
                                <?php if (!empty($p['nama_pemasok'])): ?>
                                    <span>· <?= html_escape($p['nama_pemasok']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mobile-card-stats-box">
                        <div class="mobile-stat-row mb-1.5">
                            <span class="mobile-stat-label">Sisa Stok Fisik:</span>
                            <span class="mobile-stat-value tabular-nums font-monospace <?= $stok <= 0 ? 'text-danger' : ($stok <= $minStok ? 'text-warning' : 'text-success'); ?>">
                                <?= number_format($p['stok']); ?> unit
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between text-muted" style="font-size: 0.7rem; margin-bottom: 5px;">
                            <span>Batas Minimal: <?= number_format($p['stok_minimal']); ?></span>
                            <span>Kapasitas: <?= $pct; ?>%</span>
                        </div>
                        <div class="stock-meter-bar" style="height: 5px;">
                            <div class="stock-meter-fill <?= $meterClass; ?>" style="width: <?= max(6, $pct); ?>%;"></div>
                        </div>
                    </div>

                    <div class="mobile-card-actions">
                        <a href="<?= site_url('api/produk/' . $p['id'] . '/download-qr'); ?>" target="_blank" class="btn btn-outline-secondary" title="Unduh Barcode QR">
                            <i class="bi bi-qr-code"></i> Unduh QR
                        </a>
                        <button type="button" class="btn btn-outline-secondary btn-edit-product"
                                data-id="<?= $p['id']; ?>"
                                data-nama="<?= html_escape($p['nama_produk']); ?>"
                                data-sku="<?= html_escape($p['sku']); ?>"
                                data-kategori="<?= $p['kategori_id']; ?>"
                                data-pemasok="<?= $p['pemasok_id'] ?? ''; ?>"
                                data-stok-minimal="<?= $p['stok_minimal']; ?>"
                                data-deskripsi="<?= html_escape($p['deskripsi'] ?? ''); ?>">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <a href="<?= site_url('produk/delete/' . $p['id']); ?>" class="btn btn-outline-danger btn-delete-confirm" data-confirm-name="<?= html_escape($p['nama_produk']); ?>" data-confirm-type="produk">
                            <i class="bi bi-trash"></i> Hapus
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('productSearchInput');
    const tableBody = document.getElementById('productTableBody');
    const mobileCardsContainer = document.getElementById('productMobileCards');
    const countBadge = document.getElementById('productCountBadge');
    const rows = document.querySelectorAll('.product-row');
    const cards = document.querySelectorAll('.product-card-item');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let visibleCount = 0;

            // Filter Desktop Table Rows
            rows.forEach(row => {
                const searchData = row.getAttribute('data-search') || '';
                if (searchData.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Filter Mobile Cards
            cards.forEach(card => {
                const searchData = card.getAttribute('data-search') || '';
                if (searchData.includes(query)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });

            // Update visible count in badge
            if (countBadge) {
                countBadge.innerHTML = `<i class="bi bi-box-seam"></i> ${visibleCount} Ditemukan`;
            }

            // Desktop no match row
            let noMatchRow = document.getElementById('noMatchRow');
            if (visibleCount === 0 && rows.length > 0) {
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

            // Mobile no match notice
            let noMatchMobile = document.getElementById('noMatchMobile');
            if (visibleCount === 0 && cards.length > 0) {
                if (!noMatchMobile) {
                    noMatchMobile = document.createElement('div');
                    noMatchMobile.id = 'noMatchMobile';
                    noMatchMobile.className = 'text-center py-5 text-muted';
                    noMatchMobile.innerHTML = `
                        <i class="bi bi-search fs-3 d-block mb-2 text-secondary"></i>
                        Tidak ada produk yang cocok dengan "<strong>${query}</strong>"
                    `;
                    mobileCardsContainer.appendChild(noMatchMobile);
                } else {
                    noMatchMobile.style.display = '';
                    noMatchMobile.querySelector('strong').textContent = query;
                }
            } else if (noMatchMobile) {
                noMatchMobile.style.display = 'none';
            }
        });
    }

    // Edit Product Modal Handler
    const modalEditEl = document.getElementById('modalEditProduk');
    const formEdit = document.getElementById('formEditProduk');
    const editNama = document.getElementById('edit_nama_produk');
    const editSku = document.getElementById('edit_sku');
    const editKategori = document.getElementById('edit_kategori_id');
    const editPemasok = document.getElementById('edit_pemasok_id');
    const editMinStok = document.getElementById('edit_stok_minimal');
    const editDeskripsi = document.getElementById('edit_deskripsi');

    document.querySelectorAll('.btn-edit-product').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');
            const sku = this.getAttribute('data-sku');
            const kategori = this.getAttribute('data-kategori');
            const pemasok = this.getAttribute('data-pemasok');
            const minStok = this.getAttribute('data-stok-minimal');
            const deskripsi = this.getAttribute('data-deskripsi');

            if (formEdit) {
                formEdit.action = '<?= site_url("produk/update/"); ?>' + id;
            }
            if (editNama) editNama.value = nama || '';
            if (editSku) editSku.value = sku || '';
            if (editKategori) editKategori.value = kategori || '';
            if (editPemasok) editPemasok.value = pemasok || '';
            if (editMinStok) editMinStok.value = minStok || '10';
            if (editDeskripsi) editDeskripsi.value = deskripsi || '';

            if (modalEditEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEditEl);
                modal.show();
            }
        });
    });
});

// SKU Auto-Generator for Create Modal
function generateCreateSku() {
    const input = document.getElementById('create_sku');
    if (input) {
        const prefix = '<?= $sku_prefix; ?>';
        const randomPart = Math.random().toString(36).substring(2, 8).toUpperCase();
        input.value = prefix + randomPart;
    }
}
</script>

<!-- =========================================================
     MODAL 1: Tambah Produk Baru
     ========================================================= -->
<div class="modal fade" id="modalTambahProduk" tabindex="-1" aria-labelledby="modalTambahProdukLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahProdukLabel">
                    <i class="bi bi-box-seam text-danger"></i> Tambah Produk Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <?= form_open('produk/store', ['id' => 'formTambahProduk']); ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Kertas HVS A4 80gr" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kode SKU <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="sku" id="create_sku" class="form-control" value="<?= $sku_prefix . strtoupper(substr(uniqid(), -6)); ?>" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="generateCreateSku()" title="Acak Kode SKU">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id']; ?>"><?= html_escape($cat['nama_kategori']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pemasok / Vendor</label>
                            <select name="pemasok_id" class="form-select">
                                <option value="">-- Tanpa Pemasok Tetap --</option>
                                <?php foreach ($suppliers as $sup): ?>
                                    <option value="<?= $sup['id']; ?>"><?= html_escape($sup['nama_pemasok']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok Fisik Awal</label>
                            <input type="number" name="stok_awal" class="form-control" min="0" value="0">
                            <div class="form-text">Dapat diisi 0 jika barang belum tersedia.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ambang Batas Minimal Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok_minimal" class="form-control" min="0" value="<?= $default_min_stock; ?>" required>
                            <div class="form-text">Peringatan otomatis muncul jika stok di bawah ini.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi / Spesifikasi Tambahan</label>
                            <textarea name="deskripsi" class="form-control" rows="2" placeholder="Informasi dimensi, berat, atau penempatan rak..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4 fw-semibold d-inline-flex align-items-center gap-1.5" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                        <i class="bi bi-save"></i> Simpan Produk
                    </button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL 2: Edit Data Produk
     ========================================================= -->
<div class="modal fade" id="modalEditProduk" tabindex="-1" aria-labelledby="modalEditProdukLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditProdukLabel">
                    <i class="bi bi-pencil-square text-danger"></i> Edit Data Produk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form method="POST" action="" id="formEditProduk">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="nama_produk" id="edit_nama_produk" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kode SKU <span class="text-danger">*</span></label>
                            <input type="text" name="sku" id="edit_sku" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_id" id="edit_kategori_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id']; ?>"><?= html_escape($cat['nama_kategori']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pemasok / Vendor</label>
                            <select name="pemasok_id" id="edit_pemasok_id" class="form-select">
                                <option value="">-- Tanpa Pemasok Tetap --</option>
                                <?php foreach ($suppliers as $sup): ?>
                                    <option value="<?= $sup['id']; ?>"><?= html_escape($sup['nama_pemasok']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ambang Batas Minimal Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok_minimal" id="edit_stok_minimal" class="form-control" min="0" required>
                            <div class="form-text">Peringatan otomatis muncul jika stok di bawah ini.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Stok Fisik Saat Ini</label>
                            <div class="form-control bg-light text-muted" style="cursor: not-allowed;">
                                <i class="bi bi-lock me-1"></i> Dikelola melalui menu Mutasi Masuk / Keluar
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi / Spesifikasi Tambahan</label>
                            <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4 fw-semibold d-inline-flex align-items-center gap-1.5" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                        <i class="bi bi-check-lg"></i> Perbarui Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
