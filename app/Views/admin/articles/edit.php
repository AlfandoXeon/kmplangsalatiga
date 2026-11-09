<?php
/**
 * View: admin/articles/edit.php
 * -------------------------------------------------------------------------
 * Formulir Edit Artikel & Esai Budaya.
 * -------------------------------------------------------------------------
 */
use App\Config\App;
use App\Config\Security;

$csrfToken = Security::generateCsrfToken();
$article = $article ?? [];
?>

<div class="max-w-3xl space-y-6">
    <div class="border-b-2 border-slate-900 dark:border-slate-700 pb-4">
        <div class="poster-kicker">Redaksi Khazanah Lampung</div>
        <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white">Edit Artikel</h2>
    </div>

    <form action="<?= App::baseUrl('/admin/articles/update/' . $article['id']) ?>" method="POST" enctype="multipart/form-data" class="poster-card p-6 sm:p-8 space-y-5 text-xs">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div>
            <label class="block font-mono uppercase text-[10px] font-bold mb-1">Judul Artikel *</label>
            <input type="text" name="judul" value="<?= e($article['judul']) ?>" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
        </div>

        <div class="row g-3">
            <div class="col-12 col-sm-6">
                <label class="block font-mono uppercase text-[10px] font-bold mb-1">Rubrik / Kategori</label>
                <select name="kategori" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
                    <option value="Budaya" <?= $article['kategori'] === 'Budaya' ? 'selected' : '' ?>>Budaya</option>
                    <option value="Sejarah" <?= $article['kategori'] === 'Sejarah' ? 'selected' : '' ?>>Sejarah</option>
                    <option value="Organisasi" <?= $article['kategori'] === 'Organisasi' ? 'selected' : '' ?>>Organisasi</option>
                    <option value="Berita" <?= $article['kategori'] === 'Berita' ? 'selected' : '' ?>>Berita</option>
                    <option value="Opini" <?= $article['kategori'] === 'Opini' ? 'selected' : '' ?>>Opini Anggota</option>
                </select>
            </div>
            <div class="col-12 col-sm-6">
                <label class="block font-mono uppercase text-[10px] font-bold mb-1">Status Publikasi</label>
                <select name="status" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
                    <option value="published" <?= $article['status'] === 'published' ? 'selected' : '' ?>>Publikasikan (Published)</option>
                    <option value="draft" <?= $article['status'] === 'draft' ? 'selected' : '' ?>>Draf (Draft)</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block font-mono uppercase text-[10px] font-bold mb-1">Ringkasan Pembuka (Lead Paragraph)</label>
            <textarea name="ringkasan" rows="2" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5"><?= e($article['ringkasan'] ?? '') ?></textarea>
        </div>

        <div class="p-4 bg-slate-100/80 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 rounded-xl space-y-3">
            <label class="block font-bold text-slate-900 dark:text-slate-100">Foto Sampul Artikel</label>
            <?php if (!empty($article['cover_image'])): ?>
                <div class="flex items-center gap-3">
                    <img src="<?= App::mediaUrl($article['cover_image'], 'articles', 'LampungThumb.jpg') ?>" class="w-20 h-14 object-cover rounded-lg border border-slate-300 dark:border-slate-700" alt="Cover">
                    <span class="text-xs text-slate-500">Sampul saat ini</span>
                </div>
            <?php endif; ?>
            <input type="file" name="cover" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white dark:file:bg-slate-100 dark:file:text-slate-950 cursor-pointer">
        </div>

        <?php
            $initialContent = $article['konten'] ?? '';
            $enableAutosave = false;
            require __DIR__ . '/_editor_toolbar.php';
        ?>

        <div class="flex justify-end gap-3 pt-3 border-t border-slate-200 dark:border-slate-700">
            <a href="<?= App::baseUrl('/admin/articles') ?>" class="poster-btn-secondary px-5 py-2.5 rounded-lg text-xs">Batal</a>
            <button type="submit" class="poster-btn-primary px-6 py-2.5 rounded-lg text-xs">Simpan Perubahan</button>
        </div>
    </form>
</div>
