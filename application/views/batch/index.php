<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary); letter-spacing: -0.01em;">
            Manajemen Lot & Batch Kardus
        </h2>
        <p class="text-muted small mb-0">
            Pelacakan nomor lot kardus fisik, lokasi rak penyimpanan, dan perputaran alokasi stok metode FIFO.
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold text-white shadow-sm" style="background-color: var(--mb-brand); border-color: var(--mb-brand);" data-bs-toggle="modal" data-bs-target="#modalTambahBatch">
            <i class="bi bi-plus-lg"></i> Registrasi Batch Baru
        </button>
    </div>
</div>

<div class="table-card mb-4">
    <!-- Modern Table Toolbar -->
    <div class="table-toolbar">
        <div class="table-search-input">
            <i class="bi bi-search"></i>
            <input type="text" id="batchSearchInput" class="form-control" placeholder="Cari nomor lot, SKU, nama produk...">
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge-soft badge-soft-secondary" id="batchCountBadge">
                <i class="bi bi-layers"></i> Total <?= count($batches); ?> Lot Batch
            </span>
        </div>
    </div>

    <!-- 1. Desktop High-Density Table (Hidden on Phones) -->
    <div class="table-responsive d-none d-md-block">
        <table class="table-modern" id="batchTable">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th class="text-center" style="width: 80px;">QR Batch</th>
                    <th style="width: 160px;">Nomor Lot / Batch</th>
                    <th>Nama Produk & SKU</th>
                    <th style="width: 140px;">Lokasi Rak</th>
                    <th style="width: 130px;">Tgl Masuk (FIFO)</th>
                    <th class="text-end" style="width: 100px;">Stok Awal</th>
                    <th class="text-end" style="width: 130px;">Sisa Stok</th>
                    <th class="text-center" style="width: 110px;">Status Stok</th>
                    <th class="text-center" style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="batchTableBody">
                <?php if (empty($batches)): ?>
                    <tr>
                        <td colspan="10">
                            <div class="table-empty-state">
                                <div class="table-empty-icon">
                                    <i class="bi bi-layers"></i>
                                </div>
                                <h6 class="fw-bold mb-1" style="color: var(--mb-text-primary);">Belum Ada Lot / Batch Kardus</h6>
                                <p class="text-muted small mb-3">Registrasikan nomor batch kedatangan barang untuk pelacakan alokasi perputaran FIFO.</p>
                                <a href="<?= site_url('batch/create'); ?>" class="btn btn-sm btn-danger px-3" style="background-color: var(--mb-brand);">
                                    <i class="bi bi-plus-lg me-1"></i> Registrasi Batch Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($batches as $b): ?>
                        <?php
                            $stok = (int)($b['stok_saat_ini'] ?? 0);
                            $awal = (int)($b['jumlah_awal'] ?? 1);
                            $pctSisa = $awal > 0 ? min(100, round(($stok / $awal) * 100)) : 0;
                            $status = $b['status'] ?? ($stok <= 0 ? 'habis' : ($pctSisa <= 20 ? 'hampir_habis' : 'tersedia'));
                        ?>
                        <tr class="batch-row" data-search="<?= strtolower(html_escape($b['nomor_batch'] . ' ' . ($b['nama_produk'] ?? '') . ' ' . ($b['sku'] ?? ''))); ?>">
                            <td class="text-center text-muted fw-semibold tabular-nums"><?= $no++; ?></td>
                            <td class="text-center">
                                <?php if (!empty($b['qr_code']) && file_exists(FCPATH . $b['qr_code'])): ?>
                                    <div class="table-qr-thumb" title="QR Batch: <?= html_escape($b['nomor_batch']); ?>">
                                        <img src="<?= base_url($b['qr_code']); ?>" alt="QR">
                                    </div>
                                <?php else: ?>
                                    <span class="badge-soft badge-soft-secondary" style="font-size: 0.68rem;">No QR</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="sku-badge font-monospace">
                                    <i class="bi bi-box text-muted" style="font-size: 0.7rem;"></i>
                                    <?= html_escape($b['nomor_batch']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold" style="color: var(--mb-text-primary); font-size: 0.88rem;">
                                    <?= html_escape($b['nama_produk'] ?? '-'); ?>
                                </div>
                                <code class="small text-muted font-monospace" style="font-size: 0.74rem;">
                                    <?= html_escape($b['sku'] ?? ''); ?>
                                </code>
                            </td>
                            <td>
                                <span class="badge-soft badge-soft-secondary font-monospace" style="font-size: 0.75rem;">
                                    <i class="bi bi-geo-alt text-muted me-1"></i><?= html_escape($b['lokasi_rak'] ?? '-'); ?>
                                </span>
                            </td>
                            <td class="text-muted small tabular-nums">
                                <?= !empty($b['tanggal_masuk']) ? format_date_by_preference($b['tanggal_masuk'], null, false) : '-'; ?>
                            </td>
                            <td class="text-end text-muted tabular-nums font-monospace" style="font-size: 0.82rem;">
                                <?= number_format($b['jumlah_awal']); ?>
                            </td>
                            <td class="text-end">
                                <div class="stock-level-cell">
                                    <span class="fw-bold tabular-nums font-monospace <?= $stok <= 0 ? 'text-danger' : 'text-success'; ?>">
                                        <?= number_format($stok); ?>
                                    </span>
                                    <div class="stock-meter-bar" title="Sisa Stok: <?= $pctSisa; ?>%">
                                        <div class="stock-meter-fill <?= $stok <= 0 ? 'danger' : ($pctSisa <= 25 ? 'warning' : 'safe'); ?>" style="width: <?= max(6, $pctSisa); ?>%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php if ($status === 'habis'): ?>
                                    <span class="badge-soft badge-soft-secondary">
                                        <span class="badge-dot bg-secondary"></span> Habis
                                    </span>
                                <?php elseif ($status === 'hampir_habis'): ?>
                                    <span class="badge-soft badge-soft-warning">
                                        <span class="badge-dot bg-warning"></span> Menipis
                                    </span>
                                <?php else: ?>
                                    <span class="badge-soft badge-soft-success">
                                        <span class="badge-dot bg-success"></span> Tersedia
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <a href="<?= site_url('api/batch/' . $b['id'] . '/download-qr'); ?>" target="_blank" class="table-action-btn btn-qr" title="Unduh Label Barcode QR">
                                        <i class="bi bi-qr-code"></i>
                                    </a>
                                    <a href="<?= site_url('batch/delete/' . $b['id']); ?>" class="table-action-btn btn-delete btn-delete-confirm" title="Hapus Lot / Batch" data-confirm-name="Batch <?= html_escape($b['nomor_batch']); ?>" data-confirm-type="batch">
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

    <!-- 2. Mobile Touch-Optimized Cards (Visible on Phones < 768px) -->
    <div class="mobile-card-list d-md-none p-3" id="batchMobileCards">
        <?php if (empty($batches)): ?>
            <div class="table-empty-state py-4 text-center">
                <div class="table-empty-icon mb-2">
                    <i class="bi bi-layers fs-2 text-muted"></i>
                </div>
                <h6 class="fw-bold mb-1" style="color: var(--mb-text-primary);">Belum Ada Lot / Batch</h6>
                <p class="text-muted small mb-3">Registrasikan nomor batch kardus untuk pelacakan alokasi FIFO.</p>
                <button type="button" class="btn btn-sm btn-danger px-3" style="background-color: var(--mb-brand);" data-bs-toggle="modal" data-bs-target="#modalTambahBatch">
                    <i class="bi bi-plus-lg me-1"></i> Registrasi Batch
                </button>
            </div>
        <?php else: ?>
            <?php foreach ($batches as $b): ?>
                <?php
                    $stok = (int)($b['stok_saat_ini'] ?? 0);
                    $awal = (int)($b['jumlah_awal'] ?? 1);
                    $pctSisa = $awal > 0 ? min(100, round(($stok / $awal) * 100)) : 0;
                    $status = $b['status'] ?? ($stok <= 0 ? 'habis' : ($pctSisa <= 20 ? 'hampir_habis' : 'tersedia'));
                ?>
                <div class="mobile-data-card batch-card-item" data-search="<?= strtolower(html_escape($b['nomor_batch'] . ' ' . ($b['nama_produk'] ?? '') . ' ' . ($b['sku'] ?? ''))); ?>">
                    <div class="mobile-card-header">
                        <?php if ($status === 'habis'): ?>
                            <span class="badge-soft badge-soft-secondary"><span class="badge-dot bg-secondary"></span> Habis</span>
                        <?php elseif ($status === 'hampir_habis'): ?>
                            <span class="badge-soft badge-soft-warning"><span class="badge-dot bg-warning"></span> Menipis</span>
                        <?php else: ?>
                            <span class="badge-soft badge-soft-success"><span class="badge-dot bg-success"></span> Tersedia</span>
                        <?php endif; ?>

                        <span class="mobile-card-sku-pill">
                            <i class="bi bi-layers me-1"></i><?= html_escape($b['nomor_batch']); ?>
                        </span>
                    </div>

                    <div class="mobile-card-body">
                        <?php if (!empty($b['qr_code']) && file_exists(FCPATH . $b['qr_code'])): ?>
                            <div class="mobile-card-thumb">
                                <img src="<?= base_url($b['qr_code']); ?>" alt="QR">
                            </div>
                        <?php else: ?>
                            <div class="mobile-card-thumb text-muted">
                                <i class="bi bi-qr-code mobile-card-thumb-icon"></i>
                            </div>
                        <?php endif; ?>
                        <div class="mobile-card-content">
                            <div class="mobile-card-title"><?= html_escape($b['nama_produk'] ?? 'Tanpa Produk'); ?></div>
                            <div class="mobile-card-subtitle font-monospace">
                                <span>SKU: <?= html_escape($b['sku'] ?? '-'); ?></span>
                            </div>
                            <div class="mt-1 d-flex flex-wrap align-items-center gap-1.5" style="font-size: 0.72rem;">
                                <span class="text-muted">Masuk:</span>
                                <strong class="text-body"><?= !empty($b['tanggal_masuk']) ? format_date_by_preference($b['tanggal_masuk'], null, false) : '-'; ?></strong>
                                <?php if (!empty($b['lokasi_rak'])): ?>
                                    <span class="text-muted">· Rak: <strong><?= html_escape($b['lokasi_rak']); ?></strong></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mobile-card-stats-box">
                        <div class="mobile-stat-row mb-1.5">
                            <span class="mobile-stat-label">Sisa Stok Lot:</span>
                            <span class="mobile-stat-value tabular-nums font-monospace <?= $stok <= 0 ? 'text-danger' : 'text-success'; ?>">
                                <?= number_format($stok); ?> / <?= number_format($awal); ?> unit
                            </span>
                        </div>
                        <div class="stock-meter-bar" style="height: 5px;">
                            <div class="stock-meter-fill <?= $stok <= 0 ? 'danger' : ($pctSisa <= 25 ? 'warning' : 'safe'); ?>" style="width: <?= max(6, $pctSisa); ?>%;"></div>
                        </div>
                    </div>

                    <div class="mobile-card-actions">
                        <a href="<?= site_url('api/batch/' . $b['id'] . '/download-qr'); ?>" target="_blank" class="btn btn-outline-secondary">
                            <i class="bi bi-qr-code"></i> Unduh QR
                        </a>
                        <a href="<?= site_url('batch/delete/' . $b['id']); ?>" class="btn btn-outline-danger btn-delete-confirm" data-confirm-name="Batch <?= html_escape($b['nomor_batch']); ?>" data-confirm-type="batch">
                            <i class="bi bi-trash"></i> Hapus Batch
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('batchSearchInput');
    const tableBody = document.getElementById('batchTableBody');
    const mobileCardsContainer = document.getElementById('batchMobileCards');
    const countBadge = document.getElementById('batchCountBadge');
    const rows = document.querySelectorAll('.batch-row');
    const cards = document.querySelectorAll('.batch-card-item');

    if (searchInput) {
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

            cards.forEach(card => {
                const searchData = card.getAttribute('data-search') || '';
                if (searchData.includes(query)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });

            if (countBadge) {
                countBadge.innerHTML = `<i class="bi bi-layers"></i> ${visibleCount} Ditemukan`;
            }

            let noMatchRow = document.getElementById('noMatchRowBatch');
            if (visibleCount === 0 && rows.length > 0) {
                if (!noMatchRow && tableBody) {
                    noMatchRow = document.createElement('tr');
                    noMatchRow.id = 'noMatchRowBatch';
                    noMatchRow.innerHTML = `
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-3 d-block mb-2 text-secondary"></i>
                            Tidak ada nomor batch yang cocok dengan pencarian "<strong></strong>"
                        </td>
                    `;
                    const strongEl = noMatchRow.querySelector('strong');
                    if (strongEl) strongEl.textContent = query;
                    tableBody.appendChild(noMatchRow);
                } else if (noMatchRow) {
                    noMatchRow.style.display = '';
                    const strongEl = noMatchRow.querySelector('strong');
                    if (strongEl) strongEl.textContent = query;
                }
            } else if (noMatchRow) {
                noMatchRow.style.display = 'none';
            }

            let noMatchMobile = document.getElementById('noMatchMobileBatch');
            if (visibleCount === 0 && cards.length > 0) {
                if (!noMatchMobile && mobileCardsContainer) {
                    noMatchMobile = document.createElement('div');
                    noMatchMobile.id = 'noMatchMobileBatch';
                    noMatchMobile.className = 'text-center py-5 text-muted';
                    noMatchMobile.innerHTML = `
                        <i class="bi bi-search fs-3 d-block mb-2 text-secondary"></i>
                        Tidak ada batch yang cocok dengan "<strong></strong>"
                    `;
                    const strongEl = noMatchMobile.querySelector('strong');
                    if (strongEl) strongEl.textContent = query;
                    mobileCardsContainer.appendChild(noMatchMobile);
                } else if (noMatchMobile) {
                    noMatchMobile.style.display = '';
                    const strongEl = noMatchMobile.querySelector('strong');
                    if (strongEl) strongEl.textContent = query;
                }
            } else if (noMatchMobile) {
                noMatchMobile.style.display = 'none';
            }
        });
    }
});

// Batch Code Generator for Modal
function generateCreateBatch() {
    const input = document.getElementById('create_nomor_batch');
    if (input) {
        const prefix = '<?= $lot_prefix; ?>';
        const d = new Date();
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        const rand = Math.floor(100 + Math.random() * 900);
        input.value = prefix + y + m + day + '-' + rand;
    }
}
</script>

<!-- =========================================================
     MODAL: Registrasi Batch Baru (FIFO)
     ========================================================= -->
<div class="modal fade" id="modalTambahBatch" tabindex="-1" aria-labelledby="modalTambahBatchLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahBatchLabel">
                    <i class="bi bi-layers text-danger"></i> Registrasi Batch Baru (FIFO)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <?= form_open('batch/store', ['id' => 'formTambahBatch']); ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label fw-semibold">Produk Terkait <span class="text-danger">*</span></label>
                            <select name="produk_id" class="form-select" required>
                                <option value="">-- Pilih Produk --</option>
                                <?php if (!empty($products)): ?>
                                    <?php foreach ($products as $p): ?>
                                        <option value="<?= $p['id']; ?>"><?= html_escape($p['nama_produk']); ?> (SKU: <?= html_escape($p['sku']); ?>)</option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Nomor Lot / Batch <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="nomor_batch" id="create_nomor_batch" class="form-control" value="<?= $lot_prefix . date('Ymd') . '-' . rand(100, 999); ?>" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="generateCreateBatch()" title="Acak Nomor Batch">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jumlah Lot (Unit) <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_awal" class="form-control" min="1" value="50" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal Masuk (FIFO) <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_masuk" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kapasitas Kardus</label>
                            <input type="number" name="kapasitas" class="form-control" min="1" placeholder="Opsional (misal: 100)">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Lokasi Rak / Blok Gudang</label>
                            <input type="text" name="lokasi_rak" class="form-control" placeholder="Contoh: RAK-A1-04, Blok Logistik Barat...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4 fw-semibold d-inline-flex align-items-center gap-1.5" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                        <i class="bi bi-save"></i> Daftarkan Batch
                    </button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
