# Remove UNIQUE Constraint pada Field Nama Kategori

## Deskripsi
Script ini menghapus UNIQUE constraint pada field `nama` di tabel `tb_kategori_arsip`. 
Ini diperlukan karena dengan sistem kategori bertingkat, sub-kategori dari parent yang berbeda mungkin memiliki nama yang sama.

## Cara Menjalankan

### Metode 1: Melalui phpMyAdmin
1. Buka phpMyAdmin (biasanya di `http://localhost/phpmyadmin`)
2. Pilih database `db_arsip`
3. Klik tab **SQL**
4. Copy dan paste isi file `remove_unique_nama_kategori.sql`
5. Klik **Go** atau tekan **Ctrl+Enter**

### Metode 2: Melalui Command Line (MySQL)
```bash
mysql -u root -p db_arsip < remove_unique_nama_kategori.sql
```

### Metode 3: Copy-Paste Query Langsung
Jalankan query berikut di phpMyAdmin atau MySQL client:

```sql
ALTER TABLE `tb_kategori_arsip` 
DROP INDEX `unique_nama`;
```

## Verifikasi
Setelah menjalankan script, verifikasi dengan query berikut:

```sql
SHOW INDEXES FROM `tb_kategori_arsip`;
```

Pastikan index `unique_nama` sudah tidak ada dalam daftar.

## Catatan
- Setelah migration ini, kategori dengan nama yang sama diperbolehkan
- Ini berguna untuk kategori bertingkat di mana sub-kategori dari parent berbeda bisa memiliki nama serupa
- Pastikan aplikasi sudah siap untuk menangani kategori dengan nama duplikat


