<?php
use App\Config\App;
use App\Config\Security;

$csrfToken = Security::generateCsrfToken();
?>

<div class="max-w-4xl space-y-6">
    <div class="border-b border-slate-200 dark:border-slate-800 pb-4 transition-colors">
        <h2 class="text-xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">Pengaturan Konten &amp; Struktur Website (CMS)</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Ubah kata sambutan, narasi tentang K'mplang, dan tautan sosial media langsung tanpa menyentuh kode.</p>
    </div>

    <form action="<?= App::baseUrl('/admin/settings') ?>" method="POST" class="space-y-8">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <!-- Section 1: Hero Banner -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 space-y-4 shadow-sm dark:shadow-none transition-colors">
            <h3 class="font-bold text-sm text-amber-600 dark:text-amber-400 font-montserrat flex items-center gap-2">
                <span class="material-symbols-outlined text-base">web</span>
                1. Hero / Banner Utama Beranda
            </h3>
            <div class="space-y-3.5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Judul Utama (Hero Title)</label>
                    <input type="text" name="hero_title" value="<?= e($settings['hero_title'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Sub Judul (Hero Subtitle)</label>
                    <input type="text" name="hero_subtitle" value="<?= e($settings['hero_subtitle'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Singkat (Hero Description)</label>
                    <textarea name="hero_description" rows="2" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500"><?= e($settings['hero_description'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Section 2: Tentang Kmplang -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 space-y-4 shadow-sm dark:shadow-none transition-colors">
            <h3 class="font-bold text-sm text-amber-600 dark:text-amber-400 font-montserrat flex items-center gap-2">
                <span class="material-symbols-outlined text-base">info</span>
                2. Narasi Tentang &amp; Filosofi K'mplang
            </h3>
            <div class="space-y-3.5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Judul Bagian Tentang</label>
                    <input type="text" name="about_title" value="<?= e($settings['about_title'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Paragraf 1 (Latar Belakang)</label>
                    <textarea name="about_content_1" rows="3" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500"><?= e($settings['about_content_1'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Paragraf 2 (Filosofi Nama K'mplang)</label>
                    <textarea name="about_content_2" rows="3" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500"><?= e($settings['about_content_2'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Section 3: Sambutan Ketua -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 space-y-4 shadow-sm dark:shadow-none transition-colors">
            <h3 class="font-bold text-sm text-amber-600 dark:text-amber-400 font-montserrat flex items-center gap-2">
                <span class="material-symbols-outlined text-base">record_voice_over</span>
                3. Sambutan Ketua K'mplang
            </h3>
            <div class="space-y-3.5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama / Jabatan Ketua</label>
                    <input type="text" name="nama_ketua" value="<?= e($settings['nama_ketua'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Teks Sambutan</label>
                    <textarea name="sambutan_ketua" rows="3" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500"><?= e($settings['sambutan_ketua'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Section 4: Kontak & Media Sosial -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 space-y-4 shadow-sm dark:shadow-none transition-colors">
            <h3 class="font-bold text-sm text-amber-600 dark:text-amber-400 font-montserrat flex items-center gap-2">
                <span class="material-symbols-outlined text-base">contacts</span>
                4. Sekretariat &amp; Media Sosial
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Instagram URL</label>
                    <input type="text" name="social_instagram" value="<?= e($settings['social_instagram'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">TikTok URL</label>
                    <input type="text" name="social_tiktok" value="<?= e($settings['social_tiktok'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">WhatsApp Hotline</label>
                    <input type="text" name="contact_whatsapp" value="<?= e($settings['contact_whatsapp'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Email Resmi</label>
                    <input type="email" name="contact_email" value="<?= e($settings['contact_email'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Alamat Sekretariat</label>
                    <input type="text" name="address" value="<?= e($settings['address'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-8 py-3 rounded-xl font-bold text-xs bg-amber-500 hover:bg-amber-400 text-slate-950 shadow-md transition-all">
                Simpan Perubahan Konten
            </button>
        </div>
    </form>
</div>
