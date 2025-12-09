-- Migration: Menambahkan field kode_qr untuk QR Code unik setiap barang
-- Tanggal: 2024

-- Tambahkan kolom kode_qr di tabel tb_barang
ALTER TABLE `tb_barang` 
ADD COLUMN `kode_qr` VARCHAR(50) NULL AFTER `kode`,
ADD UNIQUE KEY `unique_kode_qr` (`kode_qr`);

-- Update data existing dengan kode_qr (generate kode unik)
-- Format: # + random alphanumeric 10 karakter
UPDATE `tb_barang` SET `kode_qr` = CONCAT('#', UPPER(SUBSTRING(MD5(CONCAT(id, kode, createDate)), 1, 10))) WHERE `kode_qr` IS NULL;

-- Catatan: 
-- - Field kode_qr akan digunakan untuk QR Code scanning
-- - Field kode tetap digunakan untuk nama barang
-- - Setiap barang akan memiliki kode_qr unik yang bisa di-generate otomatis






