-- Migration: Pisahkan kolom Keterangan menjadi Asli/Kopi dan Box
-- Tanggal: 2024
-- Untuk database yang sudah menggunakan struktur baru dengan field keterangan

-- 1. Tambahkan kolom baru
ALTER TABLE `tb_arsip` 
ADD COLUMN `asli_kopi` enum('Asli','Kopi') NULL COMMENT 'Asli atau Kopi' AFTER `jumlah_berkas`,
ADD COLUMN `box` varchar(100) NULL COMMENT 'Nomor Box' AFTER `asli_kopi`;

-- 2. Migrasi data dari keterangan ke asli_kopi dan box
-- Extract Asli/Kopi
UPDATE `tb_arsip` 
SET `asli_kopi` = CASE 
    WHEN `keterangan` LIKE '%ASLI%' OR `keterangan` LIKE '%Asli%' OR `keterangan` LIKE '%asli%' THEN 'Asli'
    WHEN `keterangan` LIKE '%KOPI%' OR `keterangan` LIKE '%Kopi%' OR `keterangan` LIKE '%kopi%' THEN 'Kopi'
    ELSE NULL
END
WHERE `keterangan` IS NOT NULL;

-- Extract nomor box dari keterangan (format: BOX 1, BOX-1, Box 1, dll)
UPDATE `tb_arsip` 
SET `box` = TRIM(REGEXP_REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(`keterangan`, 'BOX', -1), ',', 1), '[^0-9]', ''))
WHERE `keterangan` IS NOT NULL AND (`keterangan` LIKE '%BOX%' OR `keterangan` LIKE '%Box%' OR `keterangan` LIKE '%box%');

-- Jika masih ada format lain, coba extract manual
-- Contoh: "ASLI, BOX 1" -> asli_kopi='Asli', box='1'
UPDATE `tb_arsip` 
SET `box` = TRIM(REPLACE(REPLACE(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(`keterangan`, 'BOX', -1), ',', 1), 'BOX', ''), 'Box', ''), 'box', ''))
WHERE `keterangan` IS NOT NULL AND `box` IS NULL AND (`keterangan` LIKE '%BOX%' OR `keterangan` LIKE '%Box%' OR `keterangan` LIKE '%box%');

-- 3. Hapus kolom keterangan
ALTER TABLE `tb_arsip` 
DROP COLUMN `keterangan`;

