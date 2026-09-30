<?php
use App\Config\App;
use App\Config\Security;
use App\Helpers\Formatter;

$csrfToken = Security::generateCsrfToken();
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-4 transition-colors">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">Manajemen Kegiatan &amp; Dokumentasi</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Publikasikan kegiatan baru dengan album foto &amp; video berkapasitas hingga 50 MB per berkas.</p>
        </div>
        <a href="<?= App::baseUrl('/admin/activities/create') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-400 text-slate-950 shadow-sm transition-all">
            <span class="material-symbols-outlined text-base">add_photo_alternate</span>
            Tambah Kegiatan &amp; Album
        </a>
    </div>

    <!-- Activities Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm dark:shadow-2xl transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="py-3 px-4 w-12">Cover</th>
                        <th class="py-3 px-4">Judul Kegiatan</th>
                        <th class="py-3 px-4">Tanggal Pelaksanaan</th>
                        <th class="py-3 px-4">Lokasi</th>
                        <th class="py-3 px-4 text-center">Dilihat</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php if (empty($activities)): ?>
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 dark:text-slate-500 italic">Belum ada kegiatan yang dipublikasikan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($activities as $act): ?>
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="w-12 h-10 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                                        <img src="<?= App::mediaUrl($act['cover_image'], 'activities', 'pelantikan1.jpg') ?>" class="w-full h-full object-cover">
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-bold text-slate-900 dark:text-slate-200">
                                    <a href="<?= App::baseUrl('/kegiatan/' . $act['slug']) ?>" target="_blank" class="hover:text-amber-600 dark:hover:text-amber-400">
                                        <?= e($act['judul']) ?>
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-slate-500 dark:text-slate-400"><?= Formatter::tanggalIndo($act['tanggal_kegiatan']) ?></td>
                                <td class="py-3 px-4 text-slate-500 dark:text-slate-400"><?= e($act['lokasi'] ?: '-') ?></td>
                                <td class="py-3 px-4 text-center font-mono font-bold text-amber-600 dark:text-amber-400"><?= $act['view_count'] ?></td>
                                <td class="py-3 px-4 text-right space-x-2 whitespace-nowrap">
                                    <a href="<?= App::baseUrl('/admin/activities/edit/' . $act['id']) ?>" class="p-1.5 rounded hover:bg-slate-100 dark:hover:bg-slate-800 text-amber-600 dark:text-amber-400 transition-colors" title="Edit & Kelola Album">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>
                                    <form action="<?= App::baseUrl('/admin/activities/delete/' . $act['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini beserta seluruh berkas albumnya?');">
                                        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                        <button type="submit" class="p-1.5 rounded hover:bg-rose-500/20 text-rose-500 dark:text-rose-400 transition-colors" title="Hapus">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
