<?php

namespace App\Core;

use App\Config\App;
use App\Config\Security;

/**
 * ============================================================================
 * Pipeline Middleware Keamanan & Otorisasi (RBAC)
 * ============================================================================
 * Berisi kontrak dan implementasi penjaga rute (Route Guards):
 * 1. AuthMiddleware  : Memastikan pengguna telah login.
 * 2. AdminMiddleware : Memastikan pengguna memiliki role 'admin' atau 'pengurus'.
 * 3. CsrfMiddleware  : Memvalidasi token anti-CSRF pada setiap request POST.
 * 4. GuestMiddleware : Mencegah pengguna yang sudah login membuka halaman login/register.
 * ============================================================================
 */
interface MiddlewareInterface
{
    public function handle(): bool;
}

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        if (empty($_SESSION['user'])) {
            $_SESSION['flash_error'] = 'Silakan masuk terlebih dahulu untuk mengakses halaman ini.';
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
            $_SESSION['flash_error'] = 'Silakan masuk dengan akun pengurus/admin terlebih dahulu.';
            header('Location: ' . App::baseUrl('/login'));
            exit;
        }

        $role = $_SESSION['user']['role'] ?? '';
        if ($role !== 'admin' && $role !== 'pengurus') {
            http_response_code(403);
            $view = new View();
            $view->render('errors/403', ['title' => '403 — Akses Ditolak'], 'main');
            exit;
        }

        return true;
    }
}

class CsrfMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            if (!Security::verifyCsrfToken($token)) {
                http_response_code(403);
                $_SESSION['flash_error'] = 'Sesi formulir Anda telah kedaluwarsa atau token keamanan tidak valid. Silakan coba kembali.';
                $referer = $_SERVER['HTTP_REFERER'] ?? App::baseUrl('/');
                header('Location: ' . $referer);
                exit;
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
