<?php
/**
 * View: articles/detail.php
 * -------------------------------------------------------------------------
 * Halaman Baca Artikel Editorial (Eye-Comfort Typography View).
 * Dirancang menggunakan proporsi kolom bacaan ideal (68ch), font serif
 * berjarak baris longgar (1.85), drop-cap pada paragraf pembuka,
 * serta kontras kertas krem hangat / abu gelap redup.
 * -------------------------------------------------------------------------
 */
use App\Config\App;
use App\Config\Security;
use App\Helpers\Formatter;

$csrfToken = Security::generateCsrfToken();
$article = $article ?? [];
$comments = $comments ?? [];
$related = $related ?? [];
$currentUserId = (int) ($_SESSION['user_id'] ?? 0);
$currentUserRole = $_SESSION['user_role'] ?? '';
$readMin = (int) ($article['waktu_baca_menit'] ?? 3);
?>

<div class="py-10 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 font-mono text-xs text-slate-500 dark:text-slate-400">
            <a href="<?= App::baseUrl('/') ?>" class="hover:text-amber-600 dark:hover:text-amber-400">Beranda</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="<?= App::baseUrl('/artikel') ?>" class="hover:text-amber-600 dark:hover:text-amber-400">Artikel</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-slate-900 dark:text-amber-400 font-bold truncate"><?= e($article['judul']) ?></span>
        </nav>

        <!-- Main Poster Editorial Article Container -->
        <article class="poster-card overflow-hidden">
            <!-- Cover Poster Banner -->
            <?php if (!empty($article['cover_image'])): ?>
                <div class="h-72 sm:h-[420px] w-full overflow-hidden bg-slate-200 dark:bg-slate-900 border-b-2 border-slate-900 dark:border-slate-700 relative">
                    <img src="<?= App::mediaUrl($article['cover_image'], 'articles', 'LampungThumb.jpg') ?>" alt="<?= e($article['judul']) ?>" class="w-full h-full object-cover">
                    <div class="absolute bottom-4 left-4">
                        <span class="poster-badge poster-badge-gold shadow-md">
                            Rubrik: <?= e($article['kategori']) ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>

            <div class="p-6 sm:p-12 space-y-8">
                <!-- Editorial Metadata Stamp -->
                <div class="flex flex-wrap items-center justify-between gap-4 pb-5 border-b-2 border-slate-900 dark:border-slate-700 font-mono text-xs text-slate-600 dark:text-slate-400">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">calendar_month</span>
                            <?= Formatter::tanggalIndo($article['created_at']) ?>
                        </span>
                        <span>&bull;</span>
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">edit_square</span>
                            <?= e($article['author_name'] ?? 'Redaksi K\'mplang') ?>
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-2.5 py-1 rounded bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">schedule</span>
                            <?= $readMin ?> Menit Baca
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                            <?= (int) $article['view_count'] ?>x
                        </span>
                    </div>
                </div>

                <!-- Display Headline -->
                <header class="space-y-4">
                    <h1 class="poster-title text-3xl sm:text-5xl text-slate-900 dark:text-white leading-tight">
                        <?= e($article['judul']) ?>
                    </h1>

                    <?php if (!empty($article['ringkasan'])): ?>
                        <p class="font-serif-reading italic text-lg sm:text-xl text-slate-600 dark:text-slate-300 border-l-4 border-amber-500 pl-4 py-1">
                            <?= e($article['ringkasan']) ?>
                        </p>
                    <?php endif; ?>
                </header>

                <hr class="poster-divider">

                <!-- Eye-Comfort Reading Body -->
                <div class="editorial-prose drop-cap text-slate-800 dark:text-slate-200">
                    <?= Formatter::renderArticleContent($article['konten'] ?? '') ?>
                </div>
            </div>
        </article>

        <!-- Related Articles -->
        <?php if (!empty($related)): ?>
            <div class="poster-card p-6 sm:p-8 space-y-4">
                <div class="poster-kicker">Bacaan Selanjutnya</div>
                <div class="row g-3">
                    <?php foreach ($related as $rel): ?>
                        <div class="col-12 col-md-6">
                            <a href="<?= App::baseUrl('/artikel/' . $rel['slug']) ?>" class="block p-4 rounded-xl bg-slate-100/80 dark:bg-slate-900/70 border border-slate-300 dark:border-slate-700 hover:border-amber-500 transition-all">
                                <span class="font-mono text-[10px] uppercase text-amber-700 dark:text-amber-400"><?= e($rel['kategori']) ?> &bull; <?= (int) ($rel['waktu_baca_menit'] ?? 3) ?> mnt baca</span>
                                <h4 class="font-display font-bold text-base text-slate-900 dark:text-slate-100 mt-1"><?= e($rel['judul']) ?></h4>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Comments Section -->
        <div class="poster-card p-6 sm:p-8 space-y-6">
            <div class="poster-card-header">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-2xl">forum</span>
                    <h3 class="font-display font-bold text-xl text-slate-900 dark:text-white">Ruang Diskusi Pembaca (<?= count($comments) ?>)</h3>
                </div>
            </div>

            <div class="space-y-4">
                <?php if (empty($comments)): ?>
                    <p class="font-serif-reading text-sm text-slate-500 italic py-3">Belum ada tanggapan untuk tulisan ini. Mari mulai diskusi yang hangat.</p>
                <?php else: ?>
                    <?php foreach ($comments as $comm): ?>
                        <div class="bg-slate-100/70 dark:bg-slate-900/70 p-4 rounded-xl border border-slate-300 dark:border-slate-700 space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-amber-500 border border-slate-900 flex items-center justify-center text-xs font-bold text-slate-950 uppercase">
                                        <?= substr($comm['nama_lengkap'], 0, 1) ?>
                                    </div>
                                    <div>
                                        <span class="text-xs font-bold text-slate-900 dark:text-slate-100"><?= e($comm['nama_lengkap']) ?></span>
                                        <?php if (($comm['role'] ?? '') === 'admin'): ?>
                                            <span class="ml-1.5 text-[9px] font-mono font-bold uppercase px-1.5 py-0.5 rounded bg-amber-500 text-slate-950">Pengurus</span>
                                        <?php endif; ?>
                                        <div class="font-mono text-[10px] text-slate-500 dark:text-slate-400"><?= Formatter::tanggalIndo($comm['created_at'], true) ?></div>
                                    </div>
                                </div>
                                <?php if ($currentUserId === (int) $comm['user_id'] || $currentUserRole === 'admin'): ?>
                                    <form action="<?= App::baseUrl('/komentar/delete/' . $comm['id']) ?>" method="POST" onsubmit="return confirm('Hapus komentar ini?');">
                                        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                        <input type="hidden" name="redirect_url" value="/artikel/<?= e($article['slug']) ?>">
                                        <button type="submit" class="text-rose-600 dark:text-rose-400 hover:underline text-[11px] font-mono flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">delete</span> Hapus
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <p class="font-serif-reading text-sm text-slate-800 dark:text-slate-200 leading-relaxed pl-10"><?= nl2br(e($comm['content'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php if (!empty($currentUser)): ?>
                <form action="<?= App::baseUrl('/komentar') ?>" method="POST" class="pt-4 border-t border-slate-300 dark:border-slate-700 space-y-3">
                    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                    <input type="hidden" name="post_type" value="article">
                    <input type="hidden" name="post_id" value="<?= (int) $article['id'] ?>">
                    <input type="hidden" name="redirect_url" value="/artikel/<?= e($article['slug']) ?>">

                    <label class="block font-mono text-xs uppercase tracking-wider font-bold text-slate-700 dark:text-slate-300">Tulis Tanggapan Anda</label>
                    <textarea name="content" rows="3" required placeholder="Bagikan pandangan atau apresiasi Anda terhadap artikel ini..." class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl p-3.5 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-amber-500 transition-colors"></textarea>

                    <div class="flex justify-end">
                        <button type="submit" class="poster-btn-primary px-5 py-2.5 rounded-lg text-xs">
                            <span class="material-symbols-outlined text-sm">send</span>
                            Kirim Komentar
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="p-4 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-700 dark:text-slate-300">
                    <span>Silakan masuk dengan akun anggota untuk menulis tanggapan di ruang diskusi.</span>
                    <button type="button" onclick="openAuthModal('login')" class="poster-btn-primary px-4 py-2 rounded-lg text-xs">
                        Masuk Akun
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
