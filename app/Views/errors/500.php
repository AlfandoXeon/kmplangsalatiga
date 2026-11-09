<?php
use App\Config\App;
?>
<div class="min-h-[70vh] flex items-center justify-center text-center px-4 py-12">
    <div class="poster-card max-w-md w-full p-8 space-y-4">
        <div class="poster-kicker justify-center">Perlindungan Lalu Lintas Jaringan</div>
        <h1 class="poster-title text-5xl text-amber-600 dark:text-amber-400"><?= isset($statusCode) ? (int) $statusCode : 503 ?></h1>
        <h2 class="font-display font-bold text-xl text-slate-900 dark:text-slate-100"><?= e($heading ?? 'Layanan Sedang Menyesuaikan Lalu Lintas') ?></h2>
        <p class="font-serif-reading text-sm text-slate-600 dark:text-slate-300">
            <?= e($message ?? 'Permintaan Anda tidak dapat diproses saat ini karena lonjakan lalu lintas atau penyesuaian sumber daya jaringan. Silakan muat ulang halaman beberapa saat lagi.') ?>
        </p>
        <?php if (!empty($debugDetail) && App::get('APP_DEBUG', 'false') === 'true'): ?>
            <div class="text-left p-3 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 font-mono text-[11px] text-slate-700 dark:text-slate-300 overflow-x-auto">
                <?= e($debugDetail) ?>
            </div>
        <?php endif; ?>
        <div class="pt-3 flex items-center justify-center gap-3">
            <a href="<?= App::baseUrl('/') ?>" class="poster-btn-primary px-6 py-2.5 rounded-lg text-xs">
                <span class="material-symbols-outlined text-sm">home</span>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
