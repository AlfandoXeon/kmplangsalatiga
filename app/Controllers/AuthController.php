<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Config\Security;
use App\Models\User;
use App\Models\AuditLog;
use App\Helpers\Sanitizer;

/**
 * ============================================================================
 * Class AuthController - Pengendali Autentikasi & Registrasi Anggota
 * ============================================================================
 * Menangani:
 * 1. Proses Login via Email atau NIM dengan proteksi Anti Brute-Force (Lockout),
 *    verifikasi hash Bcrypt, regenerasi ID sesi, dan pencatatan `last_login_at`.
 * 2. Proses Registrasi Anggota Baru langsung ke basis data dengan enkripsi
 *    AES-256 pada nomor WhatsApp.
 * 3. Proses Logout aman dengan penghancuran sesi dan pencatatan Audit Trail.
 * ============================================================================
 */
class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function showLogin(): void
    {
        $this->view('auth/login', [
            'title' => 'Masuk Akun — K\'mplang Salatiga'
        ], 'auth');
    }

    public function login(): void
    {
        // 1. Cek apakah pengguna sedang terkena penahanan sementara (Brute-Force Lockout)
        $lockoutRemaining = Security::getLoginLockoutRemaining();
        if ($lockoutRemaining > 0) {
            $this->setFlash('error', "Terlalu banyak percobaan gagal. Silakan tunggu {$lockoutRemaining} detik sebelum mencoba kembali.");
            $this->redirect('/login');
        }

        $identifier = trim($_POST['identifier'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($identifier === '' || $password === '') {
            $this->setFlash('error', 'Silakan masukkan Email/NIM dan kata sandi Anda.');
            $this->redirect('/login');
        }

        // 2. Cari akun berdasarkan Email atau NIM
        $user = $this->userModel->findByEmailOrNim($identifier);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            Security::recordFailedLogin();
            AuditLog::record(null, 'LOGIN_FAILED', 'users', null, "Failed login attempt for identifier: {$identifier}");
            $this->setFlash('error', 'Kombinasi Email/NIM dan kata sandi tidak sesuai.');
            $this->redirect('/login');
        }

        if ($user['status'] === 'rejected') {
            $this->setFlash('error', 'Mohon maaf, status pengajuan akun Anda tidak disetujui oleh pengurus.');
            $this->redirect('/login');
        }

        // 3. Autentikasi berhasil: bersihkan penghitung gagal & regenerasi ID sesi (Session Fixation Protection)
        Security::clearLoginAttempts();
        session_regenerate_id(true);
        $_SESSION['_created_time'] = time();
        $_SESSION['_last_activity'] = time();
        $_SESSION['_cookie_refreshed_at'] = time();
        Security::refreshSessionCookie();

        // 4. Catat waktu login terakhir ke database
        $this->userModel->recordLastLogin((int) $user['id']);

        // 5. Simpan identitas esensial pengguna ke dalam sesi
        $_SESSION['user'] = [
            'id'                 => (int) $user['id'],
            'nama_lengkap'       => $user['nama_lengkap'],
            'nim'                => $user['nim'],
            'email'              => $user['email'],
            'role'               => $user['role'],
            'jabatan_organisasi' => $user['jabatan_organisasi'] ?? 'Anggota',
            'fakultas'           => $user['fakultas'],
            'angkatan'           => $user['angkatan'] ?? null,
            'status'             => $user['status']
        ];

        AuditLog::record((int) $user['id'], 'LOGIN', 'users', (int) $user['id'], 'User logged in successfully');

        $this->setFlash('success', 'Tabik Pun! Selamat datang kembali, ' . $user['nama_lengkap'] . '.');

        $targetUrl = ($user['role'] === 'admin' || $user['role'] === 'pengurus')
            ? '/admin/dashboard'
            : '/';

        $this->redirect($targetUrl);
    }

    public function showRegister(): void
    {
        $this->view('auth/register', [
            'title'  => 'Pendaftaran Anggota — K\'mplang Salatiga',
            'old'    => [],
            'errors' => []
        ], 'auth');
    }

    public function register(): void
    {
        $data = Sanitizer::cleanInput($_POST);

        $rules = [
            'nama_lengkap'  => 'required|min:3|max:150|no_script',
            'email'         => 'required|email|max:150',
            'password'      => 'required|min:6|max:100',
            'asal_daerah'   => 'required|min:3|max:150|no_script',
            'whatsapp'      => 'required|phone',
            'nim'           => 'nim',
            'fakultas'      => 'max:150|no_script',
            'angkatan'      => 'year',
            'tanggal_lahir' => 'date',
            'motivasi'      => 'max:1000|no_script'
        ];

        $errors = $this->validate($data, $rules);

        // Cek keunikan Email
        if (empty($errors['email'])) {
            $existing = $this->userModel->findBy('email', $data['email']);
            if ($existing) {
                $errors['email'] = 'Email ini sudah terdaftar. Silakan gunakan email lain atau masuk ke akun Anda.';
            }
        }

        // Cek keunikan NIM apabila diisi
        if (!empty($data['nim'])) {
            $existingNim = $this->userModel->findBy('nim', $data['nim']);
            if ($existingNim) {
                $errors['nim'] = 'NIM ini sudah terdaftar di dalam sistem.';
            }
        }

        if (!empty($errors)) {
            $this->setFlash('error', reset($errors));
            $this->view('auth/register', [
                'title'  => 'Pendaftaran Anggota — K\'mplang Salatiga',
                'old'    => $data,
                'errors' => $errors
            ], 'auth');
            return;
        }

        // Simpan data anggota baru (WhatsApp otomatis dienkripsi AES-256 di dalam User::register)
        $userId = $this->userModel->register([
            'nama_lengkap' => Sanitizer::cleanPlainText($data['nama_lengkap']),
            'nim'          => !empty($data['nim']) ? Sanitizer::cleanPlainText($data['nim']) : null,
            'email'        => strtolower(trim((string) $data['email'])),
            'password'     => $data['password'],
            'asal_daerah'  => Sanitizer::cleanPlainText($data['asal_daerah']),
            'fakultas'     => !empty($data['fakultas']) ? Sanitizer::cleanPlainText($data['fakultas']) : null,
            'angkatan'     => !empty($data['angkatan']) ? Sanitizer::cleanPlainText($data['angkatan']) : null,
            'whatsapp'     => Sanitizer::cleanPlainText($data['whatsapp']),
            'tanggal_lahir'=> !empty($data['tanggal_lahir']) ? $data['tanggal_lahir'] : null,
            'motivasi'     => !empty($data['motivasi']) ? Sanitizer::cleanPlainText($data['motivasi']) : null
        ]);

        AuditLog::record($userId, 'REGISTER', 'users', $userId, 'New member registered via web portal');

        $this->setFlash('success', 'Pendaftaran berhasil disimpan! Akun Anda sedang dalam antrean verifikasi pengurus dan sudah dapat digunakan untuk masuk.');
        $this->redirect('/login');
    }

    public function logout(): void
    {
        $userId = $this->userId();
        if ($userId) {
            AuditLog::record($userId, 'LOGOUT', 'users', $userId, 'User logged out');
        }

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();

        header('Location: ' . \App\Config\App::baseUrl('/login'));
        exit;
    }
}
