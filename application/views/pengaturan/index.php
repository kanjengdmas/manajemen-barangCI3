<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Header Area -->
<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary); letter-spacing: -0.01em;">
            Pengaturan Sistem &amp; Pergudangan
        </h2>
        <p class="text-muted small mb-0">
            Personalisasi antarmuka, identitas resmi perusahaan, kebijakan operasional FEFO/FIFO, dan pemeliharaan server.
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <div class="autosave-status-pill saved" id="global-save-status">
            <i class="bi bi-cloud-check-fill text-success" id="save-status-icon"></i>
            <span id="save-status-text">Semua Perubahan Tersimpan</span>
        </div>
        <a href="<?= site_url('api/settings/backup-sql'); ?>" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold shadow-sm" title="Unduh snapshot SQL database seketika">
            <i class="bi bi-database-down"></i> Backup SQL 1-Click
        </a>
    </div>
</div>

<!-- Mobile Quick Tab Selector (Smartphones & Tablets < 768px) -->
<div class="d-md-none mb-3">
    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.68rem; letter-spacing: 0.05em;">
        <i class="bi bi-segmented-nav me-1 text-danger"></i> Menu Pengaturan:
    </label>
    <div class="input-group shadow-sm">
        <span class="input-group-text border-subtle text-danger" style="background-color: var(--mb-card-raised); border-color: var(--mb-border);">
            <i class="bi bi-gear-wide-connected" id="mobile-tab-icon"></i>
        </span>
        <select class="form-select fw-semibold" id="mobile-settings-tab-dropdown" style="background-color: var(--mb-card); border-color: var(--mb-border); color: var(--mb-text-primary); font-size: 0.85rem;">
            <option value="tab-company" selected>🏢 Profil &amp; Kop Surat</option>
            <option value="tab-operations">⚙️ Parameter Operasional</option>
            <option value="tab-appearance">🎨 Tampilan &amp; Preferensi</option>
            <option value="tab-security">🛡️ Keamanan &amp; Sesi</option>
            <option value="tab-maintenance">💾 Cadangan &amp; Pemeliharaan</option>
        </select>
    </div>
</div>

<!-- Modern Settings Tab Bar -->
<div class="settings-tabs-container mb-4">
    <button type="button" class="settings-tab-btn active" data-tab="tab-company">
        <i class="bi bi-building"></i> Profil &amp; Kop Surat
    </button>
    <button type="button" class="settings-tab-btn" data-tab="tab-operations">
        <i class="bi bi-sliders2-vertical"></i> Parameter Operasional
    </button>
    <button type="button" class="settings-tab-btn" data-tab="tab-appearance">
        <i class="bi bi-palette"></i> Tampilan &amp; Preferensi
    </button>
    <button type="button" class="settings-tab-btn" data-tab="tab-security">
        <i class="bi bi-shield-check"></i> Keamanan &amp; Sesi
    </button>
    <button type="button" class="settings-tab-btn" data-tab="tab-maintenance">
        <i class="bi bi-hdd-stack"></i> Cadangan &amp; Pemeliharaan
    </button>
</div>

<!-- TAB 1: Profil Perusahaan & Kop Surat Dokumen -->
<div class="settings-tab-content active" id="tab-company">
    <div class="row g-4">
        <!-- Form Sisi Kiri -->
        <div class="col-12 col-xl-7">
            <div class="settings-section-card h-100">
                <div class="settings-section-header">
                    <div>
                        <div class="settings-section-title">
                            <i class="bi bi-building text-danger"></i> Identitas Badan Usaha
                        </div>
                        <p class="settings-section-subtitle">
                            Data ini otomatis disematkan pada kop surat laporan PDF, bukti serah terima stok, dan surat jalan.
                        </p>
                    </div>
                </div>

                <div class="p-4">
                    <form id="company-profile-form">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Nama Badan Usaha / PT / Gudang</label>
                                <input type="text" name="nama_perusahaan" id="inp-comp-name" class="form-control"
                                       value="<?= html_escape($company['nama_perusahaan'] ?? ''); ?>" required placeholder="Contoh: PT. Gudang Solusi Indonesia">
                            </div>
                            <div class="col-md-7">
                                <label class="form-label fw-semibold small">Slogan / Tagline Bisnis</label>
                                <input type="text" name="tagline" id="inp-comp-tagline" class="form-control"
                                       value="<?= html_escape($company['tagline'] ?? ''); ?>" placeholder="Sistem Pergudangan & Inventaris Terpadu">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold small">Nomor Telepon / WhatsApp</label>
                                <input type="text" name="telepon" id="inp-comp-phone" class="form-control"
                                       value="<?= html_escape($company['telepon'] ?? ''); ?>" placeholder="021-5550192">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Alamat Lengkap Operasional</label>
                                <textarea name="alamat" id="inp-comp-address" class="form-control" rows="2"
                                          placeholder="Jl. Kawasan Industri No. 45, Jakarta Pusat"><?= html_escape($company['alamat'] ?? ''); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Alamat Email Resmi</label>
                                <input type="email" name="email" id="inp-comp-email" class="form-control"
                                       value="<?= html_escape($company['email'] ?? 'admin@gudangsolusi.id'); ?>" placeholder="admin@perusahaan.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Catatan Kaki Dokumen (Footer Note)</label>
                                <input type="text" name="footer_note" id="inp-comp-footer" class="form-control"
                                       value="<?= html_escape($company['footer_note'] ?? ''); ?>" placeholder="Dokumen sah diterbitkan secara elektronik.">
                            </div>
                            <div class="col-12 pt-2">
                                <hr class="border-subtle my-2">
                                <h6 class="fw-bold mb-3 small" style="color: var(--mb-text-primary);">
                                    <i class="bi bi-person-badge text-primary me-1"></i> Pejabat Penanggung Jawab (PIC / Penandatangan Dokumen)
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Nama Lengkap PIC</label>
                                <input type="text" name="pic_name" id="inp-comp-pic" class="form-control"
                                       value="<?= html_escape($company['pic_name'] ?? ''); ?>" placeholder="Nama Kepala Gudang">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Jabatan PIC</label>
                                <input type="text" name="pic_jabatan" id="inp-comp-jabatan" class="form-control"
                                       value="<?= html_escape($company['pic_jabatan'] ?? ''); ?>" placeholder="Kepala Gudang & Logistik">
                            </div>

                            <div class="col-12 text-end pt-3">
                                <button type="submit" class="btn btn-danger px-4 fw-semibold d-inline-flex align-items-center gap-2" id="btn-save-company" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                                    <i class="bi bi-check-circle" id="btn-comp-icon"></i>
                                    <span id="btn-comp-text">Simpan Profil &amp; Kop Surat</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Live Kop Surat Preview Sisi Kanan -->
        <div class="col-12 col-xl-5">
            <div class="settings-section-card h-100">
                <div class="settings-section-header">
                    <div>
                        <div class="settings-section-title">
                            <i class="bi bi-file-earmark-text text-primary"></i> Preview Kop Surat Resmi
                        </div>
                        <p class="settings-section-subtitle">Tampilan cetak dokumen real-time saat data diisi</p>
                    </div>
                    <span class="badge-soft badge-soft-primary" style="font-size: 0.68rem;">Live Render</span>
                </div>
                <div class="p-4">
                    <!-- Miniature Document Card -->
                    <div class="kop-surat-preview-card">
                        <div class="kop-header-preview">
                            <div style="flex: 1;">
                                <div class="kop-title-preview" id="preview-nama">
                                    <?= html_escape(!empty($company['nama_perusahaan']) ? $company['nama_perusahaan'] : 'PT. GUDANG SOLUSI INDONESIA'); ?>
                                </div>
                                <div class="kop-sub-preview fw-semibold" id="preview-tagline">
                                    <?= html_escape(!empty($company['tagline']) ? $company['tagline'] : 'Sistem Pergudangan & Manajemen Inventaris Terpadu'); ?>
                                </div>
                                <div class="kop-sub-preview" id="preview-alamat">
                                    <i class="bi bi-geo-alt me-1"></i><?= html_escape(!empty($company['alamat']) ? $company['alamat'] : 'Jl. Kawasan Industri No. 45, Jakarta'); ?>
                                </div>
                                <div class="kop-sub-preview" id="preview-kontak">
                                    <i class="bi bi-telephone me-1"></i><?= html_escape(!empty($company['telepon']) ? $company['telepon'] : '021-5550192'); ?> · <?= html_escape(!empty($company['email']) ? $company['email'] : 'admin@gudangsolusi.id'); ?>
                                </div>
                            </div>
                            <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                                 style="width: 44px; height: 44px; background: linear-gradient(135deg, #ef4444, #b91c1c); flex-shrink: 0;">
                                <i class="bi bi-box-seam-fill fs-5"></i>
                            </div>
                        </div>

                        <!-- Dummy Document Body Mockup -->
                        <div class="text-center my-3 py-2 border-bottom border-subtle">
                            <span class="fw-bold small text-uppercase" style="letter-spacing: 0.08em;">Surat Bukti Serah Terima Barang</span>
                            <div class="text-muted" style="font-size: 0.68rem; font-family: var(--mb-font-mono);">No: BUKTI/<?= date('Y/m'); ?>/001 · Tanggal: <?= date('d M Y'); ?></div>
                        </div>

                        <div class="small text-muted py-2" style="font-size: 0.72rem; line-height: 1.5;">
                            Barang inventaris telah diperiksa fisik dan dicatat dalam audit trail sistem manajemen persediaan.
                        </div>

                        <!-- Signature Block -->
                        <div class="kop-sig-row">
                            <div class="kop-sig-box">
                                <div class="text-muted" style="font-size: 0.7rem;">Kepala Gudang / PIC:</div>
                                <div class="kop-sig-line"></div>
                                <div class="fw-bold small" id="preview-pic">
                                    <?= html_escape(!empty($company['pic_name']) ? $company['pic_name'] : 'Dimas Rizqi'); ?>
                                </div>
                                <div class="text-muted" style="font-size: 0.68rem;" id="preview-jabatan">
                                    <?= html_escape(!empty($company['pic_jabatan']) ? $company['pic_jabatan'] : 'Kepala Gudang & Operasional'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info py-2 px-3 mt-3 mb-0 small d-flex align-items-center gap-2 rounded-3" style="font-size: 0.76rem;">
                        <i class="bi bi-info-circle-fill flex-shrink-0"></i>
                        <div>Kop surat ini digunakan secara dinamis saat mencetak surat jalan atau ekspor PDF laporan.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TAB 2: Parameter Pergudangan & Logistik -->
<div class="settings-tab-content d-none" id="tab-operations">
    <div class="settings-section-card">
        <div class="settings-section-header">
            <div>
                <div class="settings-section-title">
                    <i class="bi bi-sliders2-vertical text-danger"></i> Logika Stok &amp; Penomoran Unik
                </div>
                <p class="settings-section-subtitle">Standarisasi kode inventaris, kebijakan rotasi FIFO, dan penataan alokasi barang</p>
            </div>
        </div>

        <div class="settings-section-body">
            <!-- Setting Row: FIFO Policy -->
            <div class="settings-row">
                <div class="settings-row-info">
                    <div class="settings-row-title">
                        Kebijakan Pengeluaran Barang (FIFO)
                        <span class="badge-soft badge-soft-success" style="font-size: 0.66rem;">Direkomendasikan</span>
                    </div>
                    <p class="settings-row-desc">
                        Wajibkan sistem merekomendasikan batch dengan tanggal kedatangan paling awal (First In, First Out) saat mutasi keluar.
                    </p>
                </div>
                <div class="settings-row-control">
                    <label class="ios-switch">
                        <input type="checkbox" id="set_fifo_policy" <?= $settings['fifo_policy'] ? 'checked' : ''; ?> onchange="autoSaveSingleSetting('fifo_policy', this.checked ? 1 : 0)">
                        <span class="ios-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Setting Row: Default Min Stock -->
            <div class="settings-row">
                <div class="settings-row-info">
                    <div class="settings-row-title">Default Ambang Stok Minimal Produk Baru</div>
                    <p class="settings-row-desc">
                        Nilai batas minimal otomatis saat operator mendaftarkan produk baru tanpa harus mengetik manual.
                    </p>
                </div>
                <div class="settings-row-control">
                    <select id="set_default_min_stock" class="form-select form-select-sm" onchange="autoSaveSingleSetting('default_min_stock', this.value)">
                        <option value="5" <?= $settings['default_min_stock'] == 5 ? 'selected' : ''; ?>>5 Unit (Kritis Cepat)</option>
                        <option value="10" <?= $settings['default_min_stock'] == 10 ? 'selected' : ''; ?>>10 Unit (Standar Default)</option>
                        <option value="20" <?= $settings['default_min_stock'] == 20 ? 'selected' : ''; ?>>20 Unit (Gudang Menengah)</option>
                        <option value="50" <?= $settings['default_min_stock'] == 50 ? 'selected' : ''; ?>>50 Unit (Volume Besar)</option>
                    </select>
                </div>
            </div>

            <!-- Setting Row: SKU Prefix -->
            <div class="settings-row">
                <div class="settings-row-info">
                    <div class="settings-row-title">Awalan (Prefix) Kode SKU Barang</div>
                    <p class="settings-row-desc">
                        Format awalan kode SKU barang baru (misal: <code>PRD-</code> menghasilkan SKU: <code>PRD-0001</code>).
                    </p>
                </div>
                <div class="settings-row-control">
                    <input type="text" id="set_sku_prefix" class="form-control form-control-sm font-monospace"
                           value="<?= html_escape($settings['sku_prefix']); ?>" placeholder="PRD-" onblur="autoSaveSingleSetting('sku_prefix', this.value)">
                </div>
            </div>

            <!-- Setting Row: Lot Prefix -->
            <div class="settings-row">
                <div class="settings-row-info">
                    <div class="settings-row-title">Awalan (Prefix) Nomor Lot &amp; Batch</div>
                    <p class="settings-row-desc">
                        Format awalan kode kardus kedatangan fisik (misal: <code>LOT-</code> menghasilkan: <code>LOT-2026-001</code>).
                    </p>
                </div>
                <div class="settings-row-control">
                    <input type="text" id="set_lot_prefix" class="form-control form-control-sm font-monospace"
                           value="<?= html_escape($settings['lot_prefix']); ?>" placeholder="LOT-" onblur="autoSaveSingleSetting('lot_prefix', this.value)">
                </div>
            </div>

            <!-- Setting Row: FIFO Aging Threshold -->
            <div class="settings-row">
                <div class="settings-row-info">
                    <div class="settings-row-title">Prioritas Usia Simpan Batch di Rak (FIFO Aging)</div>
                    <p class="settings-row-desc">
                        Ambang batas hari penyimpanan sebelum batch diprioritaskan untuk segera dikeluarkan di dashboard.
                    </p>
                </div>
                <div class="settings-row-control">
                    <select id="set_expiry_warning_days" class="form-select form-select-sm" onchange="autoSaveSingleSetting('expiry_warning_days', this.value)">
                        <option value="7" <?= $settings['expiry_warning_days'] == 7 ? 'selected' : ''; ?>>7 Hari di Gudang</option>
                        <option value="14" <?= $settings['expiry_warning_days'] == 14 ? 'selected' : ''; ?>>14 Hari di Gudang</option>
                        <option value="30" <?= $settings['expiry_warning_days'] == 30 ? 'selected' : ''; ?>>30 Hari (Standar Rotasi)</option>
                        <option value="60" <?= $settings['expiry_warning_days'] == 60 ? 'selected' : ''; ?>>60 Hari di Gudang</option>
                    </select>
                </div>
            </div>

            <!-- Setting Row: QR Label Printing Size -->
            <div class="settings-row">
                <div class="settings-row-info">
                    <div class="settings-row-title">Ukuran Standar Cetak Stiker Label QR Barcode</div>
                    <p class="settings-row-desc">
                        Dimensi default saat membuka dialog print thermal kertas barcode fisik produk dan batch.
                    </p>
                </div>
                <div class="settings-row-control">
                    <select id="set_qr_label_size" class="form-select form-select-sm" onchange="autoSaveSingleSetting('qr_label_size', this.value)">
                        <option value="30x20" <?= $settings['qr_label_size'] === '30x20' ? 'selected' : ''; ?>>Kecil (30 × 20 mm)</option>
                        <option value="50x30" <?= $settings['qr_label_size'] === '50x30' ? 'selected' : ''; ?>>Sedang (50 × 30 mm - Rekomendasi)</option>
                        <option value="70x40" <?= $settings['qr_label_size'] === '70x40' ? 'selected' : ''; ?>>Besar / Kardus Rak (70 × 40 mm)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TAB 3: Tampilan & Preferensi Pengguna -->
<div class="settings-tab-content d-none" id="tab-appearance">
    <!-- General Display Preferences Card -->
    <div class="settings-section-card">
        <div class="settings-section-header">
            <div>
                <div class="settings-section-title">
                    <i class="bi bi-sliders text-primary"></i> Personalisasi Pengalaman Pengguna
                </div>
                <p class="settings-section-subtitle">Pengaturan format tanggal, privasi inventaris, dan audio feedback</p>
            </div>
        </div>

        <div class="settings-section-body">
            <!-- Format Tanggal -->
            <div class="settings-row">
                <div class="settings-row-info">
                    <div class="settings-row-title">Format Tampilan Tanggal Global</div>
                    <p class="settings-row-desc">Standar format waktu pada tabel transaksi, laporan, dan kartu batch.</p>
                </div>
                <div class="settings-row-control">
                    <select id="set_date_format" class="form-select form-select-sm" onchange="autoSaveSingleSetting('date_format', this.value)">
                        <option value="DD/MM/YYYY" <?= $settings['date_format'] === 'DD/MM/YYYY' ? 'selected' : ''; ?>>DD/MM/YYYY (31/12/2026)</option>
                        <option value="YYYY-MM-DD" <?= $settings['date_format'] === 'YYYY-MM-DD' ? 'selected' : ''; ?>>YYYY-MM-DD (2026-12-31 - ISO)</option>
                        <option value="DD MMMM YYYY" <?= $settings['date_format'] === 'DD MMMM YYYY' ? 'selected' : ''; ?>>DD MMMM YYYY (31 Des 2026)</option>
                    </select>
                </div>
            </div>

            <!-- Auto Refresh Dashboard -->
            <div class="settings-row">
                <div class="settings-row-info">
                    <div class="settings-row-title">Penyegaran Otomatis Dashboard (Silent Polling)</div>
                    <p class="settings-row-desc">Perbarui grafik mutasi dan kartu KPI di latar belakang tanpa reload halaman.</p>
                </div>
                <div class="settings-row-control d-flex align-items-center gap-2">
                    <select id="set_refresh_interval" class="form-select form-select-sm" style="max-width: 130px;" onchange="autoSaveSingleSetting('refresh_interval', this.value)">
                        <option value="1" <?= $settings['refresh_interval'] == 1 ? 'selected' : ''; ?>>Setiap 1 Mnt</option>
                        <option value="3" <?= $settings['refresh_interval'] == 3 ? 'selected' : ''; ?>>Setiap 3 Mnt</option>
                        <option value="5" <?= $settings['refresh_interval'] == 5 ? 'selected' : ''; ?>>Setiap 5 Mnt</option>
                        <option value="10" <?= $settings['refresh_interval'] == 10 ? 'selected' : ''; ?>>Setiap 10 Mnt</option>
                    </select>
                    <label class="ios-switch">
                        <input type="checkbox" id="set_auto_refresh" <?= $settings['auto_refresh'] ? 'checked' : ''; ?> onchange="autoSaveSingleSetting('auto_refresh', this.checked ? 1 : 0)">
                        <span class="ios-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Privasi Stok Masking -->
            <div class="settings-row">
                <div class="settings-row-info">
                    <div class="settings-row-title">Tampilkan Angka Stok (Stock Privacy Masking)</div>
                    <p class="settings-row-desc">Jika dinonaktifkan, angka stok pada KPI dashboard disamarkan menjadi <code>••••••</code> untuk privasi saat presentasi.</p>
                </div>
                <div class="settings-row-control">
                    <label class="ios-switch">
                        <input type="checkbox" id="set_show_stock" <?= $settings['show_stock'] ? 'checked' : ''; ?> onchange="toggleStockPrivacy(this.checked)">
                        <span class="ios-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Efek Suara Scanner -->
            <div class="settings-row">
                <div class="settings-row-info">
                    <div class="settings-row-title">Efek Suara Audio Scanner (Beep 880Hz)</div>
                    <p class="settings-row-desc">Bunyikan nada konfirmasi saat kode QR fisik barang sukses terbaca kamera.</p>
                </div>
                <div class="settings-row-control d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-2.5" onclick="testScannerBeep()" title="Uji suara scanner">
                        <i class="bi bi-volume-up"></i> Test Beep
                    </button>
                    <label class="ios-switch">
                        <input type="checkbox" id="set_sound_effects" <?= $settings['sound_effects'] ? 'checked' : ''; ?> onchange="autoSaveSingleSetting('sound_effects', this.checked ? 1 : 0)">
                        <span class="ios-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Kerapatan Baris Tabel -->
            <div class="settings-row">
                <div class="settings-row-info">
                    <div class="settings-row-title">Kerapatan Baris Data Tabel (Row Density)</div>
                    <p class="settings-row-desc">Pilih jarak padding sel tabel untuk kenyamanan membaca atau kepadatan data tinggi.</p>
                </div>
                <div class="settings-row-control">
                    <select id="set_row_density" class="form-select form-select-sm" onchange="autoSaveSingleSetting('row_density', this.value)">
                        <option value="normal" <?= $settings['row_density'] === 'normal' ? 'selected' : ''; ?>>Normal / Nyaman (Standar)</option>
                        <option value="compact" <?= $settings['row_density'] === 'compact' ? 'selected' : ''; ?>>Padat / Ringkas (Compact View)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TAB 4: Keamanan & Sesi -->
<div class="settings-tab-content d-none" id="tab-security">
    <div class="settings-section-card">
        <div class="settings-section-header">
            <div>
                <div class="settings-section-title">
                    <i class="bi bi-shield-check text-success"></i> Kebijakan Keamanan &amp; Sesi
                </div>
                <p class="settings-section-subtitle">Manajemen durasi login administrator dan status enkripsi transaksi</p>
            </div>
        </div>

        <div class="settings-section-body">
            <!-- Sesi Timeout -->
            <div class="settings-row">
                <div class="settings-row-info">
                    <div class="settings-row-title">Batas Waktu Ketidakaktifan Sesi (Session Timeout)</div>
                    <p class="settings-row-desc">Sesi administrator otomatis keluar jika tidak ada aktivitas dalam rentang waktu terpilih.</p>
                </div>
                <div class="settings-row-control">
                    <select id="set_session_timeout" class="form-select form-select-sm" onchange="autoSaveSingleSetting('session_timeout', this.value)">
                        <option value="30" <?= $settings['session_timeout'] == 30 ? 'selected' : ''; ?>>30 Menit</option>
                        <option value="60" <?= $settings['session_timeout'] == 60 ? 'selected' : ''; ?>>60 Menit (1 Jam)</option>
                        <option value="120" <?= $settings['session_timeout'] == 120 ? 'selected' : ''; ?>>120 Menit (2 Jam - Standar)</option>
                        <option value="480" <?= $settings['session_timeout'] == 480 ? 'selected' : ''; ?>>480 Menit (8 Jam / 1 Shift)</option>
                    </select>
                </div>
            </div>

            <!-- CSRF Protection Status -->
            <div class="settings-row">
                <div class="settings-row-info">
                    <div class="settings-row-title">Perlindungan Serangan CSRF (Cross-Site Request Forgery)</div>
                    <p class="settings-row-desc">Token unik SHA-256 dibuat otomatis pada setiap interaksi formulir dan AJAX.</p>
                </div>
                <div class="settings-row-control">
                    <span class="badge-soft badge-soft-success">
                        <span class="badge-dot bg-success"></span> SHA-256 Aktif &amp; Terproteksi
                    </span>
                </div>
            </div>

            <!-- Session Engine -->
            <div class="settings-row">
                <div class="settings-row-info">
                    <div class="settings-row-title">Mesin Penyimpanan Sesi (Session Driver)</div>
                    <p class="settings-row-desc">Penyimpanan sesi terenkripsi pada direktori aman server lokal.</p>
                </div>
                <div class="settings-row-control">
                    <span class="badge-soft badge-soft-secondary font-monospace">
                        files_driver · C:\xampp\tmp
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TAB 5: Cadangan & Pemeliharaan Sistem -->
<div class="settings-tab-content d-none" id="tab-maintenance">
    <div class="row g-4">
        <!-- Cadangan Database -->
        <div class="col-12 col-lg-6">
            <div class="settings-section-card h-100">
                <div class="settings-section-header">
                    <div>
                        <div class="settings-section-title">
                            <i class="bi bi-database text-primary"></i> Cadangan Basis Data (Backup)
                        </div>
                        <p class="settings-section-subtitle">Ekspor cadangan data inventaris untuk proteksi risiko bencana</p>
                    </div>
                </div>
                <div class="p-4 d-flex flex-column gap-3">
                    <div class="p-3 rounded-3 border border-subtle" style="background-color: var(--mb-card-raised);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="fw-bold" style="color: var(--mb-text-primary);"><i class="bi bi-filetype-sql text-danger me-1.5"></i> Full Database SQL Dump</div>
                            <span class="badge-soft badge-soft-danger" style="font-size: 0.68rem;">1-Click</span>
                        </div>
                        <p class="text-muted small mb-3">Unduh seluruh skema tabel, relasi, dan record data ke file <code>.sql</code> standar MySQL.</p>
                        <a href="<?= site_url('api/settings/backup-sql'); ?>" class="btn btn-sm btn-outline-danger w-100 fw-semibold">
                            <i class="bi bi-download me-1"></i> Unduh File Backup SQL
                        </a>
                    </div>

                    <div class="p-3 rounded-3 border border-subtle" style="background-color: var(--mb-card-raised);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="fw-bold" style="color: var(--mb-text-primary);"><i class="bi bi-filetype-json text-warning me-1.5"></i> Snapshot JSON Inventaris</div>
                            <span class="badge-soft badge-soft-secondary" style="font-size: 0.68rem;">Portable</span>
                        </div>
                        <p class="text-muted small mb-3">Ekspor seluruh entitas katalog, batch, dan log transaksi ke file <code>.json</code> terstruktur.</p>
                        <a href="<?= site_url('api/settings/backup'); ?>" class="btn btn-sm btn-outline-secondary w-100 fw-semibold">
                            <i class="bi bi-box-arrow-down me-1"></i> Unduh Snapshot JSON
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pembersihan Cache & Diagnostik -->
        <div class="col-12 col-lg-6">
            <div class="settings-section-card h-100">
                <div class="settings-section-header">
                    <div>
                        <div class="settings-section-title">
                            <i class="bi bi-cpu text-danger"></i> Cache &amp; Lingkungan Server
                        </div>
                        <p class="settings-section-subtitle">Pengosongan memori sementara dan metrik spesifikasi server</p>
                    </div>
                </div>
                <div class="p-4">
                    <!-- Clear Cache Action Box -->
                    <div class="p-3 mb-4 rounded-3 border border-subtle d-flex align-items-center justify-content-between gap-3" style="background-color: var(--mb-card-raised);">
                        <div>
                            <div class="fw-bold" style="color: var(--mb-text-primary);">Pembersihan Cache Query &amp; View</div>
                            <div class="text-muted small">Segarkan memori cache sistem jika terjadi jeda data.</div>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger px-3 flex-shrink-0" id="btn-clear-cache" onclick="executeClearCache()" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                            <i class="bi bi-trash3 me-1" id="icon-clear-cache"></i> Kosongkan Cache
                        </button>
                    </div>

                    <!-- Environment Health Metrics Grid -->
                    <h6 class="fw-bold small mb-2 text-uppercase text-muted" style="letter-spacing: 0.05em;">Spesifikasi Mesin Server</h6>
                    <div class="diag-metric-grid">
                        <div class="diag-metric-box">
                            <span class="diag-metric-lbl">Versi PHP</span>
                            <span class="diag-metric-val"><?= html_escape($server_info['php_version']); ?></span>
                        </div>
                        <div class="diag-metric-box">
                            <span class="diag-metric-lbl">Framework Core</span>
                            <span class="diag-metric-val">CI v<?= html_escape($server_info['ci_version']); ?></span>
                        </div>
                        <div class="diag-metric-box">
                            <span class="diag-metric-lbl">Platform Database</span>
                            <span class="diag-metric-val"><?= html_escape(ucfirst($server_info['db_platform'])); ?> (<?= html_escape($server_info['db_name']); ?>)</span>
                        </div>
                        <div class="diag-metric-box">
                            <span class="diag-metric-lbl">Alokasi Memori</span>
                            <span class="diag-metric-val"><?= html_escape($server_info['memory_limit']); ?></span>
                        </div>
                        <div class="diag-metric-box">
                            <span class="diag-metric-lbl">Batas Maks. Eksekusi</span>
                            <span class="diag-metric-val"><?= html_escape($server_info['max_execution_time']); ?></span>
                        </div>
                        <div class="diag-metric-box">
                            <span class="diag-metric-lbl">Batas Upload File</span>
                            <span class="diag-metric-val"><?= html_escape($server_info['upload_max_filesize']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Unified Tab Switching System (Mobile Dropdown + Desktop/Mobile Swipe Bar)
    const tabBtns = document.querySelectorAll('.settings-tab-btn');
    const tabContents = document.querySelectorAll('.settings-tab-content');
    const mobileDropdown = document.getElementById('mobile-settings-tab-dropdown');

    function switchSettingsTab(targetId) {
        tabBtns.forEach(btn => {
            const isMatch = btn.getAttribute('data-tab') === targetId;
            btn.classList.toggle('active', isMatch);
            if (isMatch) {
                btn.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            }
        });

        tabContents.forEach(content => {
            if (content.id === targetId) {
                content.classList.remove('d-none');
                content.classList.add('active');
            } else {
                content.classList.add('d-none');
                content.classList.remove('active');
            }
        });

        if (mobileDropdown && mobileDropdown.value !== targetId) {
            mobileDropdown.value = targetId;
        }

        if (window.history && window.history.replaceState) {
            window.history.replaceState(null, null, '#' + targetId);
        }
    }

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-tab');
            switchSettingsTab(targetId);
        });
    });

    if (mobileDropdown) {
        mobileDropdown.addEventListener('change', function() {
            switchSettingsTab(this.value);
        });
    }

    // URL Hash deep-linking on page load (e.g., #tab-appearance)
    const initialHash = window.location.hash.replace('#', '');
    if (initialHash && document.getElementById(initialHash)) {
        switchSettingsTab(initialHash);
    }

    // 2. Real-Time Live Kop Surat Sync
    const inpNama = document.getElementById('inp-comp-name');
    const inpTagline = document.getElementById('inp-comp-tagline');
    const inpPhone = document.getElementById('inp-comp-phone');
    const inpEmail = document.getElementById('inp-comp-email');
    const inpAddress = document.getElementById('inp-comp-address');
    const inpPic = document.getElementById('inp-comp-pic');
    const inpJabatan = document.getElementById('inp-comp-jabatan');

    const prevNama = document.getElementById('preview-nama');
    const prevTagline = document.getElementById('preview-tagline');
    const prevKontak = document.getElementById('preview-kontak');
    const prevAlamat = document.getElementById('preview-alamat');
    const prevPic = document.getElementById('preview-pic');
    const prevJabatan = document.getElementById('preview-jabatan');

    function syncKopPreview() {
        if (inpNama && prevNama) prevNama.textContent = (inpNama.value.trim() || 'PT. GUDANG SOLUSI INDONESIA').toUpperCase();
        if (inpTagline && prevTagline) prevTagline.textContent = inpTagline.value.trim() || 'Sistem Pergudangan & Manajemen Inventaris Terpadu';
        if (inpAddress && prevAlamat) prevAlamat.innerHTML = '<i class="bi bi-geo-alt me-1"></i>' + (inpAddress.value.trim() || 'Jl. Kawasan Industri No. 45, Jakarta');
        if (prevKontak) {
            const phoneVal = (inpPhone ? inpPhone.value.trim() : '') || '021-5550192';
            const emailVal = (inpEmail ? inpEmail.value.trim() : '') || 'admin@gudangsolusi.id';
            prevKontak.innerHTML = '<i class="bi bi-telephone me-1"></i>' + phoneVal + ' · ' + emailVal;
        }
        if (inpPic && prevPic) prevPic.textContent = inpPic.value.trim() || 'Dimas Rizqi';
        if (inpJabatan && prevJabatan) prevJabatan.textContent = inpJabatan.value.trim() || 'Kepala Gudang & Operasional';
    }

    [inpNama, inpTagline, inpPhone, inpEmail, inpAddress, inpPic, inpJabatan].forEach(el => {
        if (el) el.addEventListener('input', syncKopPreview);
    });

    // 3. Company Profile Submit Form Handler
    const compForm = document.getElementById('company-profile-form');
    const btnSaveComp = document.getElementById('btn-save-company');
    const btnCompText = document.getElementById('btn-comp-text');
    const btnCompIcon = document.getElementById('btn-comp-icon');

    if (compForm) {
        compForm.addEventListener('submit', function(e) {
            e.preventDefault();
            btnSaveComp.disabled = true;
            btnCompText.textContent = 'Menyimpan...';
            btnCompIcon.className = 'spinner-border spinner-border-sm';

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
                btnSaveComp.disabled = false;
                btnCompText.textContent = 'Tersimpan';
                btnCompIcon.className = 'bi bi-check-circle-fill text-success';
                triggerAutoSaveStatus(true, 'Profil & Kop Surat Berhasil Disimpan');
                setTimeout(() => {
                    btnCompText.textContent = 'Simpan Profil & Kop Surat';
                    btnCompIcon.className = 'bi bi-check-circle';
                }, 2000);
            })
            .catch(err => {
                console.error(err);
                btnSaveComp.disabled = false;
                btnCompText.textContent = 'Simpan Profil & Kop Surat';
                btnCompIcon.className = 'bi bi-check-circle';
                triggerAutoSaveStatus(false, 'Gagal Menyimpan Profil');
            });
        });
    }
});

// Auto-Save Single Setting Helper
function autoSaveSingleSetting(key, value) {
    triggerAutoSaveStatus(null, 'Menyimpan perubahan...');
    const payload = {};
    payload[key] = value;

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
        if (res.status) {
            triggerAutoSaveStatus(true, 'Perubahan Tersimpan Otomatis');
        } else {
            triggerAutoSaveStatus(false, 'Gagal Menyimpan');
        }
    })
    .catch(err => {
        console.error(err);
        triggerAutoSaveStatus(false, 'Koneksi Error');
    });
}

// Auto-Save Status Pill Animation
function triggerAutoSaveStatus(success, text) {
    const pill = document.getElementById('global-save-status');
    const icon = document.getElementById('save-status-icon');
    const label = document.getElementById('save-status-text');
    if (!pill || !label || !icon) return;

    if (success === null) {
        pill.className = 'autosave-status-pill saving';
        icon.className = 'spinner-border spinner-border-sm text-warning';
        label.textContent = text;
    } else if (success === true) {
        pill.className = 'autosave-status-pill saved';
        icon.className = 'bi bi-cloud-check-fill text-success';
        label.textContent = text;
    } else {
        pill.className = 'autosave-status-pill saving text-danger';
        icon.className = 'bi bi-exclamation-triangle-fill text-danger';
        label.textContent = text;
    }
}

// Stock Privacy Toggle
function toggleStockPrivacy(isChecked) {
    localStorage.setItem('show_stock', isChecked ? '1' : '0');
    autoSaveSingleSetting('show_stock', isChecked ? 1 : 0);
}

// Test Scanner Beep (Web Audio API)
function testScannerBeep() {
    try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();

        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(880, audioCtx.currentTime); // 880 Hz tone (A5)
        gainNode.gain.setValueAtTime(0.12, audioCtx.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.12);

        oscillator.connect(gainNode);
        gainNode.connect(audioCtx.destination);

        oscillator.start();
        oscillator.stop(audioCtx.currentTime + 0.12);
    } catch (e) {
        console.warn('Web Audio API not supported:', e);
    }
}

// Clear Cache Execution
function executeClearCache() {
    const btn = document.getElementById('btn-clear-cache');
    const icon = document.getElementById('icon-clear-cache');
    if (btn) btn.disabled = true;
    if (icon) icon.className = 'spinner-border spinner-border-sm me-1';

    fetch(window.BASE_URL + 'api/settings/clear-cache', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(res => {
        if (btn) btn.disabled = false;
        if (icon) icon.className = 'bi bi-check-circle me-1';
        triggerAutoSaveStatus(true, 'Cache Server Berhasil Dikosongkan');
        setTimeout(() => {
            if (icon) icon.className = 'bi bi-trash3 me-1';
        }, 2000);
    })
    .catch(err => {
        console.error(err);
        if (btn) btn.disabled = false;
        if (icon) icon.className = 'bi bi-trash3 me-1';
        triggerAutoSaveStatus(false, 'Gagal Mengosongkan Cache');
    });
}
</script>
