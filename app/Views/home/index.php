<?php
use App\Config\App;
use App\Helpers\Formatter;

$settings = $settings ?? [];
$stats = $stats ?? ['total_anggota' => 0, 'total_proker' => 0, 'total_kegiatan' => 0];
$stats['total_anggota'] = $stats['total_anggota'] ?? 0;
$stats['total_proker'] = $stats['total_proker'] ?? 0;
$stats['total_kegiatan'] = $stats['total_kegiatan'] ?? 0;
$recentActivities = $recentActivities ?? [];
$galleryPhotos = $galleryPhotos ?? [];
$prokerList = $prokerList ?? [];
$featuredProkers = $featuredProkers ?? array_slice($prokerList, 0, 6);

$heroTitle = $settings['hero_title'] ?? 'RUMAH PERANTAU LAMPUNG';
$heroSub = $settings['hero_subtitle'] ?? 'Di Kota Salatiga';
$heroDesc = $settings['hero_description'] ?? 'Menjadi keluarga di tanah rantau, menjunjung tinggi budaya, dan meraih mimpi bersama.';
$aboutTitle = $settings['about_title'] ?? 'Lebih dari Sekedar Komunitas';
$about1 = $settings['about_content_1'] ?? '';
$about2 = $settings['about_content_2'] ?? '';
$sambutan = $settings['sambutan_ketua'] ?? '';
$namaKetua = $settings['nama_ketua'] ?? 'Ketua K\'mplang';
?>

<!-- 1. Hero Section -->
<section id="hero" class="relative min-h-[85vh] flex items-center justify-center text-center hero-bg px-4 sm:px-6 lg:px-8 py-20 transition-colors duration-200">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="text-xs sm:text-sm font-extrabold uppercase tracking-widest text-amber-600 dark:text-amber-400">
            Keluarga Mahasiswa Perantauan Lampung
        </div>
        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight text-slate-900 dark:text-white font-montserrat">
            <?= e($heroTitle) ?>
        </h1>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-amber-600 dark:text-amber-400 font-montserrat">
            <?= e($heroSub) ?>
        </h2>
        <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto leading-relaxed">
            <?= e($heroDesc) ?>
        </p>
        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?= App::baseUrl('/register') ?>" onclick="if(window.openAuthModal){event.preventDefault();openAuthModal('register');}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-full font-extrabold text-sm text-slate-950 bg-amber-500 hover:bg-amber-400 shadow-md hover:shadow-lg transition-all cursor-pointer">
                <span class="material-symbols-outlined">how_to_reg</span>
                Gabung K'mplang Sekarang
            </a>
            <a href="<?= App::baseUrl('/program-kerja') ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-full font-bold text-sm text-slate-800 dark:text-white bg-white/90 dark:bg-white/10 hover:bg-white dark:hover:bg-white/20 border border-slate-300 dark:border-white/20 shadow-sm backdrop-blur-md transition-all">
                <span class="material-symbols-outlined">assignment</span>
                Lihat Program Kerja
            </a>
        </div>
    </div>
</section>

<!-- 2. Statistics Counter Banner -->
<section class="border-y border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-10 relative z-20 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center divide-y sm:divide-y-0 sm:divide-x divide-slate-200 dark:divide-slate-800">
            <div class="pt-4 sm:pt-0">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 mb-3 border border-amber-500/20">
                    <span class="material-symbols-outlined text-2xl">groups</span>
                </div>
                <div class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-slate-100 font-montserrat"><?= $stats['total_anggota'] ?>+</div>
                <div class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mt-1">Anggota &amp; Mahasiswa</div>
            </div>
            <div class="pt-4 sm:pt-0">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 mb-3 border border-amber-500/20">
                    <span class="material-symbols-outlined text-2xl">event_available</span>
                </div>
                <div class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-slate-100 font-montserrat"><?= $stats['total_proker'] ?></div>
                <div class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mt-1">Program Kerja Periode Ini</div>
            </div>
            <div class="pt-4 sm:pt-0">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 mb-3 border border-amber-500/20">
                    <span class="material-symbols-outlined text-2xl">photo_camera</span>
                </div>
                <div class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-slate-100 font-montserrat"><?= $stats['total_kegiatan'] ?></div>
                <div class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mt-1">Dokumentasi &amp; Galeri</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Tentang & Sambutan Section -->
<section id="tentang" class="py-20 bg-slate-50 dark:bg-slate-950 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Left: Bio & Sambutan -->
            <div class="lg:col-span-7 space-y-6">
                <div class="text-xs font-bold uppercase tracking-widest text-amber-600 dark:text-amber-400">
                    Tentang Kami
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight leading-tight">
                    <?= e($aboutTitle) ?>
                </h2>
                <div class="space-y-4 text-slate-600 dark:text-slate-300 leading-relaxed text-sm sm:text-base">
                    <p><?= nl2br(e($about1)) ?></p>
                    <p><?= nl2br(e($about2)) ?></p>
                </div>

                <!-- Card Sambutan Ketua -->
                <?php if (!empty($sambutan)): ?>
                    <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border-l-4 border-amber-500 border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-xl space-y-3">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-2xl">record_voice_over</span>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900 dark:text-slate-100 font-montserrat">Sambutan Ketua</h4>
                                <p class="text-xs text-amber-600 dark:text-amber-400 font-semibold"><?= e($namaKetua) ?></p>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 italic leading-relaxed">
                            "<?= nl2br(e($sambutan)) ?>"
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right: Foto Insignia / Dokumentasi -->
            <div class="lg:col-span-5">
                <div class="relative rounded-3xl overflow-hidden shadow-md dark:shadow-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 group">
                    <img src="<?= App::baseUrl('/assets/images/ins.png') ?>" alt="Keluarga K'mplang Salatiga" class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="p-4 text-center border-t border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 font-montserrat">Kebersamaan Mahasiswa Perantau Lampung di Salatiga</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. Kenapa Harus Gabung Section -->
<section id="mengapa" class="py-20 bg-white dark:bg-slate-900/60 border-t border-slate-200 dark:border-slate-800 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <div class="text-xs font-bold uppercase tracking-widest text-amber-600 dark:text-amber-400 mb-2">
                Nilai &amp; Kebersamaan
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white font-montserrat">Mengapa Harus Gabung K'mplang?</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Bukan sekadar organisasi, melainkan rumah kedua di perantauan.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-8 hover:border-amber-500/40 transition-all shadow-sm group">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-2xl">diversity_3</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white font-montserrat mb-3">Mendapatkan Keluarga Baru</h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    Bertemu sesama mahasiswa asal Lampung di Salatiga untuk saling menguatkan, berbagi suka duka, dan belajar bersama.
                </p>
            </div>

            <div class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-8 hover:border-amber-500/40 transition-all shadow-sm group">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-2xl">theater_comedy</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white font-montserrat mb-3">Melestarikan Budaya Lampung</h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    Menjaga tradisi seni tari, musik, bahasa, dan kuliner khas Lampung agar tetap hidup dan dikenal luas di Jawa Tengah.
                </p>
            </div>

            <div class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-8 hover:border-amber-500/40 transition-all shadow-sm group">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-2xl">psychology</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white font-montserrat mb-3">Pengembangan Kepemimpinan</h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    Mengasah soft-skill, kepemimpinan, manajemen program kerja, dan jejaring relasi alumni yang bermanfaat untuk masa depan.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- 5. Transparansi Program Kerja Unggulan -->
<section id="program-kerja" class="py-20 bg-slate-50 dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <div class="text-xs font-bold uppercase tracking-widest text-amber-600 dark:text-amber-400 mb-2">
                    Transparansi Kerja
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white font-montserrat">Program Kerja Unggulan</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Daftar agenda kegiatan pengurus periode 2025/2026.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="<?= App::baseUrl('/program-kerja') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline group">
                    <span>Lihat Seluruh 23 Proker</span>
                    <span class="material-symbols-outlined text-base group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($featuredProkers)): ?>
                <div class="col-span-full py-12 text-center text-slate-500 dark:text-slate-400 text-sm italic">
                    Belum ada program kerja yang ditampilkan.
                </div>
            <?php else: ?>
                <?php foreach ($featuredProkers as $index => $proker): ?>
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 flex flex-col justify-between hover:border-amber-500/40 transition-all shadow-sm">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    <?= e($proker['divisi']) ?>
                                </span>
                                <span class="text-xs font-medium text-slate-500 dark:text-slate-400 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">schedule</span>
                                    <?= e($proker['waktu_kegiatan']) ?>
                                </span>
                            </div>
                            <h4 class="text-base font-bold text-slate-900 dark:text-slate-100 font-montserrat mb-2"><?= e($proker['nama_program']) ?></h4>
                            <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-3 leading-relaxed mb-4"><?= e($proker['tujuan']) ?></p>
                        </div>
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500">
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm text-slate-400">person</span>
                                PJ: <?= e($proker['penanggung_jawab'] ?: '-') ?>
                            </span>
                            <span class="font-bold text-amber-600 dark:text-amber-400"><?= e($proker['anggaran'] ?: 'Rp 0') ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 6. Dokumentasi & Album Kegiatan Terbaru -->
<section class="py-20 bg-white dark:bg-slate-900/40 border-t border-slate-200 dark:border-slate-800 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <div class="text-xs font-bold uppercase tracking-widest text-amber-600 dark:text-amber-400 mb-2">
                    Galeri Dokumentasi
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white font-montserrat">Galeri &amp; Kegiatan Terbaru</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Momen kebersamaan dan rekam jejak kegiatan K'mplang Salatiga.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="<?= App::baseUrl('/kegiatan') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline group">
                    <span>Lihat Seluruh Album</span>
                    <span class="material-symbols-outlined text-base group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>
        </div>

        <!-- Photo Grid Preview -->
        <?php if (!empty($galleryPhotos)): ?>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 mb-8">
                <?php foreach ($galleryPhotos as $photo): ?>
                    <?php 
                        $photoUrl = App::mediaUrl($photo['file_path'], 'activities');
                    ?>
                    <div class="gallery-photo-card group relative h-48 sm:h-64 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-sm cursor-pointer"
                         data-url="<?= $photoUrl ?>"
                         data-name="<?= e($photo['original_name']) ?>">
                        <img src="<?= $photoUrl ?>" alt="<?= e($photo['original_name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4">
                            <span class="text-xs font-bold text-white truncate"><?= e($photo['original_name']) ?></span>
                            <span class="text-[11px] text-amber-400 flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">photo_camera</span>
                                <?= e($photo['activity_title']) ?>
                            </span>
                        </div>
                        <div class="absolute top-3 right-3 bg-slate-950/50 backdrop-blur-md rounded-full w-8 h-8 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-sm">zoom_in</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Featured Album Summary Card -->
        <?php if (!empty($recentActivities)): ?>
            <?php $mainAct = $recentActivities[0]; ?>
            <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-2xl">folder_shared</span>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">Album Dokumentasi Utama</div>
                        <h4 class="font-bold text-sm sm:text-base text-slate-900 dark:text-slate-100 font-montserrat"><?= e($mainAct['judul']) ?></h4>
                        <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-3 mt-1">
                            <span><?= Formatter::tanggalIndo($mainAct['tanggal_kegiatan']) ?></span>
                            <span>&bull;</span>
                            <span><?= $mainAct['media_count'] ?? 6 ?> Berkas Foto / Media</span>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-auto">
                    <a href="<?= App::baseUrl('/kegiatan/' . $mainAct['slug']) ?>" class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-400 text-slate-950 shadow-sm transition-all">
                        <span class="material-symbols-outlined text-sm">folder_open</span>
                        Buka Galeri Berkas
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Lightbox Modal for Homepage Gallery -->
<div id="home-lightbox-modal" class="fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="relative max-w-4xl w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-800">
            <h3 id="home-lightbox-title" class="font-bold text-sm text-slate-900 dark:text-slate-200 truncate font-montserrat">Pratinjau Foto</h3>
            <button id="home-lightbox-close" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center">
                <span class="material-symbols-outlined text-base">close</span>
            </button>
        </div>
        <div class="p-4 flex items-center justify-center bg-black min-h-[300px] max-h-[75vh]">
            <img id="home-lightbox-img" src="" alt="Pratinjau" class="max-h-[70vh] max-w-full object-contain rounded-lg">
        </div>
    </div>
</div>

<!-- 7. Pemutar Musik Lampung Interaktif -->
<section id="musik-section" class="py-20 bg-slate-50 dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800 transition-colors duration-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="text-xs font-bold uppercase tracking-widest text-amber-600 dark:text-amber-400 mb-2">
                Pelestarian Seni
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white font-montserrat">Musik Asli Daerah Lampung</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Dengarkan lantunan melodi khas tanah Lampung untuk mengobati kerinduan kampung halaman.</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm dark:shadow-xl border border-slate-200 dark:border-slate-800">
            <!-- Playlist Items -->
            <div class="space-y-2 mb-8 max-h-64 overflow-y-auto pr-2" id="playlist-container">
                <!-- Dynamically filled by JS -->
            </div>

            <!-- Now Playing Bar -->
            <div class="bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl flex flex-col md:flex-row items-center gap-6">
                <!-- Track Meta -->
                <div class="flex items-center gap-4 w-full md:w-1/3">
                    <img id="player-art" src="<?= App::baseUrl('/assets/images/LampungThumb.jpg') ?>" alt="Album Cover" class="w-14 h-14 rounded-xl object-cover shadow-sm border border-slate-200 dark:border-slate-800">
                    <div class="min-w-0">
                        <h4 id="player-title" class="font-bold text-sm text-slate-900 dark:text-slate-100 truncate font-montserrat">Pilih lagu untuk diputar</h4>
                        <p id="player-artist" class="text-xs text-amber-600 dark:text-amber-400 font-semibold truncate">K'mplang Musik</p>
                    </div>
                </div>

                <!-- Controls -->
                <div class="flex items-center justify-center gap-3 w-full md:w-1/3">
                    <button id="prev-btn" class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 flex items-center justify-center transition-transform hover:scale-105" title="Sebelumnya">
                        <span class="material-symbols-outlined text-xl">skip_previous</span>
                    </button>
                    <button id="play-pause-btn" class="w-12 h-12 rounded-full bg-amber-500 hover:bg-amber-400 text-slate-950 flex items-center justify-center shadow-md transition-transform hover:scale-105 font-bold" title="Putar / Jeda">
                        <span class="material-symbols-outlined text-2xl" id="play-icon">play_arrow</span>
                    </button>
                    <button id="next-btn" class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 flex items-center justify-center transition-transform hover:scale-105" title="Berikutnya">
                        <span class="material-symbols-outlined text-xl">skip_next</span>
                    </button>
                </div>

                <!-- Progress Bar & Time -->
                <div class="flex items-center gap-3 w-full md:w-1/3">
                    <span id="current-time" class="text-xs font-mono text-slate-500 dark:text-slate-400 w-10 text-right">0:00</span>
                    <div id="progress-container" class="flex-1 bg-slate-200 dark:bg-slate-800 cursor-pointer rounded-full h-2 relative overflow-hidden">
                        <div id="progress-bar" class="bg-amber-500 h-full w-0 rounded-full transition-all"></div>
                    </div>
                    <span id="total-duration" class="text-xs font-mono text-slate-500 dark:text-slate-400 w-10">0:00</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 8. CTA Join Banner (Dual-Theme) -->
<section class="py-20 bg-gradient-to-b from-amber-50/50 via-slate-100/70 to-white dark:from-slate-900 dark:via-slate-950 dark:to-slate-900 border-t border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white relative overflow-hidden transition-colors duration-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-3xl sm:text-5xl font-black font-montserrat mb-4 text-slate-900 dark:text-white">
            Jadilah Bagian dari Keluarga K'mplang
        </h2>
        <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto mb-8 leading-relaxed">
            Pendaftaran terbuka untuk seluruh mahasiswa perantau asal Lampung di UKSW dan perguruan tinggi se-Kota Salatiga.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?= App::baseUrl('/register') ?>" onclick="if(window.openAuthModal){event.preventDefault();openAuthModal('register');}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-full font-extrabold text-sm text-slate-950 bg-amber-500 hover:bg-amber-400 shadow-md transition-all cursor-pointer">
                <span class="material-symbols-outlined">how_to_reg</span>
                Daftar Sekarang
            </a>
            <a href="<?= App::baseUrl('/anggota') ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-full font-bold text-sm text-slate-800 hover:text-slate-950 bg-white hover:bg-slate-50 border border-slate-300 shadow-sm dark:text-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:border-slate-700 transition-all">
                <span class="material-symbols-outlined">badge</span>
                Lihat Direktori Anggota
            </a>
        </div>
    </div>
</section>

<!-- Music Player Logic -->
<script>
    const songList = [
        { name: "Tanoh Lado", artist: "TyoAlfando", src: "<?= App::baseUrl('/assets/music/tanohLado.mp3') ?>", img: "<?= App::baseUrl('/assets/images/LampungThumb.jpg') ?>" },
        { name: "Lampung Sai", artist: "TyoAlfando", src: "<?= App::baseUrl('/assets/music/lampungSai.mp3') ?>", img: "<?= App::baseUrl('/assets/images/LampungThumb.jpg') ?>" },
        { name: "REMIX LAMUNAN", artist: "AlfandoProduction", src: "<?= App::baseUrl('/assets/music/remixLamunan.mp3') ?>", img: "<?= App::baseUrl('/assets/images/remix.jpg') ?>" }
    ];

    let currentSongIndex = 0;
    const audio = new Audio();
    const playlistContainer = document.getElementById('playlist-container');
    const playPauseBtn = document.getElementById('play-pause-btn');
    const playIcon = document.getElementById('play-icon');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const playerTitle = document.getElementById('player-title');
    const playerArtist = document.getElementById('player-artist');
    const playerArt = document.getElementById('player-art');
    const progressBar = document.getElementById('progress-bar');
    const progressContainer = document.getElementById('progress-container');
    const currentTimeEl = document.getElementById('current-time');
    const totalDurationEl = document.getElementById('total-duration');

    function renderPlaylist() {
        playlistContainer.innerHTML = '';
        songList.forEach((song, idx) => {
            const isCurrent = idx === currentSongIndex;
            const item = document.createElement('div');
            item.className = 'flex items-center justify-between p-3 rounded-xl cursor-pointer transition-all ' +
                (isCurrent
                    ? 'bg-amber-500/15 border border-amber-500/30 text-amber-700 dark:text-amber-400 font-bold'
                    : 'bg-slate-50 dark:bg-slate-800/40 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-transparent');
            item.innerHTML = `
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-lg ${isCurrent ? 'text-amber-600 dark:text-amber-400' : 'text-slate-400 dark:text-slate-500'}">
                        ${isCurrent && !audio.paused ? 'graphic_eq' : 'music_note'}
                    </span>
                    <div>
                        <div class="font-bold text-xs sm:text-sm font-montserrat">${song.name}</div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400">${song.artist}</div>
                    </div>
                </div>
                <span class="material-symbols-outlined text-sm text-slate-400">play_circle</span>
            `;
            item.addEventListener('click', () => {
                currentSongIndex = idx;
                loadSong(currentSongIndex);
                audio.play();
                updateUI();
            });
            playlistContainer.appendChild(item);
        });
    }

    function loadSong(index) {
        const song = songList[index];
        audio.src = song.src;
        playerTitle.textContent = song.name;
        playerArtist.textContent = song.artist;
        playerArt.src = song.img;
        updateUI();
    }

    function updateUI() {
        renderPlaylist();
        if (audio.paused) {
            playIcon.textContent = 'play_arrow';
        } else {
            playIcon.textContent = 'pause';
        }
    }

    playPauseBtn.addEventListener('click', () => {
        if (!audio.src) {
            loadSong(0);
        }
        if (audio.paused) {
            audio.play();
        } else {
            audio.pause();
        }
        updateUI();
    });

    prevBtn.addEventListener('click', () => {
        currentSongIndex = (currentSongIndex - 1 + songList.length) % songList.length;
        loadSong(currentSongIndex);
        audio.play();
        updateUI();
    });

    nextBtn.addEventListener('click', () => {
        currentSongIndex = (currentSongIndex + 1) % songList.length;
        loadSong(currentSongIndex);
        audio.play();
        updateUI();
    });

    audio.addEventListener('timeupdate', () => {
        if (audio.duration) {
            const pct = (audio.currentTime / audio.duration) * 100;
            progressBar.style.width = pct + '%';

            const format = (t) => {
                const m = Math.floor(t / 60);
                const s = Math.floor(t % 60);
                return `${m}:${s < 10 ? '0' : ''}${s}`;
            };
            currentTimeEl.textContent = format(audio.currentTime);
            totalDurationEl.textContent = format(audio.duration);
        }
    });

    audio.addEventListener('ended', () => {
        currentSongIndex = (currentSongIndex + 1) % songList.length;
        loadSong(currentSongIndex);
        audio.play();
        updateUI();
    });

    progressContainer.addEventListener('click', (e) => {
        const width = progressContainer.clientWidth;
        const clickX = e.offsetX;
        if (audio.duration) {
            audio.currentTime = (clickX / width) * audio.duration;
        }
    });

    // Initialize with first song metadata
    loadSong(0);

    // Homepage Gallery Lightbox Logic
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
        homeModalClose.addEventListener('click', () => {
            if (homeModal) homeModal.classList.add('hidden');
        });
    }

    if (homeModal) {
        homeModal.addEventListener('click', (e) => {
            if (e.target === homeModal) {
                homeModal.classList.add('hidden');
            }
        });
    }
</script>
