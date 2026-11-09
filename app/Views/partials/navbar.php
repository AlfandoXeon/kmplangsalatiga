<?php
/**
 * ============================================================================
 * Partial Navbar Utama (navbar.php)
 * ============================================================================
 * Navigasi bergaya Editorial Poster dengan dukungan penuh Desktop & Mobile
 * Drawer serta sakelar mode warna (White-Yellow Terang / Dark Grey Gelap).
 * ============================================================================
 */
use App\Config\App;

$currentUri = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($scriptDir !== '' && str_starts_with($currentUri, $scriptDir)) {
    $currentUri = trim(substr($currentUri, strlen($scriptDir)), '/');
}
?>

<nav id="main-nav" class="fixed top-0 left-0 right-0 z-50 transition-colors duration-200 bg-slate-50/95 dark:bg-slate-900/95 backdrop-blur-md border-b-2 border-slate-900 dark:border-slate-700 text-slate-900 dark:text-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Identity (Logo Siger + Tipografi Poster) -->
            <a href="<?= App::baseUrl('/') ?>" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 p-1.5 border border-slate-300 dark:border-slate-700 flex items-center justify-center">
                    <img src="<?= App::baseUrl('/assets/images/siger.png') ?>" alt="Logo Siger Lampung" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="font-poster font-black text-xl tracking-tight text-slate-900 dark:text-white leading-none">K'MPLANG</span>
                    <span class="font-mono text-[10px] uppercase tracking-widest text-slate-500 dark:text-slate-400 font-bold mt-1">Salatiga &bull; Lampung</span>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <div class="hidden lg:flex items-center space-x-1">
                <a href="<?= App::baseUrl('/') ?>" class="px-3.5 py-2 rounded-lg text-xs font-extrabold uppercase tracking-wider transition-all duration-150 <?= $currentUri === '' ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                    Beranda
                </a>
                <a href="<?= App::baseUrl('/#tentang') ?>" class="px-3.5 py-2 rounded-lg text-xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-150">
                    Filosofi
                </a>
                <a href="<?= App::baseUrl('/program-kerja') ?>" class="px-3.5 py-2 rounded-lg text-xs font-extrabold uppercase tracking-wider transition-all duration-150 <?= $currentUri === 'program-kerja' ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                    Proker
                </a>
                <a href="<?= App::baseUrl('/kegiatan') ?>" class="px-3.5 py-2 rounded-lg text-xs font-extrabold uppercase tracking-wider transition-all duration-150 <?= str_starts_with($currentUri, 'kegiatan') ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                    Dokumentasi
                </a>
                <a href="<?= App::baseUrl('/artikel') ?>" class="px-3.5 py-2 rounded-lg text-xs font-extrabold uppercase tracking-wider transition-all duration-150 <?= str_starts_with($currentUri, 'artikel') ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                    Artikel Budaya
                </a>
                <a href="<?= App::baseUrl('/anggota') ?>" class="px-3.5 py-2 rounded-lg text-xs font-extrabold uppercase tracking-wider transition-colors duration-150 <?= $currentUri === 'anggota' ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                    Direktori
                </a>
            </div>

            <!-- Right Actions (Theme Switcher + User Profile / Auth Buttons) -->
            <div class="hidden lg:flex items-center space-x-2.5">
                <!-- Theme Toggle Button -->
                <button type="button" onclick="toggleTheme()" class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 hover:border-slate-500 transition-all flex items-center justify-center" title="Ganti Mode Tampilan">
                    <span class="material-symbols-outlined text-lg theme-icon">dark_mode</span>
                </button>

                <?php if (!empty($currentUser)): ?>
                    <div class="relative" id="profile-dropdown-container">
                        <button type="button"
                                id="profile-dropdown-btn"
                                aria-haspopup="true"
                                aria-expanded="false"
                                class="flex items-center gap-2 px-3 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:border-slate-500 transition-all cursor-pointer select-none">
                            <span class="material-symbols-outlined text-[18px] text-slate-600 dark:text-slate-400">person</span>
                            <span class="text-xs font-bold text-slate-900 dark:text-slate-100 max-w-[120px] truncate"><?= e($currentUser['nama_lengkap']) ?></span>
                            <span id="profile-arrow-icon" class="material-symbols-outlined text-slate-500 text-sm transition-transform duration-200">expand_more</span>
                        </button>
                        <div id="profile-dropdown-menu"
                             class="hidden absolute right-0 mt-2.5 w-60 poster-card bg-white dark:bg-slate-900 py-2 z-50">
                            <div class="px-4 py-2.5 border-b border-slate-200 dark:border-slate-800 bg-slate-100/70 dark:bg-slate-800/50">
                                <p class="font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Akses</p>
                                <p class="text-xs font-extrabold text-slate-900 dark:text-slate-100 uppercase tracking-wider"><?= e($currentUser['jabatan_organisasi'] ?? $currentUser['role']) ?></p>
                            </div>
                            <?php if ($isPengurus): ?>
                                <a href="<?= App::baseUrl('/admin/dashboard') ?>" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                    <span class="material-symbols-outlined text-base text-slate-600 dark:text-slate-400">dashboard</span>
                                    <span><?= ($currentUser['role'] ?? '') === 'admin' ? 'Panel Redaksi Admin' : 'Panel Pengurus' ?></span>
                                </a>
                            <?php endif; ?>
                            <a href="<?= App::baseUrl('/profil') ?>" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <span class="material-symbols-outlined text-base text-slate-600 dark:text-slate-400">person</span>
                                <span>Profil &amp; Pengaturan Akun</span>
                            </a>
                            <div class="border-t border-slate-200 dark:border-slate-800 my-1"></div>
                            <a href="<?= App::baseUrl('/logout') ?>" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <span class="material-symbols-outlined text-base">logout</span>
                                <span>Keluar</span>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <button type="button" onclick="openAuthModal('login')" class="poster-btn-secondary !py-2 !px-3.5 !text-xs">
                        Masuk
                    </button>
                    <button type="button" onclick="openAuthModal('register')" class="poster-btn-primary !py-2 !px-4 !text-xs">
                        <span class="material-symbols-outlined text-base">how_to_reg</span>
                        <span>Daftar</span>
                    </button>
                <?php endif; ?>
            </div>

            <!-- Mobile / Tablet Menu & Theme Switcher (< lg) -->
            <div class="flex items-center gap-2 lg:hidden">
                <button type="button" onclick="toggleTheme()" class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 flex items-center justify-center" title="Ganti Mode Tampilan">
                    <span class="material-symbols-outlined text-lg theme-icon">dark_mode</span>
                </button>
                <button type="button" id="mobile-menu-btn" class="w-9 h-9 rounded-lg bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 flex items-center justify-center" aria-label="Buka Menu Navigasi">
                    <span class="material-symbols-outlined text-xl">menu</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobile-menu" class="hidden lg:hidden bg-slate-50 dark:bg-slate-900 border-b border-slate-300 dark:border-slate-800 px-4 pt-3 pb-6 space-y-2 shadow-xl">
        <a href="<?= App::baseUrl('/') ?>" class="block px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-900 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800">Beranda</a>
        <a href="<?= App::baseUrl('/#tentang') ?>" class="block px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-900 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800">Filosofi &amp; Sambutan</a>
        <a href="<?= App::baseUrl('/program-kerja') ?>" class="block px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-900 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800">Transparansi Program Kerja</a>
        <a href="<?= App::baseUrl('/kegiatan') ?>" class="block px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-900 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800">Album Dokumentasi</a>
        <a href="<?= App::baseUrl('/artikel') ?>" class="block px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-900 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800">Artikel &amp; Budaya Lampung</a>
        <a href="<?= App::baseUrl('/anggota') ?>" class="block px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-900 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800">Direktori Anggota</a>

        <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-2">
            <?php if (!empty($currentUser)): ?>
                <?php if ($isPengurus): ?>
                    <a href="<?= App::baseUrl('/admin/dashboard') ?>" class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950">
                        <span class="material-symbols-outlined text-base">dashboard</span>
                        <span><?= ($currentUser['role'] ?? '') === 'admin' ? 'Panel Admin' : 'Panel Pengurus' ?></span>
                    </a>
                <?php endif; ?>
                <a href="<?= App::baseUrl('/profil') ?>" class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700">
                    <span class="material-symbols-outlined text-base">person</span>
                    <span>Profil (<?= e($currentUser['nama_lengkap']) ?>)</span>
                </a>
                <a href="<?= App::baseUrl('/logout') ?>" class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100">
                    <span class="material-symbols-outlined text-base">logout</span>
                    <span>Keluar</span>
                </a>
            <?php else: ?>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="openAuthModal('login'); document.getElementById('mobile-menu').classList.add('hidden');" class="poster-btn-secondary w-full !py-2.5">Masuk</button>
                    <button type="button" onclick="openAuthModal('register'); document.getElementById('mobile-menu').classList.add('hidden');" class="poster-btn-primary w-full !py-2.5">Daftar</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    const profileBtn = document.getElementById('profile-dropdown-btn');
    const profileMenu = document.getElementById('profile-dropdown-menu');
    const profileArrow = document.getElementById('profile-arrow-icon');
    const profileContainer = document.getElementById('profile-dropdown-container');

    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isHidden = profileMenu.classList.contains('hidden');
            if (isHidden) {
                profileMenu.classList.remove('hidden');
                profileBtn.setAttribute('aria-expanded', 'true');
                if (profileArrow) profileArrow.classList.add('rotate-180');
            } else {
                profileMenu.classList.add('hidden');
                profileBtn.setAttribute('aria-expanded', 'false');
                if (profileArrow) profileArrow.classList.remove('rotate-180');
            }
        });

        document.addEventListener('click', (e) => {
            if (profileContainer && !profileContainer.contains(e.target)) {
                profileMenu.classList.add('hidden');
                profileBtn.setAttribute('aria-expanded', 'false');
                if (profileArrow) profileArrow.classList.remove('rotate-180');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !profileMenu.classList.contains('hidden')) {
                profileMenu.classList.add('hidden');
                profileBtn.setAttribute('aria-expanded', 'false');
                if (profileArrow) profileArrow.classList.remove('rotate-180');
            }
        });
    }
</script>
