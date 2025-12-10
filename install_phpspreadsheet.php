<?php
/**
 * Script Instalasi PhpSpreadsheet
 * Jalankan script ini untuk menginstall PhpSpreadsheet secara otomatis
 * 
 * Cara penggunaan:
 * 1. Pastikan Composer sudah terinstall
 * 2. Buka terminal/command prompt di folder ini
 * 3. Jalankan: php install_phpspreadsheet.php
 */

echo "================================================================================\n";
echo "SCRIPT INSTALASI PHPSPREADSHEET\n";
echo "================================================================================\n\n";

// Cek apakah Composer tersedia
$composer_path = null;
$possible_paths = array(
    'composer',
    'composer.phar',
    __DIR__ . '/composer.phar'
);

foreach($possible_paths as $path) {
    $output = array();
    $return_var = 0;
    exec($path . ' --version 2>&1', $output, $return_var);
    if($return_var === 0) {
        $composer_path = $path;
        break;
    }
}

if(!$composer_path) {
    echo "ERROR: Composer tidak ditemukan!\n";
    echo "Silakan install Composer terlebih dahulu dari: https://getcomposer.org/download/\n";
    echo "Atau gunakan cara manual seperti yang dijelaskan di README_INSTALL_PHPSPREADSHEET.txt\n\n";
    exit(1);
}

echo "Composer ditemukan: $composer_path\n\n";

// Buat folder jika belum ada
$target_dir = __DIR__ . '/application/third_party/PhpSpreadsheet';
if(!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
    echo "Folder dibuat: $target_dir\n";
}

// Install PhpSpreadsheet
echo "Menginstall PhpSpreadsheet...\n";
chdir($target_dir);
exec($composer_path . ' require phpoffice/phpspreadsheet 2>&1', $output, $return_var);

if($return_var === 0) {
    echo "\nSUKSES! PhpSpreadsheet berhasil diinstall.\n\n";
    
    // Verifikasi instalasi
    $autoload_path = $target_dir . '/vendor/autoload.php';
    if(file_exists($autoload_path)) {
        echo "Verifikasi: File autoload.php ditemukan di: $autoload_path\n";
        echo "Instalasi berhasil!\n\n";
    } else {
        echo "PERINGATAN: File autoload.php tidak ditemukan. Mungkin ada masalah dengan instalasi.\n";
        echo "Silakan cek manual atau gunakan cara lain.\n\n";
    }
} else {
    echo "\nERROR: Gagal menginstall PhpSpreadsheet.\n";
    echo "Output:\n";
    foreach($output as $line) {
        echo "  $line\n";
    }
    echo "\nSilakan install manual sesuai panduan di README_INSTALL_PHPSPREADSHEET.txt\n\n";
    exit(1);
}

echo "================================================================================\n";
echo "SELESAI!\n";
echo "================================================================================\n";


