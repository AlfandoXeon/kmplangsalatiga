<?php
use App\Config\App;
use App\Config\Security;

$csrfToken = Security::generateCsrfToken();
?>

<div class="max-w-3xl space-y-6">
    <div class="border-b border-slate-200 dark:border-slate-800 pb-4 transition-colors">
        <h2 class="text-xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">Tulis Artikel Baru</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Publikasikan tulisan edukatif mengenai adat istiadat, sejarah, atau informasi kegiatan.</p>
    </div>

    <form action="<?= App::baseUrl('/admin/articles') ?>" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 space-y-5 shadow-sm dark:shadow-none transition-colors">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Judul Artikel <span class="text-rose-500">*</span></label>
            <input type="text" name="judul" required placeholder="Judul artikel menarik..." class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kategori</label>
                <select name="kategori" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500 cursor-pointer">
                    <option value="Budaya">Budaya</option>
                    <option value="Sejarah">Sejarah</option>
                    <option value="Berita">Berita Organisasi</option>
                    <option value="Opini">Opini / Artikel Anggota</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Status Publikasi</label>
                <select name="status" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500 cursor-pointer">
                    <option value="published">Langsung Publikasi (Published)</option>
                    <option value="draft">Simpan Draf (Draft)</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Foto Sampul Artikel</label>
            <input type="file" name="cover" accept="image/*" class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 dark:file:bg-slate-800 file:text-slate-800 dark:file:text-amber-400 hover:file:bg-slate-200 dark:hover:file:bg-slate-700 cursor-pointer">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Isi Artikel <span class="text-rose-500">*</span></label>
            <textarea name="konten" rows="8" required placeholder="Tuliskan isi artikel Anda secara lengkap..." class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-3 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500"></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
            <a href="<?= App::baseUrl('/admin/articles') ?>" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-sm transition-all">
                Publikasikan Artikel
            </button>
        </div>
    </form>
</div>
