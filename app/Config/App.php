<?php

namespace App\Config;

class App
{
    private static array $env = [];

    public static function loadEnv(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            // Split into key and value
            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Strip quotes if present
                if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                    (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                    $value = substr($value, 1, -1);
                }

                // Strip trailing comments
                if (str_contains($value, ' #')) {
                    $value = trim(explode(' #', $value, 2)[0]);
                }

                self::$env[$key] = $value;
                putenv("$key=$value");
                $_ENV[$key] = $value;
            }
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$env[$key] ?? getenv($key) ?: $default;
    }

    public static function baseUrl(string $path = ''): string
    {
        $configured = self::get('APP_URL');
        if (!empty($configured) && $configured !== 'http://localhost:8000') {
            $base = rtrim($configured, '/');
        } else {
            // Auto detect base URL from current server request (e.g. Apache subfolder or localhost:8000)
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
            $scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
            $base = $scriptDir !== '' ? "$scheme://$host/$scriptDir" : "$scheme://$host";
        }

        $path = ltrim($path, '/');
        return $path === '' ? $base : "$base/$path";
    }

    /**
     * Resolves web URL for uploaded media or static assets.
     * Checks storage/uploads/{category}/, storage/uploads/, and public/assets/images/.
     */
    public static function mediaUrl(?string $path, string $category = 'activities', string $fallback = 'pelantikan1.jpg'): string
    {
        if (empty($path)) {
            return self::baseUrl('/assets/images/' . $fallback);
        }

        // Return untouched if absolute URL or data URL
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
            return $path;
        }

        $filename = ltrim(str_replace('\\', '/', $path), '/');

        // If path already explicitly starts with storage/ or assets/
        if (str_starts_with($filename, 'storage/') || str_starts_with($filename, 'assets/')) {
            return self::baseUrl($filename);
        }

        $root = dirname(__DIR__, 2);

        // 1. Check storage/uploads/{category}/{filename}
        if (!empty($category) && file_exists($root . '/storage/uploads/' . $category . '/' . $filename)) {
            return self::baseUrl('/storage/uploads/' . $category . '/' . $filename);
        }

        // 2. Check storage/uploads/{filename}
        if (file_exists($root . '/storage/uploads/' . $filename)) {
            return self::baseUrl('/storage/uploads/' . $filename);
        }

        // 3. Check public/assets/images/{filename}
        if (file_exists($root . '/public/assets/images/' . $filename)) {
            return self::baseUrl('/assets/images/' . $filename);
        }

        // 4. Default to category storage path if category is provided
        if (!empty($category)) {
            return self::baseUrl('/storage/uploads/' . $category . '/' . $filename);
        }

        return self::baseUrl('/assets/images/' . $filename);
    }
}
