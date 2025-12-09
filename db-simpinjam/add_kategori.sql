-- Migration: Menambahkan tabel kategori dan relasi dengan barang
-- Tanggal: 2024

-- 1. Buat tabel tb_kategori
CREATE TABLE IF NOT EXISTS `tb_kategori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(256) NOT NULL,
  `createDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nama` (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 2. Tambahkan kolom kategori_id di tabel tb_barang
ALTER TABLE `tb_barang` 
ADD COLUMN `kategori_id` int(11) NULL AFTER `kode`;

-- 3. Migrasi data existing: Buat kategori dari kode yang unik
INSERT INTO `tb_kategori` (`nama`, `createDate`)
SELECT DISTINCT `kode`, MIN(`createDate`) as `createDate`
FROM `tb_barang`
GROUP BY `kode`;

-- 4. Update tb_barang untuk menghubungkan dengan kategori
UPDATE `tb_barang` b
INNER JOIN `tb_kategori` k ON b.`kode` = k.`nama`
SET b.`kategori_id` = k.`id`;

-- 5. Buat foreign key constraint
ALTER TABLE `tb_barang`
ADD CONSTRAINT `fk_barang_kategori` 
FOREIGN KEY (`kategori_id`) REFERENCES `tb_kategori` (`id`) 
ON DELETE SET NULL ON UPDATE CASCADE;

-- 6. Buat index untuk performa
ALTER TABLE `tb_barang`
ADD KEY `idx_kategori_id` (`kategori_id`);

-- Catatan: 
-- - Tabel tb_kategori menyimpan kategori barang (Laptop, LCD Proyektor, dll)
-- - Tabel tb_barang sekarang memiliki relasi dengan kategori melalui kategori_id
-- - Field kode di tb_barang tetap ada untuk backward compatibility
-- - Setiap barang akan memiliki kategori_id yang menghubungkan ke tb_kategori




