<?php
use App\Config\App;
$currentUri = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$scriptDir = trim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
if ($scriptDir !== '' && str_starts_with($currentUri, $scriptDir)) {
    $currentUri = trim(substr($currentUri, strlen($scriptDir)), '/');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Panel Admin - K\'mplang Salatiga') ?></title>
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
<body class="bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 min-h-screen flex selection:bg-amber-500 selection:text-slate-950 font-sans transition-colors duration-200">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex-shrink-0 hidden md:flex flex-col justify-between h-screen sticky top-0 transition-colors duration-200">
        <div>
            <!-- Admin Brand -->
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 p-1.5 border border-amber-500/30 flex items-center justify-center">
                    <img src="<?= App::baseUrl('/assets/images/siger.png') ?>" alt="Siger" class="w-full h-full object-contain">
                </div>
                <div>
                    <h2 class="font-black text-lg text-amber-600 dark:text-amber-400 font-montserrat">K'MPLANG</h2>
                    <p class="text-[10px] uppercase font-bold tracking-widest text-slate-500 dark:text-slate-400"><?= ($currentUser['role'] ?? '') === 'admin' ? 'Panel Administrator' : 'Panel Pengurus' ?></p>
                </div>
            </div>

            <!-- Menu Links -->
            <nav class="p-4 space-y-1">
                <a href="<?= App::baseUrl('/admin/dashboard') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all <?= $currentUri === 'admin/dashboard' ? 'bg-amber-500 text-slate-950 font-bold shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-950 dark:hover:text-white' ?>">
                    <span class="material-symbols-outlined text-lg">dashboard</span>
                    Dashboard
                </a>
                <a href="<?= App::baseUrl('/admin/settings') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all <?= $currentUri === 'admin/settings' ? 'bg-amber-500 text-slate-950 font-bold shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-950 dark:hover:text-white' ?>">
                    <span class="material-symbols-outlined text-lg">tune</span>
                    Kelola Konten (CMS)
                </a>
                <a href="<?= App::baseUrl('/admin/proker') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all <?= $currentUri === 'admin/proker' ? 'bg-amber-500 text-slate-950 font-bold shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-950 dark:hover:text-white' ?>">
                    <span class="material-symbols-outlined text-lg">assignment</span>
                    Program Kerja
                </a>
                <a href="<?= App::baseUrl('/admin/activities') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all <?= str_starts_with($currentUri, 'admin/activities') ? 'bg-amber-500 text-slate-950 font-bold shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-950 dark:hover:text-white' ?>">
                    <span class="material-symbols-outlined text-lg">photo_library</span>
                    Kegiatan &amp; Album
                </a>
                <a href="<?= App::baseUrl('/admin/articles') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all <?= str_starts_with($currentUri, 'admin/articles') ? 'bg-amber-500 text-slate-950 font-bold shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-950 dark:hover:text-white' ?>">
                    <span class="material-symbols-outlined text-lg">article</span>
                    Artikel &amp; Sejarah
                </a>
                <a href="<?= App::baseUrl('/admin/members') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all <?= $currentUri === 'admin/members' ? 'bg-amber-500 text-slate-950 font-bold shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-950 dark:hover:text-white' ?>">
                    <span class="material-symbols-outlined text-lg">group</span>
                    Verifikasi Anggota
                </a>
            </nav>
        </div>

        <!-- Sidebar Bottom Footer -->
        <div class="p-4 border-t border-slate-100 dark:border-slate-800 space-y-1.5">
            <a href="<?= App::baseUrl('/') ?>" target="_blank" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                <span class="material-symbols-outlined text-base">open_in_new</span>
                Buka Website Publik
            </a>
            <a href="<?= App::baseUrl('/logout') ?>" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                <span class="material-symbols-outlined text-base">logout</span>
                Keluar
            </a>
        </div>
    </aside>

    <!-- Main Admin Workspace -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Top Navigation Bar -->
        <header class="h-16 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 px-6 flex items-center justify-between sticky top-0 z-40 transition-colors duration-200">
            <div class="flex items-center gap-3">
                <h1 class="text-base font-black text-slate-900 dark:text-slate-100 font-montserrat truncate"><?= e($title ?? 'Dashboard Admin') ?></h1>
            </div>
            <div class="flex items-center gap-3">
                <!-- Theme Switcher Button -->
                <button type="button" onclick="toggleTheme()" class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center" title="Ganti Mode Tampilan (Terang/Gelap)">
                    <span class="material-symbols-outlined text-xl theme-icon">dark_mode</span>
                </button>

                <div class="h-5 w-px bg-slate-200 dark:bg-slate-800"></div>

                <!-- User Badge -->
                <div class="flex items-center gap-2 text-xs bg-slate-100 dark:bg-slate-800/80 px-3 py-1.5 rounded-full border border-slate-200 dark:border-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="font-bold text-slate-800 dark:text-slate-200"><?= e($currentUser['nama_lengkap'] ?? 'Admin') ?></span>
                    <span class="text-[10px] uppercase font-bold text-amber-600 dark:text-amber-400 tracking-wider">(<?= e($currentUser['role'] ?? 'admin') ?>)</span>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 pt-4">
            <?php if (!empty($flashSuccess)): ?>
                <div class="flex items-start gap-3 p-4 rounded-2xl bg-white/95 dark:bg-emerald-950/90 border border-emerald-500/40 text-emerald-800 dark:text-emerald-200 shadow-md">
                    <span class="material-symbols-outlined text-emerald-500 text-xl">check_circle</span>
                    <div class="text-sm font-medium flex-1"><?= e($flashSuccess) ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($flashError)): ?>
                <div class="flex items-start gap-3 p-4 rounded-2xl bg-white/95 dark:bg-rose-950/90 border border-rose-500/40 text-rose-800 dark:text-rose-200 shadow-md">
                    <span class="material-symbols-outlined text-rose-500 text-xl">error</span>
                    <div class="text-sm font-medium flex-1"><?= e($flashError) ?></div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Content Area -->
        <div class="p-6">
            <?= $content ?>
        </div>
    </div>

    <!-- Theme Switcher Script -->
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
        }

        document.addEventListener('DOMContentLoaded', updateThemeIcons);
    </script>
</body>
</html>
