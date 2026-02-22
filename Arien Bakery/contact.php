<?php
require_once __DIR__ . '/app/db.php';
$contact = $pdo->query("SELECT * FROM contacts LIMIT 1")->fetch();
$wa = $contact['whatsapp'] ?? '6285877772281';
$phone = $contact['phone'] ?? '';
$email = $contact['email'] ?? '';
$instagram = $contact['instagram'] ?? '@orien_beji';
$address = $contact['address'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Arien Bakery</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    </style>
</head>

<body class="bg-[#FDEFD7]">

    <?php include 'partials/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="pt-28 md:pt-24 pb-12 text-center px-4">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-playfair font-bold text-[#3F2E1F] mb-4">
            Hubungi Kami
        </h1>
        <p class="text-gray-600 max-w-2xl mx-auto text-sm sm:text-base">
            Kami siap membantu Anda. Silakan hubungi kami melalui kontak di bawah ini
        </p>
    </section>

    <!-- Contact Info - Desktop 2 kolom seperti gambar -->
    <section class="container mx-auto px-4 pb-8 max-w-5xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- WhatsApp -->
            <a href="https://api.whatsapp.com/send/?phone=<?= $wa ?>" target="_blank"
                class="bg-white rounded-xl shadow-lg p-6 flex items-center gap-4 hover:shadow-xl transition">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-[#3F2E1F] text-lg">WhatsApp</h3>
                    <p class="text-gray-600"><?= htmlspecialchars($wa) ?></p>
                    <p class="text-sm text-green-600">Klik untuk chat</p>
                </div>
            </a>

            <!-- Instagram -->
            <?php if ($instagram): ?>
                <a href="https://instagram.com/<?= htmlspecialchars(str_replace('@', '', $instagram)) ?>" target="_blank"
                    class="bg-white rounded-xl shadow-lg p-6 flex items-center gap-4 hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-[#3F2E1F] text-lg">Instagram</h3>
                        <p class="text-gray-600"><?= htmlspecialchars($instagram) ?></p>
                        <p class="text-sm text-pink-600">Follow kami</p>
                    </div>
                </a>
            <?php endif; ?>

            <!-- Address - Menempati 2 kolom di desktop -->
            <?php if ($address): ?>
                <div class="bg-white rounded-xl shadow-lg p-6 flex items-start gap-4 md:col-span-2">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-[#3F2E1F] text-lg">Alamat</h3>
                        <p class="text-gray-600"><?= nl2br(htmlspecialchars($address)) ?></p>
                        <p class="text-sm text-purple-600 mt-1">Kunjungi kami</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Map (lebar penuh) -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-4">
            <div class="rounded-lg h-96 w-full overflow-hidden">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d640.3485673008458!2d111.90245775696134!3d-8.080081289469682!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78e323b392ae19%3A0xec972acfcb255228!2sOrien%20Tart%26%20Bakery%20Beji!5e0!3m2!1sid!2sid!4v1771342306251!5m2!1sid!2sid"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

    <!-- Opening Hours -->
    <section class="container mx-auto px-4 pb-20 max-w-2xl">
        <div class="bg-[#8B5E3C] rounded-2xl shadow-lg p-6 sm:p-8 text-center text-white">
            <h2 class="text-xl sm:text-2xl font-playfair font-bold mb-6">Jam Buka</h2>
            <div class="space-y-2 text-sm sm:text-base">
                <p class="flex justify-between"><span>Senin - Minggu</span><span>08.00 - 21.00</span></p>            </div>
            <p class="mt-4 text-xs sm:text-sm opacity-80">*Untuk pesanan khusus, kami menerima pesanan di luar jam buka
            </p>
        </div>
    </section>

    <!-- FLOATING WA -->
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