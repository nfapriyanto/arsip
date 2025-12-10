-- Migration: Tambah field nama_pengisi dan link_drive, serta ubah nama_file dan path_file menjadi nullable
-- Tanggal: 2024

-- 1. Tambahkan kolom baru
ALTER TABLE `tb_arsip` 
ADD COLUMN `nama_pengisi` varchar(256) NULL COMMENT 'Nama pengisi arsip' AFTER `klasifikasi_keamanan`,
ADD COLUMN `link_drive` varchar(1000) NULL COMMENT 'Link Google Drive' AFTER `nama_pengisi`;

-- 2. Ubah nama_file dan path_file menjadi nullable
ALTER TABLE `tb_arsip` 
MODIFY COLUMN `nama_file` varchar(500) NULL,
MODIFY COLUMN `path_file` varchar(1000) NULL;

