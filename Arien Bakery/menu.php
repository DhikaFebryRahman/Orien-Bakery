<?php
require_once __DIR__ . '/app/db.php';

// Get category filter from URL
$selected_category = isset($_GET['category']) ? (int) $_GET['category'] : 0;

// ambil semua kategori
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

// Build query with category filter
$query = "
    SELECT p.*, c.name AS category_name
    FROM products p
    JOIN categories c ON p.category_id = c.id
";
if ($selected_category) {
    $query .= " WHERE p.category_id = " . $selected_category;
}
$query .= " ORDER BY c.id ASC, p.id ASC";

$stmt = $pdo->query($query);
$products = $stmt->fetchAll();

// kelompokkan produk per kategori
$groupedProducts = [];
foreach ($products as $p) {
    $groupedProducts[$p['category_id']][] = $p;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Menu – Arien Bakery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@600;700&family=Great+Vibes&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --base: #F5E8D8;
            --primary: #F1E3C6;
            --soft: #E8D8C4;
            --text: #3F2E1F;
            --accent: #8B5E3C;

            --font-playfair: 'Playfair Display', serif;
            --font-greatvibes: 'Great Vibes', cursive;
            --font-poppins: 'Poppins', sans-serif;
        }

        body {
            background: var(--base);
            color: var(--text);
            font-family: 'Poppins', sans-serif;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        .font-greatvibes {
            font-family: 'Great Vibes', cursive;
        }

        .product-carousel {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            margin: 0 auto;
            max-width: 100%;
        }

        .product-carousel-track {
            display: flex;
            transition: transform 0.3s ease;
            touch-action: pan-y;
        }

        .product-carousel-slide {
            flex: 0 0 100%;
        }

        .product-carousel-slide img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
        }

        .product-carousel-btn {
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
        }

        .product-carousel:hover .product-carousel-btn,
        .product-carousel:focus-within .product-carousel-btn {
            opacity: 1;
        }

        .product-carousel-btn:hover {
            background: white;
            transform: translateY(-50%) scale(1.1);
        }

        .product-carousel-btn.prev {
            left: 10px;
        }

        .product-carousel-btn.next {
            right: 10px;
        }

        .product-carousel-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 12px;
            margin-bottom: 2px;
        }

        .product-carousel-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ccc;
            cursor: pointer;
            transition: all 0.3s;
        }

        .product-carousel-dot.active {
            background: var(--accent);
            transform: scale(1.2);
        }

        .description-text {
            white-space: pre-line;
            line-height: 1.4;
            font-size: 1rem;
            margin-bottom: 0.5rem;
            padding: 2px 0;
        }

        .category-badge {
            display: inline-block;
            padding: 6px 16px;
            background: var(--accent);
            color: white;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .bestseller-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            background: #fbbf24;
            color: #92400e;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Product card layout */
        .product-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease;
            border: 1px solid rgba(139, 94, 60, 0.15);
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
            text-align: center;
        }

        .product-info p {
            margin: 2px 0 !important;
            line-height: 1 !important;
        }

        .product-content {
            flex: 1;
        }

        /* NEW: Container untuk memusatkan card */
        .products-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
            width: 100%;
        }

        .products-container-centered {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .product-card-wrapper {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        /* Lebar lebih lebar untuk tampilan kategori (satu card per baris) */
        .product-card-wrapper--wide {
            width: 100%;
            max-width: 550px;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .product-card-wrapper {
                width: calc(50% - 1rem);
                max-width: 450px;
            }
        }

        @media (min-width: 1024px) {
            .product-card-wrapper {
                width: calc(33.333% - 1.5rem);
                max-width: 400px;
            }
        }

        /* Jika ingin layout grid dengan card di tengah */
        .products-grid-centered {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            justify-items: center;
            /* Ini yang membuat card di tengah */
            width: 100%;
        }

        .products-grid-centered .product-card {
            width: 100%;
            max-width: 400px;
        }

        .description-text-large {
            white-space: pre-line;
            line-height: 1.4;
            font-size: 1.225rem;
            font-weight: 600;
            color: #7b5839;
            font-family: 'poppins', sans-serif;
            padding-left: 12px;
            margin: 2px 0;
            margin-top: 4px;
        }
    </style>
</head>

<body>

    <?php include 'partials/navbar.php'; ?>

    <!-- HEADER -->
    <section class="pt-24 pb-5 text-center">
        <h1 class="text-4xl font-playfair font-bold mb-2 text-[#3F2E1F]">Our Menu</h1>
        <p class="text-gray-600 -mb-8">Pilih menu favorit Anda dari Arien Bakery</p>

        <?php if ($selected_category): ?>
            <?php
            $cat_stmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
            $cat_stmt->execute([$selected_category]);
            $cat_name = $cat_stmt->fetchColumn();
            ?>
            <div class="mt-12">
                <span class="category-badge">📁 <?= htmlspecialchars($cat_name) ?></span>
                <a href="menu.php" class="ml-2 text-sm text-[#8B5E3C] hover:underline ">Lihat Semua</a>
            </div>
        <?php endif; ?>
    </section>

    <!-- MENU LIST -->
    <div class="container mx-auto px-4 pb-20 max-w-7xl">
        <div class="flex items-center justify-between mb-8 pb-4"></div>
        <!-- FILTER CATEGORIES -->
        <?php if (!$selected_category): ?>
            <div class="mb-8 flex flex-wrap gap-3 justify-center">
                <a href="menu.php"
                    class="px-4 py-2 rounded-full bg-[#8B5E3C] text-white font-medium hover:bg-[#6B4A2E] transition duration-300">
                    Semua
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="?category=<?= $cat['id'] ?>"
                        class="px-4 py-2 rounded-full bg-gray-200 text-gray-700 font-medium hover:bg-[#7b5839] transition duration-300 hover:text-white">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($selected_category && !empty($groupedProducts[$selected_category])): ?>
            <div class="mb-12">
                <!-- BORDER DIATAS TEKS CATEGORY NAME -->
                <div class="border-t-2 border-[#E8D8C4] -mt-8"></div>
                <h2 class="text-3xl font-playfair font-bold mt-8 mb-8 text-center text-[#3F2E1F]">
                    <?= htmlspecialchars($cat_name) ?>
                </h2>

                <!-- TAMPILAN KATEGORI: Satu Card Per Baris, Pusat dan Berderet Ke Bawah -->
                <div class="flex flex-col items-center gap-12">
                    <?php foreach ($groupedProducts[$selected_category] as $p): ?>
                        <?php                        $img_stmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order ASC");
                        $img_stmt->execute([$p['id']]);
                        $images = $img_stmt->fetchAll();
                        $desc_stmt = $pdo->prepare("SELECT description, is_large_font FROM product_descriptions WHERE product_id = ? ORDER BY sort_order ASC");
                        $desc_stmt->execute([$p['id']]);
                        $descs = $desc_stmt->fetchAll();    
                        $product_id = 'carousel_' . $p['id'];
                        ?>

                        <div class="product-card-wrapper--wide">
                            <!-- Product Card -->
                            <div class="product-card">
                                <!-- Foto dengan Carousel -->
                                <div class="p-4 bg-transparent">
                                    <div class="product-carousel" id="<?= $product_id ?>">
                                        <div class="product-carousel-track" id="<?= $product_id ?>_track">
                                            <?php if (!empty($images)): ?>
                                                <?php foreach ($images as $idx => $img): ?>
                                                    <div class="product-carousel-slide">
                                                        <img src="uploads/products/<?= htmlspecialchars($img['image_path']) ?>"
                                                            alt="<?= htmlspecialchars($p['name']) ?>">
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="product-carousel-slide">
                                                    <img src="https://via.placeholder.com/500x350?text=No+Image"
                                                        alt="<?= htmlspecialchars($p['name']) ?>">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (count($images) > 1): ?>
                                            <button class="product-carousel-btn prev"
                                                onclick="moveCarousel('<?= $product_id ?>', -1)">←</button>
                                            <button class="product-carousel-btn next"
                                                onclick="moveCarousel('<?= $product_id ?>', 1)">→</button>
                                            <div class="product-carousel-dots" id="<?= $product_id ?>_dots"></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="product-info">
                                    <div class="product-content">
                                        <div class="flex justify-center items-center text-center gap-2 mb-3 flex-wrap">
                                            <span class="category-badge"><?= htmlspecialchars($cat_name) ?></span>
                                            <?php if ($p['best_sell']): ?>
                                                <span class="bestseller-badge">⭐ Best Seller</span>
                                            <?php endif; ?>
                                        </div>
                                        <h3 class="text-2xl font-playfair font-bold text-[#3F2E1F] mb-2">
                                            <?= htmlspecialchars($p['name']) ?>
                                        </h3>

                                        <?php if (!empty($descs)): ?>
                                            <?php for ($i = 0; $i < min(10, count($descs)); $i++): ?>
                                                <?php $is_large = $descs[$i]['is_large_font'] ?? 0; ?>
                                                <p
                                                    class="<?= $is_large ? 'description-text-large' : 'description-text text-[#7b5839]' ?>">
                                                    <?= htmlspecialchars($descs[$i]['description']) ?>
                                                </p>
                                            <?php endfor; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mt-3 pt-4">
                                        <a href="product.php?id=<?= $p['id']; ?>" target="_self"
                                            class="inline-flex items-center justify-center gap-2 py-3 rounded-xl text-[#7b5839] hover:text-[#a67d5b] transition duration-300">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- End Product Card -->
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
            <?php include 'partials/special-request.php'; ?>

        <?php else: ?>
            <!-- TAMPIL SEMUA KATEGORI -->
            <?php foreach ($categories as $cat): ?>
                <?php if (!empty($groupedProducts[$cat['id']])): ?>
                    <div class="mb-16">
                        <div class="flex items-center justify-between mb-8 pb-4 border-b-2 border-[#E8D8C4]">
                            <div>
                                <h2 class="text-2xl font-playfair font-bold text-[#3F2E1F]">
                                    <?= htmlspecialchars($cat['name']) ?>
                                </h2>
                                <p class="text-sm text-gray-500 mt-1"><?= count($groupedProducts[$cat['id']]) ?>
                                    produk</p>
                            </div>
                            <a href="menu.php?category=<?= $cat['id'] ?>"
                                class="text-[#8B5E3C] hover:text-[#6B4A2E] font-medium text-sm flex items-center gap-1">
                                Lihat Semua →
                            </a>
                        </div>

                        <!-- Container dengan card di tengah -->
                        <div class="products-grid-centered">
                            <?php foreach ($groupedProducts[$cat['id']] as $p): ?>
                                <?php
                                $img_stmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order ASC");
                                $img_stmt->execute([$p['id']]);
                                $images = $img_stmt->fetchAll();
                                $desc_stmt = $pdo->prepare("SELECT description, is_large_font FROM product_descriptions WHERE product_id = ? ORDER BY sort_order ASC");
                                $desc_stmt->execute([$p['id']]);
                                $descs = $desc_stmt->fetchAll();
                                $product_id = 'carousel_' . $p['id'];
                                ?>

                                <!-- Product Card -->
                                <div class="product-card">
                                    <!-- Foto dengan Carousel -->
                                    <div class="p-4 bg-transparent">
                                        <div class="product-carousel" id="<?= $product_id ?>">
                                            <div class="product-carousel-track" id="<?= $product_id ?>_track">
                                                <?php if (!empty($images)): ?>
                                                    <?php foreach ($images as $idx => $img): ?>
                                                        <div class="product-carousel-slide">
                                                            <img src="uploads/products/<?= htmlspecialchars($img['image_path']) ?>"
                                                                alt="<?= htmlspecialchars($p['name']) ?>">
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="product-carousel-slide">
                                                        <img src="https://via.placeholder.com/500x350?text=No+Image"
                                                            alt="<?= htmlspecialchars($p['name']) ?>">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if (count($images) > 1): ?>
                                                <button class="product-carousel-btn prev"
                                                    onclick="moveCarousel('<?= $product_id ?>', -1)">←</button>
                                                <button class="product-carousel-btn next"
                                                    onclick="moveCarousel('<?= $product_id ?>', 1)">→</button>
                                                <div class="product-carousel-dots" id="<?= $product_id ?>_dots"></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="product-info text-center">
                                        <div class="product-content">
                                            <?php if ($p['best_sell']): ?>
                                                <div class="mb-3">
                                                    <span class="bestseller-badge">⭐ Best Seller</span>
                                                </div>
                                            <?php endif; ?>
                                            <h3 class="text-2xl font-playfair font-bold text-[#3F2E1F] -mb-3">
                                                <?= htmlspecialchars($p['name']) ?>
                                            </h3>

                                            <!-- Tampilkan hingga 3 deskripsi (jika ada) -->
                                            <?php if (!empty($descs)): ?>
                                                <?php for ($i = 0; $i < min(3, count($descs)); $i++): ?>
                                                    <?php $is_large = $descs[$i]['is_large_font'] ?? 0; ?>
                                                    <p class="<?= $is_large ? 'description-text-large' : 'description-text text-[#7b5839]' ?>">
                                                        <?= htmlspecialchars($descs[$i]['description']) ?>
                                                    </p>
                                                <?php endfor; ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mt-3 pt-4">
                                            <a href="product.php?id=<?= $p['id']; ?>" target="_self"
                                                class="inline-flex items-center justify-center gap-2 py-3 rounded-xl text-[#7b5839] hover:text-[#a67d5b] transition duration-300">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Product Card -->
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- FLOATING WA -->
    <a href="https://api.whatsapp.com/send/?phone=%2B628563559528&text&type=phone_number&app_absent=0&wame_ctl=1"
        target="_blank"
        class="fixed bottom-6 right-6 w-14 h-14 bg-green-500 text-white rounded-full flex items-center justify-center shadow-xl hover:bg-green-600 hover:scale-110 transition transform z-50">
        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
        </svg>
    </a>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi semua carousel
            document.querySelectorAll('.product-carousel').forEach(function (carousel) {
                initCarousel(carousel.id);
            });
        });

        function initCarousel(carouselId) {
            const carousel = document.getElementById(carouselId);
            const track = document.getElementById(carouselId + '_track');
            const dotsContainer = document.getElementById(carouselId + '_dots');

            if (!track) return;

            const slides = track.querySelectorAll('.product-carousel-slide');
            const totalSlides = slides.length;

            if (totalSlides <= 1) {
                if (dotsContainer) dotsContainer.style.display = 'none';
                return;
            }

            let currentSlide = 0;

            // Buat dots
            if (dotsContainer) {
                dotsContainer.innerHTML = '';
                for (let i = 0; i < totalSlides; i++) {
                    const dot = document.createElement('div');
                    dot.className = 'product-carousel-dot' + (i === 0 ? ' active' : '');
                    dot.addEventListener('click', () => goToSlide(carouselId, i));
                    dotsContainer.appendChild(dot);
                }
            }

            // Update carousel position
            function updateCarousel() {
                track.style.transform = `translateX(-${currentSlide * 100}%)`;

                if (dotsContainer) {
                    const dots = dotsContainer.querySelectorAll('.product-carousel-dot');
                    dots.forEach((dot, idx) => {
                        dot.classList.toggle('active', idx === currentSlide);
                    });
                }
            }

            // Fungsi untuk berpindah slide
            window['moveCarousel_' + carouselId] = function (dir) {
                currentSlide += dir;

                if (currentSlide < 0) {
                    currentSlide = totalSlides - 1;
                } else if (currentSlide >= totalSlides) {
                    currentSlide = 0;
                }

                updateCarousel();
            };

            // Fungsi untuk langsung ke slide tertentu
            window['goToSlide_' + carouselId] = function (idx) {
                if (idx >= 0 && idx < totalSlides) {
                    currentSlide = idx;
                    updateCarousel();
                }
            };

            // Swipe untuk mobile
            let startX = 0;
            let isDragging = false;

            track.addEventListener('touchstart', function (e) {
                startX = e.touches[0].clientX;
                isDragging = true;
            }, { passive: true });

            track.addEventListener('touchmove', function (e) {
                if (!isDragging) return;
                const diff = startX - e.touches[0].clientX;

                if (Math.abs(diff) > 50) {
                    if (diff > 0) {
                        window['moveCarousel_' + carouselId](1);
                    } else {
                        window['moveCarousel_' + carouselId](-1);
                    }
                    isDragging = false;
                }
            }, { passive: true });

            track.addEventListener('touchend', function () {
                isDragging = false;
            });

            // Update initial position
            updateCarousel();
        }

        // Fungsi global untuk dipanggil dari button
        window.moveCarousel = function (carouselId, dir) {
            if (window['moveCarousel_' + carouselId]) {
                window['moveCarousel_' + carouselId](dir);
            }
        };

        function goToSlide(carouselId, idx) {
            if (window['goToSlide_' + carouselId]) {
                window['goToSlide_' + carouselId](idx);
            }
        }
    </script>

</body>

</html>