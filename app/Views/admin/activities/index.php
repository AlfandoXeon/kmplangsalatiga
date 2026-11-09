<?php
/**
 * View: admin/activities/index.php
 * -------------------------------------------------------------------------
 * Manajemen Kegiatan & Album Dokumentasi.
 * -------------------------------------------------------------------------
 */
use App\Config\App;
use App\Config\Security;
use App\Helpers\Formatter;

$csrfToken = Security::generateCsrfToken();
$activities = $activities ?? [];
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b-2 border-slate-900 dark:border-slate-700 pb-4">
        <div>
            <div class="poster-kicker">Manajemen Arsip Lapangan</div>
            <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white">Kegiatan &amp; Album Dokumentasi</h2>
        </div>
        <a href="<?= App::baseUrl('/admin/activities/create') ?>" class="poster-btn-primary px-4 py-2.5 rounded-lg text-xs">
            <span class="material-symbols-outlined text-base">add_photo_alternate</span>
            Tambah Kegiatan &amp; Album
        </a>
    </div>

    <!-- Activities Table -->
    <div class="poster-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                <thead class="bg-slate-100 dark:bg-slate-900 font-mono text-slate-600 dark:text-slate-400 uppercase text-[10px] tracking-wider border-b-2 border-slate-900 dark:border-slate-700">
                    <tr>
                        <th class="py-3.5 px-4 w-16">Cover</th>
                        <th class="py-3.5 px-4">Judul &amp; Kategori</th>
                        <th class="py-3.5 px-4">Tanggal &amp; Lokasi</th>
                        <th class="py-3.5 px-4 text-center">Berkas</th>
                        <th class="py-3.5 px-4 text-center">Dilihat</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    <?php if (empty($activities)): ?>
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-500 italic">Belum ada kegiatan yang dipublikasikan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($activities as $act): ?>
                            <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="w-14 h-10 rounded-lg overflow-hidden bg-slate-200 dark:bg-slate-950 border border-slate-300 dark:border-slate-700">
                                        <img src="<?= App::mediaUrl($act['cover_image'], 'activities', 'thumb.jpg') ?>" class="w-full h-full object-cover" alt="Cover">
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2">
                                        <span class="poster-badge"><?= e($act['kategori_kegiatan'] ?? 'Dokumentasi') ?></span>
                                        <?php if (!empty($act['is_featured'])): ?>
                                            <span class="px-2 py-0.5 rounded font-mono text-[9px] font-bold uppercase bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700">Unggulan</span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?= App::baseUrl('/kegiatan/' . $act['slug']) ?>" target="_blank" class="font-display font-bold text-sm text-slate-900 dark:text-slate-100 hover:text-amber-600 dark:hover:text-amber-400 block mt-1">
                                        <?= e($act['judul']) ?>
                                    </a>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500 dark:text-slate-400">
                                    <div><?= Formatter::tanggalIndo($act['tanggal_kegiatan']) ?></div>
                                    <div><?= e($act['lokasi'] ?: '-') ?></div>
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono font-bold"><?= (int) ($act['media_count'] ?? 0) ?></td>
                                <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-900 dark:text-slate-100"><?= (int) $act['view_count'] ?></td>
                                <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                                    <a href="<?= App::baseUrl('/admin/activities/edit/' . $act['id']) ?>" class="inline-flex p-1.5 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 transition-colors" title="Edit & Kelola Album">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>
                                    <form action="<?= App::baseUrl('/admin/activities/delete/' . $act['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini beserta seluruh berkas albumnya?');">
                                        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                        <button type="submit" class="p-1.5 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-300 dark:border-slate-700 transition-colors" title="Hapus">
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
