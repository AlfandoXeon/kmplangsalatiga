<?php
use App\Config\App;
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'K\'mplang Salatiga') ?></title>
    <meta name="description" content="Website Resmi Komunitas Mahasiswa Perantauan Lampung (K'mplang) di Kota Salatiga. Rumah bagi mahasiswa perantau, lestarikan budaya, dan raih prestasi.">
    <link rel="icon" href="<?= App::baseUrl('/assets/images/siger.png') ?>">

    <!-- Anti-FOUC Theme Script (Default: Light Mode) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('kmplang_theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Google Material Symbols (Google Icons - Strictly No Emojis) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        amber: {
                            400: '#FFBF00',
                            500: '#F59E0B',
                            600: '#D97706',
                            700: '#B45309'
                        },
                        gold: '#DAA520'
                    },
                    fontFamily: {
                        montserrat: ['Montserrat', 'sans-serif'],
                        sans: ['Open Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= App::baseUrl('/assets/css/custom.css') ?>">
</head>
<body class="bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 font-sans min-h-screen flex flex-col antialiased selection:bg-amber-500 selection:text-white transition-colors duration-200">

    <!-- Navbar -->
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <!-- Flash Notification Messages -->
    <div class="fixed top-24 right-4 z-50 max-w-md w-full space-y-3 pointer-events-none">
        <?php if (!empty($flashSuccess)): ?>
            <div id="flash-success" class="pointer-events-auto flex items-start gap-3 p-4 rounded-2xl bg-white/95 dark:bg-emerald-950/90 border border-emerald-500/40 text-emerald-800 dark:text-emerald-200 shadow-xl backdrop-blur-md transition-all">
                <span class="material-symbols-outlined text-emerald-500 text-xl">check_circle</span>
                <div class="flex-1 text-xs sm:text-sm font-medium leading-relaxed"><?= e($flashSuccess) ?></div>
                <button onclick="document.getElementById('flash-success').remove()" class="text-emerald-600 dark:text-emerald-400 hover:opacity-75">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if (!empty($flashError)): ?>
            <div id="flash-error" class="pointer-events-auto flex items-start gap-3 p-4 rounded-2xl bg-white/95 dark:bg-rose-950/90 border border-rose-500/40 text-rose-800 dark:text-rose-200 shadow-xl backdrop-blur-md transition-all">
                <span class="material-symbols-outlined text-rose-500 text-xl">error</span>
                <div class="flex-1 text-xs sm:text-sm font-medium leading-relaxed"><?= e($flashError) ?></div>
                <button onclick="document.getElementById('flash-error').remove()" class="text-rose-600 dark:text-rose-400 hover:opacity-75">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow pt-20">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <?php require __DIR__ . '/../partials/footer.php'; ?>

    <!-- Auth Overlay Modal (Login & Register) -->
    <?php require __DIR__ . '/../partials/auth-modal.php'; ?>

    <!-- Global Theme Switcher & Auto-hide Flash Scripts -->
    <script>
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('kmplang_theme', isDark ? 'dark' : 'light');
            updateThemeIcons();
        }

        function updateThemeIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('.theme-icon').forEach(icon => {
                icon.textContent = isDark ? 'light_mode' : 'dark_mode';
            });
            document.querySelectorAll('.theme-text').forEach(text => {
                text.textContent = isDark ? 'Mode Terang' : 'Mode Gelap';
            });
        }

        document.addEventListener('DOMContentLoaded', updateThemeIcons);

        setTimeout(() => {
            const s = document.getElementById('flash-success');
            const e = document.getElementById('flash-error');
            if (s) s.remove();
            if (e) e.remove();
        }, 6000);
    </script>
</body>
</html>
