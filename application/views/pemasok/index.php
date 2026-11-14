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
        <button type="button" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold text-white shadow-sm" style="background-color: var(--mb-brand); border-color: var(--mb-brand);" data-bs-toggle="modal" data-bs-target="#modalTambahPemasok">
            <i class="bi bi-plus-lg"></i> Tambah Pemasok
        </button>
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

    <!-- 1. Desktop High-Density Table (Hidden on Phones) -->
    <div class="table-responsive d-none d-md-block">
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
                                <div class="d-inline-flex align-items-center gap-1">
                                    <button type="button" class="table-action-btn btn-edit btn-edit-pemasok" title="Edit Pemasok"
                                            data-id="<?= $sup['id']; ?>"
                                            data-nama="<?= html_escape($sup['nama_pemasok']); ?>"
                                            data-kontak="<?= html_escape($sup['kontak']); ?>"
                                            data-alamat="<?= html_escape($sup['alamat'] ?? ''); ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <a href="<?= site_url('pemasok/delete/' . $sup['id']); ?>" class="table-action-btn btn-delete btn-delete-confirm" title="Hapus Pemasok" data-confirm-name="<?= html_escape($sup['nama_pemasok']); ?>" data-confirm-type="pemasok">
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
    <div class="mobile-card-list d-md-none p-3" id="pemasokMobileCards">
        <?php if (empty($suppliers)): ?>
            <div class="table-empty-state py-4 text-center">
                <div class="table-empty-icon mb-2">
                    <i class="bi bi-truck fs-2 text-muted"></i>
                </div>
                <h6 class="fw-bold mb-1" style="color: var(--mb-text-primary);">Belum Ada Pemasok</h6>
                <p class="text-muted small mb-3">Daftarkan rekanan distributor atau vendor pemasok barang pergudangan.</p>
                <button type="button" class="btn btn-sm btn-danger px-3" style="background-color: var(--mb-brand);" data-bs-toggle="modal" data-bs-target="#modalTambahPemasok">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Pemasok
                </button>
            </div>
        <?php else: ?>
            <?php foreach ($suppliers as $sup): ?>
                <div class="mobile-data-card pemasok-card-item" data-search="<?= strtolower(html_escape($sup['nama_pemasok'] . ' ' . ($sup['kontak'] ?? '') . ' ' . ($sup['alamat'] ?? ''))); ?>">
                    <div class="mobile-card-header">
                        <span class="badge-soft badge-soft-secondary">
                            <i class="bi bi-box-seam me-1"></i> <?= number_format($sup['total_produk'] ?? 0); ?> Item Pasokan
                        </span>
                        <?php if (!empty($sup['kontak'])): ?>
                            <a href="tel:<?= preg_replace('/[^0-9+]/', '', $sup['kontak']); ?>" class="btn btn-sm btn-outline-primary py-1 px-2.5 rounded-pill" style="font-size: 0.72rem;">
                                <i class="bi bi-telephone-fill me-1"></i> Hubungi
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="mobile-card-body mb-2">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-primary flex-shrink-0"
                             style="width: 44px; height: 44px; background-color: var(--mb-info-subtle); border: 1px solid rgba(59, 130, 246, 0.2);">
                            <i class="bi bi-building fs-5"></i>
                        </div>
                        <div class="mobile-card-content">
                            <div class="mobile-card-title"><?= html_escape($sup['nama_pemasok']); ?></div>
                            <div class="mobile-card-subtitle">
                                <?php if (!empty($sup['kontak'])): ?>
                                    <div class="mb-1 font-monospace"><i class="bi bi-telephone me-1 text-muted"></i> <?= html_escape($sup['kontak']); ?></div>
                                <?php endif; ?>
                                <div><i class="bi bi-geo-alt me-1 text-muted"></i> <?= html_escape($sup['alamat'] ?? '-'); ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="mobile-card-actions">
                        <button type="button" class="btn btn-outline-secondary btn-edit-pemasok"
                                data-id="<?= $sup['id']; ?>"
                                data-nama="<?= html_escape($sup['nama_pemasok']); ?>"
                                data-kontak="<?= html_escape($sup['kontak']); ?>"
                                data-alamat="<?= html_escape($sup['alamat'] ?? ''); ?>">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <a href="<?= site_url('pemasok/delete/' . $sup['id']); ?>" class="btn btn-outline-danger btn-delete-confirm" data-confirm-name="<?= html_escape($sup['nama_pemasok']); ?>" data-confirm-type="pemasok">
                            <i class="bi bi-trash"></i> Hapus Rekanan
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('pemasokSearchInput');
    const tableBody = document.getElementById('pemasokTableBody');
    const mobileCardsContainer = document.getElementById('pemasokMobileCards');
    const countBadge = document.getElementById('pemasokCountBadge');
    const rows = document.querySelectorAll('.pemasok-row');
    const cards = document.querySelectorAll('.pemasok-card-item');

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
                countBadge.innerHTML = `<i class="bi bi-truck"></i> ${visibleCount} Ditemukan`;
            }

            let noMatchRow = document.getElementById('noMatchRowPem');
            if (visibleCount === 0 && rows.length > 0) {
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

            let noMatchMobile = document.getElementById('noMatchMobilePem');
            if (visibleCount === 0 && cards.length > 0) {
                if (!noMatchMobile) {
                    noMatchMobile = document.createElement('div');
                    noMatchMobile.id = 'noMatchMobilePem';
                    noMatchMobile.className = 'text-center py-5 text-muted';
                    noMatchMobile.innerHTML = `
                        <i class="bi bi-search fs-3 d-block mb-2 text-secondary"></i>
                        Tidak ada pemasok yang cocok dengan "<strong>${query}</strong>"
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

    // Edit Pemasok Modal Handler
    const modalEditPemEl = document.getElementById('modalEditPemasok');
    const formEditPem = document.getElementById('formEditPemasok');
    const editNamaPem = document.getElementById('edit_nama_pemasok');
    const editKontakPem = document.getElementById('edit_kontak_pemasok');
    const editAlamatPem = document.getElementById('edit_alamat_pemasok');

    document.querySelectorAll('.btn-edit-pemasok').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');
            const kontak = this.getAttribute('data-kontak');
            const alamat = this.getAttribute('data-alamat');

            if (formEditPem) {
                formEditPem.action = '<?= site_url("pemasok/update/"); ?>' + id;
            }
            if (editNamaPem) editNamaPem.value = nama || '';
            if (editKontakPem) editKontakPem.value = kontak || '';
            if (editAlamatPem) editAlamatPem.value = alamat || '';

            if (modalEditPemEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEditPemEl);
                modal.show();
            }
        });
    });
});
</script>

<!-- =========================================================
     MODAL 1: Tambah Pemasok Baru
     ========================================================= -->
<div class="modal fade" id="modalTambahPemasok" tabindex="-1" aria-labelledby="modalTambahPemasokLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahPemasokLabel">
                    <i class="bi bi-truck text-danger"></i> Tambah Pemasok Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <?= form_open('pemasok/store', ['id' => 'formTambahPemasok']); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pemasok / Badan Usaha <span class="text-danger">*</span></label>
                        <input type="text" name="nama_pemasok" class="form-control" placeholder="Contoh: PT. Sumber Makmur Perkasa" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor Telepon / Kontak <span class="text-danger">*</span></label>
                        <input type="text" name="kontak" class="form-control" placeholder="Contoh: 0812-3456-7890 / (021) 555-1234" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat kantor atau gudang suplai..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4 fw-semibold d-inline-flex align-items-center gap-1.5" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                        <i class="bi bi-save"></i> Simpan Pemasok
                    </button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL 2: Edit Pemasok
     ========================================================= -->
<div class="modal fade" id="modalEditPemasok" tabindex="-1" aria-labelledby="modalEditPemasokLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditPemasokLabel">
                    <i class="bi bi-pencil-square text-danger"></i> Edit Pemasok
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form method="POST" action="" id="formEditPemasok">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pemasok / Badan Usaha <span class="text-danger">*</span></label>
                        <input type="text" name="nama_pemasok" id="edit_nama_pemasok" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor Telepon / Kontak <span class="text-danger">*</span></label>
                        <input type="text" name="kontak" id="edit_kontak_pemasok" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Alamat Lengkap</label>
                        <textarea name="alamat" id="edit_alamat_pemasok" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4 fw-semibold d-inline-flex align-items-center gap-1.5" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                        <i class="bi bi-check-lg"></i> Perbarui Pemasok
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
