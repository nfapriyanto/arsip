# Transformasi Sistem: Dari Simpinjam ke Sistem Arsip Digital

## Ringkasan Perubahan

Proyek ini telah ditransformasi dari sistem peminjaman barang (simpinjam) menjadi sistem penyimpanan arsip digital.

## Perubahan Database

### Tabel Baru:
1. **tb_kategori_arsip** - Kategori arsip (menggantikan tb_kategori)
2. **tb_arsip** - Data arsip digital (menggantikan tb_barang)
3. **tb_riwayat_arsip** - Riwayat akses arsip (menggantikan tb_riwayat)

### Tabel yang Tetap Digunakan:
- **tb_user** - Tabel user tetap sama

## Instalasi Database

1. Buat database baru dengan nama `db_arsip`:
```sql
CREATE DATABASE db_arsip CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

2. Import file SQL:
```bash
mysql -u root -p db_arsip < db-arsip/db_arsip.sql
```

3. Update konfigurasi database di `application/config/database.php`:
   - Database name: `db_arsip` (sudah diupdate)

## Struktur Folder Upload

Sistem akan membuat folder `uploads/arsip/` secara otomatis untuk menyimpan file arsip yang diupload.

Pastikan folder tersebut memiliki permission write:
```bash
chmod 777 uploads/arsip/
```

## Fitur Baru

1. **Upload Arsip Digital**
   - Support berbagai format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, GIF, ZIP, RAR
   - Maksimal ukuran file: 10MB
   - Auto-generate nomor arsip jika tidak diisi

2. **Manajemen Kategori**
   - Kategori arsip dapat ditambah, edit, dan dihapus
   - Setiap kategori memiliki deskripsi

3. **Riwayat Akses**
   - Mencatat semua akses ke arsip (Upload, Download, View, Update, Delete)
   - Menyimpan IP address dan user yang mengakses
   - Dapat dicetak dan diexport ke Excel

4. **Pencarian Arsip**
   - Pencarian berdasarkan nomor arsip, judul, atau deskripsi
   - Tersedia untuk admin dan public

## Controller yang Diubah

1. **Barang** → **Arsip** (`application/controllers/admin/Arsip.php`)
2. **CariBarang** → **CariArsip** (`application/controllers/admin/CariArsip.php`)
3. **Cari** → **CariArsip** (`application/controllers/CariArsip.php`)
4. **Dashboard** - Diupdate untuk menampilkan statistik arsip
5. **Riwayat** - Diupdate untuk menampilkan riwayat akses arsip

## View yang Diubah

1. `application/views/admin/arsip.php` - Halaman manajemen arsip
2. `application/views/admin/riwayatArsip.php` - Halaman riwayat arsip
3. `application/views/admin/dashboard.php` - Dashboard dengan statistik arsip
4. `application/views/admin/riwayat.php` - Riwayat akses arsip
5. `application/views/admin/templates/sidebar.php` - Menu sidebar
6. `application/views/admin/templates/header.php` - Header dengan judul baru
7. `application/views/login.php` - Halaman login

## Default User

Setelah import database, default user:
- **Admin**: username: `admin`, password: `admin`
- **User**: username: `user`, password: `user`

**PENTING:** Jika mengalami error "Username atau Password anda salah!" setelah import database, jalankan file SQL berikut untuk memperbaiki password:
```sql
-- Jalankan di phpMyAdmin atau MySQL
UPDATE `tb_user` SET `password` = MD5('admin') WHERE `username` = 'admin';
UPDATE `tb_user` SET `password` = MD5('user') WHERE `username` = 'user';
```

Atau import file `db-arsip/fix_password.sql` untuk memperbaiki password secara otomatis.

## Catatan Penting

1. File arsip disimpan di folder `uploads/arsip/` dengan nama terenkripsi
2. Pastikan folder uploads memiliki permission write
3. Backup database lama sebelum melakukan transformasi
4. File controller dan view lama (Barang, CariBarang) masih ada untuk referensi, tapi tidak digunakan lagi

## Troubleshooting

### Error Upload File
- Pastikan folder `uploads/arsip/` ada dan memiliki permission write
- Cek ukuran file tidak melebihi 10MB
- Cek format file sesuai yang diizinkan

### Error Database
- Pastikan database `db_arsip` sudah dibuat
- Pastikan semua tabel sudah diimport dengan benar
- Cek koneksi database di `application/config/database.php`

## Support

Untuk pertanyaan atau masalah, silakan hubungi administrator sistem.

