<?php
require_once __DIR__ . '/app/db.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Arien Bakery</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@600;700&family=Great+Vibes&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        .font-greatvibes {
            font-family: 'Great Vibes', cursive;
        }

        .hero-bg {
            background-color: #FDEFD7;
        }

        .btn-wa {
            transition: all 0.3s ease;
        }

        .btn-wa:hover {
            transform: translateY(-2px);
        }

        .floating-wa {
            transition: all 0.3s ease;
            z-index: 50;
        }

        .floating-wa:hover {
            transform: scale(1.1);
        }
    </style>
</head>

<body class="hero-bg">

    <?php include 'partials/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="pt-24 pb-16 px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-playfair font-bold text-[#3F2E1F] mb-4">
                Tentang Kami
            </h1>
            <p class="text-gray-600 text-lg">
                Cerita di balik setiap kue lezat dari Arien Bakery
            </p>
        </div>
    </section>

    <!-- Our Story -->
    <section class="container mx-auto px-4 pb-16">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="grid md:grid-cols-2 gap-0 h-full">
                    <!-- Image Column -->
                    <div class="relative bg-gray-100 overflow-hidden min-h-[400px] md:min-h-0">
                        <img src="assets/images/bg/image.webp" alt="Arien Bakery"
                            class="absolute inset-0 w-full h-full object-cover">
                    </div>

                    <!-- Text Column -->
                    <div class="p-8 md:p-12 flex items-center">
                        <div>
                            <h2 class="text-2xl font-playfair font-bold text-[#3F2E1F] mb-4">
                                Cerita Kami
                            </h2>
                            <p class="text-gray-700 leading-relaxed text-justify">
                                Selamat datang di Arien Bakery, di mana setiap gigitannya nikmat! Kami bangga dengan
                                komitmen
                                kami terhadap kualitas, menawarkan berbagai macam makanan yang baru dipanggang yang
                                dibuat
                                dengan cinta dan perhatian. Kue kami dibuat segar setiap hari. Anda dapat menikmati
                                suguhan
                                sempurna untuk setiap kesempatan. Bergabunglah bersama kami dalam merayakan nikmatnya
                                membuat
                                kue dan nikmati cita rasa lezat yang menjadi ciri khas Arien Bakery.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="container mx-auto px-4 pb-20">
        <div class="max-w-2xl mx-auto">
            <div class="bg-[#8B5E3C] rounded-2xl shadow-lg p-8 text-center text-white">
                <h2 class="text-2xl md:text-3xl font-playfair font-bold mb-4">
                    Ingin Memesan?
                </h2>
                <p class="mb-6 text-gray-200">
                    Hubungi kami via WhatsApp untuk pemesanan atau konsultasi
                </p>
                <a href="https://api.whatsapp.com/send/?phone=%2B628563559528&text&type=phone_number&app_absent=0&wame_ctl=1"
                    target="_blank" rel="noopener noreferrer"
                    class="btn-wa inline-flex items-center gap-2 px-8 py-3 bg-white text-[#8B5E3C] rounded-full font-semibold hover:bg-[#F1E3C6] shadow-md">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    Hubungi WhatsApp
                </a>
            </div>
        </div>
    </section>

    <!-- Floating WhatsApp Button -->
    <a href="https://api.whatsapp.com/send/?phone=%2B628563559528&text&type=phone_number&app_absent=0&wame_ctl=1"
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