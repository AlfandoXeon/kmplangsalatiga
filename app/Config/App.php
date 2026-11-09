<?php

namespace App\Config;

/**
 * ============================================================================
 * Class App - Konfigurasi Lingkungan & Resolusi URL Aplikasi
 * ============================================================================
 * Bertanggung jawab untuk:
 * 1. Memuat variabel konfigurasi dari berkas `.env` ke dalam memori & environment.
 * 2. Menghasilkan Base URL secara adaptif (mendukung subfolder Apache XAMPP
 *    seperti `/Kmplang` maupun PHP Built-in Server `localhost:8000`).
 * 3. Me-resolve lokasi fisik dan URL web untuk berkas unggahan (storage/uploads)
 *    serta aset statis (public/assets/images) dengan fallback otomatis.
 * ============================================================================
 */
class App
{
    /** @var array<string, string> Penyimpanan cache variabel environment */
    private static array $env = [];

    /**
     * Memuat dan mem-parsing berkas `.env` baris demi baris.
     * Mengabaikan baris kosong dan komentar (`#`), serta membersihkan tanda kutip.
     */
    public static function loadEnv(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            // Pisahkan pasangan KEY=VALUE pada karakter '=' pertama
            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Bersihkan tanda kutip pembungkus ("..." atau '...') jika ada
                if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                    (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                    $value = substr($value, 1, -1);
                }

                // Bersihkan komentar inline yang diawali spasi + '#'
                if (str_contains($value, ' #')) {
                    $value = trim(explode(' #', $value, 2)[0]);
                }

                self::$env[$key] = $value;
                putenv("$key=$value");
                $_ENV[$key] = $value;
            }
        }
    }

    /**
     * Mengambil nilai konfigurasi berdasarkan kunci, atau mengembalikan nilai default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$env[$key] ?? getenv($key) ?: $default;
    }

    /**
     * Mendeteksi apakah koneksi berjalan di atas protokol HTTPS (termasuk di balik
     * Reverse Proxy, Cloudflare Flexible/Full SSL, atau Load Balancer Cloud Hosting seperti InfinityFree).
     */
    public static function isHttps(): bool
    {
        if (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off') {
            return true;
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
            return true;
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower((string) $_SERVER['HTTP_X_FORWARDED_SSL']) === 'on') {
            return true;
        }

        if (!empty($_SERVER['HTTP_CF_VISITOR']) && str_contains((string) $_SERVER['HTTP_CF_VISITOR'], '"scheme":"https"')) {
            return true;
        }

        if (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443) {
            return true;
        }

        return false;
    }

    /**
     * Menghasilkan URL absolut menuju path tertentu di dalam aplikasi.
     * Logika adaptif:
     * - Mendukung Cloud Hosting (InfinityFree / Cloudflare SSL) & XAMPP Subfolder (`/Kmplang`).
     * - Mensterilkan `HTTP_HOST` dari serangan Host Header Injection.
     */
    public static function baseUrl(string $path = ''): string
    {
        $configured = self::get('APP_URL');
        if (!empty($configured) && $configured !== 'http://localhost:8000') {
            $base = rtrim((string) $configured, '/');
        } else {
            $scheme = self::isHttps() ? 'https' : 'http';
            $rawHost = (string) ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost:8000');
            // Sterilkan karakter host untuk mencegah Host Header Injection
            $host = preg_replace('/[^a-zA-Z0-9.\-:]/', '', $rawHost) ?: 'localhost';

            $scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
            // Jika server mengarahkan SCRIPT_NAME ke subfolder /public namun URL asli tidak memuat /public
            $requestUri = (string) ($_SERVER['REQUEST_URI'] ?? '');
            if (($scriptDir === 'public' || str_ends_with($scriptDir, '/public')) && !str_contains($requestUri, '/public/')) {
                $scriptDir = trim(substr($scriptDir, 0, -6), '/');
            }

            $base = $scriptDir !== '' ? "$scheme://$host/$scriptDir" : "$scheme://$host";
        }

        $path = ltrim($path, '/');
        return $path === '' ? $base : "$base/$path";
    }

    /**
     * Me-resolve URL web untuk berkas media (sampul kegiatan, foto album, sampul artikel).
     * Urutan pemeriksaan fisik:
     * 1. `storage/uploads/{category}/{filename}`
     * 2. `storage/uploads/{filename}`
     * 3. `public/assets/images/{filename}`
     * 4. Fallback ke `public/assets/images/{fallback}` (default: `thumb.jpg`).
     */
    public static function mediaUrl(?string $path, string $category = 'activities', string $fallback = 'thumb.jpg'): string
    {
        if (empty($path)) {
            return self::baseUrl('/assets/images/' . $fallback);
        }

        // Biarkan utuh jika berupa URL eksternal atau Data URI
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
            return $path;
        }

        $filename = ltrim(str_replace('\\', '/', $path), '/');

        // Jika path sudah menyertakan prefix storage/ atau assets/
        if (str_starts_with($filename, 'storage/') || str_starts_with($filename, 'assets/')) {
            return self::baseUrl($filename);
        }

        $root = dirname(__DIR__, 2);

        // 1. Cek di storage/uploads/{category}/{filename}
        if (!empty($category) && is_file($root . '/storage/uploads/' . $category . '/' . $filename)) {
            return self::baseUrl('/storage/uploads/' . $category . '/' . $filename);
        }

        // 2. Cek di storage/uploads/{filename}
        if (is_file($root . '/storage/uploads/' . $filename)) {
            return self::baseUrl('/storage/uploads/' . $filename);
        }

        // 3. Cek di public/assets/images/{filename}
        if (is_file($root . '/public/assets/images/' . $filename)) {
            return self::baseUrl('/assets/images/' . $filename);
        }

        // 4. Jika berkas tidak ditemukan di mana pun, gunakan fallback gambar yang pasti ada
        if (is_file($root . '/public/assets/images/' . $fallback)) {
            return self::baseUrl('/assets/images/' . $fallback);
        }

        return self::baseUrl('/assets/images/siger.png');
    }
}
