<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary); letter-spacing: -0.01em;">
            Master Data Pemasok & Vendor
        </h2>
        <p class="text-muted small mb-0">
            Daftar rekanan vendor penyuplai produk dan riwayat kontak untuk kebutuhan pengadaan.
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?= site_url('pemasok/create'); ?>" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold text-white shadow-sm" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
            <i class="bi bi-plus-lg"></i> Tambah Pemasok
        </a>
    </div>
</div>

<div class="table-card mb-4">
    <!-- Modern Table Toolbar -->
    <div class="table-toolbar">
        <div class="table-search-input">
            <i class="bi bi-search"></i>
            <input type="text" id="pemasokSearchInput" class="form-control" placeholder="Cari nama vendor, kontak, alamat...">
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge-soft badge-soft-secondary" id="pemasokCountBadge">
                <i class="bi bi-truck"></i> Total <?= count($suppliers); ?> Pemasok
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-modern" id="pemasokTable">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th style="width: 260px;">Nama Pemasok / Vendor</th>
                    <th style="width: 180px;">Kontak & Telp</th>
                    <th>Alamat Operasional</th>
                    <th class="text-center" style="width: 150px;">Produk Dipasok</th>
                    <th class="text-center" style="width: 90px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="pemasokTableBody">
                <?php if (empty($suppliers)): ?>
                    <tr>
                        <td colspan="6">
                            <div class="table-empty-state">
                                <div class="table-empty-icon">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <h6 class="fw-bold mb-1" style="color: var(--mb-text-primary);">Belum Ada Pemasok</h6>
                                <p class="text-muted small mb-3">Daftarkan rekanan distributor atau vendor pemasok barang pergudangan.</p>
                                <a href="<?= site_url('pemasok/create'); ?>" class="btn btn-sm btn-danger px-3" style="background-color: var(--mb-brand);">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Pemasok
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($suppliers as $sup): ?>
                        <tr class="pemasok-row" data-search="<?= strtolower(html_escape($sup['nama_pemasok'] . ' ' . ($sup['kontak'] ?? '') . ' ' . ($sup['alamat'] ?? ''))); ?>">
                            <td class="text-center text-muted fw-semibold tabular-nums"><?= $no++; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center text-primary flex-shrink-0"
                                         style="width: 32px; height: 32px; background-color: var(--mb-info-subtle); border: 1px solid rgba(59, 130, 246, 0.2);">
                                        <i class="bi bi-building" style="font-size: 0.85rem;"></i>
                                    </div>
                                    <span class="fw-semibold" style="color: var(--mb-text-primary); font-size: 0.88rem;">
                                        <?= html_escape($sup['nama_pemasok']); ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($sup['kontak'])): ?>
                                    <span class="d-inline-flex align-items-center gap-1.5 font-monospace text-muted small">
                                        <i class="bi bi-telephone text-primary" style="font-size: 0.76rem;"></i>
                                        <?= html_escape($sup['kontak']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="text-muted small">
                                    <i class="bi bi-geo-alt me-1 text-secondary" style="font-size: 0.74rem;"></i>
                                    <?= html_escape($sup['alamat'] ?? '-'); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge-soft badge-soft-secondary">
                                    <i class="bi bi-box-seam me-1" style="font-size: 0.72rem;"></i>
                                    <strong class="tabular-nums font-monospace"><?= number_format($sup['total_produk'] ?? 0); ?></strong> Item
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="<?= site_url('pemasok/delete/' . $sup['id']); ?>" class="table-action-btn btn-delete" title="Hapus Pemasok" onclick="return confirm('Hapus pemasok ini?');">
                                    <i class="bi bi-trash"></i>
                                </a>
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
    const searchInput = document.getElementById('pemasokSearchInput');
    const tableBody = document.getElementById('pemasokTableBody');
    const countBadge = document.getElementById('pemasokCountBadge');
    const rows = document.querySelectorAll('.pemasok-row');

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

            if (countBadge) {
                countBadge.innerHTML = `<i class="bi bi-truck"></i> ${visibleCount} Ditemukan`;
            }

            let noMatchRow = document.getElementById('noMatchRowPem');
            if (visibleCount === 0) {
                if (!noMatchRow) {
                    noMatchRow = document.createElement('tr');
                    noMatchRow.id = 'noMatchRowPem';
                    noMatchRow.innerHTML = `
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-3 d-block mb-2 text-secondary"></i>
                            Tidak ada pemasok yang cocok dengan pencarian "<strong>${query}</strong>"
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
