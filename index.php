<?php

declare(strict_types=1);

/**
 * ============================================================================
 * Website Resmi K'mplang Salatiga (PHP OOP, MVC & MySQL)
 * ============================================================================
 * Root Web Entry Point
 * 
 * Berkas ini bertindak sebagai pintu masuk utama (Front Controller Entry Point)
 * bagi seluruh request web yang masuk ke root direktori proyek.
 * 
 * Berkas ini secara cerdas:
 * 1. Menangani static assets jika dijalankan via PHP Built-in Server (php -S).
 * 2. Meneruskan request dinamis ke Front Controller di direktori `public/index.php`.
 * ============================================================================
 */

// Tangani static assets jika dijalankan melalui PHP built-in CLI server (e.g. php -S localhost:8000)
if (php_sapi_name() === 'cli-server') {
    $rawUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $cleanUri = rawurldecode($rawUri);

    // Jika meminta aset dari /assets/..., layani langsung dari public/assets/...
    if (str_starts_with($cleanUri, '/assets/')) {
        $assetPath = __DIR__ . '/public' . $cleanUri;
        if (is_file($assetPath)) {
            $ext = strtolower(pathinfo($assetPath, PATHINFO_EXTENSION));
            $contentTypes = [
                'css'   => 'text/css; charset=utf-8',
                'js'    => 'application/javascript; charset=utf-8',
                'png'   => 'image/png',
                'jpg'   => 'image/jpeg',
                'jpeg'  => 'image/jpeg',
                'webp'  => 'image/webp',
                'gif'   => 'image/gif',
                'svg'   => 'image/svg+xml',
                'ico'   => 'image/x-icon',
                'mp3'   => 'audio/mpeg',
                'mp4'   => 'video/mp4',
                'webm'  => 'video/webm',
                'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'pdf'   => 'application/pdf',
                'woff2' => 'font/woff2',
                'woff'  => 'font/woff',
                'ttf'   => 'font/ttf',
            ];

            if (isset($contentTypes[$ext])) {
                header('Content-Type: ' . $contentTypes[$ext]);
            } else {
                header('Content-Type: ' . (mime_content_type($assetPath) ?: 'application/octet-stream'));
            }
            header('Content-Length: ' . filesize($assetPath));
            readfile($assetPath);
            exit;
        }
    }

    // Jika berkas statis ada di direktori root atau public
    if ($cleanUri !== '/' && is_file(__DIR__ . $cleanUri)) {
        return false;
    }
}

// Teruskan eksekusi ke Front Controller resmi di folder public
require_once __DIR__ . '/public/index.php';
