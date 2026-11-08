<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary);">Tambah Pemasok</h2>
                <p class="text-muted small mb-0">Daftarkan vendor atau supplier baru pengirim barang ke gudang.</p>
            </div>
            <a href="<?= site_url('pemasok'); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="mb-card p-4">
            <?= form_open('pemasok/store'); ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Pemasok / Badan Usaha <span class="text-danger">*</span></label>
                    <input type="text" name="nama_pemasok" class="form-control" placeholder="Contoh: PT. Sumber Makmur Perkasa" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor Telepon / Kontak <span class="text-danger">*</span></label>
                    <input type="text" name="kontak" class="form-control" placeholder="Contoh: 0812-3456-7890 / (021) 555-1234" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat kantor atau gudang suplai..."></textarea>
                </div>
                <div class="text-end">
                    <a href="<?= site_url('pemasok'); ?>" class="btn btn-outline-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-danger px-4" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
