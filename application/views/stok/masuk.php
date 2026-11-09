<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary);">Pencatatan Stok Masuk</h2>
                <p class="text-muted small mb-0">Catat penambahan fisik barang yang diterima di gudang secara akurat.</p>
            </div>
            <a href="<?= site_url('stok'); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
            </a>
        </div>

        <div class="mb-card p-4">
            <?= form_open('stok/masuk_store'); ?>
                <input type="hidden" name="scan_mode" value="manual">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Pilih Produk <span class="text-danger">*</span></label>
                        <select name="produk_id" id="produk-select" class="form-select" required onchange="onProductChange()">
                            <option value="">-- Pilih Barang / Produk --</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p['id']; ?>" data-sku="<?= html_escape($p['sku']); ?>" data-stock="<?= $p['stok']; ?>">
                                    <?= html_escape($p['nama_produk']); ?> (SKU: <?= html_escape($p['sku']); ?>) — Stok Saat Ini: <?= number_format($p['stok']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-7">
                        <label class="form-label fw-semibold">Alokasi ke Lot / Batch Kardus</label>
                        <select name="batch_id" id="batch-select" class="form-select">
                            <option value="">-- Tanpa Batch / Masuk ke Stok Bebas --</option>
                        </select>
                        <div class="form-text">Pilih batch jika barang diterima dengan kemasan lot teridentifikasi.</div>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Jumlah Masuk (Unit) <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" class="form-control" min="1" value="10" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Keterangan / Nomor Surat Jalan</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Penerimaan PO #9910 dari supplier PT. Sumber Makmur..."></textarea>
                    </div>

                    <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2 pt-3 border-top mt-2">
                        <a href="<?= site_url('stok'); ?>" class="btn btn-outline-secondary order-2 order-sm-1">Batal</a>
                        <button type="submit" class="btn btn-success px-4 fw-semibold order-1 order-sm-2 d-inline-flex align-items-center justify-content-center gap-1.5">
                            <i class="bi bi-check-lg"></i> Simpan Stok Masuk
                        </button>
                    </div>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
function onProductChange() {
    const prodSelect = document.getElementById('produk-select');
    const batchSelect = document.getElementById('batch-select');
    const prodId = prodSelect.value;

    batchSelect.innerHTML = '<option value="">-- Memuat batch... --</option>';

    if (!prodId) {
        batchSelect.innerHTML = '<option value="">-- Tanpa Batch / Masuk ke Stok Bebas --</option>';
        return;
    }

    fetch(window.BASE_URL + 'api/batch/recommendations?produk_id=' + prodId)
        .then(res => res.json())
        .then(res => {
            let html = '<option value="">-- Tanpa Batch / Masuk ke Stok Bebas --</option>';
            if (res.status && res.data && res.data.length > 0) {
                res.data.forEach(b => {
                    html += `<option value="${b.id}">${b.nomor_batch} (Sisa saat ini: ${b.stok_saat_ini} unit)</option>`;
                });
            }
            batchSelect.innerHTML = html;
        })
        .catch(() => {
            batchSelect.innerHTML = '<option value="">-- Tanpa Batch / Masuk ke Stok Bebas --</option>';
        });
}
</script>
