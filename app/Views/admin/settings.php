<?php
/**
 * View: admin/settings.php
 * -------------------------------------------------------------------------
 * Pengaturan Konten & Struktur Website (CMS).
 * -------------------------------------------------------------------------
 */
use App\Config\App;
use App\Config\Security;

$csrfToken = Security::generateCsrfToken();
$settings = $settings ?? [];
?>

<div class="max-w-4xl space-y-6">
    <div class="border-b-2 border-slate-900 dark:border-slate-700 pb-4">
        <div class="poster-kicker">Content Management System (CMS)</div>
        <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white">Pengaturan Narasi &amp; Identitas Website</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Ubah headline poster beranda, filosofi organisasi, sambutan ketua, dan kontak resmi secara instan.</p>
    </div>

    <form action="<?= App::baseUrl('/admin/settings') ?>" method="POST" class="space-y-6">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <!-- Section 1: Hero Banner -->
        <div class="poster-card p-6 space-y-4">
            <h3 class="font-display font-bold text-base text-slate-900 dark:text-slate-100 flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-3">
                <span class="material-symbols-outlined text-slate-700 dark:text-slate-300">web</span>
                1. Poster Utama Beranda (Hero Section)
            </h3>
            <div class="space-y-3.5 text-xs">
                <div>
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Judul Utama (Hero Title)</label>
                    <input type="text" name="hero_title" value="<?= e($settings['hero_title'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
                </div>
                <div>
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Sub Judul (Hero Subtitle)</label>
                    <input type="text" name="hero_subtitle" value="<?= e($settings['hero_subtitle'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
                </div>
                <div>
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Deskripsi Singkat (Hero Description)</label>
                    <textarea name="hero_description" rows="2" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5"><?= e($settings['hero_description'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Section 2: Tentang Kmplang -->
        <div class="poster-card p-6 space-y-4">
            <h3 class="font-display font-bold text-base text-slate-900 dark:text-slate-100 flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-3">
                <span class="material-symbols-outlined text-slate-700 dark:text-slate-300">info</span>
                2. Narasi Tentang &amp; Filosofi K'mplang
            </h3>
            <div class="space-y-3.5 text-xs">
                <div>
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Judul Bagian Tentang</label>
                    <input type="text" name="about_title" value="<?= e($settings['about_title'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
                </div>
                <div>
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Paragraf 1 (Latar Belakang)</label>
                    <textarea name="about_content_1" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5"><?= e($settings['about_content_1'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Paragraf 2 (Filosofi Nama K'mplang)</label>
                    <textarea name="about_content_2" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5"><?= e($settings['about_content_2'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Section 3: Sambutan Ketua -->
        <div class="poster-card p-6 space-y-4">
            <h3 class="font-display font-bold text-base text-slate-900 dark:text-slate-100 flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-3">
                <span class="material-symbols-outlined text-slate-700 dark:text-slate-300">record_voice_over</span>
                3. Sambutan Ketua K'mplang
            </h3>
            <div class="space-y-3.5 text-xs">
                <div>
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Nama / Jabatan Ketua</label>
                    <input type="text" name="nama_ketua" value="<?= e($settings['nama_ketua'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
                </div>
                <div>
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Teks Sambutan</label>
                    <textarea name="sambutan_ketua" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5"><?= e($settings['sambutan_ketua'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Section 4: Kontak & Media Sosial -->
        <div class="poster-card p-6 space-y-4">
            <h3 class="font-display font-bold text-base text-slate-900 dark:text-slate-100 flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-3">
                <span class="material-symbols-outlined text-slate-700 dark:text-slate-300">contacts</span>
                4. Sekretariat &amp; Media Sosial
            </h3>
            <div class="row g-3 text-xs">
                <div class="col-12 col-sm-6">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Instagram URL</label>
                    <input type="text" name="social_instagram" value="<?= e($settings['social_instagram'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
                </div>
                <div class="col-12 col-sm-6">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">TikTok URL</label>
                    <input type="text" name="social_tiktok" value="<?= e($settings['social_tiktok'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
                </div>
                <div class="col-12 col-sm-6">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">WhatsApp Hotline</label>
                    <input type="text" name="contact_whatsapp" value="<?= e($settings['contact_whatsapp'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
                </div>
                <div class="col-12 col-sm-6">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Email Resmi</label>
                    <input type="email" name="contact_email" value="<?= e($settings['contact_email'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
                </div>
                <div class="col-12">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Alamat Sekretariat</label>
                    <input type="text" name="address" value="<?= e($settings['address'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="poster-btn-primary px-8 py-3 rounded-lg text-xs">
                <span class="material-symbols-outlined text-base">save</span>
                Simpan Perubahan Konten
            </button>
        </div>
    </form>
</div>
