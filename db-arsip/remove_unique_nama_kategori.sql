-- Migration: Hapus UNIQUE constraint pada field nama di tb_kategori_arsip
-- Tanggal: 2024
-- Deskripsi: Menghapus constraint unique_nama agar kategori bisa memiliki nama yang sama
--            (diperlukan untuk kategori bertingkat yang mungkin memiliki nama serupa)

-- Hapus UNIQUE KEY unique_nama dari tabel tb_kategori_arsip
ALTER TABLE `tb_kategori_arsip` 
DROP INDEX `unique_nama`;

-- Catatan: Setelah migration ini, field nama tidak lagi unique
-- Kategori dengan nama yang sama diperbolehkan (misalnya sub-kategori dengan nama serupa)


