<?php
require_once __DIR__ . '/app/db.php';
$contact = $pdo->query("SELECT * FROM contacts LIMIT 1")->fetch();
$wa = $contact['whatsapp'] ?? '62XXXXXXXXXX';
$faqs = $pdo->query("SELECT * FROM faqs ORDER BY id ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Arien Bakery</title>
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

        /* Animasi FAQ */
        .faq-item.active .faq-answer {
            max-height: 500px;
            opacity: 1;
            padding-bottom: 1rem;
            /* beri jarang bawah saat terbuka */
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        .faq-answer {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-[#FDEFD7]">

    <?php include 'partials/navbar.php'; ?>

    <!-- Hero Section (responsif) -->
    <section class="pt-28 md:pt-24 pb-16 text-center px-4">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-playfair font-bold text-[#3F2E1F] mb-4">
            Pertanyaan Umum
        </h1>
        <p class="text-gray-600 max-w-2xl mx-auto text-sm sm:text-base">
            Temukan jawaban untuk pertanyaan yang sering diajukan
        </p>
    </section>

    <!-- FAQ Section (responsif) -->
    <section class="container mx-auto px-4 pb-20 max-w-3xl">
        <div class="space-y-4">
            <?php if (!empty($faqs)): ?>
                <?php foreach ($faqs as $faq): ?>
                    <div class="faq-item bg-white rounded-xl shadow overflow-hidden">
                        <button
                            class="faq-question w-full px-4 sm:px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition gap-2"
                            onclick="toggleFaq(this)">
                            <span
                                class="font-semibold text-[#3F2E1F] text-sm sm:text-base"><?= htmlspecialchars($faq['question']) ?></span>
                            <svg class="faq-icon w-5 h-5 text-[#8B5E3C] transition-transform duration-300 flex-shrink-0"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer px-4 sm:px-6 text-gray-600 text-sm sm:text-base">
                            <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Default FAQs jika database kosong -->
                <div class="faq-item bg-white rounded-xl shadow overflow-hidden">
                    <button
                        class="faq-question w-full px-4 sm:px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition gap-2"
                        onclick="toggleFaq(this)">
                        <span class="font-semibold text-[#3F2E1F] text-sm sm:text-base">Bagaimana cara memesan kue?</span>
                        <svg class="faq-icon w-5 h-5 text-[#8B5E3C] transition-transform duration-300 flex-shrink-0"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="faq-answer px-4 sm:px-6 text-gray-600 text-sm sm:text-base">
                        Anda dapat memesan kue melalui WhatsApp dengan mengklik tombol "Pesan via WhatsApp" di menu produk
                        atau menghubungi kami langsung di nomor yang tersedia.
                    </div>
                </div>

                <div class="faq-item bg-white rounded-xl shadow overflow-hidden">
                    <button
                        class="faq-question w-full px-4 sm:px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition gap-2"
                        onclick="toggleFaq(this)">
                        <span class="font-semibold text-[#3F2E1F] text-sm sm:text-base">Berapa lama waktu pembuatan
                            kue?</span>
                        <svg class="faq-icon w-5 h-5 text-[#8B5E3C] transition-transform duration-300 flex-shrink-0"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="faq-answer px-4 sm:px-6 text-gray-600 text-sm sm:text-base">
                        Waktu pembuatan tergantung pada jenis dan ukuran kue. Umumnya membutuhkan 1-3 hari. Untuk pesanan
                        khusus atau acara besar, kami sarankan memesan minimal 1 minggu sebelumnya.
                    </div>
                </div>

                <div class="faq-item bg-white rounded-xl shadow overflow-hidden">
                    <button
                        class="faq-question w-full px-4 sm:px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition gap-2"
                        onclick="toggleFaq(this)">
                        <span class="font-semibold text-[#3F2E1F] text-sm sm:text-base">Apakah tersedia pengiriman?</span>
                        <svg class="faq-icon w-5 h-5 text-[#8B5E3C] transition-transform duration-300 flex-shrink-0"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="faq-answer px-4 sm:px-6 text-gray-600 text-sm sm:text-base">
                        Ya, kami menyediakan layanan pengiriman untuk area tertentu. Biaya pengiriman akan dihitung
                        berdasarkan jarak. Anda juga dapat mengambil pesanan langsung di lokasi kami.
                    </div>
                </div>

                <div class="faq-item bg-white rounded-xl shadow overflow-hidden">
                    <button
                        class="faq-question w-full px-4 sm:px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition gap-2"
                        onclick="toggleFaq(this)">
                        <span class="font-semibold text-[#3F2E1F] text-sm sm:text-base">Apakah bisa custom design
                            kue?</span>
                        <svg class="faq-icon w-5 h-5 text-[#8B5E3C] transition-transform duration-300 flex-shrink-0"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="faq-answer px-4 sm:px-6 text-gray-600 text-sm sm:text-base">
                        Tentu! Kami menerima pesanan kue dengan design custom sesuai keinginan Anda. Silakan hubungi kami
                        melalui WhatsApp untuk mendiskusikan design dan harga.
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Contact CTA (responsif) -->
        <div class="mt-12 bg-[#8B5E3C] rounded-2xl shadow-lg p-6 sm:p-8 text-center text-white">
            <h2 class="text-xl sm:text-2xl font-playfair font-bold mb-4">Punya Pertanyaan Lain?</h2>
            <p class="mb-6 opacity-90 text-sm sm:text-base">Silakan hubungi kami, kami dengan senang hati akan membantu
            </p>
            <a href="https://api.whatsapp.com/send/?phone=%2B628563559528&text&type=phone_number&app_absent=0&wame_ctl=1" target="_blank"
                class="inline-flex items-center gap-2 px-6 sm:px-8 py-3 bg-white text-[#8B5E3C] rounded-full font-medium hover:bg-[#F1E3C6] transition text-sm sm:text-base">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                </svg>
                Hubungi WhatsApp
            </a>
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

    <script>
        function toggleFaq(button) {
            const faqItem = button.closest('.faq-item');
            faqItem.classList.toggle('active');
        }
    </script>

    <?php include 'partials/footer.php'; ?>

</body>

</html>