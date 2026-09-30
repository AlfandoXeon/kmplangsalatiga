<?php
use App\Config\App;
use App\Helpers\Formatter;

$prokers = $prokers ?? [];
$divisiList = $divisiList ?? [];
$activeDivisi = $activeDivisi ?? 'Semua';
$stats = $stats ?? [];
?>

<div class="py-12 bg-slate-50 dark:bg-slate-950 transition-colors duration-200 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <div class="text-xs font-bold uppercase tracking-widest text-amber-600 dark:text-amber-400 mb-2">
                Transparansi Organisasi
            </div>
            <h1 class="text-3xl sm:text-5xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">
                Program Kerja K'mplang
            </h1>
            <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 mt-3">
                Daftar program kerja kepengurusan periode 2025/2026 dari seluruh divisi dengan rincian tujuan, indikator capaian, dan anggaran transparan.
            </p>
        </div>

        <!-- Filter Divisions -->
        <div class="flex items-center justify-center flex-wrap gap-2 mb-10">
            <a href="<?= App::baseUrl('/program-kerja') ?>" class="px-4 py-2 rounded-full text-xs font-bold transition-all <?= $activeDivisi === 'Semua' ? 'bg-amber-500 text-slate-950 shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-400 hover:border-amber-500/50 hover:text-amber-600 dark:hover:text-amber-400 border border-slate-200 dark:border-slate-800' ?>">
                Semua Divisi (<?= count($prokers) ?>)
            </a>
            <?php foreach ($divisiList as $div): ?>
                <a href="<?= App::baseUrl('/program-kerja?divisi=' . urlencode($div)) ?>" class="px-4 py-2 rounded-full text-xs font-bold transition-all <?= $activeDivisi === $div ? 'bg-amber-500 text-slate-950 shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-400 hover:border-amber-500/50 hover:text-amber-600 dark:hover:text-amber-400 border border-slate-200 dark:border-slate-800' ?>">
                    <?= e($div) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Proker Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($prokers as $p): ?>
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 flex flex-col justify-between hover:border-amber-500/40 transition-all shadow-sm">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-bold uppercase bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/20">
                                <?= e($p['divisi']) ?>
                            </span>
                            <?php
                                $statusBadge = match($p['status']) {
                                    'selesai'     => 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-500/20',
                                    'berlangsung' => 'bg-sky-500/10 text-sky-700 dark:text-sky-400 border-sky-500/20',
                                    'evaluasi'    => 'bg-rose-500/10 text-rose-700 dark:text-rose-400 border-rose-500/20',
                                    default       => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700'
                                };
                            ?>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border <?= $statusBadge ?>">
                                <?= e($p['status']) ?>
                            </span>
                        </div>

                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 font-montserrat mb-1"><?= e($p['nama_program']) ?></h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed"><?= e($p['tujuan']) ?></p>
                        </div>

                        <div class="space-y-2 text-xs text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-950/60 p-3.5 rounded-xl border border-slate-200 dark:border-slate-800/80">
                            <?php if (!empty($p['indikator_kualitas'])): ?>
                                <div><strong class="text-slate-800 dark:text-slate-300 font-semibold">Kualitas:</strong> <?= e($p['indikator_kualitas']) ?></div>
                            <?php endif; ?>
                            <?php if (!empty($p['indikator_kuantitas'])): ?>
                                <div><strong class="text-slate-800 dark:text-slate-300 font-semibold">Kuantitas:</strong> <?= e($p['indikator_kuantitas']) ?></div>
                            <?php endif; ?>
                            <?php if (!empty($p['gambaran_kegiatan'])): ?>
                                <div><strong class="text-slate-800 dark:text-slate-300 font-semibold">Kegiatan:</strong> <?= e($p['gambaran_kegiatan']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm text-amber-500">account_circle</span>
                            PJ: <?= e($p['penanggung_jawab'] ?: '-') ?>
                        </span>
                        <span class="font-bold text-amber-600 dark:text-amber-400">
                            <?= e($p['anggaran'] ?: 'Rp 0') ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
