<?php
/**
 * View: members/profile.php
 * -------------------------------------------------------------------------
 * Halaman Kartu Identitas Anggota & Formulir Pembaruan Profil Mandiri.
 * -------------------------------------------------------------------------
 */
use App\Config\App;
use App\Config\Security;
use App\Helpers\Formatter;

$user = $user ?? [];
$csrfToken = Security::generateCsrfToken();
?>

<div class="py-12 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <div class="row g-4">
            <!-- Left Column: Poster Identity Card -->
            <div class="col-12 col-lg-5">
                <div class="poster-card overflow-hidden">
                    <div class="poster-card-header">
                        <span>KARTU ANGGOTA K'MPLANG</span>
                        <span><?= e(strtoupper($user['role'] ?? 'anggota')) ?></span>
                    </div>

                    <div class="p-6 space-y-5">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-amber-400 border-2 border-slate-900 text-slate-950 flex items-center justify-center font-display font-black text-2xl uppercase">
                                <?= substr($user['nama_lengkap'] ?? 'K', 0, 1) ?>
                            </div>
                            <div>
                                <h1 class="font-display font-bold text-xl text-slate-900 dark:text-white"><?= e($user['nama_lengkap']) ?></h1>
                                <p class="font-mono text-xs text-slate-600 dark:text-slate-300 font-bold"><?= e($user['jabatan_organisasi'] ?? 'Anggota') ?></p>
                                <p class="font-mono text-[11px] text-slate-500 dark:text-slate-400">NIM: <?= e($user['nim'] ?: '-') ?></p>
                            </div>
                        </div>

                        <div class="space-y-3 text-xs bg-slate-100/80 dark:bg-slate-900/70 p-4 rounded-xl border border-slate-300 dark:border-slate-700">
                            <div class="flex justify-between">
                                <span class="font-mono text-slate-500 uppercase">Status Akun</span>
                                <span class="font-mono font-bold uppercase text-slate-900 dark:text-slate-100"><?= e($user['status']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-mono text-slate-500 uppercase">Email</span>
                                <span class="font-semibold text-slate-900 dark:text-slate-100"><?= e($user['email']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-mono text-slate-500 uppercase">Asal Daerah</span>
                                <span class="font-semibold text-slate-900 dark:text-slate-100"><?= e($user['asal_daerah']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-mono text-slate-500 uppercase">Fakultas</span>
                                <span class="font-semibold text-slate-900 dark:text-slate-100"><?= e($user['fakultas'] ?: '-') ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-mono text-slate-500 uppercase">Angkatan</span>
                                <span class="font-mono font-semibold text-slate-900 dark:text-slate-100"><?= e($user['angkatan'] ?: '-') ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-mono text-slate-500 uppercase">Bergabung</span>
                                <span class="font-mono text-slate-700 dark:text-slate-300"><?= Formatter::tanggalIndo($user['created_at']) ?></span>
                            </div>
                        </div>

                        <?php if (!empty($user['motivasi'])): ?>
                            <div class="p-4 rounded-xl border border-dashed border-slate-300 dark:border-slate-700">
                                <span class="font-mono text-[10px] uppercase tracking-wider text-slate-500 block mb-1">Catatan Motivasi:</span>
                                <p class="font-serif-reading text-sm text-slate-700 dark:text-slate-300 italic">"<?= e($user['motivasi']) ?>"</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Column: Self-Service Profile Edit Form -->
            <div class="col-12 col-lg-7">
                <div class="poster-card overflow-hidden">
                    <div class="poster-card-header">
                        <span>PENGATURAN AKUN MANDIRI</span>
                        <span>BIODATA &amp; KEAMANAN</span>
                    </div>

                    <form action="<?= App::baseUrl('/profil/update') ?>" method="POST" class="p-6 sm:p-8 space-y-4">
                        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="block font-mono text-[11px] uppercase font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap *</label>
                                <input type="text" name="nama_lengkap" value="<?= e($user['nama_lengkap']) ?>" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-amber-500">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="block font-mono text-[11px] uppercase font-bold text-slate-700 dark:text-slate-300 mb-1">NIM / Nomor Induk</label>
                                <input type="text" name="nim" value="<?= e($user['nim'] ?? '') ?>" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-amber-500">
                            </div>
                            <div class="col-12 col-sm-8">
                                <label class="block font-mono text-[11px] uppercase font-bold text-slate-700 dark:text-slate-300 mb-1">Fakultas / Program Studi</label>
                                <input type="text" name="fakultas" value="<?= e($user['fakultas'] ?? '') ?>" placeholder="Contoh: FTI - Teknik Informatika" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-amber-500">
                            </div>
                            <div class="col-12 col-sm-4">
                                <label class="block font-mono text-[11px] uppercase font-bold text-slate-700 dark:text-slate-300 mb-1">Tahun Angkatan</label>
                                <input type="text" name="angkatan" value="<?= e($user['angkatan'] ?? '') ?>" placeholder="2024" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-amber-500">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="block font-mono text-[11px] uppercase font-bold text-slate-700 dark:text-slate-300 mb-1">Asal Kabupaten/Kota (Lampung) *</label>
                                <input type="text" name="asal_daerah" value="<?= e($user['asal_daerah']) ?>" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-amber-500">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="block font-mono text-[11px] uppercase font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor WhatsApp Aktif</label>
                                <input type="text" name="no_wa" value="<?= e($user['no_wa'] ?? '') ?>" placeholder="08xxxxxxxxxx" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-amber-500">
                            </div>
                            <div class="col-12">
                                <label class="block font-mono text-[11px] uppercase font-bold text-slate-700 dark:text-slate-300 mb-1">Motivasi / Pesan Singkat</label>
                                <textarea name="motivasi" rows="2" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-amber-500"><?= e($user['motivasi'] ?? '') ?></textarea>
                            </div>
                            <div class="col-12">
                                <div class="p-3.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 space-y-2">
                                    <label class="block font-mono text-[11px] uppercase font-bold text-slate-800 dark:text-slate-200">Ganti Kata Sandi (Opsional)</label>
                                    <input type="password" name="password_baru" placeholder="Kosongkan jika tidak ingin mengubah kata sandi (Min. 6 karakter)" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg px-3.5 py-2 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-amber-500">
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 flex justify-end">
                            <button type="submit" class="poster-btn-primary px-6 py-2.5 rounded-lg text-xs">
                                <span class="material-symbols-outlined text-base">save</span>
                                Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
