<?php
/**
 * Script untuk membantu mengaktifkan ekstensi zip
 * Akses: http://localhost/arsip/fix_zip_extension.php
 */

header('Content-Type: text/html; charset=utf-8');

// Cek apakah ekstensi zip sudah aktif
$zip_active = extension_loaded('zip');
$php_ini_file = php_ini_loaded_file();
$php_ini_scanned = php_ini_scanned_dir();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Ekstensi ZIP - Sistem Arsip</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .status-box {
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 18px;
            text-align: center;
        }
        .status-box.success {
            background: #d4edda;
            border: 2px solid #28a745;
            color: #155724;
        }
        .status-box.error {
            background: #f8d7da;
            border: 2px solid #dc3545;
            color: #721c24;
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .phpini-path {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            word-break: break-all;
            font-size: 14px;
        }
        .step-box {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
        }
        .step-box h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 20px;
        }
        .step-box ol {
            margin-left: 25px;
            line-height: 2.5;
        }
        .step-box li {
            margin: 15px 0;
        }
        .step-box code {
            background: #e9ecef;
            padding: 3px 8px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            color: #d63384;
            font-size: 14px;
        }
        .screenshot-hint {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #ffc107;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
            transition: background 0.3s;
            font-weight: bold;
        }
        .button:hover {
            background: #5568d3;
        }
        .button-secondary {
            background: #6c757d;
        }
        .button-secondary:hover {
            background: #5a6268;
        }
        .button-success {
            background: #28a745;
        }
        .button-success:hover {
            background: #218838;
        }
        .highlight {
            background: #fff3cd;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Fix Ekstensi ZIP</h1>
            <p>Sistem Arsip Digital - Panduan Lengkap</p>
        </div>
        
        <div class="content">
            <?php if($zip_active): ?>
                <div class="status-box success">
                    ✅ <strong>Ekstensi ZIP sudah AKTIF!</strong><br>
                    Anda bisa melakukan import Excel sekarang.
                </div>
                
                <div style="text-align: center; margin-top: 30px;">
                    <a href="check_php_extensions.php" class="button">Cek Semua Ekstensi</a>
                    <a href="admin/arsip" class="button button-success">Kembali ke Arsip</a>
                </div>
            <?php else: ?>
                <div class="status-box error">
                    ❌ <strong>Ekstensi ZIP BELUM AKTIF!</strong><br>
                    Ikuti panduan di bawah untuk mengaktifkannya.
                </div>
                
                <div class="info-box">
                    <strong>📋 Informasi PHP:</strong><br>
                    Versi PHP: <strong><?php echo PHP_VERSION; ?></strong><br>
                    File php.ini: <strong><?php echo $php_ini_file ?: 'Tidak ditemukan'; ?></strong><br>
                    <?php if($php_ini_scanned): ?>
                        Folder tambahan: <strong><?php echo $php_ini_scanned; ?></strong><br>
                    <?php endif; ?>
                </div>
                
                <?php if(!$php_ini_file): ?>
                    <div class="warning-box">
                        <strong>⚠️ File php.ini tidak ditemukan!</strong><br>
                        Lokasi default XAMPP: <code>C:\xampp\php\php.ini</code><br>
                        Pastikan Anda menggunakan XAMPP dan file php.ini ada di lokasi tersebut.
                    </div>
                <?php else: ?>
                    <div class="phpini-path">
                        <strong>File php.ini yang digunakan:</strong><br>
                        <?php echo $php_ini_file; ?>
                    </div>
                <?php endif; ?>
                
                <div class="step-box">
                    <h3>📝 Langkah 1: Buka XAMPP Control Panel</h3>
                    <ol>
                        <li>Pastikan XAMPP Control Panel sudah terbuka</li>
                        <li>Pastikan Apache sudah running (status "Running" dengan background hijau)</li>
                    </ol>
                </div>
                
                <div class="step-box">
                    <h3>📝 Langkah 2: Buka File php.ini</h3>
                    <ol>
                        <li>Di XAMPP Control Panel, klik tombol <strong>"Config"</strong> di sebelah Apache</li>
                        <li>Pilih <strong>"PHP (php.ini)"</strong></li>
                        <li>File php.ini akan terbuka di text editor default (biasanya Notepad)</li>
                        <li class="screenshot-hint">
                            <strong>💡 Tips:</strong> Jika file tidak terbuka, buka secara manual:<br>
                            Buka file: <code><?php echo $php_ini_file ?: 'C:\\xampp\\php\\php.ini'; ?></code><br>
                            Gunakan text editor seperti Notepad++, VS Code, atau Notepad biasa.
                        </li>
                    </ol>
                </div>
                
                <div class="step-box">
                    <h3>📝 Langkah 3: Cari dan Aktifkan Ekstensi ZIP</h3>
                    <ol>
                        <li>Di file php.ini, tekan <strong>Ctrl+F</strong> untuk membuka Find</li>
                        <li>Ketik: <code>;extension=zip</code></li>
                        <li>Tekan <strong>Enter</strong> untuk mencari</li>
                        <li>Jika ditemukan, Anda akan melihat baris seperti ini:<br>
                            <code style="background: #ffebee; padding: 5px 10px; display: inline-block; margin: 5px 0;">;extension=zip</code>
                        </li>
                        <li><strong>Hapus tanda <code>;</code> (semicolon)</strong> di depan baris tersebut</li>
                        <li>Seharusnya menjadi:<br>
                            <code style="background: #e8f5e9; padding: 5px 10px; display: inline-block; margin: 5px 0;">extension=zip</code>
                        </li>
                        <li class="screenshot-hint">
                            <strong>⚠️ Penting:</strong> Pastikan Anda mengedit baris yang benar!<br>
                            Jangan mengedit baris yang sudah aktif (tanpa tanda ; di depan).
                        </li>
                    </ol>
                </div>
                
                <div class="step-box">
                    <h3>📝 Langkah 4: Pastikan Ekstensi Lainnya Juga Aktif</h3>
                    <ol>
                        <li>Gunakan <strong>Ctrl+F</strong> untuk mencari masing-masing ekstensi berikut:</li>
                        <li>Cari: <code>;extension=xml</code> → Hapus <code>;</code> jika ada</li>
                        <li>Cari: <code>;extension=xmlwriter</code> → Hapus <code>;</code> jika ada</li>
                        <li>Cari: <code>;extension=mbstring</code> → Hapus <code>;</code> jika ada</li>
                        <li>Pastikan semua baris tersebut menjadi:<br>
                            <code>extension=zip</code><br>
                            <code>extension=xml</code><br>
                            <code>extension=xmlwriter</code><br>
                            <code>extension=mbstring</code>
                        </li>
                    </ol>
                </div>
                
                <div class="step-box">
                    <h3>📝 Langkah 5: Simpan File</h3>
                    <ol>
                        <li>Tekan <strong>Ctrl+S</strong> atau klik <strong>File → Save</strong></li>
                        <li>Tutup file php.ini</li>
                        <li class="screenshot-hint">
                            <strong>💡 Tips:</strong> Pastikan file benar-benar tersimpan!<br>
                            Jika menggunakan Notepad, pastikan tidak ada dialog "Save As" yang muncul.
                        </li>
                    </ol>
                </div>
                
                <div class="step-box">
                    <h3>📝 Langkah 6: Restart Apache (PENTING!)</h3>
                    <ol>
                        <li>Kembali ke <strong>XAMPP Control Panel</strong></li>
                        <li>Klik tombol <strong>"Stop"</strong> pada Apache</li>
                        <li>Tunggu beberapa detik sampai status berubah menjadi "Stopped"</li>
                        <li>Klik tombol <strong>"Start"</strong> pada Apache</li>
                        <li>Tunggu sampai status berubah menjadi "Running" (background hijau)</li>
                        <li class="screenshot-hint">
                            <strong>⚠️ PENTING:</strong> Restart Apache WAJIB dilakukan!<br>
                            Tanpa restart, perubahan di php.ini tidak akan berlaku.<br>
                            Jika masih tidak aktif setelah restart, coba restart seluruh XAMPP Control Panel.
                        </li>
                    </ol>
                </div>
                
                <div class="step-box">
                    <h3>📝 Langkah 7: Verifikasi</h3>
                    <ol>
                        <li>Refresh halaman ini (F5 atau Ctrl+R)</li>
                        <li>Atau akses: <a href="check_php_extensions.php">check_php_extensions.php</a></li>
                        <li>Ekstensi zip harus menunjukkan status <strong>"✓ AKTIF"</strong></li>
                        <li>Jika masih belum aktif, ulangi langkah 1-6 dengan lebih teliti</li>
                    </ol>
                </div>
                
                <div class="warning-box">
                    <strong>🔍 Troubleshooting:</strong><br><br>
                    <strong>Jika ekstensi masih tidak aktif setelah restart:</strong><br>
                    1. Pastikan Anda mengedit file php.ini yang benar (cek lokasi di atas)<br>
                    2. Pastikan baris <code>extension=zip</code> tidak ada tanda <code>;</code> di depan<br>
                    3. Cek apakah file <code>php_zip.dll</code> ada di folder <code>C:\xampp\php\ext\</code><br>
                    4. Jika file DLL tidak ada, ekstensi mungkin tidak terinstall dengan XAMPP<br>
                    5. Coba restart seluruh XAMPP Control Panel (tutup dan buka lagi)<br>
                    6. Cek error log Apache di XAMPP Control Panel → Logs → Apache
                </div>
                
                <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #e0e0e0;">
                    <a href="check_php_extensions.php" class="button">Cek Status Ekstensi</a>
                    <a href="fix_zip_extension.php" class="button button-success">Refresh Halaman Ini</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

