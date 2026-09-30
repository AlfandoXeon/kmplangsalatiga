<?php
use App\Config\App;
use App\Config\Security;

$csrfToken = Security::generateCsrfToken();
?>

<div class="space-y-6">
    <div class="text-center space-y-1">
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">
            Masuk ke Akun
        </h2>
        <p class="text-xs text-slate-600 dark:text-slate-400">
            Gunakan Email atau NIM yang telah terdaftar di K'mplang Salatiga.
        </p>
    </div>

    <form action="<?= App::baseUrl('/login') ?>" method="POST" class="space-y-4">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                Email atau NIM
            </label>
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">person</span>
                <input type="text" name="identifier" required placeholder="nama@email.com atau 672024xxx" class="w-full h-11 pl-11 pr-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                Kata Sandi
            </label>
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">key</span>
                <input type="password" name="password" required placeholder="Masukkan kata sandi akun Anda" class="w-full h-11 pl-11 pr-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
            </div>
        </div>

        <button type="submit" class="w-full h-11 rounded-xl font-bold text-xs bg-amber-500 hover:bg-amber-400 text-slate-950 dark:bg-amber-400 dark:hover:bg-amber-300 shadow-sm hover:shadow transition-all inline-flex items-center justify-center gap-2 leading-none">
            <span class="material-symbols-outlined text-[19px] leading-none">login</span>
            <span>Masuk Sekarang</span>
        </button>
    </form>

    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 text-center text-xs text-slate-600 dark:text-slate-400">
        <span>Belum terdaftar sebagai anggota?</span>
        <a href="<?= App::baseUrl('/register') ?>" class="font-bold text-amber-600 dark:text-amber-400 hover:underline ml-1">
            Daftar di sini
        </a>
    </div>
</div>
