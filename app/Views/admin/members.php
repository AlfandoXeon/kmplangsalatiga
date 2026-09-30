<?php
use App\Config\App;
use App\Config\Security;
use App\Helpers\Formatter;

$csrfToken = Security::generateCsrfToken();
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-4 transition-colors">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">Manajemen &amp; Verifikasi Anggota</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kelola data pendaftar: setujui akun, ubah role (Anggota, Pengurus, atau Admin), dan ekspor data anggota.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= App::baseUrl('/admin/members/export') ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-800 dark:text-amber-400 border border-slate-200 dark:border-slate-700 transition-all shadow-sm">
                <span class="material-symbols-outlined text-base">download</span>
                Ekspor ke CSV
            </a>
        </div>
    </div>

    <!-- Filter Status Tabs -->
    <div class="flex items-center gap-2">
        <a href="<?= App::baseUrl('/admin/members') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all <?= empty($statusFilter) ? 'bg-amber-500 text-slate-950' : 'bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:text-slate-950 dark:hover:text-white' ?>">
            Semua Anggota
        </a>
        <a href="<?= App::baseUrl('/admin/members?status=pending') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all <?= $statusFilter === 'pending' ? 'bg-amber-500 text-slate-950' : 'bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:text-slate-950 dark:hover:text-white' ?>">
            Menunggu Persetujuan
        </a>
        <a href="<?= App::baseUrl('/admin/members?status=active') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all <?= $statusFilter === 'active' ? 'bg-amber-500 text-slate-950' : 'bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:text-slate-950 dark:hover:text-white' ?>">
            Aktif
        </a>
        <a href="<?= App::baseUrl('/admin/members?status=rejected') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all <?= $statusFilter === 'rejected' ? 'bg-amber-500 text-slate-950' : 'bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:text-slate-950 dark:hover:text-white' ?>">
            Ditolak
        </a>
    </div>

    <!-- Members Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm dark:shadow-2xl transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="py-3 px-4">Nama &amp; NIM</th>
                        <th class="py-3 px-4">Asal &amp; Fakultas</th>
                        <th class="py-3 px-4">Nomor WhatsApp</th>
                        <th class="py-3 px-4">Role</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php if (empty($members)): ?>
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 dark:text-slate-500 italic">Tidak ada data anggota pada kategori ini.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($members as $m): ?>
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-900 dark:text-slate-200"><?= e($m['nama_lengkap']) ?></div>
                                    <div class="text-[11px] text-slate-500 font-mono">NIM: <?= e($m['nim'] ?: '-') ?></div>
                                    <div class="text-[11px] text-slate-400 dark:text-slate-500"><?= e($m['email']) ?></div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-amber-600 dark:text-amber-400 font-medium"><?= e($m['asal_daerah']) ?></div>
                                    <div class="text-slate-500 dark:text-slate-400 text-[11px]"><?= e($m['fakultas'] ?: '-') ?></div>
                                </td>
                                <td class="py-3 px-4 font-mono">
                                    <?php if (!empty($m['whatsapp']) && $m['whatsapp'] !== '-'): ?>
                                        <div class="flex items-center gap-2">
                                            <span><?= e($m['whatsapp']) ?></span>
                                            <a href="https://wa.me/<?= preg_replace('/[^\d]/', '', $m['whatsapp']) ?>" target="_blank" class="p-1 rounded bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500 hover:text-slate-950 transition-colors" title="Kirim Pesan WhatsApp">
                                                <span class="material-symbols-outlined text-sm">chat</span>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-slate-400 dark:text-slate-500">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?php if ($m['id'] == ($_SESSION['user']['id'] ?? null)): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30" title="Akun Anda yang sedang aktif">
                                            <span class="material-symbols-outlined text-xs">shield_person</span>
                                            Admin (Anda)
                                        </span>
                                    <?php else: ?>
                                        <form action="<?= App::baseUrl('/admin/members/role/' . $m['id']) ?>" method="POST" class="inline-flex items-center">
                                            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                            <select name="role" onchange="if(confirm('Ubah role <?= e(addslashes($m['nama_lengkap'])) ?> menjadi ' + this.value.toUpperCase() + '?')) { this.form.submit(); } else { this.value = '<?= $m['role'] ?>'; }" class="bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 hover:border-amber-500 focus:border-amber-500 text-[11px] rounded-lg px-2.5 py-1 font-bold transition-all cursor-pointer <?= $m['role'] === 'admin' ? 'text-amber-600 dark:text-amber-400' : ($m['role'] === 'pengurus' ? 'text-sky-600 dark:text-cyan-400' : 'text-slate-700 dark:text-slate-300') ?>" title="Klik untuk mengubah role anggota">
                                                <option value="anggota" <?= $m['role'] === 'anggota' ? 'selected' : '' ?>>Anggota</option>
                                                <option value="pengurus" <?= $m['role'] === 'pengurus' ? 'selected' : '' ?>>Pengurus</option>
                                                <option value="admin" <?= $m['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                            </select>
                                        </form>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $m['status'] === 'active' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : ($m['status'] === 'pending' ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20') ?>">
                                        <?= e($m['status']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
                                    <?php if ($m['status'] !== 'active'): ?>
                                        <form action="<?= App::baseUrl('/admin/members/status/' . $m['id']) ?>" method="POST" class="inline">
                                            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="px-2.5 py-1 rounded bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-[11px] shadow-sm" title="Setujui Akun">
                                                Setujui
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($m['status'] !== 'rejected'): ?>
                                        <form action="<?= App::baseUrl('/admin/members/status/' . $m['id']) ?>" method="POST" class="inline">
                                            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-rose-600 dark:text-rose-400 font-bold text-[11px] transition-colors" title="Tolak Akun">
                                                Tolak
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($m['role'] !== 'admin'): ?>
                                        <form action="<?= App::baseUrl('/admin/members/delete/' . $m['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Hapus permanen data anggota ini?');">
                                            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                            <button type="submit" class="p-1 rounded hover:bg-rose-500/20 text-rose-500 dark:text-rose-400 transition-colors" title="Hapus">
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
