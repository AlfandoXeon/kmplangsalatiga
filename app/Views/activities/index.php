<?php
/**
 * View: activities/index.php
 * -------------------------------------------------------------------------
 * Halaman Galeri & Album Kegiatan bergaya Pameran Poster.
 * -------------------------------------------------------------------------
 */
use App\Config\App;
use App\Helpers\Formatter;

$activities = $activities ?? [];
?>

<div class="py-12 min-h-screen overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Poster Header -->
        <div class="poster-card p-6 sm:p-10" data-aos="fade-up">
            <div class="poster-kicker mb-2">Pameran Visual &amp; Arsip Lapangan</div>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="poster-title text-3xl sm:text-5xl text-slate-900 dark:text-slate-100">
                        Album Kegiatan K'mplang
                    </h1>
                    <p class="font-serif-reading text-base text-slate-600 dark:text-slate-300 mt-3 max-w-2xl">
                        Dokumentasi kegiatan resmi, pelantikan, pertemuan kekeluargaan, dan aksi sosial K'mplang Salatiga yang dapat diakses serta diunduh secara penuh.
                    </p>
                </div>
                <div class="font-mono text-xs uppercase tracking-widest px-4 py-2.5 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300">
                    Total Arsip: <strong><?= count($activities) ?> Album</strong>
                </div>
            </div>
        </div>

        <!-- Activities Poster Grid -->
        <div class="row g-4">
            <?php if (empty($activities)): ?>
                <div class="col-12">
                    <div class="poster-card p-12 text-center text-slate-500 dark:text-slate-400">
                        Belum ada album dokumentasi yang dipublikasikan.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($activities as $idx => $act): ?>
                    <?php
                        $coverSrc = App::mediaUrl($act['cover_image'], 'activities', 'thumb.jpg');
                    ?>
                    <div class="col-12 col-md-6 col-lg-4 d-flex" data-aos="fade-up" data-aos-delay="<?= ($idx % 3) * 60 ?>">
                        <article class="poster-card w-100 overflow-hidden flex flex-col justify-between group">
                            <div>
                                <div class="relative h-60 overflow-hidden bg-slate-200 dark:bg-slate-900 border-b-2 border-slate-900 dark:border-slate-700">
                                    <img src="<?= $coverSrc ?>" alt="<?= e($act['judul']) ?>" class="w-full h-full object-cover">
                                    <div class="absolute top-3 left-3">
                                        <span class="poster-badge poster-badge-gold shadow">
                                            <?= e($act['kategori_kegiatan'] ?? 'Dokumentasi') ?>
                                        </span>
                                    </div>
                                    <div class="absolute bottom-3 right-3 bg-slate-900/90 text-slate-100 px-2.5 py-1 rounded text-xs font-mono font-bold flex items-center gap-1.5 border border-slate-700">
                                        <span class="material-symbols-outlined text-sm">collections</span>
                                        <?= (int) ($act['media_count'] ?? 0) ?> Berkas
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="flex flex-wrap items-center gap-3 font-mono text-[11px] text-slate-500 dark:text-slate-400 mb-2">
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-xs">calendar_month</span>
                                            <?= Formatter::tanggalIndo($act['tanggal_kegiatan']) ?>
                                        </span>
                                        <?php if (!empty($act['lokasi'])): ?>
                                            <span>&bull;</span>
                                            <span class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs">location_on</span>
                                                <?= e($act['lokasi']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <h2 class="font-display font-bold text-xl text-slate-900 dark:text-slate-100 group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors mb-2 leading-snug">
                                        <a href="<?= App::baseUrl('/kegiatan/' . $act['slug']) ?>"><?= e($act['judul']) ?></a>
                                    </h2>
                                    <p class="font-serif-reading text-sm text-slate-600 dark:text-slate-300 line-clamp-3 leading-relaxed">
                                        <?= e($act['deskripsi']) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="p-6 pt-0">
                                <a href="<?= App::baseUrl('/kegiatan/' . $act['slug']) ?>" class="poster-btn-secondary w-full justify-center py-2.5 rounded-lg text-xs">
                                    <span class="material-symbols-outlined text-base">folder_open</span>
                                    Buka Eksplorer Dokumentasi
                                </a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
