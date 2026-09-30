<?php
use App\Config\App;
use App\Config\Security;
use App\Helpers\Formatter;

$csrfToken = Security::generateCsrfToken();
?>

<div class="max-w-4xl space-y-8">
    <div class="border-b border-slate-200 dark:border-slate-800 pb-4 flex items-center justify-between transition-colors">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">Edit Kegiatan &amp; Kelola Album</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kelola data kegiatan serta berkas media foto dan video di dalam album.</p>
        </div>
        <a href="<?= App::baseUrl('/kegiatan/' . $activity['slug']) ?>" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-amber-600 dark:text-amber-400 text-xs font-bold transition-colors">
            <span class="material-symbols-outlined text-sm">visibility</span>
            Lihat di Web
        </a>
    </div>

    <!-- Edit Main Form -->
    <form action="<?= App::baseUrl('/admin/activities/update/' . $activity['id']) ?>" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 space-y-5 shadow-sm dark:shadow-none transition-colors">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Judul Kegiatan</label>
            <input type="text" name="judul" value="<?= e($activity['judul']) ?>" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal Pelaksanaan</label>
                <input type="date" name="tanggal_kegiatan" value="<?= e($activity['tanggal_kegiatan']) ?>" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Lokasi</label>
                <input type="text" name="lokasi" value="<?= e($activity['lokasi'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Kegiatan</label>
            <textarea name="deskripsi" rows="3" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500"><?= e($activity['deskripsi'] ?? '') ?></textarea>
        </div>

        <!-- Cover Image Preview & Edit -->
        <div class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl space-y-3">
            <label class="block text-xs font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-amber-500 text-base">image</span>
                Foto Sampul Utama (Cover Image)
            </label>
            <?php if (!empty($activity['cover_image'])): ?>
                <div class="flex items-center gap-3">
                    <img src="<?= App::mediaUrl($activity['cover_image'], 'activities', 'pelantikan1.jpg') ?>" class="w-20 h-14 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
                    <span class="text-xs text-slate-500 dark:text-slate-400">Sampul saat ini</span>
                </div>
            <?php endif; ?>
            <input type="file" name="cover" accept="image/*" class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-200 dark:file:bg-slate-800 file:text-slate-800 dark:file:text-amber-400 hover:file:bg-slate-300 dark:hover:file:bg-slate-700 cursor-pointer">
            <span class="text-[11px] text-slate-500 block">Pilih berkas baru jika ingin mengganti cover utama (Maks 10 MB).</span>
        </div>

        <!-- Add More Media to Album -->
        <div class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl space-y-2">
            <label class="block text-xs font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-amber-500 text-base">add_circle</span>
                Tambah Berkas Baru ke Album (Maks 10 MB / berkas)
            </label>
            <input type="file" name="media[]" multiple accept="image/*,video/mp4,video/webm" class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-500 file:text-slate-950 hover:file:bg-amber-400 cursor-pointer">
        </div>

        <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-sm transition-all">
                Simpan Perubahan &amp; Unggah Berkas
            </button>
        </div>
    </form>

    <!-- Existing Album Media Gallery Manager -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 space-y-4 shadow-sm dark:shadow-xl transition-colors">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white font-montserrat flex items-center gap-2">
            <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">collections</span>
            Berkas dalam Album Google Drive (<?= count($mediaList) ?> Berkas)
        </h3>

        <?php if (empty($mediaList)): ?>
            <p class="text-xs text-slate-400 dark:text-slate-500 italic py-4">Belum ada media di album ini.</p>
        <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4 pt-2">
                <?php foreach ($mediaList as $item): ?>
                    <?php
                        $mediaUrl = App::mediaUrl($item['file_path'], 'activities');
                    ?>
                    <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden flex flex-col justify-between group transition-colors">
                        <div class="h-24 bg-slate-100 dark:bg-slate-900 overflow-hidden relative flex items-center justify-center">
                            <?php if ($item['file_type'] === 'image'): ?>
                                <img src="<?= $mediaUrl ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="material-symbols-outlined text-3xl text-amber-500">video_file</span>
                            <?php endif; ?>
                        </div>
                        <div class="p-2.5 space-y-1">
                            <p class="text-[10px] font-semibold text-slate-700 dark:text-slate-300 truncate" title="<?= e($item['original_name']) ?>">
                                <?= e($item['original_name']) ?>
                            </p>
                            <div class="flex items-center justify-between text-[9px] text-slate-400 dark:text-slate-500">
                                <span><?= Formatter::formatBytes($item['file_size']) ?></span>
                                <form action="<?= App::baseUrl('/admin/activities/media/delete/' . $item['id']) ?>" method="POST" onsubmit="return confirm('Hapus berkas ini dari album?');">
                                    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                    <button type="submit" class="text-rose-500 dark:text-rose-400 hover:opacity-75" title="Hapus Berkas">
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
