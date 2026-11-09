<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary);">Edit Produk</h2>
                <p class="text-muted small mb-0">Perbarui spesifikasi produk <strong><?= html_escape($product['nama_produk']); ?></strong>.</p>
            </div>
            <a href="<?= site_url('produk'); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="mb-card p-4">
            <?= form_open('produk/update/' . $product['id']); ?>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="nama_produk" class="form-control" value="<?= html_escape($product['nama_produk']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kode SKU <span class="text-danger">*</span></label>
                        <input type="text" name="sku" class="form-control" value="<?= html_escape($product['sku']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id']; ?>" <?= $cat['id'] == $product['kategori_id'] ? 'selected' : ''; ?>>
                                    <?= html_escape($cat['nama_kategori']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Pemasok / Vendor</label>
                        <select name="pemasok_id" class="form-select">
                            <option value="">-- Tanpa Pemasok Tetap --</option>
                            <?php foreach ($suppliers as $sup): ?>
                                <option value="<?= $sup['id']; ?>" <?= $sup['id'] == $product['pemasok_id'] ? 'selected' : ''; ?>>
                                    <?= html_escape($sup['nama_pemasok']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Stok Fisik Saat Ini</label>
                        <input type="number" class="form-control bg-light" value="<?= $product['stok']; ?>" readonly disabled>
                        <div class="form-text">Perubahan stok fisik dilakukan melalui menu Transaksi Stok Masuk/Keluar.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ambang Batas Minimal Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stok_minimal" class="form-control" min="0" value="<?= $product['stok_minimal']; ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi / Spesifikasi Tambahan</label>
                        <textarea name="deskripsi" class="form-control" rows="3"><?= html_escape($product['deskripsi']); ?></textarea>
                    </div>
                    <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2 pt-3 border-top mt-2">
                        <a href="<?= site_url('produk'); ?>" class="btn btn-outline-secondary order-2 order-sm-1">Batal</a>
                        <button type="submit" class="btn btn-danger px-4 fw-semibold order-1 order-sm-2 d-inline-flex align-items-center justify-content-center gap-1.5" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                            <i class="bi bi-save"></i> Perbarui Produk
                        </button>
                    </div>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
