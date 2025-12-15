# Panduan Instalasi Tool untuk PDF Thumbnail

Sistem Gallery Arsip memerlukan tool eksternal untuk mengkonversi halaman pertama PDF menjadi gambar thumbnail. Berikut adalah panduan instalasi untuk Windows (XAMPP):

## Opsi 1: Ghostscript (Direkomendasikan)

### Langkah Instalasi:

1. **Download Ghostscript:**
   - Kunjungi: https://www.ghostscript.com/download/gsdnld.html
   - Download versi terbaru untuk Windows (64-bit atau 32-bit sesuai sistem Anda)

2. **Install Ghostscript:**
   - Jalankan installer yang sudah didownload
   - Install ke lokasi default: `C:\Program Files\gs\gs[version]\`
   - Pastikan opsi "Add Ghostscript to PATH" dicentang (jika ada)

3. **Verifikasi Instalasi:**
   - Buka Command Prompt
   - Ketik: `gswin64c --version` (atau `gswin32c --version` untuk 32-bit)
   - Jika muncul versi, berarti instalasi berhasil

4. **Konfigurasi (Opsional):**
   - Jika Ghostscript tidak terdeteksi otomatis, edit file `application/controllers/admin/Arsip.php`
   - Tambahkan path Ghostscript Anda di array `$gs_paths` pada fungsi `generatePdfThumbnail()`

## Opsi 2: Poppler Utils

### Langkah Instalasi:

1. **Download Poppler:**
   - Kunjungi: https://github.com/oschwartz10612/poppler-windows/re                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        leases/
   - Download versi terbaru (poppler-[version]-x64.zip)

2. **Extract dan Setup:**
   - Extract file zip ke folder, misalnya: `C:\poppler\`
   - Tambahkan `C:\poppler\Library\bin` ke PATH environment variable
   - Atau copy folder `bin` ke `C:\xampp\poppler\bin\`

3. **Verifikasi Instalasi:**
   - Buka Command Prompt
   - Ketik: `pdftoppm -h`
   - Jika muncul help text, berarti instalasi berhasil

## Catatan Penting:

- **Tanpa Tool:** Jika tool tidak terinstall, sistem akan menampilkan placeholder "No Preview" untuk file PDF
- **File Gambar:** File gambar (JPG, PNG, GIF) akan otomatis di-resize menjadi thumbnail tanpa perlu tool eksternal
- **Performance:** Thumbnail akan di-cache di folder `uploads/thumbnails/` untuk performa yang lebih baik
- **Security:** Pastikan folder `uploads/thumbnails/` memiliki permission write yang sesuai

## Troubleshooting:

1. **Thumbnail tidak muncul:**
   - Pastikan tool sudah terinstall dan bisa diakses dari command line
   - Cek permission folder `uploads/thumbnails/`
   - Cek error log PHP untuk detail error

2. **Error "command not found":**
   - Pastikan tool sudah ditambahkan ke PATH
   - Atau edit path di fungsi `generatePdfThumbnail()` sesuai lokasi instalasi Anda

3. **Thumbnail terlalu besar/kecil:**
   - Edit parameter `$max_width` dan `$max_height` di fungsi `resizeThumbnail()`

## Alternatif Tanpa Tool Eksternal:

Jika tidak ingin menginstall tool eksternal, sistem akan tetap berfungsi dengan menampilkan placeholder untuk file PDF. File gambar akan tetap bisa ditampilkan sebagai thumbnail.






