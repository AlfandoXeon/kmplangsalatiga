<?php

namespace App\Core;

/**
 * ============================================================================
 * Class Router - Mesin Routing URL & Eksekutor Pipeline Middleware
 * ============================================================================
 * Cara Kerja:
 * 1. Mendaftarkan rute HTTP (`GET`, `POST`) beserta pola parameter dinamis
 *    seperti `/kegiatan/{slug}` yang dikonversi menjadi Named Regex Group.
 * 2. Saat `dispatch()` dipanggil:
 *    - Menormalisasi URI request dan menghapus prefix sub-folder Apache (`/Kmplang`).
 *    - Mencocokkan metode HTTP dan pola Regex rute.
 *    - Menjalankan seluruh Middleware yang terpasang pada rute tersebut secara berurutan.
 *    - Mengeksekusi method pada Controller tujuan dengan parameter URL yang diekstrak.
 * ============================================================================
 */
class Router
{
    /** @var array<int, array<string, mixed>> Daftar rute terdaftar */
    private array $routes = [];

    /**
     * Mendaftarkan rute untuk metode HTTP GET.
     */
    public function get(string $path, array|callable $handler, array $middlewares = []): void
    {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    /**
     * Mendaftarkan rute untuk metode HTTP POST.
     */
    public function post(string $path, array|callable $handler, array $middlewares = []): void
    {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }

    /**
     * Mengubah ekspresi `{param}` pada path menjadi pola Regex `(?P<param>[^/]+)`.
     */
    private function addRoute(string $method, string $path, array|callable $handler, array $middlewares): void
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', trim($path, '/'));
        $regex = '#^' . ($pattern === '' ? '' : $pattern) . '$#';

        $this->routes[] = [
            'method'      => $method,
            'path'        => trim($path, '/'),
            'regex'       => $regex,
            'handler'     => $handler,
            'middlewares' => $middlewares
        ];
    }

    /**
     * Mencocokkan request saat ini dengan tabel rute dan mengeksekusi handler yang sesuai.
     */
    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $rawUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        // Dekode karakter URL (misal %20) dan rapikan garis miring di ujung
        $uri = trim(rawurldecode($rawUri), '/');

        // Jika dijalankan di dalam sub-direktori Apache (contoh: /Kmplang), potong prefix sub-direktorinya (case-insensitive untuk Windows)
        $scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        if (($scriptDir === 'public' || str_ends_with($scriptDir, '/public')) && !str_starts_with(strtolower($uri), 'public/')) {
            $scriptDir = trim(substr($scriptDir, 0, -6), '/');
        }
        if ($scriptDir !== '' && str_starts_with(strtolower($uri), strtolower($scriptDir))) {
            $uri = trim(substr($uri, strlen($scriptDir)), '/');
        }

        // Perlakukan metode HEAD sama seperti GET untuk kompatibilitas pemeriksaan HTTP
        $checkMethod = ($requestMethod === 'HEAD') ? 'GET' : $requestMethod;

        foreach ($this->routes as $route) {
            if ($route['method'] !== $checkMethod) {
                continue;
            }

            if (preg_match($route['regex'], $uri, $matches)) {
                // 1. Jalankan pipeline Middleware sebelum masuk ke Controller
                foreach ($route['middlewares'] as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    if (!$middleware->handle()) {
                        return; // Eksekusi dihentikan oleh Middleware
                    }
                }

                // 2. Ambil hanya named parameters dari hasil tangkapan Regex
                $params = [];
                foreach ($matches as $key => $val) {
                    if (is_string($key)) {
                        $params[$key] = $val;
                    }
                }

                // 3. Instansiasi Controller dan panggil method target
                $handler = $route['handler'];
                if (is_array($handler)) {
                    [$controllerClass, $method] = $handler;
                    $controller = new $controllerClass();
                    call_user_func_array([$controller, $method], array_values($params));
                    return;
                }

                if (is_callable($handler)) {
                    call_user_func_array($handler, array_values($params));
                    return;
                }
            }
        }

        // Jika tidak ada rute yang cocok, tampilkan halaman 404 Not Found
        http_response_code(404);
        $view = new View();
        $view->render('errors/404', ['title' => '404 — Halaman Tidak Ditemukan'], 'main');
    }
}
