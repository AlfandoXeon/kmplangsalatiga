<?php
/**
 * Partial: admin/articles/_editor_toolbar.php
 * -------------------------------------------------------------------------
 * Editor Artikel berbasis Markdown dengan Toolbar Pemformatan Instan,
 * Pratinjau Langsung (Live Preview), Pintasan Keyboard (Ctrl+B, Ctrl+I,
 * Auto-List saat Enter), Penghitung Kata & Estimasi Waktu Baca Realtime,
 * serta Penyimpanan Draf Lokal Otomatis (Auto-Save Draft).
 * -------------------------------------------------------------------------
 */
$initialContent = $initialContent ?? '';
$enableAutosave = $enableAutosave ?? false;
?>

<div class="space-y-2">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <label for="article-content-editor" class="block font-mono uppercase text-[10px] font-bold">
            Isi Artikel Lengkap *
        </label>
        <div class="flex items-center gap-2 font-mono text-[10px] text-slate-500 dark:text-slate-400">
            <?php if ($enableAutosave): ?>
                <span id="editor-autosave-status" class="hidden sm:inline">Draf tersimpan otomatis di peramban</span>
                <button type="button" id="editor-clear-draft" onclick="clearArticleDraft()" class="hidden underline hover:text-slate-900 dark:hover:text-slate-100">Reset Draf</button>
            <?php endif; ?>
            <button type="button" onclick="document.getElementById('markdown-guide-box').classList.toggle('hidden')" class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-slate-500 transition-colors">
                Panduan Format
            </button>
        </div>
    </div>

    <!-- Panduan Singkat Markdown (Collapsible) -->
    <div id="markdown-guide-box" class="hidden p-3.5 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-[11px] text-slate-700 dark:text-slate-300 space-y-2">
        <div class="font-bold text-slate-900 dark:text-slate-100">Tips Menulis Cepat (Markdown &amp; Pintasan Keyboard):</div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 font-mono text-[10px]">
            <div><code>**teks tebal**</code> &rarr; <strong>teks tebal</strong> (Ctrl+B)</div>
            <div><code>*teks miring*</code> &rarr; <em>teks miring</em> (Ctrl+I)</div>
            <div><code>## Subjudul</code> &rarr; Subjudul Besar (H2)</div>
            <div><code>### Sub-bagian</code> &rarr; Subjudul Kecil (H3)</div>
            <div><code>- Item daftar</code> &rarr; Daftar Poin (Bullet)</div>
            <div><code>1. Item urut</code> &rarr; Daftar Bernomor</div>
            <div><code>&gt; Kutipan</code> &rarr; Kotak Kutipan Tokoh</div>
            <div><code>---</code> &rarr; Garis Pemisah Bagian</div>
            <div><code>Enter 2x</code> &rarr; Paragraf Baru</div>
        </div>
    </div>

    <!-- Editor Container -->
    <div class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 overflow-hidden">
        <!-- Toolbar Atas -->
        <div class="flex flex-wrap items-center justify-between gap-2 px-3 py-2 bg-slate-100 dark:bg-slate-800/90 border-b border-slate-300 dark:border-slate-700">
            <!-- Tombol-Tombol Format Teks -->
            <div id="editor-formatting-btns" class="flex flex-wrap items-center gap-1">
                <button type="button" onclick="applyEditorFormat('bold')" class="h-8 px-2.5 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold text-xs flex items-center justify-center transition-colors" title="Tebal / Bold (Ctrl+B)">
                    <span class="material-symbols-outlined text-[16px]">format_bold</span>
                </button>
                <button type="button" onclick="applyEditorFormat('italic')" class="h-8 px-2.5 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 italic text-xs flex items-center justify-center transition-colors" title="Miring / Italic (Ctrl+I)">
                    <span class="material-symbols-outlined text-[16px]">format_italic</span>
                </button>

                <span class="w-px h-5 bg-slate-300 dark:bg-slate-700 mx-0.5"></span>

                <button type="button" onclick="applyEditorFormat('h2')" class="h-8 px-2.5 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-mono font-bold text-[11px] flex items-center justify-center transition-colors" title="Subjudul Utama (Heading 2)">
                    H2
                </button>
                <button type="button" onclick="applyEditorFormat('h3')" class="h-8 px-2.5 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-mono font-bold text-[11px] flex items-center justify-center transition-colors" title="Subjudul Bagian (Heading 3)">
                    H3
                </button>
                <button type="button" onclick="applyEditorFormat('paragraph')" class="h-8 px-2.5 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-mono font-bold text-[11px] flex items-center gap-1 transition-colors" title="Sisipkan Paragraf Baru">
                    <span class="material-symbols-outlined text-[15px]">subject</span>
                    <span class="hidden sm:inline">Paragraf</span>
                </button>

                <span class="w-px h-5 bg-slate-300 dark:bg-slate-700 mx-0.5"></span>

                <button type="button" onclick="applyEditorFormat('ul')" class="h-8 px-2.5 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 text-xs flex items-center gap-1 transition-colors" title="Daftar Poin (Bullet List)">
                    <span class="material-symbols-outlined text-[16px]">format_list_bulleted</span>
                    <span class="hidden md:inline text-[11px] font-semibold">List</span>
                </button>
                <button type="button" onclick="applyEditorFormat('ol')" class="h-8 px-2.5 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 text-xs flex items-center gap-1 transition-colors" title="Daftar Bernomor (Numbered List)">
                    <span class="material-symbols-outlined text-[16px]">format_list_numbered</span>
                    <span class="hidden md:inline text-[11px] font-semibold">Nomor</span>
                </button>
                <button type="button" onclick="applyEditorFormat('quote')" class="h-8 px-2.5 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 text-xs flex items-center gap-1 transition-colors" title="Kutipan / Blockquote">
                    <span class="material-symbols-outlined text-[16px]">format_quote</span>
                    <span class="hidden md:inline text-[11px] font-semibold">Kutipan</span>
                </button>
                <button type="button" onclick="applyEditorFormat('hr')" class="h-8 px-2.5 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 text-xs flex items-center justify-center transition-colors" title="Garis Pemisah Bagian">
                    <span class="material-symbols-outlined text-[16px]">horizontal_rule</span>
                </button>
                <button type="button" onclick="applyEditorFormat('link')" class="h-8 px-2.5 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 text-xs flex items-center justify-center transition-colors" title="Sisipkan Tautan / Link">
                    <span class="material-symbols-outlined text-[16px]">link</span>
                </button>
            </div>

            <!-- Sakelar Mode Tulis vs Pratinjau -->
            <div class="flex items-center rounded-lg bg-slate-200/80 dark:bg-slate-950 p-0.5 border border-slate-300 dark:border-slate-700">
                <button type="button" id="tab-btn-write" onclick="switchEditorTab('write')" class="px-3 py-1 rounded-md text-[11px] font-bold bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 transition-all">
                    Tulis
                </button>
                <button type="button" id="tab-btn-preview" onclick="switchEditorTab('preview')" class="px-3 py-1 rounded-md text-[11px] font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 transition-all">
                    Pratinjau Baca
                </button>
            </div>
        </div>

        <!-- Area Tulis (Textarea) -->
        <textarea id="article-content-editor"
                  name="konten"
                  rows="14"
                  required
                  placeholder="Tuliskan isi artikel Anda di sini...&#10;&#10;Gunakan tombol di atas untuk membuat **teks tebal**, *teks miring*, ## Subjudul, daftar poin (- item), atau kutipan (> kutipan). Pisahkan antar paragraf dengan baris kosong (Enter 2x)."
                  class="w-full bg-white dark:bg-slate-950 p-4 font-serif-reading text-sm sm:text-base text-slate-900 dark:text-slate-100 focus:outline-none leading-relaxed resize-y"><?= e($initialContent) ?></textarea>

        <!-- Area Pratinjau Hasil Baca (Hidden secara default) -->
        <div id="article-content-preview" class="hidden w-full min-h-[320px] bg-white dark:bg-slate-950 p-6 sm:p-8 editorial-prose max-w-none text-slate-800 dark:text-slate-200"></div>

        <!-- Status Bar Bawah (Statistik Kata, Paragraf, & Estimasi Waktu Baca) -->
        <div class="px-3.5 py-2 bg-slate-100 dark:bg-slate-800/90 border-t border-slate-300 dark:border-slate-700 flex flex-wrap items-center justify-between gap-2 font-mono text-[10px] text-slate-600 dark:text-slate-400">
            <div class="flex items-center gap-3">
                <span><strong id="stat-word-count" class="text-slate-900 dark:text-slate-100">0</strong> Kata</span>
                <span>&bull;</span>
                <span><strong id="stat-para-count" class="text-slate-900 dark:text-slate-100">0</strong> Paragraf</span>
                <span>&bull;</span>
                <span>Estimasi Baca: <strong id="stat-read-time" class="text-slate-900 dark:text-slate-100">1 mnt</strong></span>
            </div>
            <span class="hidden sm:inline">Pintasan: <strong>Ctrl+B</strong> (Tebal) &bull; <strong>Ctrl+I</strong> (Miring)</span>
        </div>
    </div>
</div>

<script>
(function() {
    const editor = document.getElementById('article-content-editor');
    const preview = document.getElementById('article-content-preview');
    const tabWrite = document.getElementById('tab-btn-write');
    const tabPreview = document.getElementById('tab-btn-preview');
    const formatBtns = document.getElementById('editor-formatting-btns');
    const wordCountEl = document.getElementById('stat-word-count');
    const paraCountEl = document.getElementById('stat-para-count');
    const readTimeEl = document.getElementById('stat-read-time');
    const autosaveEnabled = <?= $enableAutosave ? 'true' : 'false' ?>;
    const DRAFT_KEY = 'kmplang_article_new_draft_v1';

    if (!editor) return;

    // 1. Pulihkan draf lokal jika di halaman Tulis Artikel Baru dan textarea masih kosong
    if (autosaveEnabled && editor.value.trim() === '') {
        const savedDraft = localStorage.getItem(DRAFT_KEY);
        if (savedDraft && savedDraft.trim() !== '') {
            editor.value = savedDraft;
            const clearBtn = document.getElementById('editor-clear-draft');
            if (clearBtn) clearBtn.classList.remove('hidden');
        }
    }

    // Hapus draf lokal saat form berhasil disubmit
    if (autosaveEnabled && editor.form) {
        editor.form.addEventListener('submit', () => {
            localStorage.removeItem(DRAFT_KEY);
        });
    }

    window.clearArticleDraft = function() {
        if (!confirm('Kosongkan draf tulisan saat ini?')) return;
        localStorage.removeItem(DRAFT_KEY);
        editor.value = '';
        const clearBtn = document.getElementById('editor-clear-draft');
        if (clearBtn) clearBtn.classList.add('hidden');
        updateEditorStats();
        editor.focus();
    };

    // 2. Fungsi Pemformatan Teks & Penyisipan Markdown
    window.applyEditorFormat = function(type) {
        editor.focus();
        const start = editor.selectionStart;
        const end = editor.selectionEnd;
        const val = editor.value;
        const selected = val.substring(start, end);
        let replacement = '';
        let cursorOffsetStart = 0;
        let cursorOffsetEnd = 0;

        const prefixNewline = (start > 0 && val[start - 1] !== '\n') ? '\n\n' : (start > 0 && val.substring(start - 2, start) !== '\n\n' ? '\n' : '');

        switch (type) {
            case 'bold': {
                const text = selected || 'teks tebal';
                replacement = '**' + text + '**';
                cursorOffsetStart = 2;
                cursorOffsetEnd = 2 + text.length;
                break;
            }
            case 'italic': {
                const text = selected || 'teks miring';
                replacement = '*' + text + '*';
                cursorOffsetStart = 1;
                cursorOffsetEnd = 1 + text.length;
                break;
            }
            case 'h2': {
                const text = selected || 'Subjudul Bagian';
                replacement = prefixNewline + '## ' + text + '\n\n';
                cursorOffsetStart = prefixNewline.length + 3;
                cursorOffsetEnd = cursorOffsetStart + text.length;
                break;
            }
            case 'h3': {
                const text = selected || 'Sub-bagian Artikel';
                replacement = prefixNewline + '### ' + text + '\n\n';
                cursorOffsetStart = prefixNewline.length + 4;
                cursorOffsetEnd = cursorOffsetStart + text.length;
                break;
            }
            case 'paragraph': {
                const text = selected || 'Tuliskan paragraf baru di sini...';
                replacement = prefixNewline + text + '\n\n';
                cursorOffsetStart = prefixNewline.length;
                cursorOffsetEnd = cursorOffsetStart + text.length;
                break;
            }
            case 'ul': {
                if (selected.includes('\n')) {
                    const lines = selected.split('\n').map(l => '- ' + l.replace(/^[\-\*]\s+/, ''));
                    replacement = prefixNewline + lines.join('\n') + '\n';
                    cursorOffsetStart = prefixNewline.length;
                    cursorOffsetEnd = replacement.length - 1;
                } else {
                    const text = selected || 'Poin daftar pertama';
                    replacement = prefixNewline + '- ' + text + '\n';
                    cursorOffsetStart = prefixNewline.length + 2;
                    cursorOffsetEnd = cursorOffsetStart + text.length;
                }
                break;
            }
            case 'ol': {
                if (selected.includes('\n')) {
                    const lines = selected.split('\n').map((l, i) => (i + 1) + '. ' + l.replace(/^\d+\.\s+/, ''));
                    replacement = prefixNewline + lines.join('\n') + '\n';
                    cursorOffsetStart = prefixNewline.length;
                    cursorOffsetEnd = replacement.length - 1;
                } else {
                    const text = selected || 'Urutan pertama';
                    replacement = prefixNewline + '1. ' + text + '\n';
                    cursorOffsetStart = prefixNewline.length + 3;
                    cursorOffsetEnd = cursorOffsetStart + text.length;
                }
                break;
            }
            case 'quote': {
                const text = selected || 'Tuliskan kutipan falsafah atau pernyataan narasumber...';
                replacement = prefixNewline + '> ' + text.replace(/\n/g, '\n> ') + '\n\n';
                cursorOffsetStart = prefixNewline.length + 2;
                cursorOffsetEnd = cursorOffsetStart + text.length;
                break;
            }
            case 'hr': {
                replacement = prefixNewline + '---\n\n';
                cursorOffsetStart = replacement.length;
                cursorOffsetEnd = replacement.length;
                break;
            }
            case 'link': {
                const url = prompt('Masukkan alamat URL lengkap (contoh: https://uksw.edu):', 'https://');
                if (!url) return;
                const label = selected || 'Teks Tautan';
                replacement = '[' + label + '](' + url.trim() + ')';
                cursorOffsetStart = 1;
                cursorOffsetEnd = 1 + label.length;
                break;
            }
        }

        editor.setRangeText(replacement, start, end, 'select');
        editor.setSelectionRange(start + cursorOffsetStart, start + cursorOffsetEnd);
        editor.dispatchEvent(new Event('input'));
    };

    // 3. Pintasan Keyboard (Ctrl+B, Ctrl+I) & Auto-Continue List saat menekan Enter
    editor.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'b') {
            e.preventDefault();
            window.applyEditorFormat('bold');
            return;
        }
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'i') {
            e.preventDefault();
            window.applyEditorFormat('italic');
            return;
        }

        // Lanjutkan bullet list (- ) atau numbered list (1. ) otomatis saat tekan Enter
        if (e.key === 'Enter' && !e.shiftKey) {
            const pos = editor.selectionStart;
            const before = editor.value.substring(0, pos);
            const currentLine = before.split('\n').pop() || '';

            // Jika baris saat ini adalah list kosong ("- " atau "1. "), hapus prefix list
            if (/^(\-\s+|\*\s+|\d+\.\s+)$/.test(currentLine)) {
                e.preventDefault();
                const lineStart = pos - currentLine.length;
                editor.setRangeText('\n', lineStart, pos, 'end');
                editor.dispatchEvent(new Event('input'));
                return;
            }

            const bulletMatch = currentLine.match(/^([\-\*])\s+.+/);
            if (bulletMatch) {
                e.preventDefault();
                const insert = '\n' + bulletMatch[1] + ' ';
                editor.setRangeText(insert, pos, editor.selectionEnd, 'end');
                editor.dispatchEvent(new Event('input'));
                return;
            }

            const numMatch = currentLine.match(/^(\d+)\.\s+.+/);
            if (numMatch) {
                e.preventDefault();
                const nextNum = parseInt(numMatch[1], 10) + 1;
                const insert = '\n' + nextNum + '. ';
                editor.setRangeText(insert, pos, editor.selectionEnd, 'end');
                editor.dispatchEvent(new Event('input'));
            }
        }
    });

    // 4. Parser Markdown ke HTML di sisi Klien untuk Pratinjau Hasil Baca (Live Preview)
    function escapeHtml(str) {
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function parseInlineMd(str) {
        let out = escapeHtml(str);
        out = out.replace(/\[([^\]]+)\]\((https?:\/\/[^\s\)]+)\)/gi, '<a href="$2" target="_blank" rel="noopener noreferrer">$1</a>');
        out = out.replace(/\*\*\*(.+?)\*\*\*/g, '<strong><em>$1</em></strong>');
        out = out.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
        out = out.replace(/__(.+?)__/g, '<strong>$1</strong>');
        out = out.replace(/\*(?!\s)(.+?)(?<!\s)\*/g, '<em>$1</em>');
        out = out.replace(/`([^`]+)`/g, '<code>$1</code>');
        return out;
    }

    function renderMarkdownPreview(raw) {
        if (!raw || raw.trim() === '') {
            return '<p class="text-slate-400 italic">Belum ada isi tulisan untuk ditampilkan pada pratinjau.</p>';
        }

        const lines = raw.replace(/\r\n/g, '\n').split('\n');
        const blocks = [];
        let para = [];
        let listType = null;
        let listItems = [];
        let quoteLines = [];

        const flushPara = () => {
            if (para.length) {
                blocks.push('<p>' + para.map(parseInlineMd).join('<br>') + '</p>');
                para = [];
            }
        };
        const flushList = () => {
            if (listType && listItems.length) {
                blocks.push('<' + listType + '>' + listItems.map(i => '<li>' + parseInlineMd(i) + '</li>').join('') + '</' + listType + '>');
            }
            listType = null;
            listItems = [];
        };
        const flushQuote = () => {
            if (quoteLines.length) {
                blocks.push('<blockquote>' + quoteLines.map(parseInlineMd).join('<br>') + '</blockquote>');
                quoteLines = [];
            }
        };

        for (const rawLine of lines) {
            const line = rawLine.trim();
            if (line === '') {
                flushPara(); flushList(); flushQuote();
                continue;
            }
            if (/^(\-{3,}|\*{3,})$/.test(line)) {
                flushPara(); flushList(); flushQuote();
                blocks.push('<hr class="poster-divider">');
                continue;
            }
            const h3 = line.match(/^###\s+(.+)$/);
            if (h3) {
                flushPara(); flushList(); flushQuote();
                blocks.push('<h3>' + parseInlineMd(h3[1]) + '</h3>');
                continue;
            }
            const h2 = line.match(/^#{1,2}\s+(.+)$/);
            if (h2) {
                flushPara(); flushList(); flushQuote();
                blocks.push('<h2>' + parseInlineMd(h2[1]) + '</h2>');
                continue;
            }
            const qt = line.match(/^>\s*(.*)$/);
            if (qt) {
                flushPara(); flushList();
                quoteLines.push(qt[1]);
                continue;
            } else {
                flushQuote();
            }
            const ul = line.match(/^[\-\*]\s+(.+)$/);
            if (ul) {
                flushPara();
                if (listType && listType !== 'ul') flushList();
                listType = 'ul';
                listItems.push(ul[1]);
                continue;
            }
            const ol = line.match(/^\d+\.\s+(.+)$/);
            if (ol) {
                flushPara();
                if (listType && listType !== 'ol') flushList();
                listType = 'ol';
                listItems.push(ol[1]);
                continue;
            }
            if (listType) flushList();
            para.push(line);
        }

        flushPara(); flushList(); flushQuote();
        return blocks.join('\n');
    }

    // 5. Sakelar Tab Tulis vs Pratinjau
    window.switchEditorTab = function(mode) {
        if (mode === 'preview') {
            preview.innerHTML = renderMarkdownPreview(editor.value);
            editor.classList.add('hidden');
            preview.classList.remove('hidden');
            if (formatBtns) formatBtns.classList.add('opacity-40', 'pointer-events-none');
            tabPreview.className = 'px-3 py-1 rounded-md text-[11px] font-bold bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 transition-all';
            tabWrite.className = 'px-3 py-1 rounded-md text-[11px] font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 transition-all';
        } else {
            preview.classList.add('hidden');
            editor.classList.remove('hidden');
            if (formatBtns) formatBtns.classList.remove('opacity-40', 'pointer-events-none');
            tabWrite.className = 'px-3 py-1 rounded-md text-[11px] font-bold bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-950 transition-all';
            tabPreview.className = 'px-3 py-1 rounded-md text-[11px] font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 transition-all';
            editor.focus();
        }
    };

    // 6. Penghitung Statistik Kata, Paragraf, Waktu Baca, & Auto-Save
    function updateEditorStats() {
        const text = editor.value.trim();
        const words = text === '' ? 0 : text.replace(/[#*_>`~\-]/g, ' ').trim().split(/\s+/).filter(Boolean).length;
        const paras = text === '' ? 0 : text.split(/\n\s*\n/).filter(p => p.trim() !== '').length;
        const readMin = Math.max(1, Math.ceil(words / 200));

        if (wordCountEl) wordCountEl.textContent = words;
        if (paraCountEl) paraCountEl.textContent = paras;
        if (readTimeEl) readTimeEl.textContent = readMin + ' mnt';

        if (autosaveEnabled) {
            if (text !== '') {
                localStorage.setItem(DRAFT_KEY, editor.value);
                const clearBtn = document.getElementById('editor-clear-draft');
                if (clearBtn) clearBtn.classList.remove('hidden');
            } else {
                localStorage.removeItem(DRAFT_KEY);
            }
        }
    }

    editor.addEventListener('input', updateEditorStats);
    updateEditorStats();
})();
</script>
