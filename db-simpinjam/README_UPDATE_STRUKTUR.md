# Migration: Update Struktur Tabel Barang dan Kategori

## Deskripsi
File ini mengupdate struktur database dengan perubahan berikut:
1. Menghapus field `stok` dari `tb_barang` (stok sekarang dihitung dari COUNT barang per kategori)
2. Memindahkan field `tempat` dari `tb_barang` ke `tb_kategori`

## Cara Menjalankan Migration

### Via phpMyAdmin:
1. Buka phpMyAdmin
2. Pilih database `db_simpinjam`
3. Klik tab "SQL"
4. Copy dan paste isi file `update_struktur_barang.sql`
5. Klik "Go" untuk menjalankan

### Via Command Line (MySQL):
```bash
mysql -u root -p db_simpinjam < update_struktur_barang.sql
```

## Yang Dilakukan Migration:
1. Menambahkan kolom `tempat` di `tb_kategori`
2. Migrasi data tempat dari `tb_barang` ke `tb_kategori` (mengambil tempat pertama dari setiap kategori)
3. Menghapus kolom `stok` dari `tb_barang`
4. Menghapus kolom `tempat` dari `tb_barang`

## Catatan:
- **Stok**: Sekarang dihitung dari COUNT(*) barang per kategori, bukan disimpan sebagai field
- **Tempat**: Sekarang disimpan di `tb_kategori`, bukan di `tb_barang`
- Setiap kategori memiliki satu tempat
- Setiap barang memiliki kode_qr yang unique, sehingga stok = jumlah barang per kategori

## Struktur Database Setelah Migration:

### Tabel tb_kategori:
```
id (PK) | nama | tempat | createDate
```

### Tabel tb_barang (updated):
```
id (PK) | kategori_id (FK) | kode | kode_qr | createDate
```

## Setelah Migration:
Setelah migration berhasil, pastikan untuk:
1. Test halaman kategori barang (stok dihitung dari jumlah barang)
2. Test menambah kategori baru dengan tempat
3. Test menambah barang baru (tanpa stok dan tempat)
4. Test edit kategori (termasuk tempat)
5. Test edit barang (hanya kategori)

## Perubahan Aplikasi:
- Button "Tambah Data" di halaman kategori → menambah kategori
- Button "Tambah Data" di halaman daftar barang → menambah barang
- Form tambah kategori: Nama Kategori + Tempat
- Form tambah barang: Hanya pilih kategori (kode_qr auto-generate)
- Form edit kategori: Nama Kategori + Tempat
- Form edit barang: Hanya pilih kategori
- Modal kelola: Tidak ada field stok lagi




