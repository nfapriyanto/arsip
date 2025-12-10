# Perubahan Struktur Tabel Arsip

## Field Baru

Tabel `tb_arsip` telah diubah dengan field berikut:

1. **NO BERKAS** (`no_berkas`) - varchar(100)
2. **NO URUT** (`no_urut`) - int(11)
3. **KODE** (`kode`) - varchar(100)
4. **INDEKS/PEKERJAAN** (`indeks_pekerjaan`) - varchar(500)
5. **URAIAN MASALAH/KEGIATAN** (`uraian_masalah_kegiatan`) - text
6. **TAHUN** (`tahun`) - YEAR
7. **JUMLAH BERKAS** (`jumlah_berkas`) - int(11), default: 1
8. **ASLI/KOPI** (`asli_kopi`) - enum('Asli','Kopi')
9. **BOX** (`box`) - varchar(100) - Nomor Box
10. **KLASIFIKASI KEAMANAN DAN AKSES ARSIP DINAMIS** (`klasifikasi_keamanan`) - varchar(100)

## Field yang Dihapus

- `nomor_arsip`
- `judul`
- `deskripsi`
- `tahun_dokumen`
- `pembuat`
- `status`
- `keterangan` (diganti menjadi `asli_kopi` dan `box`)

## Migration

Untuk database yang sudah ada, jalankan file:
```
db-arsip/migrate_struktur_arsip_baru.sql
```

Atau jalankan manual:
```sql
-- Tambah kolom baru
ALTER TABLE `tb_arsip` 
ADD COLUMN `no_berkas` varchar(100) NULL AFTER `kategori_id`,
ADD COLUMN `no_urut` int(11) NULL AFTER `no_berkas`,
ADD COLUMN `kode` varchar(100) NULL AFTER `no_urut`,
ADD COLUMN `indeks_pekerjaan` varchar(500) NULL AFTER `kode`,
ADD COLUMN `uraian_masalah_kegiatan` text NULL AFTER `indeks_pekerjaan`,
ADD COLUMN `tahun` YEAR NULL AFTER `uraian_masalah_kegiatan`,
ADD COLUMN `jumlah_berkas` int(11) DEFAULT 1 AFTER `tahun`,
ADD COLUMN `keterangan` varchar(500) NULL COMMENT 'ASLI/KOPI, BOX' AFTER `jumlah_berkas`,
ADD COLUMN `klasifikasi_keamanan` varchar(100) NULL COMMENT 'Klasifikasi keamanan dan akses arsip dinamis' AFTER `keterangan`;

-- Migrasi data (jika ada)
UPDATE `tb_arsip` SET `no_berkas` = `nomor_arsip` WHERE `nomor_arsip` IS NOT NULL;
UPDATE `tb_arsip` SET `tahun` = `tahun_dokumen` WHERE `tahun_dokumen` IS NOT NULL;
UPDATE `tb_arsip` SET `uraian_masalah_kegiatan` = CONCAT(IFNULL(`judul`, ''), IF(`deskripsi` IS NOT NULL AND `deskripsi` != '', CONCAT(' - ', `deskripsi`), ''));

-- Hapus kolom lama
ALTER TABLE `tb_arsip` 
DROP COLUMN `nomor_arsip`,
DROP COLUMN `judul`,
DROP COLUMN `deskripsi`,
DROP COLUMN `tahun_dokumen`,
DROP COLUMN `pembuat`,
DROP COLUMN `status`;

-- Update index
ALTER TABLE `tb_arsip`
DROP KEY `nomor_arsip`,
ADD KEY `idx_no_berkas` (`no_berkas`),
ADD KEY `idx_kode` (`kode`);
```

## Klasifikasi Keamanan

Nilai yang tersedia:
- Umum
- Terbatas
- Rahasia
- Sangat Rahasia

## Field ASLI/KOPI dan BOX

**ASLI/KOPI** (`asli_kopi`):
- Tipe: ENUM('Asli','Kopi')
- Pilihan: Asli atau Kopi

**BOX** (`box`):
- Tipe: VARCHAR(100)
- Format: Bebas (contoh: "1", "2", "A-1", "BOX-1")

## Catatan

- Field `no_berkas` akan di-generate otomatis jika kosong saat insert
- Field `jumlah_berkas` default: 1
- Field `tahun` menggunakan tipe YEAR (4 digit)

