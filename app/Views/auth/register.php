<?php
use App\Config\App;
use App\Config\Security;

$csrfToken = Security::generateCsrfToken();
$old = $old ?? [];
$errors = $errors ?? [];
?>

<div class="space-y-6">
    <div class="text-center space-y-1">
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">
            Pendaftaran Anggota
        </h2>
        <p class="text-xs text-slate-600 dark:text-slate-400">
            Lengkapi data diri Anda untuk bergabung bersama keluarga besar K'mplang Salatiga.
        </p>
    </div>

    <form action="<?= App::baseUrl('/register') ?>" method="POST" class="space-y-4">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <!-- Nama Lengkap -->
        <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                Nama Lengkap <span class="text-rose-500">*</span>
            </label>
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">badge</span>
                <input type="text" name="nama_lengkap" value="<?= e($old['nama_lengkap'] ?? '') ?>" required placeholder="Masukkan nama lengkap Anda" class="w-full h-11 pl-11 pr-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
            </div>
        </div>

        <!-- NIM & Fakultas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    NIM (Nomor Induk Mahasiswa)
                </label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">tag</span>
                    <input type="text" name="nim" value="<?= e($old['nim'] ?? '') ?>" placeholder="Contoh: 672024xxx" class="w-full h-11 pl-11 pr-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Fakultas / Universitas
                </label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">school</span>
                    <input type="text" name="fakultas" value="<?= e($old['fakultas'] ?? '') ?>" placeholder="Contoh: FTI UKSW" class="w-full h-11 pl-11 pr-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
                </div>
            </div>
        </div>

        <!-- Asal Daerah -->
        <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                Asal Daerah di Lampung <span class="text-rose-500">*</span>
            </label>
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">location_on</span>
                <input type="text" name="asal_daerah" value="<?= e($old['asal_daerah'] ?? '') ?>" required placeholder="Contoh: Bandar Lampung / Metro / Lampung Timur" class="w-full h-11 pl-11 pr-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
            </div>
        </div>

        <!-- WhatsApp & Tanggal Lahir -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Nomor WhatsApp <span class="text-rose-500">*</span>
                </label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">chat</span>
                    <input type="tel" name="whatsapp" value="<?= e($old['whatsapp'] ?? '') ?>" required placeholder="Contoh: 085712345678" class="w-full h-11 pl-11 pr-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Tanggal Lahir
                </label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">calendar_today</span>
                    <input type="date" name="tanggal_lahir" value="<?= e($old['tanggal_lahir'] ?? '') ?>" class="w-full h-11 pl-11 pr-3 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all cursor-pointer">
                </div>
            </div>
        </div>

        <!-- Email & Password -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Email Pribadi <span class="text-rose-500">*</span>
                </label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">mail</span>
                    <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" required placeholder="nama@email.com" class="w-full h-11 pl-11 pr-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Kata Sandi Akun <span class="text-rose-500">*</span>
                </label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">key</span>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full h-11 pl-11 pr-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
                </div>
            </div>
        </div>

        <!-- Motivasi -->
        <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                Alasan / Motivasi Bergabung <span class="text-slate-400 font-normal">(Opsional)</span>
            </label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-3 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">edit_note</span>
                <textarea name="motivasi" rows="3" placeholder="Ceritakan motivasi atau harapan Anda bergabung bersama K'mplang..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all"><?= e($old['motivasi'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full h-11 rounded-xl font-bold text-xs bg-amber-500 hover:bg-amber-400 text-slate-950 dark:bg-amber-400 dark:hover:bg-amber-300 shadow-sm hover:shadow transition-all inline-flex items-center justify-center gap-2 leading-none">
            <span class="material-symbols-outlined text-[19px] leading-none">how_to_reg</span>
            <span>Kirim Pendaftaran &amp; Buat Akun</span>
        </button>
    </form>

    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 text-center text-xs text-slate-600 dark:text-slate-400">
        <span>Sudah memiliki akun?</span>
        <a href="<?= App::baseUrl('/login') ?>" class="font-bold text-amber-600 dark:text-amber-400 hover:underline ml-1">
            Masuk di sini
        </a>
    </div>
</div>
