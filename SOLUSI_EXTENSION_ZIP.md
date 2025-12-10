# Solusi Error: Class "ZipArchive" not found

## Masalah

Error ini terjadi karena ekstensi PHP `zip` belum diaktifkan di XAMPP. PhpSpreadsheet memerlukan ekstensi ini untuk membaca file Excel (.xlsx) karena format XLSX sebenarnya adalah file ZIP yang berisi XML.

## Solusi: Mengaktifkan Ekstensi PHP di XAMPP

### Langkah 1: Buka File php.ini

1. Buka XAMPP Control Panel
2. Klik tombol **"Config"** di sebelah Apache
3. Pilih **"PHP (php.ini)"**
4. File `php.ini` akan terbuka di text editor

**Atau** buka file secara manual:
- Lokasi file: `C:\xampp\php\php.ini`

### Langkah 2: Aktifkan Ekstensi yang Diperlukan

Cari baris-baris berikut di file `php.ini` dan pastikan **tidak ada tanda `;` (semicolon)** di depannya:

```ini
;extension=zip          ← Hapus tanda ; di depan
;extension=xml          ← Hapus tanda ; di depan  
;extension=xmlwriter    ← Hapus tanda ; di depan
;extension=mbstring     ← Hapus tanda ; di depan
```

Setelah dihapus tanda `;`, menjadi:

```ini
extension=zip
extension=xml
extension=xmlwriter
extension=mbstring
```

**Catatan:** 
- Tanda `;` di awal baris berarti baris tersebut di-comment (dinonaktifkan)
- Hapus tanda `;` untuk mengaktifkan ekstensi

### Langkah 3: Simpan dan Restart

1. **Simpan** file `php.ini` (Ctrl+S)
2. Tutup file `php.ini`
3. Di XAMPP Control Panel, **Stop** Apache (klik tombol "Stop")
4. Tunggu beberapa detik
5. **Start** Apache lagi (klik tombol "Start")

### Langkah 4: Verifikasi

Untuk memastikan ekstensi sudah aktif, buat file test dengan nama `test_extensions.php` di folder `htdocs`:

```php
<?php
echo "PHP Version: " . PHP_VERSION . "<br><br>";

$extensions = ['zip', 'xml', 'xmlwriter', 'mbstring'];

echo "Status Ekstensi PHP:<br>";
foreach($extensions as $ext) {
    $status = extension_loaded($ext) ? '✓ AKTIF' : '✗ TIDAK AKTIF';
    $color = extension_loaded($ext) ? 'green' : 'red';
    echo "<span style='color: $color;'>$ext: $status</span><br>";
}
?>
```

Akses file tersebut di browser: `http://localhost/test_extensions.php`

Semua ekstensi harus menunjukkan status **"✓ AKTIF"** (warna hijau).

## Ekstensi yang Diperlukan

PhpSpreadsheet memerlukan ekstensi berikut:

| Ekstensi | Fungsi | Wajib |
|----------|--------|-------|
| **zip** | Membaca file .xlsx (format ZIP) | ✅ WAJIB |
| **xml** | Membaca file Excel | ✅ WAJIB |
| **xmlwriter** | Membaca file Excel | ✅ WAJIB |
| **mbstring** | Encoding karakter | ✅ WAJIB |

## Troubleshooting

### Jika ekstensi masih tidak aktif setelah restart:

1. **Cek lokasi php.ini yang digunakan:**
   - Buat file `phpinfo.php` dengan isi: `<?php phpinfo(); ?>`
   - Akses di browser: `http://localhost/phpinfo.php`
   - Cari baris "Loaded Configuration File" untuk melihat lokasi `php.ini` yang sebenarnya digunakan

2. **Pastikan menggunakan php.ini yang benar:**
   - XAMPP mungkin menggunakan `php.ini` yang berbeda
   - Edit file yang ditunjukkan di phpinfo.php

3. **Cek apakah ekstensi tersedia:**
   - Di phpinfo.php, cari bagian "zip" atau "ZIP"
   - Jika tidak ada, ekstensi mungkin tidak terinstall

4. **Restart dengan benar:**
   - Pastikan Apache benar-benar stop sebelum start lagi
   - Atau restart seluruh XAMPP Control Panel

## Alternatif: Menggunakan Format .xls (Lama)

Jika ekstensi zip tidak bisa diaktifkan, Anda bisa menggunakan format Excel lama (.xls) yang tidak memerlukan ekstensi zip. Namun, format ini sudah deprecated dan tidak direkomendasikan.

## Referensi

- [PHP Zip Extension Documentation](https://www.php.net/manual/en/book.zip.php)
- [PhpSpreadsheet Requirements](https://phpspreadsheet.readthedocs.io/en/latest/#requirements)

