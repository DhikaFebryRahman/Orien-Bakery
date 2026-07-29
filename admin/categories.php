<?php
session_start();
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/heic_converter.php';

/* ======================
   PROTEKSI LOGIN
====================== */
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* ======================
   ADD CATEGORY
====================== */
if (isset($_POST['add'])) {
    $name = trim($_POST['name']);

    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $file = [
            'name' => $_FILES['image']['name'],
            'type' => $_FILES['image']['type'],
            'tmp_name' => $_FILES['image']['tmp_name'],
            'error' => $_FILES['image']['error'],
            'size' => $_FILES['image']['size']
        ];

        // Validasi file
        $validation = validateImageUpload($file);
        if (!$validation['valid']) {
            die('❌ ' . $validation['message']);
        }

        // Process upload dengan auto HEIC to PNG
        $result = processImageUpload($file, __DIR__ . '/../uploads/categories/', 'category');

        if ($result['success']) {
            $image = $result['filename'];
        } else {
            die('❌ Upload gambar gagal: ' . $result['message']);
        }
    }

    if ($name) {
        $stmt = $pdo->prepare("
            INSERT INTO categories (name, image)
            VALUES (?, ?)
        ");
        $stmt->execute([$name, $image]);

        header("Location: categories.php");
        exit;
    }
}

/* ======================
   UPDATE CATEGORY
====================== */
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $old_image = $_POST['old_image'];
    $image = $old_image;

    if (!empty($_FILES['image']['name'])) {
        $file = [
            'name' => $_FILES['image']['name'],
            'type' => $_FILES['image']['type'],
            'tmp_name' => $_FILES['image']['tmp_name'],
            'error' => $_FILES['image']['error'],
            'size' => $_FILES['image']['size']
        ];

        // Validasi file
        $validation = validateImageUpload($file);
        if (!$validation['valid']) {
            die('❌ ' . $validation['message']);
        }

        // Process upload dengan auto HEIC to PNG
        $result = processImageUpload($file, __DIR__ . '/../uploads/categories/', 'category_' . $id);

        if ($result['success']) {
            $image = $result['filename'];
        } else {
            die('❌ Upload gambar gagal: ' . $result['message']);
        }
    }

    if ($name) {
        $stmt = $pdo->prepare("
            UPDATE categories
            SET name=?, image=?
            WHERE id=?
        ");
        $stmt->execute([$name, $image, $id]);

        header("Location: categories.php");
        exit;
    }
}

/* ======================
   DELETE CATEGORY
====================== */
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");
    $stmt->execute([$_GET['delete']]);

    header("Location: categories.php");
    exit;
}

/* ======================
   AMBIL DATA
====================== */
$categories = $pdo->query(
    "SELECT * FROM categories ORDER BY id ASC"
)->fetchAll();

/* ======================
   EDIT MODE
====================== */
$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin - Categories</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --base: #F5E8D8;
            --primary: #F1E3C6;
            --soft: #E8D8C4;
            --text: #3F2E1F;
        }

        body {
            background: var(--base);
            color: var(--text);
        }
    </style>
</head>

<body>

    <div class="container mx-auto px-4 py-10 max-w-5xl">

        <h1 class="text-2xl font-bold mb-6">Manage Categories</h1>

        <div class="mb-4">
            <a href="dashboard.php" class="px-4 py-2 rounded-lg bg-[var(--soft)] hover:bg-[var(--primary)]">
                ← Kembali
            </a>
        </div>

        <!-- FORM -->
        <div class="bg-white p-6 rounded-xl shadow mb-8">
            <form method="POST" enctype="multipart/form-data" class="flex gap-4 items-center">
                <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
                <input type="hidden" name="old_image" value="<?= $editData['image'] ?? '' ?>">

                <input type="text" name="name" required placeholder="Category name"
                    value="<?= htmlspecialchars($editData['name'] ?? '') ?>" class="flex-1 border rounded-lg px-4 py-2">

                <input type="file" name="image" accept="image/*" class="border rounded-lg px-3 py-2">

                <button name="<?= $editData ? 'update' : 'add' ?>"
                    class="px-6 py-2 rounded-lg bg-[var(--primary)] font-semibold">
                    <?= $editData ? 'Update' : 'Add' ?>
                </button>

                <?php if ($editData): ?>
                    <a href="categories.php" class="px-4 py-2 border rounded-lg">Cancel</a>
                <?php endif; ?>
            </form>

            <?php if ($editData && $editData['image']): ?>
                <img src="../uploads/categories/<?= urlencode($editData['image']) ?>"
                    class="w-24 h-24 mt-4 object-cover rounded-lg border">
            <?php endif; ?>
        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-[var(--soft)]">
                    <tr>
                        <th class="p-4 text-left">#</th>
                        <th class="p-4 text-left">Image</th>
                        <th class="p-4 text-left">Category</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $i => $c): ?>
                        <tr class="border-t">
                            <td class="p-4"><?= $i + 1 ?></td>

                            <td class="p-4">
                                <?php if ($c['image']): ?>
                                    <img src="../uploads/categories/<?= urlencode($c['image']) ?>"
                                        class="w-14 h-14 object-cover rounded-lg border">
                                <?php else: ?>
                                    <span class="text-sm text-gray-400">No Image</span>
                                <?php endif; ?>
                            </td>

                            <td class="p-4 font-medium">
                                <?= htmlspecialchars($c['name']) ?>
                            </td>

                            <td class="p-4 text-center space-x-2">
                                <a href="?edit=<?= $c['id'] ?>" class="px-3 py-1 bg-yellow-400 rounded text-sm">
                                    Edit
                                </a>
                                <a href="?delete=<?= $c['id'] ?>" onclick="return confirm('Hapus kategori ini?')"
                                    class="px-3 py-1 bg-red-500 text-white rounded text-sm">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>