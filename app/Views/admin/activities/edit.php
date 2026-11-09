<?php
/**
 * View: admin/activities/edit.php
 * -------------------------------------------------------------------------
 * Formulir Edit Kegiatan & Manajemen Berkas Album Dokumentasi.
 * -------------------------------------------------------------------------
 */
use App\Config\App;
use App\Config\Security;
use App\Helpers\Formatter;

$csrfToken = Security::generateCsrfToken();
$activity = $activity ?? [];
$mediaList = $mediaList ?? [];
?>

<div class="max-w-4xl space-y-8">
    <div class="border-b-2 border-slate-900 dark:border-slate-700 pb-4 flex items-center justify-between">
        <div>
            <div class="poster-kicker">Pembaruan Dokumentasi</div>
            <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white">Edit Kegiatan &amp; Kelola Album</h2>
        </div>
        <a href="<?= App::baseUrl('/kegiatan/' . $activity['slug']) ?>" target="_blank" class="poster-btn-secondary px-3.5 py-2 rounded-lg text-xs">
            <span class="material-symbols-outlined text-sm">visibility</span>
            Lihat di Web
        </a>
    </div>

    <!-- Edit Main Form -->
    <form action="<?= App::baseUrl('/admin/activities/update/' . $activity['id']) ?>" method="POST" enctype="multipart/form-data" class="poster-card p-6 sm:p-8 space-y-5 text-xs">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div class="row g-3">
            <div class="col-12 col-sm-8">
                <label class="block font-mono uppercase text-[10px] font-bold mb-1">Judul Kegiatan *</label>
                <input type="text" name="judul" value="<?= e($activity['judul']) ?>" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
            </div>
            <div class="col-12 col-sm-4">
                <label class="block font-mono uppercase text-[10px] font-bold mb-1">Kategori Kegiatan</label>
                <?php $kat = $activity['kategori_kegiatan'] ?? 'Dokumentasi'; ?>
                <select name="kategori_kegiatan" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
                    <option value="Dokumentasi" <?= $kat === 'Dokumentasi' ? 'selected' : '' ?>>Dokumentasi</option>
                    <option value="Pelantikan &amp; Rapat" <?= $kat === 'Pelantikan & Rapat' ? 'selected' : '' ?>>Pelantikan &amp; Rapat</option>
                    <option value="Olahraga &amp; Seni" <?= $kat === 'Olahraga & Seni' ? 'selected' : '' ?>>Olahraga &amp; Seni</option>
                    <option value="Sosial &amp; Pengabdian" <?= $kat === 'Sosial & Pengabdian' ? 'selected' : '' ?>>Sosial &amp; Pengabdian</option>
                    <option value="Kebersamaan" <?= $kat === 'Kebersamaan' ? 'selected' : '' ?>>Kebersamaan</option>
                </select>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-sm-6">
                <label class="block font-mono uppercase text-[10px] font-bold mb-1">Tanggal Pelaksanaan</label>
                <input type="date" name="tanggal_kegiatan" value="<?= e($activity['tanggal_kegiatan']) ?>" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
            </div>
            <div class="col-12 col-sm-6">
                <label class="block font-mono uppercase text-[10px] font-bold mb-1">Lokasi</label>
                <input type="text" name="lokasi" value="<?= e($activity['lokasi'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
            </div>
        </div>

        <div>
            <label class="block font-mono uppercase text-[10px] font-bold mb-1">Deskripsi Kegiatan</label>
            <textarea name="deskripsi" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5"><?= e($activity['deskripsi'] ?? '') ?></textarea>
        </div>

        <div class="flex items-center gap-2.5 p-3 rounded-lg bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700">
            <input type="checkbox" id="is_featured" name="is_featured" value="1" <?= !empty($activity['is_featured']) ? 'checked' : '' ?> class="w-4 h-4 accent-slate-900 dark:accent-slate-200">
            <label for="is_featured" class="font-semibold text-slate-800 dark:text-slate-200 cursor-pointer">Tampilkan sebagai Kegiatan Unggulan (Featured Poster)</label>
        </div>

        <!-- Cover Image Preview & Edit -->
        <div class="p-4 bg-slate-100/80 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 rounded-xl space-y-3">
            <label class="block font-bold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-slate-700 dark:text-slate-300 text-base">image</span>
                Foto Sampul Utama (Cover Image)
            </label>
            <?php if (!empty($activity['cover_image'])): ?>
                <div class="flex items-center gap-3">
                    <img src="<?= App::mediaUrl($activity['cover_image'], 'activities', 'thumb.jpg') ?>" class="w-20 h-14 object-cover rounded-lg border border-slate-300 dark:border-slate-700" alt="Cover">
                    <span class="text-xs text-slate-500">Sampul saat ini</span>
                </div>
            <?php endif; ?>
            <input type="file" name="cover" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-200 dark:file:bg-slate-800 file:text-slate-900 dark:file:text-slate-100 cursor-pointer">
        </div>

        <!-- Add More Media to Album -->
        <div class="p-4 bg-slate-100/80 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 rounded-xl space-y-2">
            <label class="block font-bold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-slate-700 dark:text-slate-300 text-base">add_circle</span>
                Tambah Berkas Baru ke Album
            </label>
            <input type="file" name="media[]" multiple accept="image/*,video/mp4,video/webm" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white dark:file:bg-slate-100 dark:file:text-slate-950 cursor-pointer">
        </div>

        <div class="flex justify-end gap-3 pt-3 border-t border-slate-200 dark:border-slate-700">
            <a href="<?= App::baseUrl('/admin/activities') ?>" class="poster-btn-secondary px-5 py-2.5 rounded-lg text-xs">Kembali</a>
            <button type="submit" class="poster-btn-primary px-6 py-2.5 rounded-lg text-xs">Simpan Perubahan &amp; Unggah Berkas</button>
        </div>
    </form>

    <!-- Existing Album Media Gallery Manager -->
    <div class="poster-card p-6 sm:p-8 space-y-4">
        <h3 class="font-display font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined text-slate-700 dark:text-slate-300">collections</span>
            Berkas dalam Album Dokumentasi (<?= count($mediaList) ?> Berkas)
        </h3>

        <?php if (empty($mediaList)): ?>
            <p class="text-xs text-slate-500 italic py-4">Belum ada media di album ini.</p>
        <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4 pt-2">
                <?php foreach ($mediaList as $item): ?>
                    <?php $mediaUrl = App::mediaUrl($item['file_path'], 'activities', 'thumb.jpg'); ?>
                    <div class="bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl overflow-hidden flex flex-col justify-between">
                        <div class="h-24 bg-slate-200 dark:bg-slate-950 overflow-hidden relative flex items-center justify-center">
                            <?php if ($item['file_type'] === 'image'): ?>
                                <img src="<?= $mediaUrl ?>" class="w-full h-full object-cover" alt="">
                            <?php else: ?>
                                <span class="material-symbols-outlined text-3xl text-slate-500">video_file</span>
                            <?php endif; ?>
                        </div>
                        <div class="p-2.5 space-y-1">
                            <p class="text-[10px] font-semibold text-slate-800 dark:text-slate-200 truncate" title="<?= e($item['original_name']) ?>">
                                <?= e($item['original_name']) ?>
                            </p>
                            <div class="flex items-center justify-between font-mono text-[9px] text-slate-500">
                                <span><?= Formatter::formatBytes($item['file_size']) ?></span>
                                <form action="<?= App::baseUrl('/admin/activities/media/delete/' . $item['id']) ?>" method="POST" onsubmit="return confirm('Hapus berkas ini dari album?');">
                                    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                    <button type="submit" class="text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100" title="Hapus Berkas">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
