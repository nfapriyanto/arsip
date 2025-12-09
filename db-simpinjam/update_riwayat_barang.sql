-- Migration: Update struktur tabel riwayat dan barang
-- Tanggal: 2024
-- 
-- Perubahan:
-- 1. Hapus field label dan jumlah dari tb_riwayat
-- 2. Rename field unit menjadi peminjam di tb_riwayat
-- 3. Tambahkan field is_available di tb_barang untuk menandai apakah stock tersedia

-- 1. Hapus kolom label dari tb_riwayat
ALTER TABLE `tb_riwayat` 
DROP COLUMN `label`;

-- 2. Hapus kolom jumlah dari tb_riwayat
ALTER TABLE `tb_riwayat` 
DROP COLUMN `jumlah`;

-- 3. Rename kolom unit menjadi peminjam di tb_riwayat
ALTER TABLE `tb_riwayat` 
CHANGE COLUMN `unit` `peminjam` varchar(50) NOT NULL;

-- 4. Tambahkan kolom is_available di tb_barang
ALTER TABLE `tb_barang` 
ADD COLUMN `is_available` tinyint(1) NOT NULL DEFAULT 1 AFTER `kode_qr`;

-- 5. Set is_available berdasarkan riwayat terakhir
-- Jika ada peminjaman aktif (belum dikembalikan), set is_available = 0
UPDATE `tb_barang` b
SET b.`is_available` = 0
WHERE EXISTS (
    SELECT 1 
    FROM (
        SELECT r1.kode, r1.jenis, r1.createDate
        FROM tb_riwayat r1
        WHERE r1.jenis = 'Peminjaman'
        AND NOT EXISTS (
            SELECT 1 
            FROM tb_riwayat r2 
            WHERE r2.kode = r1.kode 
            AND r2.jenis = 'Pengembalian' 
            AND r2.createDate > r1.createDate
        )
    ) r
    WHERE r.kode = b.kode
);

-- Catatan:
-- - Field label dan jumlah dihapus dari tb_riwayat
-- - Field unit diubah menjadi peminjam
-- - Field is_available menandai apakah barang tersedia (1 = tersedia, 0 = dipinjam)
-- - is_available akan diupdate otomatis saat ada peminjaman/pengembalian




