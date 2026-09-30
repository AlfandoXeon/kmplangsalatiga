<?php

// Front Controller - K'mplang Salatiga
declare(strict_types=1);

// Autoloader for App\ namespace
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

// Require core multi-class files
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

// Load .env
App::loadEnv(__DIR__ . '/../.env');

// Set Error Reporting
if (App::get('APP_DEBUG', 'true') === 'true') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// Set Timezone
date_default_timezone_set('Asia/Jakarta');

// Initialize Secure Session & Headers
Security::initSession();
Security::setSecurityHeaders();

// Routing Engine
$router = new Router();

// ==========================================
// 1. PUBLIC ROUTES
// ==========================================
$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/program-kerja', [\App\Controllers\ProgramController::class, 'index']);
$router->get('/kegiatan', [\App\Controllers\ActivityController::class, 'index']);
$router->get('/kegiatan/{slug}', [\App\Controllers\ActivityController::class, 'detail']);
$router->get('/artikel', [\App\Controllers\ArticleController::class, 'index']);
$router->get('/artikel/{slug}', [\App\Controllers\ArticleController::class, 'detail']);
$router->get('/anggota', [\App\Controllers\MemberController::class, 'index']);

// Download routes (single file & Google Drive album ZIP)
$router->get('/download/file/{id}', [\App\Controllers\DownloadController::class, 'downloadFile']);
$router->get('/download/album/{id}', [\App\Controllers\DownloadController::class, 'downloadAlbum']);

// ==========================================
// 2. AUTHENTICATION ROUTES
// ==========================================
$router->get('/login', [\App\Controllers\AuthController::class, 'showLogin'], [GuestMiddleware::class]);
$router->post('/login', [\App\Controllers\AuthController::class, 'login'], [GuestMiddleware::class, CsrfMiddleware::class]);
$router->get('/register', [\App\Controllers\AuthController::class, 'showRegister'], [GuestMiddleware::class]);
$router->post('/register', [\App\Controllers\AuthController::class, 'register'], [GuestMiddleware::class, CsrfMiddleware::class]);
$router->get('/logout', [\App\Controllers\AuthController::class, 'logout']);

// ==========================================
// 3. MEMBER AUTHENTICATED ROUTES
// ==========================================
$router->get('/profil', [\App\Controllers\MemberController::class, 'profile'], [AuthMiddleware::class]);
$router->post('/komentar', [\App\Controllers\CommentController::class, 'store'], [AuthMiddleware::class, CsrfMiddleware::class]);

// ==========================================
// 4. ADMIN & PENGURUS ROUTES
// ==========================================
$router->get('/admin/dashboard', [\App\Controllers\Admin\DashboardController::class, 'index'], [AdminMiddleware::class]);
$router->get('/admin/settings', [\App\Controllers\Admin\SettingController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/settings', [\App\Controllers\Admin\SettingController::class, 'update'], [AdminMiddleware::class, CsrfMiddleware::class]);

// Admin Member Management
$router->get('/admin/members', [\App\Controllers\Admin\MemberAdminController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/members/status/{id}', [\App\Controllers\Admin\MemberAdminController::class, 'updateStatus'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/members/role/{id}', [\App\Controllers\Admin\MemberAdminController::class, 'updateRole'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/members/delete/{id}', [\App\Controllers\Admin\MemberAdminController::class, 'delete'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/members/export', [\App\Controllers\Admin\MemberAdminController::class, 'exportCsv'], [AdminMiddleware::class]);

// Admin Proker Management
$router->get('/admin/proker', [\App\Controllers\Admin\ProkerAdminController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/proker', [\App\Controllers\Admin\ProkerAdminController::class, 'store'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/proker/update/{id}', [\App\Controllers\Admin\ProkerAdminController::class, 'update'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/proker/delete/{id}', [\App\Controllers\Admin\ProkerAdminController::class, 'delete'], [AdminMiddleware::class, CsrfMiddleware::class]);

// Admin Activity & Album Management (up to 50MB uploads)
$router->get('/admin/activities', [\App\Controllers\Admin\ActivityAdminController::class, 'index'], [AdminMiddleware::class]);
$router->get('/admin/activities/create', [\App\Controllers\Admin\ActivityAdminController::class, 'create'], [AdminMiddleware::class]);
$router->post('/admin/activities', [\App\Controllers\Admin\ActivityAdminController::class, 'store'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/activities/edit/{id}', [\App\Controllers\Admin\ActivityAdminController::class, 'edit'], [AdminMiddleware::class]);
$router->post('/admin/activities/update/{id}', [\App\Controllers\Admin\ActivityAdminController::class, 'update'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/activities/media/delete/{id}', [\App\Controllers\Admin\ActivityAdminController::class, 'deleteMedia'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/activities/delete/{id}', [\App\Controllers\Admin\ActivityAdminController::class, 'delete'], [AdminMiddleware::class, CsrfMiddleware::class]);

// Admin Article Management
$router->get('/admin/articles', [\App\Controllers\Admin\ArticleAdminController::class, 'index'], [AdminMiddleware::class]);
$router->get('/admin/articles/create', [\App\Controllers\Admin\ArticleAdminController::class, 'create'], [AdminMiddleware::class]);
$router->post('/admin/articles', [\App\Controllers\Admin\ArticleAdminController::class, 'store'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/articles/edit/{id}', [\App\Controllers\Admin\ArticleAdminController::class, 'edit'], [AdminMiddleware::class]);
$router->post('/admin/articles/update/{id}', [\App\Controllers\Admin\ArticleAdminController::class, 'update'], [AdminMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/articles/delete/{id}', [\App\Controllers\Admin\ArticleAdminController::class, 'delete'], [AdminMiddleware::class, CsrfMiddleware::class]);

// Run Application
$router->dispatch();
