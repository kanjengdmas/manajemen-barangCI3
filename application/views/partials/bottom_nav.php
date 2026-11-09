<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Modern Mobile Bottom Navigation Bar (Docked App Bar for Smartphones) -->
<nav class="mobile-bottom-nav d-lg-none" aria-label="Navigasi Bawah Mobile">
    <div class="mobile-bottom-nav-inner">
        <!-- 1. Dashboard / Beranda -->
        <a href="<?= site_url('dashboard'); ?>" class="mobile-nav-item <?= (isset($active_menu) && $active_menu === 'dashboard') ? 'active' : ''; ?>">
            <div class="mobile-nav-icon-wrap">
                <i class="bi bi-grid-1x2-fill"></i>
            </div>
            <span class="mobile-nav-label">Ringkasan</span>
        </a>

        <!-- 2. Katalog Produk -->
        <a href="<?= site_url('produk'); ?>" class="mobile-nav-item <?= (isset($active_menu) && $active_menu === 'produk') ? 'active' : ''; ?>">
            <div class="mobile-nav-icon-wrap">
                <i class="bi bi-boxes"></i>
            </div>
            <span class="mobile-nav-label">Produk</span>
        </a>

        <!-- 3. Raised Center Action Button: Scanner QR -->
        <a href="<?= site_url('scan'); ?>" class="mobile-nav-item mobile-nav-center <?= (isset($active_menu) && $active_menu === 'scan') ? 'active' : ''; ?>" title="Pemindai Barcode / QR">
            <div class="mobile-nav-center-btn">
                <i class="bi bi-qr-code-scan"></i>
            </div>
            <span class="mobile-nav-label">Scan QR</span>
        </a>

        <!-- 4. Riwayat Mutasi Stok -->
        <a href="<?= site_url('stok'); ?>" class="mobile-nav-item <?= (isset($active_menu) && in_array($active_menu, ['stok', 'stok_masuk', 'stok_keluar'])) ? 'active' : ''; ?>">
            <div class="mobile-nav-icon-wrap">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <span class="mobile-nav-label">Mutasi</span>
        </a>

        <!-- 5. Menu Drawer Trigger (Opens Sidebar for Master Data, Batch, Laporan, Pengaturan, Profil) -->
        <button type="button" class="mobile-nav-item" id="bottom-nav-menu-btn" aria-label="Buka Seluruh Menu">
            <div class="mobile-nav-icon-wrap">
                <i class="bi bi-grid-fill"></i>
            </div>
            <span class="mobile-nav-label">Menu</span>
        </button>
    </div>
</nav>
