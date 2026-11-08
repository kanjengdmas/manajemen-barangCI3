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

<div class="mb-card mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th style="width: 100px;">QR Code</th>
                    <th>Nama Produk</th>
                    <th>SKU</th>
                    <th>Kategori</th>
                    <th>Pemasok</th>
                    <th class="text-end">Stok Fisik</th>
                    <th class="text-end">Min. Stok</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                            Belum ada master data produk. Silakan tambahkan produk baru.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($products as $p): ?>
                        <tr>
                            <td class="text-center text-muted fw-semibold"><?= $no++; ?></td>
                            <td>
                                <?php if (!empty($p['qr_code']) && file_exists(FCPATH . $p['qr_code'])): ?>
                                    <img src="<?= base_url($p['qr_code']); ?>" alt="QR" class="img-thumbnail" style="width: 48px; height: 48px;">
                                <?php else: ?>
                                    <span class="badge bg-secondary">No QR</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold" style="color: var(--mb-text-primary);"><?= html_escape($p['nama_produk']); ?></div>
                                <?php if (!empty($p['deskripsi'])): ?>
                                    <div class="small text-muted text-truncate" style="max-width: 260px;"><?= html_escape($p['deskripsi']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td><code class="text-primary fw-semibold"><?= html_escape($p['sku']); ?></code></td>
                            <td><span class="badge bg-light text-dark border"><?= html_escape($p['nama_kategori'] ?? '-'); ?></span></td>
                            <td><?= html_escape($p['nama_pemasok'] ?? '-'); ?></td>
                            <td class="text-end fw-bold tabular-nums">
                                <?= number_format($p['stok']); ?>
                            </td>
                            <td class="text-end text-muted tabular-nums">
                                <?= number_format($p['stok_minimal']); ?>
                            </td>
                            <td class="text-center">
                                <?php if ($p['stok'] <= 0): ?>
                                    <span class="badge bg-danger">Habis</span>
                                <?php elseif ($p['stok'] <= $p['stok_minimal']): ?>
                                    <span class="badge bg-warning text-dark">Menipis</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Aman</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= site_url('produk/edit/' . $p['id']); ?>" class="btn btn-outline-secondary" title="Edit Produk">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= site_url('produk/delete/' . $p['id']); ?>" class="btn btn-outline-danger" title="Hapus Produk" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
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
