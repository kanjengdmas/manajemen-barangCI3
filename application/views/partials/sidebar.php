<aside class="app-sidebar" id="app-sidebar">
    <!-- Brand / Logo Area -->
    <div class="sidebar-brand-header">
        <a href="<?= site_url('dashboard'); ?>" class="sidebar-brand" title="Manajemen Barang">
            <div class="sidebar-brand-logo">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <div class="sidebar-brand-info">
                <span class="sidebar-brand-title">Manajemen<span class="brand-accent">Barang</span></span>
                <span class="sidebar-brand-badge">Gudang Pro · v2.6</span>
            </div>
        </a>
        <button type="button" class="btn-icon-quiet d-lg-none" id="sidebar-close-btn" aria-label="Tutup Menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <!-- Quick Search Command Filter -->
    <div class="sidebar-search-box">
        <i class="bi bi-search"></i>
        <input type="text" id="sidebar-quick-search" class="sidebar-search-input" placeholder="Cari menu & fitur..." autocomplete="off">
        <kbd class="sidebar-search-kbd">Ctrl+K</kbd>
    </div>

    <!-- Scrollable Navigation Menu -->
    <div class="sidebar-scrollable" id="sidebar-nav-container">
        <!-- Overview Group -->
        <div class="sidebar-section-label">Ringkasan</div>
        <ul class="sidebar-menu-list">
            <li>
                <a href="<?= site_url('dashboard'); ?>" class="sidebar-nav-link <?= (isset($active_menu) && $active_menu === 'dashboard') ? 'active' : ''; ?>">
                    <div class="sidebar-nav-link-content">
                        <span class="sidebar-nav-icon"><i class="bi bi-grid-1x2-fill"></i></span>
                        <span class="sidebar-nav-label">Dashboard</span>
                    </div>
                    <?php if (isset($active_menu) && $active_menu === 'dashboard'): ?>
                        <span class="sidebar-pill pill-dot" title="Halaman Aktif"></span>
                    <?php endif; ?>
                </a>
            </li>
        </ul>

        <!-- Master Data Group -->
        <div class="sidebar-section-label">Master Data</div>
        <ul class="sidebar-menu-list">
            <li>
                <a href="<?= site_url('produk'); ?>" class="sidebar-nav-link <?= (isset($active_menu) && $active_menu === 'produk') ? 'active' : ''; ?>">
                    <div class="sidebar-nav-link-content">
                        <span class="sidebar-nav-icon"><i class="bi bi-boxes"></i></span>
                        <span class="sidebar-nav-label">Katalog Produk</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?= site_url('kategori'); ?>" class="sidebar-nav-link <?= (isset($active_menu) && $active_menu === 'kategori') ? 'active' : ''; ?>">
                    <div class="sidebar-nav-link-content">
                        <span class="sidebar-nav-icon"><i class="bi bi-tags-fill"></i></span>
                        <span class="sidebar-nav-label">Kategori Barang</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?= site_url('pemasok'); ?>" class="sidebar-nav-link <?= (isset($active_menu) && $active_menu === 'pemasok') ? 'active' : ''; ?>">
                    <div class="sidebar-nav-link-content">
                        <span class="sidebar-nav-icon"><i class="bi bi-truck"></i></span>
                        <span class="sidebar-nav-label">Pemasok / Supplier</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?= site_url('batch'); ?>" class="sidebar-nav-link <?= (isset($active_menu) && $active_menu === 'batch') ? 'active' : ''; ?>">
                    <div class="sidebar-nav-link-content">
                        <span class="sidebar-nav-icon"><i class="bi bi-layers-fill"></i></span>
                        <span class="sidebar-nav-label">Lot &amp; Batch Kardus</span>
                    </div>
                    <span class="sidebar-pill pill-fifo">FIFO</span>
                </a>
            </li>
        </ul>

        <!-- Inventory Operations Group -->
        <div class="sidebar-section-label">Inventaris &amp; Logistik</div>
        <ul class="sidebar-menu-list">
            <li>
                <a href="<?= site_url('stok'); ?>" class="sidebar-nav-link <?= (isset($active_menu) && $active_menu === 'stok') ? 'active' : ''; ?>">
                    <div class="sidebar-nav-link-content">
                        <span class="sidebar-nav-icon"><i class="bi bi-arrow-left-right"></i></span>
                        <span class="sidebar-nav-label">Riwayat Mutasi</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?= site_url('stok/masuk'); ?>" class="sidebar-nav-link <?= (isset($active_menu) && $active_menu === 'stok_masuk') ? 'active' : ''; ?>">
                    <div class="sidebar-nav-link-content">
                        <span class="sidebar-nav-icon"><i class="bi bi-box-arrow-in-down text-success"></i></span>
                        <span class="sidebar-nav-label">Stok Masuk</span>
                    </div>
                    <span class="sidebar-pill pill-in"><i class="bi bi-arrow-down-left"></i></span>
                </a>
            </li>
            <li>
                <a href="<?= site_url('stok/keluar'); ?>" class="sidebar-nav-link <?= (isset($active_menu) && $active_menu === 'stok_keluar') ? 'active' : ''; ?>">
                    <div class="sidebar-nav-link-content">
                        <span class="sidebar-nav-icon"><i class="bi bi-box-arrow-up text-danger"></i></span>
                        <span class="sidebar-nav-label">Stok Keluar</span>
                    </div>
                    <span class="sidebar-pill pill-fifo">FIFO</span>
                </a>
            </li>
            <li>
                <a href="<?= site_url('scan'); ?>" class="sidebar-nav-link <?= (isset($active_menu) && $active_menu === 'scan') ? 'active' : ''; ?>">
                    <div class="sidebar-nav-link-content">
                        <span class="sidebar-nav-icon"><i class="bi bi-qr-code-scan"></i></span>
                        <span class="sidebar-nav-label">Pemindai QR</span>
                    </div>
                    <span class="sidebar-pill pill-scan"><i class="bi bi-qr-code"></i></span>
                </a>
            </li>
            <li>
                <a href="<?= site_url('laporan'); ?>" class="sidebar-nav-link <?= (isset($active_menu) && $active_menu === 'laporan') ? 'active' : ''; ?>">
                    <div class="sidebar-nav-link-content">
                        <span class="sidebar-nav-icon"><i class="bi bi-file-earmark-bar-graph"></i></span>
                        <span class="sidebar-nav-label">Laporan &amp; Ekspor</span>
                    </div>
                </a>
            </li>
        </ul>

        <!-- Preferences Group -->
        <div class="sidebar-section-label">Sistem &amp; Akun</div>
        <ul class="sidebar-menu-list">
            <li>
                <a href="<?= site_url('profil'); ?>" class="sidebar-nav-link <?= (isset($active_menu) && $active_menu === 'profil') ? 'active' : ''; ?>">
                    <div class="sidebar-nav-link-content">
                        <span class="sidebar-nav-icon"><i class="bi bi-person-circle"></i></span>
                        <span class="sidebar-nav-label">Profil Saya</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?= site_url('pengaturan'); ?>" class="sidebar-nav-link <?= (isset($active_menu) && $active_menu === 'pengaturan') ? 'active' : ''; ?>">
                    <div class="sidebar-nav-link-content">
                        <span class="sidebar-nav-icon"><i class="bi bi-gear-fill"></i></span>
                        <span class="sidebar-nav-label">Pengaturan Sistem</span>
                    </div>
                </a>
            </li>
        </ul>

        <!-- Warehouse Health Micro Widget -->
        <div class="sidebar-health-widget">
            <div class="sidebar-health-header">
                <span class="sidebar-health-title">
                    <span class="sidebar-health-beacon"></span> Gudang Aktif
                </span>
                <span class="sidebar-health-meta">DB OK</span>
            </div>
            <div class="sidebar-health-bar">
                <div class="sidebar-health-progress" style="width: 78%;"></div>
            </div>
        </div>
    </div>

    <!-- User Profile Footer Card -->
    <div class="sidebar-user-footer">
        <div class="sidebar-user-details">
            <div class="sidebar-user-avatar">
                <?php if (!empty($user['avatar']) && file_exists(FCPATH . 'uploads/avatar/' . $user['avatar'])): ?>
                    <img src="<?= base_url('uploads/avatar/' . $user['avatar']); ?>" alt="Avatar">
                <?php else: ?>
                    <?= strtoupper(substr($user['name'] ?? 'A', 0, 1)); ?>
                <?php endif; ?>
                <span class="sidebar-user-status-dot" title="Online"></span>
            </div>
            <div class="sidebar-user-meta">
                <span class="sidebar-user-name" title="<?= html_escape($user['name'] ?? 'Administrator'); ?>">
                    <?= html_escape($user['name'] ?? 'Administrator'); ?>
                </span>
                <span class="sidebar-user-role"><?= strtoupper($user['role'] ?? 'ADMIN'); ?></span>
            </div>
        </div>
        <div class="sidebar-user-actions">
            <a href="<?= site_url('pengaturan'); ?>" class="sidebar-user-btn" title="Pengaturan">
                <i class="bi bi-gear"></i>
            </a>
            <form action="<?= site_url('logout'); ?>" method="POST" class="d-inline m-0 p-0">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <button type="submit" class="sidebar-user-btn logout" title="Keluar Akun" aria-label="Keluar">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
