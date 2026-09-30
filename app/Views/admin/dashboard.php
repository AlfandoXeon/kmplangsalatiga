<?php
use App\Config\App;
use App\Config\Security;
use App\Helpers\Formatter;

$csrfToken = Security::generateCsrfToken();
?>

<div class="space-y-8">
    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat 1 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl flex items-center justify-between shadow-sm dark:shadow-none transition-colors">
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">Total Anggota</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white font-montserrat mt-1"><?= $totalMembers ?></h3>
                <span class="text-[11px] text-amber-600 dark:text-amber-400 font-bold"><?= $pendingMembers ?> Menunggu Verifikasi</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">groups</span>
            </div>
        </div>

        <!-- Stat 2 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl flex items-center justify-between shadow-sm dark:shadow-none transition-colors">
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">Program Kerja</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white font-montserrat mt-1"><?= $totalProker ?></h3>
                <span class="text-[11px] text-slate-500 dark:text-slate-400">Periode 2025/2026</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sky-500/10 border border-sky-500/20 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">assignment</span>
            </div>
        </div>

        <!-- Stat 3 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl flex items-center justify-between shadow-sm dark:shadow-none transition-colors">
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">Kegiatan &amp; Album</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white font-montserrat mt-1"><?= $totalActivities ?></h3>
                <span class="text-[11px] text-slate-500 dark:text-slate-400">Dokumentasi Google Drive</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">photo_library</span>
            </div>
        </div>

        <!-- Stat 4 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl flex items-center justify-between shadow-sm dark:shadow-none transition-colors">
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">Artikel &amp; Berita</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white font-montserrat mt-1"><?= $totalArticles ?></h3>
                <span class="text-[11px] text-slate-500 dark:text-slate-400">Wawasan Budaya</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">article</span>
            </div>
        </div>
    </div>

    <!-- Quick Shortcuts Banner -->
    <div class="bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-white dark:from-amber-500/20 dark:via-slate-900 dark:to-slate-900 border border-amber-500/30 p-6 rounded-3xl flex flex-col md:flex-row items-center justify-between gap-4 transition-colors">
        <div class="space-y-1">
            <h2 class="text-base font-black text-slate-900 dark:text-white font-montserrat">Pusat Kendali Cepat Organisasi</h2>
            <p class="text-xs text-slate-600 dark:text-slate-400">Akses cepat untuk mempublikasikan kegiatan baru, program kerja, atau menyunting teks website.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="<?= App::baseUrl('/admin/activities/create') ?>" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold bg-amber-500 text-slate-950 hover:bg-amber-400 shadow-sm transition-all">
                <span class="material-symbols-outlined text-base">add_photo_alternate</span>
                Unggah Kegiatan &amp; Album (max 50MB)
            </a>
            <a href="<?= App::baseUrl('/admin/settings') ?>" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-all">
                <span class="material-symbols-outlined text-base">tune</span>
                Ubah Konten Web (CMS)
            </a>
        </div>
    </div>

    <!-- Two Columns: Pending Members & Audit Trail -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left: Recent Members -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 space-y-4 shadow-sm dark:shadow-xl transition-colors">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                <h3 class="font-bold text-sm text-slate-900 dark:text-slate-100 font-montserrat flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">person_add</span>
                    Pendaftaran Anggota Terbaru
                </h3>
                <a href="<?= App::baseUrl('/admin/members') ?>" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">Kelola Semua</a>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
                <?php if (empty($recentMembers)): ?>
                    <p class="text-xs text-slate-400 dark:text-slate-500 italic py-4">Belum ada anggota terdaftar.</p>
                <?php else: ?>
                    <?php foreach ($recentMembers as $m): ?>
                        <div class="py-3 flex items-center justify-between text-xs">
                            <div>
                                <h4 class="font-bold text-slate-800 dark:text-slate-200"><?= e($m['nama_lengkap']) ?></h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400"><?= e($m['email']) ?> &bull; <?= e($m['asal_daerah']) ?></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $m['status'] === 'active' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20' ?>">
                                    <?= e($m['status']) ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: Security Audit Logs -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 space-y-4 shadow-sm dark:shadow-xl transition-colors">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                <h3 class="font-bold text-sm text-slate-900 dark:text-slate-100 font-montserrat flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">security</span>
                    Audit Trail &amp; Log Keamanan
                </h3>
                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-mono">Terkini</span>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800/80 max-h-80 overflow-y-auto pr-1">
                <?php if (empty($recentLogs)): ?>
                    <p class="text-xs text-slate-400 dark:text-slate-500 italic py-4">Belum ada riwayat aktivitas yang tercatat.</p>
                <?php else: ?>
                    <?php foreach ($recentLogs as $log): ?>
                        <div class="py-2.5 space-y-1 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider text-[10px]"><?= e($log['action']) ?></span>
                                <span class="text-[10px] text-slate-400 dark:text-slate-500"><?= Formatter::tanggalIndo($log['created_at'], true) ?></span>
                            </div>
                            <p class="text-[11px] text-slate-600 dark:text-slate-300"><?= e($log['details'] ?: '-') ?></p>
                            <div class="text-[10px] text-slate-400 dark:text-slate-500 flex items-center gap-2 font-mono">
                                <span>Oleh: <?= e($log['nama_lengkap'] ?? 'System') ?></span>
                                <span>&bull;</span>
                                <span>IP: <?= e($log['ip_address']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
