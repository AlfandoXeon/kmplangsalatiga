<?php
use App\Config\App;
use App\Config\Security;

$csrfToken = Security::generateCsrfToken();
?>

<div class="max-w-3xl space-y-6">
    <div class="border-b border-slate-200 dark:border-slate-800 pb-4 transition-colors">
        <h2 class="text-xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">Unggah Kegiatan &amp; Dokumentasi Baru</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Buat postingan kegiatan baru dan sertakan album foto/video (maksimum 50 MB per berkas).</p>
    </div>

    <form action="<?= App::baseUrl('/admin/activities') ?>" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 space-y-5 shadow-sm dark:shadow-none transition-colors">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Judul Kegiatan <span class="text-rose-500">*</span></label>
            <input type="text" name="judul" required placeholder="Contoh: Makrab K'mplang 2026 atau Latihan Budaya Tari" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal Pelaksanaan</label>
                <input type="date" name="tanggal_kegiatan" value="<?= date('Y-m-d') ?>" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Lokasi Kegiatan</label>
                <input type="text" name="lokasi" placeholder="Contoh: Gedung B UKSW / Villa Kopeng" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Lengkap Kegiatan</label>
            <textarea name="deskripsi" rows="4" placeholder="Ceritakan ringkasan kegiatan, tujuan, dan suasana acara..." class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-3 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500"></textarea>
        </div>

        <!-- Cover Image -->
        <div class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl space-y-2">
            <label class="block text-xs font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-amber-500 text-base">image</span>
                Foto Sampul Utama (Cover Image)
            </label>
            <input type="file" name="cover" accept="image/*" class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-200 dark:file:bg-slate-800 file:text-slate-800 dark:file:text-amber-400 hover:file:bg-slate-300 dark:hover:file:bg-slate-700 cursor-pointer">
            <span class="text-[11px] text-slate-500">Maksimum ukuran 10 MB per berkas (JPG, PNG, WEBP).</span>
        </div>

        <!-- Multi-media Album Upload -->
        <div class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl space-y-2">
            <label class="block text-xs font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-amber-500 text-base">collections</span>
                Unggah Berkas Album Google Drive (Bisa Pilih Banyak Foto &amp; Video)
            </label>
            <input type="file" name="media[]" multiple accept="image/*,video/mp4,video/webm" class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-500 file:text-slate-950 hover:file:bg-amber-400 cursor-pointer">
            <span class="text-[11px] text-slate-500 block">Pilih satu atau banyak berkas sekaligus. Berkas akan otomatis masuk ke Google Drive Album Viewer untuk dilihat dan diunduh anggota. (Maks 10 MB per berkas).</span>
        </div>

        <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
            <a href="<?= App::baseUrl('/admin/activities') ?>" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-sm transition-all">
                Publikasikan Kegiatan &amp; Album
            </button>
        </div>
    </form>
</div>
