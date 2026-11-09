<?php
use App\Config\App;
?>
<div class="min-h-[70vh] flex items-center justify-center text-center px-4 py-12">
    <div class="poster-card max-w-md w-full p-8 space-y-4">
        <div class="poster-kicker justify-center">Pembatasan Hak Akses</div>
        <h1 class="poster-title text-6xl text-rose-600 dark:text-rose-400">403</h1>
        <h2 class="font-display font-bold text-xl text-slate-900 dark:text-slate-100">Akses Tidak Diizinkan</h2>
        <p class="font-serif-reading text-sm text-slate-600 dark:text-slate-300">
            Anda tidak memiliki otorisasi untuk membuka halaman ini. Silakan masuk menggunakan akun pengurus jika Anda memiliki wewenang.
        </p>
        <div class="pt-3">
            <a href="<?= App::baseUrl('/') ?>" class="poster-btn-primary px-6 py-2.5 rounded-lg text-xs">
                <span class="material-symbols-outlined text-sm">home</span>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
