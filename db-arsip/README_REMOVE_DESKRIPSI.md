# Remove Kolom Deskripsi dari Tabel Kategori Arsip

## Deskripsi
Script ini menghapus kolom `deskripsi` dari tabel `tb_kategori_arsip` karena tidak diperlukan lagi.

## Cara Menjalankan

### Metode 1: Melalui phpMyAdmin
1. Buka phpMyAdmin (biasanya di `http://localhost/phpmyadmin`)
2. Pilih database `db_arsip`
3. Klik tab **SQL**
4. Copy dan paste isi file `remove_deskripsi_kategori.sql`
5. Klik **Go** atau tekan **Ctrl+Enter**

### Metode 2: Melalui Command Line (MySQL)
```bash
mysql -u root -p db_arsip < remove_deskripsi_kategori.sql
```

### Metode 3: Copy-Paste Query Langsung
Jalankan query berikut di phpMyAdmin atau MySQL client:

```sql
ALTER TABLE `tb_kategori_arsip` 
DROP COLUMN `deskripsi`;
```

## Verifikasi
Setelah menjalankan script, verifikasi dengan query berikut:

```sql
DESCRIBE `tb_kategori_arsip`;
```

Pastikan kolom `deskripsi` sudah tidak ada dalam daftar kolom.

## Catatan
- Pastikan aplikasi sudah diupdate untuk menghapus semua referensi ke field `deskripsi`
- Backup database sebelum menjalankan migration ini
- Setelah migration, form kategori tidak akan memiliki field deskripsi lagi


