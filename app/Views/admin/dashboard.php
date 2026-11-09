<?php
/**
 * View: admin/dashboard.php
 * -------------------------------------------------------------------------
 * Dashboard Utama Panel Admin K'mplang Salatiga.
 * Mencakup ringkasan metrik organisasi, manajemen Papan Pengumuman & Agenda
 * Poster, pendaftar anggota terbaru, serta Audit Trail keamanan.
 * -------------------------------------------------------------------------
 */
use App\Config\App;
use App\Config\Security;
use App\Helpers\Formatter;

$csrfToken = Security::generateCsrfToken();
$announcements = $announcements ?? [];
$rataProgres = (int) ($rataProgres ?? 0);
?>

<div class="space-y-8">
    <!-- Stat Cards Grid -->
    <div class="row g-3">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="poster-card p-5 flex items-center justify-between">
                <div>
                    <p class="font-mono text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Anggota</p>
                    <h3 class="font-display font-black text-3xl text-slate-900 dark:text-white mt-1"><?= (int) $totalMembers ?></h3>
                    <span class="font-mono text-[11px] text-slate-600 dark:text-slate-400"><?= (int) $pendingMembers ?> Menunggu Verifikasi</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">groups</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="poster-card p-5 flex items-center justify-between">
                <div>
                    <p class="font-mono text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400">Program Kerja</p>
                    <h3 class="font-display font-black text-3xl text-slate-900 dark:text-white mt-1"><?= (int) $totalProker ?></h3>
                    <span class="font-mono text-[11px] text-slate-600 dark:text-slate-400">Rata-rata Capaian: <?= $rataProgres ?>%</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">assignment</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="poster-card p-5 flex items-center justify-between">
                <div>
                    <p class="font-mono text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400">Album Kegiatan</p>
                    <h3 class="font-display font-black text-3xl text-slate-900 dark:text-white mt-1"><?= (int) $totalActivities ?></h3>
                    <span class="font-mono text-[11px] text-slate-600 dark:text-slate-400">Eksplorer Dokumentasi</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">photo_library</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="poster-card p-5 flex items-center justify-between">
                <div>
                    <p class="font-mono text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400">Artikel &amp; Esai</p>
                    <h3 class="font-display font-black text-3xl text-slate-900 dark:text-white mt-1"><?= (int) $totalArticles ?></h3>
                    <span class="font-mono text-[11px] text-slate-600 dark:text-slate-400">Khazanah Budaya</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">article</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Shortcuts Banner -->
    <div class="poster-card p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="poster-kicker">Akses Cepat Pengurus</div>
            <h2 class="font-display font-bold text-lg text-slate-900 dark:text-white">Pusat Kendali Publikasi &amp; Transparansi</h2>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="<?= App::baseUrl('/admin/activities/create') ?>" class="poster-btn-primary px-4 py-2.5 rounded-lg text-xs">
                <span class="material-symbols-outlined text-base">add_photo_alternate</span>
                Unggah Album Kegiatan
            </a>
            <a href="<?= App::baseUrl('/admin/articles/create') ?>" class="poster-btn-secondary px-4 py-2.5 rounded-lg text-xs">
                <span class="material-symbols-outlined text-base">post_add</span>
                Tulis Artikel Baru
            </a>
            <a href="<?= App::baseUrl('/admin/settings') ?>" class="poster-btn-secondary px-4 py-2.5 rounded-lg text-xs">
                <span class="material-symbols-outlined text-base">tune</span>
                Pengaturan CMS
            </a>
        </div>
    </div>

    <!-- Bulletin & Poster Agenda Management Section -->
    <div class="poster-card p-6 space-y-5">
        <div class="poster-card-header">
            <div>
                <div class="poster-kicker">Fitur Tabel Baru: announcements</div>
                <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-slate-700 dark:text-slate-300">campaign</span>
                    Papan Pengumuman &amp; Agenda Poster Beranda
                </h3>
            </div>
            <button type="button" onclick="document.getElementById('form-new-announcement').classList.toggle('hidden')" class="poster-btn-primary px-3.5 py-2 rounded-lg text-xs">
                <span class="material-symbols-outlined text-base">add</span>
                Buat Pengumuman / Agenda
            </button>
        </div>

        <!-- Collapsible Create Announcement Form -->
        <form id="form-new-announcement" action="<?= App::baseUrl('/admin/announcements') ?>" method="POST" class="hidden bg-slate-100/80 dark:bg-slate-900/80 p-5 rounded-xl border border-slate-300 dark:border-slate-700 space-y-4 text-xs">
            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
            <div class="row g-3">
                <div class="col-12 col-md-5">
                    <label class="block font-mono uppercase text-[11px] font-bold mb-1">Judul Poster Pengumuman *</label>
                    <input type="text" name="judul" required placeholder="Contoh: Rapat Evaluasi Tengah Semester" class="w-full h-10 px-3 bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-6 col-md-2">
                    <label class="block font-mono uppercase text-[11px] font-bold mb-1">Tipe</label>
                    <select name="tipe" class="w-full h-10 px-3 bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-lg">
                        <option value="agenda">Agenda</option>
                        <option value="pengumuman">Pengumuman</option>
                        <option value="penting">Penting</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="block font-mono uppercase text-[11px] font-bold mb-1">Tanggal Acara</label>
                    <input type="date" name="tanggal_acara" class="w-full h-10 px-3 bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-md-3">
                    <label class="block font-mono uppercase text-[11px] font-bold mb-1">Lokasi</label>
                    <input type="text" name="lokasi" placeholder="Sekretariat K'mplang / Zoom" class="w-full h-10 px-3 bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-md-9">
                    <label class="block font-mono uppercase text-[11px] font-bold mb-1">Isi Ringkas Pengumuman *</label>
                    <input type="text" name="isi" required placeholder="Keterangan singkat agenda atau himbauan untuk seluruh anggota..." class="w-full h-10 px-3 bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-md-3 d-flex align-items-end">
                    <button type="submit" class="poster-btn-primary w-full justify-center h-10 rounded-lg text-xs">
                        Terbitkan ke Beranda
                    </button>
                </div>
            </div>
        </form>

        <!-- Announcements List -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                <thead class="bg-slate-100 dark:bg-slate-900 font-mono text-[10px] uppercase border-b border-slate-300 dark:border-slate-700">
                    <tr>
                        <th class="py-2.5 px-3">Tipe</th>
                        <th class="py-2.5 px-3">Judul &amp; Isi</th>
                        <th class="py-2.5 px-3">Jadwal &amp; Lokasi</th>
                        <th class="py-2.5 px-3 text-center">Status Tayang</th>
                        <th class="py-2.5 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    <?php if (empty($announcements)): ?>
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400 italic">Belum ada pengumuman atau agenda poster.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($announcements as $ann): ?>
                            <tr>
                                <td class="py-3 px-3">
                                    <span class="poster-badge">
                                        <?= e($ann['tipe']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="font-bold text-slate-900 dark:text-slate-100"><?= e($ann['judul']) ?></div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-1"><?= e($ann['isi']) ?></div>
                                </td>
                                <td class="py-3 px-3 font-mono text-[11px]">
                                    <div><?= !empty($ann['tanggal_acara']) ? Formatter::tanggalIndo($ann['tanggal_acara']) : '-' ?></div>
                                    <div class="text-slate-500"><?= e($ann['lokasi'] ?: '-') ?></div>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <form action="<?= App::baseUrl('/admin/announcements/toggle/' . $ann['id']) ?>" method="POST" class="inline">
                                        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                        <button type="submit" class="px-2.5 py-1 rounded text-[10px] font-mono font-bold uppercase border <?= (int) $ann['is_active'] === 1 ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 border-slate-900 dark:border-slate-100' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 border-slate-300 dark:border-slate-700' ?>">
                                            <?= (int) $ann['is_active'] === 1 ? 'Aktif Tayang' : 'Nonaktif' ?>
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <form action="<?= App::baseUrl('/admin/announcements/delete/' . $ann['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Hapus pengumuman ini?');">
                                        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                        <button type="submit" class="p-1 text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-200 dark:hover:bg-slate-800 rounded">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Two Columns: Recent Members & Audit Trail -->
    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="poster-card p-6 space-y-4 h-100">
                <div class="poster-card-header">
                    <h3 class="font-display font-bold text-base text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <span class="material-symbols-outlined text-slate-700 dark:text-slate-300">person_add</span>
                        Pendaftaran Anggota Terbaru
                    </h3>
                    <a href="<?= App::baseUrl('/admin/members') ?>" class="font-mono text-xs font-bold text-slate-700 dark:text-slate-300 hover:underline">Kelola Semua &rarr;</a>
                </div>

                <div class="divide-y divide-slate-200 dark:divide-slate-800">
                    <?php if (empty($recentMembers)): ?>
                        <p class="text-xs text-slate-500 italic py-4">Belum ada anggota terdaftar.</p>
                    <?php else: ?>
                        <?php foreach ($recentMembers as $m): ?>
                            <div class="py-3 flex items-center justify-between text-xs">
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-slate-100"><?= e($m['nama_lengkap']) ?></h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400"><?= e($m['email']) ?> &bull; <?= e($m['asal_daerah']) ?></p>
                                </div>
                                <span class="px-2.5 py-0.5 rounded font-mono text-[10px] font-bold uppercase bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700">
                                    <?= e($m['status']) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="poster-card p-6 space-y-4 h-100">
                <div class="poster-card-header">
                    <h3 class="font-display font-bold text-base text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <span class="material-symbols-outlined text-slate-700 dark:text-slate-300">security</span>
                        Audit Trail &amp; Log Keamanan
                    </h3>
                    <span class="font-mono text-[10px] uppercase text-slate-500">Realtime</span>
                </div>

                <div class="divide-y divide-slate-200 dark:divide-slate-800 max-h-80 overflow-y-auto pr-1">
                    <?php if (empty($recentLogs)): ?>
                        <p class="text-xs text-slate-500 italic py-4">Belum ada riwayat aktivitas yang tercatat.</p>
                    <?php else: ?>
                        <?php foreach ($recentLogs as $log): ?>
                            <div class="py-2.5 space-y-1 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-mono font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider text-[10px]"><?= e($log['action']) ?></span>
                                    <span class="font-mono text-[10px] text-slate-500"><?= Formatter::tanggalIndo($log['created_at'], true) ?></span>
                                </div>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300"><?= e($log['details'] ?: '-') ?></p>
                                <div class="text-[10px] text-slate-500 flex items-center gap-2 font-mono">
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
</div>
