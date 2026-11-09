<?php

namespace App\Config;

use App\Core\View;
use Throwable;

/**
 * ============================================================================
 * Class Security - Keamanan Sesi, Jaringan, Anti-CORS, CSRF & Anti-500 Guard
 * ============================================================================
 * Mengelola:
 * 1. Sliding Session 1 Bulan (2.592.000 detik):
 *    - Otomatis logout HANYA jika pengguna tidak aktif/tidak login selama 1 bulan.
 *    - Jika pengguna aktif/online, masa berlaku sesi & cookie diperpanjang otomatis
 *      (Sliding Expiration) dan disimpan di `storage/sessions` agar aman di Cloud Hosting.
 * 2. Network Traffic & Anti-CORS Tools:
 *    - Penanganan preflight `OPTIONS` dan header Cross-Origin agar aset/rute tidak terblokir.
 *    - Pembatas lalu lintas request (Sliding-Window Rate Limiter) pencegah beban berlebih.
 *    - Pemeriksa ukuran payload `POST` (`post_max_size` overflow guard) & sterilisasi URI.
 *    - Global Error & Exception Boundary agar galat tak terduga tidak memicu layar putih 500.
 * 3. Token CSRF kriptografis 256-bit (`hash_equals`) & Brute-Force Login Lockout.
 * ============================================================================
 */
class Security
{
    /** Maksimal percobaan login gagal sebelum akun dikunci sementara */
    private const MAX_LOGIN_ATTEMPTS = 5;

    /** Durasi penguncian sementara dalam detik (3 menit) */
    private const LOCKOUT_SECONDS = 180;

    /** Durasi inaktivitas standar sebelum auto-logout: 30 Hari / 1 Bulan (dalam detik) */
    public const ONE_MONTH_SECONDS = 2592000;

    /**
     * Menginisialisasi sesi PHP dengan durasi inaktivitas 1 bulan (30 hari)
     * dan perpanjangan otomatis (Sliding Session) selama pengguna aktif/online.
     */
    public static function initSession(): void
    {
        $lifetime = (int) App::get('SESSION_LIFETIME', self::ONE_MONTH_SECONDS);
        if ($lifetime <= 0) {
            $lifetime = self::ONE_MONTH_SECONDS;
        }

        $secure = App::isHttps();

        if (session_status() === PHP_SESSION_NONE) {
            // Gunakan direktori sesi khusus di storage/sessions agar di Cloud Hosting (InfinityFree)
            // sesi pengguna yang aktif tidak terhapus oleh pembersih /tmp server setiap 24 menit.
            $sessionDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'sessions';
            if (!is_dir($sessionDir)) {
                @mkdir($sessionDir, 0775, true);
            }
            if (is_dir($sessionDir) && is_writable($sessionDir)) {
                $htaccessFile = $sessionDir . DIRECTORY_SEPARATOR . '.htaccess';
                if (!file_exists($htaccessFile)) {
                    @file_put_contents($htaccessFile, "Options -Indexes\n<IfModule mod_authz_core.c>\n    Require all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\n    Order Deny,Allow\n    Deny from all\n</IfModule>\n");
                }
                @session_save_path($sessionDir);
            }

            @ini_set('session.use_strict_mode', '1');
            @ini_set('session.use_only_cookies', '1');
            @ini_set('session.gc_maxlifetime', (string) $lifetime);
            @ini_set('session.cookie_lifetime', (string) $lifetime);

            session_set_cookie_params([
                'lifetime' => $lifetime,
                'path'     => '/',
                'domain'   => '',
                'secure'   => $secure,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            session_name('KMPLANG_SESSID');
            session_start();
        }

        $now = time();

        // 1. Cek apakah pengguna sudah tidak aktif / tidak login selama lebih dari 1 bulan ($lifetime)
        if (isset($_SESSION['_last_activity']) && ($now - (int) $_SESSION['_last_activity']) > $lifetime) {
            $wasLoggedIn = !empty($_SESSION['user']);
            $_SESSION = [];
            @session_regenerate_id(true);
            $_SESSION['_created_time'] = $now;
            $_SESSION['_last_activity'] = $now;
            $_SESSION['_cookie_refreshed_at'] = $now;

            if ($wasLoggedIn) {
                $_SESSION['flash_error'] = 'Sesi Anda telah berakhir otomatis karena tidak aktif selama 1 bulan. Silakan masuk kembali.';
            }
            return;
        }

        // 2. Selama pengguna aktif/online, perbarui waktu aktivitas terakhir (Sliding Expiration)
        $_SESSION['_last_activity'] = $now;

        // 3. Rotasi ID sesi setiap 30 menit saat online untuk mencegah Session Fixation
        if (!isset($_SESSION['_created_time'])) {
            $_SESSION['_created_time'] = $now;
        } elseif ($now - (int) $_SESSION['_created_time'] > 1800) {
            @session_regenerate_id(true);
            $_SESSION['_created_time'] = $now;
            self::refreshSessionCookie($lifetime, $secure);
            $_SESSION['_cookie_refreshed_at'] = $now;
        }

        // 4. Perpanjang masa kedaluwarsa cookie di peramban secara berkala (tiap 5 menit saat online)
        //    agar pengguna yang terus online tidak pernah ter-logout.
        if (!isset($_SESSION['_cookie_refreshed_at']) || ($now - (int) $_SESSION['_cookie_refreshed_at']) > 300) {
            self::refreshSessionCookie($lifetime, $secure);
            $_SESSION['_cookie_refreshed_at'] = $now;
        }
    }

    /**
     * Memperbarui waktu kedaluwarsa cookie sesi di sisi peramban (Sliding Cookie).
     */
    public static function refreshSessionCookie(int $lifetime = self::ONE_MONTH_SECONDS, ?bool $secure = null): void
    {
        if (headers_sent() || session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }

        $secure = $secure ?? App::isHttps();
        setcookie(session_name(), session_id(), [
            'expires'  => time() + $lifetime,
            'path'     => '/',
            'domain'   => '',
            'secure'   => $secure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }

    /**
     * Mengatur HTTP Response Headers keamanan & Cross-Origin agar elemen/aset
     * tidak terblokir di Cloud Hosting (InfinityFree / Cloudflare) sekaligus
     * terlindung dari MIME-sniffing, Clickjacking, dan XSS.
     */
    public static function setSecurityHeaders(): void
    {
        if (headers_sent()) {
            return;
        }
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Cross-Origin-Resource-Policy: cross-origin');
        header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    }

    /**
     * Menangani header CORS & request preflight `OPTIONS` agar tidak ada request
     * aset, font, audio, atau fetch internal yang terblokir oleh kebijakan Cross-Origin.
     */
    public static function handleCorsAndPreflight(): void
    {
        if (headers_sent()) {
            return;
        }

        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        $baseHost = parse_url(App::baseUrl(), PHP_URL_HOST);
        $originHost = $origin !== '' ? parse_url($origin, PHP_URL_HOST) : '';

        if ($origin !== '' && $originHost === $baseHost) {
            header("Access-Control-Allow-Origin: $origin");
            header('Access-Control-Allow-Credentials: true');
            header('Vary: Origin');
        } else {
            header('Access-Control-Allow-Origin: *');
        }

        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, HEAD');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, X-CSRF-Token, Authorization');
        header('Access-Control-Max-Age: 86400');

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }

    /**
     * Mendapatkan alamat IP asli pengunjung (mendukung Cloudflare & Reverse Proxy InfinityFree).
     */
    public static function getClientIp(): string
    {
        $candidates = [
            $_SERVER['HTTP_CF_CONNECTING_IP'] ?? null,
            $_SERVER['HTTP_X_REAL_IP'] ?? null,
            !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? trim(explode(',', (string) $_SERVER['HTTP_X_FORWARDED_FOR'])[0]) : null,
            $_SERVER['REMOTE_ADDR'] ?? null,
        ];

        foreach ($candidates as $ip) {
            if (is_string($ip) && $ip !== '' && filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        return '127.0.0.1';
    }

    /**
     * Memvalidasi keutuhan request jaringan (URI, Query String, dan ukuran payload POST)
     * untuk mencegah HTTP 500 akibat serangan Path Traversal, Null-Byte, atau POST Overflow.
     */
    public static function validateRequestPayload(): void
    {
        $rawUri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
        $queryString = (string) ($_SERVER['QUERY_STRING'] ?? '');

        // 1. Blokir Null-Byte Injection & Path Traversal pada URL
        if (
            str_contains($rawUri, "\0") ||
            str_contains(strtolower($rawUri), '%00') ||
            str_contains($rawUri, '../') ||
            str_contains($rawUri, '..\\') ||
            str_contains($queryString, "\0") ||
            str_contains(strtolower($queryString), '%00')
        ) {
            http_response_code(400);
            die('Permintaan ditolak karena memuat karakter jalur yang tidak valid.');
        }

        // 2. Deteksi POST Payload Overflow (saat ukuran upload melampaui post_max_size PHP)
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $contentLength = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);
            $postMaxBytes = self::parseIniBytes((string) ini_get('post_max_size'));

            if ($postMaxBytes > 0 && $contentLength > $postMaxBytes) {
                $maxMb = round($postMaxBytes / 1048576, 1);
                $_SESSION['flash_error'] = "Ukuran data atau berkas yang dikirim melebihi batas maksimal server ({$maxMb} MB). Silakan perkecil ukuran berkas.";
                $referer = $_SERVER['HTTP_REFERER'] ?? App::baseUrl('/');
                header('Location: ' . $referer);
                exit;
            }
        }
    }

    /**
     * Mengatur lalu lintas request (Sliding-Window Rate Limiter) per klien
     * untuk mencegah banjir request (flood/DDoS ringan) yang dapat menghabiskan
     * koneksi MySQL di Cloud Hosting dan memicu 500 Internal Server Error.
     */
    public static function checkRateLimit(int $maxGetPerMin = 180, int $maxPostPerMin = 40): void
    {
        if (php_sapi_name() === 'cli') {
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $isPost = ($method === 'POST');
        $bucketKey = $isPost ? '_rl_post' : '_rl_get';
        $limit = $isPost ? $maxPostPerMin : $maxGetPerMin;
        $now = time();
        $window = 60;

        if (!isset($_SESSION[$bucketKey]) || !is_array($_SESSION[$bucketKey])) {
            $_SESSION[$bucketKey] = [
                'count'      => 0,
                'reset_time' => $now + $window
            ];
        }

        if ($now >= (int) ($_SESSION[$bucketKey]['reset_time'] ?? 0)) {
            $_SESSION[$bucketKey] = [
                'count'      => 0,
                'reset_time' => $now + $window
            ];
        }

        $_SESSION[$bucketKey]['count']++;
        $currentCount = (int) $_SESSION[$bucketKey]['count'];
        $remaining = max(0, $limit - $currentCount);
        $retryAfter = max(1, (int) $_SESSION[$bucketKey]['reset_time'] - $now);

        if (!headers_sent()) {
            header("X-RateLimit-Limit: {$limit}");
            header("X-RateLimit-Remaining: {$remaining}");
        }

        if ($currentCount > $limit) {
            if (!headers_sent()) {
                header("Retry-After: {$retryAfter}");
            }

            if ($isPost) {
                $_SESSION['flash_error'] = "Terlalu banyak pengiriman data dalam waktu singkat. Silakan tunggu {$retryAfter} detik sebelum mencoba lagi.";
                $referer = $_SERVER['HTTP_REFERER'] ?? App::baseUrl('/');
                header('Location: ' . $referer);
                exit;
            }

            http_response_code(429);
            $view = new View();
            $view->render('errors/500', [
                'title'      => '429 — Lalu Lintas Sedang Padat',
                'statusCode' => 429,
                'heading'    => 'Batas Lalu Lintas Permintaan Tercapai',
                'message'    => "Sistem membatasi frekuensi permintaan otomatis untuk menjaga kestabilan server. Silakan tunggu {$retryAfter} detik lalu muat ulang halaman."
            ], 'main');
            exit;
        }
    }

    /**
     * Mendaftarkan Global Error, Exception, dan Fatal Shutdown Handler
     * agar galat runtime atau lonjakan koneksi basis data dicatat ke log dan
     * ditampilkan dengan halaman terkelola (bukan layar putih 500 Internal Server Error).
     */
    public static function registerErrorHandlers(): void
    {
        set_exception_handler(function (Throwable $e): void {
            self::logError('UNCAUGHT_EXCEPTION', $e->getMessage(), $e->getFile(), $e->getLine());
            self::renderGracefulError($e);
        });

        set_error_handler(function (int $severity, string $message, string $file, int $line): bool {
            if (!(error_reporting() & $severity)) {
                return false;
            }

            // Catat warning/error penting ke log tanpa menghentikan eksekusi untuk non-fatal error
            if (in_array($severity, [E_USER_ERROR, E_RECOVERABLE_ERROR], true)) {
                self::logError('RUNTIME_ERROR', $message, $file, $line);
                self::renderGracefulError(new \ErrorException($message, 0, $severity, $file, $line));
                return true;
            }

            self::logError('PHP_NOTICE_OR_WARNING', $message, $file, $line);
            return true;
        });

        register_shutdown_function(function (): void {
            $error = error_get_last();
            if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
                self::logError('FATAL_SHUTDOWN', $error['message'], $error['file'], $error['line']);
                self::renderGracefulError(
                    new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line'])
                );
            }
        });
    }

    /**
     * Mencatat detail error internal ke `storage/logs/app-error.log` dengan rotasi otomatis (maks 2 MB).
     */
    public static function logError(string $level, string $message, string $file = '', int $line = 0): void
    {
        try {
            $logDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs';
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0775, true);
            }
            $htaccess = $logDir . DIRECTORY_SEPARATOR . '.htaccess';
            if (!file_exists($htaccess)) {
                @file_put_contents($htaccess, "Options -Indexes\n<IfModule mod_authz_core.c>\n    Require all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\n    Order Deny,Allow\n    Deny from all\n</IfModule>\n");
            }

            $logFile = $logDir . DIRECTORY_SEPARATOR . 'app-error.log';
            if (file_exists($logFile) && filesize($logFile) > 2097152) {
                @rename($logFile, $logFile . '.old');
            }

            $ip = self::getClientIp();
            $uri = $_SERVER['REQUEST_URI'] ?? '-';
            $entry = sprintf(
                "[%s] [%s] [IP:%s] [URI:%s] %s in %s:%d%s",
                date('Y-m-d H:i:s'),
                $level,
                $ip,
                $uri,
                $message,
                $file,
                $line,
                PHP_EOL
            );
            @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
        } catch (Throwable) {
            // Fail-safe: jangan pernah melemparkan exception dari dalam logger
        }
    }

    /**
     * Menampilkan respon error terkelola tanpa membocorkan struktur sensitif server di produksi.
     */
    private static function renderGracefulError(Throwable $e): void
    {
        while (ob_get_level() > 0) {
            @ob_end_clean();
        }

        if (!headers_sent()) {
            http_response_code(503);
        }

        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        if (str_contains($accept, 'application/json') || (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest')) {
            if (!headers_sent()) {
                header('Content-Type: application/json; charset=utf-8');
            }
            echo json_encode([
                'status'  => 'error',
                'message' => 'Permintaan sedang tidak dapat diproses oleh server. Silakan coba beberapa saat lagi.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        try {
            $view = new View();
            $view->render('errors/500', [
                'title'       => 'Layanan Sedang Menyesuaikan Lalu Lintas — K\'mplang Salatiga',
                'statusCode'  => 503,
                'heading'     => 'Layanan Sedang Menyesuaikan Lalu Lintas',
                'message'     => 'Sistem mendeteksi gangguan sementara pada sumber daya atau koneksi jaringan. Silakan muat ulang halaman beberapa saat lagi.',
                'debugDetail' => $e->getMessage() . ' (' . basename($e->getFile()) . ':' . $e->getLine() . ')'
            ], 'main');
        } catch (Throwable) {
            echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Layanan Sedang Menyesuaikan Lalu Lintas</title></head><body style="font-family:sans-serif;background:#0f172a;color:#f8fafc;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;padding:20px;text-align:center;"><div><h1 style="color:#f59e0b;margin-bottom:8px;">503</h1><h2 style="margin-top:0;">Layanan Sedang Menyesuaikan Lalu Lintas</h2><p style="color:#cbd5e1;max-width:420px;">Silakan muat ulang halaman beberapa saat lagi.</p></div></body></html>';
        }
        exit;
    }

    /**
     * Mengonversi notasi ukuran php.ini (misal '8M', '50M', '1G') ke dalam satuan byte.
     */
    private static function parseIniBytes(string $val): int
    {
        $val = trim($val);
        if ($val === '') {
            return 0;
        }
        $last = strtolower($val[strlen($val) - 1]);
        $num = (int) $val;
        return match ($last) {
            'g' => $num * 1073741824,
            'm' => $num * 1048576,
            'k' => $num * 1024,
            default => $num,
        };
    }

    /**
     * Menghasilkan atau mengambil token CSRF aktif pada sesi pengguna.
     */
    public static function generateCsrfToken(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    /**
     * Memverifikasi kecocokan token CSRF menggunakan `hash_equals` (tahan Timing Attack).
     */
    public static function verifyCsrfToken(?string $token): bool
    {
        if (empty($_SESSION['_csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['_csrf_token'], $token);
    }

    /**
     * Memeriksa apakah pengguna sedang dalam masa penahanan (lockout) akibat gagal login beruntun.
     * Mengembalikan sisa detik penahanan (0 jika diizinkan mencoba login).
     */
    public static function getLoginLockoutRemaining(): int
    {
        $lockoutUntil = (int) ($_SESSION['_login_lockout_until'] ?? 0);
        if ($lockoutUntil > time()) {
            return $lockoutUntil - time();
        }

        // Jika masa lockout sudah lewat, bersihkan penghitung
        if ($lockoutUntil > 0 && $lockoutUntil <= time()) {
            self::clearLoginAttempts();
        }

        return 0;
    }

    /**
     * Mencatat kegagalan autentikasi dan mengaktifkan penguncian jika melewati ambang batas.
     */
    public static function recordFailedLogin(): void
    {
        $attempts = (int) ($_SESSION['_login_attempts'] ?? 0) + 1;
        $_SESSION['_login_attempts'] = $attempts;

        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            $_SESSION['_login_lockout_until'] = time() + self::LOCKOUT_SECONDS;
        }
    }

    /**
     * Mereset penghitung gagal login setelah autentikasi berhasil.
     */
    public static function clearLoginAttempts(): void
    {
        unset($_SESSION['_login_attempts'], $_SESSION['_login_lockout_until']);
    }
}
