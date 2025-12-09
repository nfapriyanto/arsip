# Migration: Menambahkan Tabel Kategori

## Deskripsi
File ini menambahkan tabel `tb_kategori` dan menghubungkannya dengan tabel `tb_barang` melalui field `kategori_id`. Ini akan menyelesaikan masalah URI dengan karakter khusus (seperti spasi) karena sekarang menggunakan ID numerik di URL.

## Cara Menjalankan Migration

### Via phpMyAdmin:
1. Buka phpMyAdmin
2. Pilih database `db_simpinjam`
3. Klik tab "SQL"
4. Copy dan paste isi file `add_kategori.sql`
5. Klik "Go" untuk menjalankan

### Via Command Line (MySQL):
```bash
mysql -u root -p db_simpinjam < add_kategori.sql
```

## Yang Dilakukan Migration:
1. Membuat tabel `tb_kategori` dengan field:
   - `id` (Primary Key, Auto Increment)
   - `nama` (Nama kategori, Unique)
   - `createDate` (Tanggal pembuatan)

2. Menambahkan kolom `kategori_id` di tabel `tb_barang`

3. Migrasi data existing:
   - Membuat kategori dari kode yang unik di `tb_barang`
   - Menghubungkan setiap barang dengan kategori yang sesuai

4. Membuat foreign key constraint antara `tb_barang.kategori_id` dan `tb_kategori.id`

5. Membuat index untuk performa query

## Catatan:
- Field `kode` di `tb_barang` tetap ada untuk backward compatibility
- Setiap barang sekarang memiliki `kategori_id` yang menghubungkan ke `tb_kategori`
- URL sekarang menggunakan ID kategori (numerik) bukan nama kategori, sehingga menghindari masalah dengan karakter khusus

## Setelah Migration:
Setelah migration berhasil, pastikan untuk:
1. Test halaman kategori barang
2. Test menambah barang baru dengan kategori
3. Test edit barang dengan kategori
4. Test melihat daftar barang per kategori

## Struktur Database Baru:

### Tabel tb_kategori:
```
id (PK) | nama | createDate
```

### Tabel tb_barang (updated):
```
id (PK) | kategori_id (FK) | kode | kode_qr | stok | tempat | createDate
```

## Keuntungan:
- ✅ URL menggunakan ID numerik (aman dari karakter khusus)
- ✅ Struktur database lebih normal (normalized)
- ✅ Lebih mudah untuk mengelola kategori
- ✅ Backward compatible (field kode tetap ada)




