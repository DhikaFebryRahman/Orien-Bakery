<?php session_start();
if (!isset($_SESSION['admin']))
    header('Location: login.php'); ?>
<?php include '../app/db.php'; ?>

<!doctype html>
<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-[#F5E8D8] min-h-screen">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6 text-[#3F2E1F]">Admin Dashboard</h1>

        <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-4">
            <a href="categories.php"
                class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition transform hover:-translate-y-1 block">
                <div class="text-4xl mb-2">📁</div>
                <h3 class="font-semibold text-[#3F2E1F]">Categories</h3>
                <p class="text-sm text-gray-500">Kelola kategori produk</p>
            </a>

            <a href="products.php"
                class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition transform hover:-translate-y-1 block">
                <div class="text-4xl mb-2">🍰</div>
                <h3 class="font-semibold text-[#3F2E1F]">Products</h3>
                <p class="text-sm text-gray-500">Kelola menu produk</p>
            </a>

            <a href="best-seller.php"
                class="bg-yellow-50 p-6 rounded-xl shadow hover:shadow-lg transition transform hover:-translate-y-1 block border-2 border-yellow-200">
                <div class="text-4xl mb-2">⭐</div>
                <h3 class="font-semibold text-[#8B5E3C]">Best Seller</h3>
                <p class="text-sm text-gray-500">Kelola produk best seller</p>
            </a>

            <a href="faq.php"
                class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition transform hover:-translate-y-1 block">
                <div class="text-4xl mb-2">❓</div>
                <h3 class="font-semibold text-[#3F2E1F]">FAQ</h3>
                <p class="text-sm text-gray-500">Kelola pertanyaan umum</p>
            </a>

            <a href="logout.php"
                class="bg-red-100 p-6 rounded-xl shadow hover:shadow-lg transition transform hover:-translate-y-1 block">
                <div class="text-4xl mb-2">🚪</div>
                <h3 class="font-semibold text-red-600">Logout</h3>
                <p class="text-sm text-gray-500">Keluar dari admin panel</p>
            </a>
        </div>
</body>

</html>