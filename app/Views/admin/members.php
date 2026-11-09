<?php
/**
 * View: admin/members.php
 * -------------------------------------------------------------------------
 * Manajemen & Verifikasi Anggota K'mplang Salatiga.
 * Mendukung pencarian anggota, filter status, perubahan role + jabatan
 * + angkatan secara langsung, serta ekspor data ke CSV.
 * -------------------------------------------------------------------------
 */
use App\Config\App;
use App\Config\Security;

$csrfToken = Security::generateCsrfToken();
$members = $members ?? [];
$statusFilter = $statusFilter ?? null;
$search = $search ?? '';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b-2 border-slate-900 dark:border-slate-700 pb-4">
        <div>
            <div class="poster-kicker">Administrasi Keanggotaan</div>
            <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white">Manajemen &amp; Verifikasi Anggota</h2>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= App::baseUrl('/admin/members/export') ?>" class="poster-btn-secondary px-4 py-2 rounded-lg text-xs">
                <span class="material-symbols-outlined text-base">download</span>
                Ekspor Data CSV
            </a>
        </div>
    </div>

    <!-- Filter Status & Search Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            <a href="<?= App::baseUrl('/admin/members') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider border <?= empty($statusFilter) ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 border-slate-900 dark:border-slate-100' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700' ?>">
                Semua
            </a>
            <a href="<?= App::baseUrl('/admin/members?status=pending') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider border <?= $statusFilter === 'pending' ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 border-slate-900 dark:border-slate-100' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700' ?>">
                Pending
            </a>
            <a href="<?= App::baseUrl('/admin/members?status=active') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider border <?= $statusFilter === 'active' ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 border-slate-900 dark:border-slate-100' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700' ?>">
                Aktif
            </a>
            <a href="<?= App::baseUrl('/admin/members?status=rejected') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider border <?= $statusFilter === 'rejected' ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 border-slate-900 dark:border-slate-100' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700' ?>">
                Ditolak
            </a>
        </div>

        <form action="<?= App::baseUrl('/admin/members') ?>" method="GET" class="flex items-center gap-2">
            <input type="text" name="q" value="<?= e($search) ?>" placeholder="Cari nama, NIM, atau daerah..." class="h-9 px-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-xs">
            <button type="submit" class="poster-btn-primary px-3.5 h-9 rounded-lg text-xs">Cari</button>
        </form>
    </div>

    <!-- Members Table -->
    <div class="poster-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                <thead class="bg-slate-100 dark:bg-slate-900 font-mono text-slate-600 dark:text-slate-400 uppercase text-[10px] tracking-wider border-b-2 border-slate-900 dark:border-slate-700">
                    <tr>
                        <th class="py-3.5 px-4">Nama &amp; NIM</th>
                        <th class="py-3.5 px-4">Asal &amp; Fakultas</th>
                        <th class="py-3.5 px-4">WhatsApp</th>
                        <th class="py-3.5 px-4">Role &amp; Jabatan Organisasi</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    <?php if (empty($members)): ?>
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-500 italic">Tidak ada data anggota pada kategori ini.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($members as $m): ?>
                            <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="font-display font-bold text-sm text-slate-900 dark:text-slate-100"><?= e($m['nama_lengkap']) ?></div>
                                    <div class="text-[11px] text-slate-500 font-mono">NIM: <?= e($m['nim'] ?: '-') ?> &bull; Angkatan: <?= e($m['angkatan'] ?: '-') ?></div>
                                    <div class="text-[11px] text-slate-500"><?= e($m['email']) ?></div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="text-slate-900 dark:text-slate-100 font-semibold"><?= e($m['asal_daerah']) ?></div>
                                    <div class="text-slate-500 text-[11px]"><?= e($m['fakultas'] ?: '-') ?></div>
                                </td>
                                <td class="py-3.5 px-4 font-mono">
                                    <?php if (!empty($m['whatsapp']) && $m['whatsapp'] !== '-'): ?>
                                        <div class="flex items-center gap-1.5">
                                            <span><?= e($m['whatsapp']) ?></span>
                                            <a href="https://wa.me/<?= preg_replace('/[^\d]/', '', $m['whatsapp']) ?>" target="_blank" class="p-1 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 transition-colors" title="Kirim Pesan WhatsApp">
                                                <span class="material-symbols-outlined text-sm">chat</span>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-slate-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-4">
                                    <form action="<?= App::baseUrl('/admin/members/role/' . $m['id']) ?>" method="POST" class="flex flex-wrap items-center gap-1.5">
                                        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                        <?php if ($m['id'] == ($_SESSION['user_id'] ?? null)): ?>
                                            <input type="hidden" name="role" value="<?= e($m['role']) ?>">
                                            <span class="px-2 py-1 rounded text-[10px] font-mono font-bold uppercase bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950">Admin (Anda)</span>
                                        <?php else: ?>
                                            <select name="role" class="bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-[11px] rounded px-2 py-1 font-bold">
                                                <option value="anggota" <?= $m['role'] === 'anggota' ? 'selected' : '' ?>>Anggota</option>
                                                <option value="pengurus" <?= $m['role'] === 'pengurus' ? 'selected' : '' ?>>Pengurus</option>
                                                <option value="admin" <?= $m['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                            </select>
                                        <?php endif; ?>
                                        <input type="text" name="jabatan_organisasi" value="<?= e($m['jabatan_organisasi'] ?? 'Anggota') ?>" placeholder="Jabatan" class="w-28 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-[11px] rounded px-2 py-1">
                                        <input type="text" name="angkatan" value="<?= e($m['angkatan'] ?? '') ?>" placeholder="Thn" class="w-16 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-[11px] rounded px-2 py-1 font-mono">
                                        <button type="submit" class="px-2 py-1 rounded bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 font-bold text-[10px]" title="Simpan Role & Jabatan">
                                            Simpan
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="px-2.5 py-0.5 rounded font-mono text-[10px] font-bold uppercase border bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700">
                                        <?= e($m['status']) ?>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                                    <?php if ($m['status'] !== 'active'): ?>
                                        <form action="<?= App::baseUrl('/admin/members/status/' . $m['id']) ?>" method="POST" class="inline">
                                            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="px-2.5 py-1 rounded bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-950 font-bold text-[11px]">
                                                Setujui
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($m['status'] !== 'rejected' && $m['id'] != ($_SESSION['user_id'] ?? null)): ?>
                                        <form action="<?= App::baseUrl('/admin/members/status/' . $m['id']) ?>" method="POST" class="inline">
                                            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="px-2.5 py-1 rounded bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-[11px] border border-slate-300 dark:border-slate-700">
                                                Tolak
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($m['role'] !== 'admin'): ?>
                                        <form action="<?= App::baseUrl('/admin/members/delete/' . $m['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Hapus permanen data anggota ini?');">
                                            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                            <button type="submit" class="p-1 rounded hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100" title="Hapus">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
