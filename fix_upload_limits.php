<?php
/**
 * Script untuk membantu mengubah limit upload di php.ini
 * Akses via browser: http://localhost/arsip/fix_upload_limits.php
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

// Nilai yang disarankan
$recommended_upload_max = '500M';
$recommended_post_max = '500M';
$recommended_execution_time = '300';
$recommended_input_time = '300';
$recommended_memory = '256M';

// Cek apakah file php.ini bisa ditulis
$php_ini_writable = false;
if($php_ini_file && file_exists($php_ini_file)) {
    $php_ini_writable = is_writable($php_ini_file);
}

// Handle form submission untuk update php.ini
$update_success = false;
$update_error = '';
$update_message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_phpini') {
    if(!$php_ini_writable) {
        $update_error = "File php.ini tidak dapat ditulis. Silakan ubah secara manual.";
    } else {
        // Baca file php.ini
        $php_ini_content = file_get_contents($php_ini_file);
        $original_content = $php_ini_content;
        
        // Update values
        $updates = array();
        
        if(isset($_POST['upload_max_filesize'])) {
            $new_value = trim($_POST['upload_max_filesize']);
            $php_ini_content = preg_replace(
                '/^upload_max_filesize\s*=\s*.*$/m',
                'upload_max_filesize = ' . $new_value,
                $php_ini_content
            );
            $updates[] = "upload_max_filesize = $new_value";
        }
        
        if(isset($_POST['post_max_size'])) {
            $new_value = trim($_POST['post_max_size']);
            $php_ini_content = preg_replace(
                '/^post_max_size\s*=\s*.*$/m',
                'post_max_size = ' . $new_value,
                $php_ini_content
            );
            $updates[] = "post_max_size = $new_value";
        }
        
        if(isset($_POST['max_execution_time'])) {
            $new_value = trim($_POST['max_execution_time']);
            $php_ini_content = preg_replace(
                '/^max_execution_time\s*=\s*.*$/m',
                'max_execution_time = ' . $new_value,
                $php_ini_content
            );
            $updates[] = "max_execution_time = $new_value";
        }
        
        if(isset($_POST['max_input_time'])) {
            $new_value = trim($_POST['max_input_time']);
            $php_ini_content = preg_replace(
                '/^max_input_time\s*=\s*.*$/m',
                'max_input_time = ' . $new_value,
                $php_ini_content
            );
            $updates[] = "max_input_time = $new_value";
        }
        
        if(isset($_POST['memory_limit'])) {
            $new_value = trim($_POST['memory_limit']);
            $php_ini_content = preg_replace(
                '/^memory_limit\s*=\s*.*$/m',
                'memory_limit = ' . $new_value,
                $php_ini_content
            );
            $updates[] = "memory_limit = $new_value";
        }
        
        // Simpan file
        if($php_ini_content !== $original_content) {
            if(file_put_contents($php_ini_file, $php_ini_content)) {
                $update_success = true;
                $update_message = "File php.ini berhasil diupdate! Perubahan: " . implode(", ", $updates);
                $update_message .= "<br><strong>⚠️ PENTING: Restart Apache di XAMPP Control Panel agar perubahan berlaku!</strong>";
                
                // Refresh nilai
                $upload_max_filesize = ini_get('upload_max_filesize');
                $post_max_size = ini_get('post_max_size');
                $max_execution_time = ini_get('max_execution_time');
                $max_input_time = ini_get('max_input_time');
                $memory_limit = ini_get('memory_limit');
            } else {
                $update_error = "Gagal menyimpan file php.ini. Pastikan file dapat ditulis.";
            }
        } else {
            $update_error = "Tidak ada perubahan yang dilakukan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix Upload Limits - Arsip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
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
        .form-group {
            margin: 15px 0;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        .form-group input {
            width: 200px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group small {
            display: block;
            margin-top: 5px;
            color: #666;
        }
        .btn {
            background: #4CAF50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #45a049;
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
        .alert-error {
            background: #FFEBEE;
            border-left: 4px solid #f44336;
            color: #C62828;
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
        .current-value {
            color: #666;
            font-size: 0.9em;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Perbaiki Limit Upload PHP</h1>
        
        <?php if($update_success): ?>
        <div class="alert alert-success">
            <strong>✅ Berhasil!</strong><br>
            <?php echo $update_message; ?>
        </div>
        <?php endif; ?>
        
        <?php if($update_error): ?>
        <div class="alert alert-error">
            <strong>❌ Error:</strong> <?php echo $update_error; ?>
        </div>
        <?php endif; ?>
        
        <div class="alert alert-info">
            <strong>📝 Informasi:</strong> Script ini membantu mengubah limit upload di php.ini. 
            <strong>File php.ini yang digunakan:</strong> <span class="code"><?php echo $php_ini_file ?: 'Tidak ditemukan'; ?></span>
        </div>
        
        <?php if(!$php_ini_writable): ?>
        <div class="alert alert-warning">
            <strong>⚠️ Peringatan:</strong> File php.ini tidak dapat ditulis secara otomatis. 
            Silakan ikuti instruksi manual di bawah untuk mengubah secara manual.
        </div>
        <?php endif; ?>
        
        <h2>📊 Konfigurasi Saat Ini</h2>
        <table>
            <tr>
                <th>Setting</th>
                <th>Nilai Saat Ini</th>
                <th>Nilai Disarankan</th>
            </tr>
            <tr>
                <td><strong>upload_max_filesize</strong></td>
                <td><span class="code"><?php echo $upload_max_filesize; ?></span></td>
                <td><span class="code"><?php echo $recommended_upload_max; ?></span></td>
            </tr>
            <tr>
                <td><strong>post_max_size</strong></td>
                <td><span class="code"><?php echo $post_max_size; ?></span></td>
                <td><span class="code"><?php echo $recommended_post_max; ?></span></td>
            </tr>
            <tr>
                <td><strong>max_execution_time</strong></td>
                <td><span class="code"><?php echo $max_execution_time; ?> detik</span></td>
                <td><span class="code"><?php echo $recommended_execution_time; ?> detik</span></td>
            </tr>
            <tr>
                <td><strong>max_input_time</strong></td>
                <td><span class="code"><?php echo $max_input_time; ?> detik</span></td>
                <td><span class="code"><?php echo $recommended_input_time; ?> detik</span></td>
            </tr>
            <tr>
                <td><strong>memory_limit</strong></td>
                <td><span class="code"><?php echo $memory_limit; ?></span></td>
                <td><span class="code"><?php echo $recommended_memory; ?></span></td>
            </tr>
        </table>
        
        <?php if($php_ini_writable): ?>
        <h2>⚙️ Update php.ini (Otomatis)</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="update_phpini">
            
            <div class="form-group">
                <label>
                    upload_max_filesize 
                    <span class="current-value">(saat ini: <?php echo $upload_max_filesize; ?>)</span>
                </label>
                <input type="text" name="upload_max_filesize" value="<?php echo $recommended_upload_max; ?>" placeholder="500M">
                <small>Contoh: 500M, 1G, 100M</small>
            </div>
            
            <div class="form-group">
                <label>
                    post_max_size 
                    <span class="current-value">(saat ini: <?php echo $post_max_size; ?>)</span>
                </label>
                <input type="text" name="post_max_size" value="<?php echo $recommended_post_max; ?>" placeholder="500M">
                <small>Harus >= upload_max_filesize. Contoh: 500M, 1G</small>
            </div>
            
            <div class="form-group">
                <label>
                    max_execution_time 
                    <span class="current-value">(saat ini: <?php echo $max_execution_time; ?> detik)</span>
                </label>
                <input type="text" name="max_execution_time" value="<?php echo $recommended_execution_time; ?>" placeholder="300">
                <small>0 = unlimited. Contoh: 300, 600, 0</small>
            </div>
            
            <div class="form-group">
                <label>
                    max_input_time 
                    <span class="current-value">(saat ini: <?php echo $max_input_time; ?> detik)</span>
                </label>
                <input type="text" name="max_input_time" value="<?php echo $recommended_input_time; ?>" placeholder="300">
                <small>-1 = unlimited. Contoh: 300, 600, -1</small>
            </div>
            
            <div class="form-group">
                <label>
                    memory_limit 
                    <span class="current-value">(saat ini: <?php echo $memory_limit; ?>)</span>
                </label>
                <input type="text" name="memory_limit" value="<?php echo $recommended_memory; ?>" placeholder="256M">
                <small>Contoh: 256M, 512M, 1G</small>
            </div>
            
            <button type="submit" class="btn">💾 Update php.ini</button>
        </form>
        <?php endif; ?>
        
        <h2>📖 Cara Manual (Jika Otomatis Tidak Bisa)</h2>
        <div class="instructions">
            <ol>
                <li><strong>Buka XAMPP Control Panel</strong></li>
                <li>Klik tombol <strong>"Config"</strong> di sebelah Apache</li>
                <li>Pilih <strong>"PHP (php.ini)"</strong> - file akan terbuka di text editor</li>
                <li>Cari baris-baris berikut (tekan <strong>Ctrl+F</strong> untuk mencari):</li>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li><span class="code">upload_max_filesize = 40M</span> → ubah menjadi <span class="code">upload_max_filesize = 500M</span></li>
                    <li><span class="code">post_max_size = 40M</span> → ubah menjadi <span class="code">post_max_size = 500M</span></li>
                    <li><span class="code">max_execution_time = 30</span> → ubah menjadi <span class="code">max_execution_time = 300</span> (atau 0 untuk unlimited)</li>
                    <li><span class="code">max_input_time = 60</span> → ubah menjadi <span class="code">max_input_time = 300</span> (atau -1 untuk unlimited)</li>
                    <li><span class="code">memory_limit = 128M</span> → ubah menjadi <span class="code">memory_limit = 256M</span></li>
                </ul>
                <li><strong>Simpan</strong> file php.ini (Ctrl+S)</li>
                <li><strong>Restart Apache</strong> di XAMPP Control Panel (klik Stop lalu Start)</li>
                <li>Refresh halaman ini untuk melihat perubahan</li>
            </ol>
        </div>
        
        <div class="alert alert-warning">
            <strong>⚠️ PENTING:</strong> Setelah mengubah php.ini, <strong>WAJIB restart Apache</strong> agar perubahan berlaku! 
            Tanpa restart, perubahan tidak akan aktif.
        </div>
        
        <h2>🔍 Verifikasi</h2>
        <p>Setelah restart Apache, refresh halaman ini atau buka <a href="check_upload_limits.php">check_upload_limits.php</a> untuk memverifikasi perubahan.</p>
        
        <p style="margin-top: 30px; color: #666; font-size: 0.9em;">
            <strong>Lokasi file php.ini:</strong> <?php echo $php_ini_file ?: 'C:\\xampp\\php\\php.ini (default XAMPP)'; ?><br>
            <?php if($php_ini_writable): ?>
            <span style="color: green;">✓ File dapat ditulis</span>
            <?php else: ?>
            <span style="color: red;">✗ File tidak dapat ditulis (perlu akses admin atau ubah permission)</span>
            <?php endif; ?>
        </p>
    </div>
</body>
</html>

