<?php require 'app/db.php';

// Ambil product ID dari URL
$product_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ambil data produk
$stmt = $pdo->prepare("
    SELECT p.*, c.name AS category_name 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    WHERE p.id = ?
");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

// Jika produk tidak ditemukan
if (!$product) {
    echo "<script>alert('Produk tidak ditemukan'); window.location.href='index.php';</script>";
    exit;
}

// Ambil foto produk (multiple)
$images_stmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order ASC");
$images_stmt->execute([$product_id]);
$product_images = $images_stmt->fetchAll();

// Ambil deskripsi produk
$descs_stmt = $pdo->prepare("SELECT * FROM product_descriptions WHERE product_id = ? ORDER BY sort_order ASC");
$descs_stmt->execute([$product_id]);
$product_descriptions = $descs_stmt->fetchAll();

// Ambil contact untuk WhatsApp
$contact = $pdo->query("SELECT * FROM contacts LIMIT 1")->fetch();
$wa = $contact['whatsapp'] ?? '62XXXXXXXXXX';

// Siapkan pesan WhatsApp
$wa_message = urlencode(
    "Halo admin, saya ingin memesan:\n" .
    "Produk: {$product['name']}\n" .
    "Kategori: {$product['category_name']}\n" .
    (($product['price']) ? "Harga: {$product['price']}" : "")
);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Arien Bakery</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@600;700&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@600;700&family=Great+Vibes&display=swap"
        rel="stylesheet">

    <style>
        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        .font-greatvibes {
            font-family: 'Great Vibes', cursive;
        }

        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }

        /* Carousel Styles */
        .carousel-container {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
        }

        .carousel-wrapper {
            position: relative;
        }

        .carousel-wrapper:hover .carousel-btn,
        .carousel-wrapper:focus-within .carousel-btn {
            opacity: 1;
        }

        /* Untuk mobile, tombol tetap terlihat agar mudah diakses */
        @media (max-width: 768px) {
            .carousel-btn {
                opacity: 1 !important;
                width: 36px;
                height: 36px;
                font-size: 16px;
            }
        }

        .carousel-track {
            display: flex;
            transition: transform 0.4s ease-in-out;
            touch-action: pan-y;
        }

        .carousel-slide {
            min-width: 100%;
            flex-shrink: 0;
        }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 10;
            border: none;
            font-size: 18px;
            color: #3F2E1F;
            line-height: 1;
            padding-bottom: 3px;
        }

        .carousel-btn:hover {
            background: white;
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-btn.prev {
            left: 10px;
        }

        .carousel-btn.next {
            right: 10px;
        }

        .carousel-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 12px;
        }

        .carousel-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ccc;
            cursor: pointer;
            transition: all 0.3s;
        }

        .carousel-dot.active {
            background: #8B5E3C;
            transform: scale(1.2);
        }

        .description-text {
            white-space: pre-line;
            line-height: 1.1;
        }

        .description-large {
            font-size: 1.125rem;
            font-weight: 600;
            color: #8B5E3C;
        }
    </style>
</head>

<body class="bg-[#FDEFD7] font-poppins">

    <?php include 'partials/navbar.php'; ?>

    <!-- Breadcrumb -->
    <section class="pt-24 pb-4 px-4">
        <div class="container mx-auto max-w-6xl">
            <nav class="text-sm text-gray-600">
                <a href="index.php" class="hover:text-[#8B5E3C]">Beranda</a>
                <span class="mx-2">/</span>
                <a href="menu.php" class="hover:text-[#8B5E3C]">Menu</a>
                <span class="mx-2">/</span>
                <span class="text-[#8B5E3C]"><?= htmlspecialchars($product['name']) ?></span>
            </nav>
        </div>
    </section>

    <!-- Product Detail Section -->
    <section class="pb-16 px-4">
        <div class="container mx-auto max-w-6xl">

            <!-- Grid: 1 kolom di mobile, 2 kolom di desktop -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- PHOTO CAROUSEL SECTION -->
                <div class="carousel-wrapper">
                    <div class="carousel-container relative bg-white shadow-xl">
                        <div class="carousel-track" id="carouselTrack">
                            <?php if (!empty($product_images)): ?>
                                <?php foreach ($product_images as $index => $img): ?>
                                    <div class="carousel-slide">
                                        <img src="uploads/products/<?= htmlspecialchars($img['image_path']) ?>"
                                            alt="<?= htmlspecialchars($product['name']) ?> - Foto <?= $index + 1 ?>"
                                            class="w-full h-64 sm:h-80 md:h-96 object-cover"
                                            onerror="this.src='https://via.placeholder.com/600x600?text=No+Image'">
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="carousel-slide">
                                    <img src="https://via.placeholder.com/600x600?text=No+Image"
                                        alt="<?= htmlspecialchars($product['name']) ?>"
                                        class="w-full h-64 sm:h-80 md:h-96 object-cover">
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Navigation Buttons -->
                        <?php if (count($product_images) > 1): ?>
                            <button class="carousel-btn prev" onclick="moveSlide(-1)">←</button>
                            <button class="carousel-btn next" onclick="moveSlide(1)">→</button>
                        <?php endif; ?>
                    </div>

                    <!-- Dots Indicator -->
                    <?php if (count($product_images) > 1): ?>
                        <div class="carousel-dots" id="carouselDots"></div>
                    <?php endif; ?>
                </div>

                <!-- PRODUCT INFO & DESCRIPTION -->
                <div class="product-info">

                    <!-- Category Badge -->
                    <span
                        class="inline-block bg-[#DBAD7F] text-[#3F2E1F] px-4 py-1 rounded-full text-sm font-medium mb-4">
                        <?= htmlspecialchars($product['category_name']) ?>
                    </span>

                    <!-- Best Seller Badge -->
                    <?php if ($product['best_sell']): ?>
                        <span
                            class="inline-block bg-[#8B5E3C] text-white px-4 py-1 rounded-full text-sm font-medium mb-4 ml-2">
                            ⭐ Best Seller
                        </span>
                    <?php endif; ?>

                    <!-- Product Name -->
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-playfair font-bold text-[#3F2E1F] mb-2">
                        <?= htmlspecialchars($product['name']) ?>
                    </h1>

                    <!-- Subtitle Style -->
                    <p class="font-greatvibes text-xl sm:text-2xl text-[#8B5E3C] mb-3">
                        Kue Lezat dari Arien Bakery
                    </p>

                    <!-- DESCRIPTIONS & PRICE -->
                    <div class="space-y-1 mb-5">
                        <?php if (!empty($product_descriptions)): ?>
                            <?php foreach ($product_descriptions as $desc): ?>
                                <p
                                    class="description-text <?= $desc['is_large_font'] ? 'description-large' : 'text-[#7b5839]' ?>">
                                    <?= htmlspecialchars($desc['description']) ?>
                                </p>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Price (jika ada) -->
                        <?php if ($product['price']): ?>
                            <p class="description-text description-large">
                                <?= htmlspecialchars($product['price']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Order Button -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="https://api.whatsapp.com/send/?phone=<?= $wa ?>&text=<?= $wa_message ?>"
                            target="_blank"
                            class="flex-1 bg-[#8B5E3C] text-white text-center py-4 rounded-xl font-semibold hover:bg-[#6B4A2E] transition shadow-lg">
                            Pesan via WhatsApp
                        </a>
                        <a href="menu.php"
                            class="px-6 py-4 border-2 border-[#8B5E3C] text-[#8B5E3C] rounded-xl hover:bg-[#8B5E3C] hover:text-white transition text-center">
                            ← Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products (Same Category) -->
    <?php
    $related = $pdo->prepare("
        SELECT p.* FROM products p 
        WHERE p.category_id = ? AND p.id != ? 
        ORDER BY p.id DESC LIMIT 4
    ");
    $related->execute([$product['category_id'], $product['id']]);
    $relatedProducts = $related->fetchAll();

    if (!empty($relatedProducts)):
        ?>
        <section class="pb-16 px-4">
            <div class="container mx-auto max-w-6xl">
                <h2 class="font-playfair text-xl sm:text-2xl font-bold text-[#3F2E1F] mb-6">
                    Produk Lainnya di Kategori Ini
                </h2>
                <!-- Grid: 2 kolom mobile, 4 kolom desktop -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php foreach ($relatedProducts as $r):
                        // Get first image
                        $first_img = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY sort_order ASC LIMIT 1");
                        $first_img->execute([$r['id']]);
                        $r_img = $first_img->fetch();
                        ?>
                        <a href="product.php?id=<?= $r['id'] ?>"
                            class="bg-white rounded-xl shadow overflow-hidden hover:shadow-lg transition block">
                            <div class="aspect-square bg-gray-100 overflow-hidden">
                                <?php if ($r_img): ?>
                                    <img src="uploads/products/<?= htmlspecialchars($r_img['image_path']) ?>"
                                        alt="<?= htmlspecialchars($r['name']) ?>"
                                        class="w-full h-full object-cover hover:scale-105 transition duration-300"
                                        onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/300x300?text=No+Image"
                                        alt="<?= htmlspecialchars($r['name']) ?>" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            <div class="p-3">
                                <h3 class="font-semibold text-sm mb-1"><?= htmlspecialchars($r['name']) ?></h3>
                                <?php if ($r['price']): ?>
                                    <p class="text-[#8B5E3C] font-semibold text-sm"><?= htmlspecialchars($r['price']) ?></p>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Floating WhatsApp Button (menggunakan nomor dari database) -->
    <a href="https://api.whatsapp.com/send/?phone=<?= $wa ?>&text=<?= $wa_message ?>" target="_blank"
        class="fixed bottom-6 right-6 w-12 h-12 sm:w-14 sm:h-14 bg-green-500 text-white rounded-full flex items-center justify-center shadow-xl hover:bg-green-600 hover:scale-110 transition transform z-50">
        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
        </svg>
    </a>

    <?php include 'partials/footer.php'; ?>

    <!-- JavaScript untuk Carousel -->
    <script>
        let currentSlide = 0;
        const track = document.getElementById('carouselTrack');
        const slides = track ? track.querySelectorAll('.carousel-slide') : [];
        const totalSlides = slides.length;
        const dotsContainer = document.getElementById('carouselDots');
        let startX = 0;
        let isDragging = false;

        if (totalSlides > 1 && dotsContainer) {
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('div');
                dot.className = `carousel-dot ${i === 0 ? 'active' : ''}`;
                dot.onclick = () => goToSlide(i);
                dotsContainer.appendChild(dot);
            }
        }

        function moveSlide(direction) {
            if (totalSlides <= 1) return;
            currentSlide += direction;
            if (currentSlide < 0) currentSlide = totalSlides - 1;
            if (currentSlide >= totalSlides) currentSlide = 0;
            updateCarousel();
        }

        function goToSlide(index) {
            if (totalSlides <= 1) return;
            currentSlide = index;
            updateCarousel();
        }

        function updateCarousel() {
            if (!track) return;
            track.style.transform = `translateX(-${currentSlide * 100}%)`;
            document.querySelectorAll('.carousel-dot').forEach((dot, i) => {
                dot.classList.toggle('active', i === currentSlide);
            });
        }

        if (track) {
            track.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                isDragging = true;
            });

            track.addEventListener('touchmove', (e) => {
                if (!isDragging || totalSlides <= 1) return;
                const currentX = e.touches[0].clientX;
                const diff = startX - currentX;
                if (Math.abs(diff) > 50) {
                    if (diff > 0) moveSlide(1);
                    else moveSlide(-1);
                    isDragging = false;
                }
            });

            track.addEventListener('touchend', () => isDragging = false);
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') moveSlide(-1);
            if (e.key === 'ArrowRight') moveSlide(1);
        });
    </script>

</body>

</html>