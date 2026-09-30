<?php
use App\Config\App;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Autentikasi - K\'mplang Salatiga') ?></title>
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800;900&family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

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
</head>
<body class="bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 min-h-screen flex items-center justify-center p-4 relative font-sans transition-colors duration-200">

    <!-- Top Floating Actions (Home Link & Theme Toggle) -->
    <div class="fixed top-4 left-4 right-4 z-20 flex items-center justify-between max-w-5xl mx-auto">
        <a href="<?= App::baseUrl('/') ?>" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold bg-white/90 dark:bg-slate-900/90 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 shadow-sm hover:border-amber-500 hover:text-amber-600 dark:hover:text-amber-400 backdrop-blur-md transition-all">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            <span>Kembali ke Beranda</span>
        </a>

        <!-- Theme Toggle Button -->
        <button onclick="toggleTheme()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold bg-white/90 dark:bg-slate-900/90 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 shadow-sm hover:border-amber-500 hover:text-amber-600 dark:hover:text-amber-400 backdrop-blur-md transition-all" title="Ganti Mode Tampilan">
            <span class="material-symbols-outlined text-base theme-icon">dark_mode</span>
            <span class="hidden sm:inline theme-text text-xs">Mode Gelap</span>
        </button>
    </div>

    <!-- Main Auth Card Wrapper -->
    <div class="w-full max-w-xl my-16 relative z-10">
        <!-- Brand Header -->
        <div class="text-center mb-6">
            <a href="<?= App::baseUrl('/') ?>" class="inline-flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 p-2 border border-amber-500/30 group-hover:scale-105 transition-transform duration-200 flex items-center justify-center">
                    <img src="<?= App::baseUrl('/assets/images/siger.png') ?>" alt="Logo Siger" class="w-full h-full object-contain">
                </div>
                <div class="text-left">
                    <h1 class="font-black text-2xl tracking-wider text-amber-600 dark:text-amber-400 font-montserrat">K'MPLANG</h1>
                    <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 tracking-widest uppercase">Salatiga</p>
                </div>
            </a>
        </div>

        <!-- Flash Message Alerts -->
        <?php if (!empty($flashSuccess)): ?>
            <div class="mb-4 flex items-start gap-3 p-4 rounded-2xl bg-white dark:bg-emerald-950/80 border border-emerald-500/40 text-emerald-800 dark:text-emerald-200 shadow-md">
                <span class="material-symbols-outlined text-emerald-500 text-xl">check_circle</span>
                <div class="text-xs sm:text-sm font-medium flex-1 leading-relaxed"><?= e($flashSuccess) ?></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($flashError)): ?>
            <div class="mb-4 flex items-start gap-3 p-4 rounded-2xl bg-white dark:bg-rose-950/80 border border-rose-500/40 text-rose-800 dark:text-rose-200 shadow-md">
                <span class="material-symbols-outlined text-rose-500 text-xl">error</span>
                <div class="text-xs sm:text-sm font-medium flex-1 leading-relaxed"><?= e($flashError) ?></div>
            </div>
        <?php endif; ?>

        <!-- Auth Card Content -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-xl dark:shadow-2xl p-6 sm:p-10 transition-colors duration-200">
            <?= $content ?>
        </div>

        <!-- Clean Community Note (Non-technical) -->
        <div class="text-center mt-6 text-xs text-slate-500 dark:text-slate-400">
            <p>&copy; <?= date('Y') ?> K'mplang Salatiga &bull; Rumah Mahasiswa Perantau Lampung</p>
        </div>
    </div>

    <!-- Global Theme Switcher Helper Script -->
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
    </script>
</body>
</html>
