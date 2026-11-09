<?php
/**
 * View: admin/activities/create.php
 * -------------------------------------------------------------------------
 * Formulir Unggah Kegiatan & Album Dokumentasi Baru.
 * -------------------------------------------------------------------------
 */
use App\Config\App;
use App\Config\Security;

$csrfToken = Security::generateCsrfToken();
?>

<div class="max-w-3xl space-y-6">
    <div class="border-b-2 border-slate-900 dark:border-slate-700 pb-4">
        <div class="poster-kicker">Publikasi Dokumentasi</div>
        <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white">Unggah Kegiatan &amp; Album Baru</h2>
    </div>

    <form action="<?= App::baseUrl('/admin/activities') ?>" method="POST" enctype="multipart/form-data" class="poster-card p-6 sm:p-8 space-y-5 text-xs">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div class="row g-3">
            <div class="col-12 col-sm-8">
                <label class="block font-mono uppercase text-[10px] font-bold mb-1">Judul Kegiatan *</label>
                <input type="text" name="judul" required placeholder="Contoh: Makrab K'mplang 2026 atau Pelantikan Pengurus" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
            </div>
            <div class="col-12 col-sm-4">
                <label class="block font-mono uppercase text-[10px] font-bold mb-1">Kategori Kegiatan</label>
                <select name="kategori_kegiatan" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
                    <option value="Dokumentasi">Dokumentasi</option>
                    <option value="Pelantikan &amp; Rapat">Pelantikan &amp; Rapat</option>
                    <option value="Olahraga &amp; Seni">Olahraga &amp; Seni</option>
                    <option value="Sosial &amp; Pengabdian">Sosial &amp; Pengabdian</option>
                    <option value="Kebersamaan">Kebersamaan</option>
                </select>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-sm-6">
                <label class="block font-mono uppercase text-[10px] font-bold mb-1">Tanggal Pelaksanaan</label>
                <input type="date" name="tanggal_kegiatan" value="<?= date('Y-m-d') ?>" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
            </div>
            <div class="col-12 col-sm-6">
                <label class="block font-mono uppercase text-[10px] font-bold mb-1">Lokasi Kegiatan</label>
                <input type="text" name="lokasi" placeholder="Contoh: Gedung B UKSW / Salatiga" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2.5">
            </div>
        </div>

        <div>
            <label class="block font-mono uppercase text-[10px] font-bold mb-1">Deskripsi Lengkap Kegiatan</label>
            <textarea name="deskripsi" rows="4" placeholder="Ceritakan ringkasan kegiatan, tujuan, dan suasana acara..." class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-3"></textarea>
        </div>

        <div class="flex items-center gap-2.5 p-3 rounded-lg bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700">
            <input type="checkbox" id="is_featured" name="is_featured" value="1" class="w-4 h-4 accent-slate-900 dark:accent-slate-200">
            <label for="is_featured" class="font-semibold text-slate-800 dark:text-slate-200 cursor-pointer">Tampilkan sebagai Kegiatan Unggulan (Featured Poster)</label>
        </div>

        <!-- Cover Image -->
        <div class="p-4 bg-slate-100/80 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 rounded-xl space-y-2">
            <label class="block font-bold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-slate-700 dark:text-slate-300 text-base">image</span>
                Foto Sampul Utama (Cover Image)
            </label>
            <input type="file" name="cover" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-200 dark:file:bg-slate-800 file:text-slate-900 dark:file:text-slate-100 cursor-pointer">
            <span class="text-[11px] text-slate-500">Maksimum ukuran 10 MB (JPG, PNG, WEBP).</span>
        </div>

        <!-- Multi-media Album Upload -->
        <div class="p-4 bg-slate-100/80 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 rounded-xl space-y-2">
            <label class="block font-bold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-slate-700 dark:text-slate-300 text-base">collections</span>
                Unggah Berkas Album Dokumentasi (Bisa Pilih Banyak Foto &amp; Video)
            </label>
            <input type="file" name="media[]" multiple accept="image/*,video/mp4,video/webm" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white dark:file:bg-slate-100 dark:file:text-slate-950 cursor-pointer">
            <span class="text-[11px] text-slate-500 block">Pilih satu atau banyak berkas sekaligus untuk ditampilkan di eksplorer dokumentasi.</span>
        </div>

        <div class="flex justify-end gap-3 pt-3 border-t border-slate-200 dark:border-slate-700">
            <a href="<?= App::baseUrl('/admin/activities') ?>" class="poster-btn-secondary px-5 py-2.5 rounded-lg text-xs">Batal</a>
            <button type="submit" class="poster-btn-primary px-6 py-2.5 rounded-lg text-xs">Publikasikan Kegiatan &amp; Album</button>
        </div>
    </form>
</div>
