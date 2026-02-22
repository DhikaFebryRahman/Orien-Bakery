<?php
session_start();
require_once __DIR__ . '/../app/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* ======================
   TOGGLE BEST SELLER
====================== */
if (isset($_GET['toggle'])) {
    $id = (int) $_GET['toggle'];
    
    // Check if column exists and get current status
    try {
        $stmt = $pdo->prepare("SELECT best_sell FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $current = $stmt->fetchColumn();
        
        // Handle case where best_sell might be NULL or not set
        $new_status = ($current == 1) ? 0 : 1;
        
        $update = $pdo->prepare("UPDATE products SET best_sell = ? WHERE id = ?");
        $update->execute([$new_status, $id]);
    } catch (PDOException $e) {
        // If column doesn't exist, add it
        try {
            $pdo->exec("ALTER TABLE products ADD COLUMN best_sell TINYINT(1) DEFAULT 0");
            // Set the product as best seller
            $update = $pdo->prepare("UPDATE products SET best_sell = 1 WHERE id = ?");
            $update->execute([$id]);
        } catch (PDOException $e2) {
            // Table might not exist or other error
            error_log("Error managing best_seller: " . $e2->getMessage());
        }
    }

    header("Location: best-seller.php");
    exit;
}

/* ======================
   FETCH DATA
====================== */
$products = $pdo->query("
    SELECT products.*, categories.name AS category
    FROM products
    JOIN categories ON products.category_id = categories.id
    ORDER BY products.best_sell DESC, products.id DESC
")->fetchAll();

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin - Best Seller</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-[#F5E8D7] text-[#3F2E1F]">

    <div class="container mx-auto px-4 py-10 max-w-6xl">

        <h1 class="text-2xl font-bold mb-6">🏆 Manage Best Seller</h1>
        <div class="mb-4 flex gap-4">
            <a href="dashboard.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
              bg-[#E8D8C4] hover:bg-[#F1E3C6] font-medium transition">
                ← Kembali ke Dashboard
            </a>
        </div>

        <!-- INFO -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-blue-800">
                💡 <strong>Cara Menggunakan:</strong> Klik tombol "⭐ Jadikan Best Seller" atau "❌ Hapus Best Seller"
                untuk mengubah status produk. Produk Best Seller akan tampil di halaman utama.
            </p>
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
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($products as $i => $p):
                        // Get first image
                        $img_stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY sort_order ASC LIMIT 1");
                        $img_stmt->execute([$p['id']]);
                        $img = $img_stmt->fetch();

                        // Get description
                        $desc_stmt = $pdo->prepare("SELECT description FROM product_descriptions WHERE product_id = ? ORDER BY sort_order ASC LIMIT 1");
                        $desc_stmt->execute([$p['id']]);
                        $desc = $desc_stmt->fetch();
                        ?>
                        <tr class="border-t <?= $p['best_sell'] ? 'bg-yellow-50' : '' ?>">
                            <td class="p-4"><?= $i + 1 ?></td>
                            <td class="p-4">
                                <?php if ($img): ?>
                                    <img src="../uploads/products/<?= htmlspecialchars($img['image_path']) ?>"
                                        class="w-16 h-16 object-cover rounded">
                                <?php else: ?>
                                    <div
                                        class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs">
                                        No img
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <div class="font-semibold"><?= htmlspecialchars($p['name']) ?></div>
                                <?php if ($desc): ?>
                                    <div class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($desc['description']) ?></div>
                                <?php endif; ?>
                                <?php if ($p['price']): ?>
                                    <div class="text-[#8B5E3C] font-semibold mt-1"><?= htmlspecialchars($p['price']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-sm text-gray-600"><?= htmlspecialchars($p['category']) ?></td>
                            <td class="p-4">
                                <?php if ($p['best_sell']): ?>
                                    <span
                                        class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-400 text-yellow-900 rounded-full text-sm font-medium">
                                        ⭐ Best Seller
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="inline-flex items-center gap-1 px-3 py-1 bg-gray-200 text-gray-600 rounded-full text-sm">
                                        Normal
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <?php if ($p['best_sell']): ?>
                                    <a href="?toggle=<?= $p['id'] ?>" onclick="return confirm('Hapus status Best Seller?')"
                                        class="px-3 py-1 bg-red-100 text-red-600 rounded-lg text-sm inline-block hover:bg-red-200 transition">
                                        ❌ Hapus Best Seller
                                    </a>
                                <?php else: ?>
                                    <a href="?toggle=<?= $p['id'] ?>" onclick="return confirm('Jadikan Best Seller?')"
                                        class="px-3 py-1 bg-yellow-400 text-yellow-900 rounded-lg text-sm inline-block hover:bg-yellow-500 transition">
                                        ⭐ Jadikan Best Seller
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>

                </tbody>
            </table>
        </div>

</body>

</html>