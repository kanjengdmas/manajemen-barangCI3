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

<div class="mb-card mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th class="text-center" style="width: 150px;">Jumlah Produk</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-tags fs-2 d-block mb-2 text-secondary"></i>
                            Belum ada master kategori. Silakan tambahkan kategori baru.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($categories as $cat): ?>
                        <tr>
                            <td class="text-center text-muted fw-semibold"><?= $no++; ?></td>
                            <td class="fw-bold" style="color: var(--mb-text-primary);"><?= html_escape($cat['nama_kategori']); ?></td>
                            <td class="text-muted"><?= html_escape($cat['deskripsi'] ?? '-'); ?></td>
                            <td class="text-center">
                                <span class="badge bg-primary rounded-pill px-2.5 py-1">
                                    <?= number_format($cat['total_produk'] ?? 0); ?> Produk
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="<?= site_url('kategori/delete/' . $cat['id']); ?>" class="btn btn-sm btn-outline-danger" title="Hapus Kategori" onclick="return confirm('Hapus kategori ini? Produk terkait akan kehilangan relasi kategori.');">
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
