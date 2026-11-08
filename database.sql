-- =============================================================================
-- SQL Schema & Initial Seeder — Sistem Manajemen Barang (CodeIgniter 3)
-- Version: 2.6.0
-- Compatible with MySQL 5.7+ / MariaDB 10.4+ (XAMPP)
-- =============================================================================

CREATE DATABASE IF NOT EXISTS `manajemen_barang` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `manajemen_barang`;

SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------------------------
-- 1. Table: migrations
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
    `version` BIGINT(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`version`) VALUES (1);

-- -----------------------------------------------------------------------------
-- 2. Table: ci_sessions
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
    `id` VARCHAR(128) NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `timestamp` INT(10) UNSIGNED NOT NULL DEFAULT 0,
    `data` BLOB NOT NULL,
    PRIMARY KEY (`id`),
    KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 3. Table: users
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `role` ENUM('admin', 'petugas') NOT NULL DEFAULT 'petugas',
    `password` VARCHAR(255) NOT NULL,
    `avatar` VARCHAR(255) DEFAULT NULL,
    `total_login` INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 4. Table: kategori
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama_kategori` VARCHAR(255) NOT NULL,
    `deskripsi` TEXT DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 5. Table: pemasok
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `pemasok`;
CREATE TABLE `pemasok` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama_pemasok` VARCHAR(255) NOT NULL,
    `kontak` VARCHAR(100) DEFAULT NULL,
    `alamat` TEXT DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 6. Table: produk
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `produk`;
CREATE TABLE `produk` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama_produk` VARCHAR(255) NOT NULL,
    `sku` VARCHAR(100) NOT NULL UNIQUE,
    `deskripsi` TEXT DEFAULT NULL,
    `kategori_id` INT UNSIGNED DEFAULT NULL,
    `pemasok_id` INT UNSIGNED DEFAULT NULL,
    `stok` INT NOT NULL DEFAULT 0,
    `stok_minimal` INT NOT NULL DEFAULT 10,
    `qr_code` VARCHAR(255) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`kategori_id`)
        REFERENCES `kategori` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_produk_pemasok` FOREIGN KEY (`pemasok_id`)
        REFERENCES `pemasok` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 7. Table: batch
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `batch`;
CREATE TABLE `batch` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `produk_id` INT UNSIGNED NOT NULL,
    `nomor_batch` VARCHAR(100) NOT NULL,
    `qr_code` VARCHAR(255) DEFAULT NULL,
    `kapasitas` INT NOT NULL DEFAULT 0,
    `jumlah_awal` INT NOT NULL DEFAULT 0,
    `stok_saat_ini` INT NOT NULL DEFAULT 0,
    `tanggal_masuk` DATE DEFAULT NULL,
    `tanggal_kadaluarsa` DATE DEFAULT NULL,
    `lokasi_rak` VARCHAR(100) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_batch_produk` FOREIGN KEY (`produk_id`)
        REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX `idx_batch_fifo` (`produk_id`, `tanggal_kadaluarsa`, `tanggal_masuk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 8. Table: stok_transaksi
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `stok_transaksi`;
CREATE TABLE `stok_transaksi` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `produk_id` INT UNSIGNED NOT NULL,
    `batch_id` INT UNSIGNED DEFAULT NULL,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `tipe` ENUM('masuk', 'keluar') NOT NULL,
    `jumlah` INT NOT NULL,
    `catatan` TEXT DEFAULT NULL,
    `scan_mode` VARCHAR(50) DEFAULT 'manual',
    `tanggal` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_transaksi_produk` FOREIGN KEY (`produk_id`)
        REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_transaksi_batch` FOREIGN KEY (`batch_id`)
        REFERENCES `batch` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_transaksi_user` FOREIGN KEY (`user_id`)
        REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX `idx_transaksi_tanggal` (`tanggal`),
    INDEX `idx_transaksi_tipe` (`tipe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 9. Table: sisa_stok
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `sisa_stok`;
CREATE TABLE `sisa_stok` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `produk_id` INT UNSIGNED NOT NULL UNIQUE,
    `total_stok` INT NOT NULL DEFAULT 0,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_sisa_stok_produk` FOREIGN KEY (`produk_id`)
        REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 10. Table: pengaturan
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `pengaturan`;
CREATE TABLE `pengaturan` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `kunci` VARCHAR(100) NOT NULL,
    `nilai` LONGTEXT DEFAULT NULL,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_pengaturan_user` FOREIGN KEY (`user_id`)
        REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX `idx_kunci` (`kunci`),
    INDEX `idx_user_kunci` (`user_id`, `kunci`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- SEED DATA AWAL (DEFAULT & SAMPLE DATA)
-- =============================================================================

-- Users default (Password: password -> $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi)
INSERT INTO `users` (`id`, `name`, `email`, `role`, `password`, `avatar`, `total_login`, `created_at`, `updated_at`) VALUES
(1, 'Administrator Gudang', 'admin@admin.com', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 0, NOW(), NOW()),
(2, 'Petugas Gudang', 'petugas@admin.com', 'petugas', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 0, NOW(), NOW());

-- Kategori awal
INSERT INTO `kategori` (`id`, `nama_kategori`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Elektronik & Komponen', 'Perangkat keras, sensor, kabel, dan suku cadang elektronik', NOW(), NOW()),
(2, 'Alat Tulis Kantor (ATK)', 'Kertas, pena, map arsip, dan perlengkapan administrasi', NOW(), NOW()),
(3, 'Bahan Baku & Material', 'Material mentah dan komponen perakitan gudang', NOW(), NOW()),
(4, 'Perkakas & Peralatan', 'Tools, safety equipment, dan perkakas teknisi gudang', NOW(), NOW());

-- Pemasok awal
INSERT INTO `pemasok` (`id`, `nama_pemasok`, `kontak`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'PT. Sumber Makmur Abadi', '0812-3456-7890', 'Kawasan Industri Pulogadung Blok B No. 12, Jakarta Timur', NOW(), NOW()),
(2, 'CV. Mitra Logistik Sentosa', '0813-9876-5432', 'Komplek Pergudangan Margomulyo Permai No. 45, Surabaya', NOW(), NOW());

-- Pengaturan Global & Kop Surat Dokumen (JSON)
INSERT INTO `pengaturan` (`user_id`, `kunci`, `nilai`, `updated_at`) VALUES
(NULL, 'system_preferences', '{"theme":"light","language":"id","auto_refresh":true,"auto_refresh_interval":5,"show_stock_count":true,"session_timeout":120,"two_factor":false,"barcode_prefix_product":"PRD-","barcode_prefix_batch":"LOT-","default_page_length":10,"row_density":"normal","default_sort_order":"latest","label_size":"medium","label_show_info":true,"default_min_stock":10,"date_format":"DD/MM/YYYY","fifo_enabled":true}', NOW()),
(NULL, 'company_profile', '{"nama_perusahaan":"PT. Manajemen Barang Sejahtera","tagline":"Pusat Distribusi & Pergudangan Terpadu","alamat":"Jl. Industri Pergudangan No. 88, Jakarta Barat, DKI Jakarta","telepon":"021-5558899","email":"info@manajemenbarang.com","pic_name":"Bustanul Arifin","pic_position":"Kepala Operasional Gudang","footer_note":"Dokumen ini merupakan bukti sah serah terima dan mutasi inventaris gudang."}', NOW()),
(1, 'user_preferences', '{"tx_confirm_mode":"safe","date_format":"DD/MM/YYYY","daily_digest":false,"audio_feedback":true}', NOW());

-- Produk sampel
INSERT INTO `produk` (`id`, `nama_produk`, `sku`, `deskripsi`, `kategori_id`, `pemasok_id`, `stok`, `stok_minimal`, `qr_code`, `created_at`, `updated_at`) VALUES
(1, 'Barcode Scanner Handheld 2D USB', 'PRD-0001', 'Pemindai barcode & QR code kabel USB respons cepat', 1, 1, 50, 10, 'uploads/qr/PRD-0001.png', NOW(), NOW());

-- Sisa stok snapshot sampel
INSERT INTO `sisa_stok` (`id`, `produk_id`, `total_stok`, `updated_at`) VALUES
(1, 1, 50, NOW());

-- Batch sampel
INSERT INTO `batch` (`id`, `produk_id`, `nomor_batch`, `qr_code`, `kapasitas`, `jumlah_awal`, `stok_saat_ini`, `tanggal_masuk`, `tanggal_kadaluarsa`, `lokasi_rak`, `created_at`, `updated_at`) VALUES
(1, 1, 'LOT-202611-001', 'uploads/qr/LOT-202611-001.png', 100, 50, 50, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 'RAK-A-01', NOW(), NOW());

-- Transaksi stok masuk awal
INSERT INTO `stok_transaksi` (`id`, `produk_id`, `batch_id`, `user_id`, `tipe`, `jumlah`, `catatan`, `scan_mode`, `tanggal`, `created_at`) VALUES
(1, 1, 1, 1, 'masuk', 50, 'Penerimaan stok awal registrasi produk baru', 'manual', NOW(), NOW());
