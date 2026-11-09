<?php
/**
 * View: admin/articles/index.php
 * -------------------------------------------------------------------------
 * Manajemen Artikel & Khazanah Budaya.
 * -------------------------------------------------------------------------
 */
use App\Config\App;
use App\Config\Security;
use App\Helpers\Formatter;

$csrfToken = Security::generateCsrfToken();
$articles = $articles ?? [];
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b-2 border-slate-900 dark:border-slate-700 pb-4">
        <div>
            <div class="poster-kicker">Manajemen Redaksi &amp; Budaya</div>
            <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white">Artikel &amp; Sejarah Lampung</h2>
        </div>
        <a href="<?= App::baseUrl('/admin/articles/create') ?>" class="poster-btn-primary px-4 py-2.5 rounded-lg text-xs">
            <span class="material-symbols-outlined text-base">post_add</span>
            Tulis Artikel Baru
        </a>
    </div>

    <!-- Table -->
    <div class="poster-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                <thead class="bg-slate-100 dark:bg-slate-900 font-mono text-slate-600 dark:text-slate-400 uppercase text-[10px] tracking-wider border-b-2 border-slate-900 dark:border-slate-700">
                    <tr>
                        <th class="py-3.5 px-4">Judul &amp; Ringkasan</th>
                        <th class="py-3.5 px-4">Rubrik</th>
                        <th class="py-3.5 px-4">Waktu Baca</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-center">Pembaca</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    <?php if (empty($articles)): ?>
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-500 italic">Belum ada artikel yang ditulis.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($articles as $art): ?>
                            <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-3.5 px-4 max-w-md">
                                    <a href="<?= App::baseUrl('/artikel/' . $art['slug']) ?>" target="_blank" class="font-display font-bold text-sm text-slate-900 dark:text-slate-100 hover:text-amber-600 dark:hover:text-amber-400 block">
                                        <?= e($art['judul']) ?>
                                    </a>
                                    <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5"><?= e($art['ringkasan'] ?? Formatter::potongTeks($art['konten'], 100)) ?></div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="poster-badge poster-badge-gold"><?= e($art['kategori']) ?></span>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-[11px]"><?= (int) ($art['waktu_baca_menit'] ?? 3) ?> menit</td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-0.5 rounded font-mono text-[10px] font-bold uppercase border bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700">
                                        <?= e($art['status']) ?>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-900 dark:text-slate-100"><?= (int) $art['view_count'] ?></td>
                                <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                                    <a href="<?= App::baseUrl('/admin/articles/edit/' . $art['id']) ?>" class="inline-flex p-1.5 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 transition-colors" title="Edit Artikel">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>
                                    <form action="<?= App::baseUrl('/admin/articles/delete/' . $art['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Hapus artikel ini?');">
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
