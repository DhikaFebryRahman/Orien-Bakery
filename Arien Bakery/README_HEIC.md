# HEIC to PNG Converter untuk Arien Bakery

## 📋 Deskripsi

File helper ini secara otomatis mengkonversi gambar **HEIC (High Efficiency Image Format)** ke **PNG** saat upload gambar di admin panel.

## 🚀 Fitur

- ✅ Auto-konversi HEIC ke PNG saat upload
- ✅ Support multiple file upload
- ✅ Telah konversi Validasi keamanan file
- ✅ Auto-delete temporary file set
- ✅ Logging untuk debugging
- ✅ Fallback jika konversi gagal

## ⚠️ CATATAN PENTING - CPANEL

### Untuk Shared Hosting cPanel (Tanpa SSH)

**PERINGATAN**: Shared hosting cPanel UMUMNYA **TIDAK** memiliki ImageMagick terinstall dan membatasi eksekusi command line.

**Jika upload HEIC tidak berfungsi di cPanel:**

1. **Opsi 1**: Konversi HEIC ke PNG di komputer Anda SEBELUM upload
   - Gunakan aplikasi: [CopyTrans HEIC](https://www.copytrans.net/copytransheic/), [Adobe Express](https://express.adobe.com/), atau online converter seperti [heicjpg.com](https://heicjpg.com)
   - Upload file PNG ke website

2. **Opsi 2**: Upgrade ke VPS hosting dengan SSH access
   - Anda bisa install ImageMagick
   - HEIC converter akan berfungsi penuh

3. **Opsi 3**: Hosting ini akan tetap bekerja untuk file PNG/JPG/JPEG normal

### Jika ImageMagick Tidak Tersedia

File `heic_converter.php` sudah dibuat dengan fallback aman:

- File HEIC akan **diabaikan** jika ImageMagick tidak tersedia
- File PNG/JPG/JPEG akan **tetap berfungsi normal**
- Error akan di-log tapi tidak crash website

## 📦 Dependencies

### Wajib

- **ImageMagick** (`convert` command)
- **PHP** dengan ekstensi `gd`

### Verifikasi Instalasi

```bash
# Cek ImageMagick
convert --version

# Cek PHP GD
php -m | grep gd

# Cek support HEIC
convert -list format | grep HEIC
```

## 🔧 Instalasi

### 1. Install ImageMagick (jika belum ada)

**Ubuntu/Debian:**

```bash
sudo apt-get update
sudo apt-get install imagemagick php-imagick
```

**CentOS/RHEL:**

```bash
sudo yum install ImageMagick
sudo yum install php-pecl-imagick
```

**Restart PHP-FPM:**

```bash
sudo systemctl restart php-fpm
# atau
sudo service php-fpm restart
```

### 2. Verifikasi HEIC Support

```bash
convert -list format | grep -i heic
```

Output yang benar:

```
HEIC            r--   Apple High efficiency Image Format
```

## 📁 File

| File                              | Lokasi   | Deskripsi           |
| --------------------------------- | -------- | ------------------- |
| `heic_converter.php`              | `app/`   | File helper utama   |
| `heic_implementation_example.php` | `admin/` | Contoh implementasi |
| `README.md`                       | root     | Dokumentasi         |

## 💻 Penggunaan

### 1. Include di File PHP

```php
<?php
require_once __DIR__ . '/../app/heic_converter.php';
?>
```

### 2. Single Upload

```php
<?php
$file = $_FILES['image'];

// Validasi file
$validation = validateImageUpload($file);
if (!$validation['valid']) {
    die('Error: ' . $validation['message']);
}

// Process upload dengan auto HEIC to PNG
$result = processImageUpload($file, "../uploads/products/", 'product');

if ($result['success']) {
    echo "Upload berhasil: " . $result['filename'];
    if ($result['was_heic']) {
        echo " (HEIC → PNG)";
    }
} else {
    echo "Error: " . $result['message'];
}
?>
```

### 3. Multiple Upload

```php
<?php
if (!empty($_FILES['images']['name'][0])) {
    foreach ($_FILES['images']['name'] as $key => $filename) {
        if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
            $file = [
                'name' => $_FILES['images']['name'][$key],
                'type' => $_FILES['images']['type'][$key],
                'tmp_name' => $_FILES['images']['tmp_name'][$key],
                'error' => $_FILES['images']['error'][$key],
                'size' => $_FILES['images']['size'][$key]
            ];

            $result = processImageUpload($file, "../uploads/products/", 'product_' . $product_id);

            if ($result['success']) {
                // Simpan ke database...
            }
        }
    }
}
?>
```

## 📝 API Reference

### `isHeicFile($filename)`

Cek apakah file adalah HEIC.

**Parameters:**

- `$filename` (string) - Nama file

**Returns:** `bool`

### `processImageUpload($file, $targetDir, $prefix)`

Process single image upload dengan auto HEIC conversion.

**Parameters:**

- `$file` (array) - Array dari `$_FILES['fieldname']`
- `$targetDir` (string) - Direktori tujuan
- `$prefix` (string) - Prefix nama file

**Returns:** `array` dengan keys:

- `success` (bool)
- `filename` (string|null)
- `message` (string)
- `was_heic` (bool)

### `processMultipleImageUploads($files, $targetDir, $prefix)`

Process multiple image uploads.

**Parameters:**

- `$files` (array) - Array dari `$_FILES['fieldname']`
- `$targetDir` (string) - Direktori tujuan
- `$prefix` (string) - Prefix nama file

**Returns:** `array` dari results

### `validateImageUpload($file, $allowedTypes)`

Validasi file gambar sebelum upload.

**Parameters:**

- `$file` (array) - Array dari `$_FILES['fieldname']`
- `$allowedTypes` (array) - Ekstensi yang diizinkan

**Returns:** `array` dengan keys:

- `valid` (bool)
- `message` (string)

### `checkImageMagickAvailability()`

Cek apakah ImageMagick tersedia.

**Returns:** `array` dengan keys:

- `available` (bool)
- `version` (string|null)
- `message` (string)

## 🎨 Contoh Implementasi di Form HTML

Tambahkan accept attribute untuk HEIC:

```html
<input type="file" name="image" accept="image/*,.heic,.heif" />
```

Untuk multiple:

```html
<input type="file" name="images[]" accept="image/*,.heic,.heif" multiple />
```

## ⚠️ Catatan Penting

1. **Ukuran File Maksimal**: 10MB
2. **Ekstensi Didukung**: jpg, jpeg, png, gif, webp, heic, heif
3. **Kualitas Output**: PNG quality 90%
4. **Temporary File**: Dihapus otomatis setelah konversi
5. **Error Log**: Semua error logged ke PHP error log

## 🔍 Troubleshooting

### Konversi Gagal

**Masalah:** `convert: no decode delegate for this image format`

**Solusi:** Install libheif

```bash
sudo apt-get install libheif-examples
```

### Permission Denied

**Masalah:** Tidak bisa menulis ke folder uploads

**Solusi:** Ubah permission folder

```bash
chmod 775 uploads/products
chmod 775 uploads/categories
chown www-data:www-data uploads/ -R
```

### Memory Limit

**Masalah:** `Allowed memory size exhausted`

**Solusi:** Tambah memory limit di php.ini

```ini
memory_limit = 256M
```

## 📊 Alur Konversi

```
1. User upload file HEIC
           ↓
2. Validasi file (ukuran, tipe, MIME)
           ↓
3. Copy ke temporary file
           ↓
4. Jalankan: convert file_heic -quality 90 file_png
           ↓
5. Validasi hasil konversi
           ↓
6. Hapus temporary file
           ↓
7. Simpan path PNG ke database
           ↓
8. Selesai ✓
```

## 📄 Lisensi

Dibuat untuk Arien Bakery Project

## 👨‍💻 Author

BlackboxAI
