<?php

namespace App\Config;

class Security
{
    public static function initSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $lifetime = (int) App::get('SESSION_LIFETIME', 7200);
            $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

            session_set_cookie_params([
                'lifetime' => $lifetime,
                'path' => '/',
                'domain' => '',
                'secure' => $secure,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            session_name('KMPLANG_SESSID');
            session_start();
        }

        // Regenerate session ID periodically to prevent fixation
        if (!isset($_SESSION['_created_time'])) {
            $_SESSION['_created_time'] = time();
        } elseif (time() - $_SESSION['_created_time'] > 1800) {
            session_regenerate_id(true);
            $_SESSION['_created_time'] = time();
        }
    }

    public static function setSecurityHeaders(): void
    {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }

    public static function generateCsrfToken(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    public static function verifyCsrfToken(?string $token): bool
    {
        if (empty($_SESSION['_csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['_csrf_token'], $token);
    }
}
