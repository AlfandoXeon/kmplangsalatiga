<?php
use App\Config\App;
$currentUri = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$scriptDir = trim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
if ($scriptDir !== '' && str_starts_with($currentUri, $scriptDir)) {
    $currentUri = trim(substr($currentUri, strlen($scriptDir)), '/');
}
?>

<nav id="main-nav" class="fixed top-0 left-0 right-0 z-50 transition-colors duration-200 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 shadow-sm dark:shadow-none">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Logo -->
            <a href="<?= App::baseUrl('/') ?>" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 p-2 border border-amber-500/30 group-hover:scale-105 transition-transform duration-200 flex items-center justify-center">
                    <img src="<?= App::baseUrl('/assets/images/siger.png') ?>" alt="Logo Siger" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="font-black text-xl tracking-wider text-amber-600 dark:text-amber-400 group-hover:opacity-90 transition-opacity font-montserrat">K'MPLANG</span>
                    <span class="text-[10px] uppercase tracking-widest text-slate-500 dark:text-slate-400 font-semibold">Salatiga</span>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                <a href="<?= App::baseUrl('/') ?>" class="px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-150 <?= $currentUri === '' ? 'text-amber-600 dark:text-amber-400 bg-amber-500/10 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' ?>">
                    Beranda
                </a>
                <a href="<?= App::baseUrl('/#tentang') ?>" class="px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 transition-all duration-150">
                    Tentang
                </a>
                <a href="<?= App::baseUrl('/program-kerja') ?>" class="px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-150 <?= $currentUri === 'program-kerja' ? 'text-amber-600 dark:text-amber-400 bg-amber-500/10 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' ?>">
                    Program Kerja
                </a>
                <a href="<?= App::baseUrl('/kegiatan') ?>" class="px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-150 <?= str_starts_with($currentUri, 'kegiatan') ? 'text-amber-600 dark:text-amber-400 bg-amber-500/10 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' ?>">
                    Dokumentasi
                </a>
                <a href="<?= App::baseUrl('/artikel') ?>" class="px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-150 <?= str_starts_with($currentUri, 'artikel') ? 'text-amber-600 dark:text-amber-400 bg-amber-500/10 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' ?>">
                    Artikel
                </a>
                <a href="<?= App::baseUrl('/anggota') ?>" class="px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-150 <?= $currentUri === 'anggota' ? 'text-amber-600 dark:text-amber-400 bg-amber-500/10 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' ?>">
                    Anggota
                </a>
                <a href="<?= App::baseUrl('/#musik-section') ?>" class="px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 transition-all duration-150">
                    Musik
                </a>
            </div>

            <!-- Right Actions (Theme Switcher + Auth Buttons) -->
            <div class="hidden md:flex items-center space-x-2">
                <!-- Theme Toggle Button -->
                <button type="button" onclick="toggleTheme()" class="p-2.5 rounded-xl text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center" title="Ganti Mode Tampilan (Terang/Gelap)">
                    <span class="material-symbols-outlined text-xl theme-icon">dark_mode</span>
                </button>

                <div class="h-5 w-px bg-slate-200 dark:bg-slate-800 mx-1"></div>

                <?php if (!empty($currentUser)): ?>
                    <div class="relative" id="profile-dropdown-container">
                        <button type="button" 
                                id="profile-dropdown-btn"
                                aria-haspopup="true" 
                                aria-expanded="false" 
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800/80 hover:bg-slate-200 dark:hover:bg-slate-700/80 border border-slate-200 dark:border-slate-700 transition-all cursor-pointer select-none">
                            <div class="w-7 h-7 rounded-full bg-amber-500 flex items-center justify-center text-slate-950 font-bold text-xs uppercase shadow-sm">
                                <?= substr($currentUser['nama_lengkap'], 0, 1) ?>
                            </div>
                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 max-w-[120px] truncate"><?= e($currentUser['nama_lengkap']) ?></span>
                            <span id="profile-arrow-icon" class="material-symbols-outlined text-slate-400 text-sm transition-transform duration-200">expand_more</span>
                        </button>
                        <div id="profile-dropdown-menu" 
                             class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-xl py-2 transition-all z-50">
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800">
                                <p class="text-[11px] text-slate-400">Masuk sebagai</p>
                                <p class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider"><?= e($currentUser['role']) ?></p>
                            </div>
                            <?php if ($isPengurus): ?>
                                <a href="<?= App::baseUrl('/admin/dashboard') ?>" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
                                    <span class="material-symbols-outlined text-base">dashboard</span>
                                    <span><?= ($currentUser['role'] ?? '') === 'admin' ? 'Panel Admin' : 'Panel Pengurus' ?></span>
                                </a>
                            <?php endif; ?>
                            <a href="<?= App::baseUrl('/profil') ?>" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
                                <span class="material-symbols-outlined text-base">person</span>
                                <span>Profil Saya</span>
                            </a>
                            <div class="border-t border-slate-100 dark:border-slate-800 my-1"></div>
                            <a href="<?= App::baseUrl('/logout') ?>" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                                <span class="material-symbols-outlined text-base">logout</span>
                                <span>Keluar</span>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <button type="button" onclick="openAuthModal('login')" class="text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 px-3 py-2 transition-colors">
                        Masuk
                    </button>
                    <button type="button" onclick="openAuthModal('register')" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-amber-500 hover:bg-amber-400 text-slate-950 shadow-sm hover:shadow transition-all">
                        <span class="material-symbols-outlined text-base">how_to_reg</span>
                        Daftar
                    </button>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu and Theme Button -->
            <div class="flex items-center gap-2 md:hidden">
                <button type="button" onclick="toggleTheme()" class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none" title="Ganti Mode Tampilan">
                    <span class="material-symbols-outlined text-xl theme-icon">dark_mode</span>
                </button>
                <button type="button" id="mobile-menu-btn" class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none">
                    <span class="material-symbols-outlined text-2xl">menu</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobile-menu" class="hidden md:hidden bg-white/98 dark:bg-slate-900/98 border-b border-slate-200 dark:border-slate-800 px-4 pt-2 pb-6 space-y-2">
        <a href="<?= App::baseUrl('/') ?>" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Beranda</a>
        <a href="<?= App::baseUrl('/#tentang') ?>" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Tentang</a>
        <a href="<?= App::baseUrl('/program-kerja') ?>" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Program Kerja</a>
        <a href="<?= App::baseUrl('/kegiatan') ?>" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Dokumentasi</a>
        <a href="<?= App::baseUrl('/artikel') ?>" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Artikel</a>
        <a href="<?= App::baseUrl('/anggota') ?>" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Anggota</a>
        <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-2">
            <?php if (!empty($currentUser)): ?>
                <?php if ($isPengurus): ?>
                    <a href="<?= App::baseUrl('/admin/dashboard') ?>" class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-semibold text-amber-600 dark:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800">
                        <span class="material-symbols-outlined text-base">dashboard</span> <?= ($currentUser['role'] ?? '') === 'admin' ? 'Panel Admin' : 'Panel Pengurus' ?>
                    </a>
                <?php endif; ?>
                <a href="<?= App::baseUrl('/profil') ?>" class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <span class="material-symbols-outlined text-base">person</span> Profil (<?= e($currentUser['nama_lengkap']) ?>)
                </a>
                <a href="<?= App::baseUrl('/logout') ?>" class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10">
                    <span class="material-symbols-outlined text-base">logout</span> Keluar
                </a>
            <?php else: ?>
                <button type="button" onclick="openAuthModal('login'); document.getElementById('mobile-menu').classList.add('hidden');" class="block w-full text-center px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700">Masuk</button>
                <button type="button" onclick="openAuthModal('register'); document.getElementById('mobile-menu').classList.add('hidden');" class="block w-full text-center px-4 py-2.5 rounded-xl text-sm font-bold text-slate-950 bg-amber-500 hover:bg-amber-400 shadow-sm">Daftar Anggota</button>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    // Mobile Drawer Toggle
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Profile Dropdown Click-Toggle
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

        // Close when clicking outside
        document.addEventListener('click', (e) => {
            if (profileContainer && !profileContainer.contains(e.target)) {
                profileMenu.classList.add('hidden');
                profileBtn.setAttribute('aria-expanded', 'false');
                if (profileArrow) profileArrow.classList.remove('rotate-180');
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !profileMenu.classList.contains('hidden')) {
                profileMenu.classList.add('hidden');
                profileBtn.setAttribute('aria-expanded', 'false');
                if (profileArrow) profileArrow.classList.remove('rotate-180');
            }
        });
    }
</script>
