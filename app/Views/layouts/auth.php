<?php
/**
 * ============================================================================
 * Layout Halaman Autentikasi (auth.php)
 * ============================================================================
 * Digunakan untuk halaman `/login` dan `/register` mandiri dengan gaya
 * Poster Kartu Identitas K'mplang (White-Yellow Light & Dark Grey Dark).
 * ============================================================================
 */
use App\Config\App;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Autentikasi — K\'mplang Salatiga') ?></title>
    <link rel="icon" href="<?= App::baseUrl('/assets/images/siger.png') ?>">

    <!-- Anti-FOUC Theme Script -->
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

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600..900;1,9..144,600..800&family=JetBrains+Mono:wght@500;700&family=Montserrat:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">

    <!-- Google Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Bootstrap 5.3 Grid & Utilities -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-grid.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-utilities.min.css" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        slate: {
                            50:  '#FAF6E6',
                            100: '#F2EAD0',
                            200: '#DFD5B8',
                            300: '#C8BC9B',
                            400: '#9A917B',
                            500: '#6E6756',
                            600: '#4E493D',
                            700: '#35332E',
                            800: '#2C2C33',
                            900: '#232329',
                            950: '#1A1A1E'
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
                        mono: ['JetBrains Mono', 'monospace']
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="<?= App::baseUrl('/assets/css/custom.css') ?>">
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 min-h-screen flex items-center justify-center p-4 relative font-sans transition-colors duration-200">

    <!-- Top Floating Bar (Kembali ke Beranda & Sakelar Tema) -->
    <div class="fixed top-4 left-4 right-4 z-20 flex items-center justify-between max-w-5xl mx-auto">
        <a href="<?= App::baseUrl('/') ?>" class="poster-btn-secondary !py-2 !px-3.5 !text-xs">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            <span>Beranda</span>
        </a>

        <button onclick="toggleTheme()" class="poster-btn-secondary !py-2 !px-3.5 !text-xs" title="Ganti Mode Tampilan">
            <span class="material-symbols-outlined text-base theme-icon">dark_mode</span>
            <span class="hidden sm:inline theme-text text-xs">Mode Abu Gelap</span>
        </button>
    </div>

    <!-- Main Auth Poster Card Wrapper -->
    <div class="w-full max-w-xl my-16 relative z-10">
        <!-- Brand Header -->
        <div class="text-center mb-6">
            <a href="<?= App::baseUrl('/') ?>" class="inline-flex items-center gap-3.5 group">
                <div class="w-12 h-12 rounded-xl bg-amber-400/20 p-2 border-2 border-slate-900 dark:border-amber-400 flex items-center justify-center shadow-sm">
                    <img src="<?= App::baseUrl('/assets/images/siger.png') ?>" alt="Logo Siger" class="w-full h-full object-contain">
                </div>
                <div class="text-left">
                    <span class="font-mono text-[10px] font-bold uppercase tracking-widest text-amber-700 dark:text-amber-400 block">Sang Bumi Ruwa Jurai</span>
                    <h1 class="font-poster font-black text-2xl tracking-tight text-slate-900 dark:text-white leading-none">K'MPLANG SALATIGA</h1>
                </div>
            </a>
        </div>

        <!-- Flash Alerts -->
        <?php if (!empty($flashSuccess)): ?>
            <div class="mb-5 rounded-xl flex items-start gap-3 p-4 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 shadow-sm text-slate-900 dark:text-slate-100">
                <span class="material-symbols-outlined text-slate-700 dark:text-slate-300 text-xl">check_circle</span>
                <div class="text-xs sm:text-sm font-medium flex-1 leading-relaxed"><?= e($flashSuccess) ?></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($flashError)): ?>
            <div class="mb-5 rounded-xl flex items-start gap-3 p-4 bg-white dark:bg-slate-900 border border-rose-500/70 shadow-sm text-slate-900 dark:text-slate-100">
                <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-xl">error</span>
                <div class="text-xs sm:text-sm font-medium flex-1 leading-relaxed"><?= e($flashError) ?></div>
            </div>
        <?php endif; ?>

        <!-- Auth Poster Card Content -->
        <div class="poster-card bg-white dark:bg-slate-900 overflow-hidden">
            <div class="poster-card-header">
                <span>PORTAL KEANGGOTAAN RESMI</span>
                <span>EDISI 2026/2027</span>
            </div>
            <div class="p-6 sm:p-9">
                <?= $content ?>
            </div>
        </div>

        <div class="text-center mt-6 font-mono text-[11px] text-slate-500 dark:text-slate-400 uppercase tracking-wider">
            &copy; <?= date('Y') ?> K'mplang Salatiga &bull; Rumah Mahasiswa Perantau Lampung
        </div>
    </div>

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

        document.addEventListener('DOMContentLoaded', updateThemeIcons);
    </script>
</body>
</html>
