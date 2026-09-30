<?php
use App\Config\App;
use App\Helpers\Formatter;

$user = $user ?? [];
?>

<div class="py-12 bg-slate-50 dark:bg-slate-950 transition-colors duration-200 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm dark:shadow-xl">
            <!-- Header Banner -->
            <div class="h-28 bg-gradient-to-r from-amber-500/20 via-amber-600/20 to-slate-200 dark:to-slate-800 border-b border-slate-200 dark:border-slate-800 p-6 flex items-end">
            </div>

            <!-- Profile Info Body -->
            <div class="px-6 sm:px-10 pb-10 relative">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 -mt-12 mb-6">
                    <div class="w-20 h-20 rounded-2xl bg-amber-500 text-slate-950 p-1 shadow-md border-4 border-white dark:border-slate-900 flex items-center justify-center font-black text-2xl uppercase font-montserrat">
                        <?= substr($user['nama_lengkap'], 0, 1) ?>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/30 w-fit">
                        Role: <?= e($user['role']) ?>
                    </span>
                </div>

                <div class="space-y-6">
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 dark:text-white font-montserrat"><?= e($user['nama_lengkap']) ?></h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-mono mt-0.5">NIM: <?= e($user['nim'] ?: '-') ?> &bull; Status: <span class="capitalize text-emerald-600 dark:text-emerald-400 font-bold"><?= e($user['status']) ?></span></p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs bg-slate-50 dark:bg-slate-950/70 p-5 rounded-2xl border border-slate-200 dark:border-slate-800">
                        <div>
                            <span class="text-slate-400 dark:text-slate-500 block mb-1">Email Terdaftar:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200"><?= e($user['email']) ?></span>
                        </div>
                        <div>
                            <span class="text-slate-400 dark:text-slate-500 block mb-1">Asal Daerah (Lampung):</span>
                            <span class="font-semibold text-amber-700 dark:text-amber-400"><?= e($user['asal_daerah']) ?></span>
                        </div>
                        <div>
                            <span class="text-slate-400 dark:text-slate-500 block mb-1">Fakultas / Kampus:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200"><?= e($user['fakultas'] ?: '-') ?></span>
                        </div>
                        <div>
                            <span class="text-slate-400 dark:text-slate-500 block mb-1">Tanggal Bergabung:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200"><?= Formatter::tanggalIndo($user['created_at']) ?></span>
                        </div>
                    </div>

                    <?php if (!empty($user['motivasi'])): ?>
                        <div class="bg-slate-50 dark:bg-slate-950/40 p-4 rounded-xl border border-slate-200 dark:border-slate-800/80">
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-400 block mb-1">Alasan / Motivasi Bergabung:</span>
                            <p class="text-xs text-slate-700 dark:text-slate-300 italic">"<?= e($user['motivasi']) ?>"</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
