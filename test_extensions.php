<?php
/**
 * File untuk mengecek status ekstensi PHP yang diperlukan
 * Akses file ini di browser: http://localhost/arsip/test_extensions.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Ekstensi PHP - Sistem Arsip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
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
        .info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #2196F3;
        }
        .extension {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .extension.active {
            background: #e8f5e9;
            border-left: 4px solid #4CAF50;
        }
        .extension.inactive {
            background: #ffebee;
            border-left: 4px solid #f44336;
        }
        .status {
            font-weight: bold;
            padding: 5px 15px;
            border-radius: 20px;
        }
        .status.active {
            background: #4CAF50;
            color: white;
        }
        .status.inactive {
            background: #f44336;
            color: white;
        }
        .summary {
            margin-top: 30px;
            padding: 20px;
            border-radius: 5px;
            font-size: 18px;
            text-align: center;
        }
        .summary.success {
            background: #c8e6c9;
            color: #2e7d32;
        }
        .summary.error {
            background: #ffcdd2;
            color: #c62828;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Test Ekstensi PHP</h1>
        
        <div class="info">
            <strong>PHP Version:</strong> <?php echo PHP_VERSION; ?><br>
            <strong>Server API:</strong> <?php echo PHP_SAPI; ?><br>
            <strong>Loaded php.ini:</strong> <?php echo php_ini_loaded_file(); ?>
        </div>

        <h2>Status Ekstensi yang Diperlukan untuk PhpSpreadsheet:</h2>

        <?php
        $required_extensions = array(
            'zip' => array(
                'name' => 'ZipArchive',
                'description' => 'Wajib untuk membaca file .xlsx (format ZIP)',
                'required' => true
            ),
            'xml' => array(
                'name' => 'XML',
                'description' => 'Wajib untuk membaca file Excel',
                'required' => true
            ),
            'xmlwriter' => array(
                'name' => 'XMLWriter',
                'description' => 'Wajib untuk membaca file Excel',
                'required' => true
            ),
            'mbstring' => array(
                'name' => 'mbstring',
                'description' => 'Wajib untuk encoding karakter',
                'required' => true
            )
        );

        $all_active = true;
        foreach($required_extensions as $ext => $info) {
            $is_active = extension_loaded($ext);
            if(!$is_active) {
                $all_active = false;
            }
            
            $class = $is_active ? 'active' : 'inactive';
            $status_class = $is_active ? 'active' : 'inactive';
            $status_text = $is_active ? '✓ AKTIF' : '✗ TIDAK AKTIF';
            
            echo '<div class="extension ' . $class . '">';
            echo '<div>';
            echo '<strong>' . $info['name'] . '</strong> (ekstensi: ' . $ext . ')<br>';
            echo '<small>' . $info['description'] . '</small>';
            echo '</div>';
            echo '<span class="status ' . $status_class . '">' . $status_text . '</span>';
            echo '</div>';
        }
        ?>

        <div class="summary <?php echo $all_active ? 'success' : 'error'; ?>">
            <?php if($all_active): ?>
                ✅ <strong>Semua ekstensi aktif!</strong> PhpSpreadsheet siap digunakan.
            <?php else: ?>
                ❌ <strong>Ada ekstensi yang belum aktif!</strong><br>
                <small>Silakan ikuti panduan di file SOLUSI_EXTENSION_ZIP.md untuk mengaktifkan ekstensi yang diperlukan.</small>
            <?php endif; ?>
        </div>

        <div class="info" style="margin-top: 30px;">
            <strong>📝 Catatan:</strong><br>
            Jika ada ekstensi yang tidak aktif, ikuti langkah-langkah berikut:<br>
            1. Buka file <code>php.ini</code> (lihat lokasi di atas)<br>
            2. Cari baris yang berisi <code>;extension=zip</code> (dan ekstensi lainnya)<br>
            3. Hapus tanda <code>;</code> di depan baris tersebut<br>
            4. Simpan file dan restart Apache di XAMPP Control Panel<br>
            5. Refresh halaman ini untuk memverifikasi
        </div>
    </div>
</body>
</html>

