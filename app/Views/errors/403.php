<?php
use App\Config\App;
?>
<div class="min-h-[70vh] flex items-center justify-center text-center px-4">
    <div class="max-w-md space-y-4">
        <span class="material-symbols-outlined text-rose-500 text-6xl">no_accounts</span>
        <h1 class="text-5xl font-black text-slate-900 dark:text-white font-montserrat">403</h1>
        <h2 class="text-xl font-bold text-slate-800 dark:text-slate-200">Akses Ditolak</h2>
        <p class="text-xs text-slate-600 dark:text-slate-400">
            Anda tidak memiliki izin untuk membuka halaman ini. Silakan masuk dengan akun pengurus jika Anda memiliki wewenang.
        </p>
        <div class="pt-4">
            <a href="<?= App::baseUrl('/') ?>" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-xs font-bold bg-amber-500 hover:bg-amber-400 text-slate-950 transition-all shadow-sm">
                <span class="material-symbols-outlined text-sm">home</span>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
