<?php
/**
 * ============================================================================
 * View Beranda Utama (home/index.php)
 * ============================================================================
 * Konsep Desain:
 * - Estetika Poster Budaya & Editorial Majalah (Swiss/Cultural Exhibition Poster)
 * - Fokus pada efek Tipografi Kontras Tinggi (`Fraunces` + `Montserrat` + `JetBrains Mono`)
 *   dan kenyamanan mata saat membaca (`Source Serif 4`, palet White-Yellow / Dark Grey).
 * ============================================================================
 */
use App\Config\App;
use App\Helpers\Formatter;

$settings = $settings ?? [];
$announcements = $announcements ?? [];
$stats = $stats ?? ['total_anggota' => 0, 'total_proker' => 0, 'rata_progres' => 0, 'total_kegiatan' => 0];
$recentActivities = $recentActivities ?? [];
$galleryPhotos = $galleryPhotos ?? [];
$recentArticles = $recentArticles ?? [];
$prokerList = $prokerList ?? [];
$featuredProkers = $featuredProkers ?? array_slice($prokerList, 0, 6);

$heroTitle = $settings['hero_title'] ?? 'RUMAH PERANTAU LAMPUNG';
$heroSub = $settings['hero_subtitle'] ?? 'Di Kota Salatiga';
$heroDesc = $settings['hero_description'] ?? 'Menjadi keluarga di tanah rantau, menjunjung tinggi adat budaya Sang Bumi Ruwa Jurai, dan meraih mimpi bersama.';
$aboutTitle = $settings['about_title'] ?? 'Lebih dari Sekadar Komunitas Perantau';
$about1 = $settings['about_content_1'] ?? '';
$about2 = $settings['about_content_2'] ?? '';
$sambutan = $settings['sambutan_ketua'] ?? '';
$namaKetua = $settings['nama_ketua'] ?? 'Ketua Umum K\'mplang Periode 2026/2027';
?>

<!-- Pita Edisi Poster (Top Poster Issue Bar) -->
<div class="poster-issue-strip">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-wrap items-center justify-between gap-2">
        <span>EDISI RESMI 2026/2027 &bull; KELUARGA MAHASISWA PERANTAUAN LAMPUNG</span>
        <span class="hidden sm:inline">SAI BUMI RUWA JURAI &mdash; SALATIGA, JAWA TENGAH</span>
    </div>
</div>

<!-- 1. HERO POSTER EXHIBITION SECTION -->
<section id="hero" class="relative hero-bg py-16 sm:py-24 lg:py-28 border-b-2 border-slate-900 dark:border-slate-700 transition-colors duration-200 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl space-y-6 text-left" data-aos="fade-up" data-aos-duration="650">
            <div class="flex flex-wrap items-center gap-3">
                <span class="poster-kicker">
                    <span class="material-symbols-outlined text-sm">Auto_Awesome</span>
                    TABIK PUN SEMUANYA
                </span>
                <span class="font-mono text-xs font-bold uppercase tracking-widest text-slate-600 dark:text-slate-400">
                    SANG BUMI RUWA JURAI &bull; EST. SALATIGA
                </span>
            </div>

            <!-- Headline Poster Bertingkat -->
            <div class="space-y-2">
                <h1 class="poster-display-xl tracking-tight">
                    <?= e($heroTitle) ?>
                </h1>
                <h2 class="font-poster italic font-bold text-2xl sm:text-4xl lg:text-5xl text-amber-700 dark:text-amber-400">
                    <span class="poster-highlight"><?= e($heroSub) ?></span>
                </h2>
            </div>

            <!-- Lead Paragraph yang Nyaman Dibaca -->
            <p class="font-reading text-base sm:text-xl text-slate-700 dark:text-slate-200 max-w-2xl leading-relaxed">
                <?= e($heroDesc) ?>
            </p>

            <!-- Tombol Aksi Gaya Poster Cetak -->
            <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <?php if (!empty($currentUser)): ?>
                    <a href="<?= App::baseUrl('/anggota') ?>" class="poster-btn-primary">
                        <span class="material-symbols-outlined text-lg">badge</span>
                        <span>Direktori Keluarga K'mplang</span>
                    </a>
                <?php else: ?>
                    <a href="<?= App::baseUrl('/register') ?>" onclick="if(window.openAuthModal){event.preventDefault();openAuthModal('register');}" class="poster-btn-primary">
                        <span class="material-symbols-outlined text-lg">how_to_reg</span>
                        <span>Gabung Keluarga K'mplang</span>
                    </a>
                <?php endif; ?>
                <a href="<?= App::baseUrl('/program-kerja') ?>" class="poster-btn-secondary">
                    <span class="material-symbols-outlined text-lg">assignment</span>
                    <span>Eksplorasi 23 Program Kerja</span>
                </a>
            </div>

            <!-- Sub-caption Ringkas & Halus (Tanpa Kotak Mencolok) -->
            <div class="pt-4 border-t border-slate-300/80 dark:border-slate-800 flex flex-wrap items-center gap-x-6 gap-y-2 font-mono text-[11px] uppercase tracking-wider text-slate-600 dark:text-slate-400">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    Sai Batin &amp; Pepadun Bersatu
                </span>
                <span class="hidden sm:inline text-slate-300 dark:text-slate-700">&bull;</span>
                <span>Lintas Perguruan Tinggi se-Kota Salatiga</span>
            </div>
        </div>
    </div>
</section>

<!-- 2. PAPAN BULETIN & AGENDA POSTER (Tabel Baru `announcements`) -->
<?php if (!empty($announcements)): ?>
<section class="py-10 bg-slate-100 dark:bg-slate-900 border-b-2 border-slate-900 dark:border-slate-700 transition-colors duration-200 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-5" data-aos="fade-up">
            <div class="flex items-center gap-2.5">
                <span class="w-3 h-3 rounded-full bg-amber-500 animate-pulse"></span>
                <span class="font-mono text-xs font-extrabold uppercase tracking-widest text-slate-900 dark:text-white">
                    BULETIN &amp; AGENDA TERDEKAT K'MPLANG
                </span>
            </div>
            <span class="font-mono text-[11px] text-slate-500 dark:text-slate-400 hidden sm:inline">PAPAN PENGUMUMAN RESMI</span>
        </div>

        <div class="row g-4">
            <?php foreach ($announcements as $idx => $ann): ?>
                <div class="col-12 col-md-6" data-aos="fade-up" data-aos-delay="<?= ($idx % 2) * 80 ?>">
                    <div class="poster-card bg-white dark:bg-slate-900 h-100 flex flex-col justify-between">
                        <div class="poster-card-header">
                            <span class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm text-amber-600">campaign</span>
                                <?= e(strtoupper($ann['tipe'])) ?>
                            </span>
                            <span><?= !empty($ann['tanggal_acara']) ? Formatter::tanggalIndo($ann['tanggal_acara']) : 'INFORMASI AKTIF' ?></span>
                        </div>
                        <div class="p-5 space-y-2.5 flex-1">
                            <h3 class="font-poster font-bold text-lg text-slate-900 dark:text-white leading-snug">
                                <?= e($ann['judul']) ?>
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                                <?= e($ann['isi']) ?>
                            </p>
                        </div>
                        <?php if (!empty($ann['lokasi']) || !empty($ann['tautan'])): ?>
                            <div class="px-5 py-3 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs bg-slate-50 dark:bg-slate-950/50">
                                <span class="font-mono text-[11px] text-slate-600 dark:text-slate-400 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm text-amber-600">location_on</span>
                                    <?= e($ann['lokasi'] ?: 'Salatiga') ?>
                                </span>
                                <?php if (!empty($ann['tautan'])): ?>
                                    <?php
                                        $annLink = trim($ann['tautan']);
                                        $isAuthLink = in_array($annLink, ['/register', '/login'], true);
                                    ?>
                                    <?php if ($isAuthLink && !empty($currentUser)): ?>
                                        <a href="<?= App::baseUrl('/anggota') ?>" class="font-mono font-bold text-amber-700 dark:text-amber-400 hover:underline flex items-center gap-1">
                                            <span>Lihat Direktori</span>
                                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= App::baseUrl($annLink) ?>"
                                           <?php if ($isAuthLink): ?>onclick="if(window.openAuthModal){event.preventDefault();openAuthModal('<?= $annLink === '/register' ? 'register' : 'login' ?>');}"<?php endif; ?>
                                           class="font-mono font-bold text-amber-700 dark:text-amber-400 hover:underline flex items-center gap-1">
                                            <span>Selengkapnya</span>
                                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 3. BARIS METRIK STATISTIK GAYA POSTER -->
<section class="bg-white dark:bg-slate-900 border-b-2 border-slate-900 dark:border-slate-700 py-10 transition-colors duration-200 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 divide-y sm:divide-y-0 lg:divide-x-2 divide-slate-200 dark:divide-slate-800">
            <div class="pt-3 lg:pt-0 lg:px-4 text-center" data-aos="fade-up" data-aos-delay="0">
                <div class="font-mono text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1">01 // KEANGGOTAAN</div>
                <div class="font-poster font-black text-3xl sm:text-5xl text-slate-900 dark:text-white"><?= (int) ($stats['total_anggota'] ?? 0) ?>+</div>
                <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 mt-1">Mahasiswa &amp; Pengurus</div>
            </div>
            <div class="pt-3 lg:pt-0 lg:px-4 text-center" data-aos="fade-up" data-aos-delay="60">
                <div class="font-mono text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1">02 // AGENDA KERJA</div>
                <div class="font-poster font-black text-3xl sm:text-5xl text-slate-900 dark:text-white"><?= (int) ($stats['total_proker'] ?? 0) ?></div>
                <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 mt-1">Program Kerja 8 Divisi</div>
            </div>
            <div class="pt-3 lg:pt-0 lg:px-4 text-center" data-aos="fade-up" data-aos-delay="120">
                <div class="font-mono text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1">03 // REALISASI</div>
                <div class="font-poster font-black text-3xl sm:text-5xl text-slate-900 dark:text-white"><?= (int) ($stats['rata_progres'] ?? 0) ?>%</div>
                <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 mt-1">Rata-rata Progres Proker</div>
            </div>
            <div class="pt-3 lg:pt-0 lg:px-4 text-center" data-aos="fade-up" data-aos-delay="180">
                <div class="font-mono text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1">04 // ARSIP VISUAL</div>
                <div class="font-poster font-black text-3xl sm:text-5xl text-slate-900 dark:text-white"><?= (int) ($stats['total_kegiatan'] ?? 0) ?></div>
                <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 mt-1">Album Dokumentasi</div>
            </div>
        </div>
    </div>
</section>

<!-- 4. RUBRIK EDITORIAL: TENTANG, FILOSOFI & SAMBUTAN KETUA -->
<section id="tentang" class="py-20 bg-slate-50 dark:bg-slate-950 border-b-2 border-slate-900 dark:border-slate-700 transition-colors duration-200 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="row g-5 align-items-start">
            <!-- Kolom Kiri: Artikel Editorial Tentang K'mplang (Drop Cap & Reading Font) -->
            <div class="col-12 col-lg-7 space-y-6" data-aos="fade-up">
                <div>
                    <span class="poster-kicker mb-3">RUBRIK 01 // MANIFESTO &amp; SEJARAH</span>
                    <h2 class="poster-display-lg mt-2">
                        <?= e($aboutTitle) ?>
                    </h2>
                </div>

                <!-- Paragraf Bacaan Ramah Mata dengan Efek Drop Cap -->
                <div class="editorial-prose space-y-4">
                    <p class="drop-cap"><?= nl2br(e($about1)) ?></p>
                    <p><?= nl2br(e($about2)) ?></p>
                </div>

                <!-- Poster Quote Sambutan Ketua -->
                <?php if (!empty($sambutan)): ?>
                    <div class="poster-card bg-white dark:bg-slate-900 mt-6">
                        <div class="poster-card-header">
                            <span>PESAN KEPENGURUSAN</span>
                            <span><?= e($namaKetua) ?></span>
                        </div>
                        <div class="p-6 space-y-3">
                            <p class="font-reading italic text-base sm:text-lg text-slate-800 dark:text-slate-200 leading-relaxed">
                                &ldquo;<?= nl2br(e($sambutan)) ?>&rdquo;
                            </p>
                            <div class="flex items-center gap-2 pt-2 font-mono text-xs font-bold text-amber-700 dark:text-amber-400 uppercase">
                                <span class="material-symbols-outlined text-base">verified</span>
                                <span><?= e($namaKetua) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Kolom Kanan: Poster Frame Foto Keluarga Besar -->
            <div class="col-12 col-lg-5" data-aos="fade-up" data-aos-delay="100">
                <div class="poster-card bg-white dark:bg-slate-900">
                    <div class="poster-card-header">
                        <span>POTRET KEBERSAMAAN</span>
                        <span>DOK. K'MPLANG</span>
                    </div>
                    <div class="p-3 bg-slate-100 dark:bg-slate-800">
                        <img src="<?= App::baseUrl('/assets/images/ins.png') ?>" alt="Keluarga Besar K'mplang Salatiga" class="w-full h-auto rounded-lg object-cover border border-slate-900 dark:border-slate-600">
                    </div>
                    <div class="p-5 space-y-2">
                        <h3 class="font-poster font-bold text-lg text-slate-900 dark:text-white">
                            Satu Keluarga di Tanah Rantau Salatiga
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Merangkul mahasiswa perantau Lampung lintas fakultas dan perguruan tinggi dalam semangat <em>Sakai Sambayan</em> (gotong royong).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. TIGA PILAR NILAI K'MPLANG (Poster Grid 01, 02, 03) -->
<section id="mengapa" class="py-20 bg-slate-100 dark:bg-slate-900 border-b-2 border-slate-900 dark:border-slate-700 transition-colors duration-200 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4" data-aos="fade-up">
            <div>
                <span class="poster-kicker mb-3">RUBRIK 02 // PILAR ORGANISASI</span>
                <h2 class="poster-display-lg mt-2">Mengapa Bergabung Bersama K'mplang?</h2>
            </div>
            <p class="font-reading text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-md">
                Tiga fondasi utama yang menjadikan K'mplang sebagai ruang bertumbuh terbaik bagi mahasiswa Lampung di Salatiga.
            </p>
        </div>

        <div class="row g-4">
            <!-- Pilar 01 -->
            <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="0">
                <div class="poster-card bg-white dark:bg-slate-900 h-100 flex flex-col justify-between">
                    <div class="poster-card-header">
                        <span>PILAR PERSAUDARAAN</span>
                        <span>NO. 01</span>
                    </div>
                    <div class="p-6 sm:p-7 space-y-4 flex-1">
                        <div class="flex items-center justify-between">
                            <div class="w-12 h-12 rounded-xl bg-amber-400 border-2 border-slate-900 flex items-center justify-center text-slate-950">
                                <span class="material-symbols-outlined text-2xl">diversity_3</span>
                            </div>
                            <span class="poster-index-num">01</span>
                        </div>
                        <h3 class="font-poster font-black text-xl text-slate-900 dark:text-white">
                            Keluarga Baru di Perantauan
                        </h3>
                        <p class="font-reading text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                            Bertemu rekan seperjuangan dari berbagai kabupaten/kota di Lampung untuk saling mendukung akademik, tempat tinggal, hingga adaptasi kehidupan kampus.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pilar 02 -->
            <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="80">
                <div class="poster-card bg-white dark:bg-slate-900 h-100 flex flex-col justify-between">
                    <div class="poster-card-header">
                        <span>PILAR KEBUDAYAAN</span>
                        <span>NO. 02</span>
                    </div>
                    <div class="p-6 sm:p-7 space-y-4 flex-1">
                        <div class="flex items-center justify-between">
                            <div class="w-12 h-12 rounded-xl bg-amber-400 border-2 border-slate-900 flex items-center justify-center text-slate-950">
                                <span class="material-symbols-outlined text-2xl">theater_comedy</span>
                            </div>
                            <span class="poster-index-num">02</span>
                        </div>
                        <h3 class="font-poster font-black text-xl text-slate-900 dark:text-white">
                            Pelestarian Adat &amp; Seni Lampung
                        </h3>
                        <p class="font-reading text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                            Mewadahi minat seni tari Sigeh Penguten, Bedana, musik tradisional Tanoh Lado, serta pengenalan busana adat Siger &amp; Tapis di panggung budaya.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pilar 03 -->
            <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="160">
                <div class="poster-card bg-white dark:bg-slate-900 h-100 flex flex-col justify-between">
                    <div class="poster-card-header">
                        <span>PILAR KEPEMIMPINAN</span>
                        <span>NO. 03</span>
                    </div>
                    <div class="p-6 sm:p-7 space-y-4 flex-1">
                        <div class="flex items-center justify-between">
                            <div class="w-12 h-12 rounded-xl bg-amber-400 border-2 border-slate-900 flex items-center justify-center text-slate-950">
                                <span class="material-symbols-outlined text-2xl">psychology</span>
                            </div>
                            <span class="poster-index-num">03</span>
                        </div>
                        <h3 class="font-poster font-black text-xl text-slate-900 dark:text-white">
                            Inkubator Kepemimpinan &amp; Relasi
                        </h3>
                        <p class="font-reading text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                            Mengasah kemampuan manajemen organisasi, kewirausahaan, komunikasi lintas etnis, serta membangun jejaring alumni yang luas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. TRANSPARANSI PROGRAM KERJA UNGGULAN -->
<section id="program-kerja" class="py-20 bg-slate-50 dark:bg-slate-950 border-b-2 border-slate-900 dark:border-slate-700 transition-colors duration-200 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4" data-aos="fade-up">
            <div>
                <span class="poster-kicker mb-3">RUBRIK 03 // AKUNTABILITAS ORGANISASI</span>
                <h2 class="poster-display-lg mt-2">Sorotan Program Kerja 2026/2027</h2>
                <p class="font-reading text-sm sm:text-base text-slate-600 dark:text-slate-300 mt-1">
                    Rincian agenda kerja terbuka lengkap dengan penanggung jawab, anggaran, dan persentase capaian.
                </p>
            </div>
            <div>
                <a href="<?= App::baseUrl('/program-kerja') ?>" class="poster-btn-secondary">
                    <span>Lihat Seluruh <?= (int) ($stats['total_proker'] ?? 23) ?> Proker</span>
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <?php if (empty($featuredProkers)): ?>
                <div class="col-12 py-12 text-center text-slate-500 italic">
                    Belum ada program kerja yang ditampilkan.
                </div>
            <?php else: ?>
                <?php foreach ($featuredProkers as $idx => $proker): ?>
                    <?php $progres = (int) ($proker['progres_persen'] ?? 0); ?>
                    <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= ($idx % 3) * 60 ?>">
                        <div class="poster-card bg-white dark:bg-slate-900 h-100 flex flex-col justify-between">
                            <div class="poster-card-header">
                                <span>DIVISI <?= e(strtoupper($proker['divisi'])) ?></span>
                                <span><?= e(strtoupper($proker['status'])) ?> &bull; <?= $progres ?>%</span>
                            </div>

                            <div class="p-6 space-y-4 flex-1">
                                <h3 class="font-poster font-bold text-lg text-slate-900 dark:text-white leading-snug">
                                    <?= e($proker['nama_program']) ?>
                                </h3>
                                <p class="font-reading text-xs sm:text-sm text-slate-700 dark:text-slate-300 line-clamp-3 leading-relaxed">
                                    <?= e($proker['tujuan']) ?>
                                </p>

                                <!-- Bar Progres Ketercapaian -->
                                <div class="space-y-1.5 pt-2">
                                    <div class="flex justify-between font-mono text-[10px] font-bold uppercase text-slate-500 dark:text-slate-400">
                                        <span>Progres Realisasi</span>
                                        <span class="text-slate-900 dark:text-slate-100"><?= $progres ?>%</span>
                                    </div>
                                    <div class="w-full h-2 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden border border-slate-900/20">
                                        <div class="h-full bg-amber-500 rounded-full" style="width: <?= $progres ?>%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-3.5 border-t border-slate-200 dark:border-slate-800 bg-slate-100/50 dark:bg-slate-800/40 flex items-center justify-between font-mono text-xs">
                                <span class="text-slate-600 dark:text-slate-400">PJ: <strong class="text-slate-900 dark:text-slate-100"><?= e($proker['penanggung_jawab'] ?: '-') ?></strong></span>
                                <span class="font-bold text-slate-900 dark:text-slate-100"><?= e($proker['anggaran'] ?: 'Rp 0') ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 7. GALERI DOKUMENTASI & ARTIKEL KHAZANAH BUDAYA -->
<section class="py-20 bg-slate-100 dark:bg-slate-900 border-b-2 border-slate-900 dark:border-slate-700 transition-colors duration-200 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        <!-- A. Galeri Dokumentasi -->
        <div>
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4" data-aos="fade-up">
                <div>
                    <span class="poster-kicker mb-3">RUBRIK 04 // LENSA KEGIATAN</span>
                    <h2 class="poster-display-lg mt-2">Pameran Dokumentasi &amp; Album</h2>
                    <p class="font-reading text-sm sm:text-base text-slate-600 dark:text-slate-300 mt-1">
                        Rekam jejak visual kegiatan K'mplang yang tersimpan rapi dan dapat diunduh penuh.
                    </p>
                </div>
                <div>
                    <a href="<?= App::baseUrl('/kegiatan') ?>" class="poster-btn-secondary">
                        <span class="material-symbols-outlined text-base">photo_library</span>
                        <span>Buka Seluruh Album</span>
                    </a>
                </div>
            </div>

            <?php if (!empty($galleryPhotos)): ?>
                <div class="mb-8" data-aos="fade-up">
                    <!-- Track Carousel di Mobile (< md) & Grid 3 Kolom di Desktop (md+) -->
                    <div id="mobile-gallery-carousel"
                         class="flex md:grid md:grid-cols-3 gap-4 overflow-x-auto md:overflow-visible snap-x snap-mandatory scroll-smooth no-scrollbar pt-1 pb-3 -mx-4 px-4 md:mx-0 md:px-0 md:pt-0 md:pb-0">
                        <?php foreach ($galleryPhotos as $idx => $photo): ?>
                            <?php $photoUrl = App::mediaUrl($photo['file_path'], 'activities'); ?>
                            <div class="w-[78%] sm:w-[58%] md:w-auto shrink-0 snap-center md:snap-align-none">
                                <div class="gallery-photo-card poster-card bg-white dark:bg-slate-900 cursor-pointer group h-full flex flex-col justify-between"
                                     data-url="<?= $photoUrl ?>"
                                     data-name="<?= e($photo['original_name']) ?>">
                                    <div class="relative h-52 sm:h-60 overflow-hidden bg-slate-200 dark:bg-slate-950">
                                        <img src="<?= $photoUrl ?>" alt="<?= e($photo['original_name']) ?>" class="w-full h-full object-cover">
                                        <div class="absolute top-3 right-3 bg-amber-400 text-slate-950 border border-slate-900 rounded-lg w-8 h-8 flex items-center justify-center shadow-sm">
                                            <span class="material-symbols-outlined text-base">zoom_in</span>
                                        </div>
                                        <span class="md:hidden absolute bottom-2.5 left-2.5 px-2 py-0.5 rounded bg-slate-950/80 text-amber-400 font-mono text-[10px] font-bold border border-amber-400/30">
                                            <?= $idx + 1 ?> / <?= count($galleryPhotos) ?>
                                        </span>
                                    </div>
                                    <div class="p-3.5 border-t border-slate-900 dark:border-slate-700 bg-white dark:bg-slate-900">
                                        <p class="font-bold text-xs text-slate-900 dark:text-white truncate"><?= e($photo['original_name']) ?></p>
                                        <p class="font-mono text-[10px] text-amber-700 dark:text-amber-400 truncate mt-0.5"><?= e($photo['activity_title']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Kontrol Geser & Indikator Carousel (Khusus Mobile < md) -->
                    <div class="flex md:hidden items-center justify-between mt-3 px-1">
                        <div class="flex items-center gap-1.5" id="mobile-gallery-dots">
                            <?php foreach ($galleryPhotos as $idx => $photo): ?>
                                <button type="button"
                                        data-slide="<?= $idx ?>"
                                        aria-label="Slide <?= $idx + 1 ?>"
                                        class="gallery-dot h-2 rounded-full transition-all duration-200 <?= $idx === 0 ? 'w-6 bg-amber-500 border border-slate-900' : 'w-2 bg-slate-300 dark:bg-slate-700' ?>"></button>
                            <?php endforeach; ?>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mr-1">Geser</span>
                            <button type="button" id="gallery-prev-btn" class="w-8 h-8 rounded-lg bg-white dark:bg-slate-800 border-2 border-slate-900 dark:border-slate-600 flex items-center justify-center text-slate-900 dark:text-white active:bg-amber-400 active:text-slate-950 shadow-[2px_2px_0px_#1e1c17]" aria-label="Foto Sebelumnya">
                                <span class="material-symbols-outlined text-base">chevron_left</span>
                            </button>
                            <button type="button" id="gallery-next-btn" class="w-8 h-8 rounded-lg bg-amber-400 border-2 border-slate-900 flex items-center justify-center text-slate-950 active:translate-y-0.5 shadow-[2px_2px_0px_#1e1c17]" aria-label="Foto Berikutnya">
                                <span class="material-symbols-outlined text-base">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Sorotan Album Utama -->
            <?php if (!empty($recentActivities)): ?>
                <?php $mainAct = $recentActivities[0]; ?>
                <div data-aos="fade-up">
                    <div class="poster-card bg-white dark:bg-slate-900 p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <div class="flex items-start sm:items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-amber-400 border-2 border-slate-900 text-slate-950 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-3xl">folder_special</span>
                            </div>
                            <div>
                                <span class="font-mono text-[10px] font-bold uppercase tracking-widest text-amber-700 dark:text-amber-400">ALBUM SOROTAN UTAMA</span>
                                <h3 class="font-poster font-black text-lg sm:text-xl text-slate-900 dark:text-white"><?= e($mainAct['judul']) ?></h3>
                                <p class="font-mono text-xs text-slate-600 dark:text-slate-400 mt-1">
                                    <?= Formatter::tanggalIndo($mainAct['tanggal_kegiatan']) ?> &bull; <?= (int) ($mainAct['media_count'] ?? 0) ?> Berkas Tersedia (ZIP Ready)
                                </p>
                            </div>
                        </div>
                        <a href="<?= App::baseUrl('/kegiatan/' . $mainAct['slug']) ?>" class="poster-btn-primary w-full md:w-auto">
                            <span class="material-symbols-outlined text-base">folder_open</span>
                            <span>Buka Album &amp; Unduh ZIP</span>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- B. Khazanah Artikel Budaya Lampung Terbaru -->
        <?php if (!empty($recentArticles)): ?>
        <div class="pt-8 border-t-2 border-slate-300 dark:border-slate-800">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4" data-aos="fade-up">
                <div>
                    <span class="poster-kicker mb-3">RUBRIK 05 // PUSTAKA BUDAYA</span>
                    <h2 class="poster-display-lg mt-2">Wawasan &amp; Sejarah Lampung</h2>
                </div>
                <a href="<?= App::baseUrl('/artikel') ?>" class="poster-btn-secondary">
                    <span>Baca Semua Artikel</span>
                    <span class="material-symbols-outlined text-base">menu_book</span>
                </a>
            </div>

            <div class="row g-4">
                <?php foreach ($recentArticles as $idx => $art): ?>
                    <div class="col-12 col-md-6" data-aos="fade-up" data-aos-delay="<?= ($idx % 2) * 80 ?>">
                        <article class="poster-card bg-white dark:bg-slate-900 h-100 flex flex-col justify-between">
                            <div>
                                <div class="poster-card-header">
                                    <span>KATEGORI: <?= e(strtoupper($art['kategori'])) ?></span>
                                    <span><?= (int) ($art['waktu_baca_menit'] ?? 3) ?> MENIT BACA</span>
                                </div>
                                <div class="p-6 space-y-3">
                                    <h3 class="font-poster font-bold text-xl text-slate-900 dark:text-white leading-snug">
                                        <a href="<?= App::baseUrl('/artikel/' . $art['slug']) ?>" class="hover:text-amber-700 dark:hover:text-amber-400">
                                            <?= e($art['judul']) ?>
                                        </a>
                                    </h3>
                                    <p class="font-reading text-sm text-slate-700 dark:text-slate-300 line-clamp-3 leading-relaxed">
                                        <?= e(!empty($art['ringkasan']) ? $art['ringkasan'] : strip_tags($art['konten'])) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="px-6 py-3.5 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between font-mono text-xs">
                                <span class="text-slate-500"><?= Formatter::tanggalIndo($art['created_at']) ?></span>
                                <a href="<?= App::baseUrl('/artikel/' . $art['slug']) ?>" class="font-bold text-amber-700 dark:text-amber-400 hover:underline flex items-center gap-1">
                                    <span>Baca Esai</span>
                                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Modal Lightbox untuk Pratinjau Foto Galeri Beranda -->
<div id="home-lightbox-modal" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="relative max-w-4xl w-full poster-card bg-white dark:bg-slate-900 overflow-hidden">
        <div class="poster-card-header">
            <span id="home-lightbox-title" class="truncate">PRATINJAU DOKUMENTASI</span>
            <button id="home-lightbox-close" class="px-2 py-0.5 rounded bg-amber-400 text-slate-950 font-bold text-xs">
                TUTUP [ESC]
            </button>
        </div>
        <div class="p-4 flex items-center justify-center bg-black min-h-[300px] max-h-[75vh]">
            <img id="home-lightbox-img" src="" alt="Pratinjau" class="max-h-[70vh] max-w-full object-contain rounded">
        </div>
    </div>
</div>

<!-- 8. POSTER PENUTUP AJAKAN BERGABUNG (CTA) -->
<section class="py-20 bg-slate-100 dark:bg-slate-900 transition-colors duration-200 overflow-hidden">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8" data-aos="fade-up">
        <div class="poster-card bg-white dark:bg-slate-900 p-8 sm:p-12 text-center space-y-6">
            <?php if (!empty($currentUser)): ?>
                <span class="poster-kicker">KELUARGA BESAR K'MPLANG SALATIGA</span>
                <h2 class="poster-display-lg">
                    Mari Merajut Persaudaraan di Tanah Rantau Salatiga
                </h2>
                <p class="font-reading text-base sm:text-lg text-slate-700 dark:text-slate-300 max-w-2xl mx-auto">
                    Terima kasih telah menjadi bagian dari keluarga besar mahasiswa perantau Lampung di Kota Salatiga. Pastikan data profil keanggotaan Anda selalu mutakhir dan tetap aktif dalam agenda organisasi.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
                    <a href="<?= App::baseUrl('/profil') ?>" class="poster-btn-primary w-full sm:w-auto">
                        <span class="material-symbols-outlined text-lg">person</span>
                        <span>Kelola Profil Anggota</span>
                    </a>
                    <a href="<?= App::baseUrl('/anggota') ?>" class="poster-btn-secondary w-full sm:w-auto">
                        <span class="material-symbols-outlined text-lg">badge</span>
                        <span>Lihat Direktori Anggota</span>
                    </a>
                </div>
            <?php else: ?>
                <span class="poster-kicker">PANGGILAN TERBUKA MAHASISWA LAMPUNG</span>
                <h2 class="poster-display-lg">
                    Mari Merajut Persaudaraan di Tanah Rantau Salatiga
                </h2>
                <p class="font-reading text-base sm:text-lg text-slate-700 dark:text-slate-300 max-w-2xl mx-auto">
                    Pendaftaran anggota terbuka bagi seluruh mahasiswa asal Provinsi Lampung di UKSW, UIN Salatiga, dan perguruan tinggi se-Kota Salatiga.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
                    <a href="<?= App::baseUrl('/register') ?>" onclick="if(window.openAuthModal){event.preventDefault();openAuthModal('register');}" class="poster-btn-primary w-full sm:w-auto">
                        <span class="material-symbols-outlined text-lg">how_to_reg</span>
                        <span>Daftar Anggota Sekarang</span>
                    </a>
                    <a href="<?= App::baseUrl('/anggota') ?>" class="poster-btn-secondary w-full sm:w-auto">
                        <span class="material-symbols-outlined text-lg">badge</span>
                        <span>Lihat Direktori Anggota</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Script Lightbox Galeri & Carousel Mobile -->
<script>
    // Lightbox Galeri Beranda
    const homeModal = document.getElementById('home-lightbox-modal');
    const homeModalImg = document.getElementById('home-lightbox-img');
    const homeModalTitle = document.getElementById('home-lightbox-title');
    const homeModalClose = document.getElementById('home-lightbox-close');

    document.querySelectorAll('.gallery-photo-card').forEach(card => {
        card.addEventListener('click', () => {
            const url = card.dataset.url;
            const name = card.dataset.name;
            if (homeModal && homeModalImg) {
                homeModalImg.src = url;
                if (homeModalTitle) homeModalTitle.textContent = name;
                homeModal.classList.remove('hidden');
            }
        });
    });

    if (homeModalClose) {
        homeModalClose.addEventListener('click', () => homeModal.classList.add('hidden'));
    }

    if (homeModal) {
        homeModal.addEventListener('click', (e) => {
            if (e.target === homeModal) homeModal.classList.add('hidden');
        });
    }

    // Kontrol Carousel Galeri Mobile (Geser Kanan/Kiri & Indikator Titik)
    const galleryTrack = document.getElementById('mobile-gallery-carousel');
    const galleryPrevBtn = document.getElementById('gallery-prev-btn');
    const galleryNextBtn = document.getElementById('gallery-next-btn');
    const galleryDots = document.querySelectorAll('.gallery-dot');

    if (galleryTrack) {
        const getSlideWidth = () => {
            const firstSlide = galleryTrack.firstElementChild;
            return firstSlide ? firstSlide.offsetWidth + 16 : 260;
        };

        if (galleryPrevBtn) {
            galleryPrevBtn.addEventListener('click', () => {
                galleryTrack.scrollBy({ left: -getSlideWidth(), behavior: 'smooth' });
            });
        }

        if (galleryNextBtn) {
            galleryNextBtn.addEventListener('click', () => {
                galleryTrack.scrollBy({ left: getSlideWidth(), behavior: 'smooth' });
            });
        }

        galleryDots.forEach(dot => {
            dot.addEventListener('click', () => {
                const idx = parseInt(dot.dataset.slide || '0', 10);
                galleryTrack.scrollTo({ left: idx * getSlideWidth(), behavior: 'smooth' });
            });
        });

        galleryTrack.addEventListener('scroll', () => {
            const slideWidth = getSlideWidth();
            const activeIdx = Math.round(galleryTrack.scrollLeft / slideWidth);
            galleryDots.forEach((dot, idx) => {
                if (idx === activeIdx) {
                    dot.className = 'gallery-dot h-2 rounded-full transition-all duration-200 w-6 bg-amber-500 border border-slate-900';
                } else {
                    dot.className = 'gallery-dot h-2 rounded-full transition-all duration-200 w-2 bg-slate-300 dark:bg-slate-700';
                }
            });
        }, { passive: true });
    }
</script>
