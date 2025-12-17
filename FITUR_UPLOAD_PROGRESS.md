# Fitur Progress Bar Upload Arsip

## Deskripsi
Fitur ini menambahkan popup progress bar yang menampilkan persentase dan status upload saat menambahkan arsip ke server.

## Fitur yang Ditambahkan

### 1. **Progress Bar untuk Upload Arsip Single**
- Modal popup yang muncul otomatis saat upload file arsip
- Menampilkan persentase upload secara real-time (0% - 100%)
- Menampilkan nama file dan ukuran file yang sedang diupload
- Progress bar animasi dengan warna gradient yang menarik
- Notifikasi sukses atau error setelah upload selesai

### 2. **Informasi Detail Upload**
- **Nama File**: Menampilkan nama file yang sedang diupload
- **Ukuran File**: Menampilkan ukuran file dalam MB
- **Progress**: Menampilkan berapa MB yang sudah terupload dari total ukuran
- **Status**: 
  - "Mengupload arsip..." (saat upload sedang berlangsung)
  - "Memproses data..." (saat upload mencapai 100% dan server sedang memproses)
  - "Upload selesai!" (saat berhasil)
  - "Upload gagal!" (saat terjadi error)

### 3. **Handling Error**
- Menampilkan pesan error yang jelas jika upload gagal
- Mendeteksi jika file terlalu besar melebihi limit server
- Tombol "Tutup" untuk kembali ke form jika terjadi error

### 4. **Auto Reload**
- Setelah upload berhasil, halaman akan otomatis dimuat ulang dalam 2 detik
- User akan melihat arsip yang baru ditambahkan

## Cara Kerja

### Flow Upload:
1. User mengisi form "Tambah Arsip" dan memilih file
2. User klik tombol "Save"
3. Form divalidasi terlebih dahulu
4. Modal "Tambah Arsip" ditutup
5. Modal "Upload Progress" muncul dengan progress bar 0%
6. File mulai diupload menggunakan AJAX dengan XMLHttpRequest
7. Progress bar terupdate secara real-time sesuai dengan progres upload
8. Setelah upload selesai (100%), server memproses data
9. Jika sukses:
   - Progress bar berubah menjadi hijau
   - Muncul notifikasi "Upload berhasil!"
   - Halaman reload otomatis dalam 2 detik
10. Jika gagal:
    - Progress bar berubah menjadi merah
    - Muncul pesan error
    - Tombol "Tutup" muncul untuk menutup modal

### Teknologi yang Digunakan:
- **AJAX (jQuery)**: Untuk upload file tanpa reload halaman
- **XMLHttpRequest Progress Event**: Untuk tracking progress upload
- **FormData API**: Untuk mengirim form multipart/form-data
- **Bootstrap Modal**: Untuk tampilan popup
- **CSS3 Animations**: Untuk animasi progress bar

## Tampilan Progress Bar

### Warna Progress Bar:
- **Biru/Ungu Gradient** 🟣: Upload sedang berlangsung (0-99%)
- **Hijau Gradient** 🟢: Upload berhasil (100%)
- **Merah Gradient** 🔴: Upload gagal/error

### Animasi:
- Progress bar memiliki animasi striped yang bergerak saat upload
- Icon check ✓ akan beranimasi pulse saat upload berhasil

## Catatan

### Upload Link Drive
- Jika user memilih "Link Drive" (bukan upload file), maka form akan disubmit secara normal tanpa menampilkan progress bar
- Progress bar hanya muncul untuk upload file

### Kompatibilitas Browser
- Fitur ini menggunakan XMLHttpRequest level 2 yang sudah didukung semua browser modern
- Progress upload mungkin tidak akurat di browser lama (IE9 ke bawah)

### Server Configuration
- Pastikan `upload_max_filesize` dan `post_max_size` di php.ini sudah sesuai
- Jika file terlalu besar, progress bar akan menampilkan error dengan pesan yang jelas

## File yang Dimodifikasi
- `application/views/admin/arsip.php`
  - Menambahkan modal HTML untuk progress bar
  - Menambahkan JavaScript untuk handle AJAX upload
  - Menambahkan CSS styling untuk progress modal

## Fitur Tambahan (Sudah Ada Sebelumnya)
- **Bulk Upload**: Progress bar untuk upload multiple file sekaligus
- File yang sudah ada progress bar-nya tidak dimodifikasi, hanya ditambahkan untuk single upload

## Testing
Untuk menguji fitur ini:
1. Buka halaman Arsip di menu Admin
2. Pilih kategori arsip
3. Klik "Tambah Arsip"
4. Pilih "Upload File" (bukan Link Drive)
5. Pilih sebuah file (lebih baik file yang agak besar agar progress terlihat jelas)
6. Isi form lainnya
7. Klik "Save"
8. Progress bar akan muncul dan menampilkan persentase upload

## Screenshot (Konsep)
```
┌─────────────────────────────────────┐
│  📤 Upload Progress                 │
├─────────────────────────────────────┤
│                                     │
│     Mengupload arsip...             │
│                                     │
│  ████████████████░░░░  75.3%        │
│                                     │
│  📄 document.pdf                    │
│  Ukuran: 5.2 MB                     │
│                                     │
└─────────────────────────────────────┘
```

## Pengembangan Kedepan
- [ ] Menambahkan estimasi waktu tersisa
- [ ] Menambahkan tombol cancel upload
- [ ] Menambahkan history upload yang gagal
- [ ] Menambahkan retry otomatis jika upload gagal
