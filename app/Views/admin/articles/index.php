<?php
use App\Config\App;
use App\Config\Security;
use App\Helpers\Formatter;

$csrfToken = Security::generateCsrfToken();
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-4 transition-colors">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">Manajemen Artikel &amp; Sejarah</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Tulis dan publikasikan wawasan budaya, sejarah Lampung, serta warta organisasi.</p>
        </div>
        <a href="<?= App::baseUrl('/admin/articles/create') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-400 text-slate-950 shadow-sm transition-all">
            <span class="material-symbols-outlined text-base">post_add</span>
            Tulis Artikel Baru
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm dark:shadow-2xl transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="py-3 px-4">Judul Artikel</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Tanggal Buat</th>
                        <th class="py-3 px-4 text-center">Dilihat</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php if (empty($articles)): ?>
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 dark:text-slate-500 italic">Belum ada artikel yang ditulis.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($articles as $art): ?>
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-3 px-4 font-bold text-slate-900 dark:text-slate-200">
                                    <a href="<?= App::baseUrl('/artikel/' . $art['slug']) ?>" target="_blank" class="hover:text-amber-600 dark:hover:text-amber-400">
                                        <?= e($art['judul']) ?>
                                    </a>
                                </td>
                                <td class="py-3 px-4 uppercase text-[10px] font-bold text-amber-600 dark:text-amber-400"><?= e($art['kategori']) ?></td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $art['status'] === 'published' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700' ?>">
                                        <?= e($art['status']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-500 dark:text-slate-400"><?= Formatter::tanggalIndo($art['created_at']) ?></td>
                                <td class="py-3 px-4 text-center font-mono font-bold text-amber-600 dark:text-amber-400"><?= $art['view_count'] ?></td>
                                <td class="py-3 px-4 text-right space-x-2 whitespace-nowrap">
                                    <a href="<?= App::baseUrl('/admin/articles/edit/' . $art['id']) ?>" class="p-1.5 rounded hover:bg-slate-100 dark:hover:bg-slate-800 text-amber-600 dark:text-amber-400 transition-colors" title="Edit Artikel">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>
                                    <form action="<?= App::baseUrl('/admin/articles/delete/' . $art['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Hapus artikel ini?');">
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
