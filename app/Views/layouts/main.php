<?php
/**
 * ============================================================================
 * Layout Utama Publik (main.php)
 * ============================================================================
 * Mengintegrasikan:
 * - Anti-FOUC Theme Initialization (White-Yellow Light Mode & Dark Grey Dark Mode).
 * - Tipografi Editorial & Poster (Fraunces, Syne, Montserrat, Plus Jakarta Sans,
 *   Source Serif 4, JetBrains Mono).
 * - Bootstrap 5.3 Grid/Utilities + Tailwind CSS Custom Palette + Vanilla CSS Poster.
 * ============================================================================
 */
use App\Config\App;
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'K\'mplang Salatiga — Keluarga Mahasiswa Perantauan Lampung') ?></title>
    <meta name="description" content="Portal Resmi Keluarga Mahasiswa Perantauan Lampung (K'mplang) di Kota Salatiga. Rumah persaudaraan, pelestarian budaya Sang Bumi Ruwa Jurai, dan transparansi organisasi.">
    <link rel="icon" href="<?= App::baseUrl('/assets/images/siger.png') ?>">

    <!-- Anti-FOUC Theme Script (Default: Light Mode White-Yellow, Dark Mode: Dark Grey) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('kmplang_theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.setAttribute('data-bs-theme', 'light');
            }
        })();
    </script>

    <!-- Google Fonts: Poster Display Serif, Geometric Display, Reading Serif & Sans, Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600..900;1,9..144,600..800&family=JetBrains+Mono:wght@500;700&family=Montserrat:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Source+Serif+4:ital,opsz,wght@0,8..60,400..700;1,8..60,400..600&family=Syne:wght@700;800&display=swap" rel="stylesheet">

    <!-- Google Material Symbols Outlined -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Bootstrap 5.3 Grid & Utilities CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-grid.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-utilities.min.css" rel="stylesheet">

    <!-- AOS (Animate On Scroll) CSS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Tailwind CSS CDN dengan Palet Kustom (White-Yellow Light & Dark Grey Dark) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        slate: {
                            50:  '#FAF6E6', /* Warm White-Yellow Paper */
                            100: '#F2EAD0', /* Soft Cream-Yellow */
                            200: '#DFD5B8', /* Warm Poster Border */
                            300: '#C8BC9B',
                            400: '#9A917B',
                            500: '#6E6756',
                            600: '#4E493D',
                            700: '#35332E',
                            800: '#2C2C33', /* Dark Grey Elevated */
                            900: '#232329', /* Dark Grey Surface */
                            950: '#1A1A1E'  /* Matte Dark Grey Background */
                        },
                        amber: {
                            300: '#FDE047',
                            400: '#FACC15',
                            500: '#EAB308',
                            600: '#CA8A04',
                            700: '#A16207'
                        },
                        gold: '#DAA520'
                    },
                    fontFamily: {
                        poster: ['Fraunces', 'Georgia', 'serif'],
                        display: ['Syne', 'Montserrat', 'sans-serif'],
                        montserrat: ['Montserrat', 'sans-serif'],
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        reading: ['Source Serif 4', 'Georgia', 'serif'],
                        mono: ['JetBrains Mono', 'monospace']
                    }
                }
            }
        }
    </script>

    <!-- Custom Poster & Typography Vanilla CSS -->
    <link rel="stylesheet" href="<?= App::baseUrl('/assets/css/custom.css') ?>">
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 font-sans min-h-screen flex flex-col antialiased selection:bg-amber-400 selection:text-slate-950 transition-colors duration-200">

    <!-- Navbar Utama -->
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <!-- Flash Notification Messages (Minimalist 1-Base-Color Toast) -->
    <div class="fixed top-20 right-4 z-50 max-w-sm w-full space-y-2.5 pointer-events-none px-2 sm:px-0">
        <?php if (!empty($flashSuccess)): ?>
            <div id="flash-success" class="pointer-events-auto rounded-xl flex items-start gap-3 p-4 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 shadow-lg text-slate-900 dark:text-slate-100">
                <span class="material-symbols-outlined text-slate-700 dark:text-slate-300 text-xl mt-0.5">verified</span>
                <div class="flex-1 min-w-0">
                    <div class="font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">Informasi Sistem</div>
                    <div class="text-xs sm:text-sm font-semibold leading-relaxed mt-0.5"><?= e($flashSuccess) ?></div>
                </div>
                <button type="button" onclick="document.getElementById('flash-success').remove()" class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 p-0.5">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if (!empty($flashError)): ?>
            <div id="flash-error" class="pointer-events-auto rounded-xl flex items-start gap-3 p-4 bg-white dark:bg-slate-900 border border-rose-500/70 shadow-lg text-slate-900 dark:text-slate-100">
                <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-xl mt-0.5">error</span>
                <div class="flex-1 min-w-0">
                    <div class="font-mono text-[10px] font-bold uppercase tracking-widest text-rose-600 dark:text-rose-400">Peringatan</div>
                    <div class="text-xs sm:text-sm font-semibold leading-relaxed mt-0.5"><?= e($flashError) ?></div>
                </div>
                <button type="button" onclick="document.getElementById('flash-error').remove()" class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 p-0.5">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Area Konten Utama -->
    <main class="flex-grow pt-20">
        <?= $content ?>
    </main>

    <!-- Footer Editorial -->
    <?php require __DIR__ . '/../partials/footer.php'; ?>

    <!-- Modal Overlay Autentikasi Cepat (Hanya dimuat untuk pengunjung yang belum login) -->
    <?php if (empty($currentUser)): ?>
        <?php require __DIR__ . '/../partials/auth-modal.php'; ?>
    <?php endif; ?>

    <!-- Bootstrap 5.3 Bundle JS, AOS JS & Script Pengendali Tema -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            document.documentElement.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
            localStorage.setItem('kmplang_theme', isDark ? 'dark' : 'light');
            updateThemeIcons();
        }

        function updateThemeIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('.theme-icon').forEach(icon => {
                icon.textContent = isDark ? 'light_mode' : 'dark_mode';
            });
            document.querySelectorAll('.theme-text').forEach(text => {
                text.textContent = isDark ? 'Mode Kertas Terang' : 'Mode Abu Gelap';
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateThemeIcons();
            if (window.AOS) {
                AOS.init({
                    duration: 600,
                    easing: 'ease-out-cubic',
                    once: true,
                    offset: 35,
                    disable: false
                });
            }
        });

        setTimeout(() => {
            const s = document.getElementById('flash-success');
            const e = document.getElementById('flash-error');
            if (s) s.remove();
            if (e) e.remove();
        }, 6500);

        <?php if (!empty($currentUser)): ?>
        // Heartbeat Keep-Alive: Menjaga sesi tetap aktif selama pengguna online (memperbarui masa aktif 1 bulan)
        setInterval(() => {
            if (!document.hidden) {
                fetch('<?= App::baseUrl('/ping') ?>', {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                }).catch(() => {});
            }
        }, 600000);
        <?php endif; ?>
    </script>
</body>
</html>
