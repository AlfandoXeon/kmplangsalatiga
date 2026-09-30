<?php
use App\Config\App;
use App\Config\Security;

$csrfToken = Security::generateCsrfToken();
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-4 transition-colors">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">Manajemen Program Kerja</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kelola data program kerja, anggaran, penanggung jawab, dan status kegiatan.</p>
        </div>
        <button onclick="document.getElementById('modal-add-proker').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-400 text-slate-950 shadow-sm transition-all">
            <span class="material-symbols-outlined text-base">add</span>
            Tambah Program Kerja
        </button>
    </div>

    <!-- Proker Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm dark:shadow-2xl transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="py-3 px-4">Divisi</th>
                        <th class="py-3 px-4">Nama Program</th>
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Anggaran</th>
                        <th class="py-3 px-4">PJ</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php foreach ($prokers as $p): ?>
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/20">
                                    <?= e($p['divisi']) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900 dark:text-slate-200"><?= e($p['nama_program']) ?></div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-1"><?= e($p['tujuan']) ?></div>
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400"><?= e($p['waktu_kegiatan'] ?: '-') ?></td>
                            <td class="py-3 px-4 font-mono font-bold text-amber-600 dark:text-amber-400"><?= e($p['anggaran'] ?: 'Rp 0') ?></td>
                            <td class="py-3 px-4 text-slate-700 dark:text-slate-300"><?= e($p['penanggung_jawab'] ?: '-') ?></td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $p['status'] === 'selesai' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : ($p['status'] === 'berlangsung' ? 'bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20' : 'bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700') ?>">
                                    <?= e($p['status']) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right space-x-2 whitespace-nowrap">
                                <form action="<?= App::baseUrl('/admin/proker/delete/' . $p['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus program kerja ini?');">
                                    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                    <button type="submit" class="p-1 rounded hover:bg-rose-500/20 text-rose-500 dark:text-rose-400 transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add Proker -->
<div id="modal-add-proker" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl max-w-xl w-full p-6 space-y-4 shadow-2xl transition-colors">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
            <h3 class="font-bold text-sm text-slate-900 dark:text-white font-montserrat">Tambah Program Kerja Baru</h3>
            <button onclick="document.getElementById('modal-add-proker').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 dark:hover:text-white">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>

        <form action="<?= App::baseUrl('/admin/proker') ?>" method="POST" class="space-y-3.5 text-xs">
            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Divisi</label>
                    <input type="text" name="divisi" required placeholder="Contoh: BPH / Olahraga" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Program</label>
                    <input type="text" name="nama_program" required placeholder="Nama kegiatan / proker" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Tujuan</label>
                <textarea name="tujuan" rows="2" placeholder="Tujuan program kerja" class="w-full p-3 bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 focus:outline-none focus:border-amber-500"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Waktu Kegiatan</label>
                    <input type="text" name="waktu_kegiatan" placeholder="Contoh: Setiap Jumat sore" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Estimasi Anggaran</label>
                    <input type="text" name="anggaran" placeholder="Contoh: Rp 500.000" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Penanggung Jawab (PJ)</label>
                    <input type="text" name="penanggung_jawab" placeholder="Nama PJ" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Status Proker</label>
                    <select name="status" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 focus:outline-none focus:border-amber-500 cursor-pointer">
                        <option value="rencana">Rencana</option>
                        <option value="berlangsung">Berlangsung</option>
                        <option value="selesai">Selesai</option>
                        <option value="evaluasi">Evaluasi</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modal-add-proker').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold transition-all shadow-sm">
                    Simpan Program Kerja
                </button>
            </div>
        </form>
    </div>
</div>
