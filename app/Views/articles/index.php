<?php
/**
 * View: articles/index.php
 * -------------------------------------------------------------------------
 * Halaman Indeks Artikel & Khazanah Budaya Lampung bergaya Poster Majalah.
 * Mendukung filter kategori dan menampilkan estimasi waktu baca.
 * -------------------------------------------------------------------------
 */
use App\Config\App;
use App\Helpers\Formatter;

$articles = $articles ?? [];
$categories = $categories ?? ['Budaya', 'Sejarah', 'Organisasi', 'Opini'];
$activeKategori = $activeKategori ?? 'Semua';
?>

<div class="py-12 min-h-screen overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Editorial Poster Header -->
        <div class="poster-card p-6 sm:p-10" data-aos="fade-up">
            <div class="poster-kicker mb-2">Jurnal Budaya &amp; Rilis Organisasi</div>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="poster-title text-3xl sm:text-5xl text-slate-900 dark:text-slate-100">
                        Khazanah &amp; Esai Lampung
                    </h1>
                    <p class="font-serif-reading text-base text-slate-600 dark:text-slate-300 mt-3 max-w-2xl">
                        Ruang baca yang dirancang dengan tipografi ramah mata untuk mengeksplorasi falsafah hidup, sejarah adat Saibatin &amp; Pepadun, serta gagasan mahasiswa Lampung di Salatiga.
                    </p>
                </div>
                <div class="font-mono text-xs uppercase tracking-widest px-4 py-2.5 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300">
                    Edisi Terbit: <strong><?= count($articles) ?> Tulisan</strong>
                </div>
            </div>
        </div>

        <!-- Category Filter Pills -->
        <div class="flex items-center flex-wrap gap-2">
            <span class="font-mono text-xs uppercase tracking-widest text-slate-500 dark:text-slate-400 mr-2">Rubrik:</span>
            <a href="<?= App::baseUrl('/artikel') ?>" class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all border <?= $activeKategori === 'Semua' ? 'bg-amber-500 text-slate-950 border-slate-900 shadow-sm' : 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700 hover:border-amber-500' ?>">
                Semua Rubrik
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?= App::baseUrl('/artikel?kategori=' . urlencode($cat)) ?>" class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all border <?= $activeKategori === $cat ? 'bg-amber-500 text-slate-950 border-slate-900 shadow-sm' : 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700 hover:border-amber-500' ?>">
                    <?= e($cat) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Articles Poster Grid -->
        <div class="row g-4">
            <?php if (empty($articles)): ?>
                <div class="col-12">
                    <div class="poster-card p-12 text-center text-slate-500 dark:text-slate-400">
                        Belum ada artikel yang diterbitkan pada rubrik ini.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($articles as $idx => $art): ?>
                    <?php
                        $coverSrc = App::mediaUrl($art['cover_image'], 'articles', 'LampungThumb.jpg');
                        $readMin = (int) ($art['waktu_baca_menit'] ?? 3);
                        $excerpt = !empty($art['ringkasan']) ? $art['ringkasan'] : Formatter::potongTeks($art['konten'], 150);
                    ?>
                    <div class="col-12 col-md-6 col-lg-4 d-flex" data-aos="fade-up" data-aos-delay="<?= ($idx % 3) * 60 ?>">
                        <article class="poster-card w-100 overflow-hidden flex flex-col justify-between group">
                            <div>
                                <div class="relative h-52 overflow-hidden bg-slate-200 dark:bg-slate-900 border-b-2 border-slate-900 dark:border-slate-700">
                                    <img src="<?= $coverSrc ?>" alt="<?= e($art['judul']) ?>" class="w-full h-full object-cover">
                                    <span class="absolute top-3 left-3 poster-badge poster-badge-gold shadow">
                                        <?= e($art['kategori']) ?>
                                    </span>
                                    <span class="absolute bottom-3 right-3 bg-slate-900/90 text-slate-100 font-mono text-[11px] font-bold px-2.5 py-1 rounded border border-slate-700 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">schedule</span>
                                        <?= $readMin ?> mnt baca
                                    </span>
                                </div>
                                <div class="p-6">
                                    <div class="flex items-center gap-3 font-mono text-[11px] text-slate-500 dark:text-slate-400 mb-2">
                                        <span><?= Formatter::tanggalIndo($art['created_at']) ?></span>
                                        <span>&bull;</span>
                                        <span><?= (int) ($art['comment_count'] ?? 0) ?> Tanggapan</span>
                                    </div>
                                    <h2 class="font-display font-bold text-xl text-slate-900 dark:text-slate-100 group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors mb-3 leading-snug">
                                        <a href="<?= App::baseUrl('/artikel/' . $art['slug']) ?>"><?= e($art['judul']) ?></a>
                                    </h2>
                                    <p class="font-serif-reading text-sm text-slate-600 dark:text-slate-300 line-clamp-3 leading-relaxed">
                                        <?= e($excerpt) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="p-6 pt-0">
                                <a href="<?= App::baseUrl('/artikel/' . $art['slug']) ?>" class="poster-btn-secondary w-full justify-center py-2.5 rounded-lg text-xs">
                                    <span>Baca Esai Lengkap</span>
                                    <span class="material-symbols-outlined text-base">menu_book</span>
                                </a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
