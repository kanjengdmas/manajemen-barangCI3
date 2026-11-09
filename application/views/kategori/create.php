<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary);">Tambah Kategori</h2>
                <p class="text-muted small mb-0">Buat klasifikasi kelompok baru untuk barang inventaris.</p>
            </div>
            <a href="<?= site_url('kategori'); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="mb-card p-4">
            <?= form_open('kategori/store'); ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Elektronik, ATK, Bahan Baku" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Keterangan singkat mengenai kategori ini..."></textarea>
                </div>
                <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 pt-3 border-top mt-2">
                    <a href="<?= site_url('kategori'); ?>" class="btn btn-outline-secondary order-2 order-sm-1">Batal</a>
                    <button type="submit" class="btn btn-danger px-4 fw-semibold order-1 order-sm-2 d-inline-flex align-items-center justify-content-center gap-1.5" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                        <i class="bi bi-save"></i> Simpan Kategori
                    </button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
