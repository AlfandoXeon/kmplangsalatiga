<?php
/**
 * View: activities/detail.php
 * -------------------------------------------------------------------------
 * Halaman Detail Kegiatan & Galeri Dokumentasi Bergaya Media Sosial
 * (Instagram/Facebook Interactive Carousel + Unduh Per Foto & Unduh Album ZIP).
 * -------------------------------------------------------------------------
 */
use App\Config\App;
use App\Config\Security;
use App\Helpers\Formatter;

$csrfToken = Security::generateCsrfToken();
$activity = $activity ?? [];
$mediaList = $mediaList ?? [];
$comments = $comments ?? [];
$currentUserId = (int) ($_SESSION['user']['id'] ?? $_SESSION['user_id'] ?? 0);
$currentUserRole = $_SESSION['user']['role'] ?? $_SESSION['user_role'] ?? '';
$totalMedia = count($mediaList);
?>

<div class="py-10 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 font-mono text-xs text-slate-500 dark:text-slate-400">
            <a href="<?= App::baseUrl('/') ?>" class="hover:text-slate-900 dark:hover:text-slate-100">Beranda</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="<?= App::baseUrl('/kegiatan') ?>" class="hover:text-slate-900 dark:hover:text-slate-100">Dokumentasi</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-slate-900 dark:text-slate-100 font-bold truncate"><?= e($activity['judul']) ?></span>
        </nav>

        <!-- Main Social Media Post / Album Card -->
        <div class="poster-card overflow-hidden">
            <!-- Post Header (Informasi Kegiatan & Tombol Unduh Album ZIP) -->
            <div class="p-5 sm:p-7 border-b border-slate-300 dark:border-slate-800">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1.5">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="poster-badge"><?= e($activity['kategori_kegiatan'] ?? 'Dokumentasi') ?></span>
                            <span class="font-mono text-xs text-slate-500 dark:text-slate-400">ARSIP #<?= (int) $activity['id'] ?></span>
                        </div>
                        <h1 class="poster-title text-2xl sm:text-3xl text-slate-900 dark:text-white">
                            <?= e($activity['judul']) ?>
                        </h1>
                        <div class="flex flex-wrap items-center gap-4 font-mono text-xs text-slate-600 dark:text-slate-400 pt-0.5">
                            <span class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm">calendar_month</span>
                                <?= Formatter::tanggalIndo($activity['tanggal_kegiatan']) ?>
                            </span>
                            <?php if (!empty($activity['lokasi'])): ?>
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm">location_on</span>
                                    <?= e($activity['lokasi']) ?>
                                </span>
                            <?php endif; ?>
                            <span class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                                <?= (int) $activity['view_count'] ?> Kali Dilihat
                            </span>
                        </div>
                    </div>

                    <?php if ($totalMedia > 0): ?>
                        <div class="shrink-0 flex items-center gap-2">
                            <a href="<?= App::baseUrl('/download/album/' . $activity['id']) ?>" class="px-4 py-2.5 rounded-lg text-xs font-bold bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 hover:opacity-90 transition-opacity inline-flex items-center gap-2">
                                <span class="material-symbols-outlined text-base">folder_zip</span>
                                <span>Unduh Seluruh Album (ZIP)</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($activity['deskripsi'])): ?>
                    <p class="pt-4 mt-4 border-t border-slate-200 dark:border-slate-800 font-serif-reading text-sm sm:text-base text-slate-700 dark:text-slate-300 leading-relaxed">
                        <?= nl2br(e($activity['deskripsi'])) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Social Media Photo/Video Carousel Section (Instagram / Facebook Style) -->
            <?php if ($totalMedia === 0): ?>
                <div class="py-16 text-center text-slate-500 dark:text-slate-400 space-y-3">
                    <span class="material-symbols-outlined text-5xl">photo_library</span>
                    <p class="text-sm">Belum ada foto atau video yang diunggah pada album dokumentasi ini.</p>
                </div>
            <?php else: ?>
                <!-- View Switcher Bar (Mode Carousel vs Mode Grid Semua Foto) -->
                <div class="px-5 py-3 bg-slate-100/80 dark:bg-slate-900/90 border-b border-slate-300 dark:border-slate-800 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-800 dark:text-slate-200">
                        <span class="material-symbols-outlined text-base text-slate-600 dark:text-slate-400">photo_library</span>
                        <span>Galeri Dokumentasi (<?= $totalMedia ?> Foto/Video)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button type="button" id="btn-mode-carousel" onclick="setGalleryViewMode('carousel')" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 flex items-center gap-1.5 transition-all">
                            <span class="material-symbols-outlined text-sm">view_carousel</span>
                            <span>Slide Carousel</span>
                        </button>
                        <button type="button" id="btn-mode-grid" onclick="setGalleryViewMode('grid')" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 flex items-center gap-1.5 transition-all">
                            <span class="material-symbols-outlined text-sm">grid_on</span>
                            <span>Semua Grid</span>
                        </button>
                    </div>
                </div>

                <!-- 1. MODE CAROUSEL (Instagram / Facebook Feed Style) -->
                <div id="gallery-carousel-view">
                    <!-- Main Swipeable Carousel Frame -->
                    <div class="relative bg-slate-950 select-none group">
                        <!-- Indikator Urutan Slide (Contoh: 1 / 5) ala Instagram -->
                        <div class="absolute top-4 right-4 z-20 px-3 py-1 rounded-full bg-slate-900/80 text-white font-mono text-xs font-bold backdrop-blur-md border border-white/15">
                            <span id="carousel-current-index">1</span> / <?= $totalMedia ?>
                        </div>

                        <?php if ($totalMedia > 1): ?>
                            <!-- Tombol Geser Kiri -->
                            <button type="button"
                                    onclick="stepDocCarousel(-1)"
                                    aria-label="Foto Sebelumnya"
                                    class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-slate-900/75 hover:bg-slate-900 text-white border border-white/20 backdrop-blur-md flex items-center justify-center transition-all shadow-lg">
                                <span class="material-symbols-outlined text-xl">chevron_left</span>
                            </button>

                            <!-- Tombol Geser Kanan -->
                            <button type="button"
                                    onclick="stepDocCarousel(1)"
                                    aria-label="Foto Berikutnya"
                                    class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-slate-900/75 hover:bg-slate-900 text-white border border-white/20 backdrop-blur-md flex items-center justify-center transition-all shadow-lg">
                                <span class="material-symbols-outlined text-xl">chevron_right</span>
                            </button>
                        <?php endif; ?>

                        <!-- Track Horizontal Scroll-Snap -->
                        <div id="doc-carousel-track"
                             class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar"
                             style="scrollbar-width: none; -ms-overflow-style: none;">
                            <?php foreach ($mediaList as $idx => $item): ?>
                                <?php
                                    $filePath = App::mediaUrl($item['file_path'], 'activities', 'thumb.jpg');
                                    $downloadUrl = App::baseUrl('/download/file/' . $item['id']);
                                    $captionName = !empty($item['original_name']) ? pathinfo($item['original_name'], PATHINFO_FILENAME) : ('Dokumentasi #' . ($idx + 1));
                                    $sizeFormatted = Formatter::formatBytes($item['file_size']);
                                ?>
                                <div class="doc-carousel-slide w-full shrink-0 snap-center relative flex items-center justify-center h-[340px] sm:h-[460px] md:h-[520px] bg-slate-950"
                                     data-index="<?= $idx ?>"
                                     data-name="<?= e($captionName) ?>"
                                     data-size="<?= e($sizeFormatted) ?>"
                                     data-download="<?= $downloadUrl ?>"
                                     data-preview-url="<?= $filePath ?>"
                                     data-preview-type="<?= e($item['file_type']) ?>">
                                    <?php if ($item['file_type'] === 'image'): ?>
                                        <img src="<?= $filePath ?>"
                                             alt="<?= e($captionName) ?>"
                                             class="max-h-full max-w-full w-auto h-auto object-contain cursor-zoom-in"
                                             onclick="openPreviewModal('<?= $filePath ?>', '<?= e($captionName) ?>', 'image', '<?= $downloadUrl ?>')">
                                    <?php else: ?>
                                        <video src="<?= $filePath ?>"
                                               controls
                                               preload="metadata"
                                               class="max-h-full max-w-full rounded-lg"></video>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Social Media Action & Download Bar (Bawah Foto Aktif) -->
                    <div class="p-4 sm:px-6 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <!-- Keterangan Foto yang Sedang Dilihat -->
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[11px] font-bold uppercase px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700">
                                        Foto <span id="active-photo-num">1</span> dari <?= $totalMedia ?>
                                    </span>
                                    <span id="active-photo-size" class="font-mono text-xs text-slate-500 dark:text-slate-400">
                                        <?= Formatter::formatBytes($mediaList[0]['file_size'] ?? 0) ?>
                                    </span>
                                </div>
                                <h3 id="active-photo-title" class="font-display font-bold text-sm sm:text-base text-slate-900 dark:text-slate-100 truncate mt-1">
                                    <?= e(!empty($mediaList[0]['original_name']) ? pathinfo($mediaList[0]['original_name'], PATHINFO_FILENAME) : 'Dokumentasi #1') ?>
                                </h3>
                            </div>

                            <!-- Tombol Perbesar & Unduh Foto Ini -->
                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button"
                                        id="active-photo-zoom-btn"
                                        onclick="zoomActiveSlide()"
                                        class="px-3.5 py-2 rounded-lg text-xs font-bold bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 inline-flex items-center gap-1.5 transition-colors">
                                    <span class="material-symbols-outlined text-base">fullscreen</span>
                                    <span>Layar Penuh</span>
                                </button>
                                <a id="active-photo-download-btn"
                                   href="<?= App::baseUrl('/download/file/' . ($mediaList[0]['id'] ?? 0)) ?>"
                                   class="px-4 py-2 rounded-lg text-xs font-bold bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 hover:opacity-90 inline-flex items-center gap-1.5 transition-opacity">
                                    <span class="material-symbols-outlined text-base">download</span>
                                    <span>Unduh Foto Ini</span>
                                </a>
                            </div>
                        </div>

                        <?php if ($totalMedia > 1): ?>
                            <!-- Strip Thumbnail Navigasi Cepat -->
                            <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex items-center gap-2.5 overflow-x-auto pb-1">
                                <?php foreach ($mediaList as $idx => $item): ?>
                                    <?php $thumbPath = App::mediaUrl($item['file_path'], 'activities', 'thumb.jpg'); ?>
                                    <button type="button"
                                            onclick="goToDocSlide(<?= $idx ?>)"
                                            class="doc-thumb-btn relative w-16 h-16 sm:w-20 sm:h-16 rounded-lg overflow-hidden shrink-0 border-2 transition-all <?= $idx === 0 ? 'border-slate-900 dark:border-white opacity-100 ring-2 ring-slate-900/20 dark:ring-white/20' : 'border-transparent opacity-50 hover:opacity-80' ?>"
                                            data-thumb-index="<?= $idx ?>">
                                        <?php if ($item['file_type'] === 'image'): ?>
                                            <img src="<?= $thumbPath ?>" alt="Thumb <?= $idx + 1 ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-slate-800 flex items-center justify-center text-white">
                                                <span class="material-symbols-outlined text-lg">play_circle</span>
                                            </div>
                                        <?php endif; ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 2. MODE GRID SEMUA FOTO (Instagram Profile Grid Style dengan Tombol Unduh Masing-Masing) -->
                <div id="gallery-grid-view" class="hidden p-5 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($mediaList as $idx => $item): ?>
                            <?php
                                $filePath = App::mediaUrl($item['file_path'], 'activities', 'thumb.jpg');
                                $downloadUrl = App::baseUrl('/download/file/' . $item['id']);
                                $captionName = !empty($item['original_name']) ? pathinfo($item['original_name'], PATHINFO_FILENAME) : ('Dokumentasi #' . ($idx + 1));
                            ?>
                            <div class="bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl overflow-hidden flex flex-col justify-between group">
                                <div class="relative aspect-[4/3] bg-slate-200 dark:bg-slate-950 overflow-hidden cursor-pointer"
                                     onclick="openPreviewModal('<?= $filePath ?>', '<?= e($captionName) ?>', '<?= e($item['file_type']) ?>', '<?= $downloadUrl ?>')">
                                    <?php if ($item['file_type'] === 'image'): ?>
                                        <img src="<?= $filePath ?>" alt="<?= e($captionName) ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex flex-col items-center justify-center gap-1 text-slate-400">
                                            <span class="material-symbols-outlined text-4xl">play_circle</span>
                                            <span class="font-mono text-[10px] font-bold uppercase">Video</span>
                                        </div>
                                    <?php endif; ?>
                                    <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded bg-slate-900/75 text-white font-mono text-[10px] font-bold">
                                        #<?= $idx + 1 ?>
                                    </span>
                                </div>

                                <div class="p-3.5 flex items-center justify-between gap-2 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-slate-900 dark:text-slate-100 truncate" title="<?= e($captionName) ?>">
                                            <?= e($captionName) ?>
                                        </div>
                                        <div class="font-mono text-[10px] text-slate-500 dark:text-slate-400">
                                            <?= Formatter::formatBytes($item['file_size']) ?>
                                        </div>
                                    </div>
                                    <a href="<?= $downloadUrl ?>"
                                       class="px-3 py-1.5 rounded-lg bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 hover:opacity-90 text-xs font-bold inline-flex items-center gap-1 shrink-0 transition-opacity"
                                       title="Unduh Foto Ini">
                                        <span class="material-symbols-outlined text-sm">download</span>
                                        <span>Unduh</span>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Lightbox / Fullscreen Modal (Dilengkapi Tombol Unduh Langsung) -->
        <div id="preview-modal" class="fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-md hidden flex items-center justify-center p-4">
            <div class="relative max-w-5xl w-full poster-card overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900">
                    <h3 id="modal-filename" class="font-display font-bold text-sm text-slate-900 dark:text-slate-100 truncate">Pratinjau Dokumentasi</h3>
                    <div class="flex items-center gap-2">
                        <a id="modal-download-btn" href="#" class="px-3 py-1.5 rounded-lg bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 text-xs font-bold inline-flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">download</span>
                            <span>Unduh</span>
                        </a>
                        <button type="button" onclick="closePreviewModal()" class="w-8 h-8 rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center">
                            <span class="material-symbols-outlined text-base">close</span>
                        </button>
                    </div>
                </div>
                <div class="p-4 flex items-center justify-center bg-slate-950 min-h-[360px] max-h-[78vh]">
                    <img id="modal-image" src="" alt="Pratinjau" class="max-h-[72vh] max-w-full object-contain rounded-lg hidden">
                    <video id="modal-video" src="" controls class="max-h-[72vh] max-w-full rounded-lg hidden"></video>
                </div>
            </div>
        </div>

        <!-- Community Comments Section -->
        <div class="poster-card p-6 sm:p-8 space-y-6">
            <div class="poster-card-header">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-slate-700 dark:text-slate-300 text-xl">forum</span>
                    <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white">Tanggapan &amp; Diskusi (<?= count($comments) ?>)</h3>
                </div>
            </div>

            <div class="space-y-4">
                <?php if (empty($comments)): ?>
                    <p class="font-serif-reading text-sm text-slate-500 italic py-3">Belum ada tanggapan untuk dokumentasi kegiatan ini.</p>
                <?php else: ?>
                    <?php foreach ($comments as $comm): ?>
                        <div class="bg-slate-100/70 dark:bg-slate-900/70 p-4 rounded-xl border border-slate-300 dark:border-slate-700 space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-slate-200 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 flex items-center justify-center text-xs font-bold text-slate-900 dark:text-slate-100 uppercase">
                                        <?= substr($comm['nama_lengkap'], 0, 1) ?>
                                    </div>
                                    <div>
                                        <span class="text-xs font-bold text-slate-900 dark:text-slate-100"><?= e($comm['nama_lengkap']) ?></span>
                                        <?php if (($comm['role'] ?? '') === 'admin'): ?>
                                            <span class="ml-1.5 text-[9px] font-mono font-bold uppercase px-1.5 py-0.5 rounded bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200">Pengurus</span>
                                        <?php endif; ?>
                                        <div class="font-mono text-[10px] text-slate-500 dark:text-slate-400"><?= Formatter::tanggalIndo($comm['created_at'], true) ?></div>
                                    </div>
                                </div>
                                <?php if ($currentUserId === (int) $comm['user_id'] || $currentUserRole === 'admin'): ?>
                                    <form action="<?= App::baseUrl('/komentar/delete/' . $comm['id']) ?>" method="POST" onsubmit="return confirm('Hapus komentar ini?');">
                                        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                        <input type="hidden" name="redirect_url" value="/kegiatan/<?= e($activity['slug']) ?>">
                                        <button type="submit" class="text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 hover:underline text-[11px] font-mono flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">delete</span> Hapus
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <p class="font-serif-reading text-sm text-slate-800 dark:text-slate-200 leading-relaxed pl-10"><?= nl2br(e($comm['content'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php if (!empty($currentUser)): ?>
                <form action="<?= App::baseUrl('/komentar') ?>" method="POST" class="pt-4 border-t border-slate-300 dark:border-slate-700 space-y-3">
                    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                    <input type="hidden" name="post_type" value="activity">
                    <input type="hidden" name="post_id" value="<?= (int) $activity['id'] ?>">
                    <input type="hidden" name="redirect_url" value="/kegiatan/<?= e($activity['slug']) ?>">

                    <label class="block font-mono text-xs uppercase tracking-wider font-bold text-slate-700 dark:text-slate-300">Tulis Tanggapan Anda</label>
                    <textarea name="content" rows="3" required placeholder="Tuliskan kesan, dokumentasi tambahan, atau apresiasi Anda..." class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl p-3.5 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-slate-500 transition-colors"></textarea>

                    <div class="flex justify-end">
                        <button type="submit" class="poster-btn-primary px-5 py-2.5 rounded-lg text-xs">
                            <span class="material-symbols-outlined text-sm">send</span>
                            Kirim Tanggapan
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="p-4 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-700 dark:text-slate-300">
                    <span>Ingin berdiskusi atau memberikan tanggapan? Silakan masuk menggunakan akun anggota Anda.</span>
                    <button type="button" onclick="openAuthModal('login')" class="poster-btn-primary px-4 py-2 rounded-lg text-xs">
                        Masuk Akun
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
(function() {
    const track = document.getElementById('doc-carousel-track');
    const slides = document.querySelectorAll('.doc-carousel-slide');
    const thumbs = document.querySelectorAll('.doc-thumb-btn');
    const currentIndexEl = document.getElementById('carousel-current-index');
    const activeNumEl = document.getElementById('active-photo-num');
    const activeTitleEl = document.getElementById('active-photo-title');
    const activeSizeEl = document.getElementById('active-photo-size');
    const activeDownloadBtn = document.getElementById('active-photo-download-btn');

    let activeIndex = 0;

    function syncCarouselState(index) {
        if (!slides.length) return;
        activeIndex = Math.max(0, Math.min(index, slides.length - 1));
        const slide = slides[activeIndex];

        if (currentIndexEl) currentIndexEl.textContent = activeIndex + 1;
        if (activeNumEl) activeNumEl.textContent = activeIndex + 1;
        if (activeTitleEl && slide.dataset.name) activeTitleEl.textContent = slide.dataset.name;
        if (activeSizeEl && slide.dataset.size) activeSizeEl.textContent = slide.dataset.size;
        if (activeDownloadBtn && slide.dataset.download) activeDownloadBtn.href = slide.dataset.download;

        thumbs.forEach((btn, idx) => {
            if (idx === activeIndex) {
                btn.className = 'doc-thumb-btn relative w-16 h-16 sm:w-20 sm:h-16 rounded-lg overflow-hidden shrink-0 border-2 transition-all border-slate-900 dark:border-white opacity-100 ring-2 ring-slate-900/20 dark:ring-white/20';
                btn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            } else {
                btn.className = 'doc-thumb-btn relative w-16 h-16 sm:w-20 sm:h-16 rounded-lg overflow-hidden shrink-0 border-2 transition-all border-transparent opacity-50 hover:opacity-80';
            }
        });
    }

    window.goToDocSlide = function(index) {
        if (!track || !slides.length) return;
        const targetIndex = (index + slides.length) % slides.length;
        const slideWidth = track.clientWidth;
        track.scrollTo({ left: slideWidth * targetIndex, behavior: 'smooth' });
        syncCarouselState(targetIndex);
    };

    window.stepDocCarousel = function(delta) {
        window.goToDocSlide(activeIndex + delta);
    };

    window.zoomActiveSlide = function() {
        if (!slides.length) return;
        const slide = slides[activeIndex];
        openPreviewModal(
            slide.dataset.previewUrl,
            slide.dataset.name,
            slide.dataset.previewType,
            slide.dataset.download
        );
    };

    if (track && slides.length > 0) {
        let scrollTimer = null;
        track.addEventListener('scroll', () => {
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(() => {
                const w = track.clientWidth || 1;
                const newIdx = Math.round(track.scrollLeft / w);
                if (newIdx !== activeIndex) {
                    syncCarouselState(newIdx);
                }
            }, 60);
        }, { passive: true });

        // Dukungan tombol panah kiri/kanan pada keyboard
        document.addEventListener('keydown', (e) => {
            if (e.target && ['INPUT', 'TEXTAREA'].includes(e.target.tagName)) return;
            if (e.key === 'ArrowLeft') window.stepDocCarousel(-1);
            if (e.key === 'ArrowRight') window.stepDocCarousel(1);
            if (e.key === 'Escape') closePreviewModal();
        });
    }

    // Sakelar Mode Carousel vs Mode Grid
    window.setGalleryViewMode = function(mode) {
        const carouselView = document.getElementById('gallery-carousel-view');
        const gridView = document.getElementById('gallery-grid-view');
        const btnCarousel = document.getElementById('btn-mode-carousel');
        const btnGrid = document.getElementById('btn-mode-grid');
        if (!carouselView || !gridView) return;

        if (mode === 'grid') {
            carouselView.classList.add('hidden');
            gridView.classList.remove('hidden');
            btnGrid.className = 'px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 flex items-center gap-1.5 transition-all';
            btnCarousel.className = 'px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 flex items-center gap-1.5 transition-all';
        } else {
            gridView.classList.add('hidden');
            carouselView.classList.remove('hidden');
            btnCarousel.className = 'px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 flex items-center gap-1.5 transition-all';
            btnGrid.className = 'px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 flex items-center gap-1.5 transition-all';
        }
    };

    // Modal Layar Penuh (Lightbox)
    const modal = document.getElementById('preview-modal');
    const modalImage = document.getElementById('modal-image');
    const modalVideo = document.getElementById('modal-video');
    const modalFilename = document.getElementById('modal-filename');
    const modalDownloadBtn = document.getElementById('modal-download-btn');

    window.openPreviewModal = function(url, name, type, downloadUrl) {
        if (!modal) return;
        modalFilename.textContent = name || 'Pratinjau Dokumentasi';
        if (modalDownloadBtn && downloadUrl) {
            modalDownloadBtn.href = downloadUrl;
        }
        if (type === 'image') {
            modalImage.src = url;
            modalImage.classList.remove('hidden');
            modalVideo.classList.add('hidden');
            modalVideo.pause();
        } else {
            modalVideo.src = url;
            modalVideo.classList.remove('hidden');
            modalImage.classList.add('hidden');
        }
        modal.classList.remove('hidden');
    };

    window.closePreviewModal = function() {
        if (!modal) return;
        modal.classList.add('hidden');
        modalImage.src = '';
        modalVideo.src = '';
        modalVideo.pause();
    };

    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) window.closePreviewModal();
        });
    }
})();
</script>
