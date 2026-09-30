<?php
use App\Config\App;
use App\Config\Security;
use App\Helpers\Formatter;

$csrfToken = Security::generateCsrfToken();
$article = $article ?? [];
$comments = $comments ?? [];
?>

<div class="py-10 bg-slate-50 dark:bg-slate-950 transition-colors duration-200 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
            <a href="<?= App::baseUrl('/') ?>" class="hover:text-amber-600 dark:hover:text-amber-400">Beranda</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="<?= App::baseUrl('/artikel') ?>" class="hover:text-amber-600 dark:hover:text-amber-400">Artikel</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-amber-600 dark:text-amber-400 font-semibold truncate"><?= e($article['judul']) ?></span>
        </nav>

        <!-- Article Card Container -->
        <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm dark:shadow-xl">
            <!-- Cover Image -->
            <?php if (!empty($article['cover_image'])): ?>
                <div class="h-72 sm:h-96 w-full overflow-hidden bg-slate-100 dark:bg-slate-950">
                    <img src="<?= App::mediaUrl($article['cover_image'], 'articles') ?>" alt="<?= e($article['judul']) ?>" class="w-full h-full object-cover">
                </div>
            <?php endif; ?>

            <div class="p-6 sm:p-10 space-y-6">
                <!-- Meta Info -->
                <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 dark:text-slate-400 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase bg-amber-500 text-slate-950">
                        <?= e($article['kategori']) ?>
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-xs text-amber-500">calendar_month</span>
                        <?= Formatter::tanggalIndo($article['created_at']) ?>
                    </span>
                    <span>&bull;</span>
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-xs text-slate-400">person</span>
                        Penulis: <?= e($article['author_name'] ?? 'Pengurus K\'mplang') ?>
                    </span>
                    <span>&bull;</span>
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-xs text-slate-400">visibility</span>
                        <?= $article['view_count'] ?> Pembaca
                    </span>
                </div>

                <!-- Headline Title -->
                <h1 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight leading-snug">
                    <?= e($article['judul']) ?>
                </h1>

                <!-- Body Content -->
                <div class="text-sm sm:text-base text-slate-700 dark:text-slate-300 leading-relaxed space-y-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <?= $article['konten'] ?>
                </div>
            </div>
        </article>

        <!-- Comments Section -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm dark:shadow-xl">
            <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <span class="material-symbols-outlined text-amber-500 text-2xl">forum</span>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white font-montserrat">Komentar &amp; Diskusi (<?= count($comments) ?>)</h3>
            </div>

            <div class="space-y-4">
                <?php if (empty($comments)): ?>
                    <p class="text-xs text-slate-500 italic py-4">Belum ada komentar untuk artikel ini.</p>
                <?php else: ?>
                    <?php foreach ($comments as $comm): ?>
                        <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-amber-500 flex items-center justify-center text-[10px] font-bold text-slate-950 uppercase">
                                        <?= substr($comm['nama_lengkap'], 0, 1) ?>
                                    </div>
                                    <span class="text-xs font-bold text-slate-900 dark:text-slate-200"><?= e($comm['nama_lengkap']) ?></span>
                                    <?php if ($comm['role'] === 'admin'): ?>
                                        <span class="text-[9px] font-bold uppercase px-1.5 py-0.5 rounded bg-amber-500 text-slate-950">Pengurus</span>
                                    <?php endif; ?>
                                </div>
                                <span class="text-[10px] text-slate-400 dark:text-slate-500"><?= Formatter::tanggalIndo($comm['created_at'], true) ?></span>
                            </div>
                            <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed pl-9"><?= nl2br(e($comm['content'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Comment Form -->
            <?php if (!empty($currentUser)): ?>
                <form action="<?= App::baseUrl('/komentar') ?>" method="POST" class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-3">
                    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                    <input type="hidden" name="post_type" value="article">
                    <input type="hidden" name="post_id" value="<?= $article['id'] ?>">
                    <input type="hidden" name="redirect_url" value="/artikel/<?= $article['slug'] ?>">

                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Tulis Tanggapan Anda:</label>
                    <textarea name="content" rows="3" required placeholder="Tuliskan pendapat atau masukan Anda..." class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl p-3 text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 transition-colors"></textarea>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-400 text-slate-950 shadow-sm transition-all">
                            <span class="material-symbols-outlined text-sm">send</span>
                            Kirim Komentar
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs text-slate-600 dark:text-slate-400">
                    <span>Silakan masuk dengan akun anggota untuk menulis tanggapan.</span>
                    <a href="<?= App::baseUrl('/login') ?>" class="px-4 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold transition-all">
                        Masuk
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
