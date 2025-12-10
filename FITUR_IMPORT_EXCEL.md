# Fitur Import Excel untuk Data Arsip

## Deskripsi
Fitur ini memungkinkan Anda untuk mengimport data arsip secara massal dari file Excel (.xls atau .xlsx).

## Fitur yang Tersedia

### 1. Import Data dari Excel
- Upload file Excel (.xls atau .xlsx)
- Validasi data sebelum import
- Import data ke kategori yang dipilih
- Generate nomor berkas otomatis jika kosong
- Laporan hasil import (berhasil/gagal)

### 2. Download Template Excel
- Template Excel dengan format yang benar
- Contoh data untuk referensi
- Catatan dan petunjuk pengisian

## Cara Menggunakan

### Langkah 1: Install PhpSpreadsheet
Sebelum menggunakan fitur ini, Anda perlu menginstall library PhpSpreadsheet terlebih dahulu.

**Opsi A: Menggunakan Script Otomatis**
```bash
php install_phpspreadsheet.php
```

**Opsi B: Menggunakan Composer Manual**
```bash
cd application/third_party/PhpSpreadsheet
composer require phpoffice/phpspreadsheet
```

**Opsi C: Download Manual**
Lihat panduan lengkap di file `README_INSTALL_PHPSPREADSHEET.txt`

### Langkah 2: Download Template
1. Buka halaman kategori arsip
2. Klik tombol **"Download Template Excel"**
3. File template akan terdownload dengan nama `Template_Import_Arsip.xlsx`

### Langkah 3: Isi Data di Excel
Buka file template dan isi data sesuai kolom:

| Kolom | Field | Keterangan |
|-------|-------|------------|
| A | Kategori | Opsional. Jika kosong, akan menggunakan kategori yang dipilih saat import |
| B | No Berkas | Opsional. Jika kosong, akan digenerate otomatis |
| C | No Urut | Nomor urut (angka) |
| D | Kode | Kode arsip |
| E | Indeks/Pekerjaan | Indeks atau pekerjaan |
| F | Uraian Masalah/Kegiatan | Uraian lengkap masalah atau kegiatan |
| G | Tahun | Tahun dokumen (format: YYYY, contoh: 2024) |
| H | Jumlah Berkas | Jumlah berkas (default: 1) |
| I | Asli/Kopi | Harus diisi dengan "Asli" atau "Kopi" |
| J | Box | Nomor box |
| K | Klasifikasi Keamanan | Umum, Terbatas, Rahasia, atau Sangat Rahasia |
| L | Link Drive | URL lengkap jika tidak upload file |

### Langkah 4: Import Data
1. Buka halaman kategori arsip yang ingin diimport
2. Klik tombol **"Import Excel"**
3. Pilih file Excel yang sudah diisi
4. Klik **"Import"**
5. Tunggu proses import selesai
6. Lihat hasil import (berhasil/gagal)

## Validasi Data

Sistem akan melakukan validasi berikut:
- ✅ Kategori harus valid (jika diisi)
- ✅ Tahun harus angka antara 1900 - tahun sekarang+1
- ✅ No Urut harus angka
- ✅ Asli/Kopi harus "Asli" atau "Kopi"
- ✅ Jumlah Berkas harus angka (default: 1)
- ✅ Link Drive harus URL valid (jika diisi)

## Catatan Penting

1. **File Excel harus sesuai format template**
   - Jangan mengubah urutan kolom
   - Jangan menghapus header
   - Pastikan data sesuai dengan format yang diminta

2. **Kategori**
   - Jika kolom Kategori di Excel kosong, data akan diimport ke kategori yang dipilih saat import
   - Jika kolom Kategori diisi, sistem akan mencari kategori dengan nama tersebut
   - Jika kategori tidak ditemukan, data akan diimport ke kategori yang dipilih saat import

3. **No Berkas**
   - Jika kosong, akan digenerate otomatis dengan format: `KAT-YYYYMMDD-XXX`
   - KAT = 3 huruf pertama kategori
   - YYYYMMDD = tanggal hari ini
   - XXX = nomor urut (001, 002, dst)

4. **File Upload**
   - Import Excel hanya mengimport metadata (data arsip)
   - File fisik tidak bisa diimport melalui Excel
   - Gunakan kolom "Link Drive" jika file ada di cloud storage

5. **Error Handling**
   - Jika ada error, sistem akan menampilkan detail error per baris
   - Data yang valid akan tetap diimport
   - Data yang error akan dilewati

## Troubleshooting

### Error: "Library PhpSpreadsheet belum terinstall"
**Solusi:** Install PhpSpreadsheet sesuai panduan di `README_INSTALL_PHPSPREADSHEET.txt`

### Error: "Upload gagal"
**Solusi:** 
- Pastikan file format .xls atau .xlsx
- Pastikan ukuran file maksimal 5MB
- Pastikan folder `uploads/temp/` memiliki permission write

### Error: "Kategori tidak valid"
**Solusi:**
- Pastikan nama kategori di Excel sesuai dengan kategori yang ada di sistem
- Atau kosongkan kolom Kategori untuk menggunakan kategori yang dipilih

### Error: "Tahun tidak valid"
**Solusi:**
- Pastikan tahun diisi dengan angka (contoh: 2024)
- Tahun harus antara 1900 - tahun sekarang+1

### Error: "Asli/Kopi harus 'Asli' atau 'Kopi'"
**Solusi:**
- Pastikan kolom Asli/Kopi diisi dengan "Asli" atau "Kopi" (case sensitive)
- Atau kosongkan jika tidak perlu

## File yang Dibuat/Dimodifikasi

1. **application/controllers/admin/Arsip.php**
   - Fungsi `import()` - untuk import data dari Excel
   - Fungsi `download_template()` - untuk download template Excel

2. **application/views/admin/arsip.php**
   - Tombol "Import Excel"
   - Tombol "Download Template Excel"
   - Modal import Excel

3. **README_INSTALL_PHPSPREADSHEET.txt**
   - Panduan instalasi PhpSpreadsheet

4. **install_phpspreadsheet.php**
   - Script otomatis untuk install PhpSpreadsheet

## Support

Jika mengalami masalah, pastikan:
1. PhpSpreadsheet sudah terinstall dengan benar
2. File Excel sesuai format template
3. Data yang diisi valid sesuai validasi
4. Folder `uploads/temp/` memiliki permission write

---

**Selamat menggunakan fitur Import Excel!** 🎉


