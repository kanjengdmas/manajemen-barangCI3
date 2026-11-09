<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary);">Pencatatan Stok Keluar (FEFO / FIFO)</h2>
                <p class="text-muted small mb-0">Pengeluaran barang dari gudang dengan rekomendasi prioritas kadaluarsa.</p>
            </div>
            <a href="<?= site_url('stok'); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
            </a>
        </div>

        <div class="mb-card p-4">
            <?= form_open('stok/keluar_store'); ?>
                <input type="hidden" name="scan_mode" value="manual">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Pilih Produk <span class="text-danger">*</span></label>
                        <select name="produk_id" id="produk-select" class="form-select" required onchange="onProductKeluarChange()">
                            <option value="">-- Pilih Barang / Produk --</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p['id']; ?>" data-stock="<?= $p['stok']; ?>">
                                    <?= html_escape($p['nama_produk']); ?> (SKU: <?= html_escape($p['sku']); ?>) — Tersedia: <?= number_format($p['stok']); ?> unit
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Rekomendasi Batch FEFO Alert -->
                    <div class="col-12 d-none" id="fefo-recommendation-box">
                        <div class="alert alert-warning border border-warning-subtle rounded-3 p-3 mb-0">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-shield-exclamation text-warning fs-5"></i>
                                <span class="fw-bold">Rekomendasi Pengeluaran Batch (FEFO / FIFO)</span>
                            </div>
                            <div class="small text-muted mb-2">
                                Sistem merekomendasikan batch yang paling awal kadaluarsa atau paling awal masuk gudang:
                            </div>
                            <div id="batch-recommendations-list" class="d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <label class="form-label fw-semibold">Alokasi Pengurangan dari Batch</label>
                        <select name="batch_id" id="batch-select" class="form-select">
                            <option value="">-- Tanpa Batch Spesifik / Stok Bebas --</option>
                        </select>
                        <div class="form-text">Pilih batch tertentu untuk mengurangi stok lot bersangkutan.</div>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Jumlah Keluar (Unit) <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" id="jumlah-input" class="form-control" min="1" value="1" required>
                        <div class="form-text text-danger" id="stock-warning" style="display: none;">Jumlah melebihi stok yang tersedia!</div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Tujuan Pengeluaran / Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Distribusi order DO-1029 / Penjualan toko cabang..."></textarea>
                    </div>

                    <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2 pt-3 border-top mt-2">
                        <a href="<?= site_url('stok'); ?>" class="btn btn-outline-secondary order-2 order-sm-1">Batal</a>
                        <button type="submit" id="btn-submit" class="btn btn-danger px-4 fw-semibold order-1 order-sm-2 d-inline-flex align-items-center justify-content-center gap-1.5" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                            <i class="bi bi-box-arrow-up-right"></i> Simpan Stok Keluar
                        </button>
                    </div>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
function onProductKeluarChange() {
    const prodSelect = document.getElementById('produk-select');
    const batchSelect = document.getElementById('batch-select');
    const recBox = document.getElementById('fefo-recommendation-box');
    const recList = document.getElementById('batch-recommendations-list');
    const prodId = prodSelect.value;

    batchSelect.innerHTML = '<option value="">-- Memuat batch... --</option>';
    recBox.classList.add('d-none');
    recList.innerHTML = '';

    if (!prodId) {
        batchSelect.innerHTML = '<option value="">-- Tanpa Batch Spesifik / Stok Bebas --</option>';
        return;
    }

    fetch(window.BASE_URL + 'api/batch/recommendations?produk_id=' + prodId)
        .then(res => res.json())
        .then(res => {
            let html = '<option value="">-- Tanpa Batch Spesifik / Stok Bebas --</option>';
            if (res.status && res.data && res.data.length > 0) {
                recBox.classList.remove('d-none');
                res.data.forEach((b, idx) => {
                    const isFirst = idx === 0;
                    html += `<option value="${b.id}" ${isFirst ? 'selected' : ''}>${b.nomor_batch} (Sisa: ${b.stok_saat_ini} unit ${b.tanggal_kadaluarsa ? '| Exp: ' + b.tanggal_kadaluarsa : ''})</option>`;

                    const chip = document.createElement('button');
                    chip.type = 'button';
                    chip.className = `btn btn-sm ${isFirst ? 'btn-danger' : 'btn-outline-dark'} text-start`;
                    chip.innerHTML = `<span class="fw-bold">${b.nomor_batch}</span> (${b.stok_saat_ini} unit) ${b.tanggal_kadaluarsa ? '<small class="d-block">Exp: ' + b.tanggal_kadaluarsa + '</small>' : ''}`;
                    chip.onclick = function() {
                        batchSelect.value = b.id;
                    };
                    recList.appendChild(chip);
                });
            }
            batchSelect.innerHTML = html;
        })
        .catch(() => {
            batchSelect.innerHTML = '<option value="">-- Tanpa Batch Spesifik / Stok Bebas --</option>';
        });
}
</script>
