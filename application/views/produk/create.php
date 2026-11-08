<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary);">Tambah Produk Baru</h2>
                <p class="text-muted small mb-0">Lengkapi informasi produk untuk dimasukkan ke katalog inventaris gudang.</p>
            </div>
            <a href="<?= site_url('produk'); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="mb-card p-4">
            <?= form_open('produk/store'); ?>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Kertas HVS A4 80gr" required autofocus>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kode SKU <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="sku" id="sku-input" class="form-control" value="<?= $sku_prefix . strtoupper(substr(uniqid(), -6)); ?>" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="generateSku()"><i class="bi bi-arrow-clockwise"></i></button>
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
                        <div class="form-text">Dapat diisi 0 jika barang belum tersedia di gudang.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ambang Batas Minimal Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stok_minimal" class="form-control" min="0" value="<?= $default_min_stock; ?>" required>
                        <div class="form-text">Peringatan otomatis muncul jika stok di bawah angka ini.</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi / Spesifikasi Tambahan</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Informasi dimensi, berat, atau penempatan rak..."></textarea>
                    </div>
                    <div class="col-12 text-end pt-3">
                        <a href="<?= site_url('produk'); ?>" class="btn btn-outline-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-danger px-4" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                            <i class="bi bi-save me-1"></i> Simpan Produk
                        </button>
                    </div>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
function generateSku() {
    const prefix = '<?= $sku_prefix; ?>';
    const rand = Math.random().toString(36).substring(2, 8).toUpperCase();
    document.getElementById('sku-input').value = prefix + rand;
}
</script>
