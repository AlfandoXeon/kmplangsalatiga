<?php
use App\Config\App;
use App\Config\Security;

$csrfToken = Security::generateCsrfToken();
?>

<div class="max-w-3xl space-y-6">
    <div class="border-b border-slate-200 dark:border-slate-800 pb-4 transition-colors">
        <h2 class="text-xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">Edit Artikel</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Perbarui judul, kategori, sampul, atau konten artikel.</p>
    </div>

    <form action="<?= App::baseUrl('/admin/articles/update/' . $article['id']) ?>" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 space-y-5 shadow-sm dark:shadow-none transition-colors">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Judul Artikel <span class="text-rose-500">*</span></label>
            <input type="text" name="judul" value="<?= e($article['judul']) ?>" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kategori</label>
                <select name="kategori" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500 cursor-pointer">
                    <option value="Budaya" <?= $article['kategori'] === 'Budaya' ? 'selected' : '' ?>>Budaya</option>
                    <option value="Sejarah" <?= $article['kategori'] === 'Sejarah' ? 'selected' : '' ?>>Sejarah</option>
                    <option value="Berita" <?= $article['kategori'] === 'Berita' ? 'selected' : '' ?>>Berita Organisasi</option>
                    <option value="Opini" <?= $article['kategori'] === 'Opini' ? 'selected' : '' ?>>Opini / Artikel Anggota</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Status Publikasi</label>
                <select name="status" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500 cursor-pointer">
                    <option value="published" <?= $article['status'] === 'published' ? 'selected' : '' ?>>Publikasikan (Published)</option>
                    <option value="draft" <?= $article['status'] === 'draft' ? 'selected' : '' ?>>Draf (Draft)</option>
                </select>
            </div>
        </div>

        <div class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl space-y-3">
            <label class="block text-xs font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-amber-500 text-base">image</span>
                Foto Sampul Artikel (Cover Image)
            </label>
            <?php if (!empty($article['cover_image'])): ?>
                <div class="flex items-center gap-3">
                    <img src="<?= App::mediaUrl($article['cover_image'], 'articles', 'LampungThumb.jpg') ?>" class="w-20 h-14 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
                    <span class="text-xs text-slate-500 dark:text-slate-400">Sampul saat ini</span>
                </div>
            <?php endif; ?>
            <input type="file" name="cover" accept="image/*" class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-200 dark:file:bg-slate-800 file:text-slate-800 dark:file:text-amber-400 hover:file:bg-slate-300 dark:hover:file:bg-slate-700 cursor-pointer">
            <span class="text-[11px] text-slate-500 block">Pilih berkas baru jika ingin mengganti cover artikel.</span>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Isi Artikel <span class="text-rose-500">*</span></label>
            <textarea name="konten" rows="8" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-3 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500"><?= e($article['konten']) ?></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
            <a href="<?= App::baseUrl('/admin/articles') ?>" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-sm transition-all">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
