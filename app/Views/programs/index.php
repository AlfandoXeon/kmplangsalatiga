<?php
/**
 * View: programs/index.php
 * -------------------------------------------------------------------------
 * Halaman Transparansi Program Kerja bergaya Poster Editorial.
 * Menampilkan ringkasan metrik capaian organisasi, filter per divisi,
 * serta kartu poster program kerja lengkap dengan progress bar (%).
 * -------------------------------------------------------------------------
 */
use App\Config\App;

$prokers = $prokers ?? [];
$divisiList = $divisiList ?? [];
$activeDivisi = $activeDivisi ?? 'Semua';
$stats = $stats ?? [];
?>

<div class="py-12 min-h-screen overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Poster Editorial Header -->
        <div class="poster-card p-6 sm:p-10" data-aos="fade-up">
            <div class="row g-4 align-items-end">
                <div class="col-12 col-lg-7">
                    <div class="poster-kicker mb-3">Transparansi &amp; Akuntabilitas Organisasi</div>
                    <h1 class="poster-title text-3xl sm:text-5xl text-slate-900 dark:text-slate-100">
                        Program Kerja K'mplang
                    </h1>
                    <p class="font-serif-reading text-base text-slate-600 dark:text-slate-300 mt-3 max-w-2xl">
                        Rincian rencana strategis, indikator kualitas &amp; kuantitas, progres realisasi lapangan, serta keterbukaan anggaran seluruh divisi kepengurusan periode 2026/2027.
                    </p>
                </div>
                <div class="col-12 col-lg-5">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                        <div class="p-3.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-center">
                            <div class="font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400">Total</div>
                            <div class="font-display font-black text-2xl text-slate-900 dark:text-white mt-0.5"><?= (int) ($stats['total'] ?? count($prokers)) ?></div>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-center">
                            <div class="font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400">Selesai</div>
                            <div class="font-display font-black text-2xl text-slate-900 dark:text-white mt-0.5"><?= (int) ($stats['selesai'] ?? 0) ?></div>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-center">
                            <div class="font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400">Aktif</div>
                            <div class="font-display font-black text-2xl text-slate-900 dark:text-white mt-0.5"><?= (int) ($stats['berlangsung'] ?? 0) ?></div>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-center">
                            <div class="font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400">Capaian</div>
                            <div class="font-display font-black text-2xl text-slate-900 dark:text-white mt-0.5"><?= (int) ($stats['rata_progres'] ?? 0) ?>%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Divisions Pills -->
        <div class="flex items-center flex-wrap gap-2">
            <span class="font-mono text-xs uppercase tracking-widest text-slate-500 dark:text-slate-400 mr-2">Filter Divisi:</span>
            <a href="<?= App::baseUrl('/program-kerja') ?>" class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all border <?= $activeDivisi === 'Semua' ? 'bg-amber-400 text-slate-950 border-slate-900 shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700 hover:border-slate-900 dark:hover:border-slate-500' ?>">
                Semua Divisi
            </a>
            <?php foreach ($divisiList as $div): ?>
                <a href="<?= App::baseUrl('/program-kerja?divisi=' . urlencode($div)) ?>" class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all border <?= $activeDivisi === $div ? 'bg-amber-400 text-slate-950 border-slate-900 shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700 hover:border-slate-900 dark:hover:border-slate-500' ?>">
                    <?= e($div) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Proker Poster Grid -->
        <div class="row g-4">
            <?php foreach ($prokers as $idx => $p): ?>
                <?php $progres = (int) ($p['progres_persen'] ?? 0); ?>
                <div class="col-12 col-md-6 col-lg-4 d-flex" data-aos="fade-up" data-aos-delay="<?= ($idx % 3) * 50 ?>">
                    <article class="poster-card bg-white dark:bg-slate-900 w-100 flex flex-col justify-between">
                        <div>
                            <div class="poster-card-header">
                                <span>DIVISI <?= e(strtoupper($p['divisi'])) ?></span>
                                <span><?= e(strtoupper($p['status'])) ?></span>
                            </div>

                            <div class="p-6 space-y-4">
                                <div>
                                    <div class="font-mono text-[10px] uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1">
                                        PROKER #<?= str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT) ?> &bull; PERIODE <?= e($p['periode'] ?? '2026/2027') ?>
                                    </div>
                                    <h2 class="font-poster font-bold text-lg sm:text-xl text-slate-900 dark:text-slate-100 leading-snug mb-2">
                                        <?= e($p['nama_program']) ?>
                                    </h2>
                                    <p class="font-reading text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                                        <?= e($p['tujuan']) ?>
                                    </p>
                                </div>

                                <!-- Progress Bar Realisasi -->
                                <div class="pt-1">
                                    <div class="flex items-center justify-between text-[11px] font-mono mb-1.5">
                                        <span class="text-slate-500 dark:text-slate-400 uppercase">Realisasi Progres</span>
                                        <span class="font-bold text-slate-900 dark:text-slate-100"><?= $progres ?>%</span>
                                    </div>
                                    <div class="poster-progress">
                                        <div class="poster-progress-bar" style="width: <?= $progres ?>%"></div>
                                    </div>
                                </div>

                                <!-- Indikator Box -->
                                <div class="space-y-2.5 text-xs text-slate-600 dark:text-slate-300 bg-slate-100/70 dark:bg-slate-800/60 p-3.5 rounded-xl border border-slate-200 dark:border-slate-700">
                                    <?php if (!empty($p['indikator_kualitas'])): ?>
                                        <div><strong class="font-mono uppercase text-[10px] text-slate-900 dark:text-slate-100 block mb-0.5">Indikator Kualitas</strong> <?= e($p['indikator_kualitas']) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($p['indikator_kuantitas'])): ?>
                                        <div><strong class="font-mono uppercase text-[10px] text-slate-900 dark:text-slate-100 block mb-0.5">Indikator Kuantitas</strong> <?= e($p['indikator_kuantitas']) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($p['gambaran_kegiatan'])): ?>
                                        <div><strong class="font-mono uppercase text-[10px] text-slate-900 dark:text-slate-100 block mb-0.5">Gambaran Pelaksanaan</strong> <?= e($p['gambaran_kegiatan']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-3.5 border-t border-slate-200 dark:border-slate-800 bg-slate-100/50 dark:bg-slate-800/40 flex items-center justify-between font-mono text-xs">
                            <span class="text-slate-600 dark:text-slate-400">
                                PJ: <strong class="text-slate-900 dark:text-slate-100"><?= e($p['penanggung_jawab'] ?: '-') ?></strong>
                            </span>
                            <span class="font-bold text-slate-900 dark:text-slate-100">
                                <?= e($p['anggaran'] ?: 'Rp 0') ?>
                            </span>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
