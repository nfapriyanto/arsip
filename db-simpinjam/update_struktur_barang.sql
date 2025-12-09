-- Migration: Update struktur tabel barang dan kategori
-- Tanggal: 2024
-- 
-- Perubahan:
-- 1. Hapus field stok dari tb_barang (stok dihitung dari jumlah barang per kategori)
-- 2. Pindahkan field tempat dari tb_barang ke tb_kategori

-- 1. Tambahkan kolom tempat di tb_kategori jika belum ada
ALTER TABLE `tb_kategori` 
ADD COLUMN `tempat` varchar(50) NULL AFTER `nama`;

-- 2. Migrasi data tempat dari tb_barang ke tb_kategori
-- Ambil tempat pertama dari setiap kategori (jika ada beberapa tempat, ambil yang pertama)
UPDATE `tb_kategori` k
INNER JOIN (
    SELECT b1.kategori_id, b1.tempat
    FROM tb_barang b1
    INNER JOIN (
        SELECT kategori_id, MIN(id) as min_id
        FROM tb_barang
        WHERE kategori_id IS NOT NULL AND tempat IS NOT NULL AND tempat != ''
        GROUP BY kategori_id
    ) b2 ON b1.kategori_id = b2.kategori_id AND b1.id = b2.min_id
) b ON k.id = b.kategori_id
SET k.tempat = b.tempat
WHERE (k.tempat IS NULL OR k.tempat = '') AND b.tempat IS NOT NULL AND b.tempat != '';

-- 3. Hapus kolom stok dari tb_barang
ALTER TABLE `tb_barang` 
DROP COLUMN `stok`;

-- 4. Hapus kolom tempat dari tb_barang
ALTER TABLE `tb_barang` 
DROP COLUMN `tempat`;

-- Catatan:
-- - Stok sekarang dihitung dari COUNT(*) barang per kategori
-- - Tempat sekarang disimpan di tb_kategori, bukan di tb_barang
-- - Setiap kategori memiliki satu tempat

