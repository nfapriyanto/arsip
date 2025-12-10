-- Migration: Tambah kolom parent_id untuk kategori bertingkat
-- Tanggal: 2024

-- 1. Tambahkan kolom parent_id
ALTER TABLE `tb_kategori_arsip` 
ADD COLUMN `parent_id` int(11) NULL DEFAULT NULL AFTER `deskripsi`;

-- 2. Tambahkan index untuk performa
ALTER TABLE `tb_kategori_arsip`
ADD KEY `idx_parent_id` (`parent_id`);

-- 3. Tambahkan foreign key constraint (optional, bisa di-comment jika ada masalah)
-- ALTER TABLE `tb_kategori_arsip`
-- ADD CONSTRAINT `fk_kategori_parent` 
-- FOREIGN KEY (`parent_id`) REFERENCES `tb_kategori_arsip` (`id`) 
-- ON DELETE SET NULL ON UPDATE CASCADE;

