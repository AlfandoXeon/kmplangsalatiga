<?php
/**
 * View: admin/proker.php
 * -------------------------------------------------------------------------
 * Manajemen Program Kerja Organisasi (Create, Edit, Delete).
 * Dilengkapi modal tambah & modal edit interaktif dengan dukungan
 * progres realisasi (0-100%), periode, indikator kualitas & kuantitas.
 * -------------------------------------------------------------------------
 */
use App\Config\App;
use App\Config\Security;

$csrfToken = Security::generateCsrfToken();
$prokers = $prokers ?? [];
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b-2 border-slate-900 dark:border-slate-700 pb-4">
        <div>
            <div class="poster-kicker">Manajemen Rencana Strategis</div>
            <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white">Program Kerja K'mplang</h2>
        </div>
        <button type="button" onclick="document.getElementById('modal-add-proker').classList.remove('hidden')" class="poster-btn-primary px-4 py-2.5 rounded-lg text-xs">
            <span class="material-symbols-outlined text-base">add</span>
            Tambah Program Kerja
        </button>
    </div>

    <!-- Proker Table -->
    <div class="poster-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                <thead class="bg-slate-100 dark:bg-slate-900 font-mono text-slate-600 dark:text-slate-400 uppercase text-[10px] tracking-wider border-b-2 border-slate-900 dark:border-slate-700">
                    <tr>
                        <th class="py-3.5 px-4">Divisi</th>
                        <th class="py-3.5 px-4">Program &amp; Tujuan</th>
                        <th class="py-3.5 px-4">Progres</th>
                        <th class="py-3.5 px-4">Anggaran &amp; PJ</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    <?php foreach ($prokers as $p): ?>
                        <?php $progres = (int) ($p['progres_persen'] ?? 0); ?>
                        <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="poster-badge poster-badge-gold">
                                    <?= e($p['divisi']) ?>
                                </span>
                                <div class="font-mono text-[10px] text-slate-400 mt-1"><?= e($p['periode'] ?? '2026/2027') ?></div>
                            </td>
                            <td class="py-3.5 px-4 max-w-md">
                                <div class="font-display font-bold text-sm text-slate-900 dark:text-slate-100"><?= e($p['nama_program']) ?></div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-2 mt-0.5"><?= e($p['tujuan']) ?></div>
                            </td>
                            <td class="py-3.5 px-4 w-36">
                                <div class="flex justify-between font-mono text-[10px] mb-1">
                                    <span>Realisasi</span>
                                    <span class="font-bold"><?= $progres ?>%</span>
                                </div>
                                <div class="poster-progress">
                                    <div class="poster-progress-bar" style="width: <?= $progres ?>%"></div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-mono font-bold text-slate-900 dark:text-slate-100"><?= e($p['anggaran'] ?: 'Rp 0') ?></div>
                                <div class="text-[11px] text-slate-500">PJ: <?= e($p['penanggung_jawab'] ?: '-') ?></div>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded font-mono text-[10px] font-bold uppercase border bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700">
                                    <?= e($p['status']) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1">
                                <button type="button"
                                        onclick='openEditProkerModal(<?= json_encode($p, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'
                                        class="p-1.5 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 transition-colors"
                                        title="Edit Program Kerja">
                                    <span class="material-symbols-outlined text-base">edit</span>
                                </button>
                                <form action="<?= App::baseUrl('/admin/proker/delete/' . $p['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus program kerja ini?');">
                                    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                    <button type="submit" class="p-1.5 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-300 dark:border-slate-700 transition-colors" title="Hapus">
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
<div id="modal-add-proker" class="fixed inset-0 z-50 bg-slate-950/75 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="poster-card max-w-2xl w-full p-6 space-y-4 max-h-[92vh] overflow-y-auto">
        <div class="poster-card-header">
            <h3 class="font-display font-bold text-base text-slate-900 dark:text-white">Tambah Program Kerja Baru</h3>
            <button type="button" onclick="document.getElementById('modal-add-proker').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 dark:hover:text-white">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>

        <form action="<?= App::baseUrl('/admin/proker') ?>" method="POST" class="space-y-3.5 text-xs">
            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

            <div class="row g-3">
                <div class="col-12 col-sm-5">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Divisi *</label>
                    <input type="text" name="divisi" required placeholder="Contoh: BPH / Olahraga" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Nama Program *</label>
                    <input type="text" name="nama_program" required placeholder="Nama program kerja" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-sm-3">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Periode</label>
                    <input type="text" name="periode" value="2026/2027" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
            </div>

            <div>
                <label class="block font-mono uppercase text-[10px] font-bold mb-1">Tujuan Program</label>
                <textarea name="tujuan" rows="2" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg"></textarea>
            </div>

            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Indikator Kualitas</label>
                    <input type="text" name="indikator_kualitas" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-sm-6">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Indikator Kuantitas</label>
                    <input type="text" name="indikator_kuantitas" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-sm-6">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Waktu Pelaksanaan</label>
                    <input type="text" name="waktu_kegiatan" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-sm-6">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Gambaran Kegiatan</label>
                    <input type="text" name="gambaran_kegiatan" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-sm-3">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Anggaran</label>
                    <input type="text" name="anggaran" placeholder="Rp 500.000" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-sm-3">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">PJ</label>
                    <input type="text" name="penanggung_jawab" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-6 col-sm-3">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Status</label>
                    <select name="status" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                        <option value="rencana">Rencana</option>
                        <option value="berlangsung">Berlangsung</option>
                        <option value="selesai">Selesai</option>
                        <option value="evaluasi">Evaluasi</option>
                    </select>
                </div>
                <div class="col-6 col-sm-3">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Progres (0-100%)</label>
                    <input type="number" name="progres_persen" min="0" max="100" value="0" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-200 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('modal-add-proker').classList.add('hidden')" class="poster-btn-secondary px-4 py-2 rounded-lg text-xs">Batal</button>
                <button type="submit" class="poster-btn-primary px-5 py-2 rounded-lg text-xs">Simpan Program Kerja</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Proker -->
<div id="modal-edit-proker" class="fixed inset-0 z-50 bg-slate-950/75 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="poster-card max-w-2xl w-full p-6 space-y-4 max-h-[92vh] overflow-y-auto">
        <div class="poster-card-header">
            <h3 class="font-display font-bold text-base text-slate-900 dark:text-white">Edit Program Kerja</h3>
            <button type="button" onclick="document.getElementById('modal-edit-proker').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 dark:hover:text-white">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>

        <form id="form-edit-proker" action="" method="POST" class="space-y-3.5 text-xs">
            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

            <div class="row g-3">
                <div class="col-12 col-sm-5">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Divisi *</label>
                    <input type="text" id="edit-divisi" name="divisi" required class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Nama Program *</label>
                    <input type="text" id="edit-nama_program" name="nama_program" required class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-sm-3">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Periode</label>
                    <input type="text" id="edit-periode" name="periode" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
            </div>

            <div>
                <label class="block font-mono uppercase text-[10px] font-bold mb-1">Tujuan Program</label>
                <textarea id="edit-tujuan" name="tujuan" rows="2" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg"></textarea>
            </div>

            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Indikator Kualitas</label>
                    <input type="text" id="edit-indikator_kualitas" name="indikator_kualitas" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-sm-6">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Indikator Kuantitas</label>
                    <input type="text" id="edit-indikator_kuantitas" name="indikator_kuantitas" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-sm-6">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Waktu Pelaksanaan</label>
                    <input type="text" id="edit-waktu_kegiatan" name="waktu_kegiatan" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-sm-6">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Gambaran Kegiatan</label>
                    <input type="text" id="edit-gambaran_kegiatan" name="gambaran_kegiatan" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-sm-3">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Anggaran</label>
                    <input type="text" id="edit-anggaran" name="anggaran" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-12 col-sm-3">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">PJ</label>
                    <input type="text" id="edit-penanggung_jawab" name="penanggung_jawab" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
                <div class="col-6 col-sm-3">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Status</label>
                    <select id="edit-status" name="status" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                        <option value="rencana">Rencana</option>
                        <option value="berlangsung">Berlangsung</option>
                        <option value="selesai">Selesai</option>
                        <option value="evaluasi">Evaluasi</option>
                    </select>
                </div>
                <div class="col-6 col-sm-3">
                    <label class="block font-mono uppercase text-[10px] font-bold mb-1">Progres (0-100%)</label>
                    <input type="number" id="edit-progres_persen" name="progres_persen" min="0" max="100" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-200 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('modal-edit-proker').classList.add('hidden')" class="poster-btn-secondary px-4 py-2 rounded-lg text-xs">Batal</button>
                <button type="submit" class="poster-btn-primary px-5 py-2 rounded-lg text-xs">Perbarui Program Kerja</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditProkerModal(proker) {
    const form = document.getElementById('form-edit-proker');
    form.action = '<?= App::baseUrl('/admin/proker/update/') ?>' + proker.id;

    document.getElementById('edit-divisi').value = proker.divisi || '';
    document.getElementById('edit-nama_program').value = proker.nama_program || '';
    document.getElementById('edit-periode').value = proker.periode || '2026/2027';
    document.getElementById('edit-tujuan').value = proker.tujuan || '';
    document.getElementById('edit-indikator_kualitas').value = proker.indikator_kualitas || '';
    document.getElementById('edit-indikator_kuantitas').value = proker.indikator_kuantitas || '';
    document.getElementById('edit-waktu_kegiatan').value = proker.waktu_kegiatan || '';
    document.getElementById('edit-gambaran_kegiatan').value = proker.gambaran_kegiatan || '';
    document.getElementById('edit-anggaran').value = proker.anggaran || '';
    document.getElementById('edit-penanggung_jawab').value = proker.penanggung_jawab || '';
    document.getElementById('edit-status').value = proker.status || 'rencana';
    document.getElementById('edit-progres_persen').value = proker.progres_persen ?? 0;

    document.getElementById('modal-edit-proker').classList.remove('hidden');
}
</script>
