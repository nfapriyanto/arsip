# Kategori Bertingkat - Dokumentasi

## Perubahan Database

### 1. Kolom Baru
- **parent_id** (int, NULL) - Menyimpan ID kategori parent untuk membuat kategori bertingkat

### 2. Migration
Jalankan file `migrate_kategori_bertingkat.sql` untuk menambahkan kolom `parent_id` ke tabel `tb_kategori_arsip`.

```sql
ALTER TABLE `tb_kategori_arsip` 
ADD COLUMN `parent_id` int(11) NULL DEFAULT NULL AFTER `deskripsi`;
```

## Fitur Kategori Bertingkat

### Struktur
- **Kategori Parent**: Kategori utama (parent_id = NULL)
- **Sub-Kategori**: Kategori di bawah kategori parent (parent_id = ID kategori parent)

### Cara Kerja
1. Di halaman utama kategori (`admin/arsip`), hanya menampilkan kategori parent
2. Di halaman detail kategori (`admin/arsip/kategori/:id`):
   - Menampilkan arsip di kategori tersebut
   - Menampilkan sub-kategori (jika ada)
   - Tombol "Tambah Kategori" akan membuat sub-kategori dengan parent_id = kategori saat ini

### Validasi
- Kategori tidak dapat dihapus jika masih memiliki:
  - Arsip di dalamnya
  - Sub-kategori di dalamnya

## Contoh Struktur

```
Surat Masuk (Parent)
├── Surat Masuk Internal (Sub-kategori)
├── Surat Masuk Eksternal (Sub-kategori)
└── Surat Masuk Resmi (Sub-kategori)

Dokumen Kepegawaian (Parent)
├── SK Pengangkatan (Sub-kategori)
├── SK Kenaikan Pangkat (Sub-kategori)
└── Surat Tugas (Sub-kategori)
```

## Catatan Penting

1. **Hapus Kategori**: Pastikan tidak ada arsip atau sub-kategori sebelum menghapus
2. **Sub-Kategori**: Dapat memiliki arsip sendiri, terpisah dari kategori parent
3. **Breadcrumb**: Untuk navigasi yang lebih baik, pertimbangkan menambahkan breadcrumb di masa depan



