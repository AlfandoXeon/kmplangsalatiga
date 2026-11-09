<?php
/**
 * ============================================================================
 * Partial Footer Editorial & Kolofon Organisasi (footer.php)
 * ============================================================================
 */
use App\Config\App;

$settingModel = new \App\Models\SiteSetting();
$ig = $settingModel->get('social_instagram', 'https://www.instagram.com/k_mplangsalatiga');
$tt = $settingModel->get('social_tiktok', 'https://www.tiktok.com/@kmplangsalatiga');
$wa = $settingModel->get('contact_whatsapp', '085225216552');
$email = $settingModel->get('contact_email', 'sekretariat@kmplang.org');
$addr = $settingModel->get('address', 'Salatiga, Jawa Tengah, Indonesia');
?>

<footer class="bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-t-2 border-slate-900 dark:border-slate-700 pt-16 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Poster Colophon Header -->
        <div class="pb-10 mb-12 border-b border-slate-300 dark:border-slate-800 flex flex-col lg:flex-row lg:items-end justify-between gap-6">
            <div>
                <span class="font-mono text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 block mb-2">
                    SANG BUMI RUWA JURAI &bull; SALATIGA
                </span>
                <h2 class="font-poster font-black text-3xl sm:text-4xl text-slate-900 dark:text-white tracking-tight">
                    Keluarga Mahasiswa Perantauan Lampung
                </h2>
            </div>
            <div class="poster-stamp-badge">
                <span>PORTAL RESMI ORGANISASI</span>
                <span>PERIODE BAKTI 2026 / 2027</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            <!-- Col 1: Brand & Manifesto -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-200 dark:bg-slate-800 p-2 border border-slate-400 dark:border-slate-700 flex items-center justify-center">
                        <img src="<?= App::baseUrl('/assets/images/siger.png') ?>" alt="Siger Lampung" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h3 class="font-poster font-black text-lg text-slate-900 dark:text-white">K'MPLANG</h3>
                        <p class="font-mono text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase">Salatiga, Jawa Tengah</p>
                    </div>
                </div>
                <p class="text-sm leading-relaxed text-slate-700 dark:text-slate-300">
                    Menjadi rumah kedua di tanah rantau, merawat falsafah <em>Piil Pesenggiri</em>, serta mempererat solidaritas mahasiswa asal Provinsi Lampung di Kota Salatiga.
                </p>
                <div class="pt-2 flex items-center gap-2.5">
                    <a href="<?= e($ig) ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-200 border border-slate-300 dark:border-slate-700 flex items-center justify-center" title="Instagram">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="<?= e($tt) ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-200 border border-slate-300 dark:border-slate-700 flex items-center justify-center" title="TikTok">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298-.002.595.042.88.13V9.4a6.33 6.33 0 0 0-1-.08A6.34 6.34 0 0 0 3 15.66a6.34 6.34 0 0 0 10.82 4.48 6.27 6.27 0 0 0 1.86-4.47V8.71a8.3 8.3 0 0 0 5.07 1.71v-3.45a4.85 4.85 0 0 1-1.16-.28z"/>
                        </svg>
                    </a>
                    <a href="https://wa.me/<?= preg_replace('/[^\d]/', '', $wa) ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-200 border border-slate-300 dark:border-slate-700 flex items-center justify-center" title="WhatsApp">
                        <span class="material-symbols-outlined text-lg">chat</span>
                    </a>
                    <a href="mailto:<?= e($email) ?>" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-200 border border-slate-300 dark:border-slate-700 flex items-center justify-center" title="Email">
                        <span class="material-symbols-outlined text-lg">mail</span>
                    </a>
                </div>
            </div>

            <!-- Col 2: Indeks Rubrik -->
            <div>
                <h4 class="font-mono text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-4 border-b border-slate-300 dark:border-slate-700 pb-2">01 // Indeks Rubrik</h4>
                <ul class="space-y-2.5 text-sm font-medium">
                    <li>
                        <a href="<?= App::baseUrl('/') ?>" class="flex items-center gap-2 text-slate-700 dark:text-slate-300 hover:underline">
                            <span class="font-mono text-xs text-slate-500">01.</span> Beranda Utama
                        </a>
                    </li>
                    <li>
                        <a href="<?= App::baseUrl('/#tentang') ?>" class="flex items-center gap-2 text-slate-700 dark:text-slate-300 hover:underline">
                            <span class="font-mono text-xs text-slate-500">02.</span> Filosofi &amp; Sambutan
                        </a>
                    </li>
                    <li>
                        <a href="<?= App::baseUrl('/program-kerja') ?>" class="flex items-center gap-2 text-slate-700 dark:text-slate-300 hover:underline">
                            <span class="font-mono text-xs text-slate-500">03.</span> Transparansi Program Kerja
                        </a>
                    </li>
                    <li>
                        <a href="<?= App::baseUrl('/kegiatan') ?>" class="flex items-center gap-2 text-slate-700 dark:text-slate-300 hover:underline">
                            <span class="font-mono text-xs text-slate-500">04.</span> Arsip Album Dokumentasi
                        </a>
                    </li>
                    <li>
                        <a href="<?= App::baseUrl('/artikel') ?>" class="flex items-center gap-2 text-slate-700 dark:text-slate-300 hover:underline">
                            <span class="font-mono text-xs text-slate-500">05.</span> Khazanah Budaya Lampung
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Col 3: Keanggotaan -->
            <div>
                <h4 class="font-mono text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-4 border-b border-slate-300 dark:border-slate-700 pb-2">02 // Layanan Anggota</h4>
                <ul class="space-y-2.5 text-sm font-medium">
                    <?php if (!empty($currentUser)): ?>
                        <li>
                            <a href="<?= App::baseUrl('/profil') ?>" class="flex items-center gap-2 text-slate-700 dark:text-slate-300 hover:underline">
                                <span class="material-symbols-outlined text-base text-slate-500">person</span> Profil &amp; Pengaturan Akun
                            </a>
                        </li>
                        <li>
                            <a href="<?= App::baseUrl('/anggota') ?>" class="flex items-center gap-2 text-slate-700 dark:text-slate-300 hover:underline">
                                <span class="material-symbols-outlined text-base text-slate-500">badge</span> Direktori Anggota Resmi
                            </a>
                        </li>
                        <?php if (!empty($isPengurus)): ?>
                            <li>
                                <a href="<?= App::baseUrl('/admin/dashboard') ?>" class="flex items-center gap-2 text-slate-700 dark:text-slate-300 hover:underline">
                                    <span class="material-symbols-outlined text-base text-slate-500">dashboard</span> Panel Pengurus &amp; Admin
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li>
                            <a href="<?= App::baseUrl('/register') ?>" onclick="if(window.openAuthModal){event.preventDefault();openAuthModal('register');}" class="flex items-center gap-2 text-slate-700 dark:text-slate-300 hover:underline cursor-pointer">
                                <span class="material-symbols-outlined text-base text-slate-500">how_to_reg</span> Registrasi Anggota Baru
                            </a>
                        </li>
                        <li>
                            <a href="<?= App::baseUrl('/anggota') ?>" class="flex items-center gap-2 text-slate-700 dark:text-slate-300 hover:underline">
                                <span class="material-symbols-outlined text-base text-slate-500">badge</span> Direktori Anggota Resmi
                            </a>
                        </li>
                        <li>
                            <a href="<?= App::baseUrl('/login') ?>" onclick="if(window.openAuthModal){event.preventDefault();openAuthModal('login');}" class="flex items-center gap-2 text-slate-700 dark:text-slate-300 hover:underline cursor-pointer">
                                <span class="material-symbols-outlined text-base text-slate-500">login</span> Masuk Portal Anggota
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Col 4: Sekretariat -->
            <div>
                <h4 class="font-mono text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-4 border-b border-slate-300 dark:border-slate-700 pb-2">03 // Sekretariat</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-slate-500 text-lg mt-0.5">location_on</span>
                        <span><?= e($addr) ?></span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-slate-500 text-lg">call</span>
                        <a href="https://wa.me/<?= preg_replace('/[^\d]/', '', $wa) ?>" target="_blank" rel="noopener noreferrer" class="font-mono hover:underline"><?= e($wa) ?></a>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-slate-500 text-lg">mail</span>
                        <a href="mailto:<?= e($email) ?>" class="font-mono text-xs hover:underline"><?= e($email) ?></a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-300 dark:border-slate-800 pt-6 flex flex-col sm:flex-row items-center justify-between font-mono text-xs text-slate-600 dark:text-slate-400 gap-4">
            <p>&copy; <?= date('Y') ?> K'MPLANG SALATIGA &bull; HAK CIPTA DILINDUNGI</p>
            <p class="uppercase tracking-wider">TABIK PUN &bull; SANG BUMI RUWA JURAI</p>
        </div>
    </div>
</footer>
