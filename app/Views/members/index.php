<?php
use App\Config\App;
use App\Helpers\Formatter;

$members = $members ?? [];
$search = $search ?? '';
?>

<div class="py-12 bg-slate-50 dark:bg-slate-950 transition-colors duration-200 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-10">
            <div class="text-xs font-bold uppercase tracking-widest text-amber-600 dark:text-amber-400 mb-2">
                Direktori Resmi
            </div>
            <h1 class="text-3xl sm:text-5xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">
                Direktori Anggota K'mplang
            </h1>
            <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 mt-3">
                Daftar mahasiswa perantauan Lampung resmi yang telah terdaftar dalam sistem informasi K'mplang Salatiga.
            </p>
        </div>

        <!-- Search Bar -->
        <div class="max-w-xl mx-auto mb-10">
            <form action="<?= App::baseUrl('/anggota') ?>" method="GET" class="relative">
                <input type="text" name="q" value="<?= e($search) ?>" placeholder="Cari nama anggota, fakultas, atau asal daerah..." class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-full py-3.5 pl-12 pr-28 text-xs sm:text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 shadow-sm transition-all">
                <span class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 dark:text-slate-500 text-xl">search</span>
                <button type="submit" class="absolute right-2 top-2 px-5 py-2 rounded-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-sm transition-all">
                    Cari
                </button>
            </form>
        </div>

        <!-- Members Table -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-100 dark:bg-slate-950 text-slate-600 dark:text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="py-4 px-6 w-16">No.</th>
                            <th class="py-4 px-6">Nama Lengkap</th>
                            <th class="py-4 px-6">Fakultas / Kampus</th>
                            <th class="py-4 px-6">Asal Daerah (Lampung)</th>
                            <th class="py-4 px-6 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        <?php if (empty($members)): ?>
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-500 italic">
                                    Tidak ada data anggota yang cocok dengan pencarian Anda.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($members as $m): ?>
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-4 px-6 font-mono text-slate-400"><?= $no++ ?></td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-900 dark:text-slate-100 font-montserrat"><?= e($m['nama_lengkap']) ?></span>
                                            <?php if ($m['role'] === 'admin'): ?>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-500 text-slate-950">
                                                    Admin
                                                </span>
                                            <?php elseif ($m['role'] === 'pengurus'): ?>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-sky-500/10 text-sky-700 dark:text-sky-400 border border-sky-500/20">
                                                    Pengurus
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($m['nim'])): ?>
                                            <span class="text-[11px] text-slate-500 font-mono"><?= e($m['nim']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 px-6 text-slate-600 dark:text-slate-300"><?= e($m['fakultas'] ?: '-') ?></td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-1.5 text-amber-700 dark:text-amber-400 font-medium">
                                            <span class="material-symbols-outlined text-xs">location_on</span>
                                            <?= e($m['asal_daerah']) ?>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
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

        <div class="text-center mt-6">
            <p class="text-xs text-slate-500">Data anggota terverifikasi resmi &bull; K'mplang Salatiga</p>
        </div>
    </div>
</div>
