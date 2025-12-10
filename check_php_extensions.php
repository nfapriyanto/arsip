<?php
/**
 * Helper untuk mengecek dan memberikan instruksi spesifik untuk mengaktifkan ekstensi PHP
 * Akses: http://localhost/arsip/check_php_extensions.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pengecekan Ekstensi PHP - Sistem Arsip</title>
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
            max-width: 900px;
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
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #2196F3;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info-box strong {
            color: #2196F3;
        }
        .extension-status {
            display: grid;
            gap: 15px;
            margin: 20px 0;
        }
        .extension-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
        }
        .extension-item.active {
            background: #e8f5e9;
            border-color: #4CAF50;
        }
        .extension-item.inactive {
            background: #ffebee;
            border-color: #f44336;
        }
        .status-badge {
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }
        .status-badge.active {
            background: #4CAF50;
            color: white;
        }
        .status-badge.inactive {
            background: #f44336;
            color: white;
        }
        .instructions {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
        }
        .instructions h2 {
            color: #856404;
            margin-bottom: 20px;
            font-size: 22px;
        }
        .instructions ol {
            margin-left: 20px;
            line-height: 2;
        }
        .instructions li {
            margin: 10px 0;
            color: #333;
        }
        .instructions code {
            background: #f4f4f4;
            padding: 3px 8px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            color: #d63384;
        }
        .phpini-path {
            background: #e7f3ff;
            border: 2px solid #2196F3;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .success-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            color: #155724;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Pengecekan Ekstensi PHP</h1>
            <p>Sistem Arsip Digital</p>
        </div>
        
        <div class="content">
            <?php
            // Informasi PHP
            $php_version = PHP_VERSION;
            $php_ini_loaded = php_ini_loaded_file();
            $php_ini_scanned = php_ini_scanned_dir();
            
            echo '<div class="info-box">';
            echo '<strong>📋 Informasi PHP:</strong><br>';
            echo 'Versi PHP: <strong>' . $php_version . '</strong><br>';
            echo 'File php.ini yang digunakan: <strong>' . ($php_ini_loaded ?: 'Tidak ditemukan') . '</strong><br>';
            if($php_ini_scanned) {
                echo 'Folder php.ini tambahan: <strong>' . $php_ini_scanned . '</strong><br>';
            }
            echo '</div>';
            
            // Cek ekstensi
            $required_extensions = array(
                'zip' => 'ZipArchive (wajib untuk membaca file .xlsx)',
                'xml' => 'XML (wajib untuk membaca file Excel)',
                'xmlwriter' => 'XMLWriter (wajib untuk membaca file Excel)',
                'mbstring' => 'mbstring (wajib untuk encoding karakter)'
            );
            
            $missing_extensions = array();
            $all_active = true;
            
            echo '<h2 style="margin: 30px 0 20px 0;">Status Ekstensi:</h2>';
            echo '<div class="extension-status">';
            
            foreach($required_extensions as $ext => $desc) {
                $is_active = extension_loaded($ext);
                if(!$is_active) {
                    $missing_extensions[] = $ext;
                    $all_active = false;
                }
                
                $class = $is_active ? 'active' : 'inactive';
                $status_text = $is_active ? '✓ AKTIF' : '✗ TIDAK AKTIF';
                $status_class = $is_active ? 'active' : 'inactive';
                
                echo '<div class="extension-item ' . $class . '">';
                echo '<div>';
                echo '<strong>' . strtoupper($ext) . '</strong><br>';
                echo '<small style="color: #666;">' . $desc . '</small>';
                echo '</div>';
                echo '<span class="status-badge ' . $status_class . '">' . $status_text . '</span>';
                echo '</div>';
            }
            
            echo '</div>';
            
            if($all_active) {
                echo '<div class="success-box">';
                echo '<strong>✅ Semua ekstensi aktif!</strong><br>';
                echo 'PhpSpreadsheet siap digunakan. Anda bisa melakukan import Excel sekarang.';
                echo '</div>';
            } else {
                echo '<div class="warning-box">';
                echo '<strong>⚠️ Ada ekstensi yang belum aktif!</strong><br>';
                echo 'Ekstensi berikut perlu diaktifkan: <strong>' . implode(', ', $missing_extensions) . '</strong>';
                echo '</div>';
                
                // Instruksi
                echo '<div class="instructions">';
                echo '<h2>📝 Cara Mengaktifkan Ekstensi di XAMPP:</h2>';
                echo '<ol>';
                echo '<li><strong>Buka file php.ini</strong><br>';
                echo 'File php.ini yang digunakan:';
                echo '<div class="phpini-path">' . ($php_ini_loaded ?: 'Tidak ditemukan - cek di C:\\xampp\\php\\php.ini') . '</div>';
                echo 'Cara membuka:<br>';
                echo '- Buka XAMPP Control Panel<br>';
                echo '- Klik tombol <strong>"Config"</strong> di sebelah Apache<br>';
                echo '- Pilih <strong>"PHP (php.ini)"</strong><br>';
                echo 'Atau buka secara manual dengan text editor (Notepad++, VS Code, dll)';
                echo '</li>';
                
                echo '<li><strong>Cari dan aktifkan ekstensi</strong><br>';
                echo 'Gunakan fitur <strong>Find</strong> (Ctrl+F) untuk mencari baris berikut:<br>';
                foreach($missing_extensions as $ext) {
                    echo '- Cari: <code>;extension=' . $ext . '</code><br>';
                }
                echo '<br>Hapus tanda <code>;</code> (semicolon) di depan baris tersebut.<br>';
                echo 'Contoh:<br>';
                echo '<code>;extension=zip</code> → <code>extension=zip</code>';
                echo '</li>';
                
                echo '<li><strong>Pastikan ekstensi berikut juga aktif</strong> (jika belum):<br>';
                foreach($required_extensions as $ext => $desc) {
                    if(!in_array($ext, $missing_extensions)) {
                        echo '- <code>extension=' . $ext . '</code> ✓<br>';
                    } else {
                        echo '- <code>extension=' . $ext . '</code> (perlu diaktifkan)<br>';
                    }
                }
                echo '</li>';
                
                echo '<li><strong>Simpan file</strong> (Ctrl+S atau File → Save)</li>';
                
                echo '<li><strong>Restart Apache</strong><br>';
                echo '- Di XAMPP Control Panel, klik tombol <strong>"Stop"</strong> pada Apache<br>';
                echo '- Tunggu beberapa detik<br>';
                echo '- Klik tombol <strong>"Start"</strong> pada Apache<br>';
                echo 'Atau restart seluruh XAMPP Control Panel';
                echo '</li>';
                
                echo '<li><strong>Verifikasi</strong><br>';
                echo 'Refresh halaman ini atau akses: <a href="test_extensions.php">test_extensions.php</a><br>';
                echo 'Semua ekstensi harus menunjukkan status <strong>"✓ AKTIF"</strong>';
                echo '</li>';
                echo '</ol>';
                echo '</div>';
                
                // Tips tambahan
                echo '<div class="info-box">';
                echo '<strong>💡 Tips:</strong><br>';
                echo '- Pastikan Anda mengedit file php.ini yang benar (cek lokasi di atas)<br>';
                echo '- Setelah mengedit, WAJIB restart Apache agar perubahan berlaku<br>';
                echo '- Jika masih tidak aktif, cek apakah file DLL ekstensi ada di folder <code>C:\\xampp\\php\\ext\\</code><br>';
                echo '- Ekstensi zip sangat penting karena file .xlsx adalah format ZIP yang berisi XML';
                echo '</div>';
            }
            ?>
            
            <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #e0e0e0;">
                <a href="test_extensions.php" class="button">Test Ekstensi</a>
                <a href="admin/arsip" class="button button-secondary">Kembali ke Arsip</a>
            </div>
        </div>
    </div>
</body>
</html>

