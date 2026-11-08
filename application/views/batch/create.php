<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary);">Registrasi Batch Baru</h2>
                <p class="text-muted small mb-0">Daftarkan lot barang fisik yang masuk gudang dengan penandaan tanggal dan QR.</p>
            </div>
            <a href="<?= site_url('batch'); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="mb-card p-4">
            <?= form_open('batch/store'); ?>
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label fw-semibold">Produk Terkait <span class="text-danger">*</span></label>
                        <select name="produk_id" class="form-select" required>
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p['id']; ?>"><?= html_escape($p['nama_produk']); ?> (SKU: <?= html_escape($p['sku']); ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Nomor Lot / Batch <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="nomor_batch" id="batch-input" class="form-control" value="<?= $lot_prefix . date('Ymd') . '-' . rand(100, 999); ?>" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="generateBatch()"><i class="bi bi-arrow-clockwise"></i></button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Jumlah Lot (Unit) <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah_awal" class="form-control" min="1" value="50" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal Masuk <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_masuk" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal Kadaluarsa (FEFO)</label>
                        <input type="date" name="tanggal_kadaluarsa" class="form-control">
                        <div class="form-text">Kosongkan jika non-perishable.</div>
                    </div>
                    <div class="col-12 text-end pt-3">
                        <a href="<?= site_url('batch'); ?>" class="btn btn-outline-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-danger px-4" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                            <i class="bi bi-qr-code me-1"></i> Registrasi & Generate QR
                        </button>
                    </div>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
function generateBatch() {
    const prefix = '<?= $lot_prefix; ?>';
    const dateStr = new Date().toISOString().slice(0, 10).replace(/-/g, '');
    const rand = Math.floor(100 + Math.random() * 900);
    document.getElementById('batch-input').value = prefix + dateStr + '-' + rand;
}
</script>
