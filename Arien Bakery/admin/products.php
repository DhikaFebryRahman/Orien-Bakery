<?php
session_start();
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/heic_converter.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* ======================
   ADD PRODUCT
====================== */
if (isset($_POST['add'])) {
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("
        INSERT INTO products (category_id, name, price)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$category_id, $name, $price]);
    $product_id = $pdo->lastInsertId();

    // Handle multiple image uploads dengan HEIC support
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['name'] as $key => $filename) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK && !empty($filename)) {
                $sort_order = $_POST['sort_order'][$key] ?? $key;

                $file = [
                    'name' => $_FILES['images']['name'][$key],
                    'type' => $_FILES['images']['type'][$key],
                    'tmp_name' => $_FILES['images']['tmp_name'][$key],
                    'error' => $_FILES['images']['error'][$key],
                    'size' => $_FILES['images']['size'][$key]
                ];

                // Validasi file
                $validation = validateImageUpload($file);
                if (!$validation['valid']) {
                    continue;
                }

                // Process upload dengan auto HEIC to PNG
                $result = processImageUpload($file, "../uploads/products/", 'product_' . $product_id);

                if ($result['success']) {
                    $img_stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_path, sort_order) VALUES (?, ?, ?)");
                    $img_stmt->execute([$product_id, $result['filename'], $sort_order]);
                }
            }
        }
    }

    // Handle descriptions
    if (!empty($_POST['descriptions'][0])) {
        foreach ($_POST['descriptions'] as $key => $desc) {
            if (!empty(trim($desc))) {
                $is_large = isset($_POST['is_large_font'][$key]) ? 1 : 0;
                $sort_order = $_POST['desc_sort_order'][$key] ?? $key;

                $desc_stmt = $pdo->prepare("INSERT INTO product_descriptions (product_id, description, is_large_font, sort_order) VALUES (?, ?, ?, ?)");
                $desc_stmt->execute([$product_id, trim($desc), $is_large, $sort_order]);
            }
        }
    }

    header("Location: products.php");
    exit;
}

/* ======================
   UPDATE PRODUCT
====================== */
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("
        UPDATE products
        SET category_id=?, name=?, price=?
        WHERE id=?
    ");
    $stmt->execute([$category_id, $name, $price, $id]);

    // Handle additional image uploads dengan HEIC support
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['name'] as $key => $filename) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK && !empty($filename)) {
                $sort_order = $_POST['sort_order'][$key] ?? 999;

                $file = [
                    'name' => $_FILES['images']['name'][$key],
                    'type' => $_FILES['images']['type'][$key],
                    'tmp_name' => $_FILES['images']['tmp_name'][$key],
                    'error' => $_FILES['images']['error'][$key],
                    'size' => $_FILES['images']['size'][$key]
                ];

                // Validasi file
                $validation = validateImageUpload($file);
                if (!$validation['valid']) {
                    continue;
                }

                // Process upload dengan auto HEIC to PNG
                $result = processImageUpload($file, "../uploads/products/", 'product_' . $id);

                if ($result['success']) {
                    $img_stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_path, sort_order) VALUES (?, ?, ?)");
                    $img_stmt->execute([$id, $result['filename'], $sort_order]);
                }
            }
        }
    }

    // Handle additional descriptions
    if (!empty($_POST['descriptions'][0])) {
        foreach ($_POST['descriptions'] as $key => $desc) {
            if (!empty(trim($desc))) {
                $is_large = isset($_POST['is_large_font'][$key]) ? 1 : 0;
                $sort_order = $_POST['desc_sort_order'][$key] ?? 999;

                $desc_stmt = $pdo->prepare("INSERT INTO product_descriptions (product_id, description, is_large_font, sort_order) VALUES (?, ?, ?, ?)");
                $desc_stmt->execute([$id, trim($desc), $is_large, $sort_order]);
            }
        }
    }

    header("Location: products.php");
    exit;
}

/* ======================
   DELETE PRODUCT
====================== */
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: products.php");
    exit;
}

/* ======================
   DELETE IMAGE
====================== */
if (isset($_GET['delete_image'])) {
    $id = $_GET['delete_image'];
    $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE id=?");
    $stmt->execute([$id]);
    $img = $stmt->fetch();

    if ($img) {
        @unlink("../uploads/products/" . $img['image_path']);
        $del = $pdo->prepare("DELETE FROM product_images WHERE id=?");
        $del->execute([$id]);
    }

    header("Location: ?edit=" . $_GET['product_id']);
    exit;
}

/* ======================
   DELETE DESCRIPTION
====================== */
if (isset($_GET['delete_desc'])) {
    $id = $_GET['delete_desc'];
    $del = $pdo->prepare("DELETE FROM product_descriptions WHERE id=?");
    $del->execute([$id]);

    header("Location: ?edit=" . $_GET['product_id']);
    exit;
}

/* ======================
   UPDATE SORT ORDER
====================== */
if (isset($_POST['update_sort'])) {
    // Update image sort order
    if (!empty($_POST['image_sort'])) {
        foreach ($_POST['image_sort'] as $id => $sort) {
            $stmt = $pdo->prepare("UPDATE product_images SET sort_order=? WHERE id=?");
            $stmt->execute([$sort, $id]);
        }
    }

    // Update description sort order
    if (!empty($_POST['desc_sort'])) {
        foreach ($_POST['desc_sort'] as $id => $sort) {
            $stmt = $pdo->prepare("UPDATE product_descriptions SET sort_order=? WHERE id=?");
            $stmt->execute([$sort, $id]);
        }
    }

    // Update existing descriptions
    if (!empty($_POST['existing_descriptions'])) {
        foreach ($_POST['existing_descriptions'] as $id => $desc) {
            $is_large = isset($_POST['existing_is_large'][$id]) ? 1 : 0;
            $stmt = $pdo->prepare("UPDATE product_descriptions SET description=?, is_large_font=? WHERE id=?");
            $stmt->execute([trim($desc), $is_large, $id]);
        }
    }

    header("Location: ?edit=" . ($_POST['product_id'] ?? ''));
    exit;
}

/* ======================
   FETCH DATA
====================== */
$products = $pdo->query("
    SELECT products.*, categories.name AS category
    FROM products
    JOIN categories ON products.category_id = categories.id
    ORDER BY products.id DESC
")->fetchAll();

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

$edit = null;
$edit_images = [];
$edit_descriptions = [];

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();

    // Get images
    $img_stmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id=? ORDER BY sort_order ASC");
    $img_stmt->execute([$_GET['edit']]);
    $edit_images = $img_stmt->fetchAll();

    // Get descriptions
    $desc_stmt = $pdo->prepare("SELECT * FROM product_descriptions WHERE product_id=? ORDER BY sort_order ASC");
    $desc_stmt->execute([$_GET['edit']]);
    $edit_descriptions = $desc_stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin - Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .drag-handle {
            cursor: grab;
        }

        .drag-handle:active {
            cursor: grabbing;
        }
    </style>
</head>

<body class="bg-[#F5E8D7] text-[#3F2E1F]">

    <div class="container mx-auto px-4 py-10 max-w-6xl">

        <h1 class="text-2xl font-bold mb-6">Manage Products</h1>
        <div class="mb-4">
            <a href="dashboard.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
              bg-[#E8D8C4] hover:bg-[#F1E3C6] font-medium transition">
                ← Kembali ke Dashboard
            </a>
        </div>

        <!-- FORM -->
        <div class="bg-white p-6 rounded-xl shadow mb-10">
            <form method="POST" enctype="multipart/form-data" class="space-y-6">

                <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Category</label>
                        <select name="category_id" required class="w-full border rounded-lg px-4 py-2">
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= isset($edit) && $edit['category_id'] == $c['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['name']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Product Name</label>
                        <input type="text" name="name" required placeholder="Product Name"
                            value="<?= $edit['name'] ?? '' ?>" class="w-full border rounded-lg px-4 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Price (dengan deskripsi)</label>
                        <input type="text" name="price" placeholder="diameter 14 cm 85k"
                            value="<?= $edit['price'] ?? '' ?>" class="w-full border rounded-lg px-4 py-2"
                            title="Format: diameter 14 cm 85k">
                        <p class="text-xs text-gray-500 mt-1">Contoh: diameter 14 cm 85k</p>
                    </div>
                </div>

                <!-- MULTIPLE IMAGES -->
                <div class="border-t pt-6">
                    <h3 class="font-semibold mb-4">📷 Product Photos</h3>

                    <!-- Existing Images (for edit mode) -->
                    <?php if (!empty($edit_images)): ?>
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Foto yang sudah ada:</p>
                            <div class="grid grid-cols-4 gap-2" id="existingImages">
                                <?php foreach ($edit_images as $img): ?>
                                    <div class="relative group sort-item" data-id="<?= $img['id'] ?>">
                                        <img src="../uploads/products/<?= htmlspecialchars($img['image_path']) ?>"
                                            class="w-full h-24 object-cover rounded-lg">
                                        <input type="number" name="image_sort[<?= $img['id'] ?>]"
                                            value="<?= $img['sort_order'] ?>"
                                            class="absolute bottom-1 left-1 w-12 text-xs p-1 rounded border" title="Sort Order">
                                        <a href="?delete_image=<?= $img['id'] ?>&product_id=<?= $edit['id'] ?>"
                                            onclick="return confirm('Hapus foto ini?')"
                                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition">
                                            ✕
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- New Images Upload -->
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Tambah foto baru:</p>
                        <div id="imageUploadContainer" class="space-y-2">
                            <div class="flex gap-2">
                                <input type="file" name="images[]" accept="image/*" class="border rounded-lg px-4 py-2">
                                <input type="number" name="sort_order[]" value="0"
                                    class="w-16 border rounded-lg px-2 py-2" title="Sort Order">
                            </div>
                        </div>
                        <button type="button" onclick="addImageField()"
                            class="mt-2 text-sm text-[#8B5E3C] hover:underline">
                            + Tambah Field Foto Lainnya
                        </button>
                    </div>
                </div>

                <!-- DESCRIPTIONS -->
                <div class="border-t pt-6">
                    <h3 class="font-semibold mb-4">📝 Descriptions</h3>

                    <!-- Existing Descriptions (for edit mode) -->
                    <?php if (!empty($edit_descriptions)): ?>
                        <div class="mb-4 space-y-2" id="existingDescriptions">
                            <?php foreach ($edit_descriptions as $desc): ?>
                                <div class="flex gap-2 items-start sort-item" data-id="<?= $desc['id'] ?>">
                                    <input type="number" name="desc_sort[<?= $desc['id'] ?>]" value="<?= $desc['sort_order'] ?>"
                                        class="w-16 border rounded-lg px-2 py-2 text-sm" title="Sort Order">
                                    <textarea name="existing_descriptions[<?= $desc['id'] ?>]" placeholder="Deskripsi..."
                                        class="flex-1 border rounded-lg px-4 py-2 <?= $desc['is_large_font'] ? 'text-lg font-semibold border-[#8B5E3C]' : '' ?>"
                                        rows="1"><?= htmlspecialchars($desc['description']) ?></textarea>
                                    <label class="flex items-center gap-1 text-sm whitespace-nowrap">
                                        <input type="checkbox" name="existing_is_large[<?= $desc['id'] ?>]"
                                            <?= $desc['is_large_font'] ? 'checked' : '' ?>>
                                        Font Besar
                                    </label>
                                    <a href="?delete_desc=<?= $desc['id'] ?>&product_id=<?= $edit['id'] ?>"
                                        onclick="return confirm('Hapus deskripsi ini?')"
                                        class="px-3 py-2 bg-red-500 text-white rounded-lg text-sm">
                                        Hapus
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- New Descriptions -->
                    <div id="descContainer" class="space-y-2">
                        <div class="flex gap-2 items-start">
                            <input type="number" name="desc_sort_order[]" value="0"
                                class="w-16 border rounded-lg px-2 py-2 text-sm" title="Sort Order">
                            <textarea name="descriptions[]" placeholder="Tambah deskripsi baru..."
                                class="flex-1 border rounded-lg px-4 py-2" rows="1"></textarea>
                            <label class="flex items-center gap-1 text-sm whitespace-nowrap">
                                <input type="checkbox" name="is_large_font[]">
                                Font Besar
                            </label>
                        </div>
                    </div>
                    <button type="button" onclick="addDescField()" class="mt-2 text-sm text-[#8B5E3C] hover:underline">
                        + Tambah Deskripsi Lainnya
                    </button>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="border-t pt-6 flex gap-4">
                    <button name="<?= $edit ? 'update' : 'add' ?>"
                        class="bg-[#F1E3C6] hover:opacity-90 px-6 py-3 rounded-lg font-semibold">
                        <?= $edit ? '💾 Update Product' : '➕ Add Product' ?>
                    </button>

                    <?php if ($edit): ?>
                        <a href="products.php" class="px-6 py-3 border rounded-lg hover:bg-gray-100">
                            Cancel
                        </a>
                        <button type="submit" name="update_sort" value="1"
                            class="bg-blue-100 hover:bg-blue-200 px-6 py-3 rounded-lg font-semibold">
                            🔄 Update Sort Order
                        </button>
                    <?php endif; ?>
                </div>

            </form>
        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#E8D8C4]">
                    <tr>
                        <th class="p-4 text-left">#</th>
                        <th class="p-4 text-left">Foto</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-left">Kategori</th>
                        <th class="p-4 text-left">Deskripsi & Harga</th>
                        <th class="p-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($products as $i => $p):
                        // Get images count
                        $img_count = $pdo->prepare("SELECT COUNT(*) FROM product_images WHERE product_id=?");
                        $img_count->execute([$p['id']]);
                        $count = $img_count->fetchColumn();

                        // Get first image
                        $first_img = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id=? ORDER BY sort_order ASC LIMIT 1");
                        $first_img->execute([$p['id']]);
                        $img = $first_img->fetch();
                        ?>
                        <tr class="border-t">
                            <td class="p-4"><?= $i + 1 ?></td>
                            <td class="p-4">
                                <?php if ($img): ?>
                                    <img src="../uploads/products/<?= htmlspecialchars($img['image_path']) ?>"
                                        class="w-16 h-16 object-cover rounded">
                                    <?php if ($count > 1): ?>
                                        <span class="text-xs text-gray-500">+<?= $count - 1 ?> foto</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-gray-400">
                                        No img
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <div class="font-semibold"><?= htmlspecialchars($p['name']) ?></div>
                            </td>
                            <td class="p-4 text-sm text-gray-600"><?= htmlspecialchars($p['category']) ?></td>
                            <td class="p-4">
                                <?php
                                $descs = $pdo->prepare("SELECT description, is_large_font FROM product_descriptions WHERE product_id=? ORDER BY sort_order ASC");
                                $descs->execute([$p['id']]);
                                $all_descs = $descs->fetchAll();
                                foreach ($all_descs as $d):
                                    ?>
                                    <p class="<?= $d['is_large_font'] ? 'text-lg font-semibold text-[#8B5E3C]' : 'text-sm' ?>">
                                        <?= htmlspecialchars($d['description']) ?>
                                    </p>
                                <?php endforeach; ?>
                                <?php if ($p['price']): ?>
                                    <p class="font-semibold text-[#8B5E3C] mt-1">
                                        <?= htmlspecialchars($p['price']) ?>
                                    </p>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 space-x-2">
                                <a href="?edit=<?= $p['id'] ?>"
                                    class="px-3 py-1 bg-yellow-400 rounded text-sm inline-block">
                                    Edit
                                </a>
                                <a href="?delete=<?= $p['id'] ?>" onclick="return confirm('Hapus produk ini?')"
                                    class="px-3 py-1 bg-red-500 text-white rounded text-sm inline-block">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>

                </tbody>
            </table>
        </div>

    </div>

    <script>
        // Add more image upload fields
        function addImageField() {
            const container = document.getElementById('imageUploadContainer');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="file" name="images[]" accept="image/*" class="border rounded-lg px-4 py-2">
                <input type="number" name="sort_order[]" value="99" class="w-16 border rounded-lg px-2 py-2" title="Sort Order">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg">
                    ✕
                </button>
            `;
            container.appendChild(div);
        }

        // Add more description fields
        function addDescField() {
            const container = document.getElementById('descContainer');
            const div = document.createElement('div');
            div.className = 'flex gap-2 items-start';
            div.innerHTML = `
                <input type="number" name="desc_sort_order[]" value="99" class="w-16 border rounded-lg px-2 py-2 text-sm" title="Sort Order">
                <textarea name="descriptions[]" placeholder="Tambah deskripsi baru..." class="flex-1 border rounded-lg px-4 py-2" rows="1"></textarea>
                <label class="flex items-center gap-1 text-sm whitespace-nowrap">
                    <input type="checkbox" name="is_large_font[]">
                    Font Besar
                </label>
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg">
                    ✕
                </button>
            `;
            container.appendChild(div);
        }
    </script>

</body>

</html>