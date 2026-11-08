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

<div class="mb-card mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th>Nama Pemasok / Vendor</th>
                    <th>Kontak / Telp</th>
                    <th>Alamat Operasional</th>
                    <th class="text-center" style="width: 150px;">Produk Dipasok</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($suppliers)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-truck fs-2 d-block mb-2 text-secondary"></i>
                            Belum ada master pemasok. Silakan tambahkan rekanan vendor baru.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($suppliers as $sup): ?>
                        <tr>
                            <td class="text-center text-muted fw-semibold"><?= $no++; ?></td>
                            <td class="fw-bold" style="color: var(--mb-text-primary);"><?= html_escape($sup['nama_pemasok']); ?></td>
                            <td><i class="bi bi-telephone text-muted me-1"></i><?= html_escape($sup['kontak'] ?? '-'); ?></td>
                            <td class="text-muted"><?= html_escape($sup['alamat'] ?? '-'); ?></td>
                            <td class="text-center">
                                <span class="badge bg-secondary rounded-pill px-2.5 py-1">
                                    <?= number_format($sup['total_produk'] ?? 0); ?> Item
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="<?= site_url('pemasok/delete/' . $sup['id']); ?>" class="btn btn-sm btn-outline-danger" title="Hapus Pemasok" onclick="return confirm('Hapus pemasok ini?');">
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
