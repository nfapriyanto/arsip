-- Migration: Ubah kolom tahun dari YEAR menjadi DATE
-- Tanggal: 2025

-- 1. Ubah tipe kolom tahun dari YEAR ke DATE
-- Jika ada data tahun, konversi ke tanggal (1 Januari tahun tersebut)
ALTER TABLE `tb_arsip` 
MODIFY COLUMN `tahun` DATE NULL;

-- 2. Migrasi data: Konversi tahun (YEAR) ke tanggal (DATE)
-- Format: YYYY -> YYYY-01-01
UPDATE `tb_arsip` 
SET `tahun` = CONCAT(`tahun`, '-01-01')
WHERE `tahun` IS NOT NULL AND `tahun` != '' AND `tahun` != '0000-00-00';

-- 3. Set NULL untuk data yang tidak valid
UPDATE `tb_arsip` 
SET `tahun` = NULL
WHERE `tahun` = '0000-00-00' OR `tahun` = '';

