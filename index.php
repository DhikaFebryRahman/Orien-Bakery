<?php require 'app/db.php'; ?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arien Bakery</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Great+Vibes&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: #8B5E3C;
            --hover: #DBAD7F;
            --cream: #F1E3C6;
            --bg: #FFF7ED;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        .font-greatvibes {
            font-family: 'Great Vibes', cursive;
        }

        .hero-section {
            background: url(assets/images/bg/image.webp) center/cover;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>

<body class="bg-[#FDEFD7]">

    <?php include 'partials/navbar.php'; ?>

    <!-- HERO -->
    <section class="relative min-h-screen flex items-end pb-8 md:pb-20 overflow-hidden">

        <img src="assets/images/bg/image.webp" alt="Handmade Cake Arien Bakery" width="1920" height="1080"
            class="absolute inset-0 w-full h-full object-cover" fetchpriority="high">

        <div class="absolute inset-0"></div>

        <div class="relative container mx-auto px-4 md:px-10">
            <div class="max-w-xl mb-16 md:mb-10">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 text-white drop-shadow-lg">
                    Handmade Cake<br>With Love
                </h1>
                <p class="mb-6 text-white drop-shadow-lg text-base md:text-lg">
                    Premium homemade cake by Arien Bakery
                </p>
                <a href="menu.php"
                    class="inline-block px-6 py-3 md:px-8 md:py-4 rounded-full font-medium text-[#3F2E1F] bg-[#F1E3C6] hover:opacity-90 transition shadow-lg">
                    Lihat Menu
                </a>
            </div>
        </div>

    </section>


    <!-- CATEGORY - KLIK MENUJU HALAMAN KATEGORI -->
    <section class="container mx-auto mt-16 px-4 md:px-10">
        <div class="text-center mb-10">
            <h2 class="font-playfair text-3xl md:text-4xl text-[#3F2E1F]">
                Our Cake Categories
            </h2>
            <p class="mt-2 text-sm md:text-base text-[#6B4A2E]">
                Discover our selection of handcrafted cakes
            </p>
        </div>

        <!-- Flexbox dengan justify-center agar item terpusat -->
        <div class="flex flex-wrap justify-center gap-8">
            <?php
            $cats = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
            foreach ($cats as $c):
                $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
                $count_stmt->execute([$c['id']]);
                $product_count = $count_stmt->fetchColumn();
            ?>
                <a href="menu.php?category=<?= $c['id'] ?>"
                    class="w-full sm:w-1/2 lg:w-1/3 max-w-sm bg-[#B27C57] rounded-lg shadow-lg overflow-hidden hover:scale-105 transition transform group">
                    <div class="relative overflow-hidden">
                        <img src="uploads/categories/<?= htmlspecialchars($c['image']); ?>"
                            alt="<?= htmlspecialchars($c['name']); ?>"
                            class="w-full h-64 object-cover group-hover:scale-110 transition duration-500" loading="lazy">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition"></div>
                    </div>
                    <div class="py-4 px-6">
                        <p class="text-center text-[#3F2E1F] text-lg font-medium">
                            <?= htmlspecialchars($c['name']); ?>
                        </p>
                        <p class="text-center text-sm text-[#3F2E1F]/70 mt-1">
                            <?= $product_count ?> produk
                        </p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- BEST SELLER -->
    <?php
    $best = $pdo->query("SELECT * FROM products WHERE best_sell = 1 ORDER BY id DESC LIMIT 4");
    $best_products = $best->fetchAll();
    ?>
    <?php if (!empty($best_products)): ?>
        <section class="bg-[#7F5535] container mx-auto px-4 md:px-10 mt-20 py-12">
            <div class="text-center mb-10">
                <h3 class="font-playfair text-3xl text-white mb-2">Best Seller</h3>
                <p class="text-white/70">Pilihan terbaik dari Arien Bakery</p>
            </div>

            <!-- Carousel wrapper dengan scroll -->
            <div class="relative">
                <!-- Container scroll horizontal -->
                <div class="flex overflow-x-auto gap-4 md:gap-6 pb-4 scrollbar-hide snap-x snap-mandatory"
                    style="scrollbar-width: none; -ms-overflow-style: none;">
                    <?php foreach ($best_products as $p):
                        $img_stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY sort_order ASC LIMIT 1");
                        $img_stmt->execute([$p['id']]);
                        $img = $img_stmt->fetch();
                    ?>
                        <!-- Item carousel dengan lebar responsif -->
                        <a href="product.php?id=<?= $p['id']; ?>"
                            class="flex-shrink-0 w-1/2 sm:w-1/3 md:w-1/4 snap-start block rounded-xl overflow-hidden transition hover:scale-105 transform duration-300">
                            <div class="aspect-square overflow-hidden relative bg-[#6B4A2E]">
                                <?php if ($img): ?>
                                    <img src="uploads/products/<?= htmlspecialchars($img['image_path']) ?>"
                                        alt="<?= htmlspecialchars($p['name']) ?>"
                                        class="w-full h-full object-cover hover:scale-105 transition duration-300" loading="lazy">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/400x400?text=No+Image"
                                        alt="<?= htmlspecialchars($p['name']) ?>" class="w-full h-full object-cover" loading="lazy">
                                <?php endif; ?>
                            </div>
                            <div class="p-4">
                                <h4 class="tracking-wider text-center text-white"><?= htmlspecialchars($p['name']); ?></h4>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Indikator scroll (opsional) -->
                <div class="flex justify-center gap-2 mt-4 md:hidden">
                    <span class="w-2 h-2 bg-white/50 rounded-full"></span>
                    <span class="w-2 h-2 bg-white/50 rounded-full"></span>
                    <span class="w-2 h-2 bg-white/50 rounded-full"></span>
                    <span class="w-2 h-2 bg-white/50 rounded-full"></span>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- FLOATING WA (nomor bisa diambil dari database jika ada) -->
    <?php
    $contact = $pdo->query("SELECT * FROM contacts LIMIT 1")->fetch();
    $wa = $contact['whatsapp'] ?? '628563559528';
    ?>
    <a href="https://api.whatsapp.com/send/?phone=<?= $wa ?>&text&type=phone_number&app_absent=0&wame_ctl=1"
        target="_blank"
        class="fixed bottom-6 right-6 w-14 h-14 bg-green-500 text-white rounded-full flex items-center justify-center shadow-xl hover:bg-green-600 hover:scale-110 transition transform z-50">
        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
        </svg>
    </a>

    <?php include 'partials/footer.php'; ?>

</body>

</html>