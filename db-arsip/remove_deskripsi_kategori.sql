-- Migration: Hapus kolom deskripsi dari tabel tb_kategori_arsip
-- Tanggal: 2024
-- Deskripsi: Menghapus kolom deskripsi yang tidak diperlukan lagi

-- Hapus kolom deskripsi dari tabel tb_kategori_arsip
ALTER TABLE `tb_kategori_arsip` 
DROP COLUMN `deskripsi`;

