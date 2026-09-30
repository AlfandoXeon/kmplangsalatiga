<?php
use App\Config\App;
use App\Config\Security;
use App\Helpers\Formatter;

$csrfToken = Security::generateCsrfToken();
$activity = $activity ?? [];
$mediaList = $mediaList ?? [];
$comments = $comments ?? [];
?>

<div class="py-10 bg-slate-50 dark:bg-slate-950 transition-colors duration-200 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Breadcrumbs & Title Bar -->
        <div class="space-y-4">
            <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <a href="<?= App::baseUrl('/') ?>" class="hover:text-amber-600 dark:hover:text-amber-400">Beranda</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <a href="<?= App::baseUrl('/kegiatan') ?>" class="hover:text-amber-600 dark:hover:text-amber-400">Dokumentasi</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-amber-600 dark:text-amber-400 font-semibold truncate"><?= e($activity['judul']) ?></span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-6">
                <div>
                    <h1 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight mb-2">
                        <?= e($activity['judul']) ?>
                    </h1>
                    <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm text-amber-500">calendar_month</span>
                            <?= Formatter::tanggalIndo($activity['tanggal_kegiatan']) ?>
                        </span>
                        <?php if (!empty($activity['lokasi'])): ?>
                            <span class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm text-amber-500">location_on</span>
                                <?= e($activity['lokasi']) ?>
                            </span>
                        <?php endif; ?>
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm text-amber-500">visibility</span>
                            <?= $activity['view_count'] ?> Kali Dilihat
                        </span>
                    </div>
                </div>

                <!-- Album Download Action -->
                <div>
                    <a href="<?= App::baseUrl('/download/album/' . $activity['id']) ?>" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-xs font-bold text-slate-950 bg-amber-500 hover:bg-amber-400 shadow-sm hover:shadow transition-all">
                        <span class="material-symbols-outlined text-lg">folder_zip</span>
                        Unduh Seluruh Album (ZIP)
                    </a>
                </div>
            </div>

            <!-- Narrative Description -->
            <?php if (!empty($activity['deskripsi'])): ?>
                <div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 rounded-2xl p-6 text-sm text-slate-700 dark:text-slate-300 leading-relaxed shadow-sm">
                    <?= nl2br(e($activity['deskripsi'])) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- File Explorer Section -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm dark:shadow-xl">
            <!-- Explorer Toolbar -->
            <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50 dark:bg-slate-900/90">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">folder_shared</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-slate-100 font-montserrat flex items-center gap-2">
                            <span>Galeri Berkas Dokumentasi</span>
                            <span class="text-xs font-normal text-slate-500 dark:text-slate-400">(<?= count($mediaList) ?> Berkas Media)</span>
                        </h2>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Klik berkas untuk pratinjau resolusi penuh atau unduh langsung.</p>
                    </div>
                </div>

                <!-- View Mode Toggle (Grid / List) -->
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500 dark:text-slate-400 mr-1">Tampilan:</span>
                    <button id="view-grid-btn" class="p-2 rounded-lg bg-amber-500 text-slate-950 font-bold transition-all shadow-sm" title="Tampilan Grid">
                        <span class="material-symbols-outlined text-base">grid_view</span>
                    </button>
                    <button id="view-list-btn" class="p-2 rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all" title="Tampilan List">
                        <span class="material-symbols-outlined text-base">view_list</span>
                    </button>
                </div>
            </div>

            <!-- Explorer Content Area -->
            <div class="p-6">
                <?php if (empty($mediaList)): ?>
                    <div class="py-16 text-center text-slate-400 dark:text-slate-500 space-y-3">
                        <span class="material-symbols-outlined text-5xl">folder_off</span>
                        <p class="text-sm">Belum ada foto atau video yang diunggah pada album ini.</p>
                    </div>
                <?php else: ?>
                    <!-- Grid View Container -->
                    <div id="drive-grid-container" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                        <?php foreach ($mediaList as $item): ?>
                            <?php
                                $filePath = App::mediaUrl($item['file_path'], 'activities');
                            ?>
                            <div class="drive-card bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden flex flex-col justify-between group shadow-sm hover:border-amber-500/50">
                                <!-- Thumbnail Box -->
                                <div class="preview-trigger-btn relative h-32 bg-slate-100 dark:bg-slate-900 overflow-hidden flex items-center justify-center cursor-pointer"
                                     data-preview-url="<?= $filePath ?>"
                                     data-preview-name="<?= e($item['original_name']) ?>"
                                     data-preview-type="<?= $item['file_type'] ?>">
                                    <?php if ($item['file_type'] === 'image'): ?>
                                        <img src="<?= $filePath ?>" alt="<?= e($item['original_name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <?php else: ?>
                                        <div class="flex flex-col items-center gap-1 text-slate-500">
                                            <span class="material-symbols-outlined text-4xl text-amber-500">play_circle</span>
                                            <span class="text-[10px] font-bold uppercase tracking-wider">Video</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                        <span class="material-symbols-outlined text-white text-3xl">visibility</span>
                                    </div>
                                </div>

                                <!-- File Metadata Box -->
                                <div class="p-3 border-t border-slate-200 dark:border-slate-800/80 bg-white dark:bg-slate-900/60">
                                    <div class="flex items-start gap-2 mb-2">
                                        <span class="material-symbols-outlined text-amber-500 text-sm mt-0.5">
                                            <?= $item['file_type'] === 'image' ? 'image' : 'video_file' ?>
                                        </span>
                                        <span class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate flex-1" title="<?= e($item['original_name']) ?>">
                                            <?= e($item['original_name']) ?>
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                                        <span><?= Formatter::formatBytes($item['file_size']) ?></span>
                                        <a href="<?= App::baseUrl('/download/file/' . $item['id']) ?>" class="p-1 rounded hover:bg-amber-500 hover:text-slate-950 text-slate-600 dark:text-slate-400 transition-colors" title="Unduh Berkas">
                                            <span class="material-symbols-outlined text-base">download</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- List View Container (Hidden by default) -->
                    <div id="drive-list-container" class="hidden overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                            <thead class="bg-slate-100 dark:bg-slate-950 text-slate-600 dark:text-slate-400 uppercase text-[10px] border-b border-slate-200 dark:border-slate-800">
                                <tr>
                                    <th class="py-3 px-4">Nama Berkas</th>
                                    <th class="py-3 px-4">Tipe</th>
                                    <th class="py-3 px-4">Ukuran</th>
                                    <th class="py-3 px-4">Tanggal Unggah</th>
                                    <th class="py-3 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:border-slate-800">
                                <?php foreach ($mediaList as $item): ?>
                                    <?php
                                        $filePath = App::mediaUrl($item['file_path'], 'activities');
                                    ?>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="py-3 px-4 flex items-center gap-3">
                                            <span class="material-symbols-outlined text-amber-500 text-base">
                                                <?= $item['file_type'] === 'image' ? 'image' : 'video_file' ?>
                                            </span>
                                            <span class="font-medium text-slate-900 dark:text-slate-200"><?= e($item['original_name']) ?></span>
                                        </td>
                                        <td class="py-3 px-4 uppercase text-[11px] font-bold text-slate-500 dark:text-slate-400"><?= e($item['mime_type']) ?></td>
                                        <td class="py-3 px-4 font-mono"><?= Formatter::formatBytes($item['file_size']) ?></td>
                                        <td class="py-3 px-4"><?= Formatter::tanggalIndo($item['created_at']) ?></td>
                                        <td class="py-3 px-4 text-right space-x-2">
                                            <button type="button" class="preview-trigger-btn px-2.5 py-1 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs"
                                                    data-preview-url="<?= $filePath ?>"
                                                    data-preview-name="<?= e($item['original_name']) ?>"
                                                    data-preview-type="<?= $item['file_type'] ?>">
                                                Pratinjau
                                            </button>
                                            <a href="<?= App::baseUrl('/download/file/' . $item['id']) ?>" class="px-2.5 py-1 rounded bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs">
                                                Unduh
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Lightbox / Preview Modal -->
        <div id="preview-modal" class="fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-md hidden flex items-center justify-center p-4">
            <div class="relative max-w-5xl w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-500">visibility</span>
                        <h3 id="modal-filename" class="font-bold text-sm text-slate-900 dark:text-slate-200 truncate font-montserrat">Pratinjau Berkas</h3>
                    </div>
                    <button onclick="closePreviewModal()" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center">
                        <span class="material-symbols-outlined text-base">close</span>
                    </button>
                </div>
                <!-- Modal Body -->
                <div class="p-4 flex items-center justify-center bg-black min-h-[400px] max-h-[75vh]">
                    <img id="modal-image" src="" alt="Pratinjau" class="max-h-[70vh] max-w-full object-contain rounded-lg hidden">
                    <video id="modal-video" src="" controls class="max-h-[70vh] max-w-full rounded-lg hidden"></video>
                </div>
            </div>
        </div>

        <!-- Community Comments Section -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm dark:shadow-xl">
            <div class="flex items-center gap-3 border-b border-slate-200 dark:border-slate-800 pb-4">
                <span class="material-symbols-outlined text-amber-500 text-2xl">forum</span>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white font-montserrat">Tanggapan &amp; Komentar (<?= count($comments) ?>)</h3>
            </div>

            <!-- Existing Comments -->
            <div class="space-y-4">
                <?php if (empty($comments)): ?>
                    <p class="text-xs text-slate-500 italic py-4">Belum ada komentar untuk kegiatan ini. Jadilah yang pertama memberikan tanggapan!</p>
                <?php else: ?>
                    <?php foreach ($comments as $comm): ?>
                        <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-amber-500 flex items-center justify-center text-[10px] font-bold text-slate-950 uppercase">
                                        <?= substr($comm['nama_lengkap'], 0, 1) ?>
                                    </div>
                                    <span class="text-xs font-bold text-slate-900 dark:text-slate-200"><?= e($comm['nama_lengkap']) ?></span>
                                    <?php if ($comm['role'] === 'admin'): ?>
                                        <span class="text-[9px] font-bold uppercase px-1.5 py-0.5 rounded bg-amber-500 text-slate-950">Pengurus</span>
                                    <?php endif; ?>
                                </div>
                                <span class="text-[10px] text-slate-400 dark:text-slate-500"><?= Formatter::tanggalIndo($comm['created_at'], true) ?></span>
                            </div>
                            <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed pl-9"><?= nl2br(e($comm['content'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Add Comment Form -->
            <?php if (!empty($currentUser)): ?>
                <form action="<?= App::baseUrl('/komentar') ?>" method="POST" class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                    <input type="hidden" name="post_type" value="activity">
                    <input type="hidden" name="post_id" value="<?= $activity['id'] ?>">
                    <input type="hidden" name="redirect_url" value="/kegiatan/<?= $activity['slug'] ?>">

                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Tulis Tanggapan Anda:</label>
                    <textarea name="content" rows="3" required placeholder="Tuliskan apresiasi atau tanggapan Anda..." class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl p-3 text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 transition-colors"></textarea>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-400 text-slate-950 shadow-sm transition-all">
                            <span class="material-symbols-outlined text-sm">send</span>
                            Kirim Tanggapan
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs text-slate-600 dark:text-slate-400">
                    <span>Ingin memberikan tanggapan? Silakan masuk terlebih dahulu.</span>
                    <a href="<?= App::baseUrl('/login') ?>" class="px-4 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold transition-all">
                        Masuk
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Explorer View Toggle & Modal Script -->
<script>
    const gridBtn = document.getElementById('view-grid-btn');
    const listBtn = document.getElementById('view-list-btn');
    const gridContainer = document.getElementById('drive-grid-container');
    const listContainer = document.getElementById('drive-list-container');

    if (gridBtn && listBtn) {
        gridBtn.addEventListener('click', () => {
            gridContainer.classList.remove('hidden');
            listContainer.classList.add('hidden');
            gridBtn.className = 'p-2 rounded-lg bg-amber-500 text-slate-950 font-bold transition-all shadow-sm';
            listBtn.className = 'p-2 rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all';
        });

        listBtn.addEventListener('click', () => {
            listContainer.classList.remove('hidden');
            gridContainer.classList.add('hidden');
            listBtn.className = 'p-2 rounded-lg bg-amber-500 text-slate-950 font-bold transition-all shadow-sm';
            gridBtn.className = 'p-2 rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all';
        });
    }

    const modal = document.getElementById('preview-modal');
    const modalImage = document.getElementById('modal-image');
    const modalVideo = document.getElementById('modal-video');
    const modalFilename = document.getElementById('modal-filename');

    function openPreviewModal(url, name, type) {
        modalFilename.textContent = name;
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
    }

    function closePreviewModal() {
        modal.classList.add('hidden');
        modalImage.src = '';
        modalVideo.src = '';
        modalVideo.pause();
    }

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closePreviewModal();
        }
    });

    document.querySelectorAll('.preview-trigger-btn').forEach(el => {
        el.addEventListener('click', (e) => {
            e.stopPropagation();
            const url = el.dataset.previewUrl;
            const name = el.dataset.previewName;
            const type = el.dataset.previewType;
            openPreviewModal(url, name, type);
        });
    });
</script>
