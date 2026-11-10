<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid px-0">
    <!-- Page Header & Action Bar -->
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="h4 fw-bold mb-0" style="color: var(--mb-text-primary); letter-spacing: -0.01em;">Profil Saya & Pengaturan Akun</h1>
                <span class="badge-soft-success d-inline-flex align-items-center gap-1.5 px-2.5 py-1 rounded-pill" style="font-size: 0.72rem; font-weight: 600;">
                    <span class="badge-dot bg-success"></span> Akun Terverifikasi
                </span>
            </div>
            <p class="text-muted small mb-0">Kelola identitas operator inventaris, kredensial keamanan akun, dan preferensi antarmuka personal.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('dashboard'); ?>" class="btn btn-outline-secondary btn-sm px-3 fw-semibold rounded-pill d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Profile Hero Card (Identity & Quick Stats) -->
    <div class="profile-hero-card">
        <div class="row align-items-center g-4">
            <div class="col-12 col-md-auto text-center text-md-start">
                <!-- Interactive Avatar Container -->
                <div class="profile-avatar-container mx-auto mx-md-0">
                    <div class="profile-avatar-frame" id="avatar-frame">
                        <?php if (!empty($user['avatar']) && file_exists(FCPATH . 'uploads/avatar/' . $user['avatar'])): ?>
                            <img src="<?= base_url('uploads/avatar/' . $user['avatar']); ?>" alt="<?= html_escape($user['name']); ?>" id="avatar-preview-img">
                            <div class="profile-avatar-fallback d-none" id="avatar-preview-fallback">
                                <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)); ?>
                            </div>
                        <?php else: ?>
                            <img src="" alt="Avatar" id="avatar-preview-img" class="d-none">
                            <div class="profile-avatar-fallback" id="avatar-preview-fallback">
                                <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- Hover overlay trigger -->
                    <label for="avatar-input" class="profile-avatar-overlay" title="Klik untuk mengunggah foto baru">
                        <i class="bi bi-camera-fill fs-5"></i>
                        <span>Ubah</span>
                    </label>
                    <!-- Floating camera badge icon -->
                    <label for="avatar-input" class="profile-avatar-badge" title="Ganti Foto Profil">
                        <i class="bi bi-camera"></i>
                    </label>
                </div>
            </div>

            <div class="col-12 col-md text-center text-md-start">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2 mb-1.5">
                    <h2 class="h4 fw-bold mb-0" id="hero-display-name" style="color: var(--mb-text-primary);"><?= html_escape($user['name']); ?></h2>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-0.5 rounded-pill font-monospace" style="font-size: 0.72rem; font-weight: 700;">
                        <?= strtoupper($user['role'] ?? 'OPERATOR'); ?>
                    </span>
                </div>
                <div class="text-muted small mb-2.5" id="hero-display-email">
                    <i class="bi bi-envelope me-1"></i> <?= html_escape($user['email']); ?>
                </div>

                <!-- Avatar Quick Actions & Format Hints -->
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2">
                    <label for="avatar-input" class="btn btn-sm btn-outline-secondary py-1 px-2.5 rounded-pill" style="font-size: 0.72rem; cursor: pointer;">
                        <i class="bi bi-upload me-1"></i> Pilih Foto Baru
                    </label>
                    <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2.5 rounded-pill <?= empty($user['avatar']) ? 'd-none' : ''; ?>" id="btn-remove-avatar" style="font-size: 0.72rem;">
                        <i class="bi bi-trash3 me-1"></i> Hapus Foto
                    </button>
                    <span class="text-muted" style="font-size: 0.7rem;">(JPG, PNG, WebP maks. 2MB)</span>
                </div>
            </div>

            <!-- Operator Telemetry Stat Pills -->
            <div class="col-12 col-lg-auto ms-lg-auto">
                <div class="d-flex flex-wrap justify-content-center justify-content-md-end gap-2.5">
                    <div class="profile-stat-box text-center text-md-start">
                        <span class="profile-stat-label">Aktivitas Transaksi</span>
                        <span class="profile-stat-value tabular-nums text-primary"><?= number_format($stats['total_actions'] ?? 0); ?></span>
                    </div>
                    <div class="profile-stat-box text-center text-md-start">
                        <span class="profile-stat-label">Total Login</span>
                        <span class="profile-stat-value tabular-nums text-success"><?= number_format($stats['total_login'] ?? 1); ?>x</span>
                    </div>
                    <div class="profile-stat-box text-center text-md-start">
                        <span class="profile-stat-label">Bergabung</span>
                        <span class="profile-stat-value" style="font-size: 0.88rem;"><?= date('M Y', strtotime($user['created_at'])); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Quick Tab Selector (Smartphones & Tablets < 768px) -->
    <div class="d-md-none mb-3">
        <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.68rem; letter-spacing: 0.05em;">
            <i class="bi bi-segmented-nav me-1 text-danger"></i> Menu Profil Operator:
        </label>
        <div class="input-group shadow-sm">
            <span class="input-group-text border-subtle text-danger" style="background-color: var(--mb-card-raised); border-color: var(--mb-border);">
                <i class="bi bi-person-gear"></i>
            </span>
            <select class="form-select fw-semibold" id="mobile-profile-tab-dropdown" style="background-color: var(--mb-card); border-color: var(--mb-border); color: var(--mb-text-primary); font-size: 0.85rem;">
                <option value="tab-info" selected>👤 Informasi Akun Operator</option>
                <option value="tab-security">🔒 Keamanan &amp; Kata Sandi</option>
                <option value="tab-preferences">🎨 Preferensi &amp; Tema Tampilan</option>
                <option value="tab-session">💻 Sesi &amp; Audit Log</option>
            </select>
        </div>
    </div>

    <!-- Segmented Navigation Tabs -->
    <div class="report-tabs-container mb-4">
        <button type="button" class="report-tab-btn active" data-tab="tab-info">
            <i class="bi bi-person-badge"></i> Informasi Akun Operator
        </button>
        <button type="button" class="report-tab-btn" data-tab="tab-security">
            <i class="bi bi-shield-lock"></i> Keamanan &amp; Kata Sandi
        </button>
        <button type="button" class="report-tab-btn" data-tab="tab-preferences">
            <i class="bi bi-sliders"></i> Preferensi &amp; Personalisasi
        </button>
        <button type="button" class="report-tab-btn" data-tab="tab-session">
            <i class="bi bi-laptop"></i> Sesi &amp; Audit Log
        </button>
    </div>

    <!-- TAB 1: INFORMASI AKUN OPERATOR -->
    <div class="report-tab-content active" id="tab-info">
        <div class="settings-section-card">
            <div class="settings-section-header">
                <div>
                    <h3 class="settings-section-title mb-1">Informasi Identitas Operator</h3>
                    <p class="settings-section-subtitle mb-0">Informasi nama lengkap dan email resmi yang terdaftar pada sistem pergudangan.</p>
                </div>
                <span class="badge bg-light text-dark border font-monospace small px-2.5 py-1">UID: #<?= $user['id']; ?></span>
            </div>
            <div class="p-4">
                <form id="profile-form" enctype="multipart/form-data">
                    <!-- Hidden Avatar File Input & Flags -->
                    <input type="file" name="avatar" id="avatar-input" class="d-none" accept="image/jpeg,image/png,image/webp">
                    <input type="hidden" name="delete_avatar" id="delete-avatar-flag" value="0">

                    <div class="row g-3.5 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small" for="prof-name">Nama Lengkap Operator <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" id="prof-name" class="form-control border-start-0 ps-1" value="<?= html_escape($user['name']); ?>" required placeholder="Masukkan nama lengkap">
                            </div>
                            <div class="form-text small">Nama ini akan tercantum pada setiap catatan mutasi barang dan laporan audit.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small" for="prof-email">Alamat Email Resmi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="prof-email" class="form-control border-start-0 ps-1" value="<?= html_escape($user['email']); ?>" required placeholder="nama@perusahaan.com">
                            </div>
                            <div class="form-text small">Digunakan untuk keperluan autentikasi login dan pengiriman digest laporan harian.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small">Peran / Hak Akses Sistem</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-shield-check"></i></span>
                                <input type="text" class="form-control bg-light border-start-0 ps-1 text-muted" value="<?= ucfirst($user['role']); ?> (Hak Penuh Operasional)" readonly disabled>
                            </div>
                            <div class="form-text small">Peran hak akses hanya dapat diubah oleh Administrator Utama sistem.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small">Tanggal Pendaftaran Akun</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-calendar3"></i></span>
                                <input type="text" class="form-control bg-light border-start-0 ps-1 text-muted" value="<?= format_date_by_preference($user['created_at']); ?> (<?= date('H:i:s', strtotime($user['created_at'])); ?> WIB)" readonly disabled>
                            </div>
                            <div class="form-text small">Waktu pertama kali akun operator dibuat di basis data.</div>
                        </div>
                    </div>

                    <!-- Staged Avatar Notification Banner (if any selected) -->
                    <div id="staged-avatar-banner" class="alert alert-info py-2 px-3 d-none mb-4 d-flex align-items-center justify-content-between rounded-3">
                        <div class="d-flex align-items-center gap-2 small">
                            <i class="bi bi-info-circle-fill fs-5"></i>
                            <span>Foto avatar baru siap disimpan. Jangan lupa klik <strong>Simpan Perubahan Profil</strong>.</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-0.5 px-2" id="btn-cancel-avatar-stage">Batal</button>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <div class="text-muted small">
                            <i class="bi bi-clock-history me-1"></i> Terakhir diperbarui: <span class="tabular-nums"><?= format_date_by_preference($user['updated_at'] ?? $user['created_at']); ?></span>
                        </div>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold rounded-pill d-inline-flex align-items-center gap-2" id="btn-save-profile">
                            <i class="bi bi-check2"></i> Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 2: KEAMANAN & KATA SANDI -->
    <div class="report-tab-content" id="tab-security">
        <div class="settings-section-card">
            <div class="settings-section-header">
                <div>
                    <h3 class="settings-section-title mb-1">Kredensial & Kata Sandi Akun</h3>
                    <p class="settings-section-subtitle mb-0">Pastikan akun Anda terlindungi dengan menggunakan kata sandi yang aman dan tidak mudah ditebak.</p>
                </div>
                <i class="bi bi-key-fill text-warning fs-4"></i>
            </div>
            <div class="p-4">
                <form id="password-form">
                    <div class="row g-3.5 mb-4">
                        <!-- Current Password -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small" for="pass-current">Kata Sandi Saat Ini <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-lock"></i></span>
                                <input type="password" name="current_password" id="pass-current" class="form-control border-start-0 border-end-0 ps-1" required placeholder="Masukkan kata sandi lama">
                                <button type="button" class="btn btn-outline-secondary border-start-0 toggle-password-visibility" data-target="pass-current" title="Tampilkan / Sembunyikan">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="form-text small">Diperlukan untuk memverifikasi kepemilikan akun sah.</div>
                        </div>

                        <!-- Spacer on desktop -->
                        <div class="col-12 col-md-6 d-none d-md-block"></div>

                        <!-- New Password -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small" for="pass-new">Kata Sandi Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-shield-plus"></i></span>
                                <input type="password" name="new_password" id="pass-new" class="form-control border-start-0 border-end-0 ps-1" minlength="8" required placeholder="Minimal 8 karakter unik">
                                <button type="button" class="btn btn-outline-secondary border-start-0 toggle-password-visibility" data-target="pass-new" title="Tampilkan / Sembunyikan">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                            <!-- Realtime Password Strength Meter -->
                            <div class="pwd-strength-container mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted" style="font-size: 0.7rem;">Kekuatan Sandi:</span>
                                    <span id="pwd-strength-label" class="fw-bold" style="font-size: 0.72rem; color: var(--mb-text-muted);">Belum Diisi</span>
                                </div>
                                <div class="pwd-strength-bars">
                                    <div class="pwd-strength-bar" id="pwd-bar-1"></div>
                                    <div class="pwd-strength-bar" id="pwd-bar-2"></div>
                                    <div class="pwd-strength-bar" id="pwd-bar-3"></div>
                                    <div class="pwd-strength-bar" id="pwd-bar-4"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small" for="pass-confirm">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-shield-check"></i></span>
                                <input type="password" name="confirm_password" id="pass-confirm" class="form-control border-start-0 border-end-0 ps-1" minlength="8" required placeholder="Ulangi kata sandi baru">
                                <button type="button" class="btn btn-outline-secondary border-start-0 toggle-password-visibility" data-target="pass-confirm" title="Tampilkan / Sembunyikan">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div id="pwd-match-feedback" class="form-text small mt-1">
                                <span class="text-muted"><i class="bi bi-dot"></i> Ketik ulang kata sandi baru untuk mencocokkan.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Password Requirement Checklist -->
                    <div class="pwd-requirement-list mb-4">
                        <div class="pwd-req-item" id="req-len">
                            <i class="bi bi-circle"></i> Minimal 8 Karakter
                        </div>
                        <div class="pwd-req-item" id="req-case">
                            <i class="bi bi-circle"></i> Huruf Besar & Huruf Kecil
                        </div>
                        <div class="pwd-req-item" id="req-num">
                            <i class="bi bi-circle"></i> Setidaknya 1 Angka (0-9)
                        </div>
                        <div class="pwd-req-item" id="req-sym">
                            <i class="bi bi-circle"></i> Karakter Spesial / Simbol (!@#$)
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <span class="text-muted small">
                            <i class="bi bi-shield-exclamation me-1"></i> Setelah diperbarui, Anda tidak perlu login ulang pada sesi ini.
                        </span>
                        <button type="submit" class="btn btn-danger px-4 fw-semibold rounded-pill d-inline-flex align-items-center gap-2" id="btn-save-password" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                            <i class="bi bi-lock-fill"></i> Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 3: PREFERENSI & PERSONALISASI -->
    <div class="report-tab-content" id="tab-preferences">
        <div class="settings-section-card mb-4">
            <div class="settings-section-header">
                <div>
                    <h3 class="settings-section-title mb-1">Gaya Tampilan & Tema Sistem</h3>
                    <p class="settings-section-subtitle mb-0">Sesuaikan tema visual sistem inventaris sesuai kenyamanan mata saat operasional.</p>
                </div>
                <i class="bi bi-palette text-primary fs-4"></i>
            </div>
            <div class="p-4">
                <label class="form-label fw-semibold small mb-3">Pilihan Mode Tampilan Antarmuka</label>
                <div class="theme-selector-grid mb-4">
                    <!-- Light Theme Card -->
                    <div class="theme-card-option <?= (($preferences['theme'] ?? 'light') === 'light') ? 'active' : ''; ?>" data-theme-val="light">
                        <div class="theme-card-preview light-preview">
                            <div class="preview-sidebar"></div>
                            <div class="preview-body">
                                <div class="preview-bar brand"></div>
                                <div class="preview-bar" style="width: 70%;"></div>
                                <div class="preview-bar" style="width: 50%;"></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <div>
                                <div class="fw-bold small" style="color: var(--mb-text-primary);">Mode Terang</div>
                                <div class="text-muted" style="font-size: 0.72rem;">Sangat bersih & kontras tinggi</div>
                            </div>
                            <i class="bi bi-check-circle-fill text-danger fs-5 <?= (($preferences['theme'] ?? 'light') === 'light') ? '' : 'd-none'; ?>"></i>
                        </div>
                    </div>

                    <!-- Dark Theme Card -->
                    <div class="theme-card-option <?= (($preferences['theme'] ?? 'light') === 'dark') ? 'active' : ''; ?>" data-theme-val="dark">
                        <div class="theme-card-preview dark-preview">
                            <div class="preview-sidebar"></div>
                            <div class="preview-body">
                                <div class="preview-bar brand"></div>
                                <div class="preview-bar" style="width: 70%;"></div>
                                <div class="preview-bar" style="width: 50%;"></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <div>
                                <div class="fw-bold small" style="color: var(--mb-text-primary);">Mode Gelap (Dark)</div>
                                <div class="text-muted" style="font-size: 0.72rem;">Mengurangi silau mata operator</div>
                            </div>
                            <i class="bi bi-check-circle-fill text-danger fs-5 <?= (($preferences['theme'] ?? 'light') === 'dark') ? '' : 'd-none'; ?>"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operational Behavior Preferences -->
        <div class="settings-section-card">
            <div class="settings-section-header">
                <div>
                    <h3 class="settings-section-title mb-1">Preferensi Operasional & Interaksi</h3>
                    <p class="settings-section-subtitle mb-0">Pengaturan perilaku alur kerja input barang, format tanggal, dan peringatan audio.</p>
                </div>
                <i class="bi bi-gear-wide-connected text-secondary fs-4"></i>
            </div>
            <div class="p-4">
                <form id="preferences-form">
                    <div class="row g-4 mb-4">
                        <!-- Date Format Option -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small">Format Tampilan Tanggal</label>
                            <div class="d-flex flex-column gap-2">
                                <?php
                                    $currDateFormat = $preferences['date_format'] ?? 'DD/MM/YYYY';
                                ?>
                                <label class="p-2.5 rounded-3 border d-flex align-items-center gap-3 cursor-pointer" style="background-color: var(--mb-card-raised);">
                                    <input type="radio" name="date_format" value="DD/MM/YYYY" class="form-check-input mt-0" <?= $currDateFormat === 'DD/MM/YYYY' ? 'checked' : ''; ?>>
                                    <div class="small">
                                        <div class="fw-bold" style="color: var(--mb-text-primary);">DD/MM/YYYY (Standar Indonesia)</div>
                                        <div class="text-muted font-monospace" style="font-size: 0.72rem;"><?= date('d/m/Y'); ?></div>
                                    </div>
                                </label>

                                <label class="p-2.5 rounded-3 border d-flex align-items-center gap-3 cursor-pointer" style="background-color: var(--mb-card-raised);">
                                    <input type="radio" name="date_format" value="YYYY-MM-DD" class="form-check-input mt-0" <?= $currDateFormat === 'YYYY-MM-DD' ? 'checked' : ''; ?>>
                                    <div class="small">
                                        <div class="fw-bold" style="color: var(--mb-text-primary);">YYYY-MM-DD (Standar ISO)</div>
                                        <div class="text-muted font-monospace" style="font-size: 0.72rem;"><?= date('Y-m-d'); ?></div>
                                    </div>
                                </label>

                                <label class="p-2.5 rounded-3 border d-flex align-items-center gap-3 cursor-pointer" style="background-color: var(--mb-card-raised);">
                                    <input type="radio" name="date_format" value="DD MMMM YYYY" class="form-check-input mt-0" <?= $currDateFormat === 'DD MMMM YYYY' ? 'checked' : ''; ?>>
                                    <div class="small">
                                        <div class="fw-bold" style="color: var(--mb-text-primary);">DD MMMM YYYY (Format Lengkap)</div>
                                        <div class="text-muted font-monospace" style="font-size: 0.72rem;"><?= date('d F Y'); ?></div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Confirmation Mode Option -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small">Mode Konfirmasi Transaksi & Aksi</label>
                            <div class="d-flex flex-column gap-2">
                                <?php
                                    $currConfirmMode = $preferences['confirm_mode'] ?? 'safe';
                                ?>
                                <label class="p-2.5 rounded-3 border d-flex align-items-center gap-3 cursor-pointer" style="background-color: var(--mb-card-raised);">
                                    <input type="radio" name="confirm_mode" value="safe" class="form-check-input mt-0" <?= $currConfirmMode === 'safe' ? 'checked' : ''; ?>>
                                    <div class="small">
                                        <div class="fw-bold text-success"><i class="bi bi-shield-check me-1"></i> Mode Aman (Direkomendasikan)</div>
                                        <div class="text-muted" style="font-size: 0.72rem;">Memunculkan modal konfirmasi sebelum aksi hapus atau mutasi stok.</div>
                                    </div>
                                </label>

                                <label class="p-2.5 rounded-3 border d-flex align-items-center gap-3 cursor-pointer" style="background-color: var(--mb-card-raised);">
                                    <input type="radio" name="confirm_mode" value="fast" class="form-check-input mt-0" <?= $currConfirmMode === 'fast' ? 'checked' : ''; ?>>
                                    <div class="small">
                                        <div class="fw-bold text-warning"><i class="bi bi-lightning-charge me-1"></i> Mode Cepat (Fast-Paced)</div>
                                        <div class="text-muted" style="font-size: 0.72rem;">Mengurangi dialog peringatan demi kecepatan input batch barang.</div>
                                    </div>
                                </label>
                            </div>

                            <!-- Additional Switches -->
                            <div class="mt-3.5 pt-3 border-top">
                                <div class="form-check form-switch mb-2.5">
                                    <input class="form-check-input" type="checkbox" role="switch" id="pref-sound" name="sound_effects" value="1" <?= (!empty($preferences['sound_effects'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label small fw-semibold" for="pref-sound">Efek Audio Konfirmasi Transaksi</label>
                                    <div class="text-muted" style="font-size: 0.7rem;">Mainkan bunyi chime lembut saat stok berhasil disimpan.</div>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="pref-digest" name="daily_digest" value="1" <?= (!empty($preferences['daily_digest'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label small fw-semibold" for="pref-digest">Kirim Ringkasan Harian (Daily Digest)</label>
                                    <div class="text-muted" style="font-size: 0.7rem;">Kirim rangkuman barang masuk/keluar ke email terdaftar setiap sore.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4 fw-semibold rounded-pill d-inline-flex align-items-center gap-2" id="btn-save-preferences">
                            <i class="bi bi-save"></i> Simpan Preferensi Personal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 4: SESI & AUDIT LOG -->
    <div class="report-tab-content" id="tab-session">
        <div class="settings-section-card mb-4">
            <div class="settings-section-header">
                <div>
                    <h3 class="settings-section-title mb-1">Informasi Perangkat & Sesi Aktif</h3>
                    <p class="settings-section-subtitle mb-0">Status sesi koneksi browser yang sedang digunakan untuk mengakses sistem gudang saat ini.</p>
                </div>
                <span class="badge-soft-success px-2.5 py-1 rounded-pill small fw-semibold d-inline-flex align-items-center gap-1.5">
                    <span class="badge-dot bg-success"></span> Sesi Aktif (Online)
                </span>
            </div>
            <div class="p-4">
                <div class="diag-metric-grid mb-4">
                    <div class="diag-metric-box">
                        <span class="diag-metric-lbl">Alamat IP Klien</span>
                        <span class="diag-metric-val"><?= html_escape($client_session['ip_address'] ?? '127.0.0.1'); ?></span>
                    </div>
                    <div class="diag-metric-box">
                        <span class="diag-metric-lbl">Status Hak Akses</span>
                        <span class="diag-metric-val text-uppercase text-danger"><?= html_escape($user['role'] ?? 'OPERATOR'); ?></span>
                    </div>
                    <div class="diag-metric-box">
                        <span class="diag-metric-lbl">Pembaruan Profil Terakhir</span>
                        <span class="diag-metric-val" style="font-size: 0.82rem;"><?= date('d M Y, H:i', strtotime($stats['last_updated'])); ?> WIB</span>
                    </div>
                    <div class="diag-metric-box">
                        <span class="diag-metric-lbl">Protokol Keamanan</span>
                        <span class="diag-metric-val text-success">CSRF Token Valid</span>
                    </div>
                </div>

                <div class="p-3 rounded-3 mb-4" style="background-color: var(--mb-card-raised); border: 1px solid var(--mb-border);">
                    <div class="d-flex align-items-start gap-3">
                        <i class="bi bi-browser-chrome fs-3 text-muted"></i>
                        <div class="small">
                            <div class="fw-bold mb-0.5" style="color: var(--mb-text-primary);">Identifikasi Peramban (User Agent)</div>
                            <div class="text-muted font-monospace text-break" style="font-size: 0.74rem;">
                                <?= html_escape($client_session['user_agent'] ?? '-'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between pt-2">
                    <span class="text-muted small">
                        <i class="bi bi-shield-lock me-1"></i> Jika merasa akun diakses pihak lain, segera perbarui kata sandi Anda.
                    </span>
                    <form action="<?= site_url('logout'); ?>" method="POST" class="m-0">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <button type="submit" class="btn btn-outline-danger btn-sm px-3 rounded-pill fw-semibold d-inline-flex align-items-center gap-1.5">
                            <i class="bi bi-box-arrow-right"></i> Keluar dari Sesi Ini
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Unified Tab Switching Logic (Mobile Dropdown + Swipe Bar)
    const tabButtons = document.querySelectorAll('.report-tab-btn');
    const tabContents = document.querySelectorAll('.report-tab-content');
    const mobileDropdown = document.getElementById('mobile-profile-tab-dropdown');

    function switchProfileTab(targetId) {
        tabButtons.forEach(btn => {
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

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-tab');
            switchProfileTab(targetId);
        });
    });

    if (mobileDropdown) {
        mobileDropdown.addEventListener('change', function() {
            switchProfileTab(this.value);
        });
    }

    // Deep link via URL hash
    const initialHash = window.location.hash.replace('#', '');
    if (initialHash && document.getElementById(initialHash)) {
        switchProfileTab(initialHash);
    }

    // 2. Avatar Selection, Preview, and Deletion Handling
    const avatarInput = document.getElementById('avatar-input');
    const avatarPreviewImg = document.getElementById('avatar-preview-img');
    const avatarPreviewFallback = document.getElementById('avatar-preview-fallback');
    const deleteAvatarFlag = document.getElementById('delete-avatar-flag');
    const btnRemoveAvatar = document.getElementById('btn-remove-avatar');
    const stagedAvatarBanner = document.getElementById('staged-avatar-banner');
    const btnCancelAvatarStage = document.getElementById('btn-cancel-avatar-stage');

    let originalAvatarSrc = avatarPreviewImg ? avatarPreviewImg.getAttribute('src') : '';
    let hadOriginalAvatar = Boolean(originalAvatarSrc && !avatarPreviewImg.classList.contains('d-none'));

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ukuran Berkas Terlalu Besar',
                    text: 'Ukuran foto maksimal adalah 2MB. Silakan pilih foto dengan resolusi lebih kecil.'
                });
                avatarInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(evt) {
                avatarPreviewImg.src = evt.target.result;
                avatarPreviewImg.classList.remove('d-none');
                if (avatarPreviewFallback) avatarPreviewFallback.classList.add('d-none');

                deleteAvatarFlag.value = '0';
                if (btnRemoveAvatar) btnRemoveAvatar.classList.remove('d-none');
                if (stagedAvatarBanner) stagedAvatarBanner.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        });
    }

    if (btnCancelAvatarStage) {
        btnCancelAvatarStage.addEventListener('click', function() {
            avatarInput.value = '';
            deleteAvatarFlag.value = '0';
            stagedAvatarBanner.classList.add('d-none');

            if (hadOriginalAvatar && originalAvatarSrc) {
                avatarPreviewImg.src = originalAvatarSrc;
                avatarPreviewImg.classList.remove('d-none');
                if (avatarPreviewFallback) avatarPreviewFallback.classList.add('d-none');
                if (btnRemoveAvatar) btnRemoveAvatar.classList.remove('d-none');
            } else {
                avatarPreviewImg.src = '';
                avatarPreviewImg.classList.add('d-none');
                if (avatarPreviewFallback) avatarPreviewFallback.classList.remove('d-none');
                if (btnRemoveAvatar) btnRemoveAvatar.classList.add('d-none');
            }
        });
    }

    if (btnRemoveAvatar) {
        btnRemoveAvatar.addEventListener('click', function() {
            Swal.fire({
                title: 'Hapus Foto Avatar?',
                text: 'Foto profil akan dihapus dan diganti dengan inisial nama Anda.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626'
            }).then((result) => {
                if (result.isConfirmed) {
                    avatarInput.value = '';
                    deleteAvatarFlag.value = '1';
                    avatarPreviewImg.src = '';
                    avatarPreviewImg.classList.add('d-none');
                    if (avatarPreviewFallback) avatarPreviewFallback.classList.remove('d-none');
                    btnRemoveAvatar.classList.add('d-none');
                    if (stagedAvatarBanner) stagedAvatarBanner.classList.add('d-none');
                }
            });
        });
    }

    // 3. Profile Information Form Submission (AJAX)
    const profileForm = document.getElementById('profile-form');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-save-profile');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1.5"></span> Menyimpan...';

            const formData = new FormData(this);
            formData.append(window.CSRF_TOKEN_NAME, window.CSRF_HASH);

            fetch(window.BASE_URL + 'api/profile/update', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                if (res.status) {
                    // Update Hero Display
                    const heroName = document.getElementById('hero-display-name');
                    const heroEmail = document.getElementById('hero-display-email');
                    if (heroName && res.data.name) heroName.textContent = res.data.name;
                    if (heroEmail && res.data.email) heroEmail.innerHTML = '<i class="bi bi-envelope me-1"></i> ' + res.data.email;

                    // Update Topbar avatar if available
                    if (res.data.avatar_url) {
                        hadOriginalAvatar = true;
                        originalAvatarSrc = res.data.avatar_url;
                    } else if (deleteAvatarFlag.value === '1') {
                        hadOriginalAvatar = false;
                        originalAvatarSrc = '';
                    }

                    if (stagedAvatarBanner) stagedAvatarBanner.classList.add('d-none');

                    Swal.fire({
                        icon: 'success',
                        title: 'Profil Diperbarui',
                        text: res.message || 'Perubahan profil berhasil disimpan.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        text: res.message || 'Terjadi kesalahan saat menyimpan profil.'
                    });
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: 'Tidak dapat menghubungi server.'
                });
            });
        });
    }

    // 4. Password Visibility Toggles
    document.querySelectorAll('.toggle-password-visibility').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            if (!targetInput) return;

            const icon = this.querySelector('i');
            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                targetInput.type = 'password';
                icon.className = 'bi bi-eye';
            }
        });
    });

    // 5. Password Strength Meter & Requirement Realtime Evaluator
    const passNew = document.getElementById('pass-new');
    const passConfirm = document.getElementById('pass-confirm');
    const strengthLabel = document.getElementById('pwd-strength-label');
    const bar1 = document.getElementById('pwd-bar-1');
    const bar2 = document.getElementById('pwd-bar-2');
    const bar3 = document.getElementById('pwd-bar-3');
    const bar4 = document.getElementById('pwd-bar-4');

    const reqLen = document.getElementById('req-len');
    const reqCase = document.getElementById('req-case');
    const reqNum = document.getElementById('req-num');
    const reqSym = document.getElementById('req-sym');
    const matchFeedback = document.getElementById('pwd-match-feedback');

    const evaluatePassword = () => {
        const val = passNew.value;
        const confVal = passConfirm.value;

        // Check rules
        const hasLen = val.length >= 8;
        const hasCase = /[A-Z]/.test(val) && /[a-z]/.test(val);
        const hasNum = /[0-9]/.test(val);
        const hasSym = /[^A-Za-z0-9]/.test(val);

        const updateReq = (el, isMet) => {
            if (!el) return;
            if (isMet) {
                el.classList.add('met');
                el.querySelector('i').className = 'bi bi-check-circle-fill text-success';
            } else {
                el.classList.remove('met');
                el.querySelector('i').className = 'bi bi-circle text-muted';
            }
        };

        updateReq(reqLen, hasLen);
        updateReq(reqCase, hasCase);
        updateReq(reqNum, hasNum);
        updateReq(reqSym, hasSym);

        const score = [hasLen, hasCase, hasNum, hasSym].filter(Boolean).length;

        // Reset bars
        [bar1, bar2, bar3, bar4].forEach(b => {
            if (b) b.className = 'pwd-strength-bar';
        });

        if (val.length === 0) {
            strengthLabel.textContent = 'Belum Diisi';
            strengthLabel.style.color = 'var(--mb-text-muted)';
        } else if (score <= 1) {
            strengthLabel.textContent = 'Lemah';
            strengthLabel.style.color = '#ef4444';
            if (bar1) bar1.classList.add('weak');
        } else if (score === 2) {
            strengthLabel.textContent = 'Cukup';
            strengthLabel.style.color = '#f59e0b';
            if (bar1) bar1.classList.add('fair');
            if (bar2) bar2.classList.add('fair');
        } else if (score === 3) {
            strengthLabel.textContent = 'Baik';
            strengthLabel.style.color = '#3b82f6';
            if (bar1) bar1.classList.add('good');
            if (bar2) bar2.classList.add('good');
            if (bar3) bar3.classList.add('good');
        } else {
            strengthLabel.textContent = 'Sangat Kuat';
            strengthLabel.style.color = '#10b981';
            [bar1, bar2, bar3, bar4].forEach(b => {
                if (b) b.classList.add('strong');
            });
        }

        // Match check
        if (confVal.length > 0) {
            if (val === confVal) {
                matchFeedback.innerHTML = '<span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Konfirmasi kata sandi cocok.</span>';
            } else {
                matchFeedback.innerHTML = '<span class="text-danger fw-semibold"><i class="bi bi-exclamation-triangle-fill me-1"></i> Kata sandi belum cocok.</span>';
            }
        } else {
            matchFeedback.innerHTML = '<span class="text-muted"><i class="bi bi-dot"></i> Ketik ulang kata sandi baru untuk mencocokkan.</span>';
        }
    };

    if (passNew) passNew.addEventListener('input', evaluatePassword);
    if (passConfirm) passConfirm.addEventListener('input', evaluatePassword);

    // 6. Password Form Submission
    const passwordForm = document.getElementById('password-form');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const nVal = passNew.value;
            const cVal = passConfirm.value;

            if (nVal !== cVal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Kata Sandi Tidak Cocok',
                    text: 'Pastikan kata sandi baru dan konfirmasi yang Anda ketikkan identik.'
                });
                return;
            }

            if (nVal.length < 8) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Kata Sandi Terlalu Pendek',
                    text: 'Kata sandi minimal harus terdiri dari 8 karakter.'
                });
                return;
            }

            const btn = document.getElementById('btn-save-password');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1.5"></span> Memperbarui...';

            const formData = new FormData(this);
            formData.append(window.CSRF_TOKEN_NAME, window.CSRF_HASH);

            fetch(window.BASE_URL + 'api/profile/password', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                if (res.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Kata Sandi Diperbarui',
                        text: res.message || 'Kredensial kata sandi berhasil diperbarui.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    passwordForm.reset();
                    evaluatePassword();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memperbarui',
                        text: res.message || 'Kata sandi saat ini tidak sesuai.'
                    });
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: 'Gagal memproses pembaruan kata sandi.'
                });
            });
        });
    }

    // 7. Theme Selector Card Interaction
    const themeCards = document.querySelectorAll('.theme-card-option');
    themeCards.forEach(card => {
        card.addEventListener('click', function() {
            const chosenTheme = this.getAttribute('data-theme-val') || this.getAttribute('data-theme');
            if (!chosenTheme) return;

            themeCards.forEach(c => {
                c.classList.remove('active');
                const check = c.querySelector('.bi-check-circle-fill');
                if (check) check.classList.add('d-none');
            });
            this.classList.add('active');
            const myCheck = this.querySelector('.bi-check-circle-fill');
            if (myCheck) myCheck.classList.remove('d-none');

            // Apply theme instantaneously via MBTheme controller
            if (window.MBTheme && typeof window.MBTheme.setTheme === 'function') {
                window.MBTheme.setTheme(chosenTheme);
            } else {
                document.documentElement.setAttribute('data-bs-theme', chosenTheme);
            }
            localStorage.setItem('theme', chosenTheme);

            // Automatically persist theme preference via API
            fetch(window.BASE_URL + 'api/profile/preferences', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ theme: chosenTheme })
            }).catch(() => {});
        });
    });

    // 8. User Preferences Form Submission
    const prefForm = document.getElementById('preferences-form');
    if (prefForm) {
        prefForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-save-preferences');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1.5"></span> Menyimpan...';

            const activeThemeCard = document.querySelector('.theme-card-option.active');
            const activeTheme = activeThemeCard ? activeThemeCard.getAttribute('data-theme-val') : 'light';

            const selectedDateFormat = document.querySelector('input[name="date_format"]:checked')?.value || 'DD/MM/YYYY';
            const selectedConfirmMode = document.querySelector('input[name="confirm_mode"]:checked')?.value || 'safe';
            const soundEffects = document.getElementById('pref-sound')?.checked ? true : false;
            const dailyDigest = document.getElementById('pref-digest')?.checked ? true : false;

            const payload = {
                theme: activeTheme,
                date_format: selectedDateFormat,
                confirm_mode: selectedConfirmMode,
                sound_effects: soundEffects,
                daily_digest: dailyDigest
            };

            fetch(window.BASE_URL + 'api/profile/preferences', {
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
                btn.innerHTML = originalText;
                if (res.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Preferensi Disimpan',
                        text: res.message || 'Pengaturan personal Anda berhasil disimpan.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        text: res.message || 'Gagal menyimpan preferensi.'
                    });
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: 'Tidak dapat menghubungi server.'
                });
            });
        });
    }
});
</script>
