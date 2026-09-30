<?php
use App\Config\App;
?>
<div class="min-h-[70vh] flex items-center justify-center text-center px-4">
    <div class="max-w-md space-y-4">
        <span class="material-symbols-outlined text-amber-500 text-6xl">search_off</span>
        <h1 class="text-5xl font-black text-slate-900 dark:text-white font-montserrat">404</h1>
        <h2 class="text-xl font-bold text-slate-800 dark:text-slate-200">Halaman Tidak Ditemukan</h2>
        <p class="text-xs text-slate-600 dark:text-slate-400">
            Halaman yang Anda tuju mungkin telah dipindahkan, dihapus, atau tautan yang Anda masukkan salah.
        </p>
        <div class="pt-4">
            <a href="<?= App::baseUrl('/') ?>" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-xs font-bold bg-amber-500 hover:bg-amber-400 text-slate-950 transition-all shadow-sm">
                <span class="material-symbols-outlined text-sm">home</span>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
