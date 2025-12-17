<?php
/**
 * Script untuk mengecek dan memberikan informasi tentang limit upload PHP
 * Akses via browser: http://localhost/arsip/check_upload_limits.php
 */

// Fungsi untuk parse size string ke bytes
function parseSize($size) {
    if(is_numeric($size)) {
        return (int)$size;
    }
    
    $unit = strtoupper(substr($size, -1));
    $value = (float)substr($size, 0, -1);
    
    switch($unit) {
        case 'G':
            return $value * 1024 * 1024 * 1024;
        case 'M':
            return $value * 1024 * 1024;
        case 'K':
            return $value * 1024;
        default:
            return (int)$value;
    }
}

// Fungsi untuk format bytes
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, $precision) . ' ' . $units[$pow];
}

// Ambil nilai dari php.ini
$upload_max_filesize = ini_get('upload_max_filesize');
$post_max_size = ini_get('post_max_size');
$max_execution_time = ini_get('max_execution_time');
$max_input_time = ini_get('max_input_time');
$memory_limit = ini_get('memory_limit');
$php_ini_file = php_ini_loaded_file();
$php_ini_scanned = php_ini_scanned_files();

// Konversi ke bytes untuk perbandingan
$upload_max_filesize_bytes = parseSize($upload_max_filesize);
$post_max_size_bytes = parseSize($post_max_size);
$memory_limit_bytes = parseSize($memory_limit);

// Status
$status_ok = true;
$warnings = array();
$recommendations = array();

// Cek post_max_size harus >= upload_max_filesize
if($post_max_size_bytes < $upload_max_filesize_bytes) {
    $status_ok = false;
    $warnings[] = "post_max_size ($post_max_size) lebih kecil dari upload_max_filesize ($upload_max_filesize). post_max_size harus lebih besar atau sama dengan upload_max_filesize.";
}

// Cek nilai yang disarankan
if($upload_max_filesize_bytes < 100 * 1024 * 1024) { // < 100MB
    $recommendations[] = "upload_max_filesize saat ini $upload_max_filesize. Untuk upload file besar, disarankan minimal 100M atau lebih.";
}

if($post_max_size_bytes < 100 * 1024 * 1024) { // < 100MB
    $recommendations[] = "post_max_size saat ini $post_max_size. Untuk upload file besar, disarankan minimal 100M atau lebih.";
}

if($max_execution_time < 300) { // < 5 menit
    $recommendations[] = "max_execution_time saat ini $max_execution_time detik. Untuk upload file besar, disarankan minimal 300 (5 menit) atau 0 (unlimited).";
}

if($max_input_time < 300) {
    $recommendations[] = "max_input_time saat ini $max_input_time detik. Untuk upload file besar, disarankan minimal 300 (5 menit) atau -1 (unlimited).";
}

if($memory_limit_bytes < 256 * 1024 * 1024) { // < 256MB
    $recommendations[] = "memory_limit saat ini $memory_limit. Untuk upload file besar, disarankan minimal 256M atau lebih.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Upload Limits - Arsip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        h2 {
            color: #555;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #4CAF50;
            color: white;
        }
        .status-ok {
            color: #4CAF50;
            font-weight: bold;
        }
        .status-warning {
            color: #FF9800;
            font-weight: bold;
        }
        .status-error {
            color: #f44336;
            font-weight: bold;
        }
        .alert {
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .alert-info {
            background: #E3F2FD;
            border-left: 4px solid #2196F3;
            color: #1976D2;
        }
        .alert-warning {
            background: #FFF3E0;
            border-left: 4px solid #FF9800;
            color: #E65100;
        }
        .alert-success {
            background: #E8F5E9;
            border-left: 4px solid #4CAF50;
            color: #2E7D32;
        }
        .code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
        .instructions {
            background: #F5F5F5;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .instructions ol {
            margin-left: 20px;
        }
        .instructions li {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Pengecekan Limit Upload PHP</h1>
        
        <div class="alert alert-info">
            <strong>📝 Informasi:</strong> Script ini mengecek konfigurasi PHP untuk upload file. 
            Aplikasi Arsip tidak membatasi ukuran file, tetapi limit PHP server mungkin membatasi.
        </div>
        
        <?php if(!$status_ok): ?>
        <div class="alert alert-warning">
            <strong>⚠️ Peringatan:</strong> Ditemukan masalah dengan konfigurasi PHP!
        </div>
        <?php endif; ?>
        
        <h2>📊 Konfigurasi Saat Ini</h2>
        <table>
            <tr>
                <th>Setting</th>
                <th>Nilai Saat Ini</th>
                <th>Status</th>
            </tr>
            <tr>
                <td><strong>upload_max_filesize</strong></td>
                <td><span class="code"><?php echo $upload_max_filesize; ?></span> (<?php echo formatBytes($upload_max_filesize_bytes); ?>)</td>
                <td>
                    <?php if($upload_max_filesize_bytes >= 100 * 1024 * 1024): ?>
                        <span class="status-ok">✓ OK</span>
                    <?php else: ?>
                        <span class="status-warning">⚠ Disarankan ditingkatkan</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>post_max_size</strong></td>
                <td><span class="code"><?php echo $post_max_size; ?></span> (<?php echo formatBytes($post_max_size_bytes); ?>)</td>
                <td>
                    <?php if($post_max_size_bytes >= $upload_max_filesize_bytes && $post_max_size_bytes >= 100 * 1024 * 1024): ?>
                        <span class="status-ok">✓ OK</span>
                    <?php elseif($post_max_size_bytes < $upload_max_filesize_bytes): ?>
                        <span class="status-error">✗ HARUS lebih besar dari upload_max_filesize</span>
                    <?php else: ?>
                        <span class="status-warning">⚠ Disarankan ditingkatkan</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>max_execution_time</strong></td>
                <td><span class="code"><?php echo $max_execution_time; ?> detik</span> <?php echo $max_execution_time == 0 ? '(unlimited)' : ''; ?></td>
                <td>
                    <?php if($max_execution_time >= 300 || $max_execution_time == 0): ?>
                        <span class="status-ok">✓ OK</span>
                    <?php else: ?>
                        <span class="status-warning">⚠ Disarankan ditingkatkan</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>max_input_time</strong></td>
                <td><span class="code"><?php echo $max_input_time; ?> detik</span> <?php echo $max_input_time == -1 ? '(unlimited)' : ''; ?></td>
                <td>
                    <?php if($max_input_time >= 300 || $max_input_time == -1): ?>
                        <span class="status-ok">✓ OK</span>
                    <?php else: ?>
                        <span class="status-warning">⚠ Disarankan ditingkatkan</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>memory_limit</strong></td>
                <td><span class="code"><?php echo $memory_limit; ?></span> (<?php echo formatBytes($memory_limit_bytes); ?>)</td>
                <td>
                    <?php if($memory_limit_bytes >= 256 * 1024 * 1024): ?>
                        <span class="status-ok">✓ OK</span>
                    <?php else: ?>
                        <span class="status-warning">⚠ Disarankan ditingkatkan</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        
        <h2>📁 Informasi php.ini</h2>
        <table>
            <tr>
                <th>Item</th>
                <th>Nilai</th>
            </tr>
            <tr>
                <td><strong>File php.ini yang digunakan</strong></td>
                <td><span class="code"><?php echo $php_ini_file ?: 'Tidak ditemukan'; ?></span></td>
            </tr>
            <?php if($php_ini_scanned): ?>
            <tr>
                <td><strong>File php.ini tambahan</strong></td>
                <td><span class="code"><?php echo $php_ini_scanned; ?></span></td>
            </tr>
            <?php endif; ?>
        </table>
        
        <?php if(!empty($warnings)): ?>
        <h2>⚠️ Peringatan</h2>
        <?php foreach($warnings as $warning): ?>
        <div class="alert alert-warning">
            <?php echo $warning; ?>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if(!empty($recommendations)): ?>
        <h2>💡 Rekomendasi</h2>
        <?php foreach($recommendations as $rec): ?>
        <div class="alert alert-info">
            <?php echo $rec; ?>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
        
        <h2>🔧 Cara Mengubah php.ini (XAMPP)</h2>
        <div class="instructions">
            <ol>
                <li><strong>Buka XAMPP Control Panel</strong></li>
                <li>Klik tombol <strong>"Config"</strong> di sebelah Apache</li>
                <li>Pilih <strong>"PHP (php.ini)"</strong> - file akan terbuka di text editor</li>
                <li>Cari dan ubah baris-baris berikut (tekan <strong>Ctrl+F</strong> untuk mencari):</li>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li><span class="code">upload_max_filesize = 100M</span> (atau lebih besar, misalnya 500M, 1G)</li>
                    <li><span class="code">post_max_size = 100M</span> (harus >= upload_max_filesize, misalnya 500M, 1G)</li>
                    <li><span class="code">max_execution_time = 300</span> (atau 0 untuk unlimited)</li>
                    <li><span class="code">max_input_time = 300</span> (atau -1 untuk unlimited)</li>
                    <li><span class="code">memory_limit = 256M</span> (atau lebih besar)</li>
                </ul>
                <li><strong>Simpan</strong> file php.ini (Ctrl+S)</li>
                <li><strong>Restart Apache</strong> di XAMPP Control Panel</li>
            </ol>
        </div>
        
        <div class="alert alert-success">
            <strong>✅ Catatan:</strong> Setelah mengubah php.ini, pastikan untuk <strong>restart Apache</strong> agar perubahan berlaku. 
            Refresh halaman ini untuk melihat perubahan.
        </div>
        
        <p style="margin-top: 30px; color: #666; font-size: 0.9em;">
            <strong>Lokasi file php.ini:</strong> <?php echo $php_ini_file ?: 'C:\\xampp\\php\\php.ini (default XAMPP)'; ?>
        </p>
    </div>
</body>
</html>

