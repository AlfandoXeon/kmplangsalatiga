<?php
use App\Config\App;
use App\Helpers\Formatter;
?>

<div class="py-12 bg-slate-50 dark:bg-slate-950 transition-colors duration-200 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <div class="text-xs font-bold uppercase tracking-widest text-amber-600 dark:text-amber-400 mb-2">
                Wawasan &amp; Khazanah
            </div>
            <h1 class="text-3xl sm:text-5xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">
                Artikel &amp; Budaya Lampung
            </h1>
            <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 mt-3">
                Eksplorasi ragam kebudayaan, sejarah Lampung, tradisi Sang Bumi Ruwa Jurai, serta warta rilis organisasi K'mplang Salatiga.
            </p>
        </div>

        <!-- Articles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($articles as $art): ?>
                <div class="group bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 hover:border-amber-500/40 shadow-sm transition-all flex flex-col justify-between">
                    <div>
                        <div class="relative h-48 overflow-hidden bg-slate-100 dark:bg-slate-950">
                            <?php 
                                $coverSrc = App::mediaUrl($art['cover_image'], 'articles', 'LampungThumb.jpg');
                            ?>
                            <img src="<?= $coverSrc ?>" alt="<?= e($art['judul']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <span class="absolute top-3 left-3 bg-amber-500 text-slate-950 font-bold text-[10px] uppercase px-2.5 py-1 rounded-full shadow-sm">
                                <?= e($art['kategori']) ?>
                            </span>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 mb-2">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs text-amber-500">calendar_month</span>
                                    <?= Formatter::tanggalIndo($art['created_at']) ?>
                                </span>
                                <span>&bull;</span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs text-slate-400">forum</span>
                                    <?= $art['comment_count'] ?> Komentar
                                </span>
                            </div>
                            <h2 class="font-bold text-base text-slate-900 dark:text-slate-100 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors font-montserrat mb-3 line-clamp-2">
                                <?= e($art['judul']) ?>
                            </h2>
                            <div class="text-xs text-slate-600 dark:text-slate-400 line-clamp-3 leading-relaxed">
                                <?= strip_tags($art['konten']) ?>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 pt-0">
                        <a href="<?= App::baseUrl('/artikel/' . $art['slug']) ?>" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-800 hover:bg-amber-500 hover:text-slate-950 dark:hover:bg-amber-500 dark:hover:text-slate-950 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition-all">
                            <span>Baca Selengkapnya</span>
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
