# Migration: Update Struktur Tabel Riwayat dan Barang

## Deskripsi
File ini mengupdate struktur database dengan perubahan berikut:
1. Menghapus field `label` dan `jumlah` dari `tb_riwayat`
2. Rename field `unit` menjadi `peminjam` di `tb_riwayat`
3. Menambahkan field `is_available` di `tb_barang` untuk menandai apakah stock tersedia

## Cara Menjalankan Migration

### Via phpMyAdmin:
1. Buka phpMyAdmin
2. Pilih database `db_simpinjam`
3. Klik tab "SQL"
4. Copy dan paste isi file `update_riwayat_barang.sql`
5. Klik "Go" untuk menjalankan

### Via Command Line (MySQL):
```bash
mysql -u root -p db_simpinjam < update_riwayat_barang.sql
```

## Yang Dilakukan Migration:
1. Menghapus kolom `label` dari `tb_riwayat`
2. Menghapus kolom `jumlah` dari `tb_riwayat`
3. Rename kolom `unit` menjadi `peminjam` di `tb_riwayat`
4. Menambahkan kolom `is_available` (tinyint(1), default 1) di `tb_barang`
5. Set `is_available` berdasarkan riwayat terakhir (jika ada peminjaman aktif, set = 0)

## Catatan:
- **Field label dan jumlah**: Dihapus karena setiap barang memiliki kode_qr unique, tidak perlu label lagi
- **Field unit → peminjam**: Nama field lebih jelas dan sesuai dengan fungsinya
- **Field is_available**: 
  - `1` = Tersedia (barang bisa dipinjam)
  - `0` = Dipinjam (barang sedang dipinjam)
  - Akan diupdate otomatis saat ada peminjaman/pengembalian

## Struktur Database Setelah Migration:

### Tabel tb_riwayat (updated):
```
id (PK) | kode | jenis | createDate | peminjam | noTlp
```

### Tabel tb_barang (updated):
```
id (PK) | kategori_id (FK) | kode | kode_qr | is_available | createDate
```

## Setelah Migration:
Setelah migration berhasil, pastikan untuk:
1. Test form Kelola (tidak ada field label dan jumlah)
2. Test peminjaman (is_available berubah menjadi 0)
3. Test pengembalian (is_available berubah menjadi 1)
4. Test tampilan riwayat (tidak ada kolom label dan jumlah)
5. Test tampilan status di tabel barang (Tersedia/Dipinjam)

## Perubahan Aplikasi:
- Form Kelola: Hanya ada field Jenis, Peminjam, dan No Telp
- Tabel Barang: Menampilkan kolom Status (Tersedia/Dipinjam)
- Tabel Riwayat: Menghapus kolom Label dan Jumlah, menggunakan Peminjam
- Logic Kelola: Update is_available otomatis berdasarkan jenis (Peminjaman = 0, Pengembalian = 1)




