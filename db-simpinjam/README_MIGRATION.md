# Migration: Menambahkan Field Kode QR

## Deskripsi
File ini menambahkan field `kode_qr` ke tabel `tb_barang` untuk menyimpan kode QR unik setiap barang.

## Cara Menjalankan Migration

### Via phpMyAdmin:
1. Buka phpMyAdmin
2. Pilih database `db_simpinjam`
3. Klik tab "SQL"
4. Copy dan paste isi file `add_kode_qr.sql`
5. Klik "Go" untuk menjalankan

### Via Command Line (MySQL):
```bash
mysql -u root -p db_simpinjam < add_kode_qr.sql
```

## Yang Dilakukan Migration:
1. Menambahkan kolom `kode_qr` VARCHAR(50) di tabel `tb_barang`
2. Menambahkan UNIQUE constraint pada `kode_qr` untuk memastikan setiap kode unik
3. Mengisi data existing dengan kode QR otomatis (format: # + 10 karakter alphanumeric)

## Catatan:
- Field `kode` tetap digunakan untuk nama barang
- Field `kode_qr` digunakan untuk QR Code scanning
- Setiap barang akan memiliki kode_qr unik
- Kode QR bisa di-generate otomatis saat menambah barang baru

## Setelah Migration:
Setelah migration berhasil, pastikan untuk:
1. Update form tambah/edit barang untuk generate kode_qr otomatis
2. Generate QR Code image untuk setiap barang berdasarkan kode_qr
3. Test fitur search dan scan QR code






