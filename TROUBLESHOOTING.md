# Troubleshooting

## Masalah: Route tidak berfungsi

### 1. Error: Unable to load the requested file: public/cariArsip.php
**Solusi:** File sudah dibuat di `application/views/public/cariArsip.php`

### 2. Error: 404 Page Not Found untuk /admin
**Kemungkinan penyebab:**
1. **.htaccess tidak aktif** - Pastikan mod_rewrite aktif di Apache
2. **Base URL salah** - Cek `application/config/config.php`
3. **Folder name mismatch** - Pastikan folder project adalah `arsip` bukan `simpinjam`

**Langkah perbaikan:**

1. **Cek mod_rewrite Apache:**
   - Buka `httpd.conf` di XAMPP
   - Pastikan baris `LoadModule rewrite_module modules/mod_rewrite.so` tidak di-comment
   - Restart Apache

2. **Cek .htaccess:**
   - Pastikan file `.htaccess` ada di root folder
   - Pastikan `RewriteBase /arsip/` sesuai dengan folder project

3. **Test URL langsung:**
   - Coba akses: `http://localhost/arsip/index.php/admin`
   - Jika berhasil, berarti masalah di .htaccess
   - Jika tidak berhasil, cek controller Admin.php

4. **Cek controller:**
   - Pastikan file `application/controllers/admin/Admin.php` ada
   - Pastikan class name adalah `Admin` (huruf besar A)

5. **Cek database:**
   - Pastikan database `db_arsip` sudah dibuat
   - Pastikan tabel `tb_user` ada dan berisi data

## Test Manual

1. **Test route default:**
   ```
   http://localhost/arsip/
   ```
   Seharusnya menampilkan halaman cari arsip

2. **Test route admin:**
   ```
   http://localhost/arsip/admin
   ```
   Seharusnya menampilkan halaman login

3. **Test dengan index.php:**
   ```
   http://localhost/arsip/index.php/admin
   ```
   Jika ini bekerja, berarti masalah di .htaccess

## Konfigurasi XAMPP

Jika menggunakan XAMPP di Windows:

1. **Enable mod_rewrite:**
   - Buka `C:\xampp\apache\conf\httpd.conf`
   - Cari `#LoadModule rewrite_module modules/mod_rewrite.so`
   - Hapus tanda `#` di depan
   - Restart Apache

2. **AllowOverride All:**
   - Di file `httpd.conf`, cari section `<Directory "C:/xampp/htdocs">`
   - Pastikan ada `AllowOverride All`
   - Restart Apache

## Checklist

- [ ] File `.htaccess` ada di root folder
- [ ] `RewriteBase /arsip/` sesuai folder project
- [ ] mod_rewrite aktif di Apache
- [ ] AllowOverride All di httpd.conf
- [ ] Database `db_arsip` sudah dibuat
- [ ] Tabel `tb_user` ada dan berisi data
- [ ] Controller `Admin.php` ada di `application/controllers/admin/`
- [ ] View `cariArsip.php` ada di `application/views/public/`


