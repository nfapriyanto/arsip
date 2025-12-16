-- Migration: Memisahkan kode arsip ke tabel baru
-- Tanggal: 2024

-- 1. Buat tabel baru tb_kode_arsip
CREATE TABLE IF NOT EXISTS `tb_kode_arsip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) NOT NULL COMMENT 'Kode arsip (contoh: HK01, HK0101, dll)',
  `nama` varchar(500) NOT NULL COMMENT 'Nama/deskripsi kode',
  `createDate` datetime NOT NULL,
  `updateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Insert data kode default
INSERT INTO `tb_kode_arsip` (`kode`, `nama`, `createDate`) VALUES
('HK01', 'HK01 – Produk Hukum', NOW()),
('HK0101', 'HK0101 – Produk Hukum Pengaturan (PP/Permen, Pedoman, Juklak, Instruksi, SE, dll.)', NOW()),
('HK0102', 'HK0102 – Produk Hukum Penetapan (Keputusan: penetapan kegiatan, penetapan pelaksana, dll.)', NOW()),
('HK02', 'HK02 – Perjanjian Kerjasama', NOW()),
('HK0201', 'HK0201 – Perjanjian Kerjasama Dalam Negeri', NOW()),
('HK0202', 'HK0202 – Perjanjian Kerjasama Luar Negeri', NOW()),
('HK03', 'HK03 – Sosialisasi Hukum (penyebarluasan regulasi & instrumen hukum)', NOW()),
('HK04', 'HK04 – Dokumentasi Hukum (JDIH, dokumentasi & informasi hukum)', NOW()),
('HK05', 'HK05 – Hak Kekayaan Intelektual (HKI) (pengurusan & penyelesaian urusan HKI)', NOW()),
('HK06', 'HK06 – Advokasi Hukum', NOW()),
('HK0601', 'HK0601 – Advokasi Hukum Perdata', NOW()),
('HK0602', 'HK0602 – Advokasi Hukum Pidana', NOW()),
('HK0603', 'HK0603 – Advokasi Hukum TUN', NOW());

-- 3. Tambahkan kolom kode_id di tb_arsip
ALTER TABLE `tb_arsip` 
ADD COLUMN `kode_id` int(11) DEFAULT NULL COMMENT 'Foreign key ke tb_kode_arsip' AFTER `kode`;

-- 4. Migrasi data kode yang sudah ada ke kode_id
-- Update kode yang sesuai dengan data di tb_kode_arsip
UPDATE `tb_arsip` a
INNER JOIN `tb_kode_arsip` k ON a.kode = k.kode
SET a.kode_id = k.id;

-- 5. Buat foreign key constraint
ALTER TABLE `tb_arsip`
ADD CONSTRAINT `fk_arsip_kode` FOREIGN KEY (`kode_id`) REFERENCES `tb_kode_arsip` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- 6. (Opsional) Hapus kolom kode lama setelah migrasi selesai
-- Jangan dijalankan dulu, biarkan untuk backup
-- ALTER TABLE `tb_arsip` DROP COLUMN `kode`;




