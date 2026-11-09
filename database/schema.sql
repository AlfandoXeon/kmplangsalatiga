-- ============================================================================
-- Database Schema: db_kmplang
-- Sistem Informasi Resmi Keluarga Mahasiswa Perantauan Lampung (K'mplang)
-- Kota Salatiga (PHP 8.2 OOP MVC & MySQL / MariaDB)
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `db_kmplang` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_kmplang`;

-- ----------------------------------------------------------------------------
-- 1. Tabel Users (Admin, Pengurus, Anggota)
-- Menyimpan kredensial, profil akademik, asal daerah di Lampung, serta
-- nomor WhatsApp terenkripsi (AES-256-CBC + HMAC-SHA256).
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_lengkap` VARCHAR(150) NOT NULL,
    `nim` VARCHAR(30) NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'pengurus', 'anggota') NOT NULL DEFAULT 'anggota',
    `jabatan_organisasi` VARCHAR(100) NOT NULL DEFAULT 'Anggota',
    `asal_daerah` VARCHAR(150) NOT NULL,
    `fakultas` VARCHAR(150) NULL,
    `angkatan` VARCHAR(10) NULL,
    `no_whatsapp_encrypted` TEXT NULL,
    `tanggal_lahir` DATE NULL,
    `motivasi` TEXT NULL,
    `avatar` VARCHAR(255) NULL,
    `status` ENUM('pending', 'active', 'rejected') NOT NULL DEFAULT 'pending',
    `last_login_at` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_role` (`role`),
    INDEX `idx_status` (`status`),
    INDEX `idx_nim` (`nim`),
    INDEX `idx_angkatan` (`angkatan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 2. Tabel Site Settings (CMS Dinamis untuk Konten Landing Page & Info Web)
-- Memungkinkan pengurus mengubah teks halaman utama tanpa menyentuh kode.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` LONGTEXT NULL,
    `group_name` VARCHAR(50) NOT NULL DEFAULT 'general',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_setting_group` (`group_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 3. Tabel Work Programs (Transparansi Program Kerja K'mplang)
-- Dilengkapi kolom periode dan progres_persen (0-100%) untuk akuntabilitas.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `work_programs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `divisi` VARCHAR(100) NOT NULL,
    `periode` VARCHAR(20) NOT NULL DEFAULT '2026/2027',
    `nama_program` VARCHAR(255) NOT NULL,
    `tujuan` TEXT NULL,
    `indikator_kualitas` TEXT NULL,
    `indikator_kuantitas` TEXT NULL,
    `gambaran_kegiatan` TEXT NULL,
    `waktu_kegiatan` VARCHAR(255) NULL,
    `anggaran` VARCHAR(100) NULL,
    `penanggung_jawab` VARCHAR(150) NULL,
    `status` ENUM('rencana', 'berlangsung', 'selesai', 'evaluasi') NOT NULL DEFAULT 'rencana',
    `progres_persen` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_proker_divisi` (`divisi`),
    INDEX `idx_proker_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 4. Tabel Activities (Kegiatan & Album Dokumentasi)
-- Menyimpan metadata kegiatan dan sampul utama, dilengkapi kategori & sorotan.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `activities` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `judul` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `kategori_kegiatan` VARCHAR(80) NOT NULL DEFAULT 'Dokumentasi',
    `deskripsi` LONGTEXT NULL,
    `tanggal_kegiatan` DATE NULL,
    `lokasi` VARCHAR(255) NULL,
    `cover_image` VARCHAR(255) NULL,
    `author_id` INT NULL,
    `view_count` INT NOT NULL DEFAULT 0,
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_act_slug` (`slug`),
    INDEX `idx_act_date` (`tanggal_kegiatan`),
    INDEX `idx_act_featured` (`is_featured`),
    CONSTRAINT `fk_activities_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 5. Tabel Activity Media (Penyimpanan Berkas Foto & Video Album)
-- Berelasi one-to-many dengan tabel activities.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `activity_media` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `activity_id` INT NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255) NOT NULL,
    `file_type` ENUM('image', 'video') NOT NULL DEFAULT 'image',
    `mime_type` VARCHAR(100) NOT NULL,
    `file_size` BIGINT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_media_activity` (`activity_id`),
    INDEX `idx_media_type` (`file_type`),
    CONSTRAINT `fk_media_activity` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 6. Tabel Articles (Artikel, Sejarah Lampung, Warta K'mplang)
-- Dilengkapi ringkasan editorial dan estimasi waktu baca (menit).
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `articles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `judul` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `kategori` VARCHAR(100) NOT NULL DEFAULT 'Berita',
    `ringkasan` TEXT NULL,
    `konten` LONGTEXT NOT NULL,
    `cover_image` VARCHAR(255) NULL,
    `author_id` INT NULL,
    `status` ENUM('draft', 'published') NOT NULL DEFAULT 'published',
    `view_count` INT NOT NULL DEFAULT 0,
    `waktu_baca_menit` SMALLINT UNSIGNED NOT NULL DEFAULT 3,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_art_slug` (`slug`),
    INDEX `idx_art_status` (`status`),
    INDEX `idx_art_kategori` (`kategori`),
    CONSTRAINT `fk_articles_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 7. Tabel Comments (Komentar Anggota pada Kegiatan & Artikel)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `comments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `post_type` ENUM('activity', 'article') NOT NULL,
    `post_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `content` TEXT NOT NULL,
    `parent_id` INT NULL,
    `status` ENUM('approved', 'pending', 'spam') NOT NULL DEFAULT 'approved',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_comments_target` (`post_type`, `post_id`),
    INDEX `idx_comments_user` (`user_id`),
    CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 8. Tabel Announcements (Papan Poster Pengumuman & Agenda Terdekat)
-- Fitur pengumuman/bulletin yang dikelola oleh pengurus/admin.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `announcements` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `judul` VARCHAR(255) NOT NULL,
    `isi` TEXT NOT NULL,
    `tipe` ENUM('agenda', 'pengumuman', 'penting') NOT NULL DEFAULT 'pengumuman',
    `tanggal_acara` DATE NULL,
    `lokasi` VARCHAR(200) NULL,
    `tautan` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `author_id` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_ann_active` (`is_active`),
    INDEX `idx_ann_tipe` (`tipe`),
    CONSTRAINT `fk_announcements_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 9. Tabel Audit Logs (Security Tracking & Audit Trail Sistem)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `action` VARCHAR(150) NOT NULL,
    `target_table` VARCHAR(100) NULL,
    `target_id` INT NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `details` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_audit_user` (`user_id`),
    INDEX `idx_audit_action` (`action`),
    CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
