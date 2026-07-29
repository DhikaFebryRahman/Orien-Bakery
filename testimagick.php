<?php

echo "<h2>🔍 ImageMagick Test</h2>";

// ===== 1. CEK exec() =====
echo "<h3>1. Status exec()</h3>";
$disabled = array_map('trim', explode(',', ini_get('disable_functions')));
if (in_array('exec', $disabled)) {
    echo "❌ exec() dinonaktifkan di php.ini<br>";
} else {
    echo "✅ exec() aktif<br>";
}

// ===== 2. CEK OS =====
echo "<h3>2. Sistem Operasi</h3>";
echo "OS: <b>" . PHP_OS . "</b><br>";
$isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
echo "Terdeteksi sebagai: <b>" . ($isWindows ? 'Windows' : 'Linux/Mac') . "</b><br>";

// ===== 3. CEK IMAGEMAGICK =====
echo "<h3>3. Status ImageMagick</h3>";

// Coba 'magick' (Windows / IM versi 7+)
exec('magick --version 2>&1', $output1, $return1);
if ($return1 === 0) {
    echo "✅ ImageMagick tersedia via <code>magick</code><br>";
    echo "<pre>" . implode("\n", $output1) . "</pre>";
} else {
    echo "❌ Command <code>magick</code> tidak ditemukan<br>";
}

// Coba 'convert' (Linux / IM versi lama)
exec('convert --version 2>&1', $output2, $return2);
if ($return2 === 0) {
    echo "✅ ImageMagick tersedia via <code>convert</code><br>";
    echo "<pre>" . implode("\n", $output2) . "</pre>";
} else {
    echo "❌ Command <code>convert</code> tidak ditemukan atau bentrok dengan Windows system tool<br>";
}

// ===== 4. CEK PHP IMAGICK EXTENSION =====
echo "<h3>4. PHP Imagick Extension</h3>";
if (extension_loaded('imagick')) {
    echo "✅ Imagick extension aktif<br>";
} else {
    echo "❌ Imagick extension tidak aktif (tidak masalah kalau pakai exec)<br>";
}

// ===== 5. TEST KONVERSI NYATA =====
echo "<h3>5. Test Konversi ke WebP</h3>";

// Buat gambar dummy PNG dulu pakai GD
$testSource = __DIR__ . '/test_input.png';
$testOutput = __DIR__ . '/test_output.webp';

// Buat file PNG sederhana
$img = imagecreatetruecolor(100, 100);
$color = imagecolorallocate($img, 255, 100, 50);
imagefill($img, 0, 0, $color);
imagepng($img, $testSource);
imagedestroy($img);

if (file_exists($testSource)) {
    echo "✅ File test PNG berhasil dibuat<br>";

    // Pilih command sesuai OS
    $convertBin = $isWindows ? 'magick convert' : 'convert';
    $command = sprintf('%s "%s" -quality 80 "%s" 2>&1', $convertBin, $testSource, $testOutput);

    exec($command, $outputConv, $returnConv);

    if ($returnConv === 0 && file_exists($testOutput)) {
        $size = filesize($testOutput);
        echo "✅ Konversi ke WebP <b>BERHASIL!</b><br>";
        echo "📁 Output: <code>test_output.webp</code> ({$size} bytes)<br>";
        echo "🎉 ImageMagick siap digunakan di project kamu!<br>";
    } else {
        echo "❌ Konversi GAGAL<br>";
        echo "Command: <code>{$command}</code><br>";
        if (!empty($outputConv)) {
            echo "Error output:<br><pre>" . implode("\n", $outputConv) . "</pre>";
        }
    }

    // Hapus file test
    @unlink($testSource);
    @unlink($testOutput);

} else {
    echo "❌ Gagal membuat file test PNG (GD mungkin tidak aktif)<br>";
}

// ===== 6. RINGKASAN =====
echo "<h3>6. Ringkasan</h3>";
$imAvailable = ($return1 === 0 || $return2 === 0);
$execAvailable = !in_array('exec', $disabled);
$conversionOk = isset($returnConv) && $returnConv === 0;

if ($imAvailable && $execAvailable && $conversionOk) {
    echo "<div style='background:#d4edda;padding:10px;border-radius:5px;color:#155724;'>
        ✅ <b>Semua OK!</b> ImageMagick siap digunakan di project kamu.
    </div>";
} else {
    echo "<div style='background:#f8d7da;padding:10px;border-radius:5px;color:#721c24;'>
        ❌ <b>Ada masalah:</b><br>";
    if (!$execAvailable) echo "- exec() dinonaktifkan<br>";
    if (!$imAvailable)   echo "- ImageMagick tidak ditemukan<br>";
    if (!$conversionOk)  echo "- Konversi gagal<br>";
    echo "</div>";
}
?>