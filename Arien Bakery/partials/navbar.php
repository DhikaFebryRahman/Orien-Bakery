<?php
require_once __DIR__ . '/../app/db.php';
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<style>
  :root {
    --font-playfair: 'Playfair Display', serif;
    --font-greatvibes: 'Great Vibes', cursive;
    --font-poppins: 'Poppins', sans-serif;
  }
  nav {
    font-family: var(--font-poppins);
  }
  .nav-menu-item {
    position: relative;
  }
  .category-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    min-width: 200px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s ease;
    z-index: 100;
    overflow: hidden;
  }
  .nav-menu-item:hover .category-dropdown,
  .category-dropdown:hover {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
  }
  .category-dropdown a {
    display: block;
    padding: 12px 16px;
    color: #3F2E1F;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s;
  }
  .category-dropdown a:last-child {
    border-bottom: none;
  }
  .category-dropdown a:hover {
    background: #F1E3C6;
    padding-left: 20px;
  }
  .dropdown-arrow {
    font-size: 0.7em;
    margin-left: 5px;
    transition: transform 0.3s;
  }
  .nav-menu-item:hover .dropdown-arrow {
    transform: rotate(180deg);
  }

  /* ===== MOBILE STYLES (max-width: 768px) ===== */
  @media (max-width: 768px) {
    /* Nav menu animasi slide */
    #nav-menu {
      transition: transform 0.3s ease, opacity 0.3s ease, visibility 0.3s;
      transform-origin: top;
      position: absolute;
      top: 100%;
      left: 0;
      width: 100%;
      background: #8B5E3C;
      padding: 1rem;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      z-index: 1000;
    }
    /* State tersembunyi */
    #nav-menu.nav-menu-hidden {
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      pointer-events: none;
    }
    /* State muncul */
    #nav-menu:not(.nav-menu-hidden) {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
      pointer-events: auto;
    }

    /* Dropdown kategori di mobile */
    .category-dropdown {
      position: static; /* hilangkan absolute */
      box-shadow: none;
      background: #f9f9f9;
      margin-top: 0.5rem;
      border-radius: 0;
      
      /* animasi max-height */
      max-height: 0;
      opacity: 0;
      overflow: hidden;
      padding: 0 1rem; /* beri ruang horizontal */
      transition: max-height 0.4s ease, opacity 0.3s ease, padding 0.3s ease;
      
      /* non-visible, tapi kita atur via max-height & opacity */
      visibility: visible; /* biar transisi jalan */
      transform: none; /* reset transform */
    }

    /* Saat open */
    .nav-menu-item.open .category-dropdown {
      max-height: 500px; /* cukup besar untuk menampung item */
      opacity: 1;
      padding: 0.5rem 1rem; /* beri padding vertikal kecil */
    }

    .nav-menu-item.open .dropdown-arrow {
      transform: rotate(180deg);
    }

    /* Tautan dalam dropdown */
    .category-dropdown a {
      padding: 10px 12px;
      border-bottom: 1px solid #e0d0b0;
    }
  }
</style>

<nav class="fixed top-0 left-0 w-full z-50 bg-[#8B5E3C] px-4 md:px-10 py-1 flex justify-between items-center">
  <!-- Logo -->
  <h1 class="text-white text-2xl m-0.5 leading-tight">
    <div>
      <span class="block font-playfair relative top-2 tracking-[100] mb-1">ARIEN</span>
      <span class="block font-greatvibes relative left-2.5 text-lg">Bakery</span>
    </div>
  </h1>

  <!-- Hamburger button -->
  <button id="menu-toggle" class="block md:hidden text-white focus:outline-none">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
  </button>

  <!-- Navigation menu -->
  <ul id="nav-menu" class="md:flex md:flex-row gap-4 md:gap-8 lg:gap-12 text-white font-poppins md:relative md:w-auto md:bg-transparent md:p-0 md:shadow-none md:opacity-100 md:visible md:transform-none md:pointer-events-auto nav-menu-hidden">
    <li><a class="block py-2 md:py-0 hover:text-[#DBAD7F] transition" href="index.php">Home</a></li>

    <li class="nav-menu-item relative">
      <a href="menu.php" class="hover:text-[#DBAD7F] transition flex items-center justify-between md:inline-flex">
        Menu <span class="dropdown-arrow ml-1">▼</span>
      </a>
      <div class="category-dropdown md:absolute left-0 mt-2 md:mt-0 w-full md:w-auto">
        <?php if (!empty($categories)): ?>
          <?php foreach ($categories as $cat): ?>
            <a href="menu.php?category=<?= $cat['id'] ?>" class="block px-4 py-2 hover:bg-[#F1E3C6]">
              <?= htmlspecialchars($cat['name']) ?>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <a href="menu.php" class="block px-4 py-2">Semua Menu</a>
        <?php endif; ?>
      </div>
    </li>

    <li><a class="block py-2 md:py-0 hover:text-[#DBAD7F] transition" href="about.php">About</a></li>
    <li><a class="block py-2 md:py-0 hover:text-[#DBAD7F] transition" href="faq.php">FAQ</a></li>
    <li><a class="block py-2 md:py-0 hover:text-[#DBAD7F] transition" href="contact.php">Contact</a></li>
  </ul>
</nav>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');
    const menuItems = document.querySelectorAll('.nav-menu-item');

    // Toggle menu mobile dengan animasi
    if (toggleButton) {
      toggleButton.addEventListener('click', function(e) {
        e.stopPropagation();
        navMenu.classList.toggle('nav-menu-hidden');
      });
    }

    // Dropdown di mobile: klik menu "Menu" untuk expand dengan animasi
    menuItems.forEach(item => {
      const link = item.querySelector('a[href="menu.php"]');
      if (link) {
        link.addEventListener('click', function(e) {
          if (window.innerWidth < 768) {
            e.preventDefault(); // mencegah navigasi ke menu.php
            item.classList.toggle('open');
          }
        });
      }
    });

    // Klik di luar menu untuk menutup (mobile)
    window.addEventListener('click', function(e) {
      if (window.innerWidth < 768) {
        if (!navMenu.contains(e.target) && !toggleButton.contains(e.target)) {
          // Tutup menu utama
          if (!navMenu.classList.contains('nav-menu-hidden')) {
            navMenu.classList.add('nav-menu-hidden');
          }
          // Tutup juga dropdown yang mungkin terbuka (opsional)
          menuItems.forEach(item => item.classList.remove('open'));
        }
      }
    });

    // Tutup dropdown jika klik di luar setelah menu terbuka (opsional)
    navMenu.addEventListener('click', function(e) {
      e.stopPropagation(); // agar klik di dalam tidak menutup
    });
  });
</script>