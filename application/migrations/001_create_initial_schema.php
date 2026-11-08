<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_initial_schema extends CI_Migration {

    public function up()
    {
        // 1. Table users
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `users` (
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
        ");

        // 2. Table kategori
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `kategori` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `nama_kategori` VARCHAR(255) NOT NULL,
                `deskripsi` TEXT DEFAULT NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // 3. Table pemasok
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `pemasok` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `nama_pemasok` VARCHAR(255) NOT NULL,
                `kontak` VARCHAR(100) DEFAULT NULL,
                `alamat` TEXT DEFAULT NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // 4. Table produk
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `produk` (
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
        ");

        // 5. Table batch
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `batch` (
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
        ");

        // 6. Table stok_transaksi
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `stok_transaksi` (
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
        ");

        // 7. Table sisa_stok
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `sisa_stok` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `produk_id` INT UNSIGNED NOT NULL UNIQUE,
                `total_stok` INT NOT NULL DEFAULT 0,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT `fk_sisa_stok_produk` FOREIGN KEY (`produk_id`)
                    REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // 8. Table pengaturan
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `pengaturan` (
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
        ");

        // 9. Table ci_sessions
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `ci_sessions` (
                `id` VARCHAR(128) NOT NULL,
                `ip_address` VARCHAR(45) NOT NULL,
                `timestamp` INT(10) UNSIGNED NOT NULL DEFAULT 0,
                `data` BLOB NOT NULL,
                PRIMARY KEY (`id`),
                KEY `ci_sessions_timestamp` (`timestamp`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down()
    {
        $this->db->query("SET FOREIGN_KEY_CHECKS = 0;");
        $this->db->query("DROP TABLE IF EXISTS `ci_sessions`;");
        $this->db->query("DROP TABLE IF EXISTS `pengaturan`;");
        $this->db->query("DROP TABLE IF EXISTS `sisa_stok`;");
        $this->db->query("DROP TABLE IF EXISTS `stok_transaksi`;");
        $this->db->query("DROP TABLE IF EXISTS `batch`;");
        $this->db->query("DROP TABLE IF EXISTS `produk`;");
        $this->db->query("DROP TABLE IF EXISTS `pemasok`;");
        $this->db->query("DROP TABLE IF EXISTS `kategori`;");
        $this->db->query("DROP TABLE IF EXISTS `users`;");
        $this->db->query("SET FOREIGN_KEY_CHECKS = 1;");
    }
}
