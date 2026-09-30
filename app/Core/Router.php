<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array|callable $handler, array $middlewares = []): void
    {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, array|callable $handler, array $middlewares = []): void
    {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }

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

    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $rawUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        // Normalize URI with rawurldecode so '%20' matches spaces in directory names
        $uri = trim(rawurldecode($rawUri), '/');

        // Remove base folder if running under subfolder in Apache (e.g. Website Kmplang/public)
        $scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        if ($scriptDir !== '' && str_starts_with($uri, $scriptDir)) {
            $uri = trim(substr($uri, strlen($scriptDir)), '/');
        }

        $checkMethod = ($requestMethod === 'HEAD') ? 'GET' : $requestMethod;

        foreach ($this->routes as $route) {
            if ($route['method'] !== $checkMethod) {
                continue;
            }

            if (preg_match($route['regex'], $uri, $matches)) {
                // Execute middlewares
                foreach ($route['middlewares'] as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    if (!$middleware->handle()) {
                        return; // Stopped by middleware
                    }
                }

                // Extract named params
                $params = [];
                foreach ($matches as $key => $val) {
                    if (is_string($key)) {
                        $params[$key] = $val;
                    }
                }

                // Execute handler
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

        // Route not found -> 404
        http_response_code(404);
        $view = new View();
        $view->render('errors/404', ['title' => 'Halaman Tidak Ditemukan'], 'main');
    }
}
