<?php
/**
 * Script untuk mengecek dan membantu install PhpSpreadsheet
 * Jalankan script ini melalui browser atau command line
 */

echo "<h2>Pengecekan PhpSpreadsheet</h2>";
echo "<pre>";

$base_path = __DIR__;
$vendor_path = $base_path . '/application/third_party/PhpSpreadsheet/vendor';
$autoload_path = $vendor_path . '/autoload.php';
$phpoffice_path = $vendor_path . '/phpoffice/phpspreadsheet';

echo "Base Path: $base_path\n";
echo "Vendor Path: $vendor_path\n";
echo "Autoload Path: $autoload_path\n\n";

// Cek folder vendor
echo "1. Cek folder vendor...\n";
if(is_dir($vendor_path)) {
    echo "   ✓ Folder vendor ditemukan\n";
} else {
    echo "   ✗ Folder vendor TIDAK ditemukan\n";
    echo "   → Buat folder: $vendor_path\n";
    exit;
}

// Cek file autoload.php
echo "\n2. Cek file autoload.php...\n";
if(file_exists($autoload_path)) {
    echo "   ✓ File autoload.php ditemukan\n";
} else {
    echo "   ✗ File autoload.php TIDAK ditemukan\n";
    echo "   → File seharusnya ada di: $autoload_path\n";
}

// Cek folder phpoffice
echo "\n3. Cek folder phpoffice/phpspreadsheet...\n";
if(is_dir($phpoffice_path)) {
    echo "   ✓ Folder phpoffice/phpspreadsheet ditemukan\n";
} else {
    echo "   ✗ Folder phpoffice/phpspreadsheet TIDAK ditemukan\n";
    echo "   → Folder seharusnya ada di: $phpoffice_path\n";
}

// Cek composer.json
echo "\n4. Cek composer.json...\n";
$composer_json = $base_path . '/application/third_party/PhpSpreadsheet/composer.json';
if(file_exists($composer_json)) {
    echo "   ✓ File composer.json ditemukan\n";
    $composer_data = json_decode(file_get_contents($composer_json), true);
    if(isset($composer_data['require']['phpoffice/phpspreadsheet'])) {
        echo "   ✓ PhpSpreadsheet terdaftar di composer.json\n";
    } else {
        echo "   ✗ PhpSpreadsheet TIDAK terdaftar di composer.json\n";
    }
} else {
    echo "   ✗ File composer.json TIDAK ditemukan\n";
}

// Cek apakah composer tersedia
echo "\n5. Cek Composer...\n";
$composer_available = false;
$composer_path = null;

$possible_composer = array('composer', 'composer.phar');
foreach($possible_composer as $cmd) {
    $output = array();
    $return_var = 0;
    exec("$cmd --version 2>&1", $output, $return_var);
    if($return_var === 0) {
        $composer_available = true;
        $composer_path = $cmd;
        break;
    }
}

if($composer_available) {
    echo "   ✓ Composer tersedia: $composer_path\n";
} else {
    echo "   ✗ Composer TIDAK tersedia\n";
    echo "   → Download dari: https://getcomposer.org/download/\n";
}

// Kesimpulan dan solusi
echo "\n" . str_repeat("=", 70) . "\n";
echo "KESIMPULAN DAN SOLUSI:\n";
echo str_repeat("=", 70) . "\n\n";

if(file_exists($autoload_path) && is_dir($phpoffice_path)) {
    echo "✓ PhpSpreadsheet sudah terinstall dengan benar!\n";
    echo "  Path: $autoload_path\n";
} else {
    echo "✗ PhpSpreadsheet BELUM terinstall dengan benar!\n\n";
    echo "SOLUSI:\n";
    echo "--------\n\n";
    
    if($composer_available) {
        echo "1. Buka terminal/command prompt\n";
        echo "2. Masuk ke folder: application/third_party/PhpSpreadsheet\n";
        echo "3. Jalankan perintah:\n";
        echo "   $composer_path require phpoffice/phpspreadsheet\n\n";
        echo "Atau jika sudah ada composer.json:\n";
        echo "   $composer_path install\n\n";
    } else {
        echo "CARA 1: Install Composer terlebih dahulu\n";
        echo "  - Download dari: https://getcomposer.org/download/\n";
        echo "  - Install Composer\n";
        echo "  - Kemudian ikuti langkah di atas\n\n";
        
        echo "CARA 2: Download Manual\n";
        echo "  - Download PhpSpreadsheet dari: https://github.com/PHPOffice/PhpSpreadsheet\n";
        echo "  - Extract dan salin folder vendor ke: application/third_party/PhpSpreadsheet/\n";
        echo "  - Pastikan file autoload.php ada di: $autoload_path\n\n";
    }
    
    echo "Setelah install, refresh halaman ini untuk verifikasi.\n";
}

echo "\n" . str_repeat("=", 70) . "\n";


