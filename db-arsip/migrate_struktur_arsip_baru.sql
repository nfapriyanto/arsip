-- Migration: Ubah struktur tabel tb_arsip sesuai field baru
-- Tanggal: 2024

-- 1. Tambahkan kolom baru
ALTER TABLE `tb_arsip` 
ADD COLUMN `no_berkas` varchar(100) NULL AFTER `kategori_id`,
ADD COLUMN `no_urut` int(11) NULL AFTER `no_berkas`,
ADD COLUMN `kode` varchar(100) NULL AFTER `no_urut`,
ADD COLUMN `indeks_pekerjaan` varchar(500) NULL AFTER `kode`,
ADD COLUMN `uraian_masalah_kegiatan` text NULL AFTER `indeks_pekerjaan`,
ADD COLUMN `tahun` YEAR NULL AFTER `uraian_masalah_kegiatan`,
  ADD COLUMN `jumlah_berkas` int(11) DEFAULT 1 AFTER `tahun`,
  ADD COLUMN `asli_kopi` enum('Asli','Kopi') NULL COMMENT 'Asli atau Kopi' AFTER `jumlah_berkas`,
  ADD COLUMN `box` varchar(100) NULL COMMENT 'Nomor Box' AFTER `asli_kopi`,
  ADD COLUMN `klasifikasi_keamanan` varchar(100) NULL COMMENT 'Klasifikasi keamanan dan akses arsip dinamis' AFTER `box`;

-- 2. Migrasi data dari field lama ke field baru (jika ada data)
-- NO BERKAS dari nomor_arsip
UPDATE `tb_arsip` SET `no_berkas` = `nomor_arsip` WHERE `nomor_arsip` IS NOT NULL;

-- TAHUN dari tahun_dokumen
UPDATE `tb_arsip` SET `tahun` = `tahun_dokumen` WHERE `tahun_dokumen` IS NOT NULL;

-- URAIAN MASALAH/KEGIATAN dari judul dan deskripsi
UPDATE `tb_arsip` SET `uraian_masalah_kegiatan` = CONCAT(IFNULL(`judul`, ''), IF(`deskripsi` IS NOT NULL AND `deskripsi` != '', CONCAT(' - ', `deskripsi`), '')));

-- Migrasi KETERANGAN menjadi ASLI/KOPI dan BOX
-- Jika keterangan mengandung "ASLI" atau "KOPI", extract ke asli_kopi
UPDATE `tb_arsip` 
SET `asli_kopi` = CASE 
    WHEN `keterangan` LIKE '%ASLI%' OR `keterangan` LIKE '%Asli%' THEN 'Asli'
    WHEN `keterangan` LIKE '%KOPI%' OR `keterangan` LIKE '%Kopi%' THEN 'Kopi'
    ELSE NULL
END
WHERE `keterangan` IS NOT NULL;

-- Extract nomor box dari keterangan (format: BOX 1, BOX-1, Box 1, dll)
UPDATE `tb_arsip` 
SET `box` = TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(`keterangan`, 'BOX', -1), ',', 1))
WHERE `keterangan` IS NOT NULL AND `keterangan` LIKE '%BOX%';

-- 3. Hapus kolom lama (setelah migrasi data)
ALTER TABLE `tb_arsip` 
DROP COLUMN `nomor_arsip`,
DROP COLUMN `judul`,
DROP COLUMN `deskripsi`,
DROP COLUMN `tahun_dokumen`,
DROP COLUMN `pembuat`,
DROP COLUMN `status`,
DROP COLUMN `keterangan`;

-- 4. Update index
-- Hapus index lama jika ada (cek manual jika error)
-- ALTER TABLE `tb_arsip` DROP KEY `nomor_arsip`;
ALTER TABLE `tb_arsip`
ADD KEY `idx_no_berkas` (`no_berkas`),
ADD KEY `idx_kode` (`kode`);

