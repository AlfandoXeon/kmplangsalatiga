<?php

namespace App\Helpers;

/**
 * ============================================================================
 * Class Sanitizer - Sterilisasi Input, Anti-XSS, & Pembersih Karakter Berbahaya
 * ============================================================================
 * Melindungi backend dari:
 * 1. Cross-Site Scripting (XSS) melalui encoding entitas HTML (`escape()`).
 * 2. Null-Byte Injection (`\0`) & Karakter Kontrol ASCII tersembunyi (`cleanInput()`).
 * 3. Injeksi Tag Script/HTML berbahaya pada kolom teks biasa (`cleanPlainText()`).
 * 4. Path Traversal pada nama berkas (`cleanFilename()`).
 * ============================================================================
 */
class Sanitizer
{
    public static function escape(?string $value): string
    {
        if ($value === null) {
            return '';
        }
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        if (function_exists('iconv')) {
            $converted = @iconv('utf-8', 'us-ascii//TRANSLIT', (string) $text);
            if ($converted !== false) {
                $text = $converted;
            }
        }
        $text = preg_replace('~[^-\w]+~', '', (string) $text);
        $text = trim((string) $text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower((string) $text);

        return empty($text) ? 'item-' . time() : $text;
    }

    /**
     * Mensterilkan input (string atau array rekursif) dari Null-Byte (`\0`),
     * karakter kontrol berbahaya (selain tab dan baris baru), serta spasi berlebih.
     */
    public static function cleanInput(mixed $data): mixed
    {
        if (is_array($data)) {
            $cleaned = [];
            foreach ($data as $key => $val) {
                $cleanKey = is_string($key) ? preg_replace('/[\x00-\x1F\x7F]/', '', $key) : $key;
                $cleaned[$cleanKey] = self::cleanInput($val);
            }
            return $cleaned;
        }

        if (is_string($data)) {
            // Hapus null byte & karakter kontrol non-cetak (kecuali \t, \n, \r)
            $clean = str_replace("\0", '', $data);
            $clean = (string) preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $clean);
            return trim($clean);
        }

        return $data;
    }

    /**
     * Membersihkan input teks murni dari seluruh tag HTML/Script dan atribut event berbahaya.
     */
    public static function cleanPlainText(?string $text): string
    {
        if ($text === null || $text === '') {
            return '';
        }
        $clean = (string) self::cleanInput($text);
        $clean = preg_replace('#<(script|style|iframe|object|embed)[^>]*>.*?</\1>#is', '', $clean) ?? $clean;
        return trim(strip_tags($clean));
    }

    /**
     * Mensterilkan nama berkas dari karakter Path Traversal (`../`, `/`, `\`, null byte).
     */
    public static function cleanFilename(string $filename): string
    {
        $filename = str_replace("\0", '', $filename);
        $base = basename(str_replace('\\', '/', $filename));
        $base = (string) preg_replace('/[^a-zA-Z0-9._\-]/', '_', $base);
        return ltrim($base, '.');
    }
}

