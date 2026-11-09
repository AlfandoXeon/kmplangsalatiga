<?php

/**
 * ============================================================================
 * Front Controller Utama - Website Resmi K'mplang Salatiga
 * ============================================================================
 * Alur Inisialisasi Aplikasi:
 * 1. Mendaftarkan PSR-4 style Autoloader untuk namespace `App\`.
 * 2. Memuat konfigurasi `.env`, mengatur pelaporan error, dan zona waktu WIB.
 * 3. Menginisialisasi sesi aman (HttpOnly, SameSite) dan Security HTTP Headers.
 * 4. Mendaftarkan seluruh rute Publik, Autentikasi, Anggota, dan Admin beserta
 *    pipeline Middleware masing-masing.
 * 5. Menjalankan `Router::dispatch()` untuk memproses request masuk.
 * ============================================================================
 */
declare(strict_types=1);

// 1. Autoloader untuk namespace App\ (memetakan App\Folder\Class -> app/Folder/Class.php)
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// 2. Muat berkas inti multi-class dan helper fungsi global
require_once __DIR__ . '/../app/Core/Middleware.php';
require_once __DIR__ . '/../app/Helpers/Sanitizer.php';
require_once __DIR__ . '/../app/Helpers/helpers.php';

use App\Config\App;
use App\Config\Security;
use App\Core\Router;
use App\Core\AuthMiddleware;
use App\Core\AdminMiddleware;
use App\Core\CsrfMiddleware;
use App\Core\GuestMiddleware;

// 3. Muat variabel konfigurasi dari berkas .env
App::loadEnv(__DIR__ . '/../.env');

// 4. Konfigurasi pelaporan error sesuai environment
if (App::get('APP_DEBUG', 'true') === 'true') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// 5. Setel zona waktu standar Indonesia Barat (WIB)
date_default_timezone_set('Asia/Jakarta');

// 6. Daftarkan Global Error & Exception Handler (Anti-500 Internal Server Error)
Security::registerErrorHandlers();

// 7. Tangani CORS, Preflight Request, Sesi Sliding 1 Bulan, & Penjaga Lalu Lintas Jaringan
Security::handleCorsAndPreflight();
Security::initSession();
Security::setSecurityHeaders();
Security::validateRequestPayload();
Security::checkRateLimit();

// 8. Instansiasi Mesin Routing
$router = new Router();

// ============================================================================
// A. RUTE PUBLIK & NETWORK KEEP-ALIVE (Dapat diakses oleh Tamu maupun Anggota)
// ============================================================================
$router->get('/ping', function (): void {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    echo json_encode([
        'status'        => 'ok',
        'authenticated' => !empty($_SESSION['user']),
        'session_ttl'   => (int) App::get('SESSION_LIFETIME', Security::ONE_MONTH_SECONDS),
        'timestamp'     => time()
    ], JSON_UNESCAPED_UNICODE);
    exit;
});

$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/program-kerja', [\App\Controllers\ProgramController::class, 'index']);
$router->get('/kegiatan', [\App\Controllers\ActivityController::class, 'index']);
$router->get('/kegiatan/{slug}', [\App\Controllers\ActivityController::class, 'detail']);
$router->get('/artikel', [\App\Controllers\ArticleController::class, 'index']);
$router->get('/artikel/{slug}', [\App\Controllers\ArticleController::class, 'detail']);
$router->get('/anggota', [\App\Controllers\MemberController::class, 'index']);

// Unduhan berkas satuan & unduhan seluruh album berformat ZIP
$router->get('/download/file/{id}', [\App\Controllers\DownloadController::class, 'downloadFile']);
$router->get('/download/album/{id}', [\App\Controllers\DownloadController::class, 'downloadAlbum']);

// ============================================================================
// B. RUTE AUTENTIKASI (Login, Registrasi, Logout)
// ============================================================================
$router->get('/login', [\App\Controllers\AuthController::class, 'showLogin'], [GuestMiddleware::class]);
$router->post('/login', [\App\Controllers\AuthController::class, 'login'], [GuestMiddleware::class, CsrfMiddleware::class]);
$router->get('/register', [\App\Controllers\AuthController::class, 'showRegister'], [GuestMiddleware::class]);
$router->post('/register', [\App\Controllers\AuthController::class, 'register'], [GuestMiddleware::class, CsrfMiddleware::class]);
$router->get('/logout', [\App\Controllers\AuthController::class, 'logout']);

// ============================================================================
// C. RUTE ANGGOTA TERAUTENTIKASI (Profil & Diskusi Komentar)
// ============================================================================
$router->get('/profil', [\App\Controllers\MemberController::class, 'profile'], [AuthMiddleware::class]);
$router->post('/profil/update', [\App\Controllers\MemberController::class, 'updateProfile'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/komentar', [\App\Controllers\CommentController::class, 'store'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/komentar/delete/{id}', [\App\Controllers\CommentController::class, 'delete'], [AuthMiddleware::class, CsrfMiddleware::class]);

// ============================================================================
// D. RUTE PANEL ADMIN & PENGURUS (RBAC Protected)
// ============================================================================
$router->get('/admin/dashboard', [\App\Controllers\Admin\DashboardController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/announcements', [\App\Controllers\Admin\DashboardController::class, 'storeAnnouncement'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/announcements/toggle/{id}', [\App\Controllers\Admin\DashboardController::class, 'toggleAnnouncement'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/announcements/delete/{id}', [\App\Controllers\Admin\DashboardController::class, 'deleteAnnouncement'], [AdminMiddleware::class, CsrfMiddleware::class]);

$router->get('/admin/settings', [\App\Controllers\Admin\SettingController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/settings', [\App\Controllers\Admin\SettingController::class, 'update'], [AdminMiddleware::class, CsrfMiddleware::class]);

// Manajemen & Verifikasi Anggota
$router->get('/admin/members', [\App\Controllers\Admin\MemberAdminController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/members/status/{id}', [\App\Controllers\Admin\MemberAdminController::class, 'updateStatus'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/members/role/{id}', [\App\Controllers\Admin\MemberAdminController::class, 'updateRole'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/members/delete/{id}', [\App\Controllers\Admin\MemberAdminController::class, 'delete'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/members/export', [\App\Controllers\Admin\MemberAdminController::class, 'exportCsv'], [AdminMiddleware::class]);

// Manajemen Program Kerja (Create, Update, Delete)
$router->get('/admin/proker', [\App\Controllers\Admin\ProkerAdminController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/proker', [\App\Controllers\Admin\ProkerAdminController::class, 'store'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/proker/update/{id}', [\App\Controllers\Admin\ProkerAdminController::class, 'update'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/proker/delete/{id}', [\App\Controllers\Admin\ProkerAdminController::class, 'delete'], [AdminMiddleware::class, CsrfMiddleware::class]);

// Manajemen Kegiatan & Album Dokumentasi
$router->get('/admin/activities', [\App\Controllers\Admin\ActivityAdminController::class, 'index'], [AdminMiddleware::class]);
$router->get('/admin/activities/create', [\App\Controllers\Admin\ActivityAdminController::class, 'create'], [AdminMiddleware::class]);
$router->post('/admin/activities', [\App\Controllers\Admin\ActivityAdminController::class, 'store'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/activities/edit/{id}', [\App\Controllers\Admin\ActivityAdminController::class, 'edit'], [AdminMiddleware::class]);
$router->post('/admin/activities/update/{id}', [\App\Controllers\Admin\ActivityAdminController::class, 'update'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/activities/media/delete/{id}', [\App\Controllers\Admin\ActivityAdminController::class, 'deleteMedia'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/activities/delete/{id}', [\App\Controllers\Admin\ActivityAdminController::class, 'delete'], [AdminMiddleware::class, CsrfMiddleware::class]);

// Manajemen Artikel & Wawasan Budaya
$router->get('/admin/articles', [\App\Controllers\Admin\ArticleAdminController::class, 'index'], [AdminMiddleware::class]);
$router->get('/admin/articles/create', [\App\Controllers\Admin\ArticleAdminController::class, 'create'], [AdminMiddleware::class]);
$router->post('/admin/articles', [\App\Controllers\Admin\ArticleAdminController::class, 'store'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/articles/edit/{id}', [\App\Controllers\Admin\ArticleAdminController::class, 'edit'], [AdminMiddleware::class]);
$router->post('/admin/articles/update/{id}', [\App\Controllers\Admin\ArticleAdminController::class, 'update'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/articles/delete/{id}', [\App\Controllers\Admin\ArticleAdminController::class, 'delete'], [AdminMiddleware::class, CsrfMiddleware::class]);

// 8. Eksekusi Dispatcher
$router->dispatch();
