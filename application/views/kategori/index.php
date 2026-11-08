<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary); letter-spacing: -0.01em;">
            Master Data Kategori
        </h2>
        <p class="text-muted small mb-0">
            Klasifikasi kelompok barang untuk pengorganisasian stok dan laporan pergudangan.
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?= site_url('kategori/create'); ?>" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold text-white shadow-sm" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
        </a>
    </div>
</div>

<div class="table-card mb-4">
    <!-- Modern Table Toolbar -->
    <div class="table-toolbar">
        <div class="table-search-input">
            <i class="bi bi-search"></i>
            <input type="text" id="kategoriSearchInput" class="form-control" placeholder="Cari nama kategori atau deskripsi...">
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge-soft badge-soft-secondary" id="kategoriCountBadge">
                <i class="bi bi-tags"></i> Total <?= count($categories); ?> Kategori
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-modern" id="kategoriTable">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th style="width: 260px;">Nama Kategori</th>
                    <th>Deskripsi & Catatan</th>
                    <th class="text-center" style="width: 160px;">Jumlah Produk</th>
                    <th class="text-center" style="width: 90px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="kategoriTableBody">
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="5">
                            <div class="table-empty-state">
                                <div class="table-empty-icon">
                                    <i class="bi bi-tags"></i>
                                </div>
                                <h6 class="fw-bold mb-1" style="color: var(--mb-text-primary);">Belum Ada Kategori</h6>
                                <p class="text-muted small mb-3">Buat klasifikasi kategori pertama untuk mengelompokkan barang gudang.</p>
                                <a href="<?= site_url('kategori/create'); ?>" class="btn btn-sm btn-danger px-3" style="background-color: var(--mb-brand);">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($categories as $cat): ?>
                        <tr class="kategori-row" data-search="<?= strtolower(html_escape($cat['nama_kategori'] . ' ' . ($cat['deskripsi'] ?? ''))); ?>">
                            <td class="text-center text-muted fw-semibold tabular-nums"><?= $no++; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center text-danger flex-shrink-0"
                                         style="width: 32px; height: 32px; background-color: var(--mb-brand-subtle); border: 1px solid var(--mb-brand-border);">
                                        <i class="bi bi-tag-fill" style="font-size: 0.85rem;"></i>
                                    </div>
                                    <span class="fw-semibold" style="color: var(--mb-text-primary); font-size: 0.88rem;">
                                        <?= html_escape($cat['nama_kategori']); ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted small">
                                    <?= html_escape($cat['deskripsi'] ?? '-'); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge-soft badge-soft-primary">
                                    <i class="bi bi-box me-1" style="font-size: 0.72rem;"></i>
                                    <strong class="tabular-nums font-monospace"><?= number_format($cat['total_produk'] ?? 0); ?></strong> Item
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="<?= site_url('kategori/delete/' . $cat['id']); ?>" class="table-action-btn btn-delete" title="Hapus Kategori" onclick="return confirm('Hapus kategori ini? Produk terkait akan kehilangan relasi kategori.');">
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
    const searchInput = document.getElementById('kategoriSearchInput');
    const tableBody = document.getElementById('kategoriTableBody');
    const countBadge = document.getElementById('kategoriCountBadge');
    const rows = document.querySelectorAll('.kategori-row');

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
                countBadge.innerHTML = `<i class="bi bi-tags"></i> ${visibleCount} Ditemukan`;
            }

            let noMatchRow = document.getElementById('noMatchRowKat');
            if (visibleCount === 0) {
                if (!noMatchRow) {
                    noMatchRow = document.createElement('tr');
                    noMatchRow.id = 'noMatchRowKat';
                    noMatchRow.innerHTML = `
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-3 d-block mb-2 text-secondary"></i>
                            Tidak ada kategori yang cocok dengan pencarian "<strong>${query}</strong>"
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
