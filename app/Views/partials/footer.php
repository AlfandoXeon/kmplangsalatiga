<?php
use App\Config\App;
$settingModel = new \App\Models\SiteSetting();
$ig = $settingModel->get('social_instagram', 'https://www.instagram.com/k_mplangsalatiga');
$tt = $settingModel->get('social_tiktok', 'https://www.tiktok.com/@kmplangsalatiga');
$wa = $settingModel->get('contact_whatsapp', '085225216552');
$email = $settingModel->get('contact_email', 'sekretariat@kmplang.org');
$addr = $settingModel->get('address', 'Salatiga, Jawa Tengah, Indonesia');
?>

<footer class="bg-slate-100 dark:bg-slate-950 text-slate-600 dark:text-slate-400 border-t border-slate-200 dark:border-slate-800 pt-16 pb-12 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            <!-- Col 1: Brand & Bio -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 p-2 border border-amber-500/30 flex items-center justify-center">
                        <img src="<?= App::baseUrl('/assets/images/siger.png') ?>" alt="Siger Lampung" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h3 class="font-black text-lg text-amber-600 dark:text-amber-400 tracking-wide font-montserrat">K'MPLANG</h3>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-300">Salatiga, Jawa Tengah</p>
                    </div>
                </div>
                <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                    Keluarga Mahasiswa Perantauan Lampung di Kota Salatiga. Menjadi rumah kedua di tanah rantau, wadah persaudaraan, dan penjaga keluhuran budaya Sang Bumi Ruwa Jurai.
                </p>
                <div class="pt-2 flex items-center gap-2.5">
                    <!-- Instagram -->
                    <a href="<?= e($ig) ?>" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-amber-500 hover:text-slate-950 dark:hover:bg-amber-500 dark:hover:text-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center transition-all shadow-sm hover:scale-105" title="Instagram (@k_mplangsalatiga)">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <!-- TikTok -->
                    <a href="<?= e($tt) ?>" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-amber-500 hover:text-slate-950 dark:hover:bg-amber-500 dark:hover:text-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center transition-all shadow-sm hover:scale-105" title="TikTok (@kmplangsalatiga)">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298-.002.595.042.88.13V9.4a6.33 6.33 0 0 0-1-.08A6.34 6.34 0 0 0 3 15.66a6.34 6.34 0 0 0 10.82 4.48 6.27 6.27 0 0 0 1.86-4.47V8.71a8.3 8.3 0 0 0 5.07 1.71v-3.45a4.85 4.85 0 0 1-1.16-.28z"/>
                        </svg>
                    </a>
                    <!-- WhatsApp -->
                    <a href="https://wa.me/<?= preg_replace('/[^\d]/', '', $wa) ?>" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-amber-500 hover:text-slate-950 dark:hover:bg-amber-500 dark:hover:text-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center transition-all shadow-sm hover:scale-105" title="WhatsApp (<?= e($wa) ?>)">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                    </a>
                    <!-- Email -->
                    <a href="mailto:<?= e($email) ?>" class="w-9 h-9 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-amber-500 hover:text-slate-950 dark:hover:bg-amber-500 dark:hover:text-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center transition-all shadow-sm hover:scale-105" title="Email (<?= e($email) ?>)">
                        <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect width="20" height="16" x="2" y="4" rx="3"/>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider mb-4 border-b border-amber-500/30 pb-2">Navigasi Utama</h4>
                <ul class="space-y-2.5 text-sm">
                    <li>
                        <a href="<?= App::baseUrl('/') ?>" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs text-amber-500">chevron_right</span> Beranda
                        </a>
                    </li>
                    <li>
                        <a href="<?= App::baseUrl('/#tentang') ?>" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs text-amber-500">chevron_right</span> Profil &amp; Filosofi
                        </a>
                    </li>
                    <li>
                        <a href="<?= App::baseUrl('/program-kerja') ?>" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs text-amber-500">chevron_right</span> Transparansi Program Kerja
                        </a>
                    </li>
                    <li>
                        <a href="<?= App::baseUrl('/kegiatan') ?>" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs text-amber-500">chevron_right</span> Dokumentasi Kegiatan
                        </a>
                    </li>
                    <li>
                        <a href="<?= App::baseUrl('/artikel') ?>" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs text-amber-500">chevron_right</span> Artikel &amp; Wawasan Budaya
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Col 3: Keanggotaan & Layanan -->
            <div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider mb-4 border-b border-amber-500/30 pb-2">Layanan Organisasi</h4>
                <ul class="space-y-2.5 text-sm">
                    <li>
                        <a href="<?= App::baseUrl('/register') ?>" onclick="if(window.openAuthModal){event.preventDefault();openAuthModal('register');}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors flex items-center gap-2 cursor-pointer">
                            <span class="material-symbols-outlined text-xs text-amber-500">how_to_reg</span> Registrasi Anggota Baru
                        </a>
                    </li>
                    <li>
                        <a href="<?= App::baseUrl('/anggota') ?>" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs text-amber-500">badge</span> Direktori Anggota Resmi
                        </a>
                    </li>
                    <li>
                        <a href="<?= App::baseUrl('/#musik-section') ?>" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs text-amber-500">music_note</span> Pemutar Musik Lampung
                        </a>
                    </li>
                    <li>
                        <a href="<?= App::baseUrl('/login') ?>" onclick="if(window.openAuthModal){event.preventDefault();openAuthModal('login');}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors flex items-center gap-2 cursor-pointer">
                            <span class="material-symbols-outlined text-xs text-amber-500">login</span> Masuk ke Akun
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Col 4: Sekretariat & Kontak -->
            <div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider mb-4 border-b border-amber-500/30 pb-2">Sekretariat</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-lg mt-0.5">location_on</span>
                        <span><?= e($addr) ?></span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-lg">call</span>
                        <a href="https://wa.me/<?= preg_replace('/[^\d]/', '', $wa) ?>" target="_blank" rel="noopener noreferrer" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors"><?= e($wa) ?></a>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-lg">mail</span>
                        <a href="mailto:<?= e($email) ?>" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors"><?= e($email) ?></a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-200 dark:border-slate-800 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
            <p>&copy; <?= date('Y') ?> K'mplang Salatiga. Seluruh hak cipta dilindungi.</p>
            <p class="flex items-center gap-2 text-slate-500">
                <span class="material-symbols-outlined text-sm text-amber-500">favorite</span>
                <span>Rumah Mahasiswa Perantau Lampung di Kota Salatiga</span>
            </p>
        </div>
    </div>
</footer>
