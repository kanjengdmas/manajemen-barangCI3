<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary); letter-spacing: -0.01em;">
            Pengaturan Sistem & Pergudangan
        </h2>
        <p class="text-muted small mb-0">
            Konfigurasi operasional gudang, prefiks penomoran unik, identitas legal perusahaan, dan cadangan basis data.
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?= site_url('api/settings/backup-sql'); ?>" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold shadow-sm">
            <i class="bi bi-database-down"></i> Backup SQL 1-Click
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Konfigurasi Operasional Gudang -->
    <div class="col-12 col-lg-6">
        <div class="mb-card p-4 h-100">
            <h5 class="fw-bold mb-3" style="color: var(--mb-text-primary);">
                <i class="bi bi-sliders2-vertical text-primary me-2"></i>Parameter Operasional
            </h5>
            <form id="system-settings-form">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Default Ambang Minimal Stok</label>
                        <input type="number" name="default_min_stock" class="form-control" value="<?= $settings['default_min_stock']; ?>" min="1">
                        <div class="form-text">Batas peringatan restock awal.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kebijakan Keluar Barang</label>
                        <select name="fifo_policy" class="form-select">
                            <option value="1" <?= $settings['fifo_policy'] ? 'selected' : ''; ?>>Wajib FEFO / FIFO</option>
                            <option value="0" <?= !$settings['fifo_policy'] ? 'selected' : ''; ?>>Bebas / Manual</option>
                        </select>
                        <div class="form-text">Rekomendasikan batch kadaluarsa awal.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Awalan (Prefix) Kode SKU</label>
                        <input type="text" name="sku_prefix" class="form-control" value="<?= html_escape($settings['sku_prefix']); ?>">
                        <div class="form-text">Contoh: PRD-, ITM-, ART-</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Awalan (Prefix) Lot / Batch</label>
                        <input type="text" name="lot_prefix" class="form-control" value="<?= html_escape($settings['lot_prefix']); ?>">
                        <div class="form-text">Contoh: LOT-, BTH-, BOX-</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Format Tampilan Tanggal</label>
                        <select name="date_format" class="form-select">
                            <option value="DD/MM/YYYY" <?= $settings['date_format'] === 'DD/MM/YYYY' ? 'selected' : ''; ?>>DD/MM/YYYY (31/12/2026)</option>
                            <option value="YYYY-MM-DD" <?= $settings['date_format'] === 'YYYY-MM-DD' ? 'selected' : ''; ?>>YYYY-MM-DD (2026-12-31)</option>
                            <option value="DD MMM YYYY" <?= $settings['date_format'] === 'DD MMM YYYY' ? 'selected' : ''; ?>>DD MMM YYYY (31 Des 2026)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Efek Suara Beep Scanner</label>
                        <select name="sound_effects" class="form-select">
                            <option value="1" <?= $settings['sound_effects'] ? 'selected' : ''; ?>>Aktif (Web Audio Beep)</option>
                            <option value="0" <?= !$settings['sound_effects'] ? 'selected' : ''; ?>>Nonaktif (Senyap)</option>
                        </select>
                    </div>
                    <div class="col-12 text-end pt-3">
                        <button type="submit" class="btn btn-primary px-4 fw-semibold" id="btn-save-settings">
                            <i class="bi bi-save me-1"></i> Simpan Parameter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Profil Instansi & Legal Signatures -->
    <div class="col-12 col-lg-6">
        <div class="mb-card p-4 h-100">
            <h5 class="fw-bold mb-3" style="color: var(--mb-text-primary);">
                <i class="bi bi-building text-danger me-2"></i>Kop Surat & Identitas Perusahaan
            </h5>
            <form id="company-profile-form">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nama Badan Usaha / Gudang</label>
                        <input type="text" name="nama_perusahaan" class="form-control" value="<?= html_escape($company['nama_perusahaan'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Slogan / Tagline</label>
                        <input type="text" name="tagline" class="form-control" value="<?= html_escape($company['tagline'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nomor Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="<?= html_escape($company['telepon'] ?? ''); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat Lengkap</label>
                        <input type="text" name="alamat" class="form-control" value="<?= html_escape($company['alamat'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Penanggung Jawab (PIC)</label>
                        <input type="text" name="pic_name" class="form-control" value="<?= html_escape($company['pic_name'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jabatan PIC</label>
                        <input type="text" name="pic_jabatan" class="form-control" value="<?= html_escape($company['pic_jabatan'] ?? ''); ?>">
                    </div>
                    <div class="col-12 text-end pt-3">
                        <button type="submit" class="btn btn-danger px-4 fw-semibold" id="btn-save-company" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                            <i class="bi bi-save me-1"></i> Simpan Profil Legal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('system-settings-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-save-settings');
    btn.disabled = true;

    const formData = new FormData(this);
    const payload = {};
    formData.forEach((val, key) => { payload[key] = val; });

    fetch(window.BASE_URL + 'api/settings/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(res => {
        btn.disabled = false;
        if (res.status) {
            Swal.fire('Tersimpan', res.message, 'success');
        } else {
            Swal.fire('Gagal', res.message || 'Gagal menyimpan pengaturan', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
    });
});

document.getElementById('company-profile-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-save-company');
    btn.disabled = true;

    const formData = new FormData(this);
    const payload = {};
    formData.forEach((val, key) => { payload[key] = val; });

    fetch(window.BASE_URL + 'api/settings/company', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(res => {
        btn.disabled = false;
        if (res.status) {
            Swal.fire('Tersimpan', res.message, 'success');
        } else {
            Swal.fire('Gagal', res.message || 'Gagal menyimpan profil', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
    });
});
</script>
