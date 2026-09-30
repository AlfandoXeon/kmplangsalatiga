<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\AuditLog;
use App\Helpers\Sanitizer;

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
            'title'     => 'Masuk - K\'mplang Salatiga',
            'enableAos' => false // Sub-page: NO AOS
        ], 'auth');
    }

    public function login(): void
    {
        $identifier = trim($_POST['identifier'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($identifier) || empty($password)) {
            $this->setFlash('error', 'Silakan masukkan Email/NIM dan kata sandi Anda.');
            $this->redirect('/login');
        }

        $user = $this->userModel->findByEmailOrNim($identifier);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->setFlash('error', 'Kombinasi Email/NIM dan kata sandi salah.');
            $this->redirect('/login');
        }

        if ($user['status'] === 'rejected') {
            $this->setFlash('error', 'Mohon maaf, status pendaftaran Anda ditolak oleh pengurus.');
            $this->redirect('/login');
        }

        // Regenerate session for session fixation protection
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id'           => $user['id'],
            'nama_lengkap' => $user['nama_lengkap'],
            'nim'          => $user['nim'],
            'email'        => $user['email'],
            'role'         => $user['role'],
            'fakultas'     => $user['fakultas'],
            'status'       => $user['status']
        ];

        AuditLog::record($user['id'], 'LOGIN', 'users', $user['id'], 'User logged in successfully');

        $this->setFlash('success', 'Selamat datang kembali, ' . $user['nama_lengkap'] . '!');

        $targetUrl = ($user['role'] === 'admin' || $user['role'] === 'pengurus')
            ? '/admin/dashboard'
            : '/';

        $this->redirect($targetUrl);
    }

    public function showRegister(): void
    {
        $this->view('auth/register', [
            'title'     => 'Daftar Anggota - K\'mplang Salatiga',
            'old'       => [],
            'errors'    => [],
            'enableAos' => false // Sub-page: NO AOS
        ], 'auth');
    }

    public function register(): void
    {
        $data = Sanitizer::cleanInput($_POST);

        $rules = [
            'nama_lengkap'  => 'required|min:3|max:150',
            'email'         => 'required|email|max:150',
            'password'      => 'required|min:6',
            'asal_daerah'   => 'required|min:3',
            'whatsapp'      => 'required|min:8|max:20'
        ];

        $errors = $this->validate($data, $rules);

        // Check if email already registered
        if (empty($errors['email'])) {
            $existing = $this->userModel->findBy('email', $data['email']);
            if ($existing) {
                $errors['email'] = 'Email ini sudah terdaftar. Silakan gunakan email lain atau login.';
            }
        }

        // Check if NIM already registered
        if (!empty($data['nim'])) {
            $existingNim = $this->userModel->findBy('nim', $data['nim']);
            if ($existingNim) {
                $errors['nim'] = 'NIM ini sudah terdaftar di sistem.';
            }
        }

        if (!empty($errors)) {
            $this->setFlash('error', reset($errors));
            $this->view('auth/register', [
                'title'     => 'Daftar Anggota - K\'mplang Salatiga',
                'old'       => $data,
                'errors'    => $errors,
                'enableAos' => false
            ], 'auth');
            return;
        }

        // Save directly to database without WhatsApp redirection!
        $userId = $this->userModel->register([
            'nama_lengkap'  => $data['nama_lengkap'],
            'nim'           => $data['nim'] ?? null,
            'email'         => $data['email'],
            'password'      => $data['password'],
            'asal_daerah'   => $data['asal_daerah'],
            'fakultas'      => $data['fakultas'] ?? null,
            'whatsapp'      => $data['whatsapp'],
            'tanggal_lahir' => !empty($data['tanggal_lahir']) ? $data['tanggal_lahir'] : null,
            'motivasi'      => $data['motivasi'] ?? null
        ]);

        AuditLog::record($userId, 'REGISTER', 'users', $userId, 'New member registered via web');

        $this->setFlash('success', 'Pendaftaran berhasil! Akun Anda telah tersimpan dan sedang menunggu verifikasi pengurus. Anda sudah dapat mencoba login.');
        $this->redirect('/login');
    }

    public function logout(): void
    {
        $userId = $this->userId();
        if ($userId) {
            AuditLog::record($userId, 'LOGOUT', 'users', $userId, 'User logged out');
        }

        unset($_SESSION['user']);
        session_destroy();

        header('Location: ' . \App\Config\App::baseUrl('/login'));
        exit;
    }
}
