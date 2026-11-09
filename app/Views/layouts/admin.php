<?php
/**
 * ============================================================================
 * Layout Panel Admin & Pengurus (admin.php)
 * ============================================================================
 * Fitur Responsivitas & Desain:
 * 1. Mendukung penuh layar Mobile, Tablet, dan Desktop dengan Sidebar Drawer
 *    interaktif (mengatasi masalah sidebar yang sebelumnya tersembunyi di mobile).
 * 2. Menggunakan palet White-Yellow (Light Mode) & Dark Grey (Dark Mode) dengan
 *    aksen tipografi poster editorial.
 * ============================================================================
 */
use App\Config\App;

$currentUri = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($scriptDir !== '' && str_starts_with($currentUri, $scriptDir)) {
    $currentUri = trim(substr($currentUri, strlen($scriptDir)), '/');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Panel Admin — K\'mplang Salatiga') ?></title>
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
<body class="bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 min-h-screen flex selection:bg-amber-400 selection:text-slate-950 font-sans transition-colors duration-200">

    <!-- Mobile Backdrop Overlay untuk Sidebar Drawer -->
    <div id="admin-sidebar-backdrop" onclick="toggleAdminSidebar()" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm hidden lg:hidden transition-opacity"></div>

    <!-- Sidebar Navigasi Admin (Responsif: Offcanvas di Mobile/Tablet, Sticky di Desktop lg+) -->
    <aside id="admin-sidebar" class="fixed lg:sticky top-0 left-0 z-50 w-72 bg-white dark:bg-slate-900 border-r-2 border-slate-900 dark:border-slate-700 flex-shrink-0 flex flex-col justify-between h-screen -translate-x-full lg:translate-x-0 transition-transform duration-200">
        <div>
            <!-- Brand Header Admin -->
            <div class="h-14 px-4 border-b border-slate-300 dark:border-slate-800 flex items-center justify-between bg-slate-100/70 dark:bg-slate-900">
                <a href="<?= App::baseUrl('/admin/dashboard') ?>" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-slate-200 dark:bg-slate-800 p-1 border border-slate-400 dark:border-slate-700 flex items-center justify-center">
                        <img src="<?= App::baseUrl('/assets/images/siger.png') ?>" alt="Siger" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h2 class="font-poster font-black text-sm text-slate-900 dark:text-white leading-none">K'MPLANG</h2>
                        <p class="font-mono text-[9px] uppercase font-bold tracking-wider text-slate-500 dark:text-slate-400 mt-0.5">
                            <?= ($currentUser['role'] ?? '') === 'admin' ? 'Panel Admin' : 'Panel Pengurus' ?>
                        </p>
                    </div>
                </a>
                <!-- Tombol Tutup Sidebar di Mobile -->
                <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden w-8 h-8 rounded-lg flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>

            <!-- Menu Navigasi Utama Admin -->
            <div class="px-4 pt-4 pb-1.5">
                <span class="font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 px-2">Menu Manajemen</span>
            </div>
            <nav class="px-3 space-y-1">
                <a href="<?= App::baseUrl('/admin/dashboard') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-bold transition-all <?= $currentUri === 'admin/dashboard' ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                    <span class="material-symbols-outlined text-base">dashboard</span>
                    <span>Dashboard &amp; Pengumuman</span>
                </a>
                <a href="<?= App::baseUrl('/admin/settings') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-bold transition-all <?= $currentUri === 'admin/settings' ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                    <span class="material-symbols-outlined text-base">tune</span>
                    <span>Kelola Konten (CMS)</span>
                </a>
                <a href="<?= App::baseUrl('/admin/proker') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-bold transition-all <?= $currentUri === 'admin/proker' ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                    <span class="material-symbols-outlined text-base">assignment</span>
                    <span>Program Kerja &amp; Progres</span>
                </a>
                <a href="<?= App::baseUrl('/admin/activities') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-bold transition-all <?= str_starts_with($currentUri, 'admin/activities') ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                    <span class="material-symbols-outlined text-base">photo_library</span>
                    <span>Kegiatan &amp; Album</span>
                </a>
                <a href="<?= App::baseUrl('/admin/articles') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-bold transition-all <?= str_starts_with($currentUri, 'admin/articles') ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                    <span class="material-symbols-outlined text-base">article</span>
                    <span>Artikel &amp; Sejarah</span>
                </a>
                <a href="<?= App::baseUrl('/admin/members') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-bold transition-all <?= $currentUri === 'admin/members' ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                    <span class="material-symbols-outlined text-base">group</span>
                    <span>Verifikasi Anggota</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Bottom Actions -->
        <div class="p-3 border-t border-slate-200 dark:border-slate-800 space-y-1.5 bg-slate-50 dark:bg-slate-900">
            <a href="<?= App::baseUrl('/') ?>" target="_blank" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-200/70 dark:hover:bg-slate-800 transition-colors">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">open_in_new</span>
                    Lihat Website Publik
                </span>
                <span class="material-symbols-outlined text-sm">arrow_outward</span>
            </a>
            <a href="<?= App::baseUrl('/logout') ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-200/70 dark:hover:bg-slate-800 transition-colors">
                <span class="material-symbols-outlined text-base">logout</span>
                <span>Keluar dari Sesi</span>
            </a>
        </div>
    </aside>

    <!-- Area Kerja Utama Admin -->
    <div class="flex-1 flex flex-col min-w-0 overflow-x-hidden">
        <!-- Top Header Bar Minimalis -->
        <?php
            $cleanNavTitle = trim(explode('—', (string) ($title ?? 'Dashboard'))[0]);
            $fullName = trim((string) ($currentUser['nama_lengkap'] ?? 'Admin'));
            $firstName = explode(' ', $fullName)[0] ?: 'Admin';
        ?>
        <header class="h-14 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-300 dark:border-slate-800 px-3 sm:px-6 flex items-center justify-between sticky top-0 z-30 transition-colors duration-200">
            <div class="flex items-center gap-2.5 min-w-0">
                <!-- Tombol Hamburger Minimalis (< lg) -->
                <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden w-8 h-8 rounded-lg text-slate-700 dark:text-slate-200 hover:bg-slate-200/70 dark:hover:bg-slate-800 flex items-center justify-center transition-colors" title="Menu Navigasi">
                    <span class="material-symbols-outlined text-xl">menu</span>
                </button>
                <h1 class="text-sm sm:text-base font-display font-bold text-slate-900 dark:text-slate-100 truncate"><?= e($cleanNavTitle) ?></h1>
            </div>

            <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0">
                <!-- Tombol Ganti Tema Minimalis -->
                <button type="button" onclick="toggleTheme()" class="w-8 h-8 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-200/70 dark:hover:bg-slate-800 transition-colors flex items-center justify-center" title="Ganti Mode Tampilan">
                    <span class="material-symbols-outlined text-[18px] theme-icon">dark_mode</span>
                </button>

                <!-- Profil Pengguna Minimalis (1 Base Warna) -->
                <a href="<?= App::baseUrl('/profil') ?>" class="h-8 px-2.5 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:border-slate-500 text-slate-800 dark:text-slate-200 flex items-center gap-1.5 text-xs font-semibold transition-colors" title="<?= e($fullName) ?>">
                    <span class="material-symbols-outlined text-[16px] text-slate-600 dark:text-slate-400">person</span>
                    <span class="sm:hidden max-w-[80px] truncate"><?= e($firstName) ?></span>
                    <span class="hidden sm:inline max-w-[140px] truncate"><?= e($fullName) ?></span>
                </a>
            </div>
        </header>

        <!-- Notifikasi Flash Admin -->
        <div class="px-4 sm:px-6 pt-4">
            <?php if (!empty($flashSuccess)): ?>
                <div class="rounded-xl flex items-start gap-3 p-4 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 shadow-sm text-slate-900 dark:text-slate-100">
                    <span class="material-symbols-outlined text-slate-700 dark:text-slate-300 text-xl">check_circle</span>
                    <div class="text-xs sm:text-sm font-semibold flex-1"><?= e($flashSuccess) ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($flashError)): ?>
                <div class="rounded-xl flex items-start gap-3 p-4 bg-white dark:bg-slate-900 border border-rose-500/70 shadow-sm text-slate-900 dark:text-slate-100">
                    <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-xl">error</span>
                    <div class="text-xs sm:text-sm font-semibold flex-1"><?= e($flashError) ?></div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Area Konten Halaman Admin -->
        <div class="p-4 sm:p-6 lg:p-8 flex-1">
            <?= $content ?>
        </div>
    </div>

    <!-- Bootstrap 5.3 Bundle JS & Script Drawer Admin -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('admin-sidebar-backdrop');
            if (!sidebar || !backdrop) return;

            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

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
                text.textContent = isDark ? 'Kertas Terang' : 'Abu Gelap';
            });
        }

        document.addEventListener('DOMContentLoaded', updateThemeIcons);

        // Heartbeat Keep-Alive: Menjaga sesi Admin/Pengurus tetap aktif selama online (memperbarui masa aktif 1 bulan)
        setInterval(() => {
            if (!document.hidden) {
                fetch('<?= App::baseUrl('/ping') ?>', {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                }).catch(() => {});
            }
        }, 600000);
    </script>
</body>
</html>
