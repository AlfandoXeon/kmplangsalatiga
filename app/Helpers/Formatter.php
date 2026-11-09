<?php

namespace App\Helpers;

/**
 * Class Formatter
 * -------------------------------------------------------------------------
 * Utilitas pemformatan tampilan data (tanggal Bahasa Indonesia, ukuran
 * berkas byte, nominal Rupiah, dan pemotongan kutipan teks/excerpt).
 * -------------------------------------------------------------------------
 */
class Formatter
{
    private static array $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    /**
     * Mengubah string tanggal SQL menjadi format tanggal Bahasa Indonesia.
     */
    public static function tanggalIndo(?string $dateStr, bool $withTime = false): string
    {
        if (empty($dateStr)) {
            return '-';
        }

        $timestamp = strtotime($dateStr);
        if (!$timestamp) {
            return $dateStr;
        }

        $hari = date('d', $timestamp);
        $bulanIndex = (int) date('m', $timestamp);
        $tahun = date('Y', $timestamp);
        $bulanNama = self::$bulan[$bulanIndex] ?? date('F', $timestamp);

        $res = "$hari $bulanNama $tahun";
        if ($withTime) {
            $res .= ' pukul ' . date('H:i', $timestamp) . ' WIB';
        }

        return $res;
    }

    /**
     * Mengonversi ukuran byte menjadi satuan yang mudah dibaca (KB, MB, GB).
     */
    public static function formatBytes(int|float $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Memformat angka atau string anggaran menjadi format Rupiah.
     */
    public static function rupiah(string|int|float|null $amount): string
    {
        if ($amount === null || $amount === '') {
            return 'Rp 0';
        }

        if (is_string($amount) && str_starts_with(strtoupper(trim($amount)), 'RP')) {
            return $amount;
        }

        $num = (float) preg_replace('/[^\d.]/', '', (string) $amount);
        return 'Rp ' . number_format($num, 0, ',', '.');
    }

    /**
     * Memotong teks HTML/Markdown/plain menjadi ringkasan pendek yang rapi tanpa memutus kata.
     */
    public static function potongTeks(?string $text, int $limit = 140, string $suffix = '...'): string
    {
        if (empty($text)) {
            return '';
        }

        // Bersihkan tag HTML serta simbol pemformatan Markdown dasar
        $clean = strip_tags($text);
        $clean = preg_replace('/^[#>\-*+]+\s+/m', '', $clean) ?? $clean;
        $clean = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '$1', $clean) ?? $clean;
        $clean = str_replace(['**', '__', '*', '_', '~~', '`'], '', $clean);
        $clean = trim((string) preg_replace('/\s+/', ' ', $clean));

        if (mb_strlen($clean, 'UTF-8') <= $limit) {
            return $clean;
        }

        $cut = mb_substr($clean, 0, $limit, 'UTF-8');
        $lastSpace = mb_strrpos($cut, ' ', 0, 'UTF-8');
        if ($lastSpace !== false && $lastSpace > ($limit * 0.6)) {
            $cut = mb_substr($cut, 0, $lastSpace, 'UTF-8');
        }

        return rtrim($cut, ".,;:- ") . $suffix;
    }

    /**
     * Me-render konten artikel yang ditulis dengan format Markdown maupun HTML
     * menjadi elemen tipografi editorial yang aman dari XSS.
     *
     * Mendukung:
     * - **Tebal** (`**teks**`), *Miring* (`*teks*` / `_teks_`)
     * - Subjudul `## H2` dan `### H3`
     * - Daftar Poin (`- item` / `* item`) & Daftar Bernomor (`1. item`)
     * - Kutipan / Blockquote (`> kutipan`)
     * - Garis Pemisah (`---`) & Tautan (`[teks](https://...)`)
     * - Paragraf otomatis dari baris kosong (`\n\n`)
     */
    public static function renderArticleContent(?string $content): string
    {
        if ($content === null || trim($content) === '') {
            return '';
        }

        // 1. Sterilisasi tag & atribut berbahaya (XSS Protection)
        $clean = str_replace(["\r\n", "\r", "\0"], ["\n", "\n", ''], $content);
        $clean = preg_replace('#<(script|style|iframe|object|embed|form|input|button)[^>]*>.*?</\1>#is', '', $clean) ?? $clean;
        $clean = preg_replace('#<(script|style|iframe|object|embed|form|input|button)[^>]*?/?>#is', '', $clean) ?? $clean;
        $clean = preg_replace('/\s+on[a-z]+\s*=\s*(["\']).*?\1/i', '', $clean) ?? $clean;
        $clean = preg_replace('/\s+on[a-z]+\s*=[^\s>]+/i', '', $clean) ?? $clean;
        $clean = preg_replace('/javascript\s*:/i', '', $clean) ?? $clean;

        // Jika konten sudah murni berbentuk blok HTML penuh (misal artikel bawaan database),
        // tetap proses inline Markdown ringan (**bold**, *italic*) tanpa merusak struktur HTML.
        $hasHtmlBlocks = (bool) preg_match('/<(p|h2|h3|ul|ol|blockquote|div)\b/i', $clean);
        if ($hasHtmlBlocks && !preg_match('/^(#{2,3}|\-|\*|\d+\.|>)\s+/m', $clean)) {
            return self::parseInlineMarkdown($clean);
        }

        $lines = explode("\n", $clean);
        $htmlBlocks = [];
        $paragraphBuffer = [];
        $listType = null; // 'ul' | 'ol'
        $listItems = [];
        $quoteLines = [];

        $flushParagraph = function () use (&$paragraphBuffer, &$htmlBlocks): void {
            if (!empty($paragraphBuffer)) {
                $joined = implode("<br>\n", $paragraphBuffer);
                // Jangan bungkus ulang jika baris sudah berupa tag blok HTML
                if (preg_match('/^\s*<(p|h[1-6]|ul|ol|li|blockquote|hr|div)\b/i', $joined)) {
                    $htmlBlocks[] = self::parseInlineMarkdown($joined);
                } else {
                    $htmlBlocks[] = '<p>' . self::parseInlineMarkdown($joined) . '</p>';
                }
                $paragraphBuffer = [];
            }
        };

        $flushList = function () use (&$listType, &$listItems, &$htmlBlocks): void {
            if ($listType !== null && !empty($listItems)) {
                $itemsHtml = '';
                foreach ($listItems as $item) {
                    $itemsHtml .= '<li>' . self::parseInlineMarkdown($item) . '</li>';
                }
                $htmlBlocks[] = "<{$listType}>{$itemsHtml}</{$listType}>";
            }
            $listType = null;
            $listItems = [];
        };

        $flushQuote = function () use (&$quoteLines, &$htmlBlocks): void {
            if (!empty($quoteLines)) {
                $joined = implode("<br>\n", array_map([self::class, 'parseInlineMarkdown'], $quoteLines));
                $htmlBlocks[] = '<blockquote>' . $joined . '</blockquote>';
                $quoteLines = [];
            }
        };

        foreach ($lines as $rawLine) {
            $line = trim($rawLine);

            // Baris kosong memisahkan blok/paragraf
            if ($line === '') {
                $flushParagraph();
                $flushList();
                $flushQuote();
                continue;
            }

            // Garis pemisah horizontal (--- atau ***)
            if (preg_match('/^(\-{3,}|\*{3,})$/', $line)) {
                $flushParagraph();
                $flushList();
                $flushQuote();
                $htmlBlocks[] = '<hr class="poster-divider">';
                continue;
            }

            // Subjudul H3 (### Judul)
            if (preg_match('/^###\s+(.+)$/', $line, $m)) {
                $flushParagraph();
                $flushList();
                $flushQuote();
                $htmlBlocks[] = '<h3>' . self::parseInlineMarkdown($m[1]) . '</h3>';
                continue;
            }

            // Subjudul H2 (## Judul atau # Judul)
            if (preg_match('/^#{1,2}\s+(.+)$/', $line, $m)) {
                $flushParagraph();
                $flushList();
                $flushQuote();
                $htmlBlocks[] = '<h2>' . self::parseInlineMarkdown($m[1]) . '</h2>';
                continue;
            }

            // Blockquote (> Kutipan)
            if (preg_match('/^>\s*(.*)$/', $line, $m)) {
                $flushParagraph();
                $flushList();
                $quoteLines[] = $m[1];
                continue;
            } else {
                $flushQuote();
            }

            // Unordered List (- item atau * item)
            if (preg_match('/^[\-\*]\s+(.+)$/', $line, $m)) {
                $flushParagraph();
                if ($listType !== null && $listType !== 'ul') {
                    $flushList();
                }
                $listType = 'ul';
                $listItems[] = $m[1];
                continue;
            }

            // Ordered List (1. item)
            if (preg_match('/^\d+\.\s+(.+)$/', $line, $m)) {
                $flushParagraph();
                if ($listType !== null && $listType !== 'ol') {
                    $flushList();
                }
                $listType = 'ol';
                $listItems[] = $m[1];
                continue;
            }

            // Jika sebelumnya sedang di dalam list namun baris ini teks biasa, tutup list
            if ($listType !== null) {
                $flushList();
            }

            $paragraphBuffer[] = $line;
        }

        $flushParagraph();
        $flushList();
        $flushQuote();

        return implode("\n", $htmlBlocks);
    }

    /**
     * Memproses sintaks Markdown inline (**bold**, *italic*, `code`, dan [tautan](url)).
     */
    private static function parseInlineMarkdown(string $text): string
    {
        // Tautan [label](https://...)
        $text = preg_replace_callback(
            '/\[([^\]]+)\]\((https?:\/\/[^\s\)]+)\)/i',
            function (array $m): string {
                $label = htmlspecialchars($m[1], ENT_QUOTES, 'UTF-8');
                $url = htmlspecialchars($m[2], ENT_QUOTES, 'UTF-8');
                return '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . $label . '</a>';
            },
            $text
        ) ?? $text;

        // Bold + Italic (***teks***)
        $text = preg_replace('/\*\*\*(.+?)\*\*\*/s', '<strong><em>$1</em></strong>', $text) ?? $text;

        // Bold (**teks** atau __teks__)
        $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text) ?? $text;
        $text = preg_replace('/__(.+?)__/s', '<strong>$1</strong>', $text) ?? $text;

        // Italic (*teks* atau _teks_)
        $text = preg_replace('/(?<!\*)\*(?!\s)(.+?)(?<!\s)\*(?!\*)/s', '<em>$1</em>', $text) ?? $text;
        $text = preg_replace('/(?<!\w)_(.+?)_(?!\w)/s', '<em>$1</em>', $text) ?? $text;

        // Inline code (`teks`)
        $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text) ?? $text;

        return $text;
    }
}
