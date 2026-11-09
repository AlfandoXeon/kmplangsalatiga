<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\AuditLog;
use App\Helpers\Encryption;
use App\Helpers\Sanitizer;

/**
 * ============================================================================
 * Class MemberController - Direktori Anggota Publik & Pengelolaan Profil Mandiri
 * ============================================================================
 * Menangani:
 * 1. Halaman Direktori Anggota (`/anggota`) dengan pencarian dan filter angkatan.
 * 2. Halaman Profil Saya (`/profil`) bagi anggota yang sudah login.
 * 3. Pembaruan data profil mandiri (`/profil/update`) termasuk enkripsi ulang
 *    nomor WhatsApp dan pergantian kata sandi opsional.
 * ============================================================================
 */
class MemberController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Menampilkan direktori anggota resmi yang berstatus 'active'.
     */
    public function index(): void
    {
        $search = Sanitizer::cleanPlainText($_GET['q'] ?? '');
        $angkatanFilter = Sanitizer::cleanPlainText($_GET['angkatan'] ?? '');

        $members = $this->userModel->getActiveDirectory($search, $angkatanFilter);
        $angkatanList = $this->userModel->getDistinctAngkatan();

        $this->view('members/index', [
            'title'          => 'Direktori Anggota Resmi — K\'mplang Salatiga',
            'members'        => $members,
            'search'         => $search,
            'angkatanFilter' => $angkatanFilter,
            'angkatanList'   => $angkatanList
        ], 'main');
    }

    /**
     * Menampilkan halaman profil pengguna yang sedang login.
     */
    public function profile(): void
    {
        $userId = $this->userId();
        if (!$userId) {
            $this->redirect('/login');
        }

        $user = $this->userModel->findDecrypted($userId);
        if (!$user) {
            $this->redirect('/logout');
        }

        $this->view('members/profile', [
            'title' => 'Profil Anggota — K\'mplang Salatiga',
            'user'  => $user
        ], 'main');
    }

    /**
     * Memproses pembaruan profil mandiri oleh anggota yang sedang login.
     */
    public function updateProfile(): void
    {
        $userId = $this->userId();
        if (!$userId) {
            $this->redirect('/login');
        }

        $data = Sanitizer::cleanInput($_POST);
        $rules = [
            'nama_lengkap'  => 'required|min:3|max:150|no_script',
            'asal_daerah'   => 'required|min:3|max:150|no_script',
            'whatsapp'      => 'phone',
            'nim'           => 'nim',
            'fakultas'      => 'max:150|no_script',
            'angkatan'      => 'year',
            'tanggal_lahir' => 'date',
            'motivasi'      => 'max:1000|no_script'
        ];

        $errors = $this->validate($data, $rules);
        if (!empty($errors)) {
            $this->setFlash('error', reset($errors));
            $this->redirect('/profil');
        }

        $updatePayload = [
            'nama_lengkap' => Sanitizer::cleanPlainText($data['nama_lengkap']),
            'nim'          => !empty($data['nim']) ? Sanitizer::cleanPlainText($data['nim']) : null,
            'fakultas'     => !empty($data['fakultas']) ? Sanitizer::cleanPlainText($data['fakultas']) : null,
            'angkatan'     => !empty($data['angkatan']) ? Sanitizer::cleanPlainText($data['angkatan']) : null,
            'asal_daerah'  => Sanitizer::cleanPlainText($data['asal_daerah']),
            'tanggal_lahir'=> !empty($data['tanggal_lahir']) ? $data['tanggal_lahir'] : null,
            'motivasi'     => !empty($data['motivasi']) ? Sanitizer::cleanPlainText($data['motivasi']) : null
        ];

        // Jika nomor WhatsApp diisi, enkripsi ulang menggunakan AES-256-CBC
        if (!empty($data['whatsapp'])) {
            $updatePayload['no_whatsapp_encrypted'] = Encryption::encrypt($data['whatsapp']);
        }

        // Jika pengguna mengisi kata sandi baru, validasi minimal 6 karakter dan hash dengan Bcrypt
        if (!empty($_POST['new_password'])) {
            $newPass = $_POST['new_password'];
            if (mb_strlen($newPass) < 6) {
                $this->setFlash('error', 'Kata sandi baru minimal harus terdiri dari 6 karakter.');
                $this->redirect('/profil');
            }
            $updatePayload['password_hash'] = password_hash($newPass, PASSWORD_BCRYPT, ['cost' => 12]);
        }

        $this->userModel->update($userId, $updatePayload);

        // Sinkronisasi sesi aktif agar nama/fakultas di navbar langsung terbarui
        $_SESSION['user']['nama_lengkap'] = $updatePayload['nama_lengkap'];
        $_SESSION['user']['nim'] = $updatePayload['nim'];
        $_SESSION['user']['fakultas'] = $updatePayload['fakultas'];
        $_SESSION['user']['angkatan'] = $updatePayload['angkatan'];

        AuditLog::record($userId, 'UPDATE_PROFILE', 'users', $userId, 'User updated their profile');

        $this->setFlash('success', 'Profil Anda berhasil diperbarui.');
        $this->redirect('/profil');
    }
}
