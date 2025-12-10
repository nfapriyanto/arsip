-- Migration: Ubah kolom tanggal_dokumen menjadi tahun_dokumen
-- Tanggal: 2024

-- 1. Tambahkan kolom tahun_dokumen (jika belum ada)
ALTER TABLE `tb_arsip` 
ADD COLUMN `tahun_dokumen` YEAR NULL AFTER `deskripsi`;

-- 2. Migrasi data dari tanggal_dokumen ke tahun_dokumen (ambil tahun saja)
UPDATE `tb_arsip` 
SET `tahun_dokumen` = YEAR(`tanggal_dokumen`) 
WHERE `tanggal_dokumen` IS NOT NULL;

-- 3. Hapus kolom tanggal_dokumen
ALTER TABLE `tb_arsip` 
DROP COLUMN `tanggal_dokumen`;





