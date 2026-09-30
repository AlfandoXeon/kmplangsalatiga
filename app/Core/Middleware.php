<?php

namespace App\Core;

use App\Config\App;
use App\Config\Security;

interface MiddlewareInterface
{
    public function handle(): bool;
}

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        if (empty($_SESSION['user'])) {
            $_SESSION['flash_error'] = 'Silakan login terlebih dahulu untuk mengakses halaman ini.';
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '/';
            header('Location: ' . App::baseUrl('/login'));
            exit;
        }
        return true;
    }
}

class AdminMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        if (empty($_SESSION['user'])) {
            header('Location: ' . App::baseUrl('/login'));
            exit;
        }

        $role = $_SESSION['user']['role'] ?? '';
        if ($role !== 'admin' && $role !== 'pengurus') {
            http_response_code(403);
            $view = new View();
            $view->render('errors/403', ['title' => 'Akses Ditolak'], 'main');
            exit;
        }

        return true;
    }
}

class CsrfMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            if (!Security::verifyCsrfToken($token)) {
                http_response_code(403);
                die('Keamanan Sistem: Token CSRF tidak valid atau sesi Anda telah kedaluwarsa. Silakan muat ulang halaman.');
            }
        }
        return true;
    }
}

class GuestMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        if (!empty($_SESSION['user'])) {
            $role = $_SESSION['user']['role'] ?? 'anggota';
            $dest = ($role === 'admin' || $role === 'pengurus') ? '/admin/dashboard' : '/';
            header('Location: ' . App::baseUrl($dest));
            exit;
        }
        return true;
    }
}
