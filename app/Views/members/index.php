<?php
/**
 * View: members/index.php
 * -------------------------------------------------------------------------
 * Direktori Resmi Anggota K'mplang Salatiga bergaya Poster Arsip.
 * Mendukung pencarian nama/fakultas/daerah serta filter angkatan.
 * -------------------------------------------------------------------------
 */
use App\Config\App;

$members = $members ?? [];
$search = $search ?? '';
$angkatan = $angkatan ?? '';
$angkatanList = $angkatanList ?? [];
?>

<div class="py-12 min-h-screen overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Poster Header -->
        <div class="poster-card p-6 sm:p-10" data-aos="fade-up">
            <div class="poster-kicker mb-2">Basis Data Keanggotaan Terverifikasi</div>
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                <div>
                    <h1 class="poster-title text-3xl sm:text-5xl text-slate-900 dark:text-slate-100">
                        Direktori Anggota K'mplang
                    </h1>
                    <p class="font-serif-reading text-base text-slate-600 dark:text-slate-300 mt-3 max-w-2xl">
                        Daftar mahasiswa perantauan asal Provinsi Lampung di Kota Salatiga yang terdaftar dan terverifikasi aktif dalam sistem informasi organisasi.
                    </p>
                </div>
                <div class="font-mono text-xs uppercase tracking-widest px-4 py-2.5 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 shrink-0">
                    Terdaftar: <strong><?= count($members) ?> Anggota Aktif</strong>
                </div>
            </div>
        </div>

        <!-- Search & Angkatan Filter Bar -->
        <div class="poster-card p-5">
            <form action="<?= App::baseUrl('/anggota') ?>" method="GET" class="row g-3 align-items-center">
                <div class="col-12 col-md-7">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-3 text-slate-400 text-lg">search</span>
                        <input type="text" name="q" value="<?= e($search) ?>" placeholder="Cari nama lengkap, NIM, fakultas, jabatan, atau kabupaten asal..." class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg py-2.5 pl-11 pr-4 text-xs sm:text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-amber-500">
                    </div>
                </div>
                <div class="col-7 col-md-3">
                    <select name="angkatan" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg py-2.5 px-3 text-xs sm:text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-amber-500">
                        <option value="">Semua Angkatan</option>
                        <?php foreach ($angkatanList as $angk): ?>
                            <option value="<?= e($angk) ?>" <?= $angkatan === (string) $angk ? 'selected' : '' ?>>Angkatan <?= e($angk) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-5 col-md-2 d-flex gap-2">
                    <button type="submit" class="poster-btn-primary w-full justify-center py-2.5 rounded-lg text-xs">
                        Filter
                    </button>
                    <?php if ($search !== '' || $angkatan !== ''): ?>
                        <a href="<?= App::baseUrl('/anggota') ?>" class="poster-btn-secondary px-3 py-2.5 rounded-lg text-xs" title="Reset Filter">
                            <span class="material-symbols-outlined text-sm">restart_alt</span>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Members Directory Table -->
        <div class="poster-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-100 dark:bg-slate-900 font-mono text-slate-600 dark:text-slate-400 uppercase text-[11px] tracking-wider border-b-2 border-slate-900 dark:border-slate-700">
                        <tr>
                            <th class="py-4 px-5 w-14">No.</th>
                            <th class="py-4 px-5">Nama &amp; Identitas</th>
                            <th class="py-4 px-5">Jabatan Organisasi</th>
                            <th class="py-4 px-5">Fakultas / Angkatan</th>
                            <th class="py-4 px-5">Asal Daerah (Lampung)</th>
                            <th class="py-4 px-5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        <?php if (empty($members)): ?>
                            <tr>
                                <td colspan="6" class="py-14 text-center text-slate-500 italic">
                                    Tidak ada data anggota yang cocok dengan kriteria pencarian Anda.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($members as $m): ?>
                                <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-4 px-5 font-mono text-xs text-slate-400"><?= str_pad((string) $no++, 2, '0', STR_PAD_LEFT) ?></td>
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg bg-slate-200 dark:bg-slate-800 border border-slate-400 dark:border-slate-600 text-slate-900 dark:text-slate-100 font-display font-bold text-sm flex items-center justify-center shrink-0 uppercase">
                                                <?= substr($m['nama_lengkap'], 0, 1) ?>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="font-display font-bold text-base text-slate-900 dark:text-slate-100"><?= e($m['nama_lengkap']) ?></span>
                                                    <?php if ($m['role'] === 'admin'): ?>
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700">Admin</span>
                                                    <?php elseif ($m['role'] === 'pengurus'): ?>
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700">Pengurus</span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if (!empty($m['nim'])): ?>
                                                    <span class="text-[11px] text-slate-500 font-mono">NIM: <?= e($m['nim']) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-5 font-medium text-slate-800 dark:text-slate-200">
                                        <?= e($m['jabatan_organisasi'] ?? 'Anggota') ?>
                                    </td>
                                    <td class="py-4 px-5">
                                        <div class="text-slate-800 dark:text-slate-200"><?= e($m['fakultas'] ?: '-') ?></div>
                                        <?php if (!empty($m['angkatan'])): ?>
                                            <span class="font-mono text-[11px] text-slate-500">Angkatan <?= e($m['angkatan']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 px-5">
                                        <div class="inline-flex items-center gap-1.5 text-slate-700 dark:text-slate-200 font-semibold text-xs bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded border border-slate-300 dark:border-slate-700">
                                            <span class="material-symbols-outlined text-xs text-slate-500">location_on</span>
                                            <?= e($m['asal_daerah']) ?>
                                        </div>
                                    </td>
                                    <td class="py-4 px-5 text-center">
                                        <span class="inline-flex items-center gap-1.5 font-mono text-xs font-bold text-slate-700 dark:text-slate-300">
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                            Aktif
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
