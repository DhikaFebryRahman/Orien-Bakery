<?php

function convertToWebp($sourcePath, $targetPath)
{
    if (!file_exists($sourcePath)) {
        return false;
    }

    $command = sprintf(
        'convert "%s" -quality 80 "%s" 2>&1',
        escapeshellcmd($sourcePath),
        escapeshellcmd($targetPath)
    );

    exec($command, $output, $returnVar);

    if ($returnVar !== 0) {
        return false;
    }

    return file_exists($targetPath);
}

function processImageUpload($file, $targetDir, $prefix = 'image')
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [
            'success' => false,
            'filename' => null,
            'message' => 'Upload error code: ' . $file['error'],
            'was_heic' => false
        ];
    }

    if (!file_exists($file['tmp_name'])) {
        return [
            'success' => false,
            'filename' => null,
            'message' => 'Temporary file tidak ditemukan',
            'was_heic' => false
        ];
    }

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $newFilename = $prefix . '_' . time() . '_' . uniqid() . '.webp';
    $finalPath = rtrim($targetDir, '/') . '/' . $newFilename;

    if (!convertToWebp($file['tmp_name'], $finalPath)) {
        return [
            'success' => false,
            'filename' => null,
            'message' => 'Gagal mengkonversi gambar ke WEBP',
            'was_heic' => false
        ];
    }

    return [
        'success' => true,
        'filename' => $newFilename,
        'message' => 'Upload & convert ke WEBP berhasil',
        'was_heic' => false
    ];
}

function processMultipleImageUploads($files, $targetDir, $prefix = 'image')
{
    $results = [];

    if (!is_array($files['name'])) {
        return $results;
    }

    foreach ($files['name'] as $key => $name) {
        if (empty($name)) {
            continue;
        }

        $file = [
            'name' => $files['name'][$key],
            'type' => $files['type'][$key],
            'tmp_name' => $files['tmp_name'][$key],
            'error' => $files['error'][$key],
            'size' => $files['size'][$key]
        ];

        $filePrefix = $prefix . '_' . $key;
        $results[] = processImageUpload($file, $targetDir, $filePrefix);
    }

    return $results;
}

function validateImageUpload($file, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic', 'heif'])
{
    $maxSize = 10 * 1024 * 1024;

    if ($file['size'] > $maxSize) {
        return [
            'valid' => false,
            'message' => 'Ukuran file terlalu besar. Maksimal 10MB.'
        ];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedTypes)) {
        return [
            'valid' => false,
            'message' => 'Tipe file tidak diizinkan.'
        ];
    }

    $allowedMimes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/heic',
        'image/heif'
    ];

    $detectedMime = mime_content_type($file['tmp_name']);

    if (!in_array($detectedMime, $allowedMimes)) {
        return [
            'valid' => false,
            'message' => 'Tipe MIME tidak valid.'
        ];
    }

    return [
        'valid' => true,
        'message' => 'File valid'
    ];
}

function checkImageMagickAvailability()
{
    $command = 'convert --version 2>&1';
    exec($command, $output, $returnVar);

    if ($returnVar === 0) {
        return [
            'available' => true,
            'version' => implode(' ', array_slice($output, 0, 2)),
            'message' => 'ImageMagick tersedia'
        ];
    }

    return [
        'available' => false,
        'version' => null,
        'message' => 'ImageMagick tidak tersedia'
    ];
}

function setupHeicConverter()
{
    return checkImageMagickAvailability();
}

setupHeicConverter();
