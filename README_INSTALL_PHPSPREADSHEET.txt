================================================================================
PANDUAN INSTALASI PHPSPREADSHEET UNTUK FITUR IMPORT EXCEL
================================================================================

Fitur import Excel memerlukan library PhpSpreadsheet untuk membaca file Excel.
Berikut langkah-langkah instalasinya:

CARA 1: MENGGUNAKAN COMPOSER (DIREKOMENDASIKAN)
------------------------------------------------

1. Pastikan Composer sudah terinstall di sistem Anda
   - Download dari: https://getcomposer.org/download/
   - Atau jika sudah ada, skip langkah ini

2. Buka terminal/command prompt di folder root project (C:\xampp\htdocs\arsip)

3. Install PhpSpreadsheet dengan perintah:
   composer require phpoffice/phpspreadsheet

4. Jika belum ada file composer.json, Composer akan membuatkannya otomatis

5. Setelah instalasi selesai, pindahkan folder vendor ke lokasi yang benar:
   - Salin folder vendor dari root ke: application/third_party/PhpSpreadsheet/
   - Struktur akhir: application/third_party/PhpSpreadsheet/vendor/

6. Pastikan struktur folder seperti ini:
   application/
     third_party/
       PhpSpreadsheet/
         vendor/
           autoload.php
           phpoffice/
             phpspreadsheet/
               ...


CARA 2: DOWNLOAD MANUAL
------------------------

1. Download PhpSpreadsheet dari GitHub:
   https://github.com/PHPOffice/PhpSpreadsheet/releases

2. Extract file zip yang didownload

3. Salin folder vendor dari hasil extract ke:
   application/third_party/PhpSpreadsheet/

4. Pastikan struktur folder seperti ini:
   application/third_party/PhpSpreadsheet/vendor/autoload.php


CARA 3: MENGGUNAKAN GIT (JIKA MEMILIKI GIT)
--------------------------------------------

1. Buka terminal/command prompt di folder: application/third_party/

2. Clone repository PhpSpreadsheet:
   git clone https://github.com/PHPOffice/PhpSpreadsheet.git

3. Rename folder PhpSpreadsheet menjadi PhpSpreadsheet (jika perlu)

4. Install dependencies dengan Composer:
   cd PhpSpreadsheet
   composer install

5. Pastikan struktur folder seperti ini:
   application/third_party/PhpSpreadsheet/vendor/autoload.php


VERIFIKASI INSTALASI
--------------------

Setelah instalasi, pastikan file berikut ada:
- application/third_party/PhpSpreadsheet/vendor/autoload.php

Jika file tersebut ada, maka instalasi berhasil!


CATATAN PENTING
---------------

- Pastikan PHP versi 7.2 atau lebih tinggi
- Pastikan ekstensi PHP berikut aktif: zip, xml, gd
- Jika menggunakan XAMPP, biasanya ekstensi tersebut sudah aktif
- Jika ada error, pastikan folder application/third_party/ memiliki permission write


TROUBLESHOOTING
---------------

Error: "Class 'PhpOffice\PhpSpreadsheet\Spreadsheet' not found"
- Pastikan autoload.php sudah ada di lokasi yang benar
- Pastikan path di controller sudah benar

Error: "Composer not found"
- Install Composer terlebih dahulu
- Atau gunakan cara download manual

Error: "Permission denied"
- Pastikan folder application/third_party/ memiliki permission write
- Di Windows, biasanya tidak ada masalah permission


BANTUAN
-------

Jika masih mengalami masalah, silakan cek:
1. Versi PHP (php -v)
2. Ekstensi PHP yang aktif (php -m)
3. Struktur folder sesuai panduan di atas

================================================================================


