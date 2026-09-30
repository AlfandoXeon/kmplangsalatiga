<?php
use App\Config\App;
use App\Helpers\Formatter;

$activities = $activities ?? [];
?>

<div class="py-12 bg-slate-50 dark:bg-slate-950 transition-colors duration-200 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <div class="text-xs font-bold uppercase tracking-widest text-amber-600 dark:text-amber-400 mb-2">
                Dokumentasi &amp; Kegiatan
            </div>
            <h1 class="text-3xl sm:text-5xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">
                Album Kegiatan K'mplang
            </h1>
            <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 mt-3">
                Dokumentasi kegiatan resmi, pelantikan, pertemuan kebersamaan, dan aksi sosial K'mplang Salatiga.
            </p>
        </div>

        <!-- Activities Feed Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (empty($activities)): ?>
                <div class="col-span-full py-16 text-center text-slate-500 dark:text-slate-400 italic">
                    Belum ada album dokumentasi yang dipublikasikan.
                </div>
            <?php else: ?>
                <?php foreach ($activities as $act): ?>
                <div class="group bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 hover:border-amber-500/40 shadow-sm transition-all flex flex-col justify-between">
                    <div>
                        <div class="relative h-56 overflow-hidden bg-slate-100 dark:bg-slate-950">
                            <?php 
                                $coverSrc = App::mediaUrl($act['cover_image'], 'activities', 'pelantikan1.jpg');
                            ?>
                            <img src="<?= $coverSrc ?>" alt="<?= e($act['judul']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute top-3 right-3 bg-white/95 dark:bg-slate-900/90 backdrop-blur-md px-2.5 py-1 rounded-lg text-xs font-bold text-amber-600 dark:text-amber-400 flex items-center gap-1.5 border border-slate-200 dark:border-slate-700 shadow-sm">
                                <span class="material-symbols-outlined text-sm">collections</span>
                                <?= $act['media_count'] ?? 0 ?> Berkas
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 mb-2">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs text-amber-500">calendar_month</span>
                                    <?= Formatter::tanggalIndo($act['tanggal_kegiatan']) ?>
                                </span>
                                <?php if (!empty($act['lokasi'])): ?>
                                    <span>&bull;</span>
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs text-slate-400">location_on</span>
                                        <?= e($act['lokasi']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <h2 class="font-bold text-lg text-slate-900 dark:text-slate-100 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors font-montserrat mb-2 line-clamp-2">
                                <?= e($act['judul']) ?>
                            </h2>
                            <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-3 leading-relaxed">
                                <?= e($act['deskripsi']) ?>
                            </p>
                        </div>
                    </div>

                    <div class="p-6 pt-0">
                        <a href="<?= App::baseUrl('/kegiatan/' . $act['slug']) ?>" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-amber-500 hover:text-slate-950 text-slate-800 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-amber-500 dark:hover:text-slate-950 border border-slate-200 dark:border-slate-700 transition-all">
                            <span class="material-symbols-outlined text-base">folder_open</span>
                            Buka Galeri Dokumentasi
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
