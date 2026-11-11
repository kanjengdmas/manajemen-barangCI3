<header class="app-header">
    <!-- Left: Mobile Toggle & Live Status Pill -->
    <div class="header-left">
        <button type="button" class="btn-icon-quiet d-lg-none" id="sidebar-toggle-btn" aria-label="Buka Menu Navigasi">
            <i class="bi bi-list fs-5"></i>
        </button>

        <!-- Live Auto-Refresh Indicator -->
        <div class="live-indicator <?= (isset($settings['auto_refresh']) && $settings['auto_refresh'] == '1') ? '' : 'inactive'; ?>" id="header-live-badge" title="Auto-refresh background polling aktif (<?= $settings['refresh_interval'] ?? 5; ?>m)">
            <span class="live-dot"></span>
            <span class="d-none d-sm-inline">LIVE</span>
            <span class="text-muted d-none d-md-inline" style="font-size: 0.7rem;">(<?= $settings['refresh_interval'] ?? 5; ?>m)</span>
            <span class="text-muted mx-1 d-none d-sm-inline">·</span>
            <span id="header-last-updated" class="tabular-nums text-nowrap" style="font-size: 0.72rem;"><span class="d-none d-sm-inline">Pembaruan: </span><span id="header-live-seconds"><?= date('H:i:s'); ?></span></span>
        </div>

        <!-- Manual Refresh Button -->
        <button type="button" class="btn-icon-quiet" id="btn-manual-refresh" title="Pembaruan Data Instan" aria-label="Segarkan Data">
            <i class="bi bi-arrow-clockwise fs-5"></i>
        </button>
    </div>

    <!-- Right: Clock, Privacy Toggle, Theme Switch, & User Profile -->
    <div class="header-right">
        <!-- Live Clock -->
        <div class="d-none d-xl-flex align-items-center text-muted small tabular-nums text-nowrap" id="header-live-clock">
            <i class="bi bi-clock me-1.5"></i> <?= date('D, d M Y · H:i:s'); ?> WIB
        </div>

        <!-- Theme Palette Dropdown -->
        <div class="dropdown">
            <button type="button" class="btn-icon-quiet" id="theme-toggle-btn"
                    data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="true"
                    title="Pilih Tema Warna Sistem" aria-label="Ganti Tema">
                <i class="bi bi-sun-fill fs-5 text-warning" id="theme-toggle-icon" style="pointer-events: none;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow theme-palette-menu" aria-labelledby="theme-toggle-btn">
                <li class="dropdown-header px-2.5 py-1 text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px; color: var(--mb-text-muted);">
                    <i class="bi bi-palette me-1"></i> Palet Tema Sistem
                </li>
                <li><hr class="dropdown-divider my-1 opacity-25"></li>
                <li>
                    <button type="button" class="dropdown-item theme-dropdown-option" data-theme-val="light">
                        <div class="d-flex align-items-center gap-2.5">
                            <span class="theme-swatch" style="background: #ffffff; border: 2px solid #cbd5e1;"></span>
                            <span>Mode Terang (Clean Slate)</span>
                        </div>
                        <i class="bi bi-check2 text-danger fs-5 d-none theme-check-icon"></i>
                    </button>
                </li>
                <li>
                    <button type="button" class="dropdown-item theme-dropdown-option" data-theme-val="dark">
                        <div class="d-flex align-items-center gap-2.5">
                            <span class="theme-swatch" style="background: #090d16; border: 2px solid #334155;"></span>
                            <span>Mode Gelap (Deep Slate)</span>
                        </div>
                        <i class="bi bi-check2 text-danger fs-5 d-none theme-check-icon"></i>
                    </button>
                </li>
                <li>
                    <button type="button" class="dropdown-item theme-dropdown-option" data-theme-val="sepia">
                        <div class="d-flex align-items-center gap-2.5">
                            <span class="theme-swatch" style="background: #f6f1e8; border: 2px solid #d5c7b3;"></span>
                            <span>Kertas Hangat (Sepia)</span>
                        </div>
                        <i class="bi bi-check2 text-danger fs-5 d-none theme-check-icon"></i>
                    </button>
                </li>
                <li>
                    <button type="button" class="dropdown-item theme-dropdown-option" data-theme-val="navy">
                        <div class="d-flex align-items-center gap-2.5">
                            <span class="theme-swatch" style="background: #0b1329; border: 2px solid #38bdf8;"></span>
                            <span>Midnight Navy (Deep Blue)</span>
                        </div>
                        <i class="bi bi-check2 text-danger fs-5 d-none theme-check-icon"></i>
                    </button>
                </li>
                <li>
                    <button type="button" class="dropdown-item theme-dropdown-option" data-theme-val="oled">
                        <div class="d-flex align-items-center gap-2.5">
                            <span class="theme-swatch" style="background: #000000; border: 2px solid #52525b;"></span>
                            <span>OLED Pure Black (High-Contrast)</span>
                        </div>
                        <i class="bi bi-check2 text-danger fs-5 d-none theme-check-icon"></i>
                    </button>
                </li>
                <li>
                    <button type="button" class="dropdown-item theme-dropdown-option" data-theme-val="forest">
                        <div class="d-flex align-items-center gap-2.5">
                            <span class="theme-swatch" style="background: #071712; border: 2px solid #34d399;"></span>
                            <span>Forest Emerald (Sage Green)</span>
                        </div>
                        <i class="bi bi-check2 text-danger fs-5 d-none theme-check-icon"></i>
                    </button>
                </li>
            </ul>
        </div>

        <div class="vr mx-1 opacity-25 d-none d-sm-block"></div>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <button class="btn btn-link text-decoration-none p-1 d-flex align-items-center gap-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--mb-text-primary);">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                     style="width: 34px; height: 34px; background-color: var(--mb-brand-subtle); color: var(--mb-brand); font-size: 0.85rem;">
                    <?php if (!empty($user['avatar']) && file_exists(FCPATH . 'uploads/avatar/' . $user['avatar'])): ?>
                        <img src="<?= base_url('uploads/avatar/' . $user['avatar']); ?>" alt="Avatar" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <?= strtoupper(substr($user['name'] ?? 'A', 0, 1)); ?>
                    <?php endif; ?>
                </div>
                <div class="d-none d-md-block text-start" style="line-height: 1.2;">
                    <div class="fw-bold small text-truncate" style="max-width: 130px;"><?= html_escape($user['name'] ?? 'Administrator'); ?></div>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle font-monospace" style="font-size: 0.62rem; padding: 1px 5px;">
                        <?= strtoupper($user['role'] ?? 'ADMIN'); ?>
                    </span>
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="background-color: var(--mb-card); border-color: var(--mb-border); min-width: 200px;">
                <li class="px-3 py-2 border-bottom border-subtle">
                    <div class="fw-bold small"><?= html_escape($user['name'] ?? 'Administrator'); ?></div>
                    <div class="text-muted small text-truncate"><?= html_escape($user['email'] ?? 'admin@admin.com'); ?></div>
                </li>
                <li>
                    <a class="dropdown-item py-2 d-flex align-items-center gap-2 small" href="<?= site_url('profil'); ?>">
                        <i class="bi bi-person text-muted"></i> Profil Saya
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2 d-flex align-items-center gap-2 small" href="<?= site_url('pengaturan'); ?>">
                        <i class="bi bi-gear text-muted"></i> Pengaturan Sistem
                    </a>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <form action="<?= site_url('logout'); ?>" method="POST" class="m-0 p-0">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 small text-danger" onclick="sessionStorage.removeItem('mb_tab_auth');">
                            <i class="bi bi-box-arrow-right"></i> Keluar Akun
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
